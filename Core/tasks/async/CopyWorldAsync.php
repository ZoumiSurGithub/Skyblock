<?php

namespace Zoumi\Core\tasks\async;

use pocketmine\Player;
use pocketmine\scheduler\AsyncTask;
use pocketmine\Server;
use Zoumi\Core\api\SkyBlock;
use Zoumi\Core\api\Users;
use Zoumi\Core\Manager;

class CopyWorldAsync extends AsyncTask {

    /** @var string $name */
    private $name;
    /** @var $player */
    private $player;

    public function __construct(string $player, string $name)
    {
        $this->name = $name;
        $this->player = $player;
    }

    public function onRun()
    {
        Users::copyWorld($this->name);
    }

    public function onCompletion(Server $server)
    {
        $server->loadLevel($this->name);
        $server->getLevelByName($this->name)->setSpawnLocation(SkyBlock::getSpawn($this->name));
    }

}