<?php
	// Let's see if this was POST request (adding new actions)
	if ($_SERVER['REQUEST_METHOD'] === 'POST') {

		// Add new action if POST data for it is available
		if (isset(
			$_POST['name'],
			$_POST['commandList'],
			$_POST['triggers'],
			$_POST['notes']
		)) {
			// All needed vars received, now validate them!


			// -- Make sure lenghts match database

			// Array of received data and their max lengths
			$data_len_validate = array 
			(
				'name' => 100,
				'commandList' => 300,
				'triggers' => 300,
				'showOnUI' => 1,
				'notes' => 300
			);


			// Convert checkbox yes/no to tinyint(1)
			$_POST['showOnUI'] = (isset($_POST['showOnUI']) && $_POST['showOnUI'] == 'on' ? '1' : '0'); 


			// Validate length
			foreach($data_len_validate as $vdata => $maxlen) {
				if (strlen($_POST[$vdata]) > $maxlen) {
					$this->addError('Error adding new Action!',
							"$vdata is too long! Max length ".
							"for $vdata is $maxlen characters");
				}
			}

			// List of items which can't contain spaces
			$data_space_validate = array
			(
				'showOnUI'
			);


			// Validate spaceless items
			foreach($data_space_validate as $vdata) {
				if (preg_match('/\s/',$_POST[$vdata])) {
					$this->addError('Error adding new Action!',
							"$vdata has spaces. $vdata can't have spaces in it!");
				}
			}

			// That's it, if no errors so far, add new action
			if ($this->showError === false) {
				$this->query(
					'INSERT INTO hh_actions '.
					'(actionID ,name ,commandList ,triggers ,showOnUI ,notes) '.
					'VALUES ('.
						'NULL,'.
						'\''.$_POST['name'].'\','. 
						'\''.$_POST['commandList'].'\','. 
						'\''.$_POST['triggers'].'\','. 
						'\''.$_POST['showOnUI'].'\','. 
						'\''.$_POST['notes'].'\');'
					);
				$this->addNotice('Success','New action added successfully.');
			}

		} else { // Else of: isset (post vars)

			// Not all needed POST variables for adding new action received
			// Let's see if we want to delete something
			if (
					isset(
						$_POST['action'],
						$_POST['action_id']
						) 
					&& 
					$_POST['action'] == "delete"
				) {
				// action is 'delete' and action_id set, this is a delete request

				// Validate input
				if (ctype_digit($_POST['action_id'])) {
					// Input ok, delete wanted action
					$this->query('DELETE FROM hh_actions WHERE actionID='.$_POST['action_id']);
					$this->addNotice('Success','Action deleted successfully.');
				} else {
					// Delete command reveived, but the action id isn't valid
					$this->addError('Error deleting Action!',
							'No valid action_id given'.$_POST['action_id']);
				}
			} else {
				// POST request received but it isn't delete request
				// and doesn't contain all info for adding new
				$this->addError('Error adding new Action!',
								'POST request made, but not all POST variables set'.
								' for adding new action. No new actions were added.');
			}






			
		}
	} // req method = post


	// Get actions, commands and sensors lists
	$this->actions_list = $this->query("SELECT * FROM hh_actions");
	$this->commands_list = $this->query("SELECT * FROM hh_commands");
	$this->sensors_list = $this->query("SELECT * FROM hh_sensors");

?>