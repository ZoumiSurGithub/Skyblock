<?php

namespace Zoumi\Core\commands\all\coins;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\Server;
use Zoumi\Core\api\Users;
use Zoumi\Core\Manager;

class TakesCoins extends Command {

    public function __construct(string $name, string $description = "", string $usageMessage = null, array $aliases = [])
    {
        parent::__construct($name, $description, $usageMessage, $aliases);
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        if ($sender instanceof Player){
            if (!isset($args[1])){
                $sender->sendMessage(Manager::PREFIX_ALERT . "Veuillez faire /takescoins [joueur] [coins].");
                return;
            }else {
                if (Users::haveAccount($args[0])) {
                    if (!is_numeric($args[1]) && $args[1] > 0) {
                        $sender->sendMessage(Manager::PREFIX_ALERT . "L'argument 1 doit-être plus grand que 0.");
                        return;
                    }
                    if (\Zoumi\Core\api\Coins::getCoins($sender->getName()) >= $args[1]) {
                        if ($args[0] === $sender->getName()) {
                            $sender->sendMessage(Manager::PREFIX_ALERT . "Vous ne pouvez vous envoyé des coins à vous même.");
                            return;
                        }
                        \Zoumi\Core\api\Coins::removeCoins($sender->getName(), $args[1]);
                        \Zoumi\Core\api\Coins::addCoins($args[0], $args[1]);
                        $sender->sendMessage(Manager::PREFIX . "Vous avez envoyé §e" . $args[1] . "§f\u{E102} à §e" . $args[0] . "§f.");
                        $target = Server::getInstance()->getPlayer($args[0]);
                        if ($target instanceof Player) {
                            $target->sendMessage(Manager::PREFIX . "Vous avez reçu §e" . $args[1] . "§f\u{E102} de la part de §e" . $sender->getName() . ".");
                        }
                        return;
                    } else {
                        $sender->sendMessage(Manager::PREFIX_ALERT . "Vous ne possédez pas les coins que vous avez entrée.");
                        return;
                    }
                } else {
                    $sender->sendMessage(Manager::PLAYER_NOT_EXIST_IN_DATA);
                    return;
                }
            }
        }
    }

}