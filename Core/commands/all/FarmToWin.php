<?php

namespace Zoumi\Core\commands\all;

use jojoe77777\FormAPI\SimpleForm;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use Zoumi\Core\api\Coins;
use Zoumi\Core\Main;
use Zoumi\Core\Manager;

class FarmToWin extends Command {

    public function __construct(string $name, string $description = "", string $usageMessage = null, array $aliases = [])
    {
        parent::__construct($name, $description, $usageMessage, $aliases);
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        if ($sender instanceof Player){
            $this->openF2WMenu($sender);
        }
    }

    private function openF2WMenu(Player $player){
        $ui = new SimpleForm(function (Player $player, $data){
            if ($data === null){
                return;
            }
            switch ($data){
                case 0:
                    $this->openKitsMenu($player);
                    break;
                case 1:
                    $this->sendCommandsMenu($player);
                    break;
            }
        });
        $ui->setTitle("§l§7Moon§elight §f- §dSkyBlock");
        $ui->setContent("§7Bob: §fPeut importe ou tu vas, c'est intéressant.");
        $ui->addButton("Kits");
        $ui->addButton("Commandes");
        $ui->sendToPlayer($player);
    }

    private function openKitsMenu(Player $player){
        $ui = new SimpleForm(function (Player $player, $data){
            if ($data === null){
                return;
            }
            switch ($data){
                case 0:
                    if ($player->hasPermission("use.kit.farmer") or Main::getInstance()->farmer->exists($player->getName())){
                        $player->sendMessage(Manager::PREFIX_ALERT . "Vous possédez déjà ce kit.");
                        return;
                    }else{
                        if (Coins::getCoins($player->getName()) >= 200000) {
                            Coins::removecoins($player->getName(), 200000);
                            $config = Main::getInstance()->farmer;
                            $config->set($player->getName(), true);
                            $config->save();
                            $player->sendMessage(Manager::PREFIX_INFOS . "Vous venez d'acheter le kit §eFarmer §fpour §e200k§f\u{E102}");
                            return;
                        }else{
                            $player->sendMessage(Manager::PREFIX_ALERT . "Vous ne possédez pas les coins requis pour acheter ce kit.");
                            return;
                        }
                    }
                    break;
                case 1:
                    if ($player->hasPermission("use.kit.miner") or Main::getInstance()->miner->exists($player->getName())){
                        $player->sendMessage(Manager::PREFIX_ALERT . "Vous possédez déjà ce kit.");
                        return;
                    }else{
                        if (Coins::getCoins($player->getName()) >= 200000) {
                            Coins::removecoins($player->getName(), 200000);
                            $config = Main::getInstance()->miner;
                            $config->set($player->getName(), true);
                            $config->save();
                            $player->sendMessage(Manager::PREFIX_INFOS . "Vous venez d'acheter le kit §eMiner §fpour §e200k§f\u{E102}");
                            return;
                        }else{
                            $player->sendMessage(Manager::PREFIX_ALERT . "Vous ne possédez pas les coins requis pour acheter ce kit.");
                            return;
                        }
                    }
                    break;
                case 2:
                    if ($player->hasPermission("use.kit.enchanteur") or Main::getInstance()->enchanteur->exists($player->getName())){
                        $player->sendMessage(Manager::PREFIX_ALERT . "Vous possédez déjà ce kit.");
                        return;
                    }else{
                        if (Coins::getCoins($player->getName()) >= 200000) {
                            Coins::removecoins($player->getName(), 200000);
                            $config = Main::getInstance()->enchanteur;
                            $config->set($player->getName(), true);
                            $config->save();
                            $player->sendMessage(Manager::PREFIX_INFOS . "Vous venez d'acheter le kit §eEnchanteur §fpour §e200k§f\u{E102}");
                            return;
                        }else{
                            $player->sendMessage(Manager::PREFIX_ALERT . "Vous ne possédez pas les coins requis pour acheter ce kit.");
                            return;
                        }
                    }
                    break;
                case 3:
                    if ($player->hasPermission("use.kit.decorateur") or Main::getInstance()->decorateur->exists($player->getName())){
                        $player->sendMessage(Manager::PREFIX_ALERT . "Vous possédez déjà ce kit.");
                        return;
                    }else{
                        if (Coins::getCoins($player->getName()) >= 150000) {
                            Coins::removecoins($player->getName(), 150000);
                            $config = Main::getInstance()->decorateur;
                            $config->set($player->getName(), true);
                            $config->save();
                            $player->sendMessage(Manager::PREFIX_INFOS . "Vous venez d'acheter le kit §eDécorateur §fpour §e150k§f\u{E102}");
                            return;
                        }else{
                            $player->sendMessage(Manager::PREFIX_ALERT . "Vous ne possédez pas les coins requis pour acheter ce kit.");
                            return;
                        }
                    }
                    break;
                case 4:
                    $this->openF2WMenu($player);
                    break;
            }
        });
        $ui->setTitle("§l§7Moon§elight §f- §dSkyBlock");
        $ui->setContent("§7Bob: §fC'est peut-être chère à vos yeux, mais je vous assure qu'ils envoient du lourd!\n\n§7- §fVous possédez §e" . Coins::getCoins($player->getName()) . "§f\u{E102}.");
        if ($player->hasPermission("use.kit.farmer") or Main::getInstance()->farmer->exists($player->getName())) {
            $ui->addButton("§l§2Farmer");
        }else{
            $ui->addButton("Farmer\n§e200k§f\u{E102}");
        }
        if ($player->hasPermission("use.kit.miner") or Main::getInstance()->miner->exists($player->getName())) {
            $ui->addButton("§l§2Miner");
        }else{
            $ui->addButton("Miner\n§e200k§f\u{E102}");
        }
        if ($player->hasPermission("use.kit.enchanteur") or Main::getInstance()->enchanteur->exists($player->getName())) {
            $ui->addButton("§l§2Enchanteur");
        }else{
            $ui->addButton("Enchanteur\n§e200k§f\u{E102}");
        }
        if ($player->hasPermission("use.kit.decorateur") or Main::getInstance()->decorateur->exists($player->getName())) {
            $ui->addButton("§l§2Décorateur");
        }else{
            $ui->addButton("Décorateur\n§e150k§f\u{E102}");
        }
        $ui->addButton("§l§c<- RETOUR");
        $ui->sendToPlayer($player);
    }

