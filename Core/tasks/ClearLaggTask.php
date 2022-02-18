<?php

namespace Zoumi\Core\tasks;

use pocketmine\entity\object\ItemEntity;
use pocketmine\network\mcpe\protocol\PlaySoundPacket;
use pocketmine\Player;
use pocketmine\scheduler\Task;
use pocketmine\Server;
use pocketmine\utils\BinaryStream;
use Zoumi\Core\Manager;

class ClearLaggTask extends Task {

    /** @var int $time */
    public $time = 300;
    /** @var int $entityClear */
    public $entityClear = 0;

    public function onRun(int $currentTick)
    {
        if (--$this->time === 0){
            foreach (Server::getInstance()->getLevels() as $level){
                foreach ($level->getEntities() as $entity){
                    if ($entity instanceof ItemEntity){
                        $entity->flagForDespawn();
                        ++$this->entityClear;
                    }
                }
            }
            Server::getInstance()->broadcastMessage(Manager::PREFIX_INFOS . "Le nettoyage automatique a nettoyé §e" . $this->entityClear . " §fentitée(s).");
            $this->time = 300;
            $this->entityClear = 0;
            foreach (Server::getInstance()->getOnlinePlayers() as $player){
                if ($player instanceof Player){
                    $sound = new PlaySoundPacket();
                    $sound->soundName = "note.bell";
                    $sound->pitch = 1;
                    $sound->volume = 1;
                    $sound->x = $player->getX();
                    $sound->y = $player->getY();
                    $sound->z = $player->getZ();
                    $player->sendDataPacket($sound);
                }
            }
        }elseif (in_array($this->time, [60, 30, 15, 10, 5, 4, 3, 2, 1])){
            foreach (Server::getInstance()->getOnlinePlayers() as $player){
                if ($player instanceof Player){
                    $sound = new PlaySoundPacket();
                    $sound->soundName = "note.chime";
                    $sound->pitch = 1;
                    $sound->volume = 1;
                    $sound->x = $player->getX();
                    $sound->y = $player->getY();
                    $sound->z = $player->getZ();
                    $player->sendDataPacket($sound);
                    $player->sendPopup(str_replace("{time}", $this->time, "§3- §fNettoyage automatique dans §e{time} §fseconde(s) §3-"));
                }
            }
        }
    }

}