<?php

namespace Zoumi\Core\listeners\events;

use pocketmine\block\Anvil;
use pocketmine\block\BlockIds;
use pocketmine\block\Chest;
use pocketmine\block\CraftingTable;
use pocketmine\block\EnchantingTable;
use pocketmine\block\EnderChest;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\block\BlockPlaceEvent;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\server\DataPacketReceiveEvent;
use pocketmine\item\Item;
use pocketmine\network\mcpe\protocol\ItemFrameDropItemPacket;
use pocketmine\Player;
use pocketmine\Server;
use Zoumi\Core\api\SkyBlock;
use Zoumi\Core\listeners\FormListener;
use Zoumi\Core\Manager;

class Protection implements Listener {

    public function onPlace(BlockPlaceEvent $event){
        $block = $event->getBlock();
        $player = $event->getPlayer();
        if ($player->getGamemode() !== 0) return;
        /* Vérification de l'endroit */
        if ($player->getLevel()->getFolderName() === "spawn"){
            if (!$event->isCancelled()) return $event->setCancelled(true);
        }elseif ($player->getLevel()->getFolderName() === "ffa"){
            if (!$event->isCancelled()) return $event->setCancelled(true);
        }elseif ($player->getLevel()->getFolderName() === "farmzone1"){
            if (!$event->isCancelled()) return $event->setCancelled(true);
        }elseif ($player->getLevel()->getFolderName() === "farmzone2") {
            if (!$event->isCancelled()) return $event->setCancelled(true);
        }elseif ($player->getLevel()->getFolderName() !== SkyBlock::getIslandName($player->getName())){
            if (!$event->isCancelled()) return $event->setCancelled(true);
        }

        /* Si c'est l'île. */
        if ($player->getLevel()->getFolderName() === SkyBlock::getIslandName($player->getName())) {
            $b = $player->getLevel()->getBlock(Server::getInstance()->getLevelByName(SkyBlock::getIslandName($player->getName()))->getSafeSpawn()->subtract(0, 1, 0));
            if ($b->getX() === $block->getX() && $b->getY() === $block->getY() && $b->getZ() === $b->getZ()) return;
            switch ($block->getId()) {
                case BlockIds::EMERALD_BLOCK:
                    $player->sendPopup("§e+4 §fPoints d'île");
                    SkyBlock::addPoint(SkyBlock::getIslandName($player->getName()), 4);
                    break;
                case BlockIds::DIAMOND_BLOCK:
                    $player->sendPopup("§e+3 §fPoints d'île");
                    SkyBlock::addPoint(SkyBlock::getIslandName($player->getName()), 3);
                    break;
                case BlockIds::REDSTONE_BLOCK:
                    $player->sendPopup("§e+1 §fPoint d'île");
                    SkyBlock::addPoint(SkyBlock::getIslandName($player->getName()), 1);
                    break;
                case BlockIds::LAPIS_BLOCK:
                    $player->sendPopup("§e+1 §fPoint d'île");
                    SkyBlock::addPoint(SkyBlock::getIslandName($player->getName()), 1);
                    break;
                case BlockIds::COAL_BLOCK:
                    $player->sendPopup("§e+1 §fPoint d'île");
                    SkyBlock::addPoint(SkyBlock::getIslandName($player->getName()), 1);
                    break;
                case BlockIds::IRON_BLOCK:
                    $player->sendPopup("§e+2 §fPoints d'île");
                    SkyBlock::addPoint(SkyBlock::getIslandName($player->getName()), 2);
                    break;
            }
        }
    }

