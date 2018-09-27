@echo off
title HackSh1t -- Client Install
color 0a
set /p "$phpath=Give me your full system path to php.exe -> "
set /p "$server=Enter the hostname or IP address of your HackSh1t server -> "
set /p "$port=Enter the port of your HackSh1t server -> "

echo %$phpath% client.php>> client.bat

echo ^<?php> client.conf.php
echo if(!defined('client')){>> client.conf.php
echo 	die('Can\'t directly run client.conf.php');>> client.conf.php
echo }>> client.conf.php
echo $client = array('server' =^> '%$server%', 'port' =^> %$port%);>> client.conf.php

echo.
echo Successfully configured your HackSh1t client.
timeout /t 3 /nobreak>nul