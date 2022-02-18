<?php

namespace Zoumi\Core\commands\all;

use jojoe77777\FormAPI\CustomForm;
use jojoe77777\FormAPI\SimpleForm;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\item\Item;
use pocketmine\Player;
use Zoumi\Core\api\Coins;
use Zoumi\Core\Main;
use Zoumi\Core\Manager;

class Shop extends Command {

    public function __construct(string $name, string $description = "", string $usageMessage = null, array $aliases = [])
    {
        parent::__construct($name, $description, $usageMessage, $aliases);
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        if ($sender instanceof Player){
            Shop::sendShopMenu($sender);
        }
    }

    public static function sendShopMenu(Player $player){
        $ui = new SimpleForm(function (Player $player, $data){
            if ($data === null){
                return;
            }
            switch ($data){
                case 0:
                    Shop::sendOreMenu($player);
                    break;
                case 1:
                    Shop::sendBlocMenu($player);
                    break;
                case 2:
                    Shop::sendFarmMenu($player);
                    break;
                case 3:
                    Shop::sendMobMenu($player);
                    break;
                case 4:
                    Shop::sendSpawner($player);
                    break;
                case 5:
                    Shop::sendUtils($player);
                    break;
                case 6:
                    Shop::sendBois($player);
                    break;
            }
        });
        $ui->setTitle("§l§7Moon§elight §f- §dSkyBlock");
        $ui->setContent("§7Bob: §fDans quelle catégorie veux-tu aller ?");
        $ui->addButton("Minerais", 0, "textures/items/coal");
        $ui->addButton("Bloc", 0, "textures/shop/cobblestone");
        $ui->addButton("Culture", 0, "textures/items/potato");
        $ui->addButton("Mob", 0, "textures/items/bone");
        $ui->addButton("Spawner", 0, "textures/shop/mob_spawner");
        $ui->addButton("Utils", 0, "textures/shop/enderchest");
        $ui->addButton("Bois", 0, "textures/shop/log_chene");
        $ui->sendToPlayer($player);
    }

    public static function sendBois(Player $player){
        $ui = new SimpleForm(function (Player $player, $data){
            if ($data === null){
                return;
            }
            switch ($data){
                case 0:
                    Shop::sendBuySellMenu($player, 50, 0, Item::get(6));
                    break;
                case 1:
                    Shop::sendBuySellMenu($player, 50, 0, Item::get(6, 2));
                    break;
                case 2:
                    Shop::sendBuySellMenu($player, 50, 0, Item::get(6, 3));
                    break;
                case 3:
                    Shop::sendBuySellMenu($player, 50, 0, Item::get(6, 1));
                    break;
                case 4:
                    Shop::sendBuySellMenu($player, 10, 0, Item::get(5));
                    break;
                case 5:
                    Shop::sendBuySellMenu($player, 10, 0, Item::get(5, 2));
                    break;
                case 6:
                    Shop::sendBuySellMenu($player, 10, 0, Item::get(5, 3));
                    break;
                case 7:
                    Shop::sendBuySellMenu($player, 10, 0, Item::get(5, 1));
                    break;
                case 8:
                    Shop::sendShopMenu($player);
                    break;
            }
        });
        $ui->setTitle("§l§7Moonlight §f- §dSkyBlock");
        $ui->addButton("Pousse de chêne", 0, "textures/shop/sapling_chene");
        $ui->addButton("Pousse de bouleau", 0, "textures/shop/sapling_bouleau");
        $ui->addButton("Pousse tropical", 0, "textures/shop/sapling_tropical");
        $ui->addButton("Pousse de sapin", 0, "textures/shop/sapling_spruce");
        $ui->addButton("Bois de chêne", 0, "textures/shop/log_chene");
        $ui->addButton("Bois de bouleau", 0, "textures/shop/log_bouleau");
        $ui->addButton("Bois tropical", 0, "textures/shop/log_tropical");
        $ui->addButton("Bois de sapin", 0, "textures/shop/log_spruce");
        $ui->addButton("§l§c<- RETOUR");
        $ui->sendToPlayer($player);
    }

