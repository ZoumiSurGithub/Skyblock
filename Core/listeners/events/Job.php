<?php

namespace Zoumi\Core\listeners\events;

use pocketmine\block\BlockIds;
use pocketmine\block\Farmland;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\block\BlockPlaceEvent;
use pocketmine\event\inventory\CraftItemEvent;
use pocketmine\event\Listener;
use pocketmine\item\enchantment\Enchantment;
use pocketmine\item\enchantment\EnchantmentInstance;
use pocketmine\item\Item;
use pocketmine\item\ItemIds;
use pocketmine\Server;
use Zoumi\Core\api\Coins;
use Zoumi\Core\api\Jobs;
use Zoumi\Core\api\SkyBlock;
use Zoumi\Core\Manager;

class Job implements Listener
{

    public function onBreak(BlockBreakEvent $event)
    {
        $player = $event->getPlayer();
        $block = $event->getBlock();
        if ($player->getGamemode() === 1) return;
        if ($player->getLevel()->getFolderName() !== SkyBlock::getIslandName($player->getName())) return;
        /** Blé */
        if (Jobs::getLevelForJob($player->getName(), "farmer") < 10) {
            if ($block->getId() === BlockIds::BEETROOT_BLOCK && $block->getDamage() === 3) {
                $drops = [];
                switch (mt_rand(1, 500)) {
                    case 1:
                        $drops[] = Item::get(Item::GOLD_NUGGET);
                        break;
                    default:
                        switch (mt_rand(1, 10)) {
                            case 1:
                                $drops[] = Item::get(Item::BEETROOT_SEEDS);
                                break;
                        }
                        break;
                }
                $event->setDrops($drops);
            }
            if ($block->getId() === BlockIds::WHEAT_BLOCK && $block->getDamage() === 7) {
                Jobs::addXpForJob($player->getName(), "farmer", 0.3);
                $player->sendPopup("§6» §e+0.3 §7d'xp au métier de Farmeur §6«");
            }
            /** Carotte/Patate */
            if ($block->getId() === BlockIds::CARROT_BLOCK && $block->getDamage() === 7 or $block->getId() === BlockIds::POTATO_BLOCK && $block->getDamage() === 7) {
                Jobs::addXpForJob($player->getName(), "farmer", 0.5);
                $player->sendPopup("§6» §e+0.5 §7d'xp au métier de Farmeur §6«");
            }
            /** Pastèque/Citrouille */
            if ($block->getId() === BlockIds::MELON_BLOCK or $block->getId() === BlockIds::PUMPKIN) {
                Jobs::addXpForJob($player->getName(), "farmer", 0.7);
                $player->sendPopup("§6» §e+0.7 §7d'xp au métier de Farmeur §6«");
            }
            /** Verrue du nether */
            if ($block->getId() === BlockIds::NETHER_WART_PLANT && $block->getDamage() === 3) {
                Jobs::addXpForJob($player->getName(), "farmer", 1);
                $player->sendPopup("§6» §e+1 §7d'xp au métier de Farmeur §6«");
            }
            /** Ajout du niveau */
            if (Jobs::getXpForJob($player->getName(), "farmer") >= Jobs::getXpRequireForNextLevel($player->getName(), "farmer")) {
                Jobs::addLevelForJob($player->getName(), "farmer", 1);
                $level = Jobs::getLevelForJob($player->getName(), "farmer");
                if ($level === 1) {
                    $player->sendMessage(Manager::PREFIX_INFOS . "Bravo, vous venez de passer niveau §e1 §fau métier de §eFarmeur§f. Vous avez gagner §ex1 Chapeau du Fermier §f!");
                    $item = Item::get(Item::CHAIN_HELMET);
                    if ($player->getInventory()->canAddItem($item)) {
                        $player->getInventory()->addItem($item);
                    } else {
                        $player->sendMessage(Manager::PREFIX_ALERT . "Ohoh, il semblerait que votre inventaire est plein. Veuillez prendre un screen de ce message est d'ouvrir un ticket sur notre serveur discord pour récupérer votre récompense.");

                    }
                } elseif ($level === 2) {
                    $player->sendMessage(Manager::PREFIX_INFOS . "Bravo, vous venez de passer niveau §e2 §fau métier de §eFarmeur§f. Vous avez gagner §ex2 000\u{E102} §f!");
                    Coins::addCoins($player->getName(), 2000);
                } elseif ($level === 3) {
                    $player->sendMessage(Manager::PREFIX_INFOS . "Bravo, vous venez de passer niveau §e3 §fau métier de §eFarmeur§f. Vous avez gagner §ex1 Salopette du Fermier §f!");
                    $item = Item::get(Item::CHAIN_CHESTPLATE);
                    if ($player->getInventory()->canAddItem($item)) {
                        $player->getInventory()->addItem($item);
                    } else {
                        $player->sendMessage(Manager::PREFIX_ALERT . "Ohoh, il semblerait que votre inventaire est plein. Veuillez prendre un screen de ce message est d'ouvrir un ticket sur notre serveur discord pour récupérer votre récompense.");

                    }
                } elseif ($level === 4) {
                    $player->sendMessage(Manager::PREFIX_INFOS . "Bravo, vous venez de passer niveau §e4 §fau métier de §eFarmeur§f. Vous avez gagner §ex4 000\u{E102} §f!");
                    Coins::addCoins($player->getName(), 4000);
                } elseif ($level === 5) {
                    $player->sendMessage(Manager::PREFIX_INFOS . "Bravo, vous venez de passer niveau §e5 §fau métier de §eFarmeur§f. Vous avez gagner §ex1 Pantalon du Fermier §f!");
                    $item = Item::get(Item::CHAIN_LEGGINGS);
                    if ($player->getInventory()->canAddItem($item)) {
                        $player->getInventory()->addItem($item);
                    } else {
                        $player->sendMessage(Manager::PREFIX_ALERT . "Ohoh, il semblerait que votre inventaire est plein. Veuillez prendre un screen de ce message est d'ouvrir un ticket sur notre serveur discord pour récupérer votre récompense.");

                    }
                } elseif ($level === 6) {
                    $player->sendMessage(Manager::PREFIX_INFOS . "Bravo, vous venez de passer niveau §e6 §fau métier de §eFarmeur§f. Vous avez gagner §ex1 Chapeau du Fermier §f!");
                    Coins::addCoins($player->getName(), 6000);
                } elseif ($level === 7) {
                    $player->sendMessage(Manager::PREFIX_INFOS . "Bravo, vous venez de passer niveau §e7 §fau métier de §eFarmeur§f. Vous avez gagner §ex1 Bottes du Fermier §f!");
                    $item = Item::get(Item::CHAIN_BOOTS);
                    if ($player->getInventory()->canAddItem($item)) {
                        $player->getInventory()->addItem($item);
                    } else {
                        $player->sendMessage(Manager::PREFIX_ALERT . "Ohoh, il semblerait que votre inventaire est plein. Veuillez prendre un screen de ce message est d'ouvrir un ticket sur notre serveur discord pour récupérer votre récompense.");

                    }
                } elseif ($level === 8) {
                    $player->sendMessage(Manager::PREFIX_INFOS . "Bravo, vous venez de passer niveau §e8 §fau métier de §eFarmeur§f. Vous avez gagner §ex8 000\u{E102} §f!");
                    Coins::addCoins($player->getName(), 8000);
                } elseif ($level === 9) {
                    $player->sendMessage(Manager::PREFIX_INFOS . "Bravo, vous venez de passer niveau §e9 §fau métier de §eFarmeur§f. Vous avez gagner §ex5 Graines de Rubis §fet §ex64 Diamants §f!");
                    $rubis = Item::get(Item::BEETROOT_SEEDS, 0, 5);
                    $diamond = Item::get(Item::DIAMOND, 0, 64);
                    if ($player->getInventory()->canAddItem($rubis) && $player->getInventory()->canAddItem($diamond)) {
                        $player->getInventory()->addItem($rubis);
                        $player->getInventory()->addItem($diamond);
                    } else {
                        $player->sendMessage(Manager::PREFIX_ALERT . "Ohoh, il semblerait que votre inventaire est plein. Veuillez prendre un screen de ce message est d'ouvrir un ticket sur notre serveur discord pour récupérer votre récompense.");

                    }
                } elseif ($level === 10) {
                    $player->sendMessage(Manager::PREFIX_INFOS . "Bravo, vous venez de passer niveau §e10 §fau métier de §eFarmeur§f. Vous avez gagner §ex10 000\u{E102} §fet §ex1 Kit Farmeur §f!");
                    Coins::addCoins($player->getName(), 10000);
                    $rubis = Item::get(Item::BEETROOT_SEEDS);
                    $water = Item::get(Item::WATER, 0, 10);
                    $lava = Item::get(Item::LAVA, 0, 10);
                    $potato = Item::get(Item::POTATO, 0, 32);
                    $carrot = Item::get(Item::CARROT, 0, 32);
                    $verrue = Item::get(Item::NETHER_WART, 0, 32);
                    $cactus = Item::get(Item::CACTUS, 0, 16);
                    $pasteque = Item::get(Item::MELON_SEEDS, 0, 16);
                    $citrouille = Item::get(Item::PUMPKIN, 0, 16);
                    if ($player->getInventory()->canAddItem($rubis) && $player->getInventory()->canAddItem($water) &&
                        $player->getInventory()->canAddItem($lava) && $player->getInventory()->canAddItem($potato) &&
                        $player->getInventory()->canAddItem($carrot) && $player->getInventory()->canAddItem($verrue) && $player->getInventory()->canAddItem($cactus) &&
                        $player->getInventory()->canAddItem($pasteque) && $player->getInventory()->canAddItem($citrouille)) {
                        $player->getInventory()->addItem($rubis);
                        $player->getInventory()->addItem($water);
                        $player->getInventory()->addItem($lava);
                        $player->getInventory()->addItem($potato);
                        $player->getInventory()->addItem($carrot);
                        $player->getInventory()->addItem($verrue);
                        $player->getInventory()->addItem($cactus);
                        $player->getInventory()->addItem($pasteque);
                        $player->getInventory()->addItem($citrouille);
                    } else {
                        $player->sendMessage(Manager::PREFIX_ALERT . "Ohoh, il semblerait que votre inventaire est plein. Veuillez prendre un screen de ce message est d'ouvrir un ticket sur notre serveur discord pour récupérer votre récompense.");

                    }
                }
            }
        }
        if (Jobs::getLevelForJob($player->getName(), "miner") < 10) {
            /** Cobblestone */
            if ($block->getId() === BlockIds::COBBLESTONE) {
                Jobs::addXpForJob($player->getName(), "miner", 0.1);
                $player->sendPopup("§6» §e+0.1 §7d'xp au métier de Mineur §6«");
            }
            /** Charbon */
            if ($block->getId() === BlockIds::COAL_ORE) {
                Jobs::addXpForJob($player->getName(), "miner", 0.2);
                $player->sendPopup("§6» §e+0.2 §7d'xp au métier de Mineur §6«");
            }
            /** Fer */
            if ($block->getId() === BlockIds::IRON_ORE) {
                Jobs::addXpForJob($player->getName(), "miner", 0.4);
                $player->sendPopup("§6» §e+0.4 §7d'xp au métier de Mineur §6«");
            }
            /** Lapis/Redstone */
            if ($block->getId() === BlockIds::LAPIS_ORE or $block->getId() === BlockIds::REDSTONE_ORE) {
                Jobs::addXpForJob($player->getName(), "miner", 0.6);
                $player->sendPopup("§6» §e+0.6 §7d'xp au métier de Mineur §6«");
            }
            /** Diamant */
            if ($block->getId() === BlockIds::DIAMOND_ORE) {
                Jobs::addXpForJob($player->getName(), "miner", 1);
                $player->sendPopup("§6» §e+1 §7d'xp au métier de Mineur §6«");
            }
            /** Emeraude */
            if ($block->getId() === BlockIds::EMERALD_ORE) {
                Jobs::addXpForJob($player->getName(), "miner", 2);
                $player->sendPopup("§6» §e+2 §7d'xp au métier de Mineur §6«");
            }
            /** Ajout du niveau */
            if (Jobs::getXpForJob($player->getName(), "miner") >= Jobs::getXpRequireForNextLevel($player->getName(), "miner")) {
                Jobs::addLevelForJob($player->getName(), "miner", 1);
                $level = Jobs::getLevelForJob($player->getName(), "miner");
                if ($level === 1) {
                    $player->sendMessage(Manager::PREFIX_INFOS . "Bravo, vous venez de passer niveau §e1 §fau métier de §eMineur§f. Vous avez gagner §ex5 Minerai Aléatoire §f!");
                    $item = Item::get(Item::GOLD_ORE, 0, 5);
                    if ($player->getInventory()->canAddItem($item)) {
                        $player->getInventory()->addItem($item);
                    } else {
                        $player->sendMessage(Manager::PREFIX_ALERT . "Ohoh, il semblerait que votre inventaire est plein. Veuillez prendre un screen de ce message est d'ouvrir un ticket sur notre serveur discord pour récupérer votre récompense.");

                    }
                } elseif ($level === 2) {
                    $player->sendMessage(Manager::PREFIX_INFOS . "Bravo, vous venez de passer niveau §e2 §fau métier de §eMineur§f. Vous avez gagner §ex2 000\u{E102} §f!");
                    Coins::addCoins($player->getName(), 2000);
                } elseif ($level === 3) {
                    $player->sendMessage(Manager::PREFIX_INFOS . "Bravo, vous venez de passer niveau §e3 §fau métier de §eMineur§f. Vous avez gagner §ex15 Minerai Aléatoire §f!");
                    $item = Item::get(Item::GOLD_ORE, 0, 15);
                    if ($player->getInventory()->canAddItem($item)) {
                        $player->getInventory()->addItem($item);
                    } else {
                        $player->sendMessage(Manager::PREFIX_ALERT . "Ohoh, il semblerait que votre inventaire est plein. Veuillez prendre un screen de ce message est d'ouvrir un ticket sur notre serveur discord pour récupérer votre récompense.");

                    }
                } elseif ($level === 4) {
                    $player->sendMessage(Manager::PREFIX_INFOS . "Bravo, vous venez de passer niveau §e4 §fau métier de §eMineur§f. Vous avez gagner §ex4 000\u{E102} §f!");
                    Coins::addCoins($player->getName(), 4000);
                } elseif ($level === 5) {
                    $player->sendMessage(Manager::PREFIX_INFOS . "Bravo, vous venez de passer niveau §e5 §fau métier de §eMineur§f. Vous avez gagner §ex128 Bouteilles d'xp §f!");
                    $item = Item::get(Item::BOTTLE_O_ENCHANTING, 0, 128);
                    if ($player->getInventory()->canAddItem($item)) {
                        $player->getInventory()->addItem($item);
                    } else {
                        $player->sendMessage(Manager::PREFIX_ALERT . "Ohoh, il semblerait que votre inventaire est plein. Veuillez prendre un screen de ce message est d'ouvrir un ticket sur notre serveur discord pour récupérer votre récompense.");

                    }
                } elseif ($level === 6) {
                    $player->sendMessage(Manager::PREFIX_INFOS . "Bravo, vous venez de passer niveau §e6 §fau métier de §eMineur§f. Vous avez gagner §ex1 Chapeau du Fermier §f!");
                    Coins::addCoins($player->getName(), 6000);
                } elseif ($level === 7) {
                    $player->sendMessage(Manager::PREFIX_INFOS . "Bravo, vous venez de passer niveau §e7 §fau métier de §eMineur§f. Vous avez gagner §ex256 Bouteilles d'xp §f!");
                    $item = Item::get(Item::BOTTLE_O_ENCHANTING, 0, 256);
                    if ($player->getInventory()->canAddItem($item)) {
                        $player->getInventory()->addItem($item);
                    } else {
                        $player->sendMessage(Manager::PREFIX_ALERT . "Ohoh, il semblerait que votre inventaire est plein. Veuillez prendre un screen de ce message est d'ouvrir un ticket sur notre serveur discord pour récupérer votre récompense.");

                    }
                } elseif ($level === 8) {
                    $player->sendMessage(Manager::PREFIX_INFOS . "Bravo, vous venez de passer niveau §e8 §fau métier de §eMineur§f. Vous avez gagner §ex8 000\u{E102} §f!");
                    Coins::addCoins($player->getName(), 8000);
                } elseif ($level === 9) {
                    $player->sendMessage(Manager::PREFIX_INFOS . "Bravo, vous venez de passer niveau §e9 §fau métier de §eMineur§f. Vous avez gagner §ex5 Graines de Rubis §fet §ex1 Pioche en diamant U3 E5 §f!");
                    $pickaxe = Item::get(Item::DIAMOND_PICKAXE);
                    $pickaxe->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(Enchantment::UNBREAKING), 3));
                    $pickaxe->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(Enchantment::EFFICIENCY), 5));
                    if ($player->getInventory()->canAddItem($pickaxe)) {
                        $player->getInventory()->addItem($pickaxe);
                    } else {
                        $player->sendMessage(Manager::PREFIX_ALERT . "Ohoh, il semblerait que votre inventaire est plein. Veuillez prendre un screen de ce message est d'ouvrir un ticket sur notre serveur discord pour récupérer votre récompense.");

                    }
                } elseif ($level === 10) {
                    $player->sendMessage(Manager::PREFIX_INFOS . "Bravo, vous venez de passer niveau §e10 §fau métier de §eMineur§f. Vous avez gagner §ex10 000\u{E102} §fet §ex1 Kit Mineur §f!");
                    Coins::addCoins($player->getName(), 10000);
                    $pickaxe = Item::get(Item::DIAMOND_PICKAXE);
                    $pickaxe->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(Enchantment::EFFICIENCY), 5));
                    $pickaxe->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(Enchantment::UNBREAKING), 3));
                    $grenat = Item::get(Item::BEETROOT_SEEDS);
                    $emerald = Item::get(Item::EMERALD, 0, 16);
                    $diamond = Item::get(Item::DIAMOND, 0, 32);
                    $iron = Item::get(Item::IRON_INGOT, 0, 128);
                    $coal = Item::get(Item::COAL, 0, 256);
                    $random = Item::get(Item::GOLD_ORE, 0, 16);
                    if ($player->getInventory()->canAddItem($pickaxe) && $player->getInventory()->canAddItem($grenat) && $player->getInventory()->canAddItem($emerald) &&
                        $player->getInventory()->canAddItem($diamond) && $player->getInventory()->canAddItem($iron) && $player->getInventory()->canAddItem($coal) &&
                        $player->getInventory()->canAddItem($random)) {
                        $player->getInventory()->addItem($pickaxe);
                        $player->getInventory()->addItem($grenat);
                        $player->getInventory()->addItem($emerald);
                        $player->getInventory()->addItem($diamond);
                        $player->getInventory()->addItem($iron);
                        $player->getInventory()->addItem($coal);
                        $player->getInventory()->addItem($random);
                    } else {
                        $player->sendMessage(Manager::PREFIX_ALERT . "Ohoh, il semblerait que votre inventaire est plein. Veuillez prendre un screen de ce message est d'ouvrir un ticket sur notre serveur discord pour récupérer votre récompense.");

                    }
                }
            }
        }
        if ($block->getId() === ItemIds::LOG) {
            $player->sendPopup("§6» §e+0.2 §7d'xp au métier de Bucheron §6«");
            Jobs::addXpForJob($player->getName(), "bucheron", 0.2);
            /** Ajout du niveau */
            if (Jobs::getXpForJob($player->getName(), "bucheron") >= Jobs::getXpRequireForNextLevel($player->getName(), "bucheron")) {
                Jobs::addLevelForJob($player->getName(), "bucheron", 1);
                $level = Jobs::getLevelForJob($player->getName(), "bucheron");
                if ($level === 1) {
                    $player->sendMessage(Manager::PREFIX_INFOS . "Bravo, vous venez de passer niveau §e1 §fau métier de §eBucheron§f. Vous venez de gagner §ex16 Minerai Aléatoire§f.");
                    $axe = Item::get(Item::GOLD_ORE, 0, 16);
                    if ($player->getInventory()->canAddItem($axe)) {
                        $player->getInventory()->addItem($axe);
                    } else {
                        $player->sendMessage(Manager::PREFIX_ALERT . "Ohoh, il semblerait que votre inventaire est plein. Veuillez prendre un screen de ce message est d'ouvrir un ticket sur notre serveur discord pour récupérer votre récompense.");
                    }
                } elseif ($level === 2) {
                    $player->sendMessage(Manager::PREFIX_INFOS . "Bravo, vous venez de passer niveau §e2 §fau métier de §eBucheron§f. Vous venez de gagner §ex2 000\u{E102}§f.");
                    Coins::addCoins($player->getName(), 2000);
                } elseif ($level === 3) {
                    $player->sendMessage(Manager::PREFIX_INFOS . "Bravo, vous venez de passer niveau §e3 §fau métier de §eBucheron§f. Vous venez de gagner §ex32 Minerai Aléatoire§f.");
                    $axe = Item::get(Item::GOLD_ORE, 0, 32);
                    if ($player->getInventory()->canAddItem($axe)) {
                        $player->getInventory()->addItem($axe);
                    } else {
                        $player->sendMessage(Manager::PREFIX_ALERT . "Ohoh, il semblerait que votre inventaire est plein. Veuillez prendre un screen de ce message est d'ouvrir un ticket sur notre serveur discord pour récupérer votre récompense.");
                    }
                } elseif ($level === 4) {
                    $player->sendMessage(Manager::PREFIX_INFOS . "Bravo, vous venez de passer niveau §e4 §fau métier de §eBucheron§f. Vous venez de gagner §ex4 000\u{E102}§f.");
                    Coins::addCoins($player->getName(), 4000);
                } elseif ($level === 5) {
                    $player->sendMessage(Manager::PREFIX_INFOS . "Bravo, vous venez de passer niveau §e5 §fau métier de §eBucheron§f. Vous venez de gagner §ex32 Bouteille d'experience§f.");
                    $axe = Item::get(Item::BOTTLE_O_ENCHANTING, 0, 32);
                    if ($player->getInventory()->canAddItem($axe)) {
                        $player->getInventory()->addItem($axe);
                    } else {
                        $player->sendMessage(Manager::PREFIX_ALERT . "Ohoh, il semblerait que votre inventaire est plein. Veuillez prendre un screen de ce message est d'ouvrir un ticket sur notre serveur discord pour récupérer votre récompense.");
                    }
                } elseif ($level === 6) {
                    $player->sendMessage(Manager::PREFIX_INFOS . "Bravo, vous venez de passer niveau §e6 §fau métier de §eBucheron§f. Vous venez de gagner §ex6 000\u{E102}§f.");
                    Coins::addCoins($player->getName(), 6000);
                } elseif ($level === 7) {
                    $player->sendMessage(Manager::PREFIX_INFOS . "Bravo, vous venez de passer niveau §e7 §fau métier de §eBucheron§f. Vous venez de gagner §ex64 Bouteilles d'experience§f.");
                    $itm = Item::get(Item::BOTTLE_O_ENCHANTING, 0, 64);
                    if ($player->getInventory()->canAddItem($itm)) {
                        $player->getInventory()->addItem($itm);
                    } else {
                        $player->sendMessage(Manager::PREFIX_ALERT . "Ohoh, il semblerait que votre inventaire est plein. Veuillez prendre un screen de ce message est d'ouvrir un ticket sur notre serveur discord pour récupérer votre récompense.");
                    }
                } elseif ($level === 8) {
                    $player->sendMessage(Manager::PREFIX_INFOS . "Bravo, vous venez de passer niveau §e8 §fau métier de §eBucheron§f. Vous venez de gagner §ex8 000\u{E102}§f.");
                    Coins::addCoins($player->getName(), 8000);
                } elseif ($level === 9) {
                    $player->sendMessage(Manager::PREFIX_INFOS . "Bravo, vous venez de passer niveau §e9 §fau métier de §eBucheron§f. Vous venez de gagner §ex1 Hache en diamant U3 E5 S1§f.");
                    $axe = Item::get(Item::DIAMOND_AXE);
                    $axe->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(Enchantment::EFFICIENCY), 5));
                    $axe->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(Enchantment::UNBREAKING), 3));
                    $axe->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(Enchantment::SILK_TOUCH), 1));
                    if ($player->getInventory()->canAddItem($axe)) {
                        $player->getInventory()->addItem($axe);
                    } else {
                        $player->sendMessage(Manager::PREFIX_ALERT . "Ohoh, il semblerait que votre inventaire est plein. Veuillez prendre un screen de ce message est d'ouvrir un ticket sur notre serveur discord pour récupérer votre récompense.");
                    }
                } elseif ($level === 10) {
                    $player->sendMessage(Manager::PREFIX_INFOS . "Bravo, vous venez de passer niveau §e10 §fau métier de §eBucheron§f. Vous venez de gagner §ex10 000\u{E102}§f.");
                    Coins::addCoins($player->getName(), 10000);
                }
            }
        }
    }

    public function onPlace(BlockPlaceEvent $event)
    {
        $player = $event->getPlayer();
        $item = $event->getItem();
        $block = $event->getBlock();
        if ($player->getGamemode() === 1) return;
        if ($player->getLevel()->getFolderName() !== SkyBlock::getIslandName($player->getName())) return;
    }

    public function onCraft(CraftItemEvent $event){
        $player = $event->getPlayer();
        foreach ($event->getOutputs() as $item){
            if ($item->getId() === ItemIds::GOLD_AXE){
                $player->sendPopup("§6» §e+1 §7d'xp au métier de Bucheron §6«");
                Jobs::addXpForJob($player->getName(), "bucheron", 1);
                /** Ajout du niveau */
                if (Jobs::getXpForJob($player->getName(), "bucheron") >= Jobs::getXpRequireForNextLevel($player->getName(), "bucheron")) {
                    Jobs::addLevelForJob($player->getName(), "bucheron", 1);
                    $level = Jobs::getLevelForJob($player->getName(), "bucheron");
                    if ($level === 1) {
                        $player->sendMessage(Manager::PREFIX_INFOS . "Bravo, vous venez de passer niveau §e1 §fau métier de §eBucheron§f. Vous venez de gagner §ex16 Minerai Aléatoire§f.");
                        $axe = Item::get(Item::GOLD_ORE, 0, 16);
                        if ($player->getInventory()->canAddItem($axe)){
                            $player->getInventory()->addItem($axe);
                        }else{
                            $player->sendMessage(Manager::PREFIX_ALERT . "Ohoh, il semblerait que votre inventaire est plein. Veuillez prendre un screen de ce message est d'ouvrir un ticket sur notre serveur discord pour récupérer votre récompense.");
                        }
                    } elseif ($level === 2) {
                        $player->sendMessage(Manager::PREFIX_INFOS . "Bravo, vous venez de passer niveau §e2 §fau métier de §eBucheron§f. Vous venez de gagner §ex2 000\u{E102}§f.");
                        Coins::addCoins($player->getName(), 2000);
                    } elseif ($level === 3) {
                        $player->sendMessage(Manager::PREFIX_INFOS . "Bravo, vous venez de passer niveau §e3 §fau métier de §eBucheron§f. Vous venez de gagner §ex32 Minerai Aléatoire§f.");
                        $axe = Item::get(Item::GOLD_ORE, 0, 32);
                        if ($player->getInventory()->canAddItem($axe)){
                            $player->getInventory()->addItem($axe);
                        }else{
                            $player->sendMessage(Manager::PREFIX_ALERT . "Ohoh, il semblerait que votre inventaire est plein. Veuillez prendre un screen de ce message est d'ouvrir un ticket sur notre serveur discord pour récupérer votre récompense.");
                        }
                    } elseif ($level === 4) {
                        $player->sendMessage(Manager::PREFIX_INFOS . "Bravo, vous venez de passer niveau §e4 §fau métier de §eBucheron§f. Vous venez de gagner §ex4 000\u{E102}§f.");
                        Coins::addCoins($player->getName(), 4000);
                    } elseif ($level === 5) {
                        $player->sendMessage(Manager::PREFIX_INFOS . "Bravo, vous venez de passer niveau §e5 §fau métier de §eBucheron§f. Vous venez de gagner §ex32 Bouteille d'experience§f.");
                        $axe = Item::get(Item::BOTTLE_O_ENCHANTING, 0, 32);
                        if ($player->getInventory()->canAddItem($axe)){
                            $player->getInventory()->addItem($axe);
                        }else{
                            $player->sendMessage(Manager::PREFIX_ALERT . "Ohoh, il semblerait que votre inventaire est plein. Veuillez prendre un screen de ce message est d'ouvrir un ticket sur notre serveur discord pour récupérer votre récompense.");
                        }
                    } elseif ($level === 6) {
                        $player->sendMessage(Manager::PREFIX_INFOS . "Bravo, vous venez de passer niveau §e6 §fau métier de §eBucheron§f. Vous venez de gagner §ex6 000\u{E102}§f.");
                        Coins::addCoins($player->getName(), 6000);
                    } elseif ($level === 7) {
                        $player->sendMessage(Manager::PREFIX_INFOS . "Bravo, vous venez de passer niveau §e7 §fau métier de §eBucheron§f. Vous venez de gagner §ex64 Bouteilles d'experience§f.");
                        $itm = Item::get(Item::BOTTLE_O_ENCHANTING, 0, 64);
                        if ($player->getInventory()->canAddItem($itm)){
                            $player->getInventory()->addItem($itm);
                        }else{
                            $player->sendMessage(Manager::PREFIX_ALERT . "Ohoh, il semblerait que votre inventaire est plein. Veuillez prendre un screen de ce message est d'ouvrir un ticket sur notre serveur discord pour récupérer votre récompense.");
                        }
                    } elseif ($level === 8) {
                        $player->sendMessage(Manager::PREFIX_INFOS . "Bravo, vous venez de passer niveau §e8 §fau métier de §eBucheron§f. Vous venez de gagner §ex8 000\u{E102}§f.");
                        Coins::addCoins($player->getName(), 8000);
                    } elseif ($level === 9) {
                        $player->sendMessage(Manager::PREFIX_INFOS . "Bravo, vous venez de passer niveau §e9 §fau métier de §eBucheron§f. Vous venez de gagner §ex1 Hache en diamant U3 E5 S1§f.");
                        $axe = Item::get(Item::DIAMOND_AXE);
                        $axe->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(Enchantment::EFFICIENCY), 5));
                        $axe->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(Enchantment::UNBREAKING), 3));
                        $axe->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(Enchantment::SILK_TOUCH), 1));
                        if ($player->getInventory()->canAddItem($axe)){
                            $player->getInventory()->addItem($axe);
                        }else{
                            $player->sendMessage(Manager::PREFIX_ALERT . "Ohoh, il semblerait que votre inventaire est plein. Veuillez prendre un screen de ce message est d'ouvrir un ticket sur notre serveur discord pour récupérer votre récompense.");
                        }
                    } elseif ($level === 10) {
                        $player->sendMessage(Manager::PREFIX_INFOS . "Bravo, vous venez de passer niveau §e10 §fau métier de §eBucheron§f. Vous venez de gagner §ex10 000\u{E102}§f.");
                        Coins::addCoins($player->getName(), 10000);
                    }
                }
            }elseif ($item->getId() === ItemIds::IRON_AXE){
                $player->sendPopup("§6» §e+0.3 §7d'xp au métier de Bucheron §6«");
                Jobs::addXpForJob($player->getName(), "bucheron", 0.3);
                /** Ajout du niveau */
                if (Jobs::getXpForJob($player->getName(), "bucheron") >= Jobs::getXpRequireForNextLevel($player->getName(), "bucheron")) {
                    Jobs::addLevelForJob($player->getName(), "bucheron", 1);
                    $level = Jobs::getLevelForJob($player->getName(), "bucheron");
                    if ($level === 1) {
                        $player->sendMessage(Manager::PREFIX_INFOS . "Bravo, vous venez de passer niveau §e1 §fau métier de §eBucheron§f. Vous venez de gagner §ex16 Minerai Aléatoire§f.");
                        $axe = Item::get(Item::GOLD_ORE, 0, 16);
                        if ($player->getInventory()->canAddItem($axe)){
                            $player->getInventory()->addItem($axe);
                        }else{
                            $player->sendMessage(Manager::PREFIX_ALERT . "Ohoh, il semblerait que votre inventaire est plein. Veuillez prendre un screen de ce message est d'ouvrir un ticket sur notre serveur discord pour récupérer votre récompense.");
                        }
                    } elseif ($level === 2) {
                        $player->sendMessage(Manager::PREFIX_INFOS . "Bravo, vous venez de passer niveau §e2 §fau métier de §eBucheron§f. Vous venez de gagner §ex2 000\u{E102}§f.");
                        Coins::addCoins($player->getName(), 2000);
                    } elseif ($level === 3) {
                        $player->sendMessage(Manager::PREFIX_INFOS . "Bravo, vous venez de passer niveau §e3 §fau métier de §eBucheron§f. Vous venez de gagner §ex32 Minerai Aléatoire§f.");
                        $axe = Item::get(Item::GOLD_ORE, 0, 32);
                        if ($player->getInventory()->canAddItem($axe)){
                            $player->getInventory()->addItem($axe);
                        }else{
                            $player->sendMessage(Manager::PREFIX_ALERT . "Ohoh, il semblerait que votre inventaire est plein. Veuillez prendre un screen de ce message est d'ouvrir un ticket sur notre serveur discord pour récupérer votre récompense.");
                        }
                    } elseif ($level === 4) {
                        $player->sendMessage(Manager::PREFIX_INFOS . "Bravo, vous venez de passer niveau §e4 §fau métier de §eBucheron§f. Vous venez de gagner §ex4 000\u{E102}§f.");
                        Coins::addCoins($player->getName(), 4000);
                    } elseif ($level === 5) {
                        $player->sendMessage(Manager::PREFIX_INFOS . "Bravo, vous venez de passer niveau §e5 §fau métier de §eBucheron§f. Vous venez de gagner §ex32 Bouteille d'experience§f.");
                        $axe = Item::get(Item::BOTTLE_O_ENCHANTING, 0, 32);
                        if ($player->getInventory()->canAddItem($axe)){
                            $player->getInventory()->addItem($axe);
                        }else{
                            $player->sendMessage(Manager::PREFIX_ALERT . "Ohoh, il semblerait que votre inventaire est plein. Veuillez prendre un screen de ce message est d'ouvrir un ticket sur notre serveur discord pour récupérer votre récompense.");
                        }
                    } elseif ($level === 6) {
                        $player->sendMessage(Manager::PREFIX_INFOS . "Bravo, vous venez de passer niveau §e6 §fau métier de §eBucheron§f. Vous venez de gagner §ex6 000\u{E102}§f.");
                        Coins::addCoins($player->getName(), 6000);
                    } elseif ($level === 7) {
                        $player->sendMessage(Manager::PREFIX_INFOS . "Bravo, vous venez de passer niveau §e7 §fau métier de §eBucheron§f. Vous venez de gagner §ex64 Bouteilles d'experience§f.");
                        $itm = Item::get(Item::BOTTLE_O_ENCHANTING, 0, 64);
                        if ($player->getInventory()->canAddItem($itm)){
                            $player->getInventory()->addItem($itm);
                        }else{
                            $player->sendMessage(Manager::PREFIX_ALERT . "Ohoh, il semblerait que votre inventaire est plein. Veuillez prendre un screen de ce message est d'ouvrir un ticket sur notre serveur discord pour récupérer votre récompense.");
                        }
                    } elseif ($level === 8) {
                        $player->sendMessage(Manager::PREFIX_INFOS . "Bravo, vous venez de passer niveau §e8 §fau métier de §eBucheron§f. Vous venez de gagner §ex8 000\u{E102}§f.");
                        Coins::addCoins($player->getName(), 8000);
                    } elseif ($level === 9) {
                        $player->sendMessage(Manager::PREFIX_INFOS . "Bravo, vous venez de passer niveau §e9 §fau métier de §eBucheron§f. Vous venez de gagner §ex1 Hache en diamant U3 E5 S1§f.");
                        $axe = Item::get(Item::DIAMOND_AXE);
                        $axe->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(Enchantment::EFFICIENCY), 5));
                        $axe->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(Enchantment::UNBREAKING), 3));
                        $axe->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(Enchantment::SILK_TOUCH), 1));
                        if ($player->getInventory()->canAddItem($axe)){
                            $player->getInventory()->addItem($axe);
                        }else{
                            $player->sendMessage(Manager::PREFIX_ALERT . "Ohoh, il semblerait que votre inventaire est plein. Veuillez prendre un screen de ce message est d'ouvrir un ticket sur notre serveur discord pour récupérer votre récompense.");
                        }
                    } elseif ($level === 10) {
                        $player->sendMessage(Manager::PREFIX_INFOS . "Bravo, vous venez de passer niveau §e10 §fau métier de §eBucheron§f. Vous venez de gagner §ex10 000\u{E102}§f.");
                        Coins::addCoins($player->getName(), 10000);
                    }
                }
            }elseif ($item->getId() === ItemIds::DIAMOND_AXE){
                $player->sendPopup("§6» §e+0.6 §7d'xp au métier de Bucheron §6«");
                Jobs::addXpForJob($player->getName(), "bucheron", 0.6);
                /** Ajout du niveau */
                if (Jobs::getXpForJob($player->getName(), "bucheron") >= Jobs::getXpRequireForNextLevel($player->getName(), "bucheron")) {
                    Jobs::addLevelForJob($player->getName(), "bucheron", 1);
                    $level = Jobs::getLevelForJob($player->getName(), "bucheron");
                    if ($level === 1) {
                        $player->sendMessage(Manager::PREFIX_INFOS . "Bravo, vous venez de passer niveau §e1 §fau métier de §eBucheron§f. Vous venez de gagner §ex16 Minerai Aléatoire§f.");
                        $axe = Item::get(Item::GOLD_ORE, 0, 16);
                        if ($player->getInventory()->canAddItem($axe)){
                            $player->getInventory()->addItem($axe);
                        }else{
                            $player->sendMessage(Manager::PREFIX_ALERT . "Ohoh, il semblerait que votre inventaire est plein. Veuillez prendre un screen de ce message est d'ouvrir un ticket sur notre serveur discord pour récupérer votre récompense.");
                        }
                    } elseif ($level === 2) {
                        $player->sendMessage(Manager::PREFIX_INFOS . "Bravo, vous venez de passer niveau §e2 §fau métier de §eBucheron§f. Vous venez de gagner §ex2 000\u{E102}§f.");
                        Coins::addCoins($player->getName(), 2000);
                    } elseif ($level === 3) {
                        $player->sendMessage(Manager::PREFIX_INFOS . "Bravo, vous venez de passer niveau §e3 §fau métier de §eBucheron§f. Vous venez de gagner §ex32 Minerai Aléatoire§f.");
                        $axe = Item::get(Item::GOLD_ORE, 0, 32);
                        if ($player->getInventory()->canAddItem($axe)){
                            $player->getInventory()->addItem($axe);
                        }else{
                            $player->sendMessage(Manager::PREFIX_ALERT . "Ohoh, il semblerait que votre inventaire est plein. Veuillez prendre un screen de ce message est d'ouvrir un ticket sur notre serveur discord pour récupérer votre récompense.");
                        }
                    } elseif ($level === 4) {
                        $player->sendMessage(Manager::PREFIX_INFOS . "Bravo, vous venez de passer niveau §e4 §fau métier de §eBucheron§f. Vous venez de gagner §ex4 000\u{E102}§f.");
                        Coins::addCoins($player->getName(), 4000);
                    } elseif ($level === 5) {
                        $player->sendMessage(Manager::PREFIX_INFOS . "Bravo, vous venez de passer niveau §e5 §fau métier de §eBucheron§f. Vous venez de gagner §ex32 Bouteille d'experience§f.");
                        $axe = Item::get(Item::BOTTLE_O_ENCHANTING, 0, 32);
                        if ($player->getInventory()->canAddItem($axe)){
                            $player->getInventory()->addItem($axe);
                        }else{
                            $player->sendMessage(Manager::PREFIX_ALERT . "Ohoh, il semblerait que votre inventaire est plein. Veuillez prendre un screen de ce message est d'ouvrir un ticket sur notre serveur discord pour récupérer votre récompense.");
                        }
                    } elseif ($level === 6) {
                        $player->sendMessage(Manager::PREFIX_INFOS . "Bravo, vous venez de passer niveau §e6 §fau métier de §eBucheron§f. Vous venez de gagner §ex6 000\u{E102}§f.");
                        Coins::addCoins($player->getName(), 6000);
                    } elseif ($level === 7) {
                        $player->sendMessage(Manager::PREFIX_INFOS . "Bravo, vous venez de passer niveau §e7 §fau métier de §eBucheron§f. Vous venez de gagner §ex64 Bouteilles d'experience§f.");
                        $itm = Item::get(Item::BOTTLE_O_ENCHANTING, 0, 64);
                        if ($player->getInventory()->canAddItem($itm)){
                            $player->getInventory()->addItem($itm);
                        }else{
                            $player->sendMessage(Manager::PREFIX_ALERT . "Ohoh, il semblerait que votre inventaire est plein. Veuillez prendre un screen de ce message est d'ouvrir un ticket sur notre serveur discord pour récupérer votre récompense.");
                        }
                    } elseif ($level === 8) {
                        $player->sendMessage(Manager::PREFIX_INFOS . "Bravo, vous venez de passer niveau §e8 §fau métier de §eBucheron§f. Vous venez de gagner §ex8 000\u{E102}§f.");
                        Coins::addCoins($player->getName(), 8000);
                    } elseif ($level === 9) {
                        $player->sendMessage(Manager::PREFIX_INFOS . "Bravo, vous venez de passer niveau §e9 §fau métier de §eBucheron§f. Vous venez de gagner §ex1 Hache en diamant U3 E5 S1§f.");
                        $axe = Item::get(Item::DIAMOND_AXE);
                        $axe->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(Enchantment::EFFICIENCY), 5));
                        $axe->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(Enchantment::UNBREAKING), 3));
                        $axe->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(Enchantment::SILK_TOUCH), 1));
                        if ($player->getInventory()->canAddItem($axe)){
                            $player->getInventory()->addItem($axe);
                        }else{
                            $player->sendMessage(Manager::PREFIX_ALERT . "Ohoh, il semblerait que votre inventaire est plein. Veuillez prendre un screen de ce message est d'ouvrir un ticket sur notre serveur discord pour récupérer votre récompense.");
                        }
                    } elseif ($level === 10) {
                        $player->sendMessage(Manager::PREFIX_INFOS . "Bravo, vous venez de passer niveau §e10 §fau métier de §eBucheron§f. Vous venez de gagner §ex10 000\u{E102}§f.");
                        Coins::addCoins($player->getName(), 10000);
                    }
                }
            }
        }
    }

}