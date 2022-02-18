<?php

namespace Zoumi\Core\tasks;

use pocketmine\network\mcpe\protocol\PlaySoundPacket;
use pocketmine\Player;
use pocketmine\scheduler\Task;
use pocketmine\Server;
use pocketmine\utils\BinaryStream;
use Zoumi\Core\Main;
use Zoumi\Core\api\Box;
use Zoumi\Core\Manager;

class VoteParty extends Task {

    public function onRun(int $currentTick)
    {
        if (Main::getManagerConfig()->get("votePartySkyblock") >= 150){
            $box = "";
            $rand = mt_rand(1, 100);
            if ($rand >= 1 && $rand < 70){
                $box = "§eFarming";
                foreach (Server::getInstance()->getOnlinePlayers() as $player){
                    if ($player instanceof Player){
                        $pk = new PlaySoundPacket();
                        $pk->soundName = "entity.elder_guardian.curse";
                        $pk->volume = 5;
                        $pk->pitch = 3;
                        $pk->x = $player->getX();
                        $pk->z = $player->getZ();
                        $pk->y = $player->getY();
                        $player->sendDataPacket($pk);
                        Box::addKey($player->getName(), "farming", 1);
                    }
                }
            }elseif ($rand >= 70 && $rand <= 100){
                $box = "§0Spawner";
                foreach (Server::getInstance()->getOnlinePlayers() as $player){
                    if ($player instanceof Player){
                        $pk = new PlaySoundPacket();
                        $pk->soundName = "entity.elder_guardian.curse";
                        $pk->volume = 5;
                        $pk->pitch = 3;
                        $pk->x = $player->getX();
                        $pk->z = $player->getZ();
                        $pk->y = $player->getY();
                        $player->sendDataPacket($pk);
                        Box::addKey($player->getName(), "spawner", 1);
                    }
                }
            }
            $config = Main::getManagerConfig();
            $config->set("votePartySkyblock", 0);
            $config->save();
            Server::getInstance()->broadcastMessage(Manager::PREFIX_INFOS . "Le VoteParty a été atteint, vous avez tous gagner §ex1 Clé {$box}§f.");
            return true;
        }
    }

}