    public static function sendUtils(Player $player){
        $ui = new SimpleForm(function (Player $player, $data){
            if ($data === null){
                return;
            }
            switch ($data){
                case 0:
                    Shop::sendBuySellMenu($player, 500, 0, Item::get(Item::ENDER_CHEST));
                    break;
                case 1:
                    Shop::sendShopMenu($player);
                    break;
            }
        });
        $ui->setTitle("§l§7Moon§elight §f- §dSkyBlock");
        $ui->addButton("EnderChest", 0, "textures/shop/enderchest");
        $ui->addButton("§l§c<- RETOUR");
        $ui->sendToPlayer($player);
    }

    public static function sendOreMenu(Player $player){
        $ui = new SimpleForm(function (Player $player, $data){
            if ($data === null){
                return;
            }
            switch ($data){
                case 0:
                    Shop::sendBuySellMenu($player, 10, 1, Item::get(Item::COAL));
                    break;
                case 1:
                    Shop::sendBuySellMenu($player, 20, 3, Item::get(Item::IRON_INGOT));
                    break;
                case 2:
                    Shop::sendBuySellMenu($player, 7, 1, Item::get(Item::REDSTONE));
                    break;
                case 3:
                    Shop::sendBuySellMenu($player, 7, 1, Item::get(Item::DYE, 4));
                    break;
                case 4:
                    Shop::sendBuySellMenu($player, 30, 5, Item::get(Item::DIAMOND));
                    break;
                case 5:
                    Shop::sendBuySellMenu($player, 40, 7, Item::get(Item::EMERALD));
                    break;
                case 6:
                    Shop::sendShopMenu($player);
                    break;
            }
        });
        $ui->setTitle("§l§7Moon§elight §f- §dSkyBlock");
        $ui->addButton("Charbon", 0, "textures/items/coal");
        $ui->addButton("Fer", 0, "textures/items/iron_ingot");
        $ui->addButton("Redstone", 0, "textures/shop/redstone");
        $ui->addButton("Lapis lazuli", 0, "textures/items/lapis-lazuli");
        $ui->addButton("Diamant", 0, "textures/items/diamond");
        $ui->addButton("Emeraude", 0, "textures/items/emerald");
        $ui->addButton("§l§c<- RETOUR");
        $ui->sendToPlayer($player);
    }

    public static function sendBlocMenu(Player $player){
        $ui = new SimpleForm(function (Player $player, $data){
            if ($data === null){
                return;
            }
            switch ($data){
                case 0:
                    Shop::sendBuySellMenu($player, 10, 5, Item::get(Item::DIRT));
                    break;
                case 1:
                    Shop::sendBuySellMenu($player, 10, 5, Item::get(Item::SAND));
                    break;
                case 2:
                    Shop::sendBuySellMenu($player, 35, 17.5, Item::get(Item::SOUL_SAND));
                    break;
                case 3:
                    Shop::sendBuySellMenu($player, 0, 0.1, Item::get(Item::COBBLESTONE));
                    break;
                case 4:
                    Shop::sendBuySellMenu($player, 15, 7.5, Item::get(Item::QUARTZ_BLOCK));
                    break;
                case 5:
                    Shop::sendBuySellMenu($player, 15, 7.5, Item::get(Item::GLASS));
                    break;
                case 6:
                    Shop::sendBuySellMenu($player, 15, 7.5, Item::get(Item::BRICK_BLOCK));
                    break;
                case 7:
                    Shop::sendBuySellMenu($player, 15, 7.5, Item::get(Item::GRAVEL));
                    break;
                case 8:
                    Shop::sendBuySellMenu($player, 20, 10, Item::get(Item::OBSIDIAN));
                    break;
                case 9:
                    Shop::sendBuySellMenu($player, 15, 7.5, Item::get(Item::GLOWSTONE));
                    break;
                case 10:
                    Shop::sendShopMenu($player);
                    break;
            }
        });
        $ui->setTitle("§l§7Moon§elight §f- §dSkyBlock");
        $ui->addButton("Terre", 0, "textures/shop/dirt");
        $ui->addButton("Sable", 0, "textures/shop/sand");
        $ui->addButton("Sable des âmes", 0, "textures/shop/soul_sand");
        $ui->addButton("Pierre taillé", 0, "textures/shop/cobblestone");
        $ui->addButton("Quartz", 0, "textures/shop/quartz");
        $ui->addButton("Verre", 0, "textures/shop/glass");
        $ui->addButton("Brique", 0, "textures/shop/brick");
        $ui->addButton("Gravier", 0, "textures/shop/gravel");
        $ui->addButton("Obsidienne", 0, "textures/shop/obsidian");
        $ui->addButton("Pierre lumineuse", 0, "textures/shop/glowstone");
        $ui->addButton("§c§l<- RETOUR");
        $ui->sendToPlayer($player);
    }

