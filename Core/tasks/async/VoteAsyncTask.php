<?php

namespace Zoumi\Core\tasks\async;

use pocketmine\Player;
use pocketmine\scheduler\AsyncTask;
use pocketmine\Server;
use pocketmine\utils\Config;
use pocketmine\utils\Internet;
use Zoumi\Core\api\PointBoutique;
use Zoumi\Core\Main;
use Zoumi\Core\Manager;
use Zoumi\Core\api\Box;

class VoteAsyncTask extends AsyncTask {

    /** @var string $username */
    private $username;

    public function __construct(string $username)
    {
        $this->username = $username;
    }

    public function onRun()
    {
        $result = Internet::getURL("https://minecraftpocket-servers.com/api/?object=votes&element=claim&key=ntBgsPSyzpoXwV7baUGCfJLMi38xcVf7myc&username=" . str_replace([" ", "_"], "+", $this->username));
        if($result === "1") Internet::getURL("https://minecraftpocket-servers.com/api/?action=post&object=votes&element=claim&key=ntBgsPSyzpoXwV7baUGCfJLMi38xcVf7myc&username=" . str_replace([" ", "_"], "+", $this->username));
        $this->setResult($result);
    }

    public function onCompletion(Server $server)
    {
        $player = $server->getPlayerExact($this->username);
        if ($player instanceof Player){
            switch ($this->getResult()){
                case "0":
                    $player->sendMessage(Manager::PREFIX_ALERT . "Vous n'avez pas encore voter pour §7Moon§elight §f!");
                    break;
                case "1":
                    $rand = mt_rand(1, 3);
                    Server::getInstance()->broadcastMessage("§e" . $player->getName() . " §fvient de voter pour §7Moon§elight §f! Il/elle a reçu §ex2 Clés de §2Vote §fainsi que §ex{$rand} Point(s) Boutique§f.");
                    Box::addKey($player->getName(), "vote", 2);
                    PointBoutique::addPB($player->getName(), $rand);
                    $player->sendMessage(Manager::PREFIX_INFOS . "Merci d'avoir voter pour §7Moon§elight §f! Tu as reçu §ex2 Clés de §2Vote §fainsi que §ex{$rand} Point(s) Boutique§f.");
                    $config = new Config("/home/ares/data/manager.json", Config::JSON);
                    $votes = $config->get("votePartySkyblock");
                    $config->set("votePartySkyblock", $votes + 1);
                    $config->save();
                    foreach (Server::getInstance()->getOnlinePlayers() as $player) {
                        if ($player instanceof Player) {
                            if (in_array($player->getName(), Main::getInstance()->scoreboard)) {
                                Main::getInstance()->scoreboard[$player->getName()]
                                    ->setLine(8, "§6➥ §eVoteParty: §f" . Main::getManagerConfig()->get("votePartySkyblock") . "§7/§f150")
                                    ->set();
                            }
                        }
                    }
                    break;
                default:
                    $player->sendMessage(Manager::PREFIX_INFOS . "Vous avez déjà voté pour §7Moon§elight §faujourd'hui.");
                    break;
            }
        }
    }

}