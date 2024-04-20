<?php

//Start session.
session_start();

function getDatabaseObject() {
    $server = "localhost";
    $username = "root";
    $password = "";
    $databaseName = "guessthenumber";

    return new mysqli($server, $username, $password, $databaseName);
}

$database = getDatabaseObject();

//Test database connection.
if($database->connect_error) {
    die("Couldn't connect!");
}

$database->close();

if($_SERVER['REQUEST_METHOD'] == "POST") {
    handlePost();
}

if($_SERVER['REQUEST_METHOD'] == "GET") {
    handleGet();
}

//For handling post requests.
function handlePost() {
    if(isset($_POST['register'])) {
        handleRegister();
    }
    else if(isset($_POST['login'])) {
        handleLogin();
    }
    else if(isset($_POST['logout'])) {
        //Logout the user by unsetting the session variable.
        unset($_SESSION['user-logged-in']);
        $_SESSION['game-started'] = 0;
        $_SESSION['game-won'] = 0;
        header("Location: index.php");
    }
    else if(isset($_POST['quit'])) {
        $_SESSION['game-started'] = 0;
        $_SESSION['game-won'] = 0;
        header("Location: index.php");
    }
    else if(isset($_POST['submit-settings'])) {
        handleSettingsForm();
    }
}

//For handling get requests.
function handleGet() {
    if(isset($_GET['function']) && $_GET['function'] == "guess") {
        handleGuess();
    }
    else if(isset($_GET['function']) && $_GET['function'] == "resetgame") {
        handleReset();
    }
    else if(isset($_SESSION['game-started']) && $_SESSION['game-started'] == 1) {
        updateGameStatus();
    }
}

function handleRegister() {
    $database = getDatabaseObject();

    $username = $_POST['username'];
    $password = $_POST['password'];

    //Check if the user exists in the database.
    $SQL = "SELECT * FROM users WHERE username = '$username';";

    if ($database->query($SQL)->num_rows == 1) {
        $_SESSION['message'] = "This user already exists!";
    } else {
        //Hash the password just because we can.
        $hash = password_hash($password, PASSWORD_DEFAULT);

        //Create new instance in the database.
        $SQL = "INSERT INTO users (`username`, `password`) VALUES ('$username', '$hash')";

        if(!$database->query($SQL)) {
            $_SESSION['message'] = "Something went wrong and we couldn't create your account. Contact the creator.";
        } else {
            //Return a message that the user was succesfully created and also log them in.
            $_SESSION['message'] = "Succesfully created new user!";
            $_SESSION['user-logged-in'] = $username;
        }
    }

    $database->close();
}

function handleLogin() {
    $database = getDatabaseObject();

    $username = $_POST['username'];
    $password = $_POST['password'];
    //Attempt to find the correct user in the database.
    $SQL = "SELECT * FROM users WHERE username = '$username'";

    if($database->query($SQL)->num_rows > 1) {
        $_SESSION['message'] = "There appears to multiple instances of this user. Contact the creator!";

    } else if ($database->query($SQL)->num_rows == 1) {
        //Get the hashed password of the user.
        $SQL = "SELECT `password` FROM users WHERE username = '$username'";

        $result = $database->query($SQL)->fetch_assoc();

        $hash = $result['password'];

        //Compare the hash retrieved from the database with the entered password.
        if(password_verify($password, $hash)) {
            $_SESSION['user-logged-in'] = $username;
            header("Location: index.php");
            exit();
        } else {
            //If password is not correct return a message to the login page.
            $_SESSION['message'] = "Password is incorrect!";
        }
    } else {
        $_SESSION['message'] = "Username was not found or incorrect.";
    }

    $database->close();
}

//Function to update the game status if the player happens to have switched away from the page.
function updateGameStatus() {

    $currentTime = round(microtime(true));
    $elapsedTime = $currentTime - $_SESSION['start-time-guess'];

    $_SESSION['elapsed-time'] += $elapsedTime;

    $_SESSION['start-time-guess'] = $currentTime;

    if($_SESSION['remaining-time'] - $elapsedTime < 0) {
        $_SESSION['remaining-time'] = 0;
    } else {
        $_SESSION['remaining-time'] = $_SESSION['remaining-time'] - $elapsedTime;
    }
}

