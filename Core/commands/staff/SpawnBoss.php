<?php

namespace Zoumi\Core\commands\staff;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\entity\Entity;
use pocketmine\level\Position;
use pocketmine\Player;
use pocketmine\Server;
use Zoumi\Core\Main;
use Zoumi\Core\Manager;

class SpawnBoss extends Command {

    public function __construct(string $name, string $description = "", string $usageMessage = null, array $aliases = [])
    {
        parent::__construct($name, $description, $usageMessage, $aliases);
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        if ($sender instanceof Player){
            if ($sender->hasPermission("use.spawn.boss")){
                $nbt = Entity::createBaseNBT(new Position($sender->x, $sender->y, $sender->z, $sender->getLevel()), null, 0, 0);
                $nbt->setTag(clone Main::getInstance()->getSkinTag());
                $entity = Entity::createEntity("Hera", $sender->getLevel(), $nbt);
                $entity->spawnToAll();
                $sender->sendMessage(Manager::PREFIX_INFOS . "Le boss a bien spawn.");
                return;
            }else{
                $sender->sendMessage(Manager::NOT_PERM);
                return;
            }
        }
    }

}