    public static function sendFarmMenu(Player $player){
        $ui = new SimpleForm(function (Player $player, $data){
            if ($data === null){
                return;
            }
            switch ($data){
                case 0:
                    Shop::sendBuySellMenu($player, 20, Main::getInstance()->bourse->get("cactus"), Item::get(Item::CACTUS));
                    break;
                case 1:
                    Shop::sendBuySellMenu($player, 0, Main::getInstance()->bourse->get("ble"), Item::get(Item::WHEAT));
                    break;
                case 2:
                    Shop::sendBuySellMenu($player, 15, Main::getInstance()->bourse->get("carotte"), Item::get(Item::CARROT));
                    break;
                case 3:
                    Shop::sendBuySellMenu($player, 15, Main::getInstance()->bourse->get("patate"), Item::get(Item::POTATO));
                    break;
                case 4:
                    Shop::sendBuySellMenu($player, 30, Main::getInstance()->bourse->get("verrue"), Item::get(Item::NETHER_WART));
                    break;
                case 5:
                    Shop::sendBuySellMenu($player, 0, 1, Item::get(Item::PUMPKIN));
                    break;
                case 6:
                    Shop::sendBuySellMenu($player, 0, 1, Item::get(Item::MELON_BLOCK));
                    break;
                case 7:
                    Shop::sendBuySellMenu($player, 8, Main::getInstance()->bourse->get("canne"), Item::get(Item::REEDS));
                    break;
                case 8:
                    Shop::sendBuySellMenu($player, 0, 1, Item::get(Item::BEETROOT));
                    break;
                case 9:
                    Shop::sendBuySellMenu($player, 10, 0, Item::get(Item::WATER));
                    break;
                case 10:
                    Shop::sendBuySellMenu($player, 10, 0, Item::get(Item::LAVA));
                    break;
                case 11:
                    Shop::sendShopMenu($player);
                    break;
            }
        });
        $ui->setTitle("§l§7Moon§elight §f- §dSkyBlock");
        $ui->addButton("Cactus", 0, "textures/shop/cactus");
        $ui->addButton("Blé", 0, "textures/items/wheat");
        $ui->addButton("Carotte", 0, "textures/items/carrot");
        $ui->addButton("Patate", 0, "textures/items/potato");
        $ui->addButton("Verrue du nether", 0, "textures/items/nether_wart");
        $ui->addButton("Citrouille", 0, "textures/shop/pumpkin");
        $ui->addButton("Pastèque", 0, "textures/shop/melon");
        $ui->addButton("Canne à sucre", 0, "textures/items/reeds");
        $ui->addButton("Betterave", 0, "textures/items/beetroots");
        $ui->addButton("Eau", 0, "textures/shop/water");
        $ui->addButton("Lave", 0, "textures/shop/lava");
        $ui->addButton("§l§c<- RETOUR");
        $ui->sendToPlayer($player);
    }

