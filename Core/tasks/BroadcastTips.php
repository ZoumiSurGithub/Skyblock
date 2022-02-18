<?php

namespace Zoumi\Core\tasks;

use pocketmine\scheduler\Task;
use pocketmine\Server;

class BroadcastTips extends Task {

    private static  $msg = [
        "§7Bob: §fVous souhaitez obtenir une récompense tout les jours §7gratuitement §f? Alors vote pour le serveur en faisant §7/vote§f.",
        "§7Bob: §fVous souhaitez obtenir des §7commandes §fou même des §7kits §fgratuitement sans dépenser le moindre argent IRL ? Alors rendez-vous au §7/f2w §f!",
        "§7Bob: §fVous souhaitez améliorer votre rang ? Alors rendez-vous au §7/rankup§f.",
        "§7Bob: §fVous souhaitez acheter un grade avec de l'argent IRL ? Alors rendez-vous sur §7https://moonlightmc-network.tebex.io §f!",
        "§7Bob: §fEnvie de rejoindre le staff ? Alors rejoins notre discord en faisant §7/discord §fet rendez-vous dans le salon §7recrutement §f!",
        "§7Bob: §fEnvie d'obtenir des récompenses tout en jouant ? Alors faites §7/job §fet mettez-vous au travail !",
        "§7Bob: §fVous souhaitez consulter toute les actualitées concernant le serveur §dSkyBlock §fou même le §7Network §f? Alors rejoins notre discord en faisant §7/discord §f!"
    ];
    private static $old = 0;

    public function onRun(int $currentTick)
    {
        Server::getInstance()->broadcastMessage(self::$msg[self::$old]);
        self::$old++;
        if(self::$old > count(self::$msg)-1)self::$old = 0;
    }

}