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

    $_SESSION['minnumber'] = $_POST['minnumber'];

    $_SESSION['maxnumber'] = $_POST['maxnumber'];
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

function getHighscoreData() {

}

//Generate a new random number.
if($_SERVER['REQUEST_METHOD'] == "GET" && isset($_GET['function']) && $_GET['function'] == "resetgame")
{
    $_SESSION['hiddennumber'] = rand($_SESSION['minnumber'], $_SESSION['maxnumber']);
    echo $_SESSION['hiddennumber'];
}

//Generate a check the randomNumber.
else if($_SERVER['REQUEST_METHOD'] == "GET" && isset($_GET['function']) && $_GET['function'] == "guess")
{
    if($_SESSION['hiddennumber'] == $_GET['userguess'])
    {
        echo true;
    }
    else
    {
        echo false;
    }
}

