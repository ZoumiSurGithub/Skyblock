<?php

namespace Zoumi\Core\commands;

use jojoe77777\FormAPI\SimpleForm;
use pocketmine\block\Slab;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\Server;
use pocketmine\tile\Skull;
use Zoumi\Core\api\SkyBlock;
use Zoumi\Core\api\Users;
use Zoumi\Core\Manager;
use Zoumi\Core\Manager as MN;
use Zoumi\Core\Main;
use Zoumi\Core\tasks\async\CopyWorldAsync;

class Island extends Command {

    public function __construct(string $name, string $description = "", string $usageMessage = null, array $aliases = [])
    {
        parent::__construct($name, $description, $usageMessage, $aliases);
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        if ($sender instanceof Player){
            if (!isset($args[0])){
                $sender->sendMessage(MN::PREFIX_ALERT . "Pour voir la liste des sous commandes disponibles avec /is, faites /is help.");
                return;
            }
            switch (strtolower($args[0])){
                /* CREATION, SUPPRESSION et LEAVE */
                case "create":
                    if (!SkyBlock::hasIsland($sender->getName())){
                        if (!isset($args[1])){
                            $sender->sendMessage(MN::PREFIX_ALERT . "Veuillez faire /is create [nom de l'île].");
                            return;
                        }
                        if (strlen($args[1]) > 15){
                            $sender->sendMessage(MN::PREFIX_ALERT . "Le nom que vous avez mis est trop grand.");
                            return;
                        }
                        if (!SkyBlock::verif_alpha($args[1])){
                            $sender->sendMessage(MN::PREFIX_ALERT . "Le nom que vous avez mis doit possédez que des lettres.");
                            return;
                        }
                        if (SkyBlock::islandExist($args[1])){
                            $sender->sendMessage(Manager::PREFIX_ALERT . "Une île existe déjà avec ce nom.");
                            return;
                        }
                        foreach (MN::BANNED_NAMES as $BANNED_NAME){
                            if(strpos(strtolower($args[1]), $BANNED_NAME) !== false) {
                                $sender->sendMessage(MN::PREFIX_ALERT . "Le nom que vous avez mis contient soit un espace, soit un mot interdit.");
                                return;
                            }
                        }
                        Server::getInstance()->broadcastMessage(MN::PREFIX . "§e" . $sender->getName() . " §fvient de créer l'île §6" . $args[1]);
                        SkyBlock::createIsland($sender->getName(), $args[1]);
                        Server::getInstance()->getAsyncPool()->submitTask(new CopyWorldAsync($sender->getName(), $args[1]));
                    }else{
                        $sender->sendMessage(MN::HAS_ISLAND);
                        return;
                    }
                    break;
                case "leave":
                    if (!SkyBlock::hasIsland($sender->getName())){
                        $sender->sendMessage(MN::NOT_HAS_ISLAND);
                        return;
                    }
                    if (SkyBlock::isLeader($sender, SkyBlock::getIslandName($sender->getName()))){
                        $sender->sendMessage(MN::PREFIX_ALERT . "Vous êtes le chef de l'île ! Faites /is delete pour supprimer l'île. §lATTENTION UNE FOIS SUPPRIMER VOUS PERDEZ TOUT DE CE QU'IL A DEDANS");
                        return;
                    }
                    SkyBlock::broadcastMemberIsland(SkyBlock::getIslandName($sender->getName()), "§e" . $sender->getName() . " §fvient de quitté l'île.");
                    $sender->teleport(Server::getInstance()->getDefaultLevel()->getSafeSpawn());
                    SkyBlock::leaveIsland($sender->getName(), SkyBlock::getIslandName($sender->getName()));
                    break;
                case "delete":
                    if (!SkyBlock::hasIsland($sender->getName())){
                        $sender->sendMessage(MN::NOT_HAS_ISLAND);
                        return;
                    }
                    if (empty(SkyBlock::isLeader($sender, SkyBlock::getIslandName($sender->getName())))){
                        $sender->sendMessage(MN::PREFIX_ALERT . "Seul le chef de l'île peut faire cette sous-commande.");
                        return;
                    }
                    if (!empty(Server::getInstance()->getLevelByName(SkyBlock::getIslandName($sender->getName()))->getPlayers())) {
                        foreach (Server::getInstance()->getLevelByName(SkyBlock::getIslandName($sender->getName()))->getPlayers() as $player) {
                            $player->teleport(Server::getInstance()->getDefaultLevel()->getSafeSpawn());
                        }
                    }
                    Server::getInstance()->getLevelByName(SkyBlock::getIslandName($sender->getName()))->unload(true);
                    Users::removeDir(Server::getInstance()->getDataPath() . "worlds/" . SkyBlock::getIslandName($sender->getName()));
                    SkyBlock::broadcastMemberIsland(SkyBlock::getIslandName($sender->getName()), "§e" . $sender->getName() . " §fvient de supprimé l'île.");
                    SkyBlock::removeIsland(SkyBlock::getIslandName($sender->getName()));
                    break;
                    /** Join, setspawn. */
                case "join":
                case "teleport":
                case "go":
                    if (!SkyBlock::hasIsland($sender->getName())) {
                        $sender->sendMessage(MN::NOT_HAS_ISLAND);
                        return;
                    }
                    $sender->teleport(SkyBlock::getSpawn(SkyBlock::getIslandName($sender->getName())));
                    $sender->sendMessage(MN::PREFIX_INFOS . "Vous venez d'être téléporter sur votre île.");
                    break;
                case "setspawn":
                    if (!SkyBlock::hasIsland($sender->getName())){
                        $sender->sendMessage(MN::NOT_HAS_ISLAND);
                        return;
                    }
                    if (empty(SkyBlock::isLeader($sender, SkyBlock::getIslandName($sender->getName()))) or !SkyBlock::isLeader($sender, SkyBlock::getIslandName($sender->getName()))){
                        if (empty(SkyBlock::isOfficer($sender, SkyBlock::getIslandName($sender->getName()))) or !SkyBlock::isOfficer($sender, SkyBlock::getIslandName($sender->getName()))){
                            $sender->sendMessage(MN::PREFIX_ALERT . "Seul l'officier/chef peut effectué cette sous commande.");
                            return;
                        }
                    }
                    SkyBlock::setSpawn(Server::getInstance()->getLevelByName(SkyBlock::getIslandName($sender->getName())), $sender);
                    $sender->sendMessage(MN::PREFIX_INFOS . "Vous avez bien changé le spawn de l'île.");
                    break;
                    /** Invitation */
                case "invite":
                    if (!SkyBlock::hasIsland($sender->getName())){
                        $sender->sendMessage(MN::NOT_HAS_ISLAND);
                        return;
                    }
                    if (empty(SkyBlock::isLeader($sender, SkyBlock::getIslandName($sender->getName()))) or !SkyBlock::isLeader($sender, SkyBlock::getIslandName($sender->getName()))){
                        if (empty(SkyBlock::isOfficer($sender, SkyBlock::getIslandName($sender->getName()))) or !SkyBlock::isOfficer($sender, SkyBlock::getIslandName($sender->getName()))){
                            $sender->sendMessage(MN::PREFIX_ALERT . "Seul l'officier/chef peut effectué cette sous commande.");
                            return;
                        }
                    }
                    if (SkyBlock::getCount(SkyBlock::getIslandName($sender->getName())) >= 6){
                        $sender->sendMessage(MN::PREFIX_ALERT . "Vous avez atteint la limite de membre par île.");
                        return;
                    }
                    if (!isset($args[1])) {
                        $sender->sendMessage(MN::PREFIX_ALERT . "Veuillez faire /is invite [joueur].");
                        return;
                    }
                    $target = Server::getInstance()->getPlayer($args[1]);
                    if ($target instanceof Player){
                        Main::getInstance()->invite[$target->getName()] = [
                            "timeLeft" => time() + 30,
                            "owner" => $sender->getName()
                        ];
                        $target->sendMessage(MN::PREFIX_ALERT . "§e" . $sender->getName() . " §fvous a inviter à être membre sur son île.");
                        $sender->sendMessage(MN::PREFIX_INFOS . "L'invitation a bien été envoyer.");
                        return;
                    }else{
                        $sender->sendMessage(MN::PLAYER_NOT_EXIST_IN_DATA);
                        return;
                    }
                    break;
                case "accept":
                    if (SkyBlock::hasIsland($sender->getName())){
                        $sender->sendMessage(MN::HAS_ISLAND);
                        return;
                    }
                    if (!isset(Main::getInstance()->invite[$sender->getName()]) or time() >= Main::getInstance()->invite[$sender->getName()]){
                        $sender->sendMessage(MN::PREFIX_ALERT . "Vous n'avez aucune invitation d'île.");
                        return;
                    }else{
                        SkyBlock::setIsland($sender->getName(), SkyBlock::getIslandName(Main::getInstance()->invite[$sender->getName()]["owner"]));
                        SkyBlock::broadcastMemberIsland(SkyBlock::getIslandName(Main::getInstance()->invite[$sender->getName()]["owner"]), "§e" . $sender->getName() . " §fvient de rejoindre l'île en tant que membre.");
                        return;
                    }
                    break;
                case "deny":
                    if (time() >= Main::getInstance()->invite[$sender->getName()] or !isset(Main::getInstance()->invite[$sender->getName()])){
                        $sender->sendMessage(MN::PREFIX_ALERT . "Vous n'avez aucune invitation d'île.");
                        return;
                    }else{
                        unset(Main::getInstance()->invite[$sender->getName()]);
                        $sender->sendMessage(MN::PREFIX_INFOS . "Vos invitations d'île ont été supprimer.");
                        return;
                    }
                    break;
                case "chat":
                    if (!SkyBlock::hasIsland($sender->getName())){
                        $sender->sendMessage(MN::NOT_HAS_ISLAND);
                        return;
                    }
                    if (isset(Main::getInstance()->chat[$sender->getName()])){
                        unset(Main::getInstance()->chat[$sender->getName()]);
                        $sender->sendMessage(MN::PREFIX_INFOS . "Vous parlez désormais dans le chat global.");
                        return;
                    }else{
                        Main::getInstance()->chat[$sender->getName()] = $sender->getName();
                        $sender->sendMessage(MN::PREFIX_INFOS . "Vous parlez désormais dans le chat de l'île.");
                        return;
                    }
                    break;
                    /** Points d'île */
                case "top":
                    SkyBlock::getTopPoints($sender);
                    break;
                    /** Settings */
                case "settings":
                    if (!SkyBlock::hasIsland($sender->getName())){
                        $sender->sendMessage(MN::NOT_HAS_ISLAND);
                        return;
                    }
                    if (empty(SkyBlock::isLeader($sender, SkyBlock::getIslandName($sender->getName()))) or !SkyBlock::isLeader($sender, SkyBlock::getIslandName($sender->getName()))){
                        if (empty(SkyBlock::isOfficer($sender, SkyBlock::getIslandName($sender->getName()))) or !SkyBlock::isOfficer($sender, SkyBlock::getIslandName($sender->getName()))){
                            $sender->sendMessage(MN::PREFIX_ALERT . "Seul l'officier/chef peut effectué cette sous commande.");
                            return;
                        }
                    }
                    Island::openSettingsMenu($sender);
                    break;
                    /** Infos */
                case "infos":
                    if (!isset($args[1])){
                        if (!SkyBlock::hasIsland($sender->getName())){
                            $sender->sendMessage(MN::NOT_HAS_ISLAND);
                            return;
                        }
                        $members = [];

                        if (!empty(SkyBlock::getMember(SkyBlock::getIslandName($sender->getName())))){
                            $me = explode(", ", SkyBlock::getMember(SkyBlock::getIslandName($sender->getName())));
                            foreach ($me as $member){
                                if ($member instanceof Player){
                                    $members[] = "§a$member";
                                }else{
                                    $members[] = "§c$member";
                                }
                            }
                        }

                        $officer = null;

                        if (!empty(SkyBlock::getOfficer(SkyBlock::getIslandName($sender->getName())))){
                            $offi = Server::getInstance()->getPlayer(SkyBlock::getOfficer(SkyBlock::getIslandName($sender->getName())));
                            if ($offi instanceof Player){
                                $officer = "§a" . $offi->getName();
                            }else{
                                $offi = SkyBlock::getOfficer(SkyBlock::getIslandName($sender->getName()));
                                $officer = "§c$offi";
                            }
                        }

                        $leader = null;

                        if (!empty(SkyBlock::getLeader(SkyBlock::getIslandName($sender->getName())))){
                            $lead = Server::getInstance()->getPlayer(SkyBlock::getLeader(SkyBlock::getIslandName($sender->getName())));
                            if ($lead instanceof Player){
                                $leader = "§a" . $lead->getName();
                            }else{
                                $lead = SkyBlock::getLeader(SkyBlock::getIslandName($sender->getName()));
                                $leader = "§c$lead";
                            }
                        }

                        $locked = null;

                        if (SkyBlock::isLocked(SkyBlock::getIslandName($sender->getName())) > 0){
                            $locked = "Oui";
                        }else{
                            $locked = "Non";
                        }

                        $sender->sendMessage(
                            "§7- §dSkyBlock §f~ §7Moon§elight §7-\n\n" .
                            "§fVoici les informations concernant votre île.\n\n" .
                            "§6Nom de l'île: §e" . SkyBlock::getIslandName($sender->getName()) . "\n" .
                            "§6Chef: §e" . ($leader) . "\n" .
                            "§6Officier: §e" . ($officer ? $officer : "Aucun") . "\n" .
                            "§6Membre(s): §e" . ($members ? implode(", ", $members) : "Aucun") . "\n" .
                            "§6Point(s): §e" . SkyBlock::getPoint(SkyBlock::getIslandName($sender->getName())) . "\n" .
                            "§6Est verrouillé: §e" . $locked . "\n\n" .
                            "§7- §dSkyBlock §f~ §7Moon§elight §7-"
                        );
                    }elseif (SkyBlock::isIsland($args[1])){
                        $members = [];

                        if (!empty(SkyBlock::getMember($args[1]))){
                            foreach (SkyBlock::getMember($args[1]) as $member){
                                if ($member instanceof Player){
                                    $members[] = "§a$member";
                                }else{
                                    $members[] = "§c$member";
                                }
                            }
                        }

                        $officer = null;

                        if (!empty(SkyBlock::getOfficer($args[1]))){
                            $offi = Server::getInstance()->getPlayer(SkyBlock::getOfficer($args[1]));
                            if ($offi instanceof Player){
                                $officer = "§a" . $offi->getName();
                            }else{
                                $offi = SkyBlock::getOfficer($args[1]);
                                $officer = "§c$offi";
                            }
                        }

                        $leader = null;

                        if (!empty(SkyBlock::getLeader($args[1]))){
                            $lead = Server::getInstance()->getPlayer(SkyBlock::getLeader($args[1]));
                            if ($lead instanceof Player){
                                $leader = "§a" . $lead->getName();
                            }else{
                                $lead = SkyBlock::getLeader($args[1]);
                                $leader = "§c$lead";
                            }
                        }

                        $locked = null;

                        if (SkyBlock::isLocked($args[1]) > 0){
                            $locked = "Oui";
                        }else{
                            $locked = "Non";
                        }

                        $sender->sendMessage(
                            "§7- §dSkyBlock §f~ §7Moon§elight §7-\n\n" .
                            "§fVoici les informations concernant l'île §e" . $args[1] . "§f.\n\n" .
                            "§6Nom de l'île: §e" . $args[1] . "\n" .
                            "§6Chef: §e" . ($leader) . "\n" .
                            "§6Officier: §e" . ($officer ? $officer : "Aucun") . "\n" .
                            "§6Membre(s): §e" . ($members ? $members : "Aucun") . "\n" .
                            "§6Point(s): §e" . SkyBlock::getPoint($args[1]) . "\n" .
                            "§6Est verrouillé: §e" . $locked . "\n\n" .
                            "§7- §dSkyBlock §f~ §7Moon§elight §7-"
                        );
                    }else{
                        $sender->sendMessage(MN::PREFIX_ALERT . $args[1] . " n'est pas une île.");
                        return;
                    }
                    break;
                case "help":
                    $sender->sendMessage(
                        "§7- §dSkyBlock §f~ §7Moon§elight §f~ §ePanel d'aide §7-\n\n" .
                        "§e» §6/is create: §fPermet de créer son île.\n" .
                        "§e» §6/is leave: §fPermet de quitter son île.\n" .
                        "§e» §6/is delete: §fPermet de supprimer son île. §c§lAUCUN REMBOURSEMENT!§r\n" .
                        "§e» §6/is join: §fPermet de se téléporter à son île.\n" .
                        "§e» §6/is invite: §fPermet d'inviter un joueur à devenir membre sur son île.\n" .
                        "§e» §6/is accept: §fPermet d'accepter une invitation.\n" .
                        "§e» §6/is deny: §fPermet de refuser une invitation.\n" .
                        "§e» §6/is chat: §fPermet de parler uniquement à son île ou l'inverse.\n" .
                        "§e» §6/is top: §fPermet de voir le top 10 des îles ayant le plus de point.\n" .
                        "§e» §6/is infos: §fPermet de voir les informations d'une île ou de la sienne.\n" .
                        "§e» §6/is help: §fPermet de voir la liste des sous-commandes disponibles avec /is.\n" .
                        "§e» §6/is chunk: §fPermet de voir la délimitation d'un chunk.\n" .
                        "§e» §6/is settings: §fPermet de gérer son île.\n" .
                        "§e» §6/is setspawn: §fPermet de définir le spawn de son île.\n" .
                        "§e» §6/is visit: §fPermet de visiter l'île d'un joueur.\n\n" .
                        "§c§lINFORMATIONS SUR COMMENT GAGNER DES POINTS D'ÎLE§r\n\n" .
                        "§e» §6Charbon, redstone, lapis: §f+1 §e«\n" .
                        "§e» §6Fer: §f+2 §e«\n" .
                        "§e» §6Diamant: §f+3 §e«\n" .
                        "§e» §6Emeraude: §f+4 §e«"
                    );
                    break;
                case "visite":
                case "visit":
                    if (!isset($args[1])){
                        $sender->sendMessage(MN::PREFIX_ALERT . "Veuillez faire /is visit [joueur | nom d'île].");
                        return;
                    }
                    if (SkyBlock::isIsland($args[1])){
                        if (SkyBlock::isLocked($args[1])){
                            $sender->sendMessage(MN::PREFIX_ALERT . "L'île que vous essayez de rejoindre est verrouillé.");
                            return;
                        }else{
                            if (Server::getInstance()->isLevelLoaded($args[1])) {
                                $sender->teleport(SkyBlock::getSpawn($args[1]));
                                $sender->sendMessage(MN::PREFIX_INFOS . "Vous visitez l'île §e" . $args[1] . "§f.");
                                return;
                            }else{
                                Server::getInstance()->loadLevel($args[1]);
                                $sender->teleport(SkyBlock::getSpawn($args[1]));
                                $sender->sendMessage(MN::PREFIX_INFOS . "Vous visitez l'île §e" . $args[1] . "§f.");
                                return;
                            }
                        }
                    }else{
                        $target = Server::getInstance()->getPlayer($args[1]);
                        if ($target instanceof Player){
                            if (SkyBlock::hasIsland($target->getName())){
                                if (SkyBlock::isLocked(SkyBlock::getIslandName($target->getName()))){
                                    $sender->sendMessage(MN::PREFIX_ALERT . "L'île que vous essayez de rejoindre est verrouillé.");
                                    return;
                                }else{
                                    $sender->teleport(SkyBlock::getSpawn(SkyBlock::getIslandName($target->getName())));
                                    $sender->sendMessage(MN::PREFIX_INFOS . "Vous visitez l'île §e" . $args[1] . "§f.");
                                    return;
                                }
                            }else{
                                $sender->sendMessage(MN::PREFIX_ALERT . "Ce joueur ne possède pas d'île.");
                                return;
                            }
                        }else{
                            $sender->sendMessage(MN::PREFIX_ALERT . "L'argument 1 doit-être un nom d'île ou un joueur.");
                            return;
                        }
                    }
                    break;
                case "chunk":
                    if (isset(Main::getInstance()->chunk[$sender->getName()])){
                        $sender->sendMessage(MN::PREFIX_INFOS . "Vous ne verrez plus la délimitation des chunks.");
                        unset(Main::getInstance()->chunk[$sender->getName()]);
                        return;
                    }else{
                        $sender->sendMessage(MN::PREFIX_INFOS . "Vous verrez désormais la délimitation des chunks.");
                        Main::getInstance()->chunk[$sender->getName()] = $sender->getName();
                        return;
                    }
                    break;
            }
        }
    }


