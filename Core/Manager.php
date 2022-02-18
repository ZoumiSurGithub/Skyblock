<?php

namespace Zoumi\Core;

interface Manager {

    public const PREFIX_ALERT = "§f(§7Moon§cAlert§f) §c";

    public const PREFIX = "§f(§7Moon§elight§f) ";

    public const PREFIX_INFOS = "§f(§7Moon§aInfos§f) ";

    public const NOT_PERM = "§f(§7Moon§cAlert§f) §cVous ne possédez pas la permission pour faire ceci.";

    public const PLAYER_NOT_EXIST_IN_DATA = "§f(§7Moon§cAlert§f) §cCe joueur n'est pas enregistré dans la DataBase.";

    public const HAS_ISLAND = "§f(§7Moon§cAlert§f) §cVous possédez déjà une île.";

    public const NOT_HAS_ISLAND = "§f(§7Moon§cAlert§f) §cVous devez possédé une île pour faire ceci.";

    public const NOT_IN_DATA = "§f(§7Moon§cAlert§f) §cCe joueur n'existe pas dans la DataBase.";

    public const SANCTION = "§f(§7Moon§cSanction§f) ";

    public const BANNED_NAMES = array(
        "nitro",
        "nitrofaction",
        "cultmc",
        "cult",
        "bite",
        "bitte",
        "chatte",
        "pute",
        "salope",
        "symp",
        "symphonia",
        "histeria",
        "histemerde",
        "pluto",
        "plutonium",
        "suce",
        "penis"
    );

}