<?php

//Start session.
session_start();

//Database connection.
//Default username is "root" and the password is blank.
$server = "localhost";
$username = "root";
$password = "";
$databaseName = "guessthenumber";

$database = new mysqli($server, $username, $password, $databaseName);

if($database->connect_error) {
    die("Couldn't connect!");
}

$database->close();

function updateSessiondata() {

    $_SESSION['player'] = $_POST['yourname'];
    $_SESSION['minnumber'] = $_POST['minnumber'];
    $_SESSION['maxnumber'] = $_POST['maxnumber'];
    $_SESSION['numberofguesses'] = 0;
    $_SESSION['maxtries'] = $_POST['maxtries'];

    //Create the range number not to difficult just the difference between the min and the max number.
    $_SESSION['maxseconds'] = $_POST['maxseconds'];

    //Create the range number not to difficult just the difference between the min and the max number.
    $_SESSION['range'] = $_POST['maxnumber'] - $_POST['minnumber'];

    //Create the hidden number not to difficult just a rand function.
    $_SESSION['hiddennumber'] = rand($_POST['minnumber'], $_POST['maxnumber']);
}

/*
//Request handling for incoming request from the number game.
*/
if ($_SERVER['REQUEST_METHOD'] == "GET" && isset($_GET['function']) && $_GET['function'] == "resetgame")
{
    //Reset number of guesses back to 0 and create a new hidden number the rest is easily handled via javascript.
    $_SESSION['numberofguesses'] = 0;
    $_SESSION['hiddennumber'] = rand($_SESSION['minnumber'], $_SESSION['maxnumber']);
}

//Check if the random number is equal to what the user guessed.
else if ($_SERVER['REQUEST_METHOD'] == "GET" && isset($_GET['function']) && $_GET['function'] == "guess")
{
    if ($_SESSION['hiddennumber'] == $_GET['userguess'])
    {
        //When the number guessed is correct proceed to increment necessary values and create a new record in the database.
        $_SESSION['numberofguesses']++;
        $_SESSION['passedtime'] = $_GET['passedtime'];
        $_SESSION['dateandtime'] = date('Y-m-d H:i:s');

        $sql = "INSERT INTO highscores (`player`, `range`, `maxseconds`, `maxtries`, `numguesses`, `time`, `hiddennumber` ,`playeddate`)
        VALUES ('{$_SESSION["player"]}'
        , '{$_SESSION["range"]}'
        , '{$_SESSION["maxseconds"]}'
        , '{$_SESSION["maxtries"]}'
        , '{$_SESSION["numberofguesses"]}'
        , '{$_SESSION["passedtime"]}'
        , '{$_SESSION["hiddennumber"]}'
        , '{$_SESSION["dateandtime"]}')";

        $database = new mysqli($server, $username, $password, $databaseName);

        //Check if the database instance does get created else die.
        if($database->query($sql) === false) {
            die("Error couldn't create new instance in the database.");
        }

        //Gather the table data.
        $highScoreData = getHighscoreData();

        //Close database.
        $database->close();

        $data = [];

        while($row = $highScoreData->fetch_assoc())
        {
            $data[] = $row;
        }

        //Set content type header, convert the data to JSON and parse the data.
        header("Content-Type: application/json");

        $JSON = json_encode($data);

        echo $JSON;
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

if($_SERVER['REQUEST_METHOD'] == "POST")
{
    //Check if the min number is higher than the max number else redirect back.
    if($_POST['minnumber'] >= $_POST['maxnumber'])
    {
        header("Location: index.php");
        $_SESSION['message'] = "Minimum number can't be the same or higher than the maximum number!";
        exit();
    }
    updateSessiondata();
}

function getHighscoreData() {
    $sql = "SELECT * FROM highscores ORDER BY `range` DESC, `time` ASC, numguesses ASC";

    global $server, $username, $password, $databaseName;
    $database = new mysqli($server, $username, $password, $databaseName);

    if(!$database->query($sql)) {
        die("Couldn't get highscore data!");
    } else {
        $highscoreData = $database->query($sql);
    }

    $database->close();
    return $highscoreData;
}

if($_SERVER['REQUEST_METHOD'] === "GET" && isset($_GET['function']) && $_GET['function'] == "test")
{
    $result = getHighscoreData()->fetch_all();
    var_dump($result);
}


