<?php

namespace Zoumi\Core\commands\all;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use Zoumi\Core\Main;
use Zoumi\Core\Manager;

class XYZ extends Command {

    public function __construct(string $name, string $description = "", string $usageMessage = null, array $aliases = [])
    {
        parent::__construct($name, $description, $usageMessage, $aliases);
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        if ($sender instanceof Player){
            if (isset(Main::getInstance()->xyz[$sender->getName()])){
                $sender->sendMessage(Manager::PREFIX_INFOS . "Vous ne verrez plus les coordonnés.");
                unset(Main::getInstance()->xyz[$sender->getName()]);
                return;
            }else{
                $sender->sendMessage(Manager::PREFIX_INFOS . "Vous verrez désormais les coordonnés.");
                Main::getInstance()->xyz[] = $sender->getName();
                return;
            }
        }
    }

}