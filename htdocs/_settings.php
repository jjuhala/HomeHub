<?php
// HomeHub configuraion file
return array(
	"notification_email" => "your@email.com",

	//Connection information
	"arduino_ip" => "0.0.0.0",
	"arduino_port" => 80,
	"arduino_secret" => "your_arduino_server_secret_here",
	"mysql_ip" => "127.0.0.1",
	"mysql_port" => 3306,
	"mysql_database" => "your_mysql_database_for_homehub",
	"mysql_user" => "your_mysql_user",
	"mysql_password" => "your_mysql_password",

	// Available pages
	"pages_whitelist" => array 	(
									"home",
									"manage-rules"
								),

	// Api
	"api_secret" => "any_secret_for_api",
);
?>