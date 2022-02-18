<?php

namespace Zoumi\Core\commands\ranked;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\Server;
use Zoumi\Core\Main;
use Zoumi\Core\Manager;

class Annonce extends Command {

    public function __construct(string $name, string $description = "", string $usageMessage = null, array $aliases = [])
    {
        parent::__construct($name, $description, $usageMessage, $aliases);
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        if ($sender instanceof Player){
            if ($sender->hasPermission("use.annonce")){
                if (time() >= Main::getInstance()->cooldown->get($sender->getName() . "-annonce")) {
                    if (!isset($args[0])) {
                        $sender->sendMessage(Manager::PREFIX_ALERT . "Veuillez faire /annonce [votre message].");
                        return;
                    }
                    $msg = array_slice($args, 0);
                    $msg = implode(" ", $msg);
                    Server::getInstance()->broadcastMessage(
                        "§7----------------------------------------------------------------------------\n\n" .
                        "§fAnnonce de §a" . $sender->getName() . "§f.\n\n" .
                        "§f$msg\n\n" .
                        "§7----------------------------------------------------------------------------"
                    );
                    $config = Main::getInstance()->cooldown;
                    $config->set($sender->getName() . "-annonce", time() + 3600);
                    $config->save();
                    return;
                }else{
                    $sender->sendMessage(Manager::PREFIX_ALERT . "Vous devez patienter encore §e" . Main::getInstance()->convert(Main::getInstance()->cooldown->get($sender->getName() . "-annonce") - time()) . "§c.");
                    return;
                }
            }else{
                $sender->sendMessage(Manager::NOT_PERM);
                return;
            }
        }
    }

}