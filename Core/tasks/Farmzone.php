<?php

namespace Zoumi\Core\tasks;

use pocketmine\item\Item;
use pocketmine\item\ItemIds;
use pocketmine\network\mcpe\protocol\PlaySoundPacket;
use pocketmine\Player;
use pocketmine\scheduler\Task;
use pocketmine\Server;
use pocketmine\utils\BinaryStream;
use Zoumi\Core\Manager;

class Farmzone extends Task {

    public static $players = [];

    public function onRun(int $currentTick)
    {
        $x = 203.5; //PETIT
        $x2 = 209.5; //GRAND
        $y = 80; //PETIT
        $y2 = 85; //GRAND
        $z = 222.5; //PETIT
        $z2 = 226.5; //GRAND
        $levelname = "farmzone1";
        foreach (Server::getInstance()->getOnlinePlayers() as $player){
            if ($player instanceof Player){
                if (isset(self::$players[$player->getName()])) {
                    if ($player->getX() > $x and $player->getX() < $x2 and $player->getY() >= $y and $player->getY() <= $y2 and $player->getZ() > $z and $player->getZ() < $z2 and $player->getLevel()->getFolderName() == $levelname) {
                    } else {
                        unset(self::$players[$player->getName()]);
                        $player->sendMessage(Manager::PREFIX_INFOS . "Vous avez quitter la zone de purification.");
                    }
                }
            }
        }
        foreach (Server::getInstance()->getOnlinePlayers() as $player) {
            if ($player instanceof Player) {
                if ($player->getX() > $x and $player->getX() < $x2 and $player->getY() >= $y and $player->getY() <= $y2 and $player->getZ() > $z and $player->getZ() < $z2 and $player->getLevel()->getFolderName() == $levelname) {
                    if (isset(self::$players[$player->getName()])) {
                        $item = $player->getInventory()->getItemInHand();
                        if ($item->getId() === ItemIds::GOLD_NUGGET){
                            if (++self::$players[$player->getName()] === 60){
                                $itm = Item::get(Item::GOLD_INGOT);
                                if ($player->getInventory()->canAddItem($itm)) {
                                    self::$players[$player->getName()] = 0;
                                    $item->setCount($item->getCount() - 1);
                                    $player->getInventory()->setItemInHand($item);
                                    $player->getInventory()->addItem($itm);
                                    $player->sendPopup("§e§lFragment purifié !");
                                }else{
                                    $player->sendMessage(Manager::PREFIX_ALERT . "Votre inventaire est plein !");
                                }
                            }else{
                                $player->sendPopup("§fEn cours de purification\n§l§7[§e" . self::$players[$player->getName()] . "§7/§660§7]");
                            }
                        }else{
                            $player->sendMessage(Manager::PREFIX_ALERT . "Vous devez avoir un fragment de rubis endomagé dans votre main pour le purifier.");
                        }
                    } else {
                        $player->sendMessage(Manager::PREFIX_INFOS . "Vous venez de rentré dans la zone de purification.");
                        self::$players[$player->getName()] = 0;
                    }
                }
            }
        }
    }

}