    private function sendCommandsMenu(Player $player){
        $ui = new SimpleForm(function (Player $player, $data){
            if ($data === null){
                return;
            }
            switch ($data){
                case 0:
                    if ($player->hasPermission("use.enderchest") or Main::getInstance()->ec->exists($player->getName())){
                        $player->sendMessage(Manager::PREFIX_ALERT . "Vous possédez déjà cette commande.");
                        return;
                    }else{
                        if (Coins::getCoins($player->getName()) >= 100000) {
                            Coins::removecoins($player->getName(), 100000);
                            $config = Main::getInstance()->ec;
                            $config->set($player->getName(), true);
                            $config->save();
                            $player->sendMessage(Manager::PREFIX_INFOS . "Vous venez d'acheter la commande §eEnderchest §fpour §e100k§f\u{E102}");
                            return;
                        }else{
                            $player->sendMessage(Manager::PREFIX_ALERT . "Vous ne possédez pas les coins requis pour acheter cette commande.");
                            return;
                        }
                    }
                    break;
                case 1:
                    if ($player->hasPermission("use.fly") or Main::getInstance()->fly->exists($player->getName())){
                        $player->sendMessage(Manager::PREFIX_ALERT . "Vous possédez déjà cette commande.");
                        return;
                    }else{
                        if (Coins::getCoins($player->getName()) >= 75000) {
                            Coins::removecoins($player->getName(), 75000);
                            $config = Main::getInstance()->fly;
                            $config->set($player->getName(), true);
                            $config->save();
                            $player->sendMessage(Manager::PREFIX_INFOS . "Vous venez d'acheter la commande §eFly §fpour §e75k§f\u{E102}");
                            return;
                        }else{
                            $player->sendMessage(Manager::PREFIX_ALERT . "Vous ne possédez pas les coins requis pour acheter cette commande.");
                            return;
                        }
                    }
                    break;
                case 2:
                    if ($player->hasPermission("use.furnace") or Main::getInstance()->furnace->exists($player->getName())){
                        $player->sendMessage(Manager::PREFIX_ALERT . "Vous possédez déjà cette commande.");
                        return;
                    }else{
                        if (Coins::getCoins($player->getName()) >= 100000) {
                            Coins::removecoins($player->getName(), 100000);
                            $config = Main::getInstance()->furnace;
                            $config->set($player->getName(), true);
                            $config->save();
                            $player->sendMessage(Manager::PREFIX_INFOS . "Vous venez d'acheter la commande §eFurnace §fpour §e100k§f\u{E102}");
                            return;
                        }else{
                            $player->sendMessage(Manager::PREFIX_ALERT . "Vous ne possédez pas les coins requis pour acheter cette commande.");
                            return;
                        }
                    }
                    break;
                case 3:
                    if ($player->hasPermission("use.repair") or Main::getInstance()->repair->exists($player->getName())){
                        $player->sendMessage(Manager::PREFIX_ALERT . "Vous possédez déjà cette commande.");
                        return;
                    }else{
                        if (Coins::getCoins($player->getName()) >= 100000) {
                            Coins::removecoins($player->getName(), 100000);
                            $config = Main::getInstance()->repair;
                            $config->set($player->getName(), true);
                            $config->save();
                            $player->sendMessage(Manager::PREFIX_INFOS . "Vous venez d'acheter la commande §eRepair §fpour §e100k§f\u{E102}");
                            return;
                        }else{
                            $player->sendMessage(Manager::PREFIX_ALERT . "Vous ne possédez pas les coins requis pour acheter cette commande.");
                            return;
                        }
                    }
                    break;
                case 4:
                    $this->openF2WMenu($player);
                    break;
            }
        });
        $ui->setTitle("§l§7Moon§elight §f- §dSkyBlock");
        $ui->setContent("§7Bob: §fHummm, toutes ces commandes sont utiles... À toi de choisir celui qui te sera le plus utile!\n\n§7- §fVous possédez §e" . Coins::getCoins($player->getName()) . "§f\u{E102}.");
        if ($player->hasPermission("use.enderchest") or Main::getInstance()->ec->exists($player->getName())){
            $ui->addButton("§l§2Enderchest");
        }else{
            $ui->addButton("Enderchest\n§e100k§f\u{E102}");
        }
        if ($player->hasPermission("use.fly") or Main::getInstance()->fly->exists($player->getName())){
            $ui->addButton("§l§2Fly");
        }else{
            $ui->addButton("Fly\n§e75k§f\u{E102}");
        }
        if ($player->hasPermission("use.furnace") or Main::getInstance()->furnace->exists($player->getName())){
            $ui->addButton("§l§2Furnace");
        }else{
            $ui->addButton("Furnace\n§e100k§f\u{E102}");
        }
        if ($player->hasPermission("use.repair") or Main::getInstance()->repair->exists($player->getName())){
            $ui->addButton("§l§2Repair");
        }else{
            $ui->addButton("Repair\n§e100k§f\u{E102}");
        }
        $ui->addButton("§l§c<- RETOUR");
        $ui->sendToPlayer($player);
    }

}