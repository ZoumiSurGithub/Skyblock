<?php

namespace Zoumi\Core\commands\all;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use Zoumi\Core\Main;
use Zoumi\Core\Manager;

class Bourse extends Command {

    public function __construct(string $name, string $description = "", string $usageMessage = null, array $aliases = [])
    {
        parent::__construct($name, $description, $usageMessage, $aliases);
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        $sender->sendMessage(Manager::PREFIX_INFOS . "La bourse change tout les lundi à 00:00:00.\n" .
            "§6- §fBourse Hebdomadaire §6-\n\n" .
            "§eCactus §f- " . Main::getInstance()->bourse->get("cactus") . "\u{E102}\n" .
            "§eVerrue du nether §f- " . Main::getInstance()->bourse->get("verrue") . "\u{E102}\n" .
            "§eBlé §f- " . Main::getInstance()->bourse->get("ble") . "\u{E102}\n" .
            "§ePatate §f- " . Main::getInstance()->bourse->get("patate") . "\u{E102}\n" .
            "§eCarotte §f- " . Main::getInstance()->bourse->get("carotte"). "\u{E102}\n" .
            "§eCanne à sucre §f- " . Main::getInstance()->bourse->get("canne") . "\u{E102}"
        );
    }

}