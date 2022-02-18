<?php
/**
 * Inspirée de SimpleFaction. Fait par ayzrix.
 */
namespace Zoumi\Core\tasks;

use pocketmine\level\particle\DustParticle;
use pocketmine\math\Vector3;
use pocketmine\Player;
use pocketmine\scheduler\Task;
use pocketmine\Server;
use Zoumi\Core\Main;

class ChunkBorderTask extends Task {

    public function onRun(int $currentTick)
    {
        if (empty(Main::getInstance()->chunk)) return false;
        foreach (Main::getInstance()->chunk as $name) {
            $player = Server::getInstance()->getPlayer($name);
            if ($player instanceof Player) {
                if ($player->isOnline()) {
                    $level = $player->getLevel();
                    $chunk = $level->getChunkAtPosition($player);
                    $chunkX = $chunk->getX();
                    $chunkZ = $chunk->getZ();
                    $minX = (float)$chunk->getX() * 16;
                    $maxX = (float)$minX + 16;
                    $minZ = (float)$chunk->getZ() * 16;
                    $maxZ = (float)$minZ + 16;

                    for ($x = $minX; $x <= $maxX; $x += 0.5) {
                        for ($z = $minZ; $z <= $maxZ; $z += 0.5) {
                            if ($x === $minX || $x === $maxX || $z === $minZ || $z === $maxZ) {
                                $player->getLevel()->addParticle(new DustParticle(new Vector3($x, $player->getY() + 0.8, $z), 184, 4, 4), [$player]);
                            }
                        }
                    }
                }else{
                    unset(Main::getInstance()->chunk[$name]);
                }
            }else{
                unset(Main::getInstance()->chunk[$name]);
            }
        }
        return true;
    }

}