    public static function openSettingsMenu(Player $player){
        $ui = new SimpleForm(function (Player $player, $data){
            if ($data === null){
                return;
            }
            switch ($data){
                case 0:
                    Island::openLockedMenu($player);
                    break;
                case 1:
                    Island::openDamageMenu($player);
                    break;
                case 2:
                    Island::sendTimeMenu($player);
                    break;
            }
        });
        $ui->setTitle("§l§dSkyBlock §f- §7Moon§elight");
        $ui->setContent("§7Bob: §fVoici tout les paramètres disponibles pour votre île.");
        $ui->addButton("Verrouillé/Déverrouillé");
        $ui->addButton("Damage");
        $ui->addButton("Temps");
        $ui->sendToPlayer($player);
    }

    public static function openLockedMenu(Player $player){
        $ui = new SimpleForm(function (Player $player, $data){
            if ($data === null){
                return;
            }
            switch ($data){
                case 0:
                    if (SkyBlock::isLocked(SkyBlock::getIslandName($player->getName()))){
                        $player->sendMessage(MN::PREFIX_ALERT . "Votre île est déjà verrouillé.");
                        return;
                    }else{
                        SkyBlock::setLocked(SkyBlock::getIslandName($player->getName()), true);
                        $player->sendMessage(MN::PREFIX_INFOS . "Votre île vient d'être verrouillé avec succès.");
                        return;
                    }
                    break;
                case 1:
                    if (!SkyBlock::isLocked(SkyBlock::getIslandName($player->getName()))){
                        $player->sendMessage(MN::PREFIX_ALERT . "Votre île est déjà déverrouillé.");
                        return;
                    }else{
                        SkyBlock::setLocked(SkyBlock::getIslandName($player->getName()), false);
                        $player->sendMessage(MN::PREFIX_INFOS . "Votre île vient d'être déverrouillé avec succès.");
                        return;
                    }
                    break;
            }
        });
        $ui->setTitle("§l§dSkyBlock §f- §7Moon§elight");
        $ui->setContent("§7Bob: §fAlors, Que veux-tu faire ?");
        $ui->addButton("Verrouillé");
        $ui->addButton("Déverrouillé");
        $ui->sendToPlayer($player);
    }

