<?php

namespace Zoumi\Core\listeners;

use jacknoordhuis\combatlogger\CombatLogger;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\entity\EntityLevelChangeEvent;
use pocketmine\event\Listener;
use pocketmine\Player;
use pocketmine\Server;
use Zoumi\Core\api\Scoreboard;
use Zoumi\Core\api\SkyBlock;
use Zoumi\Core\api\Users;
use Zoumi\Core\entity\Bourse;
use Zoumi\Core\Main;

class EntityListener implements Listener {

    public function onEntityLevelChange(EntityLevelChangeEvent $event){
        $player = $event->getEntity();
        if ($player instanceof Player){
            if (in_array($player->getName(), Main::getInstance()->flight)) {
                if ($event->getTarget()->getFolderName() === SkyBlock::getIslandName($player->getName())) {
                    $player->setAllowFlight(true);
                } else {
                    $player->setAllowFlight(false);
                    $player->setFlying(false);
                }
            }
        }
    }

    public function onEntityDamageByEntity(EntityDamageByEntityEvent $event){
        $entity = $event->getEntity();
        $damager = $event->getDamager();
        if ($entity instanceof Bourse){
            if (!$event->isCancelled()) $event->setCancelled(true);
        }
    }

    public function onEntityDamage(EntityDamageEvent $event){
        $entity = $event->getEntity();
        $cause = $event->getCause();
        switch ($cause){
            case EntityDamageEvent::CAUSE_FALL:
                if ($entity instanceof Player) {
                    if (!$event->isCancelled()) $event->setCancelled(true);
                }
                break;
            case EntityDamageEvent::CAUSE_VOID:
                if ($entity instanceof Player) {
                    if (!CombatLogger::getInstance()->isTagged($entity)) {
                        if (!$event->isCancelled()) $event->setCancelled(true);
                        $entity->teleport(Server::getInstance()->getDefaultLevel()->getSafeSpawn());
                    }
                }
                break;
            case EntityDamageEvent::CAUSE_SUFFOCATION:
                if ($entity instanceof Player) {
                    if (!$event->isCancelled()) $event->setCancelled(true);
                    $entity->teleport(Server::getInstance()->getDefaultLevel()->getSafeSpawn());
                }
                break;
        }
    }

}