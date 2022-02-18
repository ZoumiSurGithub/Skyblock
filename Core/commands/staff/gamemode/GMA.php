<?php

namespace Zoumi\Core\commands\staff\gamemode;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use Zoumi\Core\commands\all\coins\Coins;
use Zoumi\Core\Manager;

class GMA extends Command {

    public function __construct(string $name, string $description = "", string $usageMessage = null, array $aliases = [])
    {
        parent::__construct($name, $description, $usageMessage, $aliases);
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        if ($sender instanceof Player){
            if ($sender->hasPermission("use.gma")){
                if ($sender->getGamemode() === 2){
                    $sender->sendMessage(Manager::PREFIX_ALERT . "Vous êtes déjà en GameMode Aventure.");
                    return;
                }else{
                    $sender->setGamemode(2);
                    $sender->addTitle("§eGameMode 2", "§fVous êtes désormais en §eGameMode 2§f.");
                    return;
                }
            }else{
                $sender->sendMessage(Manager::NOT_PERM);
                return;
            }
        }
    }

}