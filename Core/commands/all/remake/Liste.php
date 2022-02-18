<?php

namespace Zoumi\Core\commands\all\remake;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\utils\Internet;

class Liste extends Command {

    public function __construct(string $name, string $description = "", string $usageMessage = null, array $aliases = [])
    {
        parent::__construct($name, $description, $usageMessage, $aliases);
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        $lobby = json_decode(Internet::getURL("https://maxoooz.dev/api/mcsrvstatus/moonlight-mc.eu:19132"), null, 512, JSON_OBJECT_AS_ARRAY);
        $pvpbox = json_decode(Internet::getURL("https://maxoooz.dev/api/mcsrvstatus/moonlight-mc.eu:19135"), null, 512, JSON_OBJECT_AS_ARRAY);
        $skyblock = json_decode(Internet::getURL("https://maxoooz.dev/api/mcsrvstatus/moonlight-mc.eu:19150"), null, 512, JSON_OBJECT_AS_ARRAY);
        $sender->sendMessage(
            "§f---------------{ §7Moon§elight §f}---------------\n\n" .
            "§7Lobby: §e" . ($lobby["numplayers"] ?? 0) . "§f/§6" . ($lobby["maxplayers"] ?? 100) . "\n" .
            "§7PvPBox: §e" . ($pvpbox["numplayers"] ?? 0) . "§f/§6" . ($pvpbox["maxplayers"] ?? 100) . "\n" .
            "§7SkyBlock: §e" . ($skyblock["numplayers"] ?? 0) . "§f/§6" . ($skyblock["maxplayers"] ?? 100) . "\n\n" .
            "§f---------------{ §7Moon§elight §f}---------------"
        );
    }

}