<?php

namespace Zoumi\Core\listeners;

use pocketmine\block\Block;
use pocketmine\block\Cobblestone;
use pocketmine\block\Lava;
use pocketmine\block\Water;
use pocketmine\command\ConsoleCommandSender;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\block\BlockFormEvent;
use pocketmine\event\block\BlockSpreadEvent;
use pocketmine\event\block\BlockUpdateEvent;
use pocketmine\event\Cancellable;
use pocketmine\event\Listener;
use pocketmine\Server;
use Zoumi\Core\api\SkyBlock;

class BlockListener implements Listener {

    public function onBlockForm(BlockFormEvent $event){
        $block = $event->getBlock();
        $new = $event->getNewState();
        if ($block instanceof Water or $block instanceof Lava && $new instanceof Cobblestone){
            if (!$event->isCancelled()) $event->setCancelled(true);
            $rand = mt_rand(1, 100);
            if ($rand >= 1 && $rand < 60){
                $block->getLevel()->setBlock($block->asPosition(), Block::get(Block::COBBLESTONE), true);
            }elseif ($rand >= 60 && $rand < 75){
                $block->getLevel()->setBlock($block->asPosition(), Block::get(Block::COAL_ORE), true);
            }elseif ($rand >= 75 && $rand < 85){
                $block->getLevel()->setBlock($block->asPosition(), Block::get(Block::IRON_ORE), true);
            }elseif ($rand >= 85 && $rand < 92){
                $block->getLevel()->setBlock($block->asPosition(), Block::get(Block::GOLD_ORE), true);
            }elseif ($rand >= 92 && $rand < 97){
                switch (mt_rand(0, 1)) {
                    case 0:
                        $block->getLevel()->setBlock($block->asPosition(), Block::get(Block::REDSTONE_ORE), true);
                        break;
                    case 1:
                        $block->getLevel()->setBlock($block->asPosition(), Block::get(Block::LAPIS_ORE), true);
                        break;
                }
            }elseif ($rand >= 97 && $rand < 99){
                $block->getLevel()->setBlock($block->asPosition(), Block::get(Block::DIAMOND_ORE), true);
            }elseif ($rand >= 99 && $rand <= 100){
                $block->getLevel()->setBlock($block->asPosition(), Block::get(Block::EMERALD_ORE), true);
            }
        }
    }


    public function onBreak(BlockBreakEvent $event)
    {
        $block = $event->getBlock();
        $player = $event->getPlayer();
        $item = $event->getItem();
        if ($player->getGamemode() === 1) return;
        if ($player->getLevel()->getFolderName() !== SkyBlock::getIslandName($player->getName())) return;
        $b = $player->getLevel()->getBlock(Server::getInstance()->getLevelByName(SkyBlock::getIslandName($player->getName()))->getSafeSpawn()->subtract(0, 1, 0));
        if ($b->getX() === $block->getX() && $b->getY() === $block->getY() && $b->getZ() === $b->getZ()) return;
        $player->addXp($event->getXpDropAmount());
        foreach ($event->getDrops() as $item) {
            if ($player->getInventory()->canAddItem($item)) {
                $player->getInventory()->addItem($item);
            } else {
                $player->getLevel()->dropItem($player->getPosition(), $item);
            }
        }
        $event->setDrops([]);
        $event->setXpDropAmount(0);
    }

}