<?php

function HTMLheader()
{
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

        <title>Guess The Number!</title>
    </head>
    <body>
    <header class=
    "w-auto
    h-auto
    bg-dark
    text-white
    p-3">
        <div class="d-flex">
            <div class="container-title">
                <a class="text-decoration-none text-white d-inline-block" href="index.php">Guess the Number!</a>
            </div>
            <div class="w-auto h-auto d-flex">
                <a class="text-decoration-none margin-right" href="index.php">Home</a>
                <a class="text-decoration-none" href="highscores.php">High Scores</a>
            </div>
        </div>
    </header>
    <?php
}

function HTMLfooter()
{
    ?>
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
</body>
</html>
    <script src="js/bootstrap.js"></script>
    <?php
}

function contentIndex()
{
    ?>
    <div class=
             "w-100
             h-auto
             d-flex
             justify-content-center
             align-content-center
             ">
            <div class="d-flex flex-column w-50 mt-4 shadow">
                <div class="bg-primary bg-gradient text-white p-3">
                    <h4>Settings</h4>
                </div>
                <div class="d-flex">
                    <div class="w-50 p-3">
                        <form action="game.php" method="POST">


                            <label for="" class="form-label">Your name:</label>
                            <input type="text" class="d-block form-text w-100 mb-2 form-control" name="yourname" required/>

                            <label for="" class="form-label">Set minimum:</label>
                            <input type="number" min="0" class="d-block form-text w-100 mb-2 form-control" name="minnumber" required/>

                            <label for="" class="form-label">Set maximum:</label>
                            <input type="number" min="0" class="d-block form-text w-100 mb-2 form-control" name="maxnumber" required/>

                            <label for="" class="form-label">Max number of tries:</label>
                            <input type="number" min="0" class="d-block form-text w-100 mb-2 form-control" name="maxtries" required/>

                            <label for="" class="form-label">Max number of seconds:</label>
                            <input type="number" min="0" max="150" class="d-block form-text w-100 mb-2 form-control" name="maxseconds" required/>

                            <?php
                            //Log message
                            if(isset($_SESSION['message']))
                            {
                                ?><p><?php echo $_SESSION['message'];?></p><?php
                                unset($_SESSION['message']);
                            }
                            ?>
                            <button type="submit" class="btn btn-primary">Submit</button>
                            <button type="reset" class="btn btn-danger">Reset</button>
                        </form>
                    </div>
                    <div class="w-50 p-5">
                        <img src="images/questionmarks.png" alt="questionsmarks.png" class="w-100 h-auto">
                    </div>
                </div>
            </div>
        </div>
    <?php
}