    public static function sendMobMenu(Player $player){
        $ui = new SimpleForm(function (Player $player, $data){
            if ($data === null){
                return;
            }
            switch ($data){
                case 0:
                    Shop::sendBuySellMenu($player, 15, 3, Item::get(Item::LEATHER));
                    break;
                case 1:
                    Shop::sendBuySellMenu($player, 0, 4, Item::get(Item::ROTTEN_FLESH));
                    break;
                case 2:
                    Shop::sendBuySellMenu($player, 10, 2, Item::get(Item::BONE));
                    break;
                case 3:
                    Shop::sendBuySellMenu($player, 0, 5, Item::get(Item::ENDER_PEARL));
                    break;
                case 4:
                    Shop::sendBuySellMenu($player, 0, 2.5, Item::get(Item::STRING));
                    break;
                case 5:
                    Shop::sendBuySellMenu($player, 0, 1, Item::get(Item::BEEF));
                    break;
                case 6:
                    Shop::sendBuySellMenu($player, 0, 1, Item::get(Item::PORKCHOP));
                    break;
                case 7:
                    Shop::sendBuySellMenu($player, 0, 1, Item::get(Item::MUTTON));
                    break;
                case 8:
                    Shop::sendBuySellMenu($player, 0, 8, Item::get(Item::GUNPOWDER));
                    break;
                case 9:
                    Shop::sendShopMenu($player);
                    break;
            }
        });
        $ui->setTitle("§l§7Moon§elight §f- §dSkyBlock");
        $ui->addButton("Cuir", 0, "textures/items/leather");
        $ui->addButton("Viande de zombie", 0, "textures/items/rotten_flesh");
        $ui->addButton("Os", 0, "textures/items/bone");
        $ui->addButton("Perle du néant", 0, "textures/shop/enderpearl");
        $ui->addButton("Ficelle", 0, "textures/items/string");
        $ui->addButton("Viande de vache", 0, "textures/shop/beef");
        $ui->addButton("Viande de porc", 0, "textures/shop/porkchop");
        $ui->addButton("Viande de mouton", 0, "textures/shop/mutton");
        $ui->addButton("Poudre à canon", 0, "textures/shop/poudre");
        $ui->addButton("§l§c<- RETOUR");
        $ui->sendToPlayer($player);
    }

    public static function sendSpawner(Player $player){
        $ui = new SimpleForm(function (Player $player, $data){
            if ($data === null){
                return;
            }
            switch ($data){
                case 0:
                    Shop::sendBuySellMenu($player, 100000, 0, Item::get(Item::MOB_SPAWNER));
                    break;
                case 1:
                    Shop::sendBuySellMenu($player, 250000, 0, Item::get(Item::SPAWN_EGG, 11));
                    break;
                case 2:
                    Shop::sendBuySellMenu($player, 250000, 0, Item::get(Item::SPAWN_EGG, 13));
                    break;
                case 3:
                    Shop::sendBuySellMenu($player, 250000, 0, Item::get(Item::SPAWN_EGG, 12));
                    break;
                case 4:
                    Shop::sendBuySellMenu($player, 350000, 0, Item::get(Item::SPAWN_EGG, 32));
                    break;
                case 5:
                    Shop::sendBuySellMenu($player, 350000, 0, Item::get(Item::SPAWN_EGG, 33));
                    break;
                case 6:
                    Shop::sendBuySellMenu($player, 350000, 0, Item::get(Item::SPAWN_EGG, 35));
                    break;
                case 7:
                    Shop::sendBuySellMenu($player, 500000, 0, Item::get(Item::SPAWN_EGG, 34));
                    break;
                case 8:
                    Shop::sendBuySellMenu($player, 500000, 0, Item::get(Item::SPAWN_EGG, 38));
                    break;
                case 9:
                    Shop::sendShopMenu($player);
                    break;
            }
        });
        $ui->setTitle("§l§7Moon§elight §f- §dSkyBlock");
        $ui->addButton("Spawner", 0, "textures/shop/mob_spawner");
        $ui->addButton("Oeuf de vache", 0, "textures/items/egg_cow");
        $ui->addButton("Oeuf de mouton", 0, "textures/items/egg_sheep");
        $ui->addButton("Oeuf de cochon", 0, "textures/items/egg_pig");
        $ui->addButton("Oeuf de zombie", 0, "textures/items/egg_zombie");
        $ui->addButton("Oeuf de creeper", 0, "textures/items/egg_creeper");
        $ui->addButton("Oeuf d'araignée", 0, "textures/items/egg_spider");
        $ui->addButton("Oeuf de squelette", 0, "textures/items/egg_skeleton");
        $ui->addButton("Oeuf d'enderman", 0, "textures/items/egg_enderman");
        $ui->addButton("§l§c<- RETOUR");
        $ui->sendToPlayer($player);
    }

