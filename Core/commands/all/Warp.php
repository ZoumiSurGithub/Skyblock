<?php

namespace Zoumi\Core\commands\all;

use jacknoordhuis\combatlogger\CombatLogger;
use jojoe77777\FormAPI\SimpleForm;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\level\Position;
use pocketmine\Player;
use pocketmine\Server;
use Zoumi\Core\Manager;

class Warp extends Command {

    public function __construct(string $name, string $description = "", string $usageMessage = null, array $aliases = [])
    {
        parent::__construct($name, $description, $usageMessage, $aliases);
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        if ($sender instanceof Player){
            Warp::sendWarpMenu($sender);
        }
    }

    public static function sendWarpMenu(Player $player){
        $ui = new SimpleForm(function (Player $player, $data){
            if ($data === null){
                return;
            }
            switch ($data){
                case 0:
                    if (!CombatLogger::getInstance()->isTagged($player)){
                        $player->teleport(Server::getInstance()->getDefaultLevel()->getSafeSpawn());
                        $player->sendMessage(Manager::PREFIX_INFOS . "Vous venez d'être téléporter au spawn.");
                        return;
                    }else{
                        $player->sendMessage(Manager::PREFIX_ALERT . "Vous ne pouvez pas vous téléportez en combat !");
                        return;
                    }
                    break;
                case 1:
                    if (!CombatLogger::getInstance()->isTagged($player)){
                        $player->teleport(new Position(-472.5, 86, -400.5, Server::getInstance()->getLevelByName("spawn")));
                        $player->sendMessage(Manager::PREFIX_INFOS . "Vous venez d'être téléporter à la salle des crafts.");
                        return;
                    }else{
                        $player->sendMessage(Manager::PREFIX_ALERT . "Vous ne pouvez pas vous téléportez en combat !");
                        return;
                    }
                    break;
                case 2:
                    if (!CombatLogger::getInstance()->isTagged($player)){
                        $player->teleport(Server::getInstance()->getLevelByName("ffa")->getSafeSpawn());
                        $player->sendMessage(Manager::PREFIX_INFOS . "Vous venez d'être téléporter au ffa.");
                        return;
                    }else{
                        $player->sendMessage(Manager::PREFIX_ALERT . "Vous ne pouvez pas vous téléportez en combat !");
                        return;
                    }
                    break;
                case 3:
                    if (!CombatLogger::getInstance()->isTagged($player)){
                        $player->teleport(Server::getInstance()->getLevelByName("farmzone1")->getSafeSpawn());
                        $player->sendMessage(Manager::PREFIX_INFOS . "Vous venez d'être téléporter à la farmzone.");
                        return;
                    }else{
                        $player->sendMessage(Manager::PREFIX_ALERT . "Vous ne pouvez pas vous téléportez en combat !");
                        return;
                    }
                    break;
                case 4:
                    if ($player->hasPermission("teleport.farmzonevip")) {
                        if (!CombatLogger::getInstance()->isTagged($player)) {
                            $player->teleport(Server::getInstance()->getLevelByName("farmzone2")->getSafeSpawn());
                            $player->sendMessage(Manager::PREFIX_INFOS . "Vous venez d'être téléporter au ffa.");
                            return;
                        } else {
                            $player->sendMessage(Manager::PREFIX_ALERT . "Vous ne pouvez pas vous téléportez en combat !");
                            return;
                        }
                    }else{
                        $player->sendMessage(Manager::NOT_PERM);
                        return;
                    }
                    break;
            }
        });
        $ui->setTitle("§l§7Moon§elight §f- §dSkyBlock");
        $ui->setContent("§7Bob: §fOù veux-tu aller ?");
        $ui->addButton("Spawn");
        $ui->addButton("Salle des crafts");
        $ui->addButton("FFA");
        $ui->addButton("FarmZone");
        $ui->addButton("FarmZone §eVIP");
        $ui->sendToPlayer($player);
    }

}