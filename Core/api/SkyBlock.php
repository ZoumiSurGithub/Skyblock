<?php

namespace Zoumi\Core\api;

use mysql_xdevapi\Exception;
use pocketmine\level\Level;
use pocketmine\level\Position;
use pocketmine\Player;
use pocketmine\Server;
use Zoumi\Core\DataBase;
use Zoumi\Core\Main;
use Zoumi\Core\tasks\async\MySQLAsync;

class SkyBlock {

    /** Creation, suppression, définir */
    public static function createIsland(string $player, string $island){
        try {

            Main::getInstance()->getServer()->getAsyncPool()->submitTask(new MySQLAsync("UPDATE users SET island='$island' WHERE pseudo='$player'"));
            Main::getInstance()->getServer()->getAsyncPool()->submitTask(new MySQLAsync("INSERT INTO islands (island, leader, officer, member, x, y, z) VALUES ('$island', '$player', '', '', '-423.5', '26', '-360.5')"));
            Main::getInstance()->getServer()->getAsyncPool()->submitTask(new MySQLAsync("INSERT INTO settings (island, locked, damage) VALUES ('$island','1','1')"));

        }catch (\mysqli_sql_exception $exception){

        }
    }

    public static function setIsland(string $player, string $island)
    {
        try {

            Main::getInstance()->getServer()->getAsyncPool()->submitTask(new MySQLAsync("UPDATE users SET island='$island' WHERE pseudo='$player'"));

            $res = DataBase::getData()->query("SELECT * FROM islands WHERE island='$island'");

            $row = $res->fetch_array();

            $members = [];

            if (!empty($row["member"])) {
                $member = explode(", ", $row["member"]);
                for ($i = 0;$i < count($member);$i++){
                    $members[] = $member[$i];
                }
                $members[] = $player;
                Main::getInstance()->getServer()->getAsyncPool()->submitTask(new MySQLAsync("UPDATE islands SET member='" . implode(", ", $members) . "' WHERE island='$island'"));
            }else{
                $members = $player;
                Main::getInstance()->getServer()->getAsyncPool()->submitTask(new MySQLAsync("UPDATE islands SET member='" . $members . "' WHERE island='$island'"));
            }

        } catch (\mysqli_sql_exception $exception) {

        }
        $player = Server::getInstance()->getPlayer($player);
        if ($player instanceof Player) {
            Main::getInstance()->scoreboard[$player->getName()]
                ->setLine(5, Users::replace($player, "§6➥ §eNom: §f$island"))
                ->setLine(6, Users::replace($player, "§6➥ §ePoint(s): §f" . SkyBlock::getPoint($island)))
                ->set();
        }
    }

    public static function removeIsland(string $island)
    {
        try {

            $res = DataBase::getData()->query("SELECT * FROM users");

            $row = $res->fetch_all();

            $ret = [];

            foreach ($row as $value) {
                if ($value[3] === $island) {
                    $ret[] = $value[0];
                }
            }

            foreach ($ret as $player) {
                Main::getInstance()->getServer()->getAsyncPool()->submitTask(new MySQLAsync("UPDATE users SET island='' WHERE pseudo='" . $player . "'"));
            }

            unset($ret);

            Main::getInstance()->getServer()->getAsyncPool()->submitTask(new MySQLAsync("DELETE FROM islands WHERE island='$island'"));
            Main::getInstance()->getServer()->getAsyncPool()->submitTask(new MySQLAsync("DELETE FROM settings WHERE island='$island'"));

        } catch (\mysqli_sql_exception $exception) {

        }
        foreach (Server::getInstance()->getOnlinePlayers() as $player) {
            if (SkyBlock::getIslandName($player->getName()) === $island) {
                $player = Server::getInstance()->getPlayer($player);
                if ($player instanceof Player) {
                    Main::getInstance()->scoreboard[$player->getName()]
                        ->setLine(5, Users::replace($player, "§6➥ §eNom: §fAucun"))
                        ->setLine(6, Users::replace($player, "§6➥ §ePoint(s): §f0"))
                        ->set();
                }
            }
        }
    }

