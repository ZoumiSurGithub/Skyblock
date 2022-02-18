<?php

namespace Zoumi\Core\commands\ranked;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\item\Item;
use pocketmine\item\ItemIds;
use pocketmine\Player;
use Zoumi\Core\Main;
use Zoumi\Core\Manager;

class Furnace extends Command {
    
    public function __construct(string $name, string $description = "", string $usageMessage = null, array $aliases = [])
    {
        parent::__construct($name, $description, $usageMessage, $aliases);
    }
    
    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        if ($sender instanceof Player){
            if (Main::getInstance()->furnace->exists($sender->getName()) or $sender->hasPermission("use.furnace")){
                if (!isset($args[0])){
                    $item = $sender->getInventory()->getItemInHand();
                    switch ($item->getId()){
                        case ItemIds::BEEF:
                            $itm = Item::get(Item::COOKED_BEEF, 0, $item->getCount());
                            $sender->getInventory()->setItemInHand($itm);
                            $sender->sendMessage(Manager::PREFIX_INFOS . "L'item dans votre main a bien été cuit.");
                            break;
                        case ItemIds::CHICKEN:
                            $itm = Item::get(Item::COOKED_CHICKEN, 0, $item->getCount());
                            $sender->getInventory()->setItemInHand($itm);
                            $sender->sendMessage(Manager::PREFIX_INFOS . "L'item dans votre main a bien été cuit.");
                            break;
                        case ItemIds::FISH:
                            $itm = Item::get(Item::COOKED_FISH, 0, $item->getCount());
                            $sender->getInventory()->setItemInHand($itm);
                            $sender->sendMessage(Manager::PREFIX_INFOS . "L'item dans votre main a bien été cuit.");
                            break;
                        case ItemIds::MUTTON:
                            $itm = Item::get(Item::COOKED_MUTTON, 0, $item->getCount());
                            $sender->getInventory()->setItemInHand($itm);
                            $sender->sendMessage(Manager::PREFIX_INFOS . "L'item dans votre main a bien été cuit.");
                            break;
                        case ItemIds::PORKCHOP:
                            $itm = Item::get(Item::COOKED_PORKCHOP, 0, $item->getCount());
                            $sender->getInventory()->setItemInHand($itm);
                            $sender->sendMessage(Manager::PREFIX_INFOS . "L'item dans votre main a bien été cuit.");
                            break;
                        case ItemIds::RABBIT:
                            $itm = Item::get(Item::COOKED_RABBIT, 0, $item->getCount());
                            $sender->getInventory()->setItemInHand($itm);
                            $sender->sendMessage(Manager::PREFIX_INFOS . "L'item dans votre main a bien été cuit.");
                            break;
                        case ItemIds::SALMON:
                            $itm = Item::get(Item::COOKED_SALMON, 0, $item->getCount());
                            $sender->getInventory()->setItemInHand($itm);
                            $sender->sendMessage(Manager::PREFIX_INFOS . "L'item dans votre main a bien été cuit.");
                            break;
                        case ItemIds::DIAMOND_ORE:
                            $itm = Item::get(Item::DIAMOND, 0, $item->getCount());
                            $sender->getInventory()->setItemInHand($itm);
                            $sender->sendMessage(Manager::PREFIX_INFOS . "L'item dans votre main a bien été cuit.");
                            break;
                        case ItemIds::IRON_ORE:
                            $itm = Item::get(Item::IRON_INGOT, 0, $item->getCount());
                            $sender->getInventory()->setItemInHand($itm);
                            $sender->sendMessage(Manager::PREFIX_INFOS . "L'item dans votre main a bien été cuit.");
                            break;
                        case ItemIds::GOLD_ORE:
                            $itm = Item::get(Item::GOLD_INGOT, 0, $item->getCount());
                            $sender->getInventory()->setItemInHand($itm);
                            $sender->sendMessage(Manager::PREFIX_INFOS . "L'item dans votre main a bien été cuit.");
                            break;
                        default:
                            $sender->sendMessage(Manager::PREFIX_ALERT . "L'item dans votre main ne peut pas être cuit.");
                            break;
                    }
                }else{
                    if ($sender->hasPermission("use.furnace.all")){
                        if (strtolower($args[0]) === "all"){
                            foreach ($sender->getInventory()->getContents() as $slot => $item){
                                switch ($item->getId()){
                                    case ItemIds::BEEF:
                                        $itm = Item::get(Item::COOKED_BEEF, 0, $item->getCount());
                                        $sender->getInventory()->setItem($slot, $itm);
                                        break;
                                    case ItemIds::CHICKEN:
                                        $itm = Item::get(Item::COOKED_CHICKEN, 0, $item->getCount());
                                        $sender->getInventory()->setItem($slot, $itm);
                                        break;
                                    case ItemIds::FISH:
                                        $itm = Item::get(Item::COOKED_FISH, 0, $item->getCount());
                                        $sender->getInventory()->setItem($slot, $itm);
                                        break;
                                    case ItemIds::MUTTON:
                                        $itm = Item::get(Item::COOKED_MUTTON, 0, $item->getCount());
                                        $sender->getInventory()->setItem($slot, $itm);
                                        break;
                                    case ItemIds::PORKCHOP:
                                        $itm = Item::get(Item::COOKED_PORKCHOP, 0, $item->getCount());
                                        $sender->getInventory()->setItem($slot, $itm);
                                        break;
                                    case ItemIds::RABBIT:
                                        $itm = Item::get(Item::COOKED_RABBIT, 0, $item->getCount());
                                        $sender->getInventory()->setItem($slot, $itm);
                                        break;
                                    case ItemIds::SALMON:
                                        $itm = Item::get(Item::COOKED_SALMON, 0, $item->getCount());
                                        $sender->getInventory()->setItem($slot, $itm);
                                        break;
                                    case ItemIds::DIAMOND_ORE:
                                        $itm = Item::get(Item::DIAMOND, 0, $item->getCount());
                                        $sender->getInventory()->setItem($slot, $itm);
                                        break;
                                    case ItemIds::IRON_ORE:
                                        $itm = Item::get(Item::IRON_INGOT, 0, $item->getCount());
                                        $sender->getInventory()->setItem($slot, $itm);
                                        break;
                                    case ItemIds::GOLD_ORE:
                                        $itm = Item::get(Item::GOLD_INGOT, 0, $item->getCount());
                                        $sender->getInventory()->setItem($slot, $itm);
                                        break;
                                }
                            }
                            $sender->sendMessage(Manager::PREFIX_INFOS . "Tous les items cuisâbles ont été cuit.");
                            return;
                        }
                    }else{
                        $sender->sendMessage(Manager::NOT_PERM);
                        return;
                    }
                }
            }else{
                $sender->sendMessage(Manager::NOT_PERM);
                return;
            }
        }
    }

}