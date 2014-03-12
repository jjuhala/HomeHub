<?php
	// Let's see if this was POST request (adding new commands)
	if ($_SERVER['REQUEST_METHOD'] === 'POST') {

		// Add new if POST data for it is available
		if (isset(
			$_POST['name'],
			$_POST['command'],
			$_POST['rules'],
			$_POST['notes']
		)) {
			// All needed vars received, now validate them!


			// -- Make sure lenghts match table's maximums

			// Array of received data and their max lengths
			$data_len_validate = array 
			(
				'name' => 100,
				'command' => 50,
				'rules' => 300,
				'notes' => 300
			);

			// Validate length
			foreach($data_len_validate as $vdata => $maxlen) {
				if (strlen($_POST[$vdata]) > $maxlen) {
					$this->addError('Error adding new Command!',
							"$vdata is too long! Max length ".
							"for $vdata is $maxlen characters");
				}
			}

			// List of items which can't contain spaces
			$data_space_validate = array
			(
				'command',
				'rules'
			);

			// Validate spaceless items
			foreach($data_space_validate as $vdata) {
				if (preg_match('/\s/',$_POST[$vdata])) {
					$this->addError('Error adding new Command!',
							"$vdata has spaces. $vdata can't have spaces in it!");
				}
			}

			// That's it, if no errors so far, add a new command
			if ($this->showError === false) {
				$this->query(
					'INSERT INTO hh_commands '.
					'(commandID,name,command,ruleList,notes) '.
					'VALUES ('.
						'NULL,'.
						'\''.$_POST['name'].'\','. 
						'\''.$_POST['command'].'\','. 
						'\''.$_POST['rules'].'\','. 
						'\''.$_POST['notes'].'\');'
					);
				$this->addNotice('Success','New command added successfully.');
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
					$this->query('DELETE FROM hh_commands WHERE commandID='.$_POST['id']);
					$this->addNotice('Success','Command deleted successfully.');
				} else {
					// Delete command reveived, but the id isn't valid
					$this->addError('Error deleting Command!',
							'No valid id given');
				}
			} else {
				// POST request received but it isn't delete request
				// and doesn't contain all info for adding new
				$this->addError('Error adding new Command!',
								'POST request made, but not all POST variables set'.
								' for adding new command. No new commands were added.');
			}






			
		}
	} // req method = post


	// Commands and rules
	$this->commands_list = $this->query("SELECT * FROM hh_commands");
	$this->rules_list = $this->query("SELECT * FROM hh_rules");

?>