    public function onBreak(BlockBreakEvent $event){
        $block = $event->getBlock();
        $player = $event->getPlayer();
        if ($player->getGamemode() !== 0) return;
        /* Vérification de l'endroit */
        if ($player->getLevel()->getFolderName() === "spawn"){
            if (!$event->isCancelled()) return $event->setCancelled(true);
        }elseif ($player->getLevel()->getFolderName() === "ffa"){
            if (!$event->isCancelled()) return $event->setCancelled(true);
        }elseif ($player->getLevel()->getFolderName() === "farmzone1"){
            if (!$event->isCancelled()) return $event->setCancelled(true);
        }elseif ($player->getLevel()->getFolderName() === "farmzone2") {
            if (!$event->isCancelled()) return $event->setCancelled(true);
        }elseif ($player->getLevel()->getFolderName() !== SkyBlock::getIslandName($player->getName())){
            if (!$event->isCancelled()) return $event->setCancelled(true);
        }

        /* Si c'est son île. */
        if ($player->getLevel()->getFolderName() === SkyBlock::getIslandName($player->getName())) {
            $b = $player->getLevel()->getBlock(Server::getInstance()->getLevelByName(SkyBlock::getIslandName($player->getName()))->getSafeSpawn()->subtract(0, 1, 0));
            if ($b->getX() === $block->getX() && $b->getY() === $block->getY() && $b->getZ() === $b->getZ()) return;
            switch ($block->getId()) {
                case BlockIds::EMERALD_BLOCK:
                    $player->sendPopup("§e-4 §fPoints d'île");
                    if (SkyBlock::getPoint(SkyBlock::getIslandName($player->getName())) >= 4) {
                        SkyBlock::removePoint(SkyBlock::getIslandName($player->getName()), 4);
                    }elseif (SkyBlock::getPoint(SkyBlock::getIslandName($player->getName())) < 4){
                        SkyBlock::setPoint(SkyBlock::getIslandName($player->getName()), 0);
                    }
                    break;
                case BlockIds::DIAMOND_BLOCK:
                    $player->sendPopup("§e-3 §fPoints d'île");
                    if (SkyBlock::getPoint(SkyBlock::getIslandName($player->getName())) >= 3) {
                        SkyBlock::removePoint(SkyBlock::getIslandName($player->getName()), 3);
                    }elseif (SkyBlock::getPoint(SkyBlock::getIslandName($player->getName())) < 3){
                        SkyBlock::setPoint(SkyBlock::getIslandName($player->getName()), 0);
                    }
                    break;
                case BlockIds::REDSTONE_BLOCK:
                    $player->sendPopup("§e-1 §fPoint d'île");
                    if (SkyBlock::getPoint(SkyBlock::getIslandName($player->getName())) >= 1) {
                        SkyBlock::removePoint(SkyBlock::getIslandName($player->getName()), 1);
                    }else{
                        SkyBlock::setPoint(SkyBlock::getIslandName($player->getName()), 0);
                    }
                    break;
                case BlockIds::LAPIS_BLOCK:
                    $player->sendPopup("§e-1 §fPoint d'île");
                    if (SkyBlock::getPoint(SkyBlock::getIslandName($player->getName())) >= 1) {
                        SkyBlock::removePoint(SkyBlock::getIslandName($player->getName()), 1);
                    }else{
                        SkyBlock::setPoint(SkyBlock::getIslandName($player->getName()), 0);
                    }
                    break;
                case BlockIds::COAL_BLOCK:
                    $player->sendPopup("§e-1 §fPoint d'île");
                    if (SkyBlock::getPoint(SkyBlock::getIslandName($player->getName())) >= 1) {
                        SkyBlock::removePoint(SkyBlock::getIslandName($player->getName()), 1);
                    }else{
                        SkyBlock::setPoint(SkyBlock::getIslandName($player->getName()), 0);
                    }
                    break;
                case BlockIds::IRON_BLOCK:
                    $player->sendPopup("§e-2 §fPoints d'île");
                    if (SkyBlock::getPoint(SkyBlock::getIslandName($player->getName())) >= 2) {
                        SkyBlock::removePoint(SkyBlock::getIslandName($player->getName()), 2);
                    }elseif (SkyBlock::getPoint(SkyBlock::getIslandName($player->getName())) < 2){
                        SkyBlock::setPoint(SkyBlock::getIslandName($player->getName()), 0);
                    }
                    break;
            }
        }
    }