    public static function sendBuySellMenu(Player $player, int $buy, float $sell, Item $item){
        $ui = new CustomForm(function (Player $player, $data) use ($buy, $sell, $item){
            if ($data === null){
                return;
            }
            if ($data[1] === 0){
                if ($buy === 0){
                    $player->sendMessage(Manager::PREFIX_ALERT . "Cet item n'est pas achetable.");
                    return;
                }
                if (empty($data[2]) or $data[2] < 1){
                    $player->sendMessage(Manager::PREFIX_ALERT . "Vous devez entré un nombre plus grand ou égale à 1.");
                    return;
                }
                if (Coins::getCoins($player->getName()) >= $buy * $data[2]){
                    $itm = Item::get($item->getId(), $item->getDamage(), $data[2]);
                    if ($player->getInventory()->canAddItem($itm)){
                        Coins::removeCoins($player->getName(), $buy * $data[2]);
                        $player->getInventory()->addItem($itm);
                        $player->sendMessage(Manager::PREFIX_INFOS . "Vous venez d'acheter §ex" . $data[2] . " " . $item->getName() . " §fpour " . $buy * $data[2] . "\u{E102}.");
                        return;
                    }else{
                        $player->sendMessage(Manager::PREFIX_ALERT . "Vous n'avez pas asser de place dans votre inventaire.");
                        return;
                    }
                }else{
                    $player->sendMessage(Manager::PREFIX_ALERT . "Vous ne possédez pas le nombre de coins requis.");
                    return;
                }
            }elseif ($data[1] === 1) {
                $itm = $player->getInventory()->getItemInHand();
                if ($itm->getId() !== $item->getId() && $itm->getDamage() !== $item->getDamage()){
                    $player->sendMessage(Manager::PREFIX_ALERT . "L'item dans votre main n'est pas §e" . $item->getName() . "§f.");
                    return;
                }
                if ($sell <= 0){
                    $player->sendMessage(Manager::PREFIX_ALERT . "Cet item n'est pas vendable.");
                    return;
                }
                $count = $player->getInventory()->getItemInHand()->getCount() * $sell;
                $cn = $itm->getCount();
                $player->getInventory()->removeItem($player->getInventory()->getItemInHand());
                Coins::addCoins($player->getName(), $count);
                $player->sendMessage(Manager::PREFIX_INFOS . "Vous avez vendue §ex" . $cn . " " . $item->getName() . " §fpour $count\u{E102}.");
                return;
            }elseif ($data[1] === 2){
                if ($sell <= 0){
                    $player->sendMessage(Manager::PREFIX_ALERT . "Cet item n'est pas vendable.");
                    return;
                }
                $count = 0;
                if ($player->getInventory()->contains($item)){
                    foreach ($player->getInventory()->getContents() as $itm){
                        if ($itm->getId() === $item->getId() && $itm->getDamage() === $item->getDamage()){
                            $count += $itm->getCount();
                        }
                    }
                }
                if ($count === 0){
                    $player->sendMessage(Manager::PREFIX_ALERT . "Vous ne possédez pas l'item §e" . $item->getName() . "§f.");
                    return;
                }
                $player->getInventory()->remove($item);
                Coins::addCoins($player->getName(), $sell * $count);
                $player->sendMessage(Manager::PREFIX_INFOS . "Vous venez de vendre §ex" . $count . " " . $item->getName() . " §fpour " . $sell * $count . "\u{E102}.");
                return;
            }
        });
        $count = 0;
        if ($player->getInventory()->contains($item)){
            foreach ($player->getInventory()->getContents() as $itm){
                if ($itm->getId() === $item->getId() && $itm->getDamage() === $item->getDamage()){
                    $count += $itm->getCount();
                }
            }
        }
        $ui->setTitle("§l§7Moon§elight §f- §dSkyBlock");
        $ui->addLabel("§7À l'achat: §f{$buy}/u\u{E102}\n§7À la vente: §f{$sell}/u\u{E102}\n\n§fTu possèdes §ex" . $count . " " . $item->getName() . " §fdans ton inventaire.");
        $ui->addDropdown("§7Bob: §fQue souhaites-tu faire ?", ["Acheter", "Vendre", "Tout Vendre"]);
        $ui->addInput("§7Bob: §fCombien ?", "Si vous avez sélectionner \"Acheter\".");
        $ui->sendToPlayer($player);
    }

}