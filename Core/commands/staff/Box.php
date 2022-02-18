<?php

namespace Zoumi\Core\commands\staff;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use Zoumi\Core\api\Users;
use Zoumi\Core\Manager;
use Zoumi\Core\api\Box as B;

class Box extends Command {

    public function __construct(string $name, string $description = "", string $usageMessage = null, array $aliases = [])
    {
        parent::__construct($name, $description, $usageMessage, $aliases);
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        if ($sender->hasPermission("use.box.give")) {
            if (!isset($args[0])){
                $sender->sendMessage(Manager::PREFIX_ALERT . "Veuillez /box add|remove.");
                return;
            }elseif (strtolower($args[0]) === "add"){
                if (!isset($args[1])){
                    $sender->sendMessage(Manager::PREFIX_ALERT . "Veuillez faire /box add vote|farming|boutique|spawner.");
                    return;
                }elseif (strtolower($args[1]) === "vote"){
                    if (!isset($args[3])){
                        $sender->sendMessage(Manager::PREFIX_ALERT . "Veuillez /box add vote [joueur] [nombre].");
                        return;
                    }
                    if (!Users::haveAccount($args[2])){
                        $sender->sendMessage(Manager::PLAYER_NOT_EXIST_IN_DATA);
                        return;
                    }
                    if (!is_numeric($args[3])){
                        $sender->sendMessage(Manager::PREFIX_ALERT . "Veuillez entré un nombre valide.");
                        return;
                    }
                    B::addKey($args[2], "vote", $args[3]);
                    $sender->sendMessage(Manager::PREFIX_INFOS . "Vous avez ajouter §ex" . $args[3] . " Clé(s) §2Vote§f.");
                    return;
                }elseif (strtolower($args[1]) === "farming"){
                    if (!isset($args[3])){
                        $sender->sendMessage(Manager::PREFIX_ALERT . "Veuillez /box add farming [joueur] [nombre].");
                        return;
                    }
                    if (!Users::haveAccount($args[2])){
                        $sender->sendMessage(Manager::PLAYER_NOT_EXIST_IN_DATA);
                        return;
                    }
                    if (!is_numeric($args[3])){
                        $sender->sendMessage(Manager::PREFIX_ALERT . "Veuillez entré un nombre valide.");
                        return;
                    }
                    B::addKey($args[2], "farming", $args[3]);
                    $sender->sendMessage(Manager::PREFIX_INFOS . "Vous avez ajouter §ex" . $args[3] . " Clé(s) §eFarming§f.");
                    return;
                }elseif (strtolower($args[1]) === "boutique"){
                    if (!isset($args[3])){
                        $sender->sendMessage(Manager::PREFIX_ALERT . "Veuillez /box add boutique [joueur] [nombre].");
                        return;
                    }
                    if (!Users::haveAccount($args[2])){
                        $sender->sendMessage(Manager::PLAYER_NOT_EXIST_IN_DATA);
                        return;
                    }
                    if (!is_numeric($args[3])){
                        $sender->sendMessage(Manager::PREFIX_ALERT . "Veuillez entré un nombre valide.");
                        return;
                    }
                    B::addKey($args[2], "boutique", $args[3]);
                    $sender->sendMessage(Manager::PREFIX_INFOS . "Vous avez ajouter §ex" . $args[3] . " Clé(s) §6Boutique§f.");
                    return;
                }elseif (strtolower($args[1]) === "spawner"){
                    if (!isset($args[3])){
                        $sender->sendMessage(Manager::PREFIX_ALERT . "Veuillez /box add spawner [joueur] [nombre].");
                        return;
                    }
                    if (!Users::haveAccount($args[2])){
                        $sender->sendMessage(Manager::PLAYER_NOT_EXIST_IN_DATA);
                        return;
                    }
                    if (!is_numeric($args[3])){
                        $sender->sendMessage(Manager::PREFIX_ALERT . "Veuillez entré un nombre valide.");
                        return;
                    }
                    B::addKey($args[2], "spawner", $args[3]);
                    $sender->sendMessage(Manager::PREFIX_INFOS . "Vous avez ajouter §ex" . $args[3] . " Clé(s) §0Spawner§f.");
                    return;
                }
            }
        } else {
            $sender->sendMessage(Manager::NOT_PERM);
            return;
        }
    }

}