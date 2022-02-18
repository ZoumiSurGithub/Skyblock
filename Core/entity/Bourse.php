<?php

namespace Zoumi\Core\entity;

use pocketmine\entity\Animal;
use pocketmine\entity\Human;
use pocketmine\entity\Skin;
use pocketmine\level\Level;
use pocketmine\nbt\tag\CompoundTag;
use Zoumi\Core\Main;

class Bourse extends Animal {

    public const NETWORK_ID = self::CHICKEN;

    public $width = 0.7;
    public $height = 0.7;

    public $changed = false;

    public function __construct(Level $level, CompoundTag $nbt)
    {
        parent::__construct($level, $nbt);
    }

    public function getName(): string
    {
        return "Bourse";
    }

    public function onUpdate(int $currentTick): bool
    {
        $this->setScale(0.001);
        $this->setImmobile(true);
        $this->setNameTagAlwaysVisible(true);
        if (!Main::getInstance()->bourse->exists("cactus")) {
            $cactus = mt_rand(1, 3);
            $nether = mt_rand(6, 8);
            $patate = mt_rand(1, 6);
            $carotte = mt_rand(1, 6);
            $canne = mt_rand(1, 7);
            $ble = mt_rand(1, 4);
            $config = Main::getInstance()->bourse;
            $config->set("cactus", $cactus);
            $config->set("verrue", $nether);
            $config->set("ble", $ble);
            $config->set("carotte", $carotte);
            $config->set("patate", $patate);
            $config->set("canne", $canne);
            $config->save();
            $nametag =
                "§6- §fBourse Hebdomadaire §6-\n\n" .
                "§eCactus §f- {$cactus}\u{E102}\n" .
                "§eVerrue du nether §f- {$nether}\u{E102}\n" .
                "§eBlé §f- {$ble}\u{E102}\n" .
                "§ePatate §f- {$patate}\u{E102}\n" .
                "§eCarotte §f- {$carotte}\u{E102}\n" .
                "§eCanne à sucre §f- {$carotte}\u{E102}";
            $this->changed = true;
            return true;
        }
        $nametag =
            "§6- §fBourse Hebdomadaire §6-\n\n" .
            "§eCactus §f- " . Main::getInstance()->bourse->get("cactus") . "\u{E102}\n" .
            "§eVerrue du nether §f- " . Main::getInstance()->bourse->get("verrue") . "\u{E102}\n" .
            "§eBlé §f- " . Main::getInstance()->bourse->get("ble") . "\u{E102}\n" .
            "§ePatate §f- " . Main::getInstance()->bourse->get("patate") . "\u{E102}\n" .
            "§eCarotte §f- " . Main::getInstance()->bourse->get("carotte"). "\u{E102}\n" .
            "§eCanne à sucre §f- " . Main::getInstance()->bourse->get("canne") . "\u{E102}";
        $this->setNameTag($nametag);
        return true;
    }

}