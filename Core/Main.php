<?php

namespace Zoumi\Core;

use pocketmine\block\BlockFactory;
use pocketmine\entity\Entity;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\server\DataPacketReceiveEvent;
use pocketmine\inventory\ShapedRecipe;
use pocketmine\item\Item;
use pocketmine\item\ItemIds;
use pocketmine\nbt\JsonNbtParser;
use pocketmine\nbt\tag\ByteArrayTag;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\NamedTag;
use pocketmine\nbt\tag\StringTag;
use pocketmine\network\mcpe\protocol\BatchPacket;
use pocketmine\network\mcpe\protocol\CraftingDataPacket;
use pocketmine\network\mcpe\protocol\ItemFrameDropItemPacket;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\Server;
use pocketmine\tile\Tile;
use pocketmine\utils\BinaryStream;
use pocketmine\utils\Config;
use Zoumi\Core\api\Coins;
use Zoumi\Core\blocks\FarmingChest;
use Zoumi\Core\blocks\GoldenOre;
use Zoumi\Core\blocks\IronOre;
use Zoumi\Core\blocks\Lava;
use Zoumi\Core\blocks\ShulkerBox;
use Zoumi\Core\blocks\TallGrass;
use Zoumi\Core\commands\all\BottleXp;
use Zoumi\Core\commands\all\coins\AddCoins;
use Zoumi\Core\commands\all\coins\RemoveCoins;
use Zoumi\Core\commands\all\coins\SetCoins;
use Zoumi\Core\commands\all\coins\TakesCoins;
use Zoumi\Core\commands\all\coins\TopCoins;
use Zoumi\Core\commands\all\Events;
use Zoumi\Core\commands\all\FarmToWin;
use Zoumi\Core\commands\all\Job;
use Zoumi\Core\commands\all\Kit;
use Zoumi\Core\commands\all\Ping;
use Zoumi\Core\commands\all\Players;
use Zoumi\Core\commands\all\Rankup;
use Zoumi\Core\commands\all\remake\Liste;
use Zoumi\Core\commands\all\Shop;
use Zoumi\Core\commands\all\Spawn;
use Zoumi\Core\commands\all\Transfer;
use Zoumi\Core\commands\all\Vote;
use Zoumi\Core\commands\all\Warp;
use Zoumi\Core\commands\all\XYZ;
use Zoumi\Core\commands\Island;
use Zoumi\Core\commands\ranked\Annonce;
use Zoumi\Core\commands\ranked\Enderchest;
use Zoumi\Core\commands\ranked\Fly;
use Zoumi\Core\commands\ranked\Furnace;
use Zoumi\Core\commands\ranked\Repair;
use Zoumi\Core\commands\staff\Box;
use Zoumi\Core\commands\staff\EntitySpawn;
use Zoumi\Core\commands\staff\gamemode\GMA;
use Zoumi\Core\commands\staff\gamemode\GMC;
use Zoumi\Core\commands\staff\gamemode\GMS;
use Zoumi\Core\commands\staff\gamemode\GMSP;
use Zoumi\Core\commands\staff\pb\AddPB;
use Zoumi\Core\commands\staff\pb\RemovePB;
use Zoumi\Core\commands\staff\pb\SetPB;
use Zoumi\Core\commands\staff\SetBourse;
use Zoumi\Core\commands\staff\SpawnBoss;
use Zoumi\Core\entity\Hera;
use Zoumi\Core\entity\Purification;
use Zoumi\Core\inventory\FarmingChestInventory;
use Zoumi\Core\listeners\BlockListener;
use Zoumi\Core\listeners\EntityListener;
use Zoumi\Core\listeners\events\BoxEvent;
use Zoumi\Core\listeners\events\Fourche;
use Zoumi\Core\listeners\events\Protection;
use Zoumi\Core\listeners\PlayerListener;
use Zoumi\Core\tasks\Bourse;
use Zoumi\Core\tasks\BroadcastTips;
use Zoumi\Core\tasks\ChunkBorderTask;
use Zoumi\Core\tasks\ClearLaggTask;
use Zoumi\Core\tasks\events\Bingo;
use Zoumi\Core\tasks\Farmzone;
use Zoumi\Core\tasks\Farmzone2;
use Zoumi\Core\tasks\VoteParty;
use Zoumi\Core\tasks\XYZTask;
use Zoumi\Core\tiles\FarmingChestTile;

