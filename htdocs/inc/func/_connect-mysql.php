<?php
try {
	$host = $ui->conf['mysql_ip'];
	$db = $ui->conf['mysql_database'];
	$user = $ui->conf['mysql_user'];
	$pw = $ui->conf['mysql_password'];
    $conn = new PDO(
    		"mysql:host=$host;". // HOST
    		"dbname=$db", // DB
    		$user, // USER
    		$pw,  // PASSWORD
            array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    return $conn;
} catch(PDOException $pdoer) {
    $ui->kill("Couldn't connect to MySQL.<br>PDO Exception: " . $pdoer->getMessage());
}

?>