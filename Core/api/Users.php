<?php

namespace Zoumi\Core\api;

use pocketmine\nbt\BigEndianNBTStream;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\Player;
use pocketmine\Server;
use Zoumi\Core\DataBase;
use Zoumi\Core\Main;
use Zoumi\Core\tasks\async\MySQLAsync;

class Users {

    public static function haveAccount(string $player): bool{
        $result = DataBase::getData()->query("SELECT pseudo FROM `users` WHERE pseudo='" . $player . "'");
        DataBase::getData()->close();
        return $result->num_rows > 0 ? true : false;
    }

    /**
     * @param Player $player
     */
    public static function createAccount(Player $player){
        try {

            Main::getInstance()->getServer()->getAsyncPool()->submitTask(new MySQLAsync("INSERT INTO `users` (pseudo, coins, pb, island, prefix) VALUES ('" . $player->getName() . "', '0', '0', '', '')"));

            Main::getInstance()->getServer()->getAsyncPool()->submitTask(new MySQLAsync("INSERT INTO `jobs` (`pseudo`, `farmer_lvl`, `miner_lvl`, `chasseur_lvl`, `bucheron_lvl`) VALUES ('" . $player->getName() . "', '0', '0', '0', '0')"));

        }catch (\mysqli_sql_exception $exception){

        }
    }

    public static function replace(Player $player, string $message){
        $pure = Server::getInstance()->getPluginManager()->getPlugin("PurePerms");
        $message = str_replace(["{pseudo}", "{displayName}", "{group}", "{prefix}", "{coins}", "{coin}", "{islandName}", "{islandPoint}", "{pb}"], [$player->getName(), $player->getDisplayName(), $pure->getUserDataMgr()->getGroup($player), (Users::getPrefix($player->getName()) ?? "Aucun"), Coins::getCoins($player->getName()), "\u{E102}", (SkyBlock::getIslandName($player->getName()) ? SkyBlock::getIslandName($player->getName()) : "Aucun"), (SkyBlock::getPoint(SkyBlock::getIslandName($player->getName())) ? SkyBlock::getPoint(SkyBlock::getIslandName($player->getName())) : "0"), PointBoutique::getPB($player->getName())], $message);
        return $message;
    }

    public static function copyWorld(string $name): bool{
        @mkdir("/home/ares/skyblock/worlds/$name/");
        @mkdir("/home/ares/skyblock/worlds/$name/region/");
        copy("/home/ares/skyblock/worlds/schema/level.dat", "/home/ares/skyblock/worlds/$name/level.dat");
        $levelPath = "/home/ares/skyblock/worlds/schema/level.dat";
        $levelPath = "/home/ares/skyblock/worlds/$name/level.dat";

        $nbt = new BigEndianNBTStream();
        $levelData = $nbt->readCompressed(file_get_contents($levelPath));
        $levelData = $levelData->getCompoundTag("Data");
        $oldName = $levelData->getString("LevelName");
        $levelData->setString("LevelName", $name);
        $nbt = new BigEndianNBTStream();
        file_put_contents($levelPath, $nbt->writeCompressed(new CompoundTag("", [$levelData])));
        self::copy_directory("/home/ares/skyblock/worlds/schema/region/", "/home/ares/skyblock/worlds/$name/region/");
        return true;
    }
    
    private static function copy_directory($src,$dst) {
        $dir = opendir($src);
        @mkdir($dst);
        while(false !== ( $file = readdir($dir)) ) {
            if (( $file != '.' ) && ( $file != '..' )) {
                if ( is_dir($src . '/' . $file) ) {
                    self::copy_directory($src . '/' . $file,$dst . '/' . $file);
                }
                else {
                    copy($src . '/' . $file,$dst . '/' . $file);
                }
            }
        }
        closedir($dir);
    }

    public static function removeDir($strDirectory){
        $handle = opendir($strDirectory);
        while(false !== ($entry = readdir($handle))){
            if($entry != '.' && $entry != '..'){
                if(is_dir($strDirectory.'/'.$entry)){
                    Users::removeDir($strDirectory.'/'.$entry);
                }
                elseif(is_file($strDirectory.'/'.$entry)){
                    unlink($strDirectory.'/'.$entry);
                }
            }
        }
        rmdir($strDirectory.'/'.$entry);
        closedir($handle);
    }

    public static function getPrefix(string $player): string{
        try {

            $res = DataBase::getData()->query("SELECT * FROM users WHERE pseudo='$player'");

            $row = $res->fetch_array();

            if (empty($row["prefix"])){
                return '';
            }
            return $row["prefix"];

        }catch (\mysqli_sql_exception $exception){

        }
    }

    public static function setPrefix(Player $player, string $prefix){
        try {

            Main::getInstance()->getServer()->getAsyncPool()->submitTask(new MySQLAsync("UPDATE users SET prefix='$prefix' WHERE pseudo='" . $player->getName() . "'"));

        }catch (\mysqli_sql_exception $exception){

        }
        Main::getInstance()->cache[$player->getName()]["prefix"] = $prefix;
    }

}