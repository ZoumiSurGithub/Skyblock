<?php

namespace Zoumi\Core\commands\all;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\item\Item;
use pocketmine\Player;
use Zoumi\Core\Main;
use Zoumi\Core\Manager;

class BottleXp extends Command {

    public function __construct(string $name, string $description = "", string $usageMessage = null, array $aliases = [])
    {
        parent::__construct($name, $description, $usageMessage, $aliases);
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        if ($sender instanceof Player){
            if (time() >= Main::getInstance()->cooldown->get($sender->getName() . "-bottlexp")){
                if ($sender->getXpLevel() > 0) {
                    $sender->getInventory()->addItem($this->sendBottle($sender));
                    $config = Main::getInstance()->cooldown;
                    $config->set($sender->getName() . "-bottlexp", time() + 3600);
                    $config->save();
                }else{
                    $sender->sendMessage(Manager::PREFIX_ALERT . "Vous n'avez pas de niveau d'xp à stocker.");
                    return;
                }
            }else{
                $sender->sendMessage(Manager::PREFIX_ALERT . "Veuillez patienter encore §e" . Main::getInstance()->convert(Main::getInstance()->cooldown->get($sender->getName() . "-bottlexp") - time()) . "§c.");
                return;
            }
        }
    }

    public function sendBottle(Player $player): Item{
        $item = Item::get(Item::BOTTLE_O_ENCHANTING);
        $item->setCustomName("§r§l§6Boutteille de §e" . $player->getName());
        $nbt = $item->getNamedTag();
        $nbt->setInt("Level", $player->getXpLevel());
        $nbt->setString("Joueur", $player->getName());
        $item->setNamedTag($nbt);
        $player->sendMessage(Manager::PREFIX_INFOS . "Vous avez transformer §e" . $player->getXpLevel() . " §fniveau(x) d'xp(s) en boutteille.");
        $player->setXpLevel(0);
        return $item;
    }

}