class Main extends PluginBase implements Listener {

    /** @var static $instance */
    public static $instance;
    /** @var Config $ec */
    public $ec;
    /** @var Config $fly */
    public $fly;
    /** @var Config $furnace */
    public $furnace;
    /** @var Config $repair */
    public $repair;
    /** @var Config $farmer */
    public $farmer;
    /** @var Config $decorateur */
    public $decorateur;
    /** @var Config $enchanteur*/
    public $enchanteur;
    /** @var Config $miner */
    public $miner;
    /** @var Config $cooldown */
    public $cooldown;
    /** @var array $scoreboard */
    public $scoreboard = [];
    /** @var Config $score */
    public $score;
    /** @var array $invite */
    public $invite = [];
    /** @var array $chat */
    public $chat = [];
    /** @var array $chunk */
    public $chunk = [];
    /** @var array $cache */
    public $cache = [];
    /** @var array $flight */
    public $flight = [];
    /** @var array $xyz */
    public $xyz = [];
    public $craftCache;

    /** BOURSE */
    /** @var Config $bourse */
    public $bourse;

    public static function getInstance(): self{
        return self::$instance;
    }

    /**
     * @throws \ReflectionException
     */
    public function onEnable()
    {
        self::$instance = $this;
        $this->setupFile();
        $this->setConfig();

        /*
        BlockFactory::registerBlock(new Lava(), true);
        */

        try {
            DataBase::setupTable();
            $this->getLogger()->info("§eConnection à MySQL effectué avec succès.");
        }catch (\mysqli_sql_exception $mysqli_sql_exception){
            $this->getLogger()->error("Connection à MySQL échoué.");
        }

        /** Monde */
        /*
        $this->getServer()->loadLevel("ffa");
        $this->getServer()->loadLevel("farmzone1");
        $this->getServer()->loadLevel("farmzone2");
        $this->getServer()->getLevelByName("spawn")->setTime(6000);
        $this->getServer()->getLevelByName("spawn")->stopTime();
        */

        /* Commands */
        $this->getServer()->getCommandMap()->unregister($this->getServer()->getCommandMap()->getCommand("transferserver"));
        $this->getServer()->getCommandMap()->unregister($this->getServer()->getCommandMap()->getCommand("list"));
        $this->getServer()->getCommandMap()->registerAll("Core", [
            /* All */
            new FarmToWin("farmtowin", "Permet d'afficher le menu farm2win.", "/f2w", ["f2w"]),
            new Kit("kit", "Permet de prendre votre kit.", "/kit", []),
            new BottleXp("bottlexp", "Permet de transformer ses niveaux d'xps en boutteille.", "/bottlexp", ["xpbottle"]),
            new Island("island", "Menu du SkyBlock.", "/island", ["sb", "skyblock", "is"]),
            new Job("job", "Permet d'afficher le menu des métiers.", "/job", []),
            new Rankup("rankup", "Permet de monter en rang.", "/rang", ["ru"]),
            new Shop("shop", "Permet d'afficher le menu du shop.", "/shop", []),
            new \Zoumi\Core\commands\all\Bourse("bourse", "Permet de voir la bourse actuelle.", "/bourse", []),
            new Warp("warp", "Permet d'afficher les warps disponibles.", "/warp", []),
            new Spawn("spawn", "Permet de se téléporter au spawn.", "/spawn", []),
            new Vote("vote", "Permet de récuperer sa récompense de vote.", "/vote", []),
            new Transfer("transfer", "Permet de se transferer vers un de nos serveurs.", "/transfer", ["transferserver"]),
            new Liste("list", "Permet de voir la liste des joueurs connectés sur le network.", "/list", []),
            new Players("players", "Permet de voir la liste des joueurs connectés sur le serveur.", "/players", []),
            new XYZ("xyz", "Permet de voir ses coordonnés.", "/xyz", []),
            new Ping("ping", "Permet de voir ses pings ou se d'un joueur.", "/ping", []),
            new Events("events", "Permet de voir la liste des événements disponibles.", "/events", []),

            /* Ranked */
            new Enderchest("enderchest", "Permet d'ouvrir votre coffre de l'ender.", "/ec", ["ec"]),
            new Repair("repair", "Permet de réparer vos items.", "/repair", []),
            new Annonce("annonce", "Permet de faire une annonce.", "/annonce", []),
            new Fly("fly", "Permet de volé dans son île.", "/fly", []),
            new Furnace("furnace", "Permet de cuîr vos items.", "/furnace", []),
            new Box("box", "Permet d'ajouter/retirer les keys d'un joueur.", "/box", []),

            /** Staff */
            new GMA("gma", "Permet de se mettre en gamemode aventure.", "/gma", ["gm2"]),
            new GMC("gmc", "Permet de se mettre en gamemode créatif.", "/gmc", ["gm1"]),
            new GMS("gms", "Permet de se mettre en gamemode survie.", "/gms", ["gm0"]),
            new GMSP("gmsp", "Permet de se mettre en gamemode spectateur.", "/gmsp", ["gm3"]),
            new EntitySpawn("entityspawn", "Permet de faire spawn une entitée.", "/entityspawn", []),

            /* Coins */
            new AddCoins("addcoins", "Permet d'ajouté des coins à un joueur.", "/addcoins", []),
            new \Zoumi\Core\commands\all\coins\Coins("coins", "Permet de voir vos coins actuel.","/coins", ["mycoins"]),
            new RemoveCoins("removecoins", "Permet de retiré des coins à un koueur.", "/removecoins", []),
            new SetCoins("setcoins", "Permet de définir les coins d'un joueur.", "/setcoins", []),
            new TakesCoins("takescoins", "Permet d'envoyé des coins à un joueur.", "/takescoins", ["pay"]),
            new TopCoins("topcoins", "Permet de voir le top 10 des joueurs ayant le plus de coins.", "/topcoins", []),

            /* PB */
            new SetPB("setpb", "Permet de définir les points boutique d'un joueur.", "/setpb", []),
            new AddPB("addpb", "Permet d'ajouter des points boutique à un joueur.", "/addpb", []),
            new RemovePB("removepb", "Permet de retirer des points boutique à un joueur.", "/removepb", [])

        ]);

        /* Listener */
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
        $this->getServer()->getPluginManager()->registerEvents(new Protection(), $this);
        $this->getServer()->getPluginManager()->registerEvents(new PlayerListener(), $this);
        $this->getServer()->getPluginManager()->registerEvents(new BoxEvent(), $this);
        $this->getServer()->getPluginManager()->registerEvents(new BlockListener(), $this);
        $this->getServer()->getPluginManager()->registerEvents(new EntityListener(), $this);
        $this->getServer()->getPluginManager()->registerEvents(new Fourche(), $this);
        $this->getServer()->getPluginManager()->registerEvents(new \Zoumi\Core\listeners\events\Job(), $this);

        /** Task */
        $this->getScheduler()->scheduleRepeatingTask(new ChunkBorderTask(), 15);
        $this->getScheduler()->scheduleRepeatingTask(new XYZTask(), 15);
        $this->getScheduler()->scheduleRepeatingTask(new ClearLaggTask(), 20);
        $this->getScheduler()->scheduleRepeatingTask(new BroadcastTips(), 20 * 60 * 15);
        $this->getScheduler()->scheduleRepeatingTask(new Bourse(), 20);
        $this->getScheduler()->scheduleRepeatingTask(new VoteParty(), 20);
        $this->getScheduler()->scheduleRepeatingTask(new Farmzone(), 20);
        $this->getScheduler()->scheduleRepeatingTask(new Farmzone2(), 20);
        $this->getScheduler()->scheduleRepeatingTask(new Bingo(), 20);

        /** Blocks */
        BlockFactory::registerBlock(new IronOre(), true);
        BlockFactory::registerBlock(new GoldenOre(), true);
        BlockFactory::registerBlock(new TallGrass(), true);
        BlockFactory::registerBlock(new ShulkerBox(), true);

        /** Entity */
        Entity::registerEntity(\Zoumi\Core\entity\Bourse::class, true);
        Entity::registerEntity(Hera::class, true);
        Entity::registerEntity(\Zoumi\Core\entity\TopCoins::class, true);
        Entity::registerEntity(Purification::class, true);

        $this->craftingDataCache();
    }

