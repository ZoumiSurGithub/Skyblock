<?php

namespace Zoumi\Core\commands\all;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use jacknoordhuis\combatlogger\CombatLogger;
use Zoumi\Core\Manager;

class Transfer extends Command {

    public function __construct(string $name, string $description = "", string $usageMessage = null, array $aliases = [])
    {
        parent::__construct($name, $description, $usageMessage, $aliases);
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        if ($sender instanceof Player){
            if (!CombatLogger::getInstance()->isTagged($sender)){
                if (!isset($args[0])){
                    $sender->sendMessage(Manager::PREFIX_ALERT . "Veuillez faire /transfer pvpbox|lobby.");
                    return;
                }
                if (strtolower($args[0]) === "pvpbox"){
                    $sender->sendMessage(Manager::PREFIX_INFOS . "Transfer vers le serveur §ePvPBox§f.");
                    $sender->transfer("moonlight-mc.eu", 19135, "transfer pvpbox");
                    return;
                }elseif (strtolower($args[0]) === "lobby"){
                    $sender->sendMessage(Manager::PREFIX_INFOS . "Transfer vers le serveur §eLobby§f.");
                    $sender->transfer("moonlight-mc.eu", 19132, "transfer lobby");
                    return;
                }
            }else{
                $sender->sendMessage(Manager::PREFIX_ALERT . "Cette commande n'est pas disponible en combat.");
                return;
            }
        }
    }

}