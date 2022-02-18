<?php

namespace Zoumi\Core\commands\all;

use jacknoordhuis\combatlogger\CombatLogger;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\Server;
use Zoumi\Core\commands\all\coins\Coins;
use Zoumi\Core\Manager;

class Spawn extends Command {
    
    public function __construct(string $name, string $description = "", string $usageMessage = null, array $aliases = [])
    {
        parent::__construct($name, $description, $usageMessage, $aliases);
    }
    
    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        if ($sender instanceof Player){
            if (!CombatLogger::getInstance()->isTagged($sender)){
                $sender->teleport(Server::getInstance()->getDefaultLevel()->getSafeSpawn());
                $sender->sendMessage(Manager::PREFIX_INFOS . "Vous venez d'être téléporter au spawn.");
                return;
            }else{
                $sender->sendMessage(Manager::PREFIX_ALERT . "Vous ne pouvez pas vous téléportez en combat !");
                return;
            }
        }
    }

}