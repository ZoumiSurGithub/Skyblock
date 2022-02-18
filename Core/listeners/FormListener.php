<?php

namespace Zoumi\Core\listeners;

use jojoe77777\FormAPI\CustomForm;
use jojoe77777\FormAPI\Form;
use jojoe77777\FormAPI\ModalForm;
use jojoe77777\FormAPI\SimpleForm;
use pocketmine\block\Block;
use pocketmine\event\Listener;
use pocketmine\item\Armor;
use pocketmine\item\Axe;
use pocketmine\item\Durable;
use pocketmine\item\enchantment\Enchantment;
use pocketmine\item\enchantment\EnchantmentInstance;
use pocketmine\item\Hoe;
use pocketmine\item\Item;
use pocketmine\item\Pickaxe;
use pocketmine\item\Shovel;
use pocketmine\item\Sword;
use pocketmine\network\mcpe\protocol\AnvilDamagePacket;
use pocketmine\network\mcpe\protocol\PlaySoundPacket;
use pocketmine\Player;
use pocketmine\Server;
use pocketmine\utils\BinaryStream;
use Zoumi\Core\api\Coins;
use Zoumi\Core\api\Users;
use Zoumi\Core\Manager;

class FormListener implements Listener {

    /* Enchantement */
    public static function sendEnchantMenu(Player $player){
        $ui = new SimpleForm(function (Player $player, $data){
            if ($data === null){
                return;
            }
            switch ($data){
                case 0:
                    $item = $player->getInventory()->getItemInHand();
                    if ($item instanceof Durable){
                        FormListener::sendEnchant($player, Enchantment::getEnchantment(Enchantment::UNBREAKING));
                    }else{
                        $player->sendMessage(Manager::PREFIX_ALERT . "L'item dans votre main doit possédez une durabilité.");
                        return;
                    }
                    break;
                case 1:
                    $item = $player->getInventory()->getItemInHand();
                    if ($item instanceof Pickaxe or $item instanceof Shovel or $item instanceof Hoe or $item instanceof Axe){
                        FormListener::sendEnchant($player, Enchantment::getEnchantment(Enchantment::EFFICIENCY));
                    }else{
                        $player->sendMessage(Manager::PREFIX_ALERT . "L'item dans votre main ne prend pas en compte l'enchantement efficacité.");
                        return;
                    }
                    break;
                case 2:
                    $item = $player->getInventory()->getItemInHand();
                    if ($item instanceof Sword){
                        FormListener::sendEnchant($player, Enchantment::getEnchantment(Enchantment::SHARPNESS));
                    }else{
                        $player->sendMessage(Manager::PREFIX_ALERT . "L'item dans votre main ne prend pas en compte l'enchantement tranchant.");
                        return;
                    }
                    break;
                case 3:
                    $item = $player->getInventory()->getItemInHand();
                    if ($item instanceof Pickaxe or $item instanceof Shovel or $item instanceof Hoe or $item instanceof Axe){
                        FormListener::sendEnchant($player, Enchantment::getEnchantment(Enchantment::SILK_TOUCH));
                    }else{
                        $player->sendMessage(Manager::PREFIX_ALERT . "L'item dans votre main ne prend pas en compte l'enchantement toucher de soie.");
                        return;
                    }
                    break;
                case 4:
                    $item = $player->getInventory()->getItemInHand();
                    if ($item instanceof Armor){
                        FormListener::sendEnchant($player, Enchantment::getEnchantment(Enchantment::PROTECTION));
                    }else{
                        $player->sendMessage(Manager::PREFIX_ALERT . "L'item dans votre main ne prend pas en compte l'enchantement protection.");
                        return;
                    }
                    break;
            }
        });
        $ui->setTitle("§l§7Moon§elight §f- §dSkyBlock");
        $ui->setContent("§7Bob: §fTu souhaites améliorer tes outils ? Pas de soucis j'ai tout ce qu'il te faut ici !");
        $ui->addButton("Solidité");
        $ui->addButton("Efficacité");
        $ui->addButton("Tranchant");
        $ui->addButton("Toucher de soie");
        $ui->addButton("Protection");
        $ui->sendToPlayer($player);
    }