    public function setupFile(): void{
        @mkdir($this->getDataFolder() . "f2w");
        @mkdir($this->getDataFolder() . "f2w/commands");
        @mkdir($this->getDataFolder() . "f2w/kits");
        if (!file_exists($this->getDataFolder() . "f2w/commands/ec.json")){
            $this->saveResource("f2w/commands/ec.json");
        }
        if (!file_exists($this->getDataFolder() . "f2w/commands/fly.json")){
            $this->saveResource("f2w/commands/fly.json");
        }
        if (!file_exists($this->getDataFolder() . "f2w/commands/furnace.json")){
            $this->saveResource("f2w/commands/furnace.json");
        }
        if (!file_exists($this->getDataFolder() . "f2w/commands/repair.json")){
            $this->saveResource("f2w/commands/repair.json");
        }
        if (!file_exists($this->getDataFolder() . "f2w/kits/farmer.json")){
            $this->saveResource("f2w/kits/farmer.json");
        }
        if (!file_exists($this->getDataFolder() . "f2w/kits/decorateur.json")){
            $this->saveResource("f2w/kits/decorateur.json");
        }
        if (!file_exists($this->getDataFolder() . "f2w/kits/enchanteur.json")){
            $this->saveResource("f2w/kits/enchanteur.json");
        }
        if (!file_exists($this->getDataFolder() . "f2w/kits/miner.json")){
            $this->saveResource("f2w/kits/miner.json");
        }
        @mkdir($this->getDataFolder() . "box");
        if (!file_exists($this->getDataFolder() . "box/spawner.json")){
            $this->saveResource("box/spawner.json");
        }
        if (!file_exists($this->getDataFolder() . "box/farming.json")){
            $this->saveResource("box/farming.json");
        }
        if (!file_exists($this->getDataFolder() . "box/boutique.json")){
            $this->saveResource("box/boutique.json");
        }
        if (!file_exists($this->getDataFolder() . "box/vote.json")){
            $this->saveResource("box/vote.json");
        }
        if (!file_exists($this->getDataFolder() . "cooldown.json")){
            $this->saveResource("cooldown.json");
        }
        if (!file_exists($this->getDataFolder() . "score.yml")){
            $this->saveResource("score.yml");
        }
        if (!file_exists($this->getDataFolder() . "bourse.json")){
            $this->saveResource("bourse.json");
        }
        if (!file_exists($this->getDataFolder() . "hera.png")){
            $this->saveResource("hera.png");
        }
        if (!file_exists($this->getDataFolder() . "hera-geo.json")){
            $this->saveResource("hera-geo.json");
        }
    }