function gameInit() {

    $_SESSION['minnumber'] = $_POST['minnumber'];
    $_SESSION['maxnumber'] = $_POST['maxnumber'];
    $_SESSION['numberofguesses'] = 0;
    $_SESSION['maxtries'] = $_POST['maxtries'];
    $_SESSION['game-won'] = 0;

    //Session variable for keeping track of the remaining attempts in a started game.
    $_SESSION['remaining-tries'] = $_POST['maxtries'];

    //Set the game started variable to 1 to indicate that the game has started.
    $_SESSION['game-started'] = 1;

    //Session variable to eventually get the elapsed time near the end when a game is finished.
    $_SESSION['game-started-time'] = round(microtime(true));

    //Session variable to keep track of the elapsed time between guesses.
    $_SESSION['start-time-guess'] = round(microtime(true));

    //Session variable for the remaining time.
    $_SESSION['remaining-time'] = $_POST['maxseconds'];

    //Session variable that indicated the max time between each guess.
    $_SESSION['maxseconds'] = $_POST['maxseconds'];

    //Set the elapsed time to 0 at the start of the game.
    $_SESSION['elapsed-time'] = 0;

    //Create the range number not to difficult just the difference between the min and the max number.
    $_SESSION['range'] = $_POST['maxnumber'] - $_POST['minnumber'];

    //Create the hidden number not to difficult just a rand function.
    $_SESSION['hiddennumber'] = rand($_POST['minnumber'], $_POST['maxnumber']);
}

function handleReset() {
    //Reset number of guesses back to 0 and create a new hidden number the rest is easily handled via javascript.
    $_SESSION['numberofguesses'] = 0;
    $_SESSION['game-started-time'] = round(microtime(true));
    $_SESSION['hiddennumber'] = rand($_SESSION['minnumber'], $_SESSION['maxnumber']);
    $_SESSION['remaining-tries'] = $_SESSION['maxtries'];
    $_SESSION['remaining-time'] = $_SESSION['maxseconds'];
    $_SESSION['start-time-guess'] = round(microtime(true));
    $_SESSION['elapsed-time'] = 0;
    $_SESSION['game-won'] = 0;

    if(isset($_SESSION['showsessiondata']) && $_SESSION['showsessiondata'] == 1)
    {
        header("Content-Type: application/json");
        $JSON = json_encode(print_r($_SESSION, true));

        echo $JSON;
    }
}

function handleGuess() {
    //decrement the remaining tries which is needed to update in case the player refreshes the page or switched to another page.
    $_SESSION['remaining-tries']--;
    $_SESSION['numberofguesses']++;
    if ($_SESSION['hiddennumber'] == $_GET['userguess'])
    {
        /*When the number guessed is correct proceed to increment necessary values and create a new record in the database.
        Get the current time again and subtract the original time from it. Increment the amount of guesses and set the date
        and time via the date() function.*/
        $timeDifference = round(microtime(true)) - $_SESSION['game-started-time'];

        $_SESSION['dateandtime'] = date('Y-m-d H:i:s');
        $_SESSION['game-won'] = 1;

        $sql = "INSERT INTO highscores (`player`, `range`, `maxseconds`, `maxtries`, `numguesses`, `time`, `hiddennumber` ,`playeddate`)
        VALUES ('{$_SESSION["user-logged-in"]}'
        , '{$_SESSION["range"]}'
        , '{$_SESSION["maxseconds"]}'
        , '{$_SESSION["maxtries"]}'
        , '{$_SESSION["numberofguesses"]}'
        , '$timeDifference'
        , '{$_SESSION["hiddennumber"]}'
        , '{$_SESSION["dateandtime"]}')";

        $database = getDatabaseObject();

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
    else if($_GET['userguess'] > $_SESSION['hiddennumber'])
    {
        $_SESSION['remaining-time'] = $_SESSION['maxseconds'];
        $_SESSION['start-time-guess'] = round(microtime(true));

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
        $_SESSION['remaining-time'] = $_SESSION['maxseconds'];
        $_SESSION['start-time-guess'] = round(microtime(true));
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

function handleSettingsForm() {

    /*First check if the user is logged in, this is a requirement, so it wouldn't make sense
    to force them to log in after they have finished a game.*/
    if(!isset($_SESSION['user-logged-in'])) {
        $_SESSION['message'] = "The user requires to be logged in to submit their score!";
        header("Location: index.php");
        exit();
    }
    //Check if the min number is higher than the max number else redirect back.
    else if($_POST['minnumber'] >= $_POST['maxnumber'])
    {
        header("Location: index.php");
        $_SESSION['message'] = "Minimum number can't be the same or higher than the maximum number!";
        header("Location: index.php");
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
    gameInit();
    header("Location: index.php");
}


function getHighscoreData() {
    $sql = "SELECT * FROM highscores ORDER BY `range` DESC, `time` ASC, numguesses ASC";

    $database = getDatabaseObject();

    if(!$database->query($sql)) {
        die("Couldn't get highscore data!");
    } else {
        $highscoreData = $database->query($sql);
    }

    $database->close();
    return $highscoreData;
}




