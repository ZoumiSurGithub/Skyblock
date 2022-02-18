<?php

namespace Zoumi\Core\commands\staff;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\entity\Entity;
use pocketmine\level\Position;
use pocketmine\Player;
use pocketmine\Server;
use Zoumi\Core\commands\all\coins\Coins;
use Zoumi\Core\Main;
use Zoumi\Core\Manager;

class EntitySpawn extends Command {

    public function __construct(string $name, string $description = "", string $usageMessage = null, array $aliases = [])
    {
        parent::__construct($name, $description, $usageMessage, $aliases);
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        if ($sender instanceof Player){
            if ($sender->hasPermission("use.entityspawn")){
                if (!isset($args[0])){
                    $sender->sendMessage(Manager::PREFIX_ALERT . "Veuillez faire /entityspawn bourse|topcoins|purification.");
                    return;
                }elseif (strtolower($args[0]) === "bourse"){
                    $nbt = Entity::createBaseNBT(new Position(-447.5, 97, -391.5, Server::getInstance()->getLevelByName("spawn")), null, 0, 0);
                    $entity = Entity::createEntity("Bourse", $sender->getLevel(), $nbt);
                    $entity->spawnToAll();
                    $sender->sendMessage(Manager::PREFIX_INFOS . "L'entitée §eBourse §fa bien été créer.");
                    return;
                }elseif (strtolower($args[0]) === "topcoins"){
                    $nbt = Entity::createBaseNBT(new Position(-451.5, 95, -374.5, Server::getInstance()->getLevelByName("spawn")), null, 0, 0);
                    $entity = Entity::createEntity("TopCoins", $sender->getLevel(), $nbt);
                    $entity->spawnToAll();
                    $sender->sendMessage(Manager::PREFIX_INFOS . "L'entitée §eCoins §fa bien été créer.");
                    return;
                }elseif (strtolower($args[0]) === "purification"){
                    $nbt = Entity::createBaseNBT(new Position(206.5, 83, 224.5, Server::getInstance()->getLevelByName("farmzone1")), null, 0, 0);
                    $entity = Entity::createEntity("Purification", Server::getInstance()->getLevelByName("farmzone1"), $nbt);
                    $entity->spawnToAll();
                    $nbt = Entity::createBaseNBT(new Position(209.5, 85, 208.5, Server::getInstance()->getLevelByName("farmzone2")), null, 0, 0);
                    $entity = Entity::createEntity("Purification", Server::getInstance()->getLevelByName("farmzone2"), $nbt);
                    $entity->spawnToAll();
                    $sender->sendMessage(Manager::PREFIX_INFOS . "Les entitées de §epurification §font été créer.");
                }
            }else{
                $sender->sendMessage(Manager::NOT_PERM);
                return;
            }
        }
    }

}