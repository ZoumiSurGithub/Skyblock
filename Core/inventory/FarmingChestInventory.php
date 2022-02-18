<?php

namespace Zoumi\Core\inventory;

use pocketmine\inventory\ContainerInventory;
use pocketmine\level\Position;
use pocketmine\network\mcpe\protocol\BlockEventPacket;
use pocketmine\network\mcpe\protocol\LevelSoundEventPacket;
use pocketmine\network\mcpe\protocol\types\WindowTypes;
use pocketmine\Player;
use Zoumi\Core\tiles\FarmingChestTile;

class FarmingChestInventory extends ContainerInventory {

    /** @var FarmingChestTile */
    protected $holder;

    public function __construct(FarmingChestTile $tile){
        parent::__construct($tile);
    }

    public function getNetworkType() : int{
        return WindowTypes::CONTAINER;
    }

    public function getName() : string{
        return "Farming Chest";
    }

    public function getDefaultSize() : int{
        return 27;
    }

    /**
     * This override is here for documentation and code completion purposes only.
     * @return FarmingChestTile|Position
     */
    public function getHolder(){
        return $this->holder;
    }

    protected function getOpenSound() : int{
        return LevelSoundEventPacket::SOUND_CHEST_OPEN;
    }

    protected function getCloseSound() : int{
        return LevelSoundEventPacket::SOUND_CHEST_CLOSED;
    }

    public function onOpen(Player $who) : void{
        parent::onOpen($who);

        if(count($this->getViewers()) === 1 and $this->getHolder()->isValid()){
            //TODO: this crap really shouldn't be managed by the inventory
            $this->broadcastBlockEventPacket(true);
            $this->getHolder()->getLevelNonNull()->broadcastLevelSoundEvent($this->getHolder()->add(0.5, 0.5, 0.5), $this->getOpenSound());
        }
    }

    public function onClose(Player $who) : void{
        if(count($this->getViewers()) === 1 and $this->getHolder()->isValid()){
            //TODO: this crap really shouldn't be managed by the inventory
            $this->broadcastBlockEventPacket(false);
            $this->getHolder()->getLevelNonNull()->broadcastLevelSoundEvent($this->getHolder()->add(0.5, 0.5, 0.5), $this->getCloseSound());
        }
        parent::onClose($who);
    }

    protected function broadcastBlockEventPacket(bool $isOpen) : void{
        $holder = $this->getHolder();

        $pk = new BlockEventPacket();
        $pk->x = (int) $holder->x;
        $pk->y = (int) $holder->y;
        $pk->z = (int) $holder->z;
        $pk->eventType = 1; //it's always 1 for a chest
        $pk->eventData = $isOpen ? 1 : 0;
        $holder->getLevelNonNull()->broadcastPacketToViewers($holder, $pk);
    }

}