    public function setConfig(): void{
        /* F2W */
        $this->ec = new Config($this->getDataFolder() . "f2w/commands/ec.json", Config::JSON);
        $this->fly = new Config($this->getDataFolder() . "f2w/commands/fly.json", Config::JSON);
        $this->furnace = new Config($this->getDataFolder() . "f2w/commands/furnace.json", Config::JSON);
        $this->repair = new Config($this->getDataFolder() . "f2w/commands/repair.json", Config::JSON);
        $this->farmer = new Config($this->getDataFolder() . "f2w/kits/farmer.json", Config::JSON);
        $this->decorateur = new Config($this->getDataFolder() . "f2w/kits/farmer.json", Config::JSON);
        $this->miner = new Config($this->getDataFolder() . "f2w/kits/farmer.json", Config::JSON);
        $this->enchanteur = new Config($this->getDataFolder() . "f2w/kits/farmer.json", Config::JSON);

        /* Basic */
        $this->cooldown = new Config($this->getDataFolder() . "cooldown.json", Config::JSON);
        $this->score = new Config($this->getDataFolder() . "score.yml", Config::YAML);
        $this->bourse = new Config($this->getDataFolder() . "bourse.json", Config::JSON);
    }

    public function convert($time){
        if($time >= 60){
            $m = $time / 60;
            $mins = floor($m);
            $s = $m - $mins;
            $secs = floor($s * 60);
            if($mins >= 60){
                $h = $mins / 60;
                $hrs = floor($h);
                $m = $h - $hrs;
                $mins = floor($m * 60);
                return $hrs . " §cheure(s), §e" . $mins . " §cminute(s) et §e" . $secs . " §cseconde(s)";
            } else {
                return $mins . " §cminute(s) et §e" . $secs . " §cseconde(s)";
            }
        } else {
            return $time . " §cseconde(s)";
        }
    }

