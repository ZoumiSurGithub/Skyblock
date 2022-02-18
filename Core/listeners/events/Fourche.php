<?php

namespace Zoumi\Core\listeners\events;

use pocketmine\block\Block;
use pocketmine\block\BlockFactory;
use pocketmine\block\BlockIds;
use pocketmine\block\Dirt;
use pocketmine\block\Farmland;
use pocketmine\block\Grass;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\inventory\CraftItemEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\item\Item;
use pocketmine\level\Position;
use pocketmine\network\mcpe\protocol\LevelSoundEventPacket;
use pocketmine\Player;

class Fourche implements Listener {

    public function onInteract(PlayerInteractEvent $event){
        $player = $event->getPlayer();
        $block = $event->getBlock();
        $item = $event->getItem();
        $action = $event->getAction();
        if ($item->getId() === Item::GOLD_HOE && $action === PlayerInteractEvent::RIGHT_CLICK_AIR && $player->isSneaking()){
            if ($event->isCancelled()) $event->setCancelled(true);
            if ($item->getNamedTag()->getString("Farm", "Blé") === "Blé"){
                $nbt = $item->getNamedTag();
                $nbt->setString("Farm", "Carotte");
                $item->setNamedTag($nbt);
                $item->setLore([
                    "§eNiveau: §f" . $nbt->getInt("Level"),
                    "§ePlantation choisie: §fCarotte",
                    "§cMettez vous en sneak et faites un clique droit\ndans le vide pour changer de plantation."
                ]);
                $player->getInventory()->setItemInHand($item);
            }elseif ($item->getNamedTag()->getString("Farm", "Blé") === "Carotte"){
                $nbt = $item->getNamedTag();
                $nbt->setString("Farm", "Patate");
                $item->setNamedTag($nbt);
                $item->setLore([
                    "§eNiveau: §f" . $nbt->getInt("Level"),
                    "§ePlantation choisie: §fPatate",
                    "§cMettez vous en sneak et faites un clique droit\ndans le vide pour changer de plantation."
                ]);
                $player->getInventory()->setItemInHand($item);
            }elseif ($item->getNamedTag()->getString("Farm", "Blé") === "Patate"){
                $nbt = $item->getNamedTag();
                $nbt->setString("Farm", "Verrue du nether");
                $item->setNamedTag($nbt);
                $item->setLore([
                    "§eNiveau: §f" . $nbt->getInt("Level"),
                    "§ePlantation choisie: §fVerrue du nether",
                    "§cMettez vous en sneak et faites un clique droit\ndans le vide pour changer de plantation."
                ]);
                $player->getInventory()->setItemInHand($item);
            }elseif ($item->getNamedTag()->getString("Farm", "Blé") === "Verrue du nether"){
                $nbt = $item->getNamedTag();
                $nbt->setString("Farm", "Blé");
                $item->setNamedTag($nbt);
                $item->setLore([
                    "§eNiveau: §f" . $nbt->getInt("Level"),
                    "§ePlantation choisie: §fBlé §7(Par défaut)",
                    "§cMettez vous en sneak et faites un clique droit\ndans le vide pour changer de plantation."
                ]);
                $player->getInventory()->setItemInHand($item);
            }
        }
        if ($item->getId() === Item::GOLD_HOE){
            $this->addBlock($block, $player, $item);
        }
    }

    /*
    public function onBreak(BlockBreakEvent $event){
        $player = $event->getPlayer();
        $block = $event->getBlock();
        $item = $event->getItem();
        if ($item->getId() === Item::GOLD_HOE){
            if (in_array($block->getId(), [Block::WHEAT_BLOCK, Block::CARROT_BLOCK, Block::POTATO_BLOCK, Block::NETHER_WART_BLOCK])) {
                if ($item->getNamedTag()->getInt("Level", 1) === 1) {
                    $block = $block->getLevel()->getBlockAt($block->x, $block->y, $block->z);
                    if ($item->getNamedTag()->getString("Farm", "Blé") === "Blé") {
                        if ($block->getId() === BlockIds::WHEAT_BLOCK && $block->getDamage() === 7) {
                            
                        }
                    }elseif ($item->getNamedTag()->getString("Farm", "Blé") === "Carotte"){
                        
                    }elseif ($item->getNamedTag()->getString("Farm", "Blé") === "Patate"){
                        
                    }elseif ($item->getNamedTag()->getString("Farm", "Blé") === "Verrue du nether"){
                        
                    }
                }
            }
        }
    }
    */

