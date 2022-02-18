<?php

namespace Zoumi\Core\tasks\async;

use pocketmine\scheduler\AsyncTask;
use pocketmine\Server;
use Zoumi\Core\DataBase;

class MySQLAsync extends AsyncTask {

    /** @var string $query */
    private $query;

    public function __construct(string $query)
    {
        $this->query = $query;
    }

    public function onRun()
    {
        DataBase::getData()->query($this->query);
    }

}