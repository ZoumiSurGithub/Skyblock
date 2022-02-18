<?php

namespace Zoumi\Core\tiles;

use pocketmine\block\Air;
use pocketmine\block\Block;
use pocketmine\block\BlockIds;
use pocketmine\block\Farmland;
use pocketmine\block\TallGrass;
use pocketmine\inventory\ChestInventory;
use pocketmine\inventory\InventoryHolder;
use pocketmine\item\Item;
use pocketmine\item\ItemIds;
use pocketmine\level\Level;
use pocketmine\level\Position;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\tile\Container;
use pocketmine\tile\ContainerTrait;
use pocketmine\tile\Nameable;
use pocketmine\tile\NameableTrait;
use pocketmine\tile\Spawnable;
use pocketmine\tile\Tile;
use Zoumi\Core\inventory\FarmingChestInventory;

class FarmingChestTile extends Spawnable implements InventoryHolder, Container, Nameable {

    use NameableTrait {
        addAdditionalSpawnData as addNameSpawnData;
    }
    use ContainerTrait;

    public function __construct(Level $level, CompoundTag $nbt)
    {
        parent::__construct($level, $nbt);
        $this->scheduleUpdate();
    }

    /** @var FarmingChestInventory */
    protected $inventory;

    protected function writeSaveData(CompoundTag $nbt): void
    {
        $this->saveName($nbt);
        $this->saveItems($nbt);
    }

    protected function readSaveData(CompoundTag $nbt): void
    {
        $this->loadName($nbt);

        $this->inventory = new FarmingChestInventory($this);
        $this->loadItems($nbt);
    }

    /**
     * @return FarmingChestInventory
     */
    public function getInventory(): FarmingChestInventory
    {
        return $this->inventory;
    }

    /**
     * @return FarmingChestInventory
     */
    public function getRealInventory(): FarmingChestInventory
    {
        return $this->inventory;
    }

    public function getDefaultName(): string
    {
        return "Farming Chest";
    }

    public function onUpdate(): bool
    {
        $minX = $this->getX() - 2;
        $maxX = $this->getX() + 2;
        $minY = $this->getY() - 1;
        $maxY = $this->getY() + 1;
        $minZ = $this->getZ() - 2;
        $maxZ = $this->getZ() + 2;
        for ($x = $minX; $x < $maxX; $x++) {
            for ($y = $minY; $y < $maxY; $y++) {
                for ($z = $minZ; $z < $maxZ; $z++) {
                    $block = $this->getLevel()->getBlock(new Position($x, $y, $z, $this->getLevel()));
                    if ($block instanceof TallGrass) {
                        $block1 = $this->getLevel()->getBlock(new Position($x, $y, $z, $this->getLevel()));
                        var_dump($block1);
                        if (in_array($block1->getId(), [BlockIds::WHEAT_BLOCK, BlockIds::CARROT_BLOCK, BlockIds::POTATO_BLOCK]) && $block1->getDamage() === 7) {
                            foreach ($block1->getDrops(Item::get(0)) as $item) {
                                if ($this->getInventory()->canAddItem($item)) {
                                    $this->getInventory()->addItem($item);
                                    $block1->getLevel()->setBlock(new Position($x, $y + 1, $z, $this->getLevel()), new Air());
                                    $b = null;
                                    foreach ($this->getInventory()->getContents() as $item) {
                                        if ($item->getId() === ItemIds::CARROT) {
                                            $b = BlockIds::CARROT_BLOCK;
                                            $this->getInventory()->remove($item);
                                            break;
                                        } elseif ($item->getId() === ItemIds::WHEAT_SEEDS) {
                                            $b = BlockIds::WHEAT_BLOCK;
                                            $this->getInventory()->remove($item);
                                            break;
                                        } elseif ($item->getId() === ItemIds::POTATO) {
                                            $b = BlockIds::POTATO_BLOCK;
                                            $this->getInventory()->remove($item);
                                            break;
                                        }
                                        return true;
                                    }
                                    $block1->getLevel()->setBlock(new Position($x, $y + 1, $z, $this->getLevel()), Block::get($b));
                                }
                            }
                        }
                    }
                }
            }
            return true;
        }
    }
}