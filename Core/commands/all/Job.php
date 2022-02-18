<?php

namespace Zoumi\Core\commands\all;

use jojoe77777\FormAPI\ModalForm;
use jojoe77777\FormAPI\SimpleForm;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use Zoumi\Core\api\Jobs;
use Zoumi\Core\api\SkyBlock;

class Job extends Command {

    public function __construct(string $name, string $description = "", string $usageMessage = null, array $aliases = [])
    {
        parent::__construct($name, $description, $usageMessage, $aliases);
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        if ($sender instanceof Player){
            Job::sendJobMenu($sender);
        }
    }

    public static function sendJobMenu(Player $player){
        $ui = new SimpleForm(function (Player $player, $data){
            if ($data === null){
                return;
            }
            switch ($data){
                case 0:
                    Job::sendFarmerMenu($player);
                    break;
                case 1:
                    Job::sendMinerMenu($player);
                    break;
                case 2:
                    Job::sendChasseurMenu($player);
                    break;
                case 3:
                    Job::sendBucheronMenu($player);
                    break;
            }
        });
        $ui->setTitle("§l§dSkyBlock §f- §7Moon§elight");
        $ui->setContent("§7Bob: §fHummm... Tu progresses vite.");
        $farmer = 20 - Jobs::getLevelForJob($player->getName(), "farmer");
        $miner = 20 - Jobs::getLevelForJob($player->getName(), "miner");
        $chasseur = 20 - Jobs::getLevelForJob($player->getName(), "chasseur");
        $bucheron = 20 - Jobs::getLevelForJob($player->getName(), "bucheron");
        $ui->addButton("Farmeur\n" . str_repeat("§a|", Jobs::getLevelForJob($player->getName(), "farmer")) . str_repeat("§c|", $farmer), 0, "textures/items/diamond_hoe");
        $ui->addButton("Mineur\n" . str_repeat("§a|", Jobs::getLevelForJob($player->getName(), "miner")) . str_repeat("§c|", $miner), 0, "textures/items/diamond_pickaxe");
        $ui->addButton("Chasseur\n" . str_repeat("§a|", Jobs::getLevelForJob($player->getName(), "chasseur")) . str_repeat("§c|",$chasseur), 0, "textures/items/diamond_sword");
        $ui->addButton("Bucheron\n" . str_repeat("§a|", Jobs::getLevelForJob($player->getName(), "bucheron")) . str_repeat("§c|", $bucheron), 0, "textures/items/diamond_axe");
        $ui->sendToPlayer($player);
    }

    /** Farmer */
    public static function sendFarmerMenu(Player $player){
        $ui = new SimpleForm(function (Player $player, $data){
            if ($data === null){
                return;
            }
            switch ($data){
                case 0:
                    Job::sendFarmerInfos($player);
                    break;
                case 1:
                    Job::howToXpFarmer($player);
                    break;
            }
        });
        $ui->setTitle("§l§dSkyBlock §f- §7Moon§elight");
        $ui->setContent("§7Bob: §fQue souhaites tu savoir à propos de ce métier ?");
        $ui->addButton("Informations");
        $ui->addButton("Comment XP ?");
        $ui->sendToPlayer($player);
    }