    public function convertFor($time){
        if($time >= 60){
            $m = $time / 60;
            $mins = floor($m);
            $s = $m - $mins;
            $secs = floor($s * 60);
            if($mins >= 60){
                $h = $mins / 60;
                $hrs = floor($h);
                $m = $h - $hrs;
                $mins = floor($m * 60);
                return $hrs . "h:" . $mins . "m:" . $secs . "s";
            } else {
                return $mins . "m:" . $secs . "s";
            }
        } else {
            return $time . "s";
        }
    }

    public function PNGtoBYTES($path) : string
    {
        $img = @imagecreatefrompng($path);
        $bytes = "";
        $L = (int) @getimagesize($path)[0];
        $l = (int) @getimagesize($path)[1];
        for ($y = 0; $y < $l; $y++) {
            for ($x = 0; $x < $L; $x++) {
                $rgba = @imagecolorat($img, $x, $y);
                $a = ((~((int)($rgba >> 24))) << 1) & 0xff;
                $r = ($rgba >> 16) & 0xff;
                $g = ($rgba >> 8) & 0xff;
                $b = $rgba & 0xff;
                $bytes .= chr($r) . chr($g) . chr($b) . chr($a);
            }
        }
        @imagedestroy($img);
        return $bytes;
    }

    public function getSkinTag() : NamedTag
    {
        $skin = str_repeat("\x00", 8192);
        return new CompoundTag("Skin", [
            new StringTag("Name", "Standard_Custom"),
            new ByteArrayTag("Data", $skin),
        ]);
    }

    public static function getManagerConfig(): Config {
        return new Config("/home/ares/data/manager.json", Config::JSON);
    }
    
    /** Craft */
    public function hasItems(Item $item) : bool
    {
        foreach (["294:0", "371:0", "266:0"] as $data) {
            $id = Item::fromString($data);
            if ($item->getId() === $id->getId() and ($id->getDamage() == 0 or $item->getDamage() == $id->getDamage())) {
                return true;
            }
        }
        return false;
    }

