<?php

include "inc/functions.php";

?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    <header>
        Guess the number!
    </header>
        <div id="containerGame">
            <form action="">
                <h1>Guess the number!</h1>
                <h2>Guess the number | Play | Sjors Pynaert</h2>

                <p>Time left:</p>
                <p>Time Spent:</p>

                <label for="">Your guess:</label>
                <input type="text"/>
                <p>Max:</p>

                <button>Make your guess</button>
                <button>Quit or reset</button>
            </form>

            <h1>Message:</h1>
            <p>Number of Guesses:</p>
            <p>Previously Guessed:</p>

            <h1>Current High Scores</h1>

            <table>
                <caption>High scores guess the number</caption>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Place</th>
                            <th>Player</th>
                            <th>Range</th>
                            <th>Max seconds</th>
                            <th>Max tries</th>
                            <th>Num guesses</th>
                            <th>Time</th>
                            <th>Secret number</th>
                            <th>Cheated</th>
                            <th>Played date</th>
                        </tr>
                    </thead>

                    <tbody>
                    <!-- add for each to fetch the highscores from the database -->
                    </tbody>

            </table>
        </div>
    <footer>

    </footer>
</body>
</html>