    public static function sendFarmerInfos(Player $player){
        $ui = new ModalForm(function (Player $player, $data){
            if ($data === null){
                return;
            }
            switch ($data){
                case true:
                    Job::sendFarmerMenu($player);
                    break;
                case false:
                    break;
            }
        });
        $ui->setTitle("§l§dSkyBlock §f- §7Moon§elight");
        $xp = 0;

        if (Jobs::getXpForJob($player->getName(), "farmer") < 0.1){
            $xp = Jobs::getXpRequireForNextLevel($player->getName(), "farmer") / 50;
        }else{
            $xp = Jobs::getXpRequireForNextLevel($player->getName(), "farmer") - Jobs::getXpForJob($player->getName(), "farmer") / 50;
        }
        $ui->setContent(
            "§6Métier: §eFarmeur\n" .
            "§6Progression du niveau §7(" . Jobs::getLevelForJob($player->getName(), "farmer") . "/20)\n§e" . str_repeat("§a|", Jobs::getLevelForJob($player->getName(), "farmer")) . str_repeat("§c|", 20 - Jobs::getLevelForJob($player->getName(), "farmer")) . "\n" .
            "§6Progression d'xp §7(" . Jobs::getXpForJob($player->getName(), "farmer") . "/" . Jobs::getXpRequireForNextLevel($player->getName(), "farmer") . ")\n§e" . str_repeat("§a|", Jobs::getXpForJob($player->getName(), "farmer") / 100) . str_repeat("§c|", $xp)
        );
        $ui->setButton1("RETOUR");
        $ui->setButton2("QUITTER");
        $ui->sendToPlayer($player);
    }

    public static function howToXpFarmer(Player $player){
        $ui = new ModalForm(function (Player $player, $data){
            if ($data === null){
                return;
            }
            switch ($data){
                case true:
                    Job::sendFarmerMenu($player);
                    break;
                case false:
                    break;
            }
        });
        $ui->setTitle("§l§dSkyBlock §f- §7Moon§elight");
        $ui->setContent(
            "§7Bob: §fIl existe de différente manière pour xp  le métier de farmeur, les voici.\n\n" .
            "§6Casser du blé §f-> §e0.3 d'xp\n" .
            "§6Casser une carotte/patate §f-> §e0.5 d'xp\n" .
            "§6Casser une citrouille/pastèque §f-> §e0.7 d'xp\n" .
            "§6Casser une verrue du nether §f-> §e1 d'xp"
        );
        $ui->setButton1("RETOUR");
        $ui->setButton2("QUITTER");
        $ui->sendToPlayer($player);
    }

    /** Miner */
    public static function sendMinerMenu(Player $player){
        $ui = new SimpleForm(function (Player $player, $data){
            if ($data === null){
                return;
            }
            switch ($data){
                case 0:
                    Job::sendMinerInfos($player);
                    break;
                case 1:
                    Job::howToXpMiner($player);
                    break;
            }
        });
        $ui->setTitle("§l§dSkyBlock §f- §7Moon§elight");
        $ui->setContent("§7Bob: §fQue souhaites tu savoir à propos de ce métier ?");
        $ui->addButton("Informations");
        $ui->addButton("Comment XP ?");
        $ui->sendToPlayer($player);
    }

    public static function sendMinerInfos(Player $player){
        $ui = new ModalForm(function (Player $player, $data){
            if ($data === null){
                return;
            }
            switch ($data){
                case true:
                    Job::sendMinerMenu($player);
                    break;
                case false:
                    break;
            }
        });
        $ui->setTitle("§l§dSkyBlock §f- §7Moon§elight");
        $xp = 0;
        if (Jobs::getXpForJob($player->getName(), "miner") < 0.1){
            $xp = Jobs::getXpRequireForNextLevel($player->getName(), "miner") / 50;
        }else{
            $xp = Jobs::getXpRequireForNextLevel($player->getName(), "miner") - Jobs::getXpForJob($player->getName(), "miner") / 50;
        }
        $ui->setContent(
            "§6Métier: §eMineur\n" .
            "§6Progression du niveau §7(" . Jobs::getLevelForJob($player->getName(), "miner") . "/20)\n§e" . str_repeat("§a|", Jobs::getLevelForJob($player->getName(), "miner")) . str_repeat("§c|", 20 - Jobs::getLevelForJob($player->getName(), "miner")) . "\n" .
            "§6Progression d'xp §7(" . Jobs::getXpForJob($player->getName(), "miner") . "/" . Jobs::getXpRequireForNextLevel($player->getName(), "miner") . ")\n§e" . str_repeat("§a|", Jobs::getXpForJob($player->getName(), "miner") / 100) . str_repeat("§c|", $xp)
        );
        $ui->setButton1("RETOUR");
        $ui->setButton2("QUITTER");
        $ui->sendToPlayer($player);
    }