    /**
     * @return void
     */
    public function craftingDataCache() : void {
        $datas = $this->getServer()->getCraftingManager();
        $pk = new CraftingDataPacket();

        foreach ($datas->getShapelessRecipes() as $list) {
            foreach ($list as $recipe) {
                $delete = false;
                foreach ($recipe->getResults() as $result) {
                    if ($result->getId() === ItemIds::GOLD_NUGGET or $result->getId() === ItemIds::GOLD_HOE or $result->getId() === ItemIds::GOLD_INGOT){
                        $this->getLogger()->info("§fL'item §e" . $result->getName() . "§r§f a été supprimer.");
                        $delete = true;
                    }
                }
                if (!$delete) $pk->addShapelessRecipe($recipe);
            }
        }

        foreach ($datas->getShapedRecipes() as $list) {
            foreach ($list as $recipe) {
                $delete = false;
                foreach ($recipe->getResults() as $result) {
                    if ($result->getId() === ItemIds::GOLD_NUGGET or $result->getId() === ItemIds::GOLD_HOE or $result->getId() === ItemIds::GOLD_INGOT){
                        $this->getLogger()->info("§fL'item §e" . $result->getName() . "§r§f a été supprimer.");
                        $delete = true;
                    }
                }
                if (!$delete) $pk->addShapedRecipe($recipe);
            }
        }

        $fourche = new ShapedRecipe(
            [
                " AA",
                " BA",
                " B "
            ],
            ["A" => Item::get(Item::GOLD_BLOCK), "B" => Item::get(Item::STICK)],
            [Item::get(Item::GOLD_HOE)]
        );
        $pk->addShapedRecipe($fourche);
        $this->getServer()->getCraftingManager()->registerShapedRecipe($fourche);
        $farming = new ShapedRecipe(
            [
                "AAA",
                "ABA",
                "AAA"
            ],
            ["A" => Item::get(Item::GOLD_INGOT), "B" => Item::get(Item::CHEST)],
            [Item::get(Item::TRAPPED_CHEST)]
        );
        $pk->addShapedRecipe($farming);
        $this->getServer()->getCraftingManager()->registerShapedRecipe($farming);

        foreach ($datas->getFurnaceRecipes() as $recipe) {
            $pk->addFurnaceRecipe($recipe);
        }

        $pk->encode();
        $batch = new BatchPacket();
        $batch->addPacket($pk);
        $batch->setCompressionLevel(Server::getInstance()->networkCompressionLevel);
        $batch->encode();
        $this->craftCache = $batch;
    }

    /**
     * @param array $item
     * @return Item
     */
    public function getItem(array $item) : Item {
        if (is_string($item[0])) {
            $data = Item::fromString($item[0]);
            $result = Item::get($data->getId(),$data->getDamage(),1);
        } else {
            $result = Item::get($item[0],0,1);
        }
        if (isset($item[1])) {
            $result->setCount($item[1]);
        }
        if (isset($item[2])) {
            $tags = $exception = null;
            $data = $item[2];
            try {
                $tags = JsonNbtParser::parseJson($data);
            } catch (\Throwable $ex){
                $exception = $ex;
            }
            if (!($tags instanceof CompoundTag) or $exception !== null) {
                return $result;
            }
            $result->setNamedTag($tags);
        }
        return $result;
    }

    /* CRATE TOO MANY PACKETS
    public function onDataPacketSend(DataPacketSendEvent $event) : void {
        $packet = $event->getPacket();

        if ($packet instanceof BatchPacket) {
            $packet->offset = 1;
            $packet->decode();
            foreach ($packet->getPackets() as $buf) {
                $pk = PacketPool::getPacketById(ord($buf{0}));
                $player = $event->getPlayer();

                if ($pk instanceof CraftingDataPacket) {
                    if ($packet->payload == $this->getServer()->getCraftingManager()->getCraftingDataPacket()->payload) {
                        $event->setCancelled();
                        $player->sendDataPacket($this->cache);
                    }
                }
            }
        }
    }*/
    

}