    private function addBlock(Block $blocks, Player $player, Item $item)
    {
        if ($item->getNamedTag()->getInt("Level", 1) === 1) {
            $minX = $blocks->x - 1;
            $maxX = $blocks->x + 1;
            $minZ = $blocks->z - 1;
            $maxZ = $blocks->z + 1;
            for ($x = $minX; $x <= $maxX; $x++) {
                for ($z = $minZ; $z <= $maxZ; $z++) {
                    $y = $blocks->y;
                    $block = $blocks->getLevel()->getBlockAt($x, $y, $z);
                    if ($item->getNamedTag()->getString("Farm", "Blé") === "Blé") {
                        if ($block->getId() == BlockIds::WHEAT_BLOCK and $block->getDamage() == 7) {
                            if ($player->getInventory()->contains(Item::get(Item::WHEAT_SEEDS, 0, 1))) {
                                $get = Block::get(Block::WHEAT_BLOCK);
                                $rand = mt_rand(1, 2);
                                $item = Item::get(Item::WHEAT, 0, $rand);
                                $block->level->setBlock($block, $get);
                                $player->getInventory()->addItem($item);
                                $item->setDamage($item->getDamage() - 1);
                            }
                        }
                    }elseif ($item->getNamedTag()->getString("Farm", "Blé") === "Carotte"){
                        if ($block->getId() == BlockIds::CARROT_BLOCK and $block->getDamage() == 7) {
                            if ($player->getInventory()->contains(Item::get(Item::CARROT, 0, 1))) {
                                $get = Block::get(Block::CARROT_BLOCK);
                                $rand = mt_rand(1, 4);
                                $item = Item::get(Item::CARROT, 0, $rand);
                                $block->level->setBlock($block, $get);
                                $player->getInventory()->addItem($item);
                                $item->setDamage($item->getDamage() - 1);
                            }
                        }
                    }elseif ($item->getNamedTag()->getString("Farm", "Blé") === "Patate"){
                        if ($block->getId() == BlockIds::POTATO_BLOCK and $block->getDamage() == 7) {
                            if ($player->getInventory()->contains(Item::get(Item::POTATO, 0, 1))) {
                                $get = Block::get(Block::POTATO_BLOCK);
                                $rand = mt_rand(1, 4);
                                $item = Item::get(Item::POTATO, 0, $rand);
                                $block->level->setBlock($block, $get);
                                $player->getInventory()->addItem($item);
                                $item->setDamage($item->getDamage() - 1);
                            }
                        }
                    }elseif ($item->getNamedTag()->getString("Farm", "Blé") === "Verrue du nether"){
                        if ($block->getId() == BlockIds::NETHER_WART_BLOCK and $block->getDamage() == 7) {
                            if ($player->getInventory()->contains(Item::get(Item::NETHER_WART, 0, 1))) {
                                $get = Block::get(Block::NETHER_WART_BLOCK);
                                $rand = mt_rand(2, 4);
                                $item = Item::get(Item::NETHER_WART, 0, $rand);
                                $block->level->setBlock($block, $get);
                                $player->getInventory()->addItem($item);
                                $item->setDamage($item->getDamage() - 1);
                            }
                        }
                    }
                }
            }
        }else if ($item->getNamedTag()->getInt("Level", 1) === 2) {
            $minX = $blocks->x - 2;
            $maxX = $blocks->x + 2;
            $minZ = $blocks->z - 2;
            $maxZ = $blocks->z + 2;
            for ($x = $minX; $x <= $maxX; $x++) {
                for ($z = $minZ; $z <= $maxZ; $z++) {
                    $y = $blocks->y;
                    $block = $blocks->getLevel()->getBlockAt($x, $y, $z);
                    if ($item->getNamedTag()->getString("Farm", "Blé") === "Blé") {
                        if ($block->getId() == BlockIds::WHEAT_BLOCK and $block->getDamage() == 7) {
                            if ($player->getInventory()->contains(Item::get(Item::WHEAT_SEEDS, 0, 1))) {
                                $get = Block::get(Block::WHEAT_BLOCK);
                                $rand = mt_rand(1, 2);
                                $item = Item::get(Item::WHEAT, 0, $rand);
                                $block->level->setBlock($block, $get);
                                $player->getInventory()->addItem($item);
                                $item->setDamage($item->getDamage() - 1);
                            }
                        }
                    }elseif ($item->getNamedTag()->getString("Farm", "Blé") === "Carotte"){
                        if ($block->getId() == BlockIds::CARROT_BLOCK and $block->getDamage() == 7) {
                            if ($player->getInventory()->contains(Item::get(Item::CARROT, 0, 1))) {
                                $get = Block::get(Block::CARROT_BLOCK);
                                $rand = mt_rand(1, 4);
                                $item = Item::get(Item::CARROT, 0, $rand);
                                $block->level->setBlock($block, $get);
                                $player->getInventory()->addItem($item);
                                $item->setDamage($item->getDamage() - 1);
                            }
                        }
                    }elseif ($item->getNamedTag()->getString("Farm", "Blé") === "Patate"){
                        if ($block->getId() == BlockIds::POTATO_BLOCK and $block->getDamage() == 7) {
                            if ($player->getInventory()->contains(Item::get(Item::POTATO, 0, 1))) {
                                $get = Block::get(Block::POTATO_BLOCK);
                                $rand = mt_rand(1, 4);
                                $item = Item::get(Item::POTATO, 0, $rand);
                                $block->level->setBlock($block, $get);
                                $player->getInventory()->addItem($item);
                                $item->setDamage($item->getDamage() - 1);
                            }
                        }
                    }elseif ($item->getNamedTag()->getString("Farm", "Blé") === "Verrue du nether"){
                        if ($block->getId() == BlockIds::NETHER_WART_BLOCK and $block->getDamage() == 7) {
                            if ($player->getInventory()->contains(Item::get(Item::NETHER_WART, 0, 1))) {
                                $get = Block::get(Block::NETHER_WART_BLOCK);
                                $rand = mt_rand(2, 4);
                                $item = Item::get(Item::NETHER_WART, 0, $rand);
                                $block->level->setBlock($block, $get);
                                $player->getInventory()->addItem($item);
                                $item->setDamage($item->getDamage() - 1);
                            }
                        }
                    }
                }
            }
        }else if ($item->getNamedTag()->getInt("Level", 1) === 3) {
            $minX = $blocks->x - 3;
            $maxX = $blocks->x + 3;
            $minZ = $blocks->z - 3;
            $maxZ = $blocks->z + 3;
            for ($x = $minX; $x <= $maxX; $x++) {
                for ($z = $minZ; $z <= $maxZ; $z++) {
                    $y = $blocks->y;
                    $block = $blocks->getLevel()->getBlockAt($x, $y, $z);
                    if ($item->getNamedTag()->getString("Farm", "Blé") === "Blé") {
                        if ($block->getId() == BlockIds::WHEAT_BLOCK and $block->getDamage() == 7) {
                            if ($player->getInventory()->contains(Item::get(Item::WHEAT_SEEDS, 0, 1))) {
                                $get = Block::get(Block::WHEAT_BLOCK);
                                $rand = mt_rand(1, 2);
                                $item = Item::get(Item::WHEAT, 0, $rand);
                                $block->level->setBlock($block, $get);
                                $player->getInventory()->addItem($item);
                                $item->setDamage($item->getDamage() - 1);
                            }
                        }
                    }elseif ($item->getNamedTag()->getString("Farm", "Blé") === "Carotte"){
                        if ($block->getId() == BlockIds::CARROT_BLOCK and $block->getDamage() == 7) {
                            if ($player->getInventory()->contains(Item::get(Item::CARROT, 0, 1))) {
                                $get = Block::get(Block::CARROT_BLOCK);
                                $rand = mt_rand(1, 4);
                                $item = Item::get(Item::CARROT, 0, $rand);
                                $block->level->setBlock($block, $get);
                                $player->getInventory()->addItem($item);
                                $item->setDamage($item->getDamage() - 1);
                            }
                        }
                    }elseif ($item->getNamedTag()->getString("Farm", "Blé") === "Patate"){
                        if ($block->getId() == BlockIds::POTATO_BLOCK and $block->getDamage() == 7) {
                            if ($player->getInventory()->contains(Item::get(Item::POTATO, 0, 1))) {
                                $get = Block::get(Block::POTATO_BLOCK);
                                $rand = mt_rand(1, 4);
                                $item = Item::get(Item::POTATO, 0, $rand);
                                $block->level->setBlock($block, $get);
                                $player->getInventory()->addItem($item);
                                $item->setDamage($item->getDamage() - 1);
                            }
                        }
                    }elseif ($item->getNamedTag()->getString("Farm", "Blé") === "Verrue du nether"){
                        if ($block->getId() == BlockIds::NETHER_WART_BLOCK and $block->getDamage() == 7) {
                            if ($player->getInventory()->contains(Item::get(Item::NETHER_WART, 0, 1))) {
                                $get = Block::get(Block::NETHER_WART_BLOCK);
                                $rand = mt_rand(2, 4);
                                $item = Item::get(Item::NETHER_WART, 0, $rand);
                                $block->level->setBlock($block, $get);
                                $player->getInventory()->addItem($item);
                                $item->setDamage($item->getDamage() - 1);
                            }
                        }
                    }
                }
            }
        }
    }
    