    public static function howToXpMiner(Player $player){
        $ui = new ModalForm(function (Player $player, $data){
            if ($data === null){
                return;
            }
            switch ($data){
                case true:
                    Job::sendMinerMenu($player);
                    break;
                case false:
                    break;
            }
        });
        $ui->setTitle("§l§dSkyBlock §f- §7Moon§elight");
        $ui->setContent(
            "§7Bob: §fIl existe de différente manière pour xp  le métier de mineur, les voici.\n\n" .
            "§6Casser de la pierre taillée §f-> §e0.1 d'xp\n" .
            "§6Casser du charbon §f-> §e0.2 d'xp\n" .
            "§6Casser du fer §f-> §e0.4 d'xp\n" .
            "§6Casser du lapis ou de la redstone §f-> §e0.6 d'xp\n" .
            "§6Casser du diamant §f-> §e1 d'xp\n" .
            "§6Casser de l'émeraude §f-> §e2 d'xp"
        );
        $ui->setButton1("RETOUR");
        $ui->setButton2("QUITTER");
        $ui->sendToPlayer($player);
    }

    /** Chasseur */
    public static function sendChasseurMenu(Player $player){
        $ui = new SimpleForm(function (Player $player, $data){
            if ($data === null){
                return;
            }
            switch ($data){
                case 0:
                    Job::sendChasseurInfos($player);
                    break;
                case 1:
                    Job::howToXpChasseur($player);
                    break;
            }
        });
        $ui->setTitle("§l§dSkyBlock §f- §7Moon§elight");
        $ui->setContent("§7Bob: §fQue souhaites tu savoir à propos de ce métier ?");
        $ui->addButton("Informations");
        $ui->addButton("Comment XP ?");
        $ui->sendToPlayer($player);
    }

    public static function sendChasseurInfos(Player $player){
        $ui = new ModalForm(function (Player $player, $data){
            if ($data === null){
                return;
            }
            switch ($data){
                case true:
                    Job::sendChasseurMenu($player);
                    break;
                case false:
                    break;
            }
        });
        $ui->setTitle("§l§dSkyBlock §f- §7Moon§elight");
        $xp = 0;

        if (Jobs::getXpForJob($player->getName(), "chasseur") < 0.1){
            $xp = Jobs::getXpRequireForNextLevel($player->getName(), "chasseur") / 50;
        }else{
            $xp = Jobs::getXpRequireForNextLevel($player->getName(), "chasseur") - Jobs::getXpForJob($player->getName(), "chasseur") / 50;
        }
        $ui->setContent(
            "§6Métier: §eChasseur\n" .
            "§6Progression du niveau §7(" . Jobs::getLevelForJob($player->getName(), "chasseur") . "/20)\n§e" . str_repeat("§a|", Jobs::getLevelForJob($player->getName(), "chasseur")) . str_repeat("§c|", 20 - Jobs::getLevelForJob($player->getName(), "chasseur")) . "\n" .
            "§6Progression d'xp §7(" . Jobs::getXpForJob($player->getName(), "chasseur") . "/" . Jobs::getXpRequireForNextLevel($player->getName(), "chasseur") . ")\n§e" . str_repeat("§a|", Jobs::getXpForJob($player->getName(), "chasseur") / 100) . str_repeat("§c|", $xp)
        );
        $ui->setButton1("RETOUR");
        $ui->setButton2("QUITTER");
        $ui->sendToPlayer($player);
    }

