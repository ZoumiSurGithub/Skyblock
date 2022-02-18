<?php

namespace Zoumi\Core\api;

use DiscordWebhookAPI\Embed;
use DiscordWebhookAPI\Message;
use pocketmine\Player;
use pocketmine\Server;

class Webhook {

    public static function sendMessage(Player $player, string $message){
        $webhook = new \DiscordWebhookAPI\Webhook("https://discord.com/api/webhooks/839471383963893761/mR62NWTDwiEHx-ezThZ-usldybcP61ek3d3NjKGnWApwVQ3xNTLFjHIfBIlYao9XUQuw");
        $msg = new Message();
        $message = str_replace(["§1", "§2", "§3", "§4", "§5", "§6", "§7", "§8", "§9", "§0", "§l", "§r"], "", $message);
        $rank = Server::getInstance()->getPluginManager()->getPlugin("PurePerms")->getUserDataMgr()->getGroup($player);
        $msg->setContent(str_replace(["§1", "§2", "§3", "§4", "§5", "§6", "§7", "§8", "§9", "§0", "§l", "§r", "§b", "§a", "§e", "§o"], "", Users::getPrefix($player->getName()) . "[" . ($rank ?? "") . "] " . $player->getDisplayName() . " >> " . $message));
        $webhook->send($msg);
    }

    public static function sendJoinQuit(Player $player, string $type){
        $webhook = new \DiscordWebhookAPI\Webhook("https://discord.com/api/webhooks/839471383963893761/mR62NWTDwiEHx-ezThZ-usldybcP61ek3d3NjKGnWApwVQ3xNTLFjHIfBIlYao9XUQuw");
        $msg = new Message();
        if ($type === "join") {
            $msg->setContent("[+] " . $player->getName());
        }elseif ($type === "quit"){
            $msg->setContent("[-] " . $player->getName());
        }
        $webhook->send($msg);
    }

    public static function sendSanction(string $message, string $sanction){
        $webhook = new \DiscordWebhookAPI\Webhook("https://discord.com/api/webhooks/839471848806285322/YKOErNXpX7A2fmR66_hjVeNIwZhePZQ85v5Dq2s_uHXm8dC0kC1YH7ZKfaBsfF1k7etK");
        $msg = new Message();
        $embed = new Embed();
        $embed->setTitle("Serveur Héra ~ $sanction");
        $embed->setDescription($message);
        $msg->addEmbed($embed);
        $webhook->send($msg);
    }

    public static function sendCommand(string $message){
        $webhook = new \DiscordWebhookAPI\Webhook("https://discord.com/api/webhooks/839880484007575554/drornnsOHNiFc083ilocAi2tW05OHEfz9R5VRXNWUM6NID_sMO7Zpcz6d4ye4jzWbDQh");
        $msg = new Message();
        $msg->setContent($message);
        $webhook->send($msg);
    }

}