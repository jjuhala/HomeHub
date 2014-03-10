<?php
	$this->actions_list = $this->query("SELECT * FROM hh_actions");
	$this->commands_list = $this->query("SELECT * FROM hh_commands");
	$this->sensors_list = $this->query("SELECT * FROM hh_sensors");
?>