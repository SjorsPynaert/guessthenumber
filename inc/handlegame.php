<?php
//Script for handling various ajax requests related to the number game./
session_start();
//When a user resets the game, generate a random number and reset the timer via javascript.
if ($_SERVER['REQUEST_METHOD'] == "GET" && isset($_GET['function']) && $_GET['function'] == "resetgame")
{
    $_SESSION['hiddennumber'] = rand($_SESSION['minnumber'], $_SESSION['maxnumber']);
    echo $_SESSION['hiddennumber'];
}

//Check if the random number is equal to what the user guessed.
else if ($_SERVER['REQUEST_METHOD'] == "GET" && isset($_GET['function']) && $_GET['function'] == "guess")
{
    if ($_SESSION['hiddennumber'] == $_GET['userguess'])
    {
        //When number is guessed correctly proceed to update the stats in the database.
        $_SESSION['numberofguesses']++;
        $_SESSION['passedtime'] = $_GET['passedtime'];
        echo "correct";

        $query = "INSERT INTO highscores (player, range, maxseconds, maxtries, numguesses, time, hiddennumber ,playeddate)
        VALUES 
        ()";
    }
    else
    {
        $_SESSION['numberofguesses']++;
        if($_GET['userguess'] > $_SESSION['hiddennumber'])
        {
            echo "high";
        }
        else if ($_GET['userguess'] < $_SESSION['hiddennumber'])
        {
            echo "low";
        }
    }
}