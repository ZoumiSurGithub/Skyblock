<?php

namespace Zoumi\Core\blocks;

use pocketmine\block\BlockToolType;
use pocketmine\block\Solid;
use pocketmine\item\Item;
use pocketmine\item\TieredTool;

class ShulkerBox extends Solid {

    protected $id = self::SHULKER_BOX;

    public function __construct(int $meta = 0)
    {
        $this->meta = $meta;
    }

    public function getName(): string
    {
        return "Shulker Box";
    }

    public function getToolType() : int{
        return BlockToolType::TYPE_PICKAXE;
    }

    public function getToolHarvestLevel() : int{
        return TieredTool::TIER_DIAMOND;
    }

}