    public static function sendEnchant(Player $player, Enchantment $enchantment){
        $ui = new SimpleForm(function (Player $player, $data) use ($enchantment){
            if ($data === null){
                return;
            }
            switch ($data){
                case 0:
                    if ($player->getXpLevel() >= 5){
                        $item = $player->getInventory()->getItemInHand();
                        if ($item->hasEnchantment($enchantment->getId(), 1)){
                            $player->sendMessage(Manager::PREFIX_ALERT . "Vous possédez déjà cet enchantement avec le même niveau.");
                            return;
                        }else{
                            $player->setXpLevel($player->getXpLevel() - 5);
                            $item->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment($enchantment->getId()), 1));
                            $player->getInventory()->setItemInHand($item);
                            $player->sendMessage(Manager::PREFIX_INFOS . "Vous avez bien enchanté l'item dans votre main.");
                            return;
                        }
                    }else{
                        $player->sendMessage(Manager::PREFIX_ALERT . "Vous ne possédez pas le niveau d'xp requis pour enchanter votre outil.");
                        return;
                    }
                    break;
                case 1:
                    if ($player->getXpLevel() >= 10){
                        $item = $player->getInventory()->getItemInHand();
                        if ($item->hasEnchantment($enchantment->getId(), 2)){
                            $player->sendMessage(Manager::PREFIX_ALERT . "Vous possédez déjà cet enchantement avec le même niveau.");
                            return;
                        }else{
                            $player->setXpLevel($player->getXpLevel() - 10);
                            $item->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment($enchantment->getId()), 2));
                            $player->getInventory()->setItemInHand($item);
                            $player->sendMessage(Manager::PREFIX_INFOS . "Vous avez bien enchanté l'item dans votre main.");
                            return;
                        }
                    }else{
                        $player->sendMessage(Manager::PREFIX_ALERT . "Vous ne possédez pas le niveau d'xp requis pour enchanter votre outil.");
                        return;
                    }
                    break;
                case 2:
                    if ($player->getXpLevel() >= 15){
                        $item = $player->getInventory()->getItemInHand();
                        if ($item->hasEnchantment($enchantment->getId(), 3)){
                            $player->sendMessage(Manager::PREFIX_ALERT . "Vous possédez déjà cet enchantement avec le même niveau.");
                            return;
                        }else{
                            $player->setXpLevel($player->getXpLevel() - 15);
                            $item->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment($enchantment->getId()), 3));
                            $player->getInventory()->setItemInHand($item);
                            $player->sendMessage(Manager::PREFIX_INFOS . "Vous avez bien enchanté l'item dans votre main.");
                            return;
                        }
                    }else{
                        $player->sendMessage(Manager::PREFIX_ALERT . "Vous ne possédez pas le niveau d'xp requis pour enchanter votre outil.");
                        return;
                    }
                    break;
                case 3:
                    if ($player->getXpLevel() >= 20){
                        $item = $player->getInventory()->getItemInHand();
                        if ($item->hasEnchantment($enchantment->getId(), 4)){
                            $player->sendMessage(Manager::PREFIX_ALERT . "Vous possédez déjà cet enchantement avec le même niveau.");
                            return;
                        }else{
                            $player->setXpLevel($player->getXpLevel() - 20);
                            $item->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment($enchantment->getId()), 4));
                            $player->getInventory()->setItemInHand($item);
                            $player->sendMessage(Manager::PREFIX_INFOS . "Vous avez bien enchanté l'item dans votre main.");
                            return;
                        }
                    }else{
                        $player->sendMessage(Manager::PREFIX_ALERT . "Vous ne possédez pas le niveau d'xp requis pour enchanter votre outil.");
                        return;
                    }
                    break;
                case 4:
                    if ($player->getXpLevel() >= 25){
                        $item = $player->getInventory()->getItemInHand();
                        if ($item->hasEnchantment($enchantment->getId(), 5)){
                            $player->sendMessage(Manager::PREFIX_ALERT . "Vous possédez déjà cet enchantement avec le même niveau.");
                            return;
                        }else{
                            $player->setXpLevel($player->getXpLevel() - 25);
                            $item->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment($enchantment->getId()), 5));
                            $player->getInventory()->setItemInHand($item);
                            $player->sendMessage(Manager::PREFIX_INFOS . "Vous avez bien enchanté l'item dans votre main.");
                            return;
                        }
                    }else{
                        $player->sendMessage(Manager::PREFIX_ALERT . "Vous ne possédez pas le niveau d'xp requis pour enchanter votre outil.");
                        return;
                    }
                    break;
            }
        });
        $ui->setTitle("§l§7Moon§elight §f- §dSkyBlock");
        $ui->setContent("§7Bob: §fCet enchantement est intéressant...");
        for ($i = 1;$i <= $enchantment->getMaxLevel();$i++){
            if ($enchantment->getId() === Enchantment::UNBREAKING){
                $ui->addButton("Solidité $i\n§a" . ($i * 5) . " §7niveau d'xp requis.");
            }
            if ($enchantment->getId() === Enchantment::EFFICIENCY){
                $ui->addButton("Efficacité $i\n§a" . ($i * 5) . " §7niveau d'xp requis.");
            }
            if ($enchantment->getId() === Enchantment::SHARPNESS){
                $ui->addButton("Tranchant $i\n§a" . ($i * 5) . " §7niveau d'xp requis.");
            }
            if ($enchantment->getId() === Enchantment::FORTUNE){
                $ui->addButton("Fortune $i\n§a" . ($i * 5) . " §7niveau d'xp requis.");
            }
            if ($enchantment->getId() === Enchantment::SILK_TOUCH){
                $ui->addButton("Toucher de soie $i\n§a" . ($i * 5) . " §7niveau d'xp requis.");
            }
            if ($enchantment->getId() === Enchantment::LOOTING){
                $ui->addButton("Butin $i\n§a" . ($i * 5) . " §7niveau d'xp requis.");
            }
            if ($enchantment->getId() === Enchantment::PROTECTION){
                $ui->addButton("Protection $i\n§a" . ($i * 5) . " §7niveau d'xp requis.");
            }
        }
        $ui->sendToPlayer($player);
    }

    /** Anvil */
    public static function sendAnvilMenu(Player $player, Block $block){
        $ui = new SimpleForm(function (Player $player, $data) use ($block){
            if ($data === null){
                return;
            }
            switch ($data){
                case 0:
                    if ($player->getXpLevel() >= 10){
                        $item = $player->getInventory()->getItemInHand();
                        if ($item instanceof Durable){
                            if ($item->getDamage() > 5){
                                $player->setXpLevel($player->getXpLevel() - 10);
                                AnvilDamagePacket::create($block->getX(), $block->getY(), $block->getZ(), 10);
                                $item->setDamage(0);
                                $player->getInventory()->setItemInHand($item);
                                $player->sendMessage(Manager::PREFIX_INFOS . "Votre item a été réparé avec succès.");
                                $sound = new PlaySoundPacket();
                                $sound->soundName = "random.anvil_use";
                                $sound->pitch = 1;
                                $sound->volume = 1;
                                $sound->x = $player->getX();
                                $sound->y = $player->getY();
                                $sound->z = $player->getZ();
                                $player->sendDataPacket($sound);
                                return;
                            }else{
                                $player->sendMessage(Manager::PREFIX_ALERT . "L'item dans votre main est au maximum de sa durabilitée.");
                                return;
                            }
                        }else{
                            $player->sendMessage(Manager::PREFIX_ALERT . "Seul les items possédant une durabilitée peuvent être réparé.");
                            return;
                        }
                    }else{
                        $player->sendMessage(Manager::PREFIX_ALERT . "Vous ne possédez pas le niveau d'xp requis.");
                        return;
                    }
                    break;
                case 1:
                    FormListener::sendRenameAnvil($player, $block);
                    break;
            }
        });
        $ui->setTitle("§l§dSkyBlock §f- §7Moon§elight");
        $ui->setContent("§7Bob: §fÀ ce que je vois, tu as besoin de moi.");
        if ($player->getXpLevel() >= 10) {
            $ui->addButton("Réparé\n§a10 §7niveaux requis", 0, "textures/items/book_writable.png");
        }else{
            $ui->addButton("Réparé\n§c10 §7niveaux requis", 0, "textures/items/book_writable.png");
        }
        if ($player->getXpLevel() >= 5) {
            $ui->addButton("Renommé\n§a5 §7niveaux requis", 0, "textures/items/name_tag.png");
        }else{
            $ui->addButton("Renommé\n§c5 §7niveaux requis", 0, "textures/items/name_tag.png");
        }
        $ui->sendToPlayer($player);
    }

    public static function sendRenameAnvil(Player $player, Block $block){
        $ui = new CustomForm(function (Player $player, $data) use ($block) {
            if ($data === null) {
                return;
            }
            if ($player->getXpLevel() >= 5) {
                if (empty($data[0])) {
                    return;
                } else {
                    if (strlen($data[0]) > 15) {
                        $player->sendMessage(Manager::PREFIX_ALERT . "Le nom que vous avez entré est trop grand.");
                        return;
                    }
                    foreach (Manager::BANNED_NAMES as $BANNED_NAME) {
                        if (strpos(strtolower($data[0]), $BANNED_NAME) !== false) {
                            $player->sendMessage(Manager::PREFIX_ALERT . "Ce nom est interdit.");
                            return;
                        }
                    }
                    $player->setXpLevel($player->getXpLevel() - 5);
                    $item = $player->getInventory()->getItemInHand();
                    $item->setCustomName($data[0]);
                    $player->getInventory()->setItemInHand($item);
                    AnvilDamagePacket::create($block->getX(), $block->getY(), $block->getZ(), 5);
                    $player->sendMessage(Manager::PREFIX_INFOS . "Vous venez de renommé l'item dans votre main en §e" . $data[0] . "§f.");
                    $sound = new PlaySoundPacket();
                    $sound->soundName = "random.anvil_use";
                    $sound->pitch = 1;
                    $sound->volume = 1;
                    $sound->x = $player->getX();
                    $sound->y = $player->getY();
                    $sound->z = $player->getZ();
                    $player->sendDataPacket($sound);
                    return;
                }
            } else {
                $player->sendMessage(Manager::PREFIX_ALERT . "Vous ne possédez pas le niveau d'xp requis.");
                return;
            }
        });
        $ui->setTitle("§l§dSkyBlock §f- §7Moon§elight");
        $ui->addInput("§7Bob: §fComment veux-tu l'appeler ?", "Bob le bricoleur");
        $ui->sendToPlayer($player);
    }

    /** Rankup */
    public static function sendRankUp(Player $player){
        $ui = new ModalForm(function (Player $player, $data){
            if ($data === null){
                return;
            }
            switch ($data){
                case true:
                    if (empty(Users::getPrefix($player->getName()))){
                        $item = Item::get(Item::COBBLESTONE, 0, 1024);
                        if (FormListener::hasItem($player, $item)){
                            $player->getInventory()->removeItem($item);
                            Users::setPrefix($player, "§7[§fOuvrier§7]");
                            Coins::addCoins($player->getName(), 5000);
                            $player->sendMessage(Manager::PREFIX_INFOS . "Bien joué ! Vous venez de passer au rang §7[§fOuvrier§7]§f.");
                            return;
                        }else{
                            $player->sendMessage(Manager::PREFIX_ALERT . "Vous ne possédez pas les items requis !");
                            return;
                        }
                    }elseif (Users::getPrefix($player->getName()) === "§7[§fOuvrier§7]"){
                        $item = Item::get(Item::WHEAT, 0, 256);
                        if (FormListener::hasItem($player, $item)){
                            $player->getInventory()->removeItem($item);
                            Users::setPrefix($player, "§6[§eAgriculteur§6]");
                            Coins::addCoins($player->getName(), 3000);
                            $player->sendMessage(Manager::PREFIX_INFOS . "Bien joué ! Vous venez de passer au rang §6[§eAgriculteur§6]§f.");
                            return;
                        }else{
                            $player->sendMessage(Manager::PREFIX_ALERT . "Vous ne possédez pas les items requis !");
                            return;
                        }
                    }elseif (Users::getPrefix($player->getName()) === "§6[§eAgriculteur§6]"){
                        $sword = Item::get(Item::DIAMOND_SWORD);
                        $pickaxe = Item::get(Item::DIAMOND_PICKAXE);
                        $shovel = Item::get(Item::DIAMOND_SHOVEL);
                        $hoe = Item::get(Item::DIAMOND_HOE);
                        $axe = Item::get(Item::DIAMOND_AXE);
                        if (FormListener::hasItem($player, $sword) && FormListener::hasItem($player, $pickaxe) && FormListener::hasItem($player, $shovel) && FormListener::hasItem($player, $hoe) && FormListener::hasItem($player, $axe)){
                            $player->getInventory()->removeItem($sword);
                            $player->getInventory()->removeItem($pickaxe);
                            $player->getInventory()->removeItem($shovel);
                            $player->getInventory()->removeItem($hoe);
                            $player->getInventory()->removeItem($axe);
                            $player->getInventory()->addItem(Item::get(Item::BEETROOT_SEEDS));
                            Users::setPrefix($player, "§1[§9Artisan§1]");
                            $player->sendMessage(Manager::PREFIX_INFOS . "Bien joué ! Vous venez de passer au rang §1[§9Artisan§1]§f.");
                            return;
                        }else{
                            $player->sendMessage(Manager::PREFIX_ALERT . "Vous ne possédez pas les items requis !");
                            return;
                        }
                    }elseif (Users::getPrefix($player->getName()) === "§1[§9Artisan§1]"){
                        $item = Item::get(Item::COOKED_PORKCHOP, 0, 256);
                        if (FormListener::hasItem($player, $item)){
                            Users::setPrefix($player, "§a[§2Hunter§a]");
                            Coins::addCoins($player->getName(), 5000);
                            $player->sendMessage(Manager::PREFIX_INFOS . "Bien joué ! Vous venez de passer au rang §a[§2Hunter§a]§f.");
                            return;
                        }else{
                            $player->sendMessage(Manager::PREFIX_ALERT . "Vous ne possédez pas les items requis !");
                            return;
                        }
                    }elseif (Users::getPrefix($player->getName()) === "§a[§2Hunter§a]"){
                        $item = Item::get(373, 31, 3);
                        if (FormListener::hasItem($player, $item)){
                            $player->getInventory()->removeItem($item);
                            $item = Item::get(373, 33, 1);
                            $player->getInventory()->addItem($item);
                            $item = Item::get(373, 28);
                            $player->getInventory()->addItem($item);
                            Users::setPrefix($player, "§5[§dS§fo§dr§fc§di§fe§dr§5]");
                            Coins::addCoins($player->getName(), 5000);
                            $player->sendMessage(Manager::PREFIX_INFOS . "Bien joué ! Vous venez de passer au rang §5[§dS§fo§dr§fc§di§fe§dr§5]§f.");
                            return;
                        }else{
                            $player->sendMessage(Manager::PREFIX_ALERT . "Vous ne possédez pas les items requis !");
                            return;
                        }
                    }elseif (Users::getPrefix($player->getName()) === "§5[§dS§fo§dr§fc§di§fe§dr§5]"){
                        $item = Item::get(Item::GOLDEN_APPLE, 0, 10);
                        if (FormListener::hasItem($player, $item)){
                            $player->getInventory()->removeItem($item);
                            $item = Item::get(373, 21, 3);
                            $player->getInventory()->addItem($item);
                            Users::setPrefix($player, "§4[§cGuérisseur§4]");
                            $player->sendMessage(Manager::PREFIX_INFOS . "Bien joué ! Vous venez de passer au rang §4[§cGuérisseur§4]§f.");
                            return;
                        }else{
                            $player->sendMessage(Manager::PREFIX_ALERT . "Vous ne possédez pas les items requis !");
                            return;
                        }
                    }elseif (Users::getPrefix($player->getName()) === "§4[§cGuérisseur§4]"){
                        $item = Item::get(Item::CAKE, 0, 5);
                        if (FormListener::hasItem($player, $item)){
                            $player->getInventory()->removeItem($item);
                            Coins::addCoins($player->getName(), 5000);
                            Users::setPrefix($player, "§f[§7Cuisinier§f]");
                            $player->sendMessage(Manager::PREFIX_INFOS . "Bien joué ! Vous venez de passer au rang §f[§7Cuisinier§f]§f.");
                            return;
                        }else{
                            $player->sendMessage(Manager::PREFIX_ALERT . "Vous ne possédez pas les items requis !");
                            return;
                        }
                    }elseif (Users::getPrefix($player->getName()) === "§f[§7Cuisinier§f]"){
                        $item = Item::get(Item::SHIELD, 0, 10);
                        if (FormListener::hasItem($player, $item)){
                            $player->getInventory()->removeItem($item);
                            $item = Item::get(Item::BEETROOT_SEEDS, 0, 5);
                            $player->getInventory()->addItem($item);
                            Users::setPrefix($player, "§0[§8Chevalier§0]");
                            $player->sendMessage(Manager::PREFIX_INFOS . "Bien joué ! Vous venez de passer au rang §0[§8Chevalier§0]§f.");
                            return;
                        }else{
                            $player->sendMessage(Manager::PREFIX_ALERT . "Vous ne possédez pas les items requis !");
                            return;
                        }
                    }elseif (Users::getPrefix($player->getName()) === "§0[§8Chevalier§0]"){
                        $item = Item::get(Item::CHEST, 0, 256);
                        if (FormListener::hasItem($player, $item)){
                            $player->getInventory()->removeItem($item);
                            Coins::addCoins($player->getName(), 10000);
                            Users::setPrefix($player, "§6[§eT§fr§ee§fs§eo§fr§ei§fe§er§6]");
                            $player->sendMessage(Manager::PREFIX_INFOS . "Bien joué ! Vous venez de passer au rang §6[§eT§fr§ee§fs§eo§fr§ei§fe§er§6]§f.");
                            return;
                        }else{
                            $player->sendMessage(Manager::PREFIX_ALERT . "Vous ne possédez pas les items requis !");
                            return;
                        }
                    }elseif (Users::getPrefix($player->getName()) === "§6[§eT§fr§ee§fs§eo§fr§ei§fe§er§6]"){
                        $item = Item::get(Item::EMERALD, 0, 32);
                        if (FormListener::hasItem($player, $item)){
                            $player->getInventory()->removeItem($item);
                            Coins::addCoins($player->getName(), 15000);
                            Users::setPrefix($player, "§3[§bTrader§3]");
                            $player->sendMessage(Manager::PREFIX_INFOS . "Bien joué ! Vous venez de passer au rang §3[§bTrader§3]§f.");
                            return;
                        }else{
                            $player->sendMessage(Manager::PREFIX_ALERT . "Vous ne possédez pas les items requis !");
                            return;
                        }
                    }elseif (Users::getPrefix($player->getName()) === "§3[§bTrader§3]"){
                        break;
                    }
                    break;
                case false:
                    break;
            }
        });
        $ui->setTitle("§l§dSkyBlock §f- §7Moon§elight");
        if (empty(Users::getPrefix($player->getName()))){
            $ui->setContent("§7Bob: §fPour monter au rang §7[§fOuvrier§7]§f, il faut que tu me ramènes 1024 pierre taillé, soit 2 ligne. Tu gagneras 5 000\u{E102} pour m'avoir aider !\n\n§6Information: §eIl faut que vous ramenez tout d'un seul coup.");;
        }elseif (Users::getPrefix($player->getName()) === "§7[§fOuvrier§7]"){
            $ui->setContent("§7Bob: §fPour monter au rang §6[§eAgriculteur§6]§f, il faut que tu me ramènes 256 Blés, soit 4 stack de blé. Tu gagneras 3 000\u{E102} pour m'avoir aider !\n\n§6Information: §eIl faut que vous ramenez tout d'un seul coup.");
        }elseif (Users::getPrefix($player->getName()) === "§6[§eAgriculteur§6]"){
            $ui->setContent("§7Bob: §fPour monter au rang §1[§9Artisan§1]§f, il faut que tu me ramènes tout les outils en diamant. Tu gagneras 1 graine de rubis pour m'avoir aider !\n\n§6Information: §eIl faut que vous ramenez tout d'un seul coup.");
        }elseif (Users::getPrefix($player->getName()) === "§1[§9Artisan§1]"){
            $ui->setContent("§7Bob: §fPour monter au rang §a[§2Hunter§a]§f, il faut que tu me ramènes 256 viandes de porc cuît. Tu gagneras 5 000\u{E102} pour m'avoir aider !\n\n§6Information: §eIl faut que vous ramenez tout d'un seul coup.");
        }elseif (Users::getPrefix($player->getName()) === "§a[§2Hunter§a]"){
            $ui->setContent("§7Bob: §fPour monter au rang §5[§dS§fo§dr§fc§di§fe§dr§5]§f, il faut que tu me ramènes 3 potions de force niveau 1. Tu gagneras 1 potion de force niveau 2 ainsi qu'une potion de régénération pour m'avoir aider !\n\n§6Information: §eIl faut que vous ramenez tout d'un seul coup.");
        }elseif (Users::getPrefix($player->getName()) === "§5[§dS§fo§dr§fc§di§fe§dr§5]"){
            $ui->setContent("§7Bob: §fPour monter au rang §4[§cGuérisseur§4]§f, il faut que tu me ramènes 10 pommes en or. Tu gagneras 3 potions de soin instantanés pour m'avoir aider !\n\n§6Information: §eIl faut que vous ramenez tout d'un seul coup.");
        }elseif (Users::getPrefix($player->getName()) === "§4[§cGuérisseur§4]"){
            $ui->setContent("§7Bob: §fPour monter au rang §f[§7Cuisinier§f], il faut que tu me ramènes 5 gâteaux. Tu gagneras 5 000\u{E102} pour m'avoir aider !\n\n§6Information: §eIl faut que vous ramenez tout d'un seul coup.");
        }elseif (Users::getPrefix($player->getName()) === "§f[§7Cuisinier§f]"){
            $ui->setContent("§7Bob: §fPour monter au rang §0[§8Chevalier§0]§f, il faut que tu me ramènes 10 boucliers. Tu gagneras 5 graines de rubis pour m'avoir aider !\n\n§6Information: §eIl faut que vous ramenez tout d'un seul coup.");
        }elseif (Users::getPrefix($player->getName()) === "§0[§8Chevalier§0]"){
            $ui->setContent("§7Bob: §fPour monter au rang §6[§eT§fr§ee§fs§eo§fr§ei§fe§er§6]§f, il faut que tu me ramènes 256 coffres. Soit 4 stack. Tu gagneras 10 000\u{E102} pour m'avoir aider !\n\n§6Information: §eIl faut que vous ramenez tout d'un seul coup.");
        }elseif (Users::getPrefix($player->getName()) === "§6[§eT§fr§ee§fs§eo§fr§ei§fe§er§6]"){
            $ui->setContent("§7Bob: §fPour monter au rang §3[§bTrader§3]§f, il faut que tu me ramènes 32 émeraudes. Tu gagneras 15 000\u{E102} pour m'avoir aider !\n\n§6Information: §eIl faut que vous ramenez tout d'un seul coup.");
        }elseif (Users::getPrefix($player->getName()) === "§3[§bTrader§3]"){
            $ui->setContent("§7Bob: §fOhoh... Je suis désoler mais tu as atteint le rang maximal du serveur.");
        }
        $ui->setButton1("RANKUP");
        $ui->setButton2("QUITTER");
        $ui->sendToPlayer($player);
    }

    public static function hasItem(Player $player, Item $item): bool{
        $count = 0;
        foreach ($player->getInventory()->getContents() as $itm){
            if ($itm->getId() === $item->getId()){
                $count += $itm->getCount();
            }
        }

        if ($count >= $item->getCount()){
            return true;
        }
        return false;
    }

    /** Box */
    public static function sendVoteInfos(Player $player){
        $ui = new SimpleForm(function (Player $player, $data){
            if ($data === null){
                return;
            }
            switch ($data){
                case 0:
                    break;
            }
        });
        $ui->setTitle("§l§7Moon§elight §f- §dSkyBlock");
        $ui->setContent(
            "§7Bob: §fVoici les loots disponibles via la box §2Vote§f.\n\n" .
            "§6» §f5 000\u{E102} §e- §7(30%%)\n" .
            "§6» §f10 000\u{E102} §e- §7(20%%)\n" .
            "§6» §f20 00\u{E102} §e- §7(10%%)\n" .
            "§6» §fx32 Blocs de diamant §e- §7(10%%)\n" .
            "§6» §fx32 Minerai Aléatoire §e- §7(10%%)\n" .
            "§6» §fx32 Terres §e- §7(5%%)\n" .
            "§6» §fx16 Sable des âmes §e- §7(5%%)\n" .
            "§6» §fx16 Blocs d'émeraude §e- §7(4%%)\n" .
            "§6» §fx1 Clé spawner §e- §7(3%%)"
        );
        $ui->addButton("Quitter");
        $ui->sendToPlayer($player);
    }

    public static function sendFarmingInfos(Player $player){
        $ui = new SimpleForm(function (Player $player, $data){
            if ($data === null){
                return;
            }
            switch ($data){
                case 0:
                    break;
            }
        });
        $ui->setTitle("§l§7Moon§elight §f- §dSkyBlock");
        $ui->setContent(
            "§7Bob: §fVoici les loots disponibles via la box §eFarming§f.\n\n" .
            "§6» §fx64 Cactus §e- §7(10%%)\n" .
            "§6» §fx64 Graines §e- §7(10%%)\n" .
            "§6» §fx64 Patates §e- §7(10%%)\n" .
            "§6» §fx64 Carottes §e- §7(10%%)\n" .
            "§6» §fx64 Cannes à sucre §e- §7(10%%)\n" .
            "§6» §fx1 Casque de Farmer §e- §7(10%%)\n" .
            "§6» §fx1 Plastron de Farmer §e- §7(10%%)\n" .
            "§6» §fx1 Jambière de Farmer §e- §7(10%%)\n" .
            "§6» §fx1 Bottes de Farmer §e- §7(10%%)\n" .
            "§6» §fx1 Graine de rubis §e- §7(5%%)\n" .
            "§6» §fx1 Spawner §e- §7(5%%)"
        );
        $ui->addButton("Quitter");
        $ui->sendToPlayer($player);
    }

    public static function sendBoutiqueInfos(Player $player){
        $ui = new SimpleForm(function (Player $player, $data){
            if ($data === null){
                return;
            }
            switch ($data){
                case 0:
                    break;
            }
        });
        $ui->setTitle("§l§7Moon§elight §f- §dSkyBlock");
        $ui->setContent(
            "§7Bob: §fVoici les loots disponibles via la box §6Boutique§f.\n\n" .
            "§6» §f50 000\u{E102} §e- §7(20%%)\n" .
            "§6» §f100 000\u{E102} §e- §7(10%%)\n" .
            "§6» §fx1 Spawner §e- §7(10%%)\n" .
            "§6» §fx128 Sable des âmes §e- §7(10%%)\n" .
            "§6» §fx128 Blocs d'émeraude §e- §7(10%%)\n" .
            "§6» §fx256 Blocs de diamant §e- §7(10%%)\n" .
            "§6» §fx256 Terres §e- §7(10%%)\n" .
            "§6» §fx1 Clé §0Spawner §e- §7(5%%)\n" .
            "§6» §fx1 Clé §eFarming §e- §7(5%%)\n" .
            "§6» §fx5 Graines de rubis §e- §7(4%%)\n" .
            "§6» §fx3 Clés §0Spawner §e- §7(3%%)\n" .
            "§6» §fx3 Clés §eFarming §e- §7(3%%)"
        );
        $ui->addButton("Quitter");
        $ui->sendToPlayer($player);
    }

    public static function sendSpawnerInfos(Player $player){
        $ui = new SimpleForm(function (Player $player, $data){
            if ($data === null){
                return;
            }
            switch ($data){
                case 0:
                    break;
            }
        });
        $ui->setTitle("§l§7Moon§elight §f- §dSkyBlock");
        $ui->setContent(
            "§7Bob: §fVoici les loots disponibles via la box §0Spawner§f.\n\n" .
            "§6» §fx1 Spawner §e- §7(25%%)\n" .
            "§6» §fx1 Oeuf de vache §e- §7(15%%)\n" .
            "§6» §fx1 Oeuf de mouton §e- §7(15%%)\n" .
            "§6» §fx1 Oeuf de cochon §e- §7(15%%)\n" .
            "§6» §fx1 Oeuf d'araignée §e- §7(10%%)\n" .
            "§6» §fx1 Oeuf de creeper §e- §7(10%%)\n" .
            "§6» §fx1 Oeuf de squelette §e- §7(5%%)\n" .
            "§6» §fx1 Oeuf d'enderman §e- §7(5%%)"
        );
        $ui->addButton("Quitter");
        $ui->sendToPlayer($player);
    }

    /* Menu */
    public static function openWelcomeMenu(Player $player){
        $ui = new SimpleForm(function (Player $player, $data){
            if ($data === null){
                return;
            }
            switch ($data){

            }
        });
        $ui->setTitle("§l§7Moon§elight §f- §dSkyBlock");
        $ui->setContent("§7Bob: §fOh ! Tu es nouveau ? Oui ? Alors laisse moi te présenter le serveur !\n\n§7fTout d'abord je tien à préciser que §7Moon§elight §fest un §7Network §fce qui veut dire que nous possédons plusieurs serveur.\n\n§7- §fChoisi l'une des catégories ci-dessous pour en savoir plus sur celui-ci.");
        $ui->addButton("Coins", 0, "textures/menu/coins");
        $ui->addButton("Point Boutique");
        $ui->addButton("Vote", 0, "textures/menu/vote");
        $ui->addButton("Kit", 0, "textures/menu/coins");
        $ui->addButton("Armure/item moddé", 0, "textures/menu/coins");
        $ui->addButton("Les métiers", 0, "textures/menu/coins");
        $ui->addButton("La bourse", 0, "textures/menu/coins");
        $ui->addButton("Event", 0, "textures/menu/coins");
        $ui->sendToPlayer($player);
    }

    public static function openWelcomeCoins(Player $player){
        $ui = new SimpleForm(function (Player $player, $data){
            if ($data === null){
                return;
            }
            switch ($data){
                case 0:
                    FormListener::openWelcomeMenu($player);
                    break;
            }
        });
        $ui->setTitle("§l§7Moon§elight §f- §dSkyBlock");
        $ui->setContent("§7Bob: §fLe §eCoins §fest la monnaie principale du serveur, celle-ci vous sert à acheter des items dans le §7/shop §fet même à en vendre au §7/hdv§f.\n\n§7Liste des commandes disponibles lui concernant:\n§f- /coins\n- /takescoins\n- /topcoins\n- /hdv\n- /bourse\n- /f2w\n- /shop");
        $ui->addButton("§l§c<- RETOUR");
        $ui->sendToPlayer($player);
    }

    public static function openWelcomePB(Player $player){
        $ui = new SimpleForm(function (Player $player, $data){
            if ($data === null){
                return;
            }
            switch ($data){
                case 0:
                    FormListener::openWelcomeMenu($player);
                    break;
            }
        });
        $ui->setTitle("§l§7Moon§elight §f- §dSkyBlock");
        $ui->setContent("§7Bob: §fLes fameux point boutique, les points boutique servent uniquement à acheter un grade en jeu avec celui-ci. Le grade est permanent.\n\n§7Comment on obtenir ?\n§fVOus pouvez en obtenir tout simplement en votant pour le serveur, une fois voter faites §7/vote §fet vous gagnerez entre 1 et 3 points boutique.");
        $ui->addButton("§l§c<- RETOUR");
        $ui->sendToPlayer($player);
    }

}