    public static function howToXpChasseur(Player $player){
        $ui = new ModalForm(function (Player $player, $data){
            if ($data === null){
                return;
            }
            switch ($data){
                case true:
                    Job::sendChasseurMenu($player);
                    break;
                case false:
                    break;
            }
        });
        $ui->setTitle("§l§dSkyBlock §f- §7Moon§elight");
        $ui->setContent(
            "§7Bob: §fIl existe de différente manière pour xp  le métier de chasseur, les voici.\n\n" .
            "§6Tué une vache, un cochon ou un mouton §f-> §e0.3 d'xp\n" .
            "§6Tué un zombie, un squelette ou une araignée §f-> §e0.5 d'xp\n" .
            "§6Tué un creeper ou un enderman §f-> §e0.8 d'xp\n" .
            "§6Tué un joueur §f-> §e1.5 d'xp"
        );
        $ui->setButton1("RETOUR");
        $ui->setButton2("QUITTER");
        $ui->sendToPlayer($player);
    }

    /** Bûcheron */
    public static function sendBucheronMenu(Player $player){
        $ui = new SimpleForm(function (Player $player, $data){
            if ($data === null){
                return;
            }
            switch ($data){
                case 0:
                    Job::sendBucheronInfos($player);
                    break;
                case 1:
                    Job::howToXpBucheron($player);
                    break;
            }
        });
        $ui->setTitle("§l§dSkyBlock §f- §7Moon§elight");
        $ui->setContent("§7Bob: §fQue souhaites tu savoir à propos de ce métier ?");
        $ui->addButton("Informations");
        $ui->addButton("Comment XP ?");
        $ui->sendToPlayer($player);
    }

    public static function sendBucheronInfos(Player $player){
        $ui = new ModalForm(function (Player $player, $data){
            if ($data === null){
                return;
            }
            switch ($data){
                case true:
                    Job::sendBucheronMenu($player);
                    break;
                case false:
                    break;
            }
        });
        $ui->setTitle("§l§dSkyBlock §f- §7Moon§elight");
        $xp = 0;

        if (Jobs::getXpForJob($player->getName(), "bucheron") < 0.1){
            $xp = Jobs::getXpRequireForNextLevel($player->getName(), "bucheron") / 50;
        }else{
            $xp = Jobs::getXpRequireForNextLevel($player->getName(), "bucheron") - Jobs::getXpForJob($player->getName(), "bucheron") / 50;
        }
        $ui->setContent(
            "§6Métier: §eBucheron\n" .
            "§6Progression du niveau §7(" . Jobs::getLevelForJob($player->getName(), "bucheron") . "/20)\n§e" . str_repeat("§a|", Jobs::getLevelForJob($player->getName(), "bucheron")) . str_repeat("§c|", 20 - Jobs::getLevelForJob($player->getName(), "bucheron")) . "\n" .
            "§6Progression d'xp §7(" . Jobs::getXpForJob($player->getName(), "bucheron") . "/" . Jobs::getXpRequireForNextLevel($player->getName(), "bucheron") . ")\n§e" . str_repeat("§a|", Jobs::getXpForJob($player->getName(), "bucheron") / 100) . str_repeat("§c|", $xp)
        );
        $ui->setButton1("RETOUR");
        $ui->setButton2("QUITTER");
        $ui->sendToPlayer($player);
    }

    public static function howToXpBucheron(Player $player){
        $ui = new ModalForm(function (Player $player, $data){
            if ($data === null){
                return;
            }
            switch ($data){
                case true:
                    Job::sendBucheronMenu($player);
                    break;
                case false:
                    break;
            }
        });
        $ui->setTitle("§l§dSkyBlock §f- §7Moon§elight");
        $ui->setContent(
            "§7Bob: §fIl existe de différente manière pour xp  le métier de bucheron, les voici.\n\n" .
            "§6Casser des bûches §f-> §e0.2 d'xp\n" .
            "§6Crafter une hache en fer §f-> §e0.3 d'xp\n" .
            "§6Crafter une hache en diamant §f-> §e0.6 d'xp\n" .
            "§6Crafter une hache en rubis §f-> §e1 d'xp"
        );
        $ui->setButton1("RETOUR");
        $ui->setButton2("QUITTER");
        $ui->sendToPlayer($player);
    }

}