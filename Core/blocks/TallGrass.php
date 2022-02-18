<?php

namespace Zoumi\Core\blocks;

use pocketmine\item\Item;

class TallGrass extends \pocketmine\block\TallGrass {

    public function getDrops(Item $item): array
    {
        $drops = [];
        switch (mt_rand(1, 100)){
            case 1:
                $drops[] = Item::get(Item::BEETROOT_SEEDS);
                break;
            default:
                $drops[] = Item::get(Item::SEEDS);
                break;
        }
        return $drops;
    }

}