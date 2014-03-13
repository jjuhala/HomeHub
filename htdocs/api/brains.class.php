<?php
    /*
     *      Copyright (C) 2014 Janne Juhala 
     *      http://jjj.fi/
     *      janne.juhala@outlook.com
     *      https://github.com/jjuhala/HomeHub
     *
     *  This project is licensed under the terms of the MIT license.
     *
     */
    
class Brains {
	protected $vars = array();

	var $result = array(
			"status" => "error",
			"messages" => array ()
		);



	public function Run($get) {
		// Authorize request (get ['s'] must match api_secret from settings)
		$this->Authorize($get);

		// If q and name are not set, die
		if (!isset($get['q'])) $this->kill("No cmd given");
		if (!isset($get['name'])) $this->kill("No name given");

		// connect database
		$this->pdo = $this->ConnectPdo();

		$wcmd = $get['q'];
		if ($wcmd == "action") {
			$this->RunAction($get['name']);
		}
	}



	public function __construct() {
		$this->ResultPrinted = false;
	}

	public function Authorize($get) {
		if (isset($get['s']) === false || $get['s'] !== $this->conf['api_secret']) {
			$this->kill("Invalid secret");
		}
	}

	public function RunAction($action) {
		// Get wanted action
		$act_arr = $this->query(
			'SELECT * FROM hh_actions WHERE name = :action_name',
			array(':action_name' => $action)
		);
		if (count($act_arr) !== 1) $this->kill("Requested action not found");
		
		// Adjust the array a bit
		$act_arr = $act_arr[0];

		// Make sure we have commands list
		if (strlen($act_arr['commandList']) < 1) $this->kill("Action found but command list is empty");

		// List commands
		$commands_array = explode('|', $act_arr['commandList']);

		// Run each command
		$this->RunCommand($commands_array);
	}

	// Runs commands, takes array of commands to run
	public function RunCommand($commands) {
		// Die if no array given
		if (!is_array($commands)) $this->kill("Trying to run command but the given parameter is not an array");

		// Get wanted commands

		$clist = ':id_'.implode(',:id_', array_keys($commands));
		$params = array_combine(explode(",", $clist), $commands);	

		$cmdlist_ret = $this->query("SELECT * FROM hh_commands WHERE name IN ($clist)",$params);

		// Die if not all commands were found
		if (count($cmdlist_ret) < 1) $this->kill("None of the commands were found. No commands executed.");
		if (count($cmdlist_ret) !== count($commands)) 
			$this->kill("Not all commands were found. No commands executed.");
		

		// All commands found, now iterate through them and execute if no rules set or rules pass ^_^
		foreach($cmdlist_ret as $cmd) {
			if ($this->CheckRule($cmd['ruleList'],$cmd['commandID'])) {
				// Execute
				$this->ExecuteCommand($cmd['command'],$cmd['name']);

				// Update last execution time
				$this->query(
					"UPDATE hh_commands SET lastExecutionTime=NOW() WHERE commandID = :id",
					array(':id' => $cmd['commandID']),
					false // No fetch for UPDATE query
				);

			} else {
				$this->AddMessage('Command \''.$cmd['command'].'\' (name: \''.$cmd['name'].'\') rules didn\'t pass => not executed.');
			}
		}

		// All done
		$this->SetStatus("ok");
		$this->PrintResult();

	}


