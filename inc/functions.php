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

    //We have to keep track of the time using PHP which we can easily do using the microtime and round functions.
    $_SESSION['currenttime'] = round(microtime(true));

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
    $_SESSION['currenttime'] = round(microtime(true));
    $_SESSION['hiddennumber'] = rand($_SESSION['minnumber'], $_SESSION['maxnumber']);

    if(isset($_SESSION['showsessiondata']) && $_SESSION['showsessiondata'] == 1)
    {
        header("Content-Type: application/json");

        $JSON = json_encode(print_r($_SESSION, true));
        echo $JSON;
    }
}

//Check if the random number is equal to what the user guessed.
else if ($_SERVER['REQUEST_METHOD'] == "GET" && isset($_GET['function']) && $_GET['function'] == "guess")
{
    if ($_SESSION['hiddennumber'] == $_GET['userguess'])
    {
        /*When the number guessed is correct proceed to increment necessary values and create a new record in the database.
        Get the current time again and subtract the original time from it. Increment the amount of guesses and set the date
        and time via the date() function.*/
        $timeDifference = round(microtime(true)) - $_SESSION['currenttime'];
        $_SESSION['currenttime'] =  round(microtime(true));
        $_SESSION['numberofguesses']++;
        $_SESSION['dateandtime'] = date('Y-m-d H:i:s');

        $sql = "INSERT INTO highscores (`player`, `range`, `maxseconds`, `maxtries`, `numguesses`, `time`, `hiddennumber` ,`playeddate`)
        VALUES ('{$_SESSION["player"]}'
        , '{$_SESSION["range"]}'
        , '{$_SESSION["maxseconds"]}'
        , '{$_SESSION["maxtries"]}'
        , '{$_SESSION["numberofguesses"]}'
        , '$timeDifference'
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

        //Check if show session data is set or not.
        if($_SESSION['showsessiondata'] == 1)
        {
            //If it's the case we need both the session array and the sql data.
            $data = [
                "data" => $data,
                "session" => print_r($_SESSION, true)
            ];

            $JSON = json_encode($data);
            echo $JSON;
        }
        else
        {
            $JSON = json_encode($data);
            echo $JSON;
        }
    }
    else
    {
        $_SESSION['numberofguesses']++;
        if($_GET['userguess'] > $_SESSION['hiddennumber'])
        {
            //Check if the "showsessiondata" variable is set and if it's true. false and true are indicated as 0 and 1 in a session.
            if(isset($_SESSION['showsessiondata']) && $_SESSION['showsessiondata'] == 1)
            {
                /*If it's the case we need two different key pair values.
                The first one is the message which is used as filter mechanism in our javascript code
                The second one is the actual data.*/

                $data = [
                    "message" => "high",
                    "session" => print_r($_SESSION, true)
                ];
                header("Content-Type: application/json");
                $JSONData = json_encode($data);

                echo $JSONData;
            }
            else
            {
                echo "high";
            }
        }
        else if ($_GET['userguess'] < $_SESSION['hiddennumber'])
        {
            if(isset($_SESSION['showsessiondata']) && $_SESSION['showsessiondata'] == 1)
            {
                $data = [
                    "message" => "low",
                    "session" => print_r($_SESSION, true)
                ];
                header("Content-Type: application/json");
                $JSONData = json_encode($data);

                echo $JSONData;
            }
            else
            {
                echo "low";
            }
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
    //Check if show session data is set to true if show the session data when playing the game.
    else if(isset($_POST['showsessiondata']))
    {
        $_SESSION['showsessiondata'] = 1;
    }
    else
    {
        $_SESSION['showsessiondata'] = 0;
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


