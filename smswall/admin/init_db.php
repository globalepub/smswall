<?php
require('../conf.inc.php');

/**
 * Création de la BDD MySQL
 */

try {
    $con = new PDO("mysql:host=$host", $root, $root_password);

    $user_exist = $con->prepare("SELECT * FROM mysql.user WHERE User = :user;");
    $user_exist->bindParam(':user', $user);
    $user_exist->execute();

    if($user_exist->rowCount() > 0){
        $con->exec("UPDATE mysql.user SET password=PASSWORD('$pass') WHERE User='$user';");
    }else{
        $con->exec("CREATE USER '$user'@'$host' IDENTIFIED BY '$pass';");
    }

    $con->exec("CREATE DATABASE IF NOT EXISTS `$db` DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci;
            GRANT ALL ON `$db`.* TO '$user'@'$host';
            FLUSH PRIVILEGES;")
    or die(print_r($con->errorInfo(), true));

    echo "BDD: ok";

} catch (PDOException $e) {
    die("DB ERROR: ". $e->getMessage());
}

?>