<?php
require('../conf.inc.php');

/**
 * Création des tables config_wall et items
 */

try {
    $con = new PDO("mysql:host=$host;port=$port;dbname=$db;charset=utf8", $user, $pass);
    // PHP < 5.3.6
    $con->exec("set names utf8");
    $con->exec("CREATE TABLE IF NOT EXISTS config_wall (
        id INT(11) NOT NULL AUTO_INCREMENT,
        channel_id VARCHAR(50),
        modo_type BOOL,
        hashtag TEXT,
        userstream BOOL,
        phone_number VARCHAR(16),
        theme VARCHAR(20),
        bulle INT DEFAULT 6,
        avatar BOOL,
        retweet BOOL,
        ctime timestamp,
        mtime timestamp,
        PRIMARY KEY (id),
        UNIQUE KEY id (id)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;

    CREATE TABLE IF NOT EXISTS messages (
        id INT(11) NOT NULL AUTO_INCREMENT,
        provider VARCHAR(10) NOT NULL,
        ref_id VARCHAR(20),
        author VARCHAR(25),
        message TEXT,
        message_html TEXT,
        avatar VARCHAR(200),
        links TEXT,
        medias TEXT,
        ctime datetime,
        visible BOOL,
        PRIMARY KEY (id),
        UNIQUE KEY id (id)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;");

    echo "Tables: OK<br/>";

} catch(PDOException $e) {
    die("DB ERROR TABLES: ". $e->getMessage());
}

/**
 * Création de la config par défaut
 */

try{
    $con = new PDO("mysql:host=$host;port=$port;dbname=$db;charset=utf8", $user, $pass;
    // PHP < 5.3.6
    $con->exec("set names utf8");
    $con->beginTransaction();
    $qry = $con->prepare('INSERT INTO config_wall (channel_id, modo_type, hashtag, userstream, phone_number, theme, bulle, avatar, retweet, ctime, mtime) VALUES(?,?,?,?,?,?,?,?,?,CURRENT_TIMESTAMP,CURRENT_TIMESTAMP)');
    $qry->execute(array(uniqid(), 1, '#rennes', 0, '0606060606', 'default', 6, 1, 1 ));
    $con->commit();

    echo "Configuration: OK";

}catch(PDOException $e){
    die("DB ERROR CONFIG insert: ". $e->getMessage());
}

?>
