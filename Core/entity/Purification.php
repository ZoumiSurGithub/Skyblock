<?php

namespace Zoumi\Core\entity;

use pocketmine\entity\Animal;
use pocketmine\entity\Human;
use pocketmine\entity\Skin;
use pocketmine\level\Level;
use pocketmine\nbt\tag\CompoundTag;
use Zoumi\Core\Main;

class Purification extends Animal {

    public const NETWORK_ID = self::CHICKEN;

    public $width = 0.7;
    public $height = 0.7;

    public $changed = false;

    public function __construct(Level $level, CompoundTag $nbt)
    {
        parent::__construct($level, $nbt);

        $this->setNameTag("§l§6- §fZone de purification §6-");
        $this->setImmobile(true);
        $this->setScale(0.001);
    }

    public function getName(): string
    {
        return "Purification";
    }

    public function onUpdate(int $currentTick): bool
    {
        return true;
    }

}