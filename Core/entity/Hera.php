<?php

namespace Zoumi\Core\entity;

use pocketmine\block\Flowable;
use pocketmine\entity\Human;
use pocketmine\entity\Skin;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\item\Item;
use pocketmine\level\Level;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\Player;
use pocketmine\Server;
use Zoumi\Core\Main;

class Hera extends Human {

    public $height = 1.8;

    private $target = "";
    private $motionY;
    private $attackTick = 20;
    private $findTargetTick = 100;

    public function __construct(Level $level, CompoundTag $nbt)
    {
        parent::__construct($level, $nbt);
        $this->setNameTagAlwaysVisible(true);
        $this->namedtag = "§l§dHéra";
        $this->setScoreTag("§l§7[§f" . $this->getHealth() . "§7/§f" . $this->getMaxHealth() . "§7]");

        $path = Main::getInstance()->getDataFolder()."hera.png";
        $data = Main::getInstance()->PNGtoBYTES($path);
        $cape = "";
        $path = Main::getInstance()->getDataFolder()."hera-geo.json";
        $geometry = file_get_contents($path);

        $this->setSkin(new Skin("Hera", $data, $cape, "geometry.hera", $geometry));

        $this->setScale(2);
    }

    public function getMaxHealth(): int
    {
        return 500;
    }

    public function getHealth(): float
    {
        return 500;
    }

    public function getName(): string
    {
        return "Hera";
    }

    public function entityBaseTick(int $tickDiff = 1) : bool
    {
        if($this->closed)
        {
            $this->flagForDespawn();
            return false;
        }

        if($this->isAlive())
        {
            $this->setNameTag("§l§dHéra");
            $this->setScoreTag("§l§7[§f" . $this->getHealth() . "§7/§f" . $this->getMaxHealth() . "§7]");

            if(is_null($this->getTargetAsPlayer()) || $this->getTargetAsPlayer()->distance($this) >= 15)
            {
                $this->target = "";
            }

            if(--$this->findTargetTick === 0)
            {
                if(is_null($this->getTargetAsPlayer()))
                {
                    $this->target = $this->findTarget();
                }
                if(!is_null($this->getTargetAsPlayer()) && !$this->isTargetAlive())
                {
                    $this->target = "";
                }
                $this->findTargetTick = 40;
            }

            if($this->onGround && !is_null($this->getTargetAsPlayer()))
            {
                $target = $this->getTargetAsPlayer();
                $x = $target->getX() - $this->getX();
                $y = $target->getY() - $this->getY();
                $z = $target->getZ() - $this->getZ();
                if($x ** 2 + $z ** 2 < 0.7)
                {
                    $this->motion->x = 0;
                    $this->motion->z = 0;
                }
                else
                {
                    $diff = abs($x) + abs($z);
                    $this->motion->x = $this->getSpeed() * 0.15 * ($x / $diff);
                    $this->motion->z = $this->getSpeed() * 0.15 * ($z / $diff);
                }

                $this->yaw = rad2deg(atan2(-$x, $z));
                $this->pitch = rad2deg(atan(-$y));
                if($this->needToJump())
                {
                    $this->customJump();
                }
                $this->move($this->motion->x, $this->motion->y, $this->motion->z);
            }

            if(--$this->attackTick <= 0)
            {
                if(!is_null($this->getTargetAsPlayer()))
                {
                    $target = $this->getTargetAsPlayer();
                    if($target->distance($this) <= mt_rand(3,4.5))
                    {
                        $this->attackTarget();
                        $this->attackTick = mt_rand(15,20);
                    }
                }
            }
        }

        return parent::entityBaseTick($tickDiff);
    }

    public function isTargetAlive() : bool
    {
        $target = $this->getTargetAsPlayer();
        if(is_null($target))
        {
            return false;
        }

        return $target->isAlive();
    }

    public function getCollidedBlock($y = 0): \pocketmine\block\Block
    {
        $dir = $this->getDirectionVector();
        return $this->getLevel()->getBlock($this->asVector3()->add($dir->getX() * $this->getScale(), $y + 1, $dir->getZ() * $this->getScale())->round());
    }

    public function customJump()
    {
        $this->motionY = $this->gravity * 16;
        $this->move($this->motion->x * 1.40, $this->motionY, $this->motion->z * 1.40);
    }

    public function needToJump() : bool
    {
        return $this->isCollidedHorizontally || ($this->getCollidedBlock()->getId() !== 0 && $this->getCollidedBlock(1)->getId() !== 0 && !$this->getCollidedBlock() instanceof Flowable);
    }

    public function attackTarget()
    {
        $target = $this->getTargetAsPlayer();
        $ev = new EntityDamageByEntityEvent($this, $target, EntityDamageEvent::CAUSE_ENTITY_ATTACK, $this->getAttackDamage());
        $this->broadcastEntityEvent(4);
        $target->attack($ev);
    }

    public function findTarget() : string
    {
        foreach ($this->getLevel()->getPlayers() as $player)
        {
            if($player->distance($this) <= 15 && !$player->isCreative() && !$player->isSpectator())
            {
                return $player->getName();
            }
        }
        return "";
    }

    static function getArrayDropsFromString(string $str = null)
    {
        if(is_null($str) || empty($str)) return [];

        $return = [];
        foreach (explode(",", $str) as $rowItem)
        {
            if(strpos($rowItem, ":") !== false)
            {
                $jveuxtetaperptn = explode(":", $rowItem);

                if(count($jveuxtetaperptn) <= 2)
                {
                    $id = $jveuxtetaperptn[0];
                    $meta = $jveuxtetaperptn[1];
                    $amount = 1;
                }
                else
                {
                    $id = $jveuxtetaperptn[0];
                    $meta = $jveuxtetaperptn[1];
                    $amount = $jveuxtetaperptn[2];
                }
            }
            else
            {
                $id = $rowItem;
                $meta = 0;
                $amount = 1;
            }
            $return[] = Item::get($id, $meta, $amount);
        }
        return $return;
    }

    public function getTargetAsPlayer() : ?Player
    {
        return Server::getInstance()->getPlayerExact($this->target);
    }

    public function getAttackDamage(): int
    {
        return 5;
    }

    public function getSpeed(): int
    {
        return 1;
    }

    public function getDrops(): array
    {
        return [Item::get(Item::GOLD_INGOT, 0, 1)];
    }

    public function getXpDropAmount(): int
    {
        return 50;
    }

}