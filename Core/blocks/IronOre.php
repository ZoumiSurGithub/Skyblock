<?php

namespace Zoumi\Core\blocks;

use pocketmine\item\Item;

class IronOre extends \pocketmine\block\IronOre {

    public function getDrops(Item $item): array
    {
        return [Item::get(Item::IRON_INGOT, 0, 1)];
    }

}