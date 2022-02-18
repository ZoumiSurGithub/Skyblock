<?php

namespace Zoumi\Core\commands\all\coins;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\Server;
use Zoumi\Core\api\Users;
use Zoumi\Core\Manager;

class SetCoins extends Command {
    
    public function __construct(string $name, string $description = "", string $usageMessage = null, array $aliases = [])
    {
        parent::__construct($name, $description, $usageMessage, $aliases);
    }
    
    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        if ($sender instanceof Player){
            if ($sender->hasPermission("use.setcoins")){
                if (!isset($args[1])){
                    $sender->sendMessage(Manager::PREFIX_ALERT . "Veuillez faire /setcoins [joueur] [coins].");
                    return;
                }else{
                    if (Users::haveAccount($args[0])){
                        if ($args[1] >= 0){
                            \Zoumi\Core\api\Coins::setCoins($args[0], $args[1]);
                            $sender->sendMessage(Manager::PREFIX_INFOS . "Vous avez définis les coins du joueur §e" . $args[0] . " §fà §e" . $args[1] . "§f.");
                            $target = Server::getInstance()->getPlayer($args[0]);
                            if ($target instanceof Player){
                                $target->sendMessage(Manager::PREFIX_INFOS . "§e" . $sender->getName() . " §fà définis vos coins à §e" . $args[1] . "§f.");
                            }
                        }else{
                            $sender->sendMessage(Manager::PREFIX_ALERT . "L'argument 1 doit-être un nombre décimal et plus grand que 0.");
                            return;
                        }
                    }else{
                        $sender->sendMessage(Manager::PLAYER_NOT_EXIST_IN_DATA);
                        return;
                    }
                }
            }else{
                $sender->sendMessage(Manager::NOT_PERM);
                return;
            }
        }else{
            if (!isset($args[1])){
                $sender->sendMessage(Manager::PREFIX_ALERT . "Veuillez faire /setcoins [joueur] [coins].");
                return;
            }else{
                if (Users::haveAccount($args[0])){
                    if ($args[1] >= 0){
                        \Zoumi\Core\api\Coins::setCoins($args[0], $args[1]);
                        $sender->sendMessage(Manager::PREFIX_INFOS . "Vous avez définis les coins du joueur §e" . $args[0] . " §fà §e" . $args[1] . "§f.");
                        $target = Server::getInstance()->getPlayer($args[0]);
                        if ($target instanceof Player){
                            $target->sendMessage(Manager::PREFIX_INFOS . "§e" . $sender->getName() . " §fà définis vos coins à §e" . $args[1] . "§f.");
                        }
                    }else{
                        $sender->sendMessage(Manager::PREFIX_ALERT . "L'argument 1 doit-être un nombre décimal et plus grand que 0.");
                        return;
                    }
                }else{
                    $sender->sendMessage(Manager::PLAYER_NOT_EXIST_IN_DATA);
                    return;
                }
            }
        }
    }

}