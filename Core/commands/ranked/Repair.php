<?php

namespace Zoumi\Core\commands\ranked;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\item\Durable;
use pocketmine\Player;
use Zoumi\Core\Main;
use Zoumi\Core\Manager;

class Repair extends Command {

    public function __construct(string $name, string $description = "", string $usageMessage = null, array $aliases = [])
    {
        parent::__construct($name, $description, $usageMessage, $aliases);
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        if ($sender instanceof Player){
            if (Main::getInstance()->repair->exists($sender->getName()) or $sender->hasPermission("use.repair")){
                if (!isset($args[0])) {
                    if (time() >= Main::getInstance()->cooldown->get($sender->getName() . "-repair")) {
                        $item = $sender->getInventory()->getItemInHand();
                        if ($item instanceof Durable) {
                            if ($item->getDamage() > 5) {
                                $item->setDamage(0);
                                $sender->getInventory()->setItemInHand($item);
                                $sender->sendMessage(Manager::PREFIX_INFOS . "L'item dans votre main a été réparé avec succès.");
                                $config = Main::getInstance()->cooldown;
                                $config->set($sender->getName() . "-repair", time() + 600);
                                $config->save();
                                return;
                            } else {
                                $sender->sendMessage(Manager::PREFIX_ALERT . "L'item dans votre main est au maximum de sa durabiltée.");
                                return;
                            }
                        } else {
                            $sender->sendMessage(Manager::PREFIX_ALERT . "L'item dans votre main ne posséde pas de durabilité.");
                            return;
                        }
                    }else{
                        $sender->sendMessage(Manager::PREFIX_ALERT . "Vous devez patienter encore §a" . Main::getInstance()->convert(Main::getInstance()->cooldown->get($sender->getName() . "-repair") - time()) . "§c.");
                    }
                }else{
                    if ($sender->hasPermission("use.repair.all")){
                        if (strtolower($args[0]) === "all"){
                            if (time() >= Main::getInstance()->cooldown->get($sender->getName() . "-repair-all")) {
                                foreach ($sender->getInventory()->getContents() as $index => $item) {
                                    if ($item instanceof Durable) {
                                        if ($item->getDamage() > 5) {
                                            $item->setDamage(0);
                                            $sender->getInventory()->setItem($index, $item);
                                        }
                                    }
                                }
                                foreach ($sender->getArmorInventory()->getContents() as $index => $item) {
                                    if ($item instanceof Durable) {
                                        if ($item->getDamage() > 5) {
                                            $item->setDamage(0);
                                            $sender->getArmorInventory()->setItem($index, $item);
                                        }
                                    }
                                }
                                $sender->sendMessage(Manager::PREFIX_INFOS . "Vos items ont été réparé.");
                                $config = Main::getInstance()->cooldown;
                                $config->set($sender->getName() . "-repair-all", time() + 1800);
                                $config->save();
                                return;
                            }else{
                                $sender->sendMessage(Manager::PREFIX_ALERT . "Vous devez patienter encore §a" . Main::getInstance()->convert(Main::getInstance()->cooldown->get($sender->getName() . "-repair-all") - time()) . "§c.");
                                return;
                            }
                        }
                    }
                }
            }else{
                $sender->sendMessage(Manager::NOT_PERM);
                return;
            }
        }
    }

}