<?php
	require('inc/ui.class.php');
	$ui = new UI();
	$ui->conf = include('_settings.php');
	$ui->validate_and_render($_GET);
?>