    public static function leaveIsland(string $player, string $island)
    {
        try {

            Main::getInstance()->getServer()->getAsyncPool()->submitTask(new MySQLAsync("UPDATE users SET island='' WHERE pseudo='$player'"));

            if (SkyBlock::isOfficer(Server::getInstance()->getPlayerExact($player), $island)) {
                Main::getInstance()->getServer()->getAsyncPool()->submitTask(new MySQLAsync("UPDATE islands SET officer='' WHERE island='$island'"));
                return;
            } elseif (SkyBlock::isMember($player, $island)) {
                $res = DataBase::getData()->query("SELECT * FROM islands WHERE island='$island'");

                $row = $res->fetch_array();

                $members = [];

                if (!empty($row["member"])) {
                    $mem = explode(", ", $row["member"]);
                    foreach ($mem as $member) {
                        if ($member !== $player) {
                            $members[] = $member;
                        }
                    }
                }

                Main::getInstance()->getServer()->getAsyncPool()->submitTask(new MySQLAsync("UPDATE islands SET member='" . implode(", ", $members) . "' WHERE island='$island'"));
            }

        } catch (\mysqli_sql_exception $exception) {

        }
        $player = Server::getInstance()->getPlayer($player);
        if ($player instanceof Player) {
            Main::getInstance()->scoreboard[$player->getName()]
                ->setLine(5, Users::replace($player, "§6➥ §eNom: §fAucun"))
                ->setLine(6, Users::replace($player, "§6➥ §ePoint(s): §f0"))
                ->set();
        }
    }

    /** SetSpawn, locked, unlocked */
    public static function setSpawn(Level $level, Player $player){
        try {

            $x = $player->getX();

            $y = $player->getY();

            $z = $player->getZ();

            Server::getInstance()->getAsyncPool()->submitTask(new MySQLAsync("UPDATE islands SET x='$x', y='$y', z='$z' WHERE island='" . $level->getFolderName() . "'"));

            $level->setSpawnLocation(new Position($x, $y, $z, $level));

        }catch (\mysqli_sql_exception $exception){

        }
    }

    public static function getSpawn(string $island, $value = 0): Position{
        try {

            $res = DataBase::getData()->query("SELECT * FROM islands WHERE island='$island'");

            $row = $res->fetch_array();

            if (!empty($row["x"])){
                if ($value === 0) {
                    return new Position($row["x"], $row["y"], $row["z"], Server::getInstance()->getLevelByName($island));
                }elseif ($value === 1){
                    return new Position($row["x"], $row["y"] - 1, $row["z"], Server::getInstance()->getLevelByName($island));
                }
            }else{
                return new Position(-423.5, 26, -360.5, Server::getInstance()->getLevelByName($island));
            }

        }catch (\mysqli_sql_exception $exception){

        }
    }

    /** Vérification */
    public static function hasIsland(string $player): bool
    {
        try {

            $res = DataBase::getData()->query("SELECT * FROM users WHERE pseudo='$player'");

            $row = $res->fetch_array();

            if ($row["island"] == "" or empty($row["island"])){
                return false;
            }

            return true;

        }catch (\mysqli_sql_exception $exception){

        }
    }

    public static function isLeader(Player $player, string $island): bool
    {
        try {

            $res = DataBase::getData()->query("SELECT * FROM islands WHERE island='$island'");

            $row = $res->fetch_array();

            if ($row["leader"] == $player->getName()){
                return true;
            }else {
                return false;
            }

        }catch (\mysqli_sql_exception $exception){

        }
    }

    public static function isOfficer(Player $player, string $island): bool
    {
        try {

            $res = DataBase::getData()->query("SELECT * FROM islands WHERE island='$island'");

            $row = $res->fetch_array();

            if ($row["officer"] == $player->getName()){
                return true;
            }else {
                return false;
            }

        }catch (\mysqli_sql_exception $exception){

        }
    }

    public static function isMember(string $player, string $island): bool
    {
        try {

            $res = DataBase::getData()->query("SELECT * FROM islands WHERE island='$island'");

            $row = $res->fetch_array();

            if (!empty($row["member"])){
                $mem = explode(", ", $row["member"]);
                if (in_array($player, $mem)){
                    return true;
                }
            }
            return false;

        }catch (\mysqli_sql_exception $exception){

        }
    }

    public static function isIsland(string $island): bool{
        try {

            $res = DataBase::getData()->query("SELECT * FROM islands WHERE island='$island'");

            if (!empty($res->fetch_array()["island"])){
                return true;
            }

            return false;

        }catch (\mysqli_sql_exception $exception){

        }
    }

    /** Utile */
    public static function getIslandName(string $player){
        try {

            $res = DataBase::getData()->query("SELECT * FROM users WHERE pseudo='$player'");

            return $res->fetch_array()["island"];

        }catch (\mysqli_sql_exception $exception){

        }
    }
    public static function getLeader(string $island){
        try {

            $res = DataBase::getData()->query("SELECT * FROM islands WHERE island='$island'");

            return $res->fetch_array()["leader"];

        }catch (\mysqli_sql_exception $exception){

        }
    }

    public static function getOfficer(string $island){
        try {

            $res = DataBase::getData()->query("SELECT * FROM islands WHERE island='$island'");

            return $res->fetch_array()["officer"];

        }catch (\mysqli_sql_exception $exception){

        }
    }

