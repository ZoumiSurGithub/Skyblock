<?php

namespace Zoumi\Core\commands\staff\gamemode;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use Zoumi\Core\Manager;

class GMSP extends Command {

    public function __construct(string $name, string $description = "", string $usageMessage = null, array $aliases = [])
    {
        parent::__construct($name, $description, $usageMessage, $aliases);
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        if ($sender instanceof Player){
            if ($sender->hasPermission("use.gmsp")){
                if ($sender->getGamemode() === 3){
                    $sender->sendMessage(Manager::PREFIX_ALERT . "Vous êtes déjà en GameMode Spectateur.");
                    return;
                }else{
                    $sender->setGamemode(3);
                    $sender->addTitle("§eGameMode 3", "§fVous êtes désormais en §eGameMode 3§f.");
                    return;
                }
            }else{
                $sender->sendMessage(Manager::NOT_PERM);
                return;
            }
        }
    }

}