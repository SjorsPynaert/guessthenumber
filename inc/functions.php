<?php

//Check if a session is active or not. If not start a new one.
if(session_start() != PHP_SESSION_ACTIVE)
{
    session_start();
}

//Database connection.
//Default username is "root" and the password is blank.
$server = "localhost";
$username = "root";
$password = "";
$databaseName = "guessthenumber";

$mysql = new mysqli($server, $username, $password, $databaseName);

if($mysql->connect_error)
{
    die("Couldn't connect!");
}
else
{
    echo "Connected";
}

$mysql->close();