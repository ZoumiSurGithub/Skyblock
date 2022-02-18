<?php

namespace Zoumi\Core\blocks;

use pocketmine\block\GoldOre;
use pocketmine\item\Item;

class GoldenOre extends GoldOre {

    public function getDrops(Item $item): array
    {
        $drops = [];

        $rand = mt_rand(1, 100);

        if ($rand >= 1 && $rand < 60){
            $rand = mt_rand(1, 3);
            $drops[] = Item::get(Item::COAL, 0, $rand);
        }elseif ($rand >= 60 && $rand <= 80){
            $drops[] = Item::get(Item::IRON_INGOT);
        }elseif ($rand >= 80 && $rand < 87){
            $rand = mt_rand(1, 3);
            $drops[] = Item::get(Item::REDSTONE, 0, $rand);
        }elseif ($rand >= 87 && $rand < 94){
            $rand = mt_rand(1, 3);
            $drops[] = Item::get(351, 4, $rand);
        }elseif ($rand >= 94 && $rand < 98){
            $drops[] = Item::get(Item::DIAMOND);
        }elseif ($rand >= 98 && $rand <= 100){
            $drops[] = Item::get(Item::EMERALD);
        }

        return $drops;
    }

}