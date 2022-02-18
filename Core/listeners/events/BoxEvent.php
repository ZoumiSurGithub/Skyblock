<?php

namespace Zoumi\Core\listeners\events;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\item\Item;
use pocketmine\Server;
use Zoumi\Core\api\Box;
use Zoumi\Core\api\Coins;
use Zoumi\Core\listeners\FormListener;
use Zoumi\Core\Manager;

class BoxEvent implements Listener {

    public function onInteract(PlayerInteractEvent $event){
        $player = $event->getPlayer();
        $block = $event->getBlock();
        /** Spawner */
        if ($block->getId() === 218 && $block->getDamage() === 7 && $event->getAction() === PlayerInteractEvent::RIGHT_CLICK_BLOCK){
            if (Box::getKey($player->getName(), "spawner") >= 1){
                $rand = mt_rand(1, 100);
                if ($rand >= 1 && $rand < 25){
                    $item = Item::get(Item::MOB_SPAWNER);
                    if ($player->getInventory()->canAddItem($item)){
                        Box::removeKey($player->getName(), "spawner", 1);
                        $player->getInventory()->addItem($item);
                        Server::getInstance()->broadcastMessage("§e" . $player->getName() . " §fvient d'obtenir x1 Spawner dans la box §0Spawner§f.");
                        return;
                    }
                }elseif ($rand >= 25 && $rand < 40){
                    $item = Item::get(Item::SPAWN_EGG, 11);
                    if ($player->getInventory()->canAddItem($item)){
                        Box::removeKey($player->getName(), "spawner", 1);
                        $player->getInventory()->addItem($item);
                        Server::getInstance()->broadcastMessage("§e" . $player->getName() . " §fvient d'obtenir x1 Oeuf de vache dans la box §0Spawner§f.");
                        return;
                    }
                }elseif ($rand >= 40 && $rand < 55){
                    $item = Item::get(Item::SPAWN_EGG, 13);
                    if ($player->getInventory()->canAddItem($item)){
                        Box::removeKey($player->getName(), "spawner", 1);
                        $player->getInventory()->addItem($item);
                        Server::getInstance()->broadcastMessage("§e" . $player->getName() . " §fvient d'obtenir x1 Oeuf de mouton dans la box §0Spawner§f.");
                        return;
                    }
                }elseif ($rand >= 55 && $rand < 70){
                    $item = Item::get(Item::SPAWN_EGG, 12);
                    if ($player->getInventory()->canAddItem($item)){
                        Box::removeKey($player->getName(), "spawner", 1);
                        $player->getInventory()->addItem($item);
                        Server::getInstance()->broadcastMessage("§e" . $player->getName() . " §fvient d'obtenir x1 Oeuf de cochon dans la box §0Spawner§f.");
                        return;
                    }
                }elseif ($rand >= 70 && $rand < 80){
                    $item = Item::get(Item::SPAWN_EGG, 35);
                    if ($player->getInventory()->canAddItem($item)){
                        Box::removeKey($player->getName(), "spawner", 1);
                        $player->getInventory()->addItem($item);
                        Server::getInstance()->broadcastMessage("§e" . $player->getName() . " §fvient d'obtenir x1 Oeuf d'araignée dans la box §0Spawner§f.");
                        return;
                    }
                }elseif ($rand >= 80 && $rand < 90){
                    $item = Item::get(Item::SPAWN_EGG, 33);
                    if ($player->getInventory()->canAddItem($item)){
                        Box::removeKey($player->getName(), "spawner", 1);
                        $player->getInventory()->addItem($item);
                        Server::getInstance()->broadcastMessage("§e" . $player->getName() . " §fvient d'obtenir x1 Oeuf de creeper dans la box §0Spawner§f.");
                        return;
                    }
                }elseif ($rand >= 90 && $rand < 95){
                    $item = Item::get(Item::SPAWN_EGG, 34);
                    if ($player->getInventory()->canAddItem($item)){
                        Box::removeKey($player->getName(), "spawner", 1);
                        $player->getInventory()->addItem($item);
                        Server::getInstance()->broadcastMessage("§e" . $player->getName() . " §fvient d'obtenir x1 Oeuf de squelette dans la box §0Spawner§f.");
                        return;
                    }
                }elseif ($rand >= 95 && $rand <= 100){
                    $item = Item::get(Item::SPAWN_EGG, 38);
                    if ($player->getInventory()->canAddItem($item)){
                        Box::removeKey($player->getName(), "spawner", 1);
                        $player->getInventory()->addItem($item);
                        Server::getInstance()->broadcastMessage("§e" . $player->getName() . " §fvient d'obtenir x1 Oeuf d'enderman dans la box §0Spawner§f.");
                        return;
                    }
                }
            }else{
                FormListener::sendSpawnerInfos($player);
                return;
            }
            /** Vote */
        }elseif($block->getId() === 218 && $block->getDamage() === 0 && $event->getAction() === PlayerInteractEvent::RIGHT_CLICK_BLOCK){
            if (Box::getKey($player->getName(), "vote") >= 1){
                $rand = mt_rand(1, 100);
                if ($rand >= 1 && $rand < 3){
                    Box::removeKey($player->getName(), "vote", 1);
                    Box::addKey($player->getName(), "spawner", 1);
                    Server::getInstance()->broadcastMessage("§e" . $player->getName() . " §fvient d'obtenir §ex1 Clé §0Spawner §fdans la box §2Vote§f.");
                    return;
                }elseif ($rand >= 3 && $rand < 6){
                    Box::removeKey($player->getName(), "vote", 1);
                    Box::addKey($player->getName(), "farming", 1);
                    Server::getInstance()->broadcastMessage("§e" . $player->getName() . " §fvient d'obtenir §ex1 Clé §eFarming §fdans la box §2Vote§f.");
                    return;
                }elseif ($rand >= 6 && $rand < 11){
                    $item = Item::get(Item::DIRT, 0, 32);
                    if ($player->getInventory()->canAddItem($item)){
                        Box::removeKey($player->getName(), "vote", 1);
                        $player->getInventory()->addItem($item);
                        Server::getInstance()->broadcastMessage("§e" . $player->getName() . " §fvient d'obtenir §ex32 Terre §fdans la box §2Vote§f.");
                        return;
                    }
                }elseif ($rand >= 11 && $rand < 16){
                    $item = Item::get(Item::SOUL_SAND, 0, 16);
                    if ($player->getInventory()->canAddItem($item)){
                        Box::removeKey($player->getName(), "vote", 1);
                        $player->getInventory()->addItem($item);
                        Server::getInstance()->broadcastMessage("§e" . $player->getName() . " §fvient d'obtenir §ex16 Sable des âmes §fdans la box §2Vote§f.");
                        return;
                    }
                }elseif ($rand >= 16 && $rand < 26){
                    Box::removeKey($player->getName(), "vote", 1);
                    Coins::addCoins($player->getName(), 20000);
                    Server::getInstance()->broadcastMessage("§e" . $player->getName() . " §fvient d'obtenir §ex20 000\u{E102} §fdans la box §2Vote§f.");
                    return;
                }elseif ($rand >= 26 && $rand < 46){
                    Box::removeKey($player->getName(), "vote", 1);
                    Coins::addCoins($player->getName(), 10000);
                    Server::getInstance()->broadcastMessage("§e" . $player->getName() . " §fvient d'obtenir §ex10 000\u{E102} §fdans la box §2Vote§f.");
                    return;
                }elseif ($rand >= 46 && $rand < 76){
                    Box::removeKey($player->getName(), "vote", 1);
                    Coins::addCoins($player->getName(), 5000);
                    Server::getInstance()->broadcastMessage("§e" . $player->getName() . " §fvient d'obtenir §ex5 000\u{E102} §fdans la box §2Vote§f.");
                    return;
                }elseif ($rand >= 76 && $rand < 80){
                    $item = Item::get(Item::EMERALD_BLOCK, 0, 16);
                    if ($player->getInventory()->canAddItem($item)){
                        Box::removeKey($player->getName(), "vote", 1);
                        $player->getInventory()->addItem($item);
                        Server::getInstance()->broadcastMessage("§e" . $player->getName() . " §fvient d'obtenir §ex16 Blocs d'émeraude §fdans la box §2Vote§f.");
                        return;
                    }
                }elseif ($rand >= 80 && $rand < 90){
                    $item = Item::get(Item::DIAMOND_BLOCK, 0, 32);
                    if ($player->getInventory()->canAddItem($item)){
                        Box::removeKey($player->getName(), "vote", 1);
                        $player->getInventory()->addItem($item);
                        Server::getInstance()->broadcastMessage("§e" . $player->getName() . " §fvient d'obtenir §ex32 Blocs de diamant §fdans la box §2Vote§f.");
                        return;
                    }
                }elseif ($rand >= 90 && $rand <= 100){
                    $item = Item::get(Item::GOLD_ORE, 0, 32);
                    if ($player->getInventory()->canAddItem($item)){
                        Box::removeKey($player->getName(), "vote", 1);
                        $player->getInventory()->addItem($item);
                        Server::getInstance()->broadcastMessage("§e" . $player->getName() . " §fvient d'obtenir §ex32 Minerai Aléatoire §fdans la box §2Vote§f.");
                        return;
                    }
                }
            }else{
                FormListener::sendVoteInfos($player);
                return;
            }
            /** Farming */
        }elseif ($block->getId() === 218 && $block->getDamage() === 4 && $event->getAction() === PlayerInteractEvent::RIGHT_CLICK_BLOCK){
            if (Box::getKey($player->getName(), "farming") >= 1){
                $rand = mt_rand(1, 100);
                if ($rand >= 1 && $rand < 10){
                    $item = Item::get(Item::WHEAT_SEEDS, 0, 64);
                    if ($player->getInventory()->canAddItem($item)){
                        Box::removeKey($player->getName(), "farming", 1);
                        $player->getInventory()->addItem($item);
                        Server::getInstance()->broadcastMessage("§e" . $player->getName() . " §fvient d'obtenir §ex64 Graines §fdans la box §eFarming§f.");
                        return;
                    }
                }elseif ($rand >= 10 && $rand < 20){
                    $item = Item::get(Item::CACTUS, 0, 64);
                    if ($player->getInventory()->canAddItem($item)){
                        Box::removeKey($player->getName(), "farming", 1);
                        $player->getInventory()->addItem($item);
                        Server::getInstance()->broadcastMessage("§e" . $player->getName() . " §fvient d'obtenir §ex64 Cactus §fdans la box §eFarming§f.");
                        return;
                    }
                }elseif ($rand >= 20 && $rand <= 30){
                    $item = Item::get(Item::POTATO, 0, 64);
                    if ($player->getInventory()->canAddItem($item)){
                        Box::removeKey($player->getName(), "farming", 1);
                        $player->getInventory()->addItem($item);
                        Server::getInstance()->broadcastMessage("§e" . $player->getName() . " §fvient d'obtenir §ex64 Patates §fdans la box §eFarming§f.");
                        return;
                    }
                }elseif ($rand >= 30 && $rand < 40){
                    $item = Item::get(Item::CARROT, 0, 64);
                    if ($player->getInventory()->canAddItem($item)){
                        Box::removeKey($player->getName(), "farming", 1);
                        $player->getInventory()->addItem($item);
                        Server::getInstance()->broadcastMessage("§e" . $player->getName() . " §fvient d'obtenir §ex64 Carottes §fdans la box §eFarming§f.");
                        return;
                    }
                }elseif ($rand >= 40 && $rand <= 50) {
                    $item = Item::get(Item::REEDS, 0, 64);
                    if ($player->getInventory()->canAddItem($item)) {
                        Box::removeKey($player->getName(), "farming", 1);
                        $player->getInventory()->addItem($item);
                        Server::getInstance()->broadcastMessage("§e" . $player->getName() . " §fvient d'obtenir §ex64 Cannes à sucre §fdans la box §eFarming§f.");
                        return;
                    }
                }elseif ($rand >= 50 && $rand < 60){
                    $item = Item::get(Item::CHAIN_HELMET);
                    if ($player->getInventory()->canAddItem($item)){
                        Box::removeKey($player->getName(), "farming", 1);
                        $player->getInventory()->addItem($item);
                        Server::getInstance()->broadcastMessage("§e" . $player->getName() . " §fvient d'obtenir §ex1 Casque de Farmer §fdans la box §eFarming§f.");
                        return;
                    }
                }elseif ($rand >= 60 && $rand < 70){
                    $item = Item::get(Item::CHAIN_CHESTPLATE);
                    if ($player->getInventory()->canAddItem($item)){
                        Box::removeKey($player->getName(), "farming", 1);
                        $player->getInventory()->addItem($item);
                        Server::getInstance()->broadcastMessage("§e" . $player->getName() . " §fvient d'obtenir §ex1 Plastron de Farmer §fdans la box §eFarming§f.");
                        return;
                    }
                }elseif ($rand >= 70 && $rand < 80){
                    $item = Item::get(Item::CHAIN_LEGGINGS);
                    if ($player->getInventory()->canAddItem($item)){
                        Box::removeKey($player->getName(), "farming", 1);
                        $player->getInventory()->addItem($item);
                        Server::getInstance()->broadcastMessage("§e" . $player->getName() . " §fvient d'obtenir §ex1 Jambière de Farmer §fdans la box §eFarming§f.");
                        return;
                    }
                }elseif ($rand >= 80 && $rand < 90){
                    $item = Item::get(Item::CHAIN_BOOTS);
                    if ($player->getInventory()->canAddItem($item)){
                        Box::removeKey($player->getName(), "farming", 1);
                        $player->getInventory()->addItem($item);
                        Server::getInstance()->broadcastMessage("§e" . $player->getName() . " §fvient d'obtenir §ex1 Bottes de Farmer §fdans la box §eFarming§f.");
                        return;
                    }
                }elseif($rand >= 90 && $rand < 93){
                    $item = Item::get(Item::DIRT, 0, 64);
                    if ($player->getInventory()->canAddItem($item)){
                        Box::removeKey($player->getName(), "farming", 1);
                        $player->getInventory()->addItem($item);
                        Server::getInstance()->broadcastMessage("§e" . $player->getName() . " §fvient d'obtenir §ex64 Terre §fdans la box §eFarming§f.");
                        return;
                    }
                }elseif ($rand >= 93 && $rand < 96){
                    $item = Item::get(Item::SOUL_SAND, 0, 32);
                    if ($player->getInventory()->canAddItem($item)){
                        Box::removeKey($player->getName(), "farming", 1);
                        $player->getInventory()->addItem($item);
                        Server::getInstance()->broadcastMessage("§e" . $player->getName() . " §fvient d'obtenir §ex32 Sable des âmes §fdans la box §eFarming§f.");
                        return;
                    }
                }elseif ($rand >= 96 && $rand <= 100){
                    $item = Item::get(Item::BEETROOT_SEEDS);
                    if ($player->getInventory()->canAddItem($item)){
                        Box::removeKey($player->getName(), "farming", 1);
                        $player->getInventory()->addItem($item);
                        Server::getInstance()->broadcastMessage("§e" . $player->getName() . " §fvient d'obtenir §ex1 Graine de rubis §fdans la box §eFarming§f.");
                        return;
                    }
                }
            }else{
                FormListener::sendFarmingInfos($player);
                return;
            }
            /** Boutique */
        }elseif($block->getId() === 218 && $block->getDamage() === 1 && $event->getAction() === PlayerInteractEvent::RIGHT_CLICK_BLOCK){
            if (Box::getKey($player->getName(), "boutique") >= 1){
                $rand = mt_rand(1, 100);
                if ($rand >= 1 && $rand < 3){
                    Box::removeKey($player->getName(), "boutique", 1);
                    Box::addKey($player->getName(), "spawner", 3);
                    Server::getInstance()->broadcastMessage("§e" . $player->getName() . " §fvient d'obtenir §ex3 Clés §0Spawner §fdans la box §6Boutique§f.");
                    return;
                }elseif ($rand >= 3 && $rand < 6){
                    Box::removeKey($player->getName(), "boutique", 1);
                    Box::addKey($player->getName(), "farming", 3);
                    Server::getInstance()->broadcastMessage("§e" . $player->getName() . " §fvient d'obtenir §ex3 Clés §eFarming §fdans la box §6Boutique§f.");
                    return;
                }elseif ($rand >= 6 && $rand < 11){
                    Box::removeKey($player->getName(), "boutique", 1);
                    Box::addKey($player->getName(), "spawner", 1);
                    Server::getInstance()->broadcastMessage("§e" . $player->getName() . " §fvient d'obtenir §ex1 Clé §0Spawner §fdans la box §6Boutique§f.");
                    return;
                }elseif ($rand >= 11 && $rand < 16){
                    Box::removeKey($player->getName(), "boutique", 1);
                    Box::addKey($player->getName(), "farming", 1);
                    Server::getInstance()->broadcastMessage("§e" . $player->getName() . " §fvient d'obtenir §ex1 Clé §eFarming §fdans la box §6Boutique§f.");
                    return;
                }elseif ($rand >= 16 && $rand < 26){
                    $item = Item::get(Item::DIRT, 0, 256);
                    if ($player->getInventory()->canAddItem($item)){
                        Box::removeKey($player->getName(), "boutique", 1);
                        $player->getInventory()->addItem($item);
                        Server::getInstance()->broadcastMessage("§e" . $player->getName() . " §fvient d'obtenir §ex256 Terres §fdans la box §6Boutique§f.");
                        return;
                    }
                }elseif ($rand >= 26 && $rand < 36){
                    $item = Item::get(Item::DIRT, 0, 128);
                    if ($player->getInventory()->canAddItem($item)){
                        Box::removeKey($player->getName(), "boutique", 1);
                        $player->getInventory()->addItem($item);
                        Server::getInstance()->broadcastMessage("§e" . $player->getName() . " §fvient d'obtenir §ex128 Sable des âmes §fdans la box §6Boutique§f.");
                        return;
                    }
                }elseif ($rand >= 36 && $rand < 46){
                    $item = Item::get(Item::EMERALD_BLOCK, 0, 128);
                    if ($player->getInventory()->canAddItem($item)){
                        Box::removeKey($player->getName(), "boutique", 1);
                        $player->getInventory()->addItem($item);
                        Server::getInstance()->broadcastMessage("§e" . $player->getName() . " §fvient d'obtenir §ex126 Blocs d'émeraude §fdans la box §6Boutique§f.");
                        return;
                    }
                }elseif ($rand >= 46 && $rand < 56){
                    $item = Item::get(Item::DIAMOND_BLOCK, 0, 256);
                    if ($player->getInventory()->canAddItem($item)){
                        Box::removeKey($player->getName(), "boutique", 1);
                        $player->getInventory()->addItem($item);
                        Server::getInstance()->broadcastMessage("§e" . $player->getName() . " §fvient d'obtenir §ex256 Blocs de diamant §fdans la box §6Boutique§f.");
                        return;
                    }
                }elseif ($rand >= 56 && $rand < 60){
                    $item = Item::get(Item::BEETROOT_SEEDS, 0, 5);
                    if ($player->getInventory()->canAddItem($item)){
                        Box::removeKey($player->getName(), "boutique", 1);
                        $player->getInventory()->addItem($item);
                        Server::getInstance()->broadcastMessage("§e" . $player->getName() . " §fvient d'obtenir §ex5 Graines de rubis §fdans la box §6Boutique§f.");
                        return;
                    }
                }elseif ($rand >= 60 && $rand < 70){
                    Box::removeKey($player->getName(), "boutique", 1);
                    Coins::addCoins($player->getName(), 100000);
                    Server::getInstance()->broadcastMessage("§e" . $player->getName() . " §fvient d'obtenir §ex100 000\u{E102} §fdans la box §6Boutique§f.");
                    return;
                }elseif ($rand >= 70 && $rand < 90){
                    Box::removeKey($player->getName(), "boutique", 1);
                    Coins::addCoins($player->getName(), 50000);
                    Server::getInstance()->broadcastMessage("§e" . $player->getName() . " §fvient d'obtenir §ex50 000\u{E102} §fdans la box §6Boutique§f.");
                    return;
                }elseif ($rand >= 90 && $rand <= 100){
                    $item = Item::get(Item::MOB_SPAWNER);
                    if ($player->getInventory()->canAddItem($item)){
                        Box::removeKey($player->getName(), "boutique", 1);
                        $player->getInventory()->addItem($item);
                        Server::getInstance()->broadcastMessage("§e" . $player->getName() . " §fvient d'obtenir §ex1 Spawner §fdans la box §6Boutique§f.");
                        return;
                    }
                }
            }else{
                FormListener::sendBoutiqueInfos($player);
                return;
            }
        }
    }

}