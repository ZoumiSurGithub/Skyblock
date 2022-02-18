<?php

namespace Zoumi\Core\commands\all;

use jojoe77777\FormAPI\SimpleForm;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use Zoumi\Core\Main;
use Zoumi\Core\tasks\events\Bingo;

class Events extends Command {

    public function __construct(string $name, string $description = "", string $usageMessage = null, array $aliases = [])
    {
        parent::__construct($name, $description, $usageMessage, $aliases);
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        if ($sender instanceof Player){
            Events::sendEventMenu($sender);
        }
    }

    public static function sendEventMenu(Player $player){
        $ui = new SimpleForm(function (Player $player, $data){

        });
        $ui->setTitle("§l§7Moon§elight §f- §dSkyBlock");
        $ui->setContent(
            "§7Bob: §fVoici la liste des événements disponibles.\n\n" .
            "§6» §fBingo §7- §fTous les 2 heures. (reste " . Main::getInstance()->convertFor(Bingo::$time) . ")\n" .
            "§6» §fDevineLeMessage §7- §fToute les heures.\n" .
            "§6» §fChestRefill §7- §fToute les heures.\n" .
            "§6» §fBoss §7- §fTous les 18h00 (heure française)."
        );
        $ui->addButton("Quitter");
        $ui->sendToPlayer($player);
    }

}