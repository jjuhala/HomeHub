<?php
	require('inc/ui.class.php');
	$ui = new UI();
	$ui->conf = include('_settings.php');
	$ui->pdo = include('inc/func/_connect-mysql.php');
	$ui->validate_and_render($_GET);
?>