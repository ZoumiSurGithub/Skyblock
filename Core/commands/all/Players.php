<?php

namespace Zoumi\Core\commands\all;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\Server;
use Zoumi\Core\Manager;

class Players extends Command {

    public function __construct(string $name, string $description = "", string $usageMessage = null, array $aliases = [])
    {
        parent::__construct($name, $description, $usageMessage, $aliases);
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        $playerNames = array_map(function(Player $player) : string{
            return $player->getName();
        }, array_filter($sender->getServer()->getOnlinePlayers(), function(Player $player) use ($sender) : bool{
            return $player->isOnline() and (!($sender instanceof Player) or $sender->canSee($player));
        }));
        sort($playerNames, SORT_STRING);

        $sender->sendMessage(Manager::PREFIX_INFOS . "Voici la liste des joueurs connectés §7(" . count(Server::getInstance()->getOnlinePlayers()) . "/" . Server::getInstance()->getMaxPlayers() . ")§f:\n§e" . (implode("§f, §e", $playerNames) ?? "Aucun."));
    }

}