<?php

namespace Zoumi\Core\entity;

use pocketmine\entity\Animal;
use pocketmine\level\Level;
use pocketmine\nbt\tag\CompoundTag;
use Zoumi\Core\DataBase;
use Zoumi\Core\Main;

class TopCoins extends Animal {

    public const NETWORK_ID = self::CHICKEN;

    public $width = 0.7;
    public $height = 0.7;

    private $time = 0;

    public function __construct(Level $level, CompoundTag $nbt)
    {
        parent::__construct($level, $nbt);

        $this->setNameTag("§l§6- §fTop 10 des joueurs ayant le plus de \u{E102} §6-");
        $this->setScale(0.001);
        $this->setImmobile(true);
    }

    public function getName(): string
    {
        return "TopCoins";
    }

    public function onUpdate(int $currentTick): bool
    {
        if (--$this->time <= 0){
            $namedtag = "§l§6- §fTop 10 des joueurs ayant le plus de \u{E102} §6-\n\n";
            $res = DataBase::getData()->query("SELECT * FROM users ORDER BY coins desc LIMIT 10;");
            $ret = [];
            foreach ($res->fetch_all() as $val){
                $ret[$val[0]] = $val[1];
            }
            $top = 1;
            foreach ($ret as $pseudo => $coins){
                if ($top === 10){
                    $namedtag .= "§e#§f{$top} §6{$pseudo} §favec §e{$coins}\u{E102}";
                    break;
                }
                $namedtag .= "§e#§f{$top} §6{$pseudo} §favec §e{$coins}\u{E102}\n";
                $top++;
            }
            $this->setNameTag($namedtag);
            $this->setScale(0.001);
            $this->setImmobile(true);
            $this->time = 20 * 60;
            return true;
        }
        return true;
    }

}