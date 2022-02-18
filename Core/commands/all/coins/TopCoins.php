<?php

namespace Zoumi\Core\commands\all\coins;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Player;

class TopCoins extends Command {

    public function __construct(string $name, string $description = "", string $usageMessage = null, array $aliases = [])
    {
        parent::__construct($name, $description, $usageMessage, $aliases);
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        if ($sender instanceof Player){
            \Zoumi\Core\api\Coins::getTop($sender);
        }else{
            \Zoumi\Core\api\Coins::getTopConsole($sender);
        }
    }

}