	// Give this awesome function a rule's name or two.. or n, and it returns true or false
	// To show more informative errors she wants cmdID too
	public function CheckRule($rules,$cmdid) {
		
		// If no rules set, return true automatically
		if (strlen($rules)<1) return true;

		// Parse rules
		$rulearr = explode('|',$rules);

		$clist = ':id_'.implode(',:id_', array_keys($rulearr));
		$params = array_combine(explode(",", $clist), $rulearr);	

		$rulelist = $this->query("SELECT * FROM hh_rules WHERE ruleName IN ($clist)",$params);

		// Die if wanted rules weren't found
		if (count($rulelist) < 1) $this->kill("None of the rules were found (Command ID $cmdid). No commands executed.");
		if (count($rulelist) !== count($rulearr)) 
			$this->kill("Not all rules were found (Command ID $cmdid). No commands executed.");


		foreach ($rulelist as $rule) {
			
			// Time based "fake sensors" dont need mysql actions (we know date&time without MySQL)
			if ($this->StartsWith($rule['sensorName'],'time:')) {
				$markup = str_replace('time:', '', $rule['sensorName']);
				$rule['sensor_val'] = date($markup);
			} else {
				// Otherwise we need to get sensor value
				$sensor_val = $this->query(
					"SELECT * FROM hh_sensors WHERE name = :sensor_name",
					array(':sensor_name' => $rule['sensorName'])
				);
				// Die if sensor wasn't found
				if (count($sensor_val) < 1) $this->kill('Sensor not found (Rule ID '.$rule['ruleID'].')');

				$rule['sensor_val'] = $sensor_val[0]['currentVal'];
			}	

			// Now as we know the sensor value, let's see if the rule passes
			$rule_result = $this->RuleCompare($rule['value'],$rule['rule'],$rule['sensor_val'],$rule['ruleID']);
			if ($rule_result == false) {
				// At least one was false, that's all we need to know that this doesn't pass
				return false;
			}

		}
		// None of the iterated rules returned false so all passed!
		return true;
	}

	public function RuleCompare($rule_value,$rule,$sensor_value,$ruleid) {

		switch ($rule) {
			case ">":
					//$this->AddMessage("Joo".$rule_value."-".$sensor_value);
				if ($rule_value > $sensor_value) {

					return true;
				} else {
					return false;
				}
			case "<":
				if ($rule_value < $sensor_value) {
					return true;
				} else {
					return false;
				}
			case "=":
				if ($rule_value == $sensor_value) {
					return true;
				} else {
					return false;
				}
			default:
				$this->kill("Invalid comaprison operator for rule ID $ruleid. Should be < > or =");
		}
	}


	// Sends the command to server
	public function ExecuteCommand($command,$cmdname) {
		$response = @file_get_contents('http://'.$this->conf['arduino_ip'].':'.$this->conf['arduino_port'].'/'.$command);
		if (strpos($response,'OK|ProcessedCmd') === false) {

			$this->kill(
				"Tried to execute command, but arduino server didn't respond at " . 
				$this->conf['arduino_ip'].':'.$this->conf['arduino_port']);
			
		} else {
			$this->AddMessage(
				'Command \''.$command.
				'\' (name: \''.$cmdname.'\') executed.');
		}
	}



    // MySQL query
    // Returns array of fetched items if fetch is active (default)
    public function query($query,$params = NULL,$fetch = true, $errorIdentifier = '') {
        try {
			$pdoq = $this->pdo->prepare($query);
			$pdoq->execute($params);
			// Use fetch assoc to save some memory
			if ($fetch) $result = $pdoq->fetchAll(PDO::FETCH_ASSOC);
        } catch(PDOException $pdoer) {
            $this->kill("MySQL Query failed. $errorIdentifier PDO Exception: " . $pdoer->getMessage());
        }

        if ($fetch) return $result;
    }




    public function kill($error) {
    	$this->SetStatus("error");
    	$this->AddMessage($error);
    	$this->PrintResult();
    	exit;
    }
    public function AddMessage($error) {
    	$this->result['messages'][] = array("message" => $error);
    }
    public function SetStatus ($status) {
    	$this->result['status'] = $status;
    }

    public function PrintResult() {
    	$this->ResultPrinted = true;
    	echo json_encode($this->result);
    	
    }


    public function ConnectPdo() {
    	try {
			$host = $this->conf['mysql_ip'];
			$db = $this->conf['mysql_database'];
			$user = $this->conf['mysql_user'];
			$pw = $this->conf['mysql_password'];
		    $conn = new PDO(
		    		"mysql:host=$host;". // HOST
		    		"dbname=$db", // DB
		    		$user, // USER
		    		$pw,  // PASSWORD
		            array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
		    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		    return $conn;
		} catch(PDOException $pdoer) {
		    $this->kill("Couldn't connect to MySQL. PDO Exception: " . $pdoer->getMessage());
		}
    }


    public function __set($key, $val) {
        $this->vars[$key] = $val;
    }
    public function __get($key) {
        return $this->vars[$key];
    }











    // Some cool functions to make my life easier
    public function StartsWith($haystack, $src) {
    	return $src === "" || strpos($haystack, $src) === 0;
	}


}