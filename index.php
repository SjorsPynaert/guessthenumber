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

    <!-- bootstrap and stylesheet links -->
    <link rel="stylesheet" href="css/bootstrap.css">
    <link rel="stylesheet" href="css/style.css">

    <title>Document</title>
</head>
<body>
    <header class=
    "w-auto
    h-auto
    bg-dark
    text-white
    p-3">
    Guess the number!
    </header>
        <div class=
             "w-100
             h-auto
             d-flex
             justify-content-center
             align-content-center
             ">
            <div class=
                 "m-3
                 border
                 border-dark
                 rounded-5
                 p-3
                 ">
                <form action="">
                    <h2>Guess the number!</h2>
                    <h3>Guess the number | Play | Sjors Pynaert</h3>

                    <p>Time left:</p>
                    <p>Time Spent:</p>

                    <label for="">Your guess:</label>
                    <input type="text"/>
                    <p>Max:</p>

                    <button>Make your guess</button>
                    <button>Quit or reset</button>
                </form>

                <h2>Message:</h2>
                <p>Number of Guesses:</p>
                <p>Previously Guessed:</p>

                <h2>Current High Scores</h2>

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
        </div>
    <footer class=
    "w-100
    h-auto
    bg-dark
    text-white
    p-1
    position-fixed
    bottom-0
    d-flex
    justify-content-center
    main-footer
    ">
    Sjors Pynaert all rights reserved
    </footer>

    <script src="js/bootstrap.js"></script>
</body>
</html>