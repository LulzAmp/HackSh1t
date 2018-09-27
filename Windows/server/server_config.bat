@echo off
title HackSh1t -- Server Install
color 0a
set /p "$phpath=Give me your full system path to php.exe -> "
:port
set /p "$port=Choose a port to listen on (must be above 1024) -> "
if %$port% lss 1025 (
	echo I said: port must be above 1024!
	echo.
	goto port
)
set /p "$time=Enter the maximum amount of time users will be able to flood for (seconds) -> "

echo %$phpath% server.php>> server.bat

echo ^<?php> server.conf.php
echo if(!defined('server')){>> server.conf.php
echo 	die('Can\'t directly run server.conf.php');>> server.conf.php
echo }>> server.conf.php
echo $server = array('port' =^> %$port%, 'max_flooding_time' =^> %$time%);>> server.conf.php

echo.
echo Successfully configured your HackSh1t server.
timeout /t 3 /nobreak>nul