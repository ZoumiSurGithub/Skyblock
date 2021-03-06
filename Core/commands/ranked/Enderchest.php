<?php

namespace Zoumi\Core\commands\ranked;

use pocketmine\block\Block;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\IntTag;
use pocketmine\nbt\tag\StringTag;
use pocketmine\Player;
use pocketmine\tile\Tile;
use Zoumi\Core\Main;
use Zoumi\Core\Manager;

class Enderchest extends Command {

    public function __construct(string $name, string $description = "", string $usageMessage = null, array $aliases = [])
    {
        parent::__construct($name, $description, $usageMessage, $aliases);
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        if ($sender instanceof Player){
            if (Main::getInstance()->ec->exists($sender->getName()) or $sender->hasPermission("use.enderchest")){
                $nbt = new CompoundTag("", [new StringTag("id", Tile::CHEST), new StringTag("CustomName", "EnderChest"), new IntTag("x", (int)floor($sender->x)), new IntTag("y", (int)floor($sender->y) - 4), new IntTag("z", (int)floor($sender->z))]);
                /** @var \pocketmine\tile\EnderChest $tile */
                $tile = Tile::createTile("EnderChest", $sender->getLevel(), $nbt);
                $block = Block::get(Block::ENDER_CHEST);
                $block->x = (int)$tile->x;
                $block->y = (int)$tile->y;
                $block->z = (int)$tile->z;
                $block->level = $tile->getLevel();
                $block->level->sendBlocks([$sender], [$block]);
                $sender->getEnderChestInventory()->setHolderPosition($tile);
                $sender->addWindow($sender->getEnderChestInventory());
            }else{
                $sender->sendMessage(Manager::NOT_PERM);
                return;
            }
        }
    }

}