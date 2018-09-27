#!/bin/bash
function jumpto
{
    label=$1
    cmd=$(sed -n "/$label:/{:a;n;p;ba};" $0 | grep -v ':$')
    eval "$cmd"
    exit
}
echo
echo Press return to configure your HackSh1t client
read lol

port=${1:-"server"}
echo -e "Enter the hostname or IP address of your HackSh1t server"
read server

time=${1:-"port"}
echo "Enter the port of your HackSh1t server"
read port

out="<?php if(!defined('client')){ die('Can\'t directly run client.conf.php.'); } \$client = array('server' => '$server', 'port' => $port);"
echo -e "$out" > client.conf.php
echo

echo "Successfully configured your HackSh1t server."
echo
exit
