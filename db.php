<?php 


Class Create_db {
    protected $host = 'localhost';
    protected $user = 'root';
    protected $pass = '';

    function __construct() {
        $db = new PDO("mysql: host=host", user, pass);

        $db -> query("CREATE DATABASE social");

        $db -> query("CREATE DATABASE messages");

        $db -> query("CREATE TABLE `social`.`posts` ( `id` INT(11) NOT NULL AUTO_INCREMENT , `post` VARCHAR(1000) NOT NULL , `time` VARCHAR(30) NOT NULL , `posters_id` INT(11) NOT NULL , PRIMARY KEY (`id`)) ENGINE = InnoDB;");
            
        $db -> query("CREATE TABLE `social`.`accounts` ( `id` INT(11) NOT NULL AUTO_INCREMENT , `username` VARCHAR(30) NOT NULL , `email` VARCHAR(30) NOT NULL , `password` INT(200) NOT NULL , PRIMARY KEY (`id`)) ENGINE = InnoDB;");
    } 
}

$create = new Create_db();