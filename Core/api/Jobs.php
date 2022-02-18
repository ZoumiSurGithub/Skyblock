<?php

namespace Zoumi\Core\api;

use Cassandra\Date;
use pocketmine\event\server\DataPacketReceiveEvent;
use Zoumi\Core\DataBase;
use Zoumi\Core\Main;
use Zoumi\Core\tasks\async\MySQLAsync;

class Jobs {

    /** Add set remove */
    public static function addXpForJob(string $player, string $job, float $xp){
        try {

            $res = DataBase::getData()->query("SELECT * FROM jobs WHERE pseudo='$player'");

            $row = $res->fetch_array();

            $calc = $row[$job . "_xp"] + $xp;

            Main::getInstance()->getServer()->getAsyncPool()->submitTask(new MySQLAsync("UPDATE jobs SET " . $job . "_xp" . "='$calc' WHERE pseudo='$player'"));

        }catch (\mysqli_sql_exception $exception){

        }
    }

    public static function removeXpForJob(string $player, string $job, float $xp){
        try {

            $res = DataBase::getData()->query("SELECT * FROM jobs WHERE pseudo='$player'");

            $row = $res->fetch_array();

            $calc = $row[$job . "_xp"] - $xp;

            Main::getInstance()->getServer()->getAsyncPool()->submitTask(new MySQLAsync("UPDATE jobs SET " . $job . "_xp" . "='$calc' WHERE pseudo='$player'"));

        }catch (\mysqli_sql_exception $exception){

        }
    }

    public static function setXpForJob(string $player, string $job, float $xp){
        try {

            Main::getInstance()->getServer()->getAsyncPool()->submitTask(new MySQLAsync("UPDATE jobs SET " . $job . "_xp" . "='$xp' WHERE pseudo='$player'"));

        }catch (\mysqli_sql_exception $exception){

        }
    }

    public static function addLevelForJob(string $player, string $job, int $level){
        try {

            $res = DataBase::getData()->query("SELECT * FROM jobs WHERE pseudo='$player'");

            $row = $res->fetch_array();

            $calc = $row[$job . "_lvl"] + $level;

            Main::getInstance()->getServer()->getAsyncPool()->submitTask(new MySQLAsync("UPDATE jobs SET " . $job . "_lvl" . "='$calc' WHERE pseudo='$player'"));

        }catch (\mysqli_sql_exception $exception){

        }
    }

    public static function removeLevelForJob(string $player, string $job, int $level){
        try {

            $res = DataBase::getData()->query("SELECT * FROM jobs WHERE pseudo='$player'");

            $row = $res->fetch_array();

            $calc = $row[$job . "_lvl"] - $level;

            Main::getInstance()->getServer()->getAsyncPool()->submitTask(new MySQLAsync("UPDATE jobs SET " . $job . "_lvl" . "='$calc' WHERE pseudo='$player'"));

        }catch (\mysqli_sql_exception $exception){

        }
    }

    public static function setLevelForJob(string $player, string $job, int $level){
        try {

            Main::getInstance()->getServer()->getAsyncPool()->submitTask(new MySQLAsync("UPDATE jobs SET " . $job . "_lvl" . "='$level' WHERE pseudo='$player'"));

        }catch (\mysqli_sql_exception $exception){

        }
    }

    /** Verif & Get */
    public static function getXpRequireForNextLevel(string $player, string $job){
        $res = DataBase::getData()->query("SELECT * FROM jobs WHERE pseudo='$player'");

        $row = $res->fetch_array();

        if ($row[$job . "_lvl"] === 0){
            return 1500;
        }

        return (($row[$job . "_lvl"] + 1) * 1500);
    }

    public static function getXpForJob(string $player, string $job){
        try {

            $res = DataBase::getData()->query("SELECT * FROM jobs WHERE pseudo='$player'");

            $row = $res->fetch_array();

            return $row[$job . "_xp"];

        }catch (\mysqli_sql_exception $exception){

        }
    }

    public static function getNextLevelForJob(string $player, string $job): int{
        try {

            $res = DataBase::getData()->query("SELECT * FROM jobs WHERE pseudo='$player'");

            $row = $res->fetch_array();

            $job = $job . "_lvl";

            return $row[$job] + 1;

        }catch (\mysqli_sql_exception $exception){

        }
    }

    public static function getLevelForJob(string $player, string $job): int{
        try {

            $res = DataBase::getData()->query("SELECT * FROM jobs WHERE pseudo='$player'");

            $row = $res->fetch_array();

            $job = $job . "_lvl";

            return $row[$job];

        }catch (\mysqli_sql_exception $exception){

        }
    }

}