    public function onEntityDamageByEntity(EntityDamageByEntityEvent $event){
        $entity = $event->getEntity();
        $damager = $event->getDamager();
        /* Vérification de l'endroit */
        if ($damager instanceof Player && $entity instanceof Player) {
            $event->setKnockBack(0.420);
            if ($damager->getLevel()->getFolderName() === "spawn") {
                if (!$event->isCancelled()) return $event->setCancelled(true);
            } elseif ($damager->getLevel()->getFolderName() === "farmzone1") {
                if (!$entity instanceof Player) return;
                if (!$event->isCancelled()) return $event->setCancelled(true);
            } elseif ($damager->getLevel()->getFolderName() === "farmzone2") {
                if (!$entity instanceof Player) return;
                if (!$event->isCancelled()) return $event->setCancelled(true);
            }elseif ($damager->getLevel()->getFolderName() === SkyBlock::getIslandName($damager->getName())){
                if (SkyBlock::isDamage(SkyBlock::getIslandName($damager->getName()))){
                    if (!$event->isCancelled()) return $event->setCancelled(true);
                }
            }elseif ($damager->getLevel()->getFolderName() !== "ffa" && $damager->getLevel()->getFolderName() !== SkyBlock::getIslandName($damager->getName())){
                if (!$event->isCancelled()) return $event->setCancelled(true);
            }
        }
    }

    public function onInteract(PlayerInteractEvent $event){
        $player = $event->getPlayer();
        $block = $event->getBlock();
        $item = $event->getItem();
        $action = $event->getAction();
        if ($player instanceof Player) {
            if ($player->getGamemode() !== 0) return;
            if ($block instanceof Anvil && $action === PlayerInteractEvent::RIGHT_CLICK_BLOCK) {
                if (!$event->isCancelled()) $event->setCancelled(true);
                FormListener::sendAnvilMenu($player, $block);
                return;
            }elseif ($block instanceof EnchantingTable && $action === PlayerInteractEvent::RIGHT_CLICK_BLOCK) {
                if (!$event->isCancelled()) $event->setCancelled(true);
                FormListener::sendEnchantMenu($player);
                return;
            }elseif ($block instanceof CraftingTable) {
                return;
            }elseif ($block instanceof EnderChest) {
                return;
            }

            if ($item->getId() === Item::BOTTLE_O_ENCHANTING && $event->getAction() === PlayerInteractEvent::RIGHT_CLICK_AIR){
                if ($item->getNamedTag()->getInt("Level", 0) > 0){
                    if (!$event->isCancelled()) $event->setCancelled(true);
                    $player->addXpLevels($item->getNamedTag()->getInt("Level", 0));
                    $player->sendMessage(Manager::PREFIX_INFOS . "Vous avez bien utilisé votre bouteille de §e" . $item->getNamedTag()->getInt("Level", 0) . " §fniveau(x) d'expérience(s).");
                    $player->getInventory()->removeItem($item);
                }
            }
            
            /* Vérification du monde */
            if ($player->getLevel()->getFolderName() === "spawn"){
                if (!$event->isCancelled()) return $event->setCancelled(true);
            }elseif ($player->getLevel()->getFolderName() === "farmzone1"){
                if (!$event->isCancelled()) return $event->setCancelled(true);
            }elseif ($player->getLevel()->getFolderName() === "farmzone2"){
                if (!$event->isCancelled()) return $event->setCancelled(true);
            }elseif($player->getLevel()->getFolderName() !== "ffa" && $player->getLevel()->getFolderName() !== SkyBlock::getIslandName($player->getName())){
                if (!$event->isCancelled()) return $event->setCancelled(true);
            }
        }
    }

    public function onEntityDamage(EntityDamageEvent $event){
        $player = $event->getEntity();
        $cause = $event->getCause();
        if ($player instanceof Player) {
            switch ($cause) {
                case EntityDamageEvent::CAUSE_VOID:
                    if ($player->getLevel()->getFolderName() === "spawn"){
                        $player->teleport(Server::getInstance()->getDefaultLevel()->getSafeSpawn());
                    }elseif ($player->getLevel()->getFolderName() === "farmzone1"){
                        $player->teleport(Server::getInstance()->getLevelByName("farmzone1")->getSafeSpawn());
                    }elseif ($player->getLevel()->getFolderName() === "farmzone2"){
                        $player->teleport(Server::getInstance()->getLevelByName("farmzone2")->getSafeSpawn());
                    }
                    break;
                case EntityDamageEvent::CAUSE_FALL:
                    if (!$event->isCancelled()) return $event->setCancelled(true);
                    break;
            }
        }
    }

    public function onDataPacketReceive(DataPacketReceiveEvent $event){
        $pk = $event->getPacket();
        $player = $event->getPlayer();
        if ($pk instanceof ItemFrameDropItemPacket){
            if ($player instanceof Player) {
                if (!$player->isOp()) $event->setCancelled(true);
            }
        }
    }

}