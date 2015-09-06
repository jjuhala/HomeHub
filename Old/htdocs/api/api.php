<?php
	header('Content-Type: application/json');
	require('brains.class.php');
	$brains = new Brains();
	// Get additional settings file if it exists
	$brains->settings_file = (file_exists('../_addn_settings.php') ? '../_addn_settings.php' : '../_settings.php');
	// Read config
	$brains->conf = include($brains->settings_file);
	// Poke brains
	$brains->Run($_GET);

	// Brainfart
	if (!$brains->ResultPrinted === true) {
		// Result should always get printed when Run()-method is called
		// and thus this should never happen.
		$brains->AddMessage('Server error');
		echo json_encode($brains->result);
	}

	
?>