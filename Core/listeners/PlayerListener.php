<?php

namespace Zoumi\Core\listeners;

use pocketmine\block\Anvil;
use pocketmine\block\EnchantingTable;
use pocketmine\entity\Effect;
use pocketmine\entity\EffectInstance;
use pocketmine\event\entity\EntityArmorChangeEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerChatEvent;
use pocketmine\event\player\PlayerCommandPreprocessEvent;
use pocketmine\event\player\PlayerExhaustEvent;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerPreLoginEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\item\Item;
use pocketmine\item\ItemIds;
use pocketmine\network\mcpe\protocol\PlaySoundPacket;
use pocketmine\Player;
use pocketmine\Server;
use pocketmine\utils\BinaryStream;
use Zoumi\Core\api\Coins;
use Zoumi\Core\api\Scoreboard;
use Zoumi\Core\api\SkyBlock;
use Zoumi\Core\api\Users;
use Zoumi\Core\api\Webhook;
use Zoumi\Core\Main;
use Zoumi\Core\Manager;
use Zoumi\Core\tasks\events\Bingo;

class PlayerListener implements Listener {

    public function onPreLogin(PlayerPreLoginEvent $event){
        $player = $event->getPlayer();
        if (!Users::haveAccount($player->getName())){
            Users::createAccount($player);
        }
        if (Users::haveAccount($player->getName())) {
            if (SkyBlock::hasIsland($player->getName())) {
                if (!Server::getInstance()->isLevelLoaded(SkyBlock::getIslandName($player->getName()))) {
                    Server::getInstance()->loadLevel(SkyBlock::getIslandName($player->getName()));
                }
            }
        }
    }

    public function onJoin(PlayerJoinEvent $event){
        $player = $event->getPlayer();
        $event->setJoinMessage(null);
        $player->sendDataPacket(Main::getInstance()->craftCache);
        if (!$player->hasPlayedBefore()){
            Server::getInstance()->broadcastMessage("§e" . $player->getName() . " §fvient de nous rejoindre pour la première fois. Souhaitez lui la bienvenue !");
        }
        $sound = new PlaySoundPacket();
        $sound->soundName = "mob.guardian.attack_loop";
        $sound->pitch = 1;
        $sound->volume = 1;
        $sound->x = $player->getX();
        $sound->y = $player->getY();
        $sound->z = $player->getZ();
        $player->sendDataPacket($sound);
        /* Scoreboard */
        $scoreboard = Main::getInstance()->scoreboard[$player->getName()] = new Scoreboard($player);
        $scoreboard
            ->setLine(0, "          ")
            ->setLine(1, "§e➤ §6Vos Informations")
            ->setLine(2, Users::replace($player, "§6➥ §eRang: §{prefix}"))
            ->setLine(3, Users::replace($player, "§6➥ §eCoins: §f{coins}\u{E102}"))
            ->setLine(4, Users::replace($player, "§6➥ §ePB: §d{pb}"))
            ->setLine(5, "          ")
            ->setLine(6, "§e➤ §6Autres")
            ->setLine(7, "§6➥ §eVoteParty: §f" . Main::getManagerConfig()->get("votePartySkyblock") . "§7/§f150")
            ->setLine(8, "§6➥ §eIp: §fmoonlight-mc.eu")
            ->set();
        Main::getInstance()->cache[$player->getName()] = [
            "prefix" => Users::getPrefix($player->getName())
        ];
        Server::getInstance()->broadcastPopup("§7[§2+§7] §2" . $player->getName());
        Webhook::sendJoinQuit($player, "join");
    }

    public function onQuit(PlayerQuitEvent $event){
        $player = $event->getPlayer();
        $event->setQuitMessage(null);
        Server::getInstance()->broadcastPopup("§7[§4-§7] §4" . $player->getName());
        Webhook::sendJoinQuit($player, "quit");
    }

    public function onExhaust(PlayerExhaustEvent $event){
        $event->getPlayer()->setFood(18);
        $event->setCancelled(true);
    }

    public function onChat(PlayerChatEvent $event){
        $player = $event->getPlayer();
        $message = $event->getMessage();
        if (isset(Main::getInstance()->chat[$player->getName()])){
            SkyBlock::broadcastMemberIsland(SkyBlock::getIslandName($player->getName()), "§7" . $player->getName() . " §f>> §7" . $message);
            $event->setCancelled(true);
        }
        if (Bingo::$isEnable === true){
            if (Bingo::$number === (int)$message){
                Server::getInstance()->broadcastMessage("§7(§e!!§7) §fLe bingo est terminer ! Le vainqueur est §e" . $player->getName() . " §f! Le nombre était §e" . Bingo::$number . "§f.");
                $player->sendMessage(Manager::PREFIX_INFOS . "Bravo, tu as gagné le bingo ! Tu as remporter 2 000\u{E102}.");
                Coins::addCoins($player->getName(), 2000);
            }
        }
        Webhook::sendMessage($player, $message);
        $event->setFormat(Main::getInstance()->cache[$player->getName()]["prefix"] . $message);
    }

    public function onCommand(PlayerCommandPreprocessEvent $event)
    {
        $player = $event->getPlayer();
        $message = $event->getMessage();
        if (substr($message, 0, 1) === "/" or substr($message, 0, 2) === "//"){
            Webhook::sendCommand($player->getName() . " -> " . $message);
        }
    }

    public function onArmor(EntityArmorChangeEvent $event){
        $entity = $event->getEntity();
        $old = $event->getOldItem();
        $new = $event->getNewItem();
        if ($entity instanceof Player) {
            if ($new->getId() === ItemIds::CHAIN_HELMET) {
                $entity->addEffect(new EffectInstance(Effect::getEffect(Effect::NIGHT_VISION), 999999, 0, true));
            }elseif ($old->getId() === ItemIds::CHAIN_HELMET){
                $entity->removeEffect(Effect::NIGHT_VISION);
            }
            if ($new->getId() === ItemIds::CHAIN_CHESTPLATE){
                $entity->addEffect(new EffectInstance(Effect::getEffect(Effect::HASTE), 999999, 1, true));
            }elseif ($old->getId() === ItemIds::CHAIN_CHESTPLATE){
                $entity->removeEffect(Effect::HASTE);
            }
            if ($new->getId() === ItemIds::CHAIN_LEGGINGS){
                $entity->addEffect(new EffectInstance(Effect::getEffect(Effect::JUMP_BOOST), 999999, 0, true));
            }elseif ($old->getId() === ItemIds::CHAIN_LEGGINGS){
                $entity->removeEffect(Effect::JUMP_BOOST);
            }
            if ($new->getId() === ItemIds::CHAIN_BOOTS){
                $entity->addEffect(new EffectInstance(Effect::getEffect(Effect::SPEED), 999999, 1, true));
            }elseif ($old->getId() === ItemIds::CHAIN_BOOTS){
                $entity->removeEffect(Effect::SPEED);
            }
        }
    }

}