    public static function getMember(string $island){
        try {

            $res = DataBase::getData()->query("SELECT * FROM islands WHERE island='$island'");

            return $res->fetch_array()["member"];

        }catch (\mysqli_sql_exception $exception){

        }
    }

    public static function verif_alpha($str){
        // On cherche tt les caractères autre que [A-z]
        preg_match("/([^A-Za-z\s])/",$str,$result);
        // si on trouve des caractère autre que A-z
        if(!empty($result)){
            return false;
        }
        return true;
    }

    public static function islandExist(string $island): bool{
        try {

            $res = DataBase::getData()->query("SELECT * FROM islands WHERE island='$island'");

            $row = $res->fetch_array();

            if (empty($row)){
                return false;
            }

            return true;

        }catch (\mysqli_sql_exception $exception){

        }
    }

    public static function broadcastMemberIsland(string $island, string $message){
        foreach (Server::getInstance()->getOnlinePlayers() as $player){
            if (SkyBlock::hasIsland($player->getName())){
                if (SkyBlock::getIslandName($player->getName()) === $island){
                    $player->sendMessage("§f(§dSkyBlock §f- §2{$island}§f) $message");
                }
            }
        }
    }

    public static function getCount(string $island): int{
        try {

            $res = DataBase::getData()->query("SELECT * FROM islands WHERE island='$island'");

            $row = $res->fetch_array();

            $count = 1;

            if (!empty($row["officer"]) or $row["officer"] !== ""){
                $count++;
            }

            if (!empty($row["member"]) or $row["member"] !== ""){
                $member = explode(", ", $row["member"]);
                foreach ($member as $value){
                    $count++;
                }
            }

            return $count;
        }catch (\mysqli_sql_exception $exception){

        }
    }

    /** Point */
    public static function addPoint(string $island, int $points){
        try {

            $res = DataBase::getData()->query("SELECT * FROM islands WHERE island='$island'");

            $calc = $res->fetch_array()["points"] + $points;

            Main::getInstance()->getServer()->getAsyncPool()->submitTask(new MySQLAsync("UPDATE islands SET points='$calc' WHERE island='$island'"));

        }catch (\mysqli_sql_exception $exception){

        }
        foreach (Server::getInstance()->getOnlinePlayers() as $player) {
            $player = Server::getInstance()->getPlayer($player);
            if ($player instanceof Player) {
                Main::getInstance()->scoreboard[$player->getName()]
                    ->setLine(6, Users::replace($player, "§6➥ §ePoint(s): §f" . SkyBlock::getPoint($island)))
                    ->set();
            }
        }
    }

    public static function setPoint(string $island, int $points){
        try {

            $res = DataBase::getData()->query("SELECT * FROM islands WHERE island='$island'");

            Main::getInstance()->getServer()->getAsyncPool()->submitTask(new MySQLAsync("UPDATE islands SET points='$points' WHERE island='$island'"));

        }catch (\mysqli_sql_exception $exception){

        }
        foreach (Server::getInstance()->getOnlinePlayers() as $player) {
            $player = Server::getInstance()->getPlayer($player);
            if ($player instanceof Player) {
                Main::getInstance()->scoreboard[$player->getName()]
                    ->setLine(6, Users::replace($player, "§6➥ §ePoint(s): §f" . SkyBlock::getPoint($island)))
                    ->set();
            }
        }
    }

    public static function removePoint(string $island, int $points = 1){
        try {

            $res = DataBase::getData()->query("SELECT * FROM islands WHERE island='$island'");

            $calc = $res->fetch_array()["points"] - $points;


            Main::getInstance()->getServer()->getAsyncPool()->submitTask(new MySQLAsync("UPDATE islands SET points='$calc' WHERE island='$island'"));

        }catch (\mysqli_sql_exception $exception){

        }
        foreach (Server::getInstance()->getOnlinePlayers() as $player) {
            $player = Server::getInstance()->getPlayer($player);
            if ($player instanceof Player) {
                Main::getInstance()->scoreboard[$player->getName()]
                    ->setLine(6, Users::replace($player, "§6➥ §ePoint(s): §f" . SkyBlock::getPoint($island)))
                    ->set();
            }
        }
    }

    public static function getPoint(string $island): int{
        try {

            $res = DataBase::getData()->query("SELECT * FROM islands WHERE island='$island'");

            $row = $res->fetch_array();

            if (empty($row["points"])){
                return 0;
            }

            return $row["points"];

        }catch (\mysqli_sql_exception $exception){

        }
    }