function contentGame()
{
    ?>
    <div class="w-100
    h-auto
    d-flex
    justify-content-center
    align-content-center
    ">
        <div class="w-auto mt-5 mb-5 shadow d-flex flex-column">
            <div class="bg-primary p-3 text-white">
                <h2>Guess the number!</h2>
            </div>
            <div class="w-100 p-3 bg-white">
                <h3>Guess the number | play | <?php echo $_SESSION['player']?></h3>
                <div class="border border-dark mb-2 opacity-50"></div>

                <p>Time left: <span id="remainingtime" class="text-info"> <?php echo $_SESSION['maxseconds']?> </span></p>
                <p>Time spend: <span id="timespend">0</span></p>

                <label for="" class="form-label">Your guess</label>
                <input id="userguess" type="text" class="d-block form-text w-100 mb-2 form-control" name="yourguess">
                <p>Min number: <span id="minmnumber"> <?php echo $_SESSION['minnumber']?> </span></p>
                <p>Max number: <span id="maxnumber"> <?php echo $_SESSION['maxnumber']?> </span></p>
                <p>Remaining tries: <span id="remainingtries"> <?php echo $_SESSION['maxtries']?> </span></p>

                <button id="guessbutton" class="btn btn-success d-block mb-2" onclick="guess();">Make your guess</button>
                <button id="quitorresetbutton" class="btn btn-danger d-block mb-2" onclick="showDialogueBox(`Do you want to quit or reset?`);">Quit or reset</button>

                <h2>Message: <span id="message"></span></h2>

                <p>Number of guesses: <span id="numberofguesses">0</span></p>
                <p>Previously guessed: <span id="previouslyguessed"></span></p>
                <div class="border border-dark mb-2 opacity-50"></div>
                <h2>High Scores:</h2>
                <?php highScoresTable() ?>
            </div>
        </div>

        <!-- Create dialogue box inside the main container with a z index of 1 and a absolute position.
        This will cause the dialogue box to be forced to the center due to justify content center and align
        content center. -->
        <div id="popupquitorreset" class="z-1 position-fixed h-auto w-auto shadow flex-column mt-5">
            <div class="bg-primary text-white p-3">
                <h4 id="messagequitorreset">Do you want to quit or reset?</h4>
            </div>
            <div class="bg-white p-3">
                <div class="d-flex justify-content-between w-100">
                    <button onclick="reset();" id="guessbutton" class="btn btn-success">Reset</button>
                    <button onclick="cancel();" id="cancelbutton" type="button" class="btn btn-warning">Cancel</button>
                    <button onclick="quit();" id="quitbutton" class="btn btn-danger">Quit</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Script for the timer. Required to be here because we need to make use of php to
    correctly set the variables. -->
    <script>
        //Some elements.
        let userInput = document.getElementById("userguess");
        let logMessage = document.getElementById("message");
        let quitOrResetPopUp = document.getElementById("popupquitorreset");
        let quitOrResetMessage = document.getElementById("messagequitorreset")
        let timeElement = document.getElementById("remainingtime");
        let timeSpend = document.getElementById("timespend");
        let guessButton = document.getElementById("guessbutton");
        let previouslyGuessed = document.getElementById("previouslyguessed");
        let numberOfGuesses = document.getElementById("numberofguesses");
        let maxTries = document.getElementById("remainingtries");
        let highScoreTableBody = document.getElementById("highscoretablebody");

        //Some variables.
        let maxTime = <?php echo $_SESSION['maxseconds'];?>;
        let sessionTime = <?php echo $_SESSION['maxseconds'];?>;
        let passedTime = 0;
        let totalGuesses = 0;
        let remainingTries = <?php echo $_SESSION['maxtries'];?>;
        let isEnabled = true;

        //Timer that activates every second.
        let interval = setInterval(function (){
            if(isEnabled)
            {
                maxTime--;
                passedTime++;
                if(maxTime === 0)
                {
                    disable();
                    timeElement.innerHTML = maxTime.toString();
                    timeSpend.innerHTML = passedTime.toString();
                    timeElement.innerHTML = "Time is up!";
                }
                else
                {
                    timeElement.innerHTML = maxTime.toString();
                    timeSpend.innerHTML = passedTime.toString();
                }
            }
        }, 1000);

        function guess()
        {
            let userValue = userInput.value;
            if(userValue === null)
            {
                logMessage.innerHTML = "User guess is empty"
            }
            else
            {
                let url = "inc/functions.php?" + "function=guess&userguess=" + userValue + "&passedtime=" + passedTime;

                //Check if the user's guess is correct or not.
                let xhttp = new XMLHttpRequest();
                xhttp.onreadystatechange = function () {
                    if (this.readyState == 4 && this.status == 200) {
                        if (this.responseText === "low") {
                            //respond with correct, stop the timer and proceed to enter the user's score into the database.
                            totalGuesses++;
                            remainingTries--;
                            maxTries.innerHTML = remainingTries;
                            if(remainingTries === 0)
                            {
                                disable();
                            }
                            numberOfGuesses.innerHTML = totalGuesses.toString();
                            logMessage.innerHTML = "Incorrect! You guessed to low!";
                            previouslyGuessed.innerHTML = userValue;
                        }
                        else if(this.responseText === "high")
                        {
                            totalGuesses++;
                            remainingTries--;
                            maxTries.innerHTML = remainingTries;
                            if(remainingTries === 0)
                            {
                                disable();
                            }
                            numberOfGuesses.innerHTML = totalGuesses.toString();
                            logMessage.innerHTML = "Incorrect! You guessed to high!";
                            previouslyGuessed.innerHTML = userValue;
                        }
                        else
                        {
                            let JSONData = JSON.parse(this.response);
                            updateTableData(JSONData);
                            remainingTries--;
                            totalGuesses++;
                            logMessage.innerHTML = "Correct!";
                            maxTries.innerHTML = remainingTries;
                            numberOfGuesses.innerHTML = totalGuesses.toString();
                            disable();
                        }
                    }
                }
                xhttp.open("GET", url, true);
                xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                xhttp.send();
            }
        }

        function enable()
        {
            guessButton.disabled = false;
            userInput.disabled = false;
            isEnabled = true;
        }

        function disable()
        {
            guessButton.disabled = true;
            userInput.disabled = true;
            isEnabled = false;
        }

        //Function for showing dialogue box.
        function showDialogueBox(message)
        {
            guessButton.disabled = true;
            quitOrResetMessage.innerHTML = message;
            quitOrResetPopUp.style.display = "block";
        }

        function reset()
        {
            //Generate a new number and reset the timer via ajax.
            let xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function() {
                if(this.readyState == 4 && this.status == 200) {

                    //Reset variables and update them.
                    passedTime = 0;
                    maxTime = sessionTime;
                    timeElement.innerHTML = maxTime;
                    timeSpend.innerHTML = passedTime;
                    remainingTries = <?php echo $_SESSION['maxtries']; ?>;
                    maxTries.innerHTML = <?php echo $_SESSION['maxtries']; ?>;
                    totalGuesses = 0;
                    numberOfGuesses.innerHTML = totalGuesses.toString();
                    quitOrResetPopUp.style.display = "none";
                    enable();
                }
            }
            xhttp.open("GET", "inc/functions.php?" + "function=resetgame", true);
            xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xhttp.send();
        }

        function cancel()
        {
            quitOrResetPopUp.style.display = "none";
            guessButton.disabled = false;

        }
        function quit()
        {
            window.location.href = "index.php";
        }

        function updateTableData(JSON)
        {
            //Clear the table body and insert the new data.
            highScoreTableBody.innerHTML = "";
            let i = 1;

            JSON.forEach((highscore) => {
                /*Apparently you need to create a new row and table-data element for each element cause else it would
                replace the previous one.*/
                let row = document.createElement("tr");

                let td = document.createElement("td");
                td.innerHTML = highscore.id;
                row.appendChild(td)

                td = document.createElement("td");
                td.innerHTML = i.toString();
                row.appendChild(td)

                td = document.createElement("td");
                td.innerHTML = highscore.player;
                row.appendChild(td)

                td = document.createElement("td");
                td.innerHTML = highscore.range;
                row.appendChild(td)

                td = document.createElement("td");
                td.innerHTML = highscore.maxseconds;
                row.appendChild(td)

                td = document.createElement("td");
                td.innerHTML = highscore.maxtries;
                row.appendChild(td)

                td = document.createElement("td");
                td.innerHTML = highscore.numguesses;
                row.appendChild(td)

                td = document.createElement("td");
                td.innerHTML = highscore.time;
                row.appendChild(td)

                td = document.createElement("td");
                td.innerHTML = highscore.hiddennumber;
                row.appendChild(td)

                td = document.createElement("td");
                td.innerHTML = highscore.playeddate;
                row.appendChild(td)

                highScoreTableBody.appendChild(row);

                i++;
            });
        }
    </script>

    <?php
}

