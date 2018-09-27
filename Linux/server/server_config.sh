#!/bin/bash
function jumpto
{
    label=$1
    cmd=$(sed -n "/$label:/{:a;n;p;ba};" $0 | grep -v ':$')
    eval "$cmd"
    exit
}
echo
echo Press return to configure your HackSh1t server
read lol 

port=${1:-"port"}
echo -e "Choose a port to listen on (must be above \e[31;1m1024\e[0m)"
read port
if [ $port -lt 1025 ]; then
	echo
	echo -e "I said: port must be above \e[31;1m1024\e[0m!"
	jumpto $port
	echo
else
	echo
fi

time=${1:-"time"}
echo "Enter the maximum amount of time users will be able to flood for (seconds)"
read time

out="<?php if(!defined('server')){ die('Can\'t directly run server.conf.php.'); } \$server = array('port' => $port, 'max_flooding_time' => $time);"
echo -e "$out" > server.conf.php
echo

echo "Successfully configured your HackSh1t server."
echo
exit
