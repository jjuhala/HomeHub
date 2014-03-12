<?php
    /*
     *      Copyright (C) 2014 Janne Juhala 
     *      http://jjj.fi/
     *      janne.juhala@outlook.com
     *  	https://github.com/jjuhala/HomeHub
     *
     *  This project is licensed under the terms of the MIT license.
     *
     */
	require('inc/ui.class.php');
	$ui = new UI();
	$ui->settings_file = (file_exists('_addn_settings.php') ? '_addn_settings.php' : '_settings.php');
	$ui->conf = include($ui->settings_file);
	$ui->pdo = include('inc/func/_connect-mysql.php');
	$ui->validate_and_render($_GET);
?>