<?php

namespace Zoumi\Core\blocks;

use pocketmine\block\TrappedChest;
use pocketmine\item\Item;
use pocketmine\Player;
use pocketmine\tile\Tile;
use Zoumi\Core\tiles\FarmingChestTile;

class FarmingChest extends TrappedChest {

    protected $id = self::TRAPPED_CHEST;

    public function getName() : string{
        return "Farming Chest";
    }

    public function onActivate(Item $item, Player $player = null): bool
    {
        if ($player instanceof Player) {
            $farming = Tile::createTile("FarmingChestTile", $this->level, FarmingChestTile::createNBT($this));
            if (!($farming instanceof FarmingChestTile)){
                return true;
            }
        }
        return true;
    }

}