    public static function openDamageMenu(Player $player){
        $ui = new SimpleForm(function (Player $player, $data){
            if ($data === null){
                return;
            }
            switch ($data){
                case 0:
                    if (!SkyBlock::isDamage(SkyBlock::getIslandName($player->getName()))){
                        $player->sendMessage(MN::PREFIX_ALERT . "Les damages sont déjà activé.");
                        return;
                    }else{
                        SkyBlock::setDamage(SkyBlock::getIslandName($player->getName()), false);
                        $player->sendMessage(MN::PREFIX_INFOS . "Les damages sont désormais activer.");
                        return;
                    }
                    break;
                case 1:
                    if (SkyBlock::isDamage(SkyBlock::getIslandName($player->getName()))){
                        $player->sendMessage(MN::PREFIX_ALERT . "Les damages sont déjà désactiver.");
                        return;
                    }else{
                        SkyBlock::setDamage(SkyBlock::getIslandName($player->getName()), true);
                        $player->sendMessage(MN::PREFIX_INFOS . "Les damages sont désormais désactiver.");
                        return;
                    }
                    break;
            }
        });
        $ui->setTitle("§l§dSkyBlock §f- §7Moon§elight");
        $ui->setContent("§7Bob: §fAlors, Que veux-tu faire ?");
        $ui->addButton("Activer");
        $ui->addButton("Désactiver");
        $ui->sendToPlayer($player);
    }

