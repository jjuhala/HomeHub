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

    
	// Let's see if this was POST request (adding new commands)
	if ($_SERVER['REQUEST_METHOD'] === 'POST') {

		// Let's add a new item if POST data for it is available
		if (isset(
			$_POST['name'],
			$_POST['cmd_name'],
			$_POST['notes']
		)) {
			// All needed vars received, now validate them!


			// -- Make sure lenghts match table's maximums

			// Array of received data and their max lengths
			$data_len_validate = array 
			(
				'name' => 100,
				'cmd_name' => 100,
				'showOnUI' => 1,
				'notes' => 300
			);


			// Convert checkbox yes/no to tinyint(1)
			$_POST['showOnUI'] = (isset($_POST['showOnUI']) && $_POST['showOnUI'] == 'on' ? '1' : '0'); 


			// Validate length
			foreach($data_len_validate as $vdata => $maxlen) {
				if (strlen($_POST[$vdata]) > $maxlen) {
					$this->addError('Error adding new Sensor!',
							"$vdata is too long! Max length ".
							"for $vdata is $maxlen characters");
				}
			}

			// List of items which can't contain spaces
			$data_space_validate = array
			(
				'cmd_name',
				'showOnUI'
			);

			// Validate spaceless items
			foreach($data_space_validate as $vdata) {
				if (preg_match('/\s/',$_POST[$vdata])) {
					$this->addError('Error adding new Sensor!',
							"$vdata has spaces. $vdata can't have spaces in it!");
				}
			}

			// That's it, if no errors so far, add a new sensor
			if ($this->showError === false) {
				$this->query(
					'INSERT INTO hh_sensors '.
					'(sensorID,name,cmd_name,currentVal,lastUpdate,showOnUI,notes) '.
					'VALUES ('.
						'NULL,'.
						'\''.$_POST['name'].'\','. 
						'\''.$_POST['cmd_name'].'\','. 
						'NULL,'. 
						'NULL,'. 
						'\''.$_POST['showOnUI'].'\','. 
						'\''.$_POST['notes'].'\');'
					);
				$this->addNotice('Success','New Sensor added successfully.');
			}

		} else { // Else of: isset (post vars)

			// Not all needed POST variables for adding new items received
			// Let's see if we want to delete something
			if (
					isset(
						$_POST['action'],
						$_POST['id']
						) 
					&& 
					$_POST['action'] == "delete"
				) {
				// action is 'delete' and id set, this is a delete request

				// Validate input
				if (ctype_digit($_POST['id'])) {
					// Input ok, delete wanted items
					$this->query('DELETE FROM hh_sensors WHERE sensorID='.$_POST['id']);
					$this->addNotice('Success','Sensor deleted successfully.');
				} else {
					// Delete sensor reveived, but the id isn't valid
					$this->addError('Error deleting Sensor!',
							'No valid id given');
				}
			} else {
				// POST request received but it isn't delete request
				// and doesn't contain all info for adding new
				$this->addError('Error adding new Sensor!',
								'POST request made, but not all POST variables set'.
								' for adding new sensor. No new sensors were added.');
			}






			
		}
	} // req method = post


	// Sensors and rules
	$this->sensors_list = $this->query("SELECT * FROM hh_sensors");

?>