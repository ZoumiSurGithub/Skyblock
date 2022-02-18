<?php

namespace Zoumi\Core\commands\all;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\Server;
use Zoumi\Core\Manager;

class Ping extends Command {

    public function __construct(string $name, string $description = "", string $usageMessage = null, array $aliases = [])
    {
        parent::__construct($name, $description, $usageMessage, $aliases);
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        if ($sender instanceof Player) {
            if (!isset($args[0])) {
                $sender->sendMessage(Manager::PREFIX_INFOS . "Tu possèdes §e" . $sender->getPing() . "§fms.");
                return;
            }else{
                $target = Server::getInstance()->getPlayer($args[0]);
                if ($target instanceof Player){
                    $sender->sendMessage(Manager::PREFIX_INFOS . "Le joueur §e" . $target->getName() . " §fpossède §e" . $target->getPing() . "§fms.");
                    return;
                }else{
                    $sender->sendMessage(Manager::PREFIX_ALERT . "Ce joueur n'est pas en ligne.");
                    return;
                }
            }
        }
    }

}