<?php

namespace Zoumi\Core;

class DataBase {

    public static function getData(): \mysqli{
        return new \mysqli("127.0.0.1", "moon", "moonlightnetwork123", "test", 3306);
    }

    public static function setupTable(): void{
        DataBase::getData()->query("CREATE TABLE IF NOT EXISTS `users` ( `pseudo` VARCHAR(55) NOT NULL , `coins` INT NOT NULL , `pb` INT NOT NULL , `island` VARCHAR(55) NOT NULL , `prefix` TEXT NOT NULL , PRIMARY KEY (`pseudo`)) ENGINE = InnoDB;");
        DataBase::getData()->query("CREATE TABLE IF NOT EXISTS `jobs` ( `pseudo` VARCHAR(55) NOT NULL , `farmer_lvl` INT NOT NULL , `farmer_xp` FLOAT NOT NULL , `miner_lvl` INT NOT NULL , `miner_xp` FLOAT NOT NULL , `chasseur_lvl` INT NOT NULL , `chasseur_xp` FLOAT NOT NULL , `bucheron_lvl` INT NOT NULL , `bucheron_xp` FLOAT NOT NULL , PRIMARY KEY (`pseudo`)) ENGINE = InnoDB;");
        DataBase::getData()->query("CREATE TABLE IF NOT EXISTS `islands` ( `island` VARCHAR(55) NOT NULL , `leader` TEXT NOT NULL , `officer` TEXT NOT NULL , `member` TEXT NOT NULL , `points` INT NOT NULL , `locked` TEXT NOT NULL , `vote` INT NOT NULL , PRIMARY KEY (`island`)) ENGINE = InnoDB;");
        DataBase::getData()->query("CREATE TABLE IF NOT EXISTS `settings` ( `island` INT NOT NULL , `locked` BOOLEAN NOT NULL , `damage` BOOLEAN NOT NULL , PRIMARY KEY (`island`)) ENGINE = InnoDB;");
    }

}