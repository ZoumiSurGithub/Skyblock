<?php

namespace Zoumi\Core\tasks;

use pocketmine\Player;
use pocketmine\scheduler\Task;
use pocketmine\Server;
use Zoumi\Core\Main;

class XYZTask extends Task {

    public function onRun(int $currentTick)
    {
        if (empty(Main::getInstance()->xyz)) return false;
        foreach (Main::getInstance()->xyz as $player){
            $player = Server::getInstance()->getPlayer($player);
            if ($player instanceof Player){
                if ($player->isOnline()){
                    $player->sendPopup("§6« §7X: §e" . $player->getX() . " §7Y: §e" . $player->getY() . " §7Z: §e" . $player->getZ() . " §6»\n§6« §7Monde: §e" . $player->getLevel()->getFolderName() . " §6»");
                }else unset(Main::getInstance()->xyz[$player->getName()]);
            }else unset(Main::getInstance()->xyz[$player]);
        }
    }

}