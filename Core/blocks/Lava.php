<?php

namespace Zoumi\Core\blocks;

use pocketmine\block\Block;
use pocketmine\block\BlockFactory;
use pocketmine\block\BlockIds;
use pocketmine\block\Water;
use pocketmine\entity\Entity;
use pocketmine\event\entity\EntityCombustByBlockEvent;
use pocketmine\event\entity\EntityDamageByBlockEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\item\Item;
use pocketmine\math\Vector3;
use pocketmine\network\mcpe\protocol\LevelSoundEventPacket;
use pocketmine\Player;
use RedCraftPE\RedSkyBlock\SkyBlock;

class Lava extends \pocketmine\block\Lava {

    protected $id = self::FLOWING_LAVA;

    public function __construct(int $meta = 0) {

        $this->meta = $meta;
    }

    public function getLightLevel(): int {

        return 15;
    }

    public function getName(): string {

        return "Lava";
    }

    public function getStillForm(): Block {

        return BlockFactory::get(Block::STILL_LAVA, $this->meta);
    }

    public function getFlowingForm(): Block {

        return BlockFactory::get(Block::FLOWING_LAVA, $this->meta);
    }

    public function getBucketFillSound(): int {

        return LevelSoundEventPacket::SOUND_BUCKET_FILL_LAVA;
    }

    public function getBucketEmptySound(): int {

        return LevelSoundEventPacket::SOUND_BUCKET_EMPTY_LAVA;
    }

    public function tickRate(): int {

        return 30;
    }

    public function getFlowDecayPerBlock(): int {

        return 2;
    }

    protected function checkForHarden()
    {

        $colliding = null;
        for ($side = 1; $side <= 5; ++$side) {

            $blockSide = $this->getSide($side);
            if ($blockSide instanceof Water) {

                $colliding = $blockSide;
                break;
            }
        }

        if ($colliding !== null) {

            if ($this->getDamage() === 0) {

                $rand = mt_rand(1, 100);
                var_dump($rand);
                if ($rand >= 1 && $rand < 60) {
                    $this->liquidCollide($colliding, BlockFactory::get(Block::COBBLESTONE));
                } elseif ($rand >= 60 && $rand < 75) {
                    $this->liquidCollide($colliding, BlockFactory::get(Block::COAL_ORE));
                } elseif ($rand >= 75 && $rand < 85) {
                    $this->liquidCollide($colliding, BlockFactory::get(Block::IRON_ORE));
                } elseif ($rand >= 85 && $rand < 92) {
                    $this->liquidCollide($colliding, BlockFactory::get(Block::GOLD_ORE));
                } elseif ($rand >= 92 && $rand < 97) {
                    switch (mt_rand(0, 1)) {
                        case 0:
                            $this->liquidCollide($colliding, BlockFactory::get(Block::REDSTONE_ORE));
                            break;
                        case 1:
                            $this->liquidCollide($colliding, BlockFactory::get(Block::LAPIS_ORE));
                            break;
                    }
                } elseif ($rand >= 97 && $rand < 99) {
                    $this->liquidCollide($colliding, BlockFactory::get(Block::DIAMOND_ORE));
                } elseif ($rand >= 99 && $rand <= 100) {
                    $this->liquidCollide($colliding, BlockFactory::get(Block::EMERALD_ORE));
                }
            }
        }
    }

    protected function flowIntoBlock(Block $block, int $newFlowDecay): void {

        if ($block instanceof Water) {

            $block->liquidCollide($this, BlockFactory::get(Block::STONE));
        } else {

            parent::flowIntoBlock($block, $newFlowDecay);
        }
    }

    public function onEntityCollide(Entity $entity): void {

        $entity->fallDistance *= 0.5;

        $ev = new EntityDamageByBlockEvent($this, $entity, EntityDamageEvent::CAUSE_LAVA, 4);
        $entity->attack($ev);

        $ev = new EntityCombustByBlockEvent($this, $entity, 15);
        $ev->call();

        if (!$ev->isCancelled()) {

            $entity->setOnFire($ev->getDuration());
        }

        $entity->resetFallDistance();
    }

    public function place(Item $item, Block $blockReplace, Block $blockClicked, int $face, Vector3 $clickVector, Player $player = null): bool {

        $ret = $this->getLevel()->setBlock($this, $this, true, false);
        $this->getLevel()->scheduleDelayedBlockUpdate($this, $this->tickRate());

        return $ret;
    }

}