    public function getIdOfPlantation(Item $item): int{
        if ($item->getNamedTag()->getString("Farm", "Blé") === "Blé"){
            return Item::WHEAT_SEEDS;
        }elseif ($item->getNamedTag()->getString("Farm", "Blé") === "Carotte"){
            return Item::CARROT;
        }elseif ($item->getNamedTag()->getString("Farm", "Blé") === "Patate"){
            return Item::POTATO;
        }elseif ($item->getNamedTag()->getString("Farm", "Blé") === "Verrue du nether"){
            return Item::NETHER_WART;
        }
    }

    public function getIdOfPlantationToBlock(Item $item): Block{
        if ($item->getNamedTag()->getString("Farm", "Blé") === "Blé"){
            return BlockFactory::get(Block::WHEAT_BLOCK);
        }elseif ($item->getNamedTag()->getString("Farm", "Blé") === "Carotte"){
            return BlockFactory::get(Block::CARROT_BLOCK);
        }elseif ($item->getNamedTag()->getString("Farm", "Blé") === "Patate"){
            return BlockFactory::get(Block::POTATO_BLOCK);
        }elseif ($item->getNamedTag()->getString("Farm", "Blé") === "Verrue du nether"){
            return BlockFactory::get(Block::NETHER_WART_BLOCK);
        }
    }

    public function onCraft(CraftItemEvent $event){
        foreach ($event->getOutputs() as $output){
            if ($output->getId() === Item::GOLD_HOE){
                if (!$event->isCancelled()) $event->setCancelled(true);
                $player = $event->getPlayer();
                $player->getInventory()->removeItem(Item::get(Item::STICK, 0, 2));
                $player->getInventory()->removeItem(Item::get(Item::GOLD_BLOCK, 0, 3));
                $nbt = $output->getNamedTag();
                $nbt->setInt("Level", 1);
                $nbt->setString("Farm", "Blé");
                $output->setNamedTag($nbt);
                $output->setLore([
                    "§eNiveau: §f1",
                    "§ePlantation choisie: §fBlé §7(Par défaut)\n",
                    "§cMettez vous en sneak et faites un clique droit\ndans le vide pour changer de plantation."
                ]);
                $event->getPlayer()->getInventory()->addItem($output);
            }
        }
    }

}