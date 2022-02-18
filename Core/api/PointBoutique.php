<?php

namespace Zoumi\Core\api;

use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\Server;
use Zoumi\Core\DataBase;

class PointBoutique {

    public static function addPB(string $player, float $pb){
        try {
            $res = DataBase::getData()->query("SELECT * from users WHERE pseudo='" . $player . "'");

            $calc = $res->fetch_array()["pb"] + $pb;

            $res->close();

            Main::getInstance()->getServer()->getAsyncPool()->submitTask(new MySQLAsync("UPDATE `users` set pb=" . $calc . " WHERE pseudo='" . $player . "'"));
        } catch (\mysqli_sql_exception $e) {

        }
        $player = Server::getInstance()->getPlayer($player);
        if ($player instanceof Player){
            Main::getInstance()->scoreboard[$player->getName()]
                ->setLine(3, Users::replace($player, "§6➥ §eCoins: §f{coins}\u{E102}"))
                ->set();
        }
    }

    public static function removePB(string $player, float $pb)
    {
        try {
            $res = DataBase::getData()->query("SELECT * from users WHERE pseudo='" . $player . "'");

            $calc = $res->fetch_array()["pb"] - $pb;

            Main::getInstance()->getServer()->getAsyncPool()->submitTask(new MySQLAsync("UPDATE users set pb='$calc' WHERE pseudo='" . $player . "'"));

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

    public static function setPB(string $player, int $pb){
        try {

            Main::getInstance()->getServer()->getAsyncPool()->submitTask(new MySQLAsync("UPDATE users set pb='$pb' WHERE pseudo='" . $player . "'"));

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
    public static function getPB(string $player){
        try {

            $res = DataBase::getData()->query("SELECT * from `users` WHERE pseudo='$player'");

            $row = $res->fetch_array();

            if ($res->num_rows > 0) {
                return $row['pb'];
            }

            $res->close();

        } catch (\mysqli_sql_exception $e) {
            echo $e->getMessage();
        }
    }

    public static function getTop(Player $player)
    {
        try{

            $res = DataBase::getData()->query("SELECT * FROM users ORDER BY pb desc LIMIT 10;");
            $ret = [];
            foreach ($res->fetch_all() as $val){
                $ret[$val[0]] = $val[1];
            }
            $player->sendMessage("§6- §fTop 10 des joueurs ayant le plus de point boutique §6-");
            $top = 1;
            foreach ($ret as $pseudo => $pb){
                if ($pb > 1){
                    $player->sendMessage("§f#§e$top §f- §6$pseudo §favec §e{$pb}§f points boutique");
                }else {
                    $player->sendMessage("§f#§e$top §f- §6$pseudo §favec §e{$pb}§f point boutique");
                }
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