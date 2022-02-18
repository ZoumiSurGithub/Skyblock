<?php

namespace Zoumi\Core\tasks;

use pocketmine\scheduler\Task;
use Zoumi\Core\Main;

class Bourse extends Task {

    public function onRun(int $currentTick)
    {
        if (date("N H:i:s") === "1 00:00:00"){
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
        }
    }

}