<?php

namespace Zoumi\Core\commands\all\coins;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use Zoumi\Core\api\Users;
use Zoumi\Core\Manager;

class Coins extends Command {

    public function __construct(string $name, string $description = "", string $usageMessage = null, array $aliases = [])
    {
        parent::__construct($name, $description, $usageMessage, $aliases);
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    { 
        if ($sender instanceof Player){
        if (!isset($args[0])){
            $sender->sendMessage(Manager::PREFIX_INFOS . "Vous possédez §e" . \Zoumi\Core\api\Coins::getCoins($sender->getLowerCaseName()) . "§f\u{E102}.");
            return;
        }else{
            if (Users::haveAccount($args[0])){
                $sender->sendMessage(Manager::PREFIX_INFOS . "Le joueur §e" . $args[0] . " §fpossède §e" . \Zoumi\Core\api\Coins::getCoins($args[0]) . "§f\u{E102}.");
            }else{
                $sender->sendMessage(Manager::PLAYER_NOT_EXIST_IN_DATA);
                return;
            }
        }
    }else{
        if (!isset($args[0])){
            $sender->sendMessage(Manager::PREFIX_ALERT . "Veuillez faire /coins [joueur].");
            return;
        }else{
            if (Users::haveAccount(strtolower($args[0]))){
                $sender->sendMessage(Manager::PREFIX_INFOS . "Le joueur §e" . $args[0] . " §fpossède §e" . \Zoumi\Core\api\Coins::getCoins($args[0]) . "§f$.");
            }else{
                $sender->sendMessage(Manager::PLAYER_NOT_EXIST_IN_DATA);
                return;
            }
        }
    }
    }

}