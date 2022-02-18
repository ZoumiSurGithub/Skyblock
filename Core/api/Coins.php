<?php

namespace Zoumi\Core\api;

use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\Server;
use Zoumi\Core\DataBase;
use Zoumi\Core\Main;
use Zoumi\Core\tasks\async\MySQLAsync;

class Coins {

    public static function addCoins(string $player, float $coins){
        try {
            $res = DataBase::getData()->query("SELECT * from users WHERE pseudo='" . $player . "'");

            $calc = $res->fetch_array()["coins"] + $coins;

            $res->close();

            Main::getInstance()->getServer()->getAsyncPool()->submitTask(new MySQLAsync("UPDATE `users` set coins=" . $calc . " WHERE pseudo='" . $player . "'"));
        } catch (\mysqli_sql_exception $e) {

        }
        $player = Server::getInstance()->getPlayer($player);
        if ($player instanceof Player){
            Main::getInstance()->scoreboard[$player->getName()]
                ->setLine(3, Users::replace($player, "§6➥ §eCoins: §f{coins}\u{E102}"))
                ->set();
        }
    }

    public static function removeCoins(string $player, float $coins)
    {
        try {
            $res = DataBase::getData()->query("SELECT * from users WHERE pseudo='" . $player . "'");

            $calc = $res->fetch_array()["coins"] - $coins;

            Main::getInstance()->getServer()->getAsyncPool()->submitTask(new MySQLAsync("UPDATE users set coins='$calc' WHERE pseudo='" . $player . "'"));

            $res->close();

        } catch (\mysqli_sql_exception $e) {
            echo $e->getMessage();
        }
        $player = Server::getInstance()->getPlayer($player);
        if ($player instanceof Player){
            Main::getInstance()->scoreboard[$player->getName()]
                ->setLine(3, Users::replace($player, "§6➥ §eCoins: §f{coins}\u{E102}"))
                ->set();
        }
   }

   public static function setCoins(string $player, int $coins){
       try {

           Main::getInstance()->getServer()->getAsyncPool()->submitTask(new MySQLAsync("UPDATE users set coins='$coins' WHERE pseudo='" . $player . "'"));

       } catch (\mysqli_sql_exception $e) {
           echo $e->getMessage();
       }
       $player = Server::getInstance()->getPlayer($player);
       if ($player instanceof Player){
           Main::getInstance()->scoreboard[$player->getName()]
               ->setLine(3, Users::replace($player, "§6➥ §eCoins: §f{coins}\u{E102}"))
               ->set();
       }
   }

    /**
     * @param string $player
     * @return mixed
     */
    public static function getCoins(string $player){
        try {

            $res = DataBase::getData()->query("SELECT * from `users` WHERE pseudo='$player'");

            $row = $res->fetch_array();

            if ($res->num_rows > 0) {
                return $row['coins'];
            }

            $res->close();

        } catch (\mysqli_sql_exception $e) {
            echo $e->getMessage();
        }
    }

    public static function getTop(Player $player)
    {
        try{

            $res = DataBase::getData()->query("SELECT * FROM users ORDER BY coins desc LIMIT 10;");
            $ret = [];
            foreach ($res->fetch_all() as $val){
                $ret[$val[0]] = $val[1];
            }
            $player->sendMessage("§6- §fTop 10 des joueurs ayant le plus de \u{E102} §6-");
            $top = 1;
            foreach ($ret as $pseudo => $coins){
                $player->sendMessage("§f#§e$top §f- §6$pseudo §favec §e{$coins}§f\u{E102}");
                $top++;
            }
            return $ret;
        }catch (\mysqli_sql_exception $mySQLErrorException){

        }
    }

    public static function getTopConsole(CommandSender $player)
    {
        try{

            $res = DataBase::getData()->query("SELECT * FROM users ORDER BY coins desc LIMIT 10;");
            $ret = [];
            foreach ($res->fetch_all() as $val){
                $ret[$val[0]] = $val[1];
            }
            $player->sendMessage("§6- §fTop 10 des joueurs ayant le plus de coins §6-");
            $top = 1;
            foreach ($ret as $pseudo => $coins){
                $player->sendMessage("§f#§e$top §f- §6$pseudo §favec §e$coins §fcoins");
                $top++;
            }
            return $ret;
        }catch (\mysqli_sql_exception $mySQLErrorException){

        }
    }

}