    public static function getTopPoints(Player $player)
    {
        try{

            $res = DataBase::getData()->query("SELECT * FROM islands ORDER BY points desc LIMIT 10;");
            $ret = [];
            foreach ($res->fetch_all() as $val){
                $ret[$val[0]] = $val[4];
            }
            $player->sendMessage("§6- §fTop 10 des îles ayant le plus de point §6-");
            $top = 1;
            foreach ($ret as $island => $points){
                if ($points > 1) {
                    $player->sendMessage("§f#§e$top §f- §6$island §favec §e{$points} §fpoints");
                }else{
                    $player->sendMessage("§f#§e$top §f- §6$island §favec §e{$points} §fpoint");
                }
                $top++;
            }
            return $ret;
        }catch (\mysqli_sql_exception $mySQLErrorException){

        }
    }

    /** Vote */
    public static function addVote(string $island, int $vote){
        try {

            $res = DataBase::getData()->query("SELECT * FROM islands WHERE island='$island'");

            $calc = $res->fetch_array()["vote"] + $vote;

            Main::getInstance()->getServer()->getAsyncPool()->submitTask(new MySQLAsync("UPDATE islands SET vote='$calc' WHERE island='$island'"));

        }catch (\mysqli_sql_exception $exception){

        }
    }

    public static function setVote(string $island, int $vote){
        try {

            $res = DataBase::getData()->query("SELECT * FROM islands WHERE island='$island'");

            Main::getInstance()->getServer()->getAsyncPool()->submitTask(new MySQLAsync("UPDATE islands SET vote='$vote' WHERE island='$island'"));

        }catch (\mysqli_sql_exception $exception){

        }
    }

    public static function removeVote(string $island, int $vote){
        try {

            $res = DataBase::getData()->query("SELECT * FROM islands WHERE island='$island'");

            $calc = $res->fetch_array()["vote"] - $vote;


            Main::getInstance()->getServer()->getAsyncPool()->submitTask(new MySQLAsync("UPDATE islands SET vote='$calc' WHERE island='$island'"));

        }catch (\mysqli_sql_exception $exception){

        }
    }

    public static function getVote(string $island): int{
        try {

            $res = DataBase::getData()->query("SELECT * FROM islands WHERE island='$island'");

            $row = $res->fetch_array();

            if (empty($row["vote"])){
                return 0;
            }

            return $row["vote"];

        }catch (\mysqli_sql_exception $exception){

        }
    }

    public static function getTopVote(Player $player)
    {
        try{

            $res = DataBase::getData()->query("SELECT * FROM islands ORDER BY vote desc LIMIT 10;");
            $ret = [];
            foreach ($res->fetch_all() as $val){
                $ret[$val[0]] = $val[6];
            }
            $player->sendMessage("§6- §fTop 10 des îles ayant le plus de vote §6-");
            $top = 1;
            foreach ($ret as $island => $vote){
                if ($vote > 1) {
                    $player->sendMessage("§f#§e$top §f- §6$island §favec §e{$vote} §fvotes");
                }else{
                    $player->sendMessage("§f#§e$top §f- §6$island §favec §e{$vote} §fvote");
                }
                $top++;
            }
            return $ret;
        }catch (\mysqli_sql_exception $mySQLErrorException){

        }
    }

    /** Lock Unlock */
    public static function setLocked(string $island, $value = true){
        try {
            if ($value === true) {
                Main::getInstance()->getServer()->getAsyncPool()->submitTask(new MySQLAsync("UPDATE settings SET locked='1' WHERE island='$island'"));
            }elseif ($value === false){
                Main::getInstance()->getServer()->getAsyncPool()->submitTask(new MySQLAsync("UPDATE settings SET locked='0' WHERE island='$island'"));
            }

        }catch (\mysqli_sql_exception $exception){

        }
    }

    public static function isLocked(string $island): bool{
        try {

            $res = DataBase::getData()->query("SELECT * FROM settings WHERE island='$island'");

            $row = $res->fetch_array();

            return $row["locked"];

        }catch (\mysqli_sql_exception $exception){

        }
    }

    public static function setDamage(string $island, $value = true){
        try {
            if ($value === true) {
                Main::getInstance()->getServer()->getAsyncPool()->submitTask(new MySQLAsync("UPDATE settings SET damage='1' WHERE island='$island'"));
            }elseif ($value === false){
                Main::getInstance()->getServer()->getAsyncPool()->submitTask(new MySQLAsync("UPDATE settings SET damage='0' WHERE island='$island'"));
            }

        }catch (\mysqli_sql_exception $exception){

        }
    }

    public static function isDamage(string $island): bool{
        try {

            $res = DataBase::getData()->query("SELECT * FROM settings WHERE island='$island'");

            $row = $res->fetch_array();

            return $row["damage"];

        }catch (\mysqli_sql_exception $exception){

        }
    }

}