function contentHighScores()
{
    ?>
    <div class="w-100
    h-auto
    d-flex
    justify-content-center
    align-content-center
    ">
        <div class="mt-5 mb-5 shadow d-flex flex-column">
            <div class="bg-primary text-white p-3 w-100 h-auto">
                <h2>High Scores:</h2>
            </div>
            <div class="bg-white w-100 h-auto p-3">
                <?php
                highScoresTable();
                ?>
            </div>
        </div>
    </div>
    <?php
}

function highScoresTable()
{
    $highscoreData = getHighscoreData();
    ?>
            <table class="table">
                <!-- table head -->
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
                        <th>Hidden number</th>
                        <th>Play date</th>
                    </tr>
                </thead>

                <!-- table body -->
                <tbody id="highscoretablebody">
                    <?php
                    //Simple foreach to pump out the data. Make sure to also check whether the returned data is not null.
                    if(!$highscoreData)
                    {
                        ?><h2>No high scores available.</h2><?php
                    }
                    else
                    {
                        $i = 1;
                        while ($row = $highscoreData->fetch_assoc())
                        {
                            ?>
                            <tr>
                                <td><?php echo $row['id']; ?></td>
                                <td><?php echo $i; ?></td>
                                <td><?php echo $row['player']; ?></td>
                                <td><?php echo $row['range']; ?></td>
                                <td><?php echo $row['maxseconds'] ?></td>
                                <td><?php echo $row['maxtries']; ?></td>
                                <td><?php echo $row['numguesses']; ?></td>
                                <td><?php echo $row['time']; ?></td>
                                <td><?php echo $row['hiddennumber']; ?></td>
                                <td><?php echo $row['playeddate']; ?></td>
                            </tr>
                            <?php
                            $i++;
                        }
                    }
                    ?>
                </tbody>
            </table>
    <?php
}