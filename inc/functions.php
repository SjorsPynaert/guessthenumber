<?php

//Start session.
session_start();

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
    //echo "Connected";
}

$mysql->close();

function updateSessiondata() {

    $_SESSION['player'] = $_POST['yourname'];

    //Create the hidden number not to difficult just a rand function.
    $_SESSION['hiddennumber'] = rand($_POST['minnumber'], $_POST['maxnumber']);

    //Create the range number not to difficult just the difference between the min and the max number.
    $_SESSION['range'] = $_POST['maxnumber'] - $_POST['minnumber'];

    //Create the range number not to difficult just the difference between the min and the max number.
    $_SESSION['maxseconds'] = $_POST['maxseconds'];

    $_SESSION['maxtries'] = $_POST['maxtries'];

    $_SESSION['dateandtime'] = date('Y-m-d H:i:s');
}

if($_SERVER['REQUEST_METHOD'] == "POST")
{
    updateSessiondata();
}

