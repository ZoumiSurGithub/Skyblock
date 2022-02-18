<?php

namespace Zoumi\Core\commands\ranked;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use Zoumi\Core\api\SkyBlock;
use Zoumi\Core\Main;
use Zoumi\Core\Manager;

class Fly extends Command {

    public function __construct(string $name, string $description = "", string $usageMessage = null, array $aliases = [])
    {
        parent::__construct($name, $description, $usageMessage, $aliases);
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        if ($sender instanceof Player){
            if (Main::getInstance()->fly->exists($sender->getName()) or $sender->hasPermission("use.fly")){
                if ($sender->getLevel()->getName() === SkyBlock::getIslandName($sender->getName())){
                    if ($sender->getAllowFlight()){
                        unset(Main::getInstance()->flight[$sender->getName()]);
                        $sender->setAllowFlight(false);
                        $sender->sendMessage(Manager::PREFIX_INFOS . "Vous ne pouvez désormais plus volé.");
                        return;
                    }else{
                        Main::getInstance()->flight[] = $sender->getName();
                        $sender->setAllowFlight(true);
                        $sender->sendMessage(Manager::PREFIX_INFOS . "Vous pouvez désormais volé.");
                        return;
                    }
                }else{
                    $sender->sendMessage(Manager::PREFIX_ALERT . "Cette commandes est utilisable uniquement sur votre île.");
                    return;
                }
            }else{
                $sender->sendMessage(Manager::NOT_PERM);
                return;
            }
        }
    }

}