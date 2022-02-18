<?php

namespace Zoumi\Core\api;

use pocketmine\utils\Config;
use Zoumi\Core\Main;

class Box {

    public static function getKey(string $player, string $box): int{
        $config = new Config(Main::getInstance()->getDataFolder() . "box/{$box}.json", Config::JSON);
        if ($config->exists($player)){
            return $config->get($player);
        }
        return 0;
    }

    public static function addKey(string $player, string $box, int $count): void{
        $config = new Config(Main::getInstance()->getDataFolder() . "box/{$box}.json", Config::JSON);
        if ($config->exists($player)){
            $count = $config->get($player) + $count;
            $config->set($player, $count);
            $config->save();
            return;
        }
        $config->set($player, $count);
        $config->save();
        return;
    }

    public static function removeKey(string $player, string $box, int $count): void{
        $config = new Config(Main::getInstance()->getDataFolder() . "box/{$box}.json", Config::JSON);
        if ($config->exists($player)){
            $count = $config->get($player) - $count;
            $config->set($player, $count);
            $config->save();
            return;
        }
        return;
    }

}