    public static function sendTimeMenu(Player $player){
        $ui = new SimpleForm(function (Player $player, $data){
            if ($data === null){
                return;
            }
            switch ($data){
                case 0:
                    $level = Server::getInstance()->getLevelByName(SkyBlock::getIslandName($player->getName()));
                    $level->setTime(1000);
                    $player->sendMessage(Manager::PREFIX_INFOS . "Le temps a bien été mis au matin.");
                    break;
                case 1:
                    $level = Server::getInstance()->getLevelByName(SkyBlock::getIslandName($player->getName()));
                    $level->setTime(6000);
                    $player->sendMessage(Manager::PREFIX_INFOS . "Le temps a bien été mis à midi.");
                    break;
                case 2:
                    $level = Server::getInstance()->getLevelByName(SkyBlock::getIslandName($player->getName()));
                    $level->setTime(8000);
                    $player->sendMessage(Manager::PREFIX_INFOS . "Le temps a bien été mis en après-midi.");
                    break;
                case 3:
                    $level = Server::getInstance()->getLevelByName(SkyBlock::getIslandName($player->getName()));
                    $level->setTime(14000);
                    $player->sendMessage(Manager::PREFIX_INFOS . "Le temps a bien été mis en nuit.");
                    break;
                case 4:
                    $level = Server::getInstance()->getLevelByName(SkyBlock::getIslandName($player->getName()));
                    $level->setTime(18000);
                    $player->sendMessage(Manager::PREFIX_INFOS . "Le temps a bien été mis à minuit.");
                    break;
                case 5:
                    $level = Server::getInstance()->getLevelByName(SkyBlock::getIslandName($player->getName()));
                    $level->stopTime();
                    $player->sendMessage(Manager::PREFIX_INFOS . "Le temps a bien été stopper.");
                    break;
                case 6:
                    $level = Server::getInstance()->getLevelByName(SkyBlock::getIslandName($player->getName()));
                    $level->startTime();
                    $player->sendMessage(Manager::PREFIX_INFOS . "le temps a bien été repris.");
                    break;
            }
        });
        $ui->setTitle("§l§7Moon§elight §f- §dSkyBlock");
        $ui->setContent("§7Bob: §fQuel temps souhaites-tu mettre ?");
        $ui->addButton("Matin");
        $ui->addButton("Midi");
        $ui->addButton("Après-midi");
        $ui->addButton("Nuit");
        $ui->addButton("Minuit");
        $ui->addButton("Stopper le temps");
        $ui->addButton("Remettre le temps");
        $ui->sendToPlayer($player);
    }

}