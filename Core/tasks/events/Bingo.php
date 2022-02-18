<?php

namespace Zoumi\Core\tasks\events;

use pocketmine\scheduler\Task;
use pocketmine\Server;

class Bingo extends Task {

    /** @var int $time */
    public static $time = 7200;
    /** @var int $number */
    public static $number = 0;
    /** @var bool $isEnable */
    public static $isEnable = false;
    /** @var bool $isFinish */
    public static $isFinish = true;
    /** @var int $timeTofinish */
    public static $timeTofinish = 60;

    public function onRun(int $currentTick)
    {
        if (self::$isFinish) {
            if (--self::$time === 0) {
                $rand = mt_rand(0, 50);
                self::$number = $rand;
                Server::getInstance()->broadcastMessage("§7(§e!!§7) §fQue le bingo commence ! Petit indice ! Le nombre est entre §e0 §fet §e50§f. La récompense est de 2 000\u{E102}.");
                self::$isEnable = true;
                self::$isFinish = false;
            } elseif (self::$time === 60) {
                Server::getInstance()->broadcastMessage("§7(§e!!§7) §fLe bingo commence dans §e60 §fsecondes.");
            } elseif (self::$time === 30) {
                Server::getInstance()->broadcastMessage("§7(§e!!§7) §fLe bingo commence dans §e30 §fsecondes.");
            } elseif (self::$time === 15) {
                Server::getInstance()->broadcastMessage("§7(§e!!§7) §fLe bingo commence dans §e15 §fsecondes.");
            } elseif (self::$time === 10) {
                Server::getInstance()->broadcastMessage("§7(§e!!§7) §fLe bingo commence dans §e10 §fsecondes.");
            } elseif (self::$time === 5) {
                Server::getInstance()->broadcastMessage("§7(§e!!§7) §fLe bingo commence dans §e5 §fsecondes.");
            }
        }else{
            if (--self::$timeTofinish === 0){
                Server::getInstance()->broadcastMessage("§7(§e!!§7) §fLe bingo est terminer ! Malheureusement il y a eu aucun vainqueur.");
                self::$isEnable = false;
                self::$isFinish = true;
                self::$time = 7200;
            }
        }
    }

}