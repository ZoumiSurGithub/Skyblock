<?php

namespace Zoumi\Core\commands\all;

use jojoe77777\FormAPI\SimpleForm;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\item\enchantment\Enchantment;
use pocketmine\item\enchantment\EnchantmentInstance;
use pocketmine\item\Item;
use pocketmine\Player;
use Zoumi\Core\Main;
use Zoumi\Core\Manager;

class Kit extends Command {

    public function __construct(string $name, string $description = "", string $usageMessage = null, array $aliases = [])
    {
        parent::__construct($name, $description, $usageMessage, $aliases);
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        if ($sender instanceof Player){
            Kit::sendKitMenu($sender);
        }
    }

    public static function sendKitMenu(Player $player){
        $ui = new SimpleForm(function (Player $player, $data){
            if ($data === null){
                return;
            }
            switch ($data){
                case 0:
                    if (time() >= Main::getInstance()->cooldown->get($player->getName() . "-kit-joueur")){
                        $helmet = Item::get(Item::IRON_HELMET);
                        $helmet->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(Enchantment::PROTECTION), 1));
                        $chestplate = Item::get(Item::IRON_CHESTPLATE);
                        $chestplate->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(Enchantment::PROTECTION), 1));
                        $leggings = Item::get(Item::IRON_LEGGINGS);
                        $leggings->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(Enchantment::PROTECTION), 1));
                        $boots = Item::get(Item::IRON_BOOTS);
                        $boots->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(Enchantment::PROTECTION), 1));
                        $sword = Item::get(Item::IRON_SWORD);
                        $pickaxe = Item::get(Item::IRON_PICKAXE);
                        $pickaxe->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(Enchantment::EFFICIENCY), 2));
                        $shovel = Item::get(Item::IRON_SHOVEL);
                        $shovel->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(Enchantment::EFFICIENCY), 2));
                        $axe = Item::get(Item::IRON_AXE);
                        $axe->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(Enchantment::EFFICIENCY), 2));
                        $player->getInventory()->addItem($helmet);
                        $player->getInventory()->addItem($chestplate);
                        $player->getInventory()->addItem($leggings);
                        $player->getInventory()->addItem($boots);
                        $player->getInventory()->addItem($sword);
                        $player->getInventory()->addItem($pickaxe);
                        $player->getInventory()->addItem($axe);
                        $player->getInventory()->addItem($shovel);
                        $player->getInventory()->addItem(Item::get(Item::IRON_HOE));
                        $player->sendMessage(Manager::PREFIX_INFOS . "Vous venez de recevoir le kit joueur avec succès.");
                        $config = Main::getInstance()->cooldown;
                        $config->set($player->getName() . "-kit-joueur", time() + 86400);
                        $config->save();
                        return;
                    }else{
                        $player->sendMessage(Manager::PREFIX_ALERT . "Vous devez patienter encore §e" . Main::getInstance()->convert(Main::getInstance()->cooldown->get($player->getName() . "-kit-joueur") - time()) . "§f.");
                        return;
                    }
                    break;
                case 1:
                    if (time() >= Main::getInstance()->cooldown->get($player->getName() . "-kit-youboost")){
                        $helmet = Item::get(Item::IRON_HELMET);
                        $helmet->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(Enchantment::PROTECTION), 2));
                        $chestplate = Item::get(Item::IRON_CHESTPLATE);
                        $chestplate->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(Enchantment::PROTECTION), 2));
                        $leggings = Item::get(Item::IRON_LEGGINGS);
                        $leggings->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(Enchantment::PROTECTION), 2));
                        $boots = Item::get(Item::IRON_BOOTS);
                        $boots->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(Enchantment::PROTECTION), 2));
                        $sword = Item::get(Item::IRON_SWORD);
                        $pickaxe = Item::get(Item::IRON_PICKAXE);
                        $pickaxe->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(Enchantment::EFFICIENCY), 3));
                        $shovel = Item::get(Item::IRON_SHOVEL);
                        $shovel->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(Enchantment::EFFICIENCY), 3));
                        $axe = Item::get(Item::IRON_AXE);
                        $axe->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(Enchantment::EFFICIENCY), 3));
                        $player->getInventory()->addItem($helmet);
                        $player->getInventory()->addItem($chestplate);
                        $player->getInventory()->addItem($leggings);
                        $player->getInventory()->addItem($boots);
                        $player->getInventory()->addItem($sword);
                        $player->getInventory()->addItem($pickaxe);
                        $player->getInventory()->addItem($axe);
                        $player->getInventory()->addItem($shovel);
                        $player->getInventory()->addItem(Item::get(Item::IRON_HOE));
                        $player->sendMessage(Manager::PREFIX_INFOS . "Vous venez de recevoir le kit youtuber/booster avec succès.");
                        $config = Main::getInstance()->cooldown;
                        $config->set($player->getName() . "-kit-youboost", time() + 86400);
                        $config->save();
                        return;
                    }else{
                        $player->sendMessage(Manager::PREFIX_ALERT . "Vous devez patienter encore §e" . Main::getInstance()->convert(Main::getInstance()->cooldown->get($player->getName() . "-kit-youboost") - time()) . "§f.");
                        return;
                    }
                    break;
                case 2:
                    if (time() >= Main::getInstance()->cooldown->get($player->getName() . "-kit-vip")){
                        $helmet = Item::get(Item::IRON_HELMET);
                        $helmet->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(Enchantment::PROTECTION), 3));
                        $chestplate = Item::get(Item::IRON_CHESTPLATE);
                        $chestplate->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(Enchantment::PROTECTION), 3));
                        $leggings = Item::get(Item::IRON_LEGGINGS);
                        $leggings->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(Enchantment::PROTECTION), 3));
                        $boots = Item::get(Item::IRON_BOOTS);
                        $boots->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(Enchantment::PROTECTION), 3));
                        $sword = Item::get(Item::IRON_SWORD);
                        $pickaxe = Item::get(Item::IRON_PICKAXE);
                        $pickaxe->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(Enchantment::EFFICIENCY), 4));
                        $shovel = Item::get(Item::IRON_SHOVEL);
                        $shovel->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(Enchantment::EFFICIENCY), 4));
                        $axe = Item::get(Item::IRON_AXE);
                        $axe->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(Enchantment::EFFICIENCY), 4));
                        $player->getInventory()->addItem($helmet);
                        $player->getInventory()->addItem($chestplate);
                        $player->getInventory()->addItem($leggings);
                        $player->getInventory()->addItem($boots);
                        $player->getInventory()->addItem($sword);
                        $player->getInventory()->addItem($pickaxe);
                        $player->getInventory()->addItem($axe);
                        $player->getInventory()->addItem($shovel);
                        $player->getInventory()->addItem(Item::get(Item::IRON_HOE));
                        $player->sendMessage(Manager::PREFIX_INFOS . "Vous venez de recevoir le kit vip avec succès.");
                        $config = Main::getInstance()->cooldown;
                        $config->set($player->getName() . "-kit-vip", time() + 86400);
                        $config->save();
                        return;
                    }else{
                        $player->sendMessage(Manager::PREFIX_ALERT . "Vous devez patienter encore §e" . Main::getInstance()->convert(Main::getInstance()->cooldown->get($player->getName() . "-kit-vip") - time()) . "§f.");
                        return;
                    }
                    break;
                case 3:
                    if (time() >= Main::getInstance()->cooldown->get($player->getName() . "-kit-vipp")){
                        $helmet = Item::get(Item::IRON_HELMET);
                        $helmet->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(Enchantment::PROTECTION), 4));
                        $chestplate = Item::get(Item::IRON_CHESTPLATE);
                        $chestplate->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(Enchantment::PROTECTION), 4));
                        $leggings = Item::get(Item::IRON_LEGGINGS);
                        $leggings->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(Enchantment::PROTECTION), 4));
                        $boots = Item::get(Item::IRON_BOOTS);
                        $boots->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(Enchantment::PROTECTION), 4));
                        $sword = Item::get(Item::IRON_SWORD);
                        $pickaxe = Item::get(Item::IRON_PICKAXE);
                        $pickaxe->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(Enchantment::EFFICIENCY), 5));
                        $shovel = Item::get(Item::IRON_SHOVEL);
                        $shovel->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(Enchantment::EFFICIENCY), 5));
                        $axe = Item::get(Item::IRON_AXE);
                        $axe->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(Enchantment::EFFICIENCY), 5));
                        $player->getInventory()->addItem($helmet);
                        $player->getInventory()->addItem($chestplate);
                        $player->getInventory()->addItem($leggings);
                        $player->getInventory()->addItem($boots);
                        $player->getInventory()->addItem($sword);
                        $player->getInventory()->addItem($pickaxe);
                        $player->getInventory()->addItem($axe);
                        $player->getInventory()->addItem($shovel);
                        $player->getInventory()->addItem(Item::get(Item::IRON_HOE));
                        $player->sendMessage(Manager::PREFIX_INFOS . "Vous venez de recevoir le kit vip+ avec succès.");
                        $config = Main::getInstance()->cooldown;
                        $config->set($player->getName() . "-kit-vipp", time() + 86400);
                        $config->save();
                        return;
                    }else{
                        $player->sendMessage(Manager::PREFIX_ALERT . "Vous devez patienter encore §e" . Main::getInstance()->convert(Main::getInstance()->cooldown->get($player->getName() . "-kit-vipp") - time()) . "§f.");
                        return;
                    }
                    break;
                case 4:
                    if (time() >= Main::getInstance()->cooldown->get($player->getName() . "-kit-mvp")){
                        $helmet = Item::get(Item::DIAMOND_HELMET);
                        $chestplate = Item::get(Item::DIAMOND_CHESTPLATE);
                        $leggings = Item::get(Item::DIAMOND_LEGGINGS);
                        $boots = Item::get(Item::DIAMOND_BOOTS);
                        $sword = Item::get(Item::DIAMOND_SWORD);
                        $pickaxe = Item::get(Item::DIAMOND_PICKAXE);
                        $shovel = Item::get(Item::DIAMOND_SHOVEL);
                        $axe = Item::get(Item::DIAMOND_AXE);
                        $player->getInventory()->addItem($helmet);
                        $player->getInventory()->addItem($chestplate);
                        $player->getInventory()->addItem($leggings);
                        $player->getInventory()->addItem($boots);
                        $player->getInventory()->addItem($sword);
                        $player->getInventory()->addItem($pickaxe);
                        $player->getInventory()->addItem($axe);
                        $player->getInventory()->addItem($shovel);
                        $player->getInventory()->addItem(Item::get(Item::DIAMOND_HOE));
                        $player->sendMessage(Manager::PREFIX_INFOS . "Vous venez de recevoir le kit mvp avec succès.");
                        $config = Main::getInstance()->cooldown;
                        $config->set($player->getName() . "-kit-mvp", time() + 86400);
                        $config->save();
                        return;
                    }else{
                        $player->sendMessage(Manager::PREFIX_ALERT . "Vous devez patienter encore §e" . Main::getInstance()->convert(Main::getInstance()->cooldown->get($player->getName() . "-kit-mvp") - time()) . "§f.");
                        return;
                    }
                    break;
                case 5:
                    if (time() >= Main::getInstance()->cooldown->get($player->getName() . "-kit-farmer")){
                        $grenat = Item::get(Item::BEETROOT_SEEDS);
                        $water = Item::get(Item::WATER, 0, 10);
                        $lava = Item::get(Item::LAVA, 0, 10);
                        $potato = Item::get(Item::POTATO, 0, 32);
                        $carrot = Item::get(Item::CARROT, 0, 32);
                        $verrue = Item::get(Item::NETHER_WART, 0, 32);
                        $cactus = Item::get(Item::CACTUS, 0, 16);
                        $pasteque = Item::get(Item::MELON_SEEDS, 0, 16);
                        $citrouille = Item::get(Item::PUMPKIN, 0, 16);
                        $player->getInventory()->addItem($grenat);
                        $player->getInventory()->addItem($water);
                        $player->getInventory()->addItem($lava);
                        $player->getInventory()->addItem($potato);
                        $player->getInventory()->addItem($carrot);
                        $player->getInventory()->addItem($cactus);
                        $player->getInventory()->addItem($verrue);
                        $player->getInventory()->addItem($pasteque);
                        $player->getInventory()->addItem($citrouille);
                        $player->sendMessage(Manager::PREFIX_INFOS . "Vous venez de recevoir le kit farmer avec succès.");
                        $config = Main::getInstance()->cooldown;
                        $config->set($player->getName() . "-kit-farmer", time() + 86400);
                        $config->save();
                        return;
                    }else{
                        $player->sendMessage(Manager::PREFIX_ALERT . "Vous devez patienter encore §e" . Main::getInstance()->convert(Main::getInstance()->cooldown->get($player->getName() . "-kit-farmer") - time()) . "§f.");
                        return;
                    }
                    break;
                case 6:
                    if (time() >= Main::getInstance()->cooldown->get($player->getName() . "-kit-miner")){
                        $pickaxe = Item::get(Item::DIAMOND_PICKAXE);
                        $pickaxe->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(Enchantment::EFFICIENCY), 5));
                        $pickaxe->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(Enchantment::UNBREAKING), 3));
                        $grenat = Item::get(Item::BEETROOT_SEEDS);
                        $emerald = Item::get(Item::EMERALD, 0, 16);
                        $diamond = Item::get(Item::DIAMOND, 0, 32);
                        $iron = Item::get(Item::IRON_INGOT, 0, 128);
                        $coal = Item::get(Item::COAL, 0, 256);
                        $random = Item::get(Item::GOLD_ORE, 0, 16);
                        $player->getInventory()->addItem($pickaxe);
                        $player->getInventory()->addItem($random);
                        $player->getInventory()->addItem($grenat);
                        $player->getInventory()->addItem($emerald);
                        $player->getInventory()->addItem($diamond);
                        $player->getInventory()->addItem($iron);
                        $player->getInventory()->addItem($coal);
                        $player->sendMessage(Manager::PREFIX_INFOS . "Vous venez de recevoir le kit miner avec succès.");
                        $config = Main::getInstance()->cooldown;
                        $config->set($player->getName() . "-kit-miner", time() + 86400);
                        $config->save();
                        return;
                    }else{
                        $player->sendMessage(Manager::PREFIX_ALERT . "Vous devez patienter encore §e" . Main::getInstance()->convert(Main::getInstance()->cooldown->get($player->getName() . "-kit-miner") - time()) . "§f.");
                        return;
                    }
                    break;
                case 7:
                    if (time() >= Main::getInstance()->cooldown->get($player->getName() . "-kit-enchanteur")){
                        $xp = Item::get(Item::BOTTLE_O_ENCHANTING, 0, 128);
                        $lapis = Item::get(Item::LAPIS_ORE, 0, 128);
                        $table = Item::get(Item::ENCHANTING_TABLE);
                        $anvil = Item::get(Item::ANVIL);
                        $player->getInventory()->addItem($xp);
                        $player->getInventory()->addItem($lapis);
                        $player->getInventory()->addItem($table);
                        $player->getInventory()->addItem($anvil);
                        $player->sendMessage(Manager::PREFIX_INFOS . "Vous venez de recevoir le kit enchanteur avec succès.");
                        $config = Main::getInstance()->cooldown;
                        $config->set($player->getName() . "-kit-enchanteur", time() + 86400);
                        $config->save();
                        return;
                    }else{
                        $player->sendMessage(Manager::PREFIX_ALERT . "Vous devez patienter encore §e" . Main::getInstance()->convert(Main::getInstance()->cooldown->get($player->getName() . "-kit-enchanteur") - time()) . "§f.");
                        return;
                    }
                    break;
                case 8:
                    if (time() >= Main::getInstance()->cooldown->get($player->getName() . "-kit-decorateur")){
                        $quartz = Item::get(Item::QUARTZ_BLOCK, 0, 64);
                        $pierre = Item::get(Item::MOSSY_COBBLESTONE, 0, 32);
                        $glow = Item::get(Item::GLOWSTONE, 0, 16);
                        $lierre = Item::get(Item::VINE, 0, 16);
                        $torche = Item::get(Item::TORCH, 0, 64);
                        $oak = Item::get(Item::LOG, 0, 16);
                        $player->getInventory()->addItem($quartz);
                        $player->getInventory()->addItem($pierre);
                        $player->getInventory()->addItem($glow);
                        $player->getInventory()->addItem($lierre);
                        $player->getInventory()->addItem($torche);
                        $player->getInventory()->addItem($oak);
                        $player->sendMessage(Manager::PREFIX_INFOS . "Vous venez de recevoir le kit decorateur avec succès.");
                        $config = Main::getInstance()->cooldown;
                        $config->set($player->getName() . "-kit-decorateur", time() + 86400);
                        $config->save();
                        return;
                    }else{
                        $player->sendMessage(Manager::PREFIX_ALERT . "Vous devez patienter encore §e" . Main::getInstance()->convert(Main::getInstance()->cooldown->get($player->getName() . "-kit-decorateur") - time()) . "§f.");
                        return;
                    }
                    break;
            }
        });
        $ui->setTitle("§l§7Moon§elight §f- §dSkyBlock");
        $ui->setContent("§7Bob: §fEquipe toi et part à l'aventure !");
        $ui->addButton("Joueur");
        if ($player->hasPermission("use.kit.youboost")){
            $ui->addButton("§cYou§ftuber§7/§dBooster");
        }
        if ($player->hasPermission("use.kit.vip")){
            $ui->addButton("§bVIP");
        }
        if ($player->hasPermission("use.kit.vipp")){
            $ui->addButton("§eVIP+");
        }
        if ($player->hasPermission("use.kit.mvp")){
            $ui->addButton("§1MVP");
        }
        if ($player->hasPermission("use.kit.farmer")){
            $ui->addButton("Farmer");
        }
        if ($player->hasPermission("use.kit.miner")){
            $ui->addButton("Miner");
        }
        if ($player->hasPermission("use.kit.enchanteur")){
            $ui->addButton("Enchanteur");
        }
        if ($player->hasPermission("use.kit.decorateur")){
            $ui->addButton("Décorateur");
        }
        $ui->sendToPlayer($player);
    }

}