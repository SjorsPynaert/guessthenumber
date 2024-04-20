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
        <div class="d-flex justify-content-between">

            <div class="d-flex position-relative">
                <div class="container-title">
                    <a class="text-decoration-none text-white d-inline-block" href="index.php">Guess the Number!</a>
                </div>
                <div class="w-auto h-auto d-flex">
                    <a class="text-decoration-none margin-right" href="index.php">Home</a>
                    <a class="text-decoration-none" href="highscores.php">High Scores</a>
                </div>
            </div>
                <div class="text-center d-flex">
                <?php
                if(!isset($_SESSION['user-logged-in'])) {
                    ?>
                    <a href="login.php" style="text-decoration: none;">Login</a>
                    <?php
                } else {
                    ?><p style="margin-bottom: 0;">Welcome <?php echo $_SESSION['user-logged-in'];?></p>
                    <form id="logout-form" method="POST">
                        <input type="hidden" name="logout">
                        <a href="#" class="margin-left" id="logout" style="text-decoration: none;">Logout</a>
                    </form>
                    <script>
                        let hyperlinkLogout = document.getElementById("logout"),
                            logoutForm = document.getElementById("logout-form");

                        hyperlinkLogout.addEventListener("click", () => {
                           logoutForm.submit();
                        });
                    </script>
                    <?php
                }
                ?>
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
                        <form method="POST">

                            <label for="" class="form-label">Set minimum:</label>
                            <input type="number" min="0" class="d-block form-text w-100 mb-2 form-control" name="minnumber" required/>

                            <label for="" class="form-label">Set maximum:</label>
                            <input type="number" min="0" class="d-block form-text w-100 mb-2 form-control" name="maxnumber" required/>

                            <label for="" class="form-label">Max number of tries:</label>
                            <input type="number" min="0" class="d-block form-text w-100 mb-2 form-control" name="maxtries" required/>

                            <label for="" class="form-label">Max number of seconds for each guess:</label>
                            <input type="number" min="0" max="150" class="d-block form-text w-100 mb-2 form-control" name="maxseconds" required/>

                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" value="" name="showsessiondata" id="flexCheckDefault">
                                <label class="form-check-label" for="flexCheckDefault">
                                    Show session data?
                                </label>
                            </div>

                            <?php
                            //Log message
                            if(isset($_SESSION['message']))
                            {
                                ?><p><?php echo $_SESSION['message'];?></p><?php
                                unset($_SESSION['message']);
                            }
                            ?>
                            <input type="hidden" name="submit-settings">
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
                <h3>Guess the number | play | <?php echo $_SESSION['user-logged-in'];?></h3>
                <div class="border border-dark mb-2 opacity-50"></div>

                <p>Time left: <span id="remainingtime" class="text-info"> <?php echo $_SESSION['remaining-time']?> </span></p>
                <p>Time spend: <span id="timespend"><?php echo $_SESSION['elapsed-time'];?></span></p>

                <label for="" class="form-label">Your guess</label>
                <input id="userguess" type="text" class="d-block form-text w-100 mb-2 form-control" name="yourguess">
                <p>Min number: <span id="minmnumber"> <?php echo $_SESSION['minnumber']?> </span></p>
                <p>Max number: <span id="maxnumber"> <?php echo $_SESSION['maxnumber']?> </span></p>
                <p>Remaining tries: <span id="remainingtries"> <?php echo $_SESSION['remaining-tries']?> </span></p>

                <button id="guessbutton" class="btn btn-success d-block mb-2" onclick="guess();">Make your guess</button>
                <button id="quitorresetbutton" class="btn btn-danger d-block mb-2" onclick="showDialogueBox(`Do you want to quit or reset?`);">Quit or reset</button>

                <h2>Message: <span id="message"></span></h2>

                <p>Number of guesses: <span id="numberofguesses">0</span></p>
                <p>Previously guessed: <span id="previouslyguessed"></span></p>
                <div class="border border-dark mb-2 opacity-50"></div>
            </div>
            <div class="p-3 bg-primary w-100 h-auto text-white" id="sessiondatacontainer">
                <?php
                if(isset($_SESSION['showsessiondata']) && $_SESSION['showsessiondata'] === 1){
                    echo '<pre id="sessiondata">' . print_r($_SESSION, TRUE) . '</pre>';
                }
                else
                {
                    /*We need to make sure this element exists if this is not the case javascript will throw an
                    error because it can't create a variable if it attempts to read the element.*/
                    echo '<pre id="sessiondata"></pre>';
                }
                ?>
            </div>
            <div class="p-3 bg-white w-100 h-auto">
                <h2>High Scores:</h2>
                <?php
                highScoresTable();
                ?>
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
                    <form method="POST">
                        <input type="hidden" name="quit">
                        <button type="submit" id="quitbutton" class="btn btn-danger">Quit</button>
                    </form>
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
        let sessionDataContainer = document.getElementById("sessiondatacontainer");

        //Some variables for handling the game on the page.
        let maxTime = <?php echo $_SESSION['remaining-time'];?>;
        let sessionTime = <?php echo $_SESSION['maxseconds'];?>;
        let passedTime = <?php echo $_SESSION['elapsed-time'];?>;
        let totalGuesses = 0;
        let remainingTries = <?php echo $_SESSION['remaining-tries'];?>;
        let isEnabled = true;
        let showSessionData = <?php echo $_SESSION['showsessiondata'];?>;
        let gameWon = <?php echo $_SESSION['game-won'];?>

        if(showSessionData === 0)
        {
            sessionDataContainer.style.display = "none";
        }

        if(gameWon === 1) {
            disable();
            timeElement.innerHTML = maxTime.toString();
            timeSpend.innerHTML = "---";
            timeElement.innerHTML = "---";
            logMessage.innerHTML = "Congrats you won the game! Reset or Quit for different settings."
        }
        else if(remainingTries === 0) {
            disable();
            timeElement.innerHTML = maxTime.toString();
            timeSpend.innerHTML = "---";
            timeElement.innerHTML = "---";
            logMessage.innerHTML = "You ran out of attempts!"
        }
        else if(maxTime === 0)
        {
            disable();
            timeElement.innerHTML = maxTime.toString();
            timeSpend.innerHTML = "---";
            timeElement.innerHTML = "---";
            logMessage.innerHTML = "You ran out of time!"
        }

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

        //Function for showing dialogue box.
        function showDialogueBox(message)
        {
            guessButton.disabled = true;
            quitOrResetMessage.innerHTML = message;
            quitOrResetPopUp.style.display = "block";
        }

        function guess()
        {
            let userValue = userInput.value;
            if(userValue === null)
            {
                logMessage.innerHTML = "User guess is empty"
            }
            else
            {
                let url = "inc/functions.php?" + "function=guess&userguess=" + userValue;

                //Check if the user's guess is correct or not.
                let xhttp = new XMLHttpRequest();
                xhttp.onreadystatechange = function () {
                    if (this.readyState == 4 && this.status == 200) {
                        updateTime();
                        if(showSessionData === 1)
                        {
                            let JSONResponse = JSON.parse(this.response);
                            let sessionData = document.getElementById("sessiondata");
                            if(JSONResponse.message === "low")
                            {
                                sessionData.innerHTML = JSONResponse.session
                                updateLow(userValue);
                            }
                            else if(JSONResponse.message === "high")
                            {
                                sessionData.innerHTML = JSONResponse.session
                                updateHigh(userValue);
                            }
                            else
                            {
                                sessionData.innerHTML = JSONResponse.session
                                updateTableData(JSONResponse.data)
                                remainingTries--;
                                totalGuesses++;
                                logMessage.innerHTML = "Correct!";
                                maxTries.innerHTML = remainingTries;
                                numberOfGuesses.innerHTML = totalGuesses.toString();
                                disable();
                            }
                        }
                        else if(this.responseText === "low")
                        {
                            updateLow(userValue);
                        }
                        else if(this.responseText === "high")
                        {
                            updateHigh(userValue);
                        }
                        else
                        {
                            let JSONResponse = JSON.parse(this.response);
                            updateTableData(JSONResponse);
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

        function reset()
        {
            //Generate a new number and reset the timer via ajax.
            let xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function() {
                if(this.readyState == 4 && this.status == 200) {
                    if(showSessionData === 1)
                    {
                        let sessionData = document.getElementById("sessiondata");
                        sessionData.innerHTML = JSON.parse(this.response);
                        updateReset();
                        enable();
                    }
                    else
                    {
                        //Reset variables and update them.
                        updateReset();
                        enable();
                    }
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

        function updateLow(userValue)
        {
            totalGuesses++;
            remainingTries--;
            maxTries.innerHTML = remainingTries;
            if(remainingTries === 0) {
                disable();
                timeSpend.innerHTML = "---";
                timeElement.innerHTML = "You ran out of attempts!";
            }
            numberOfGuesses.innerHTML = totalGuesses.toString();
            logMessage.innerHTML = "Incorrect! You guessed to low!";
            previouslyGuessed.innerHTML = userValue;
        }

        function updateHigh(userValue)
        {
            totalGuesses++;
            remainingTries--;
            maxTries.innerHTML = remainingTries;
            if(remainingTries === 0)
            {
                disable();
                timeSpend.innerHTML = "---";
                timeElement.innerHTML = "You ran out of attempts!";
            }
            numberOfGuesses.innerHTML = totalGuesses.toString();
            logMessage.innerHTML = "Incorrect! You guessed to high!";
            previouslyGuessed.innerHTML = userValue;
        }

        function updateTime() {
            maxTime = sessionTime;
            timeElement.innerHTML = maxTime;
            timeSpend.innerHTML = passedTime;
        }

        function updateReset()
        {
            passedTime = 0;
            maxTime = sessionTime;
            timeElement.innerHTML = maxTime;
            timeSpend.innerHTML = passedTime;
            remainingTries = <?php echo $_SESSION['maxtries']; ?>;
            maxTries.innerHTML = <?php echo $_SESSION['maxtries']; ?>;
            totalGuesses = 0;
            numberOfGuesses.innerHTML = totalGuesses.toString();
            quitOrResetPopUp.style.display = "none";
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
                    ?>
                </tbody>
            </table>
    <?php
}

//Login screen.
function contentLogin() {
    ?>
    <div class="w-100
        h-auto
        d-flex
        justify-content-center
        align-content-center
        ">
        <form method="POST" id="login-container" class="mt-5 bg-white shadow object-fit-contain">

            <h1 class="bg-primary p-2 text-white">Login</h1>

            <div class="p-2">
                <label class="form-label" for="username">Username</label>
                <input class="form-control" name="username" type="text" required>

                <label class="form-label" for="password">Password</label>
                <input class="form-control" id="password" name="password" type="password" required>

                <div class="form-check">
                    <label class="form-check-label" for="show-hide-password">Show password</label>
                    <input class="form-check-input" id="show-hide-password" type="checkbox">
                </div>

                <p>
                    <?php
                    if(isset($_SESSION['message'])) {
                        echo $_SESSION['message'];
                        unset($_SESSION['message']);
                    }
                    ?>
                </p>

                <a class="d-block" href="register.php">Don't have an account yet? Click here to register now.</a>

                <input type="hidden" name="login">

                <button class="mt-2 btn btn-success" type="submit">Login</button>
            </div>

        </form>
    </div>

    <script src="js/login.js"></script>
    <?php
}

function contentRegister() {
    ?>
    <div class="w-100
    h-auto
    d-flex
    justify-content-center
    align-content-center
    ">
        <form method="POST" id="form-register" class="mt-5 bg-white shadow w-25">

            <h1 class="bg-primary p-2 text-white">Register</h1>

            <div class="p-2">
                <label class="form-label" for="username">Username</label>
                <input id="username" class="form-control" name="username" type="text" required>

                <label class="form-label" for="password">Password</label>
                <input id="password" class="form-control" name="password" type="password" required>

                <label  class="form-label" for="password-check">Password Check</label>
                <input id="password-check" class="form-control" name="password-check" type="password" required>

                <div class="form-check">
                    <label class="form-check-label" for="show-hide-password">Show password</label>
                    <input class="form-check-input" id="show-hide-password" type="checkbox">
                </div>

                <input type="hidden" name="register">

                <p id="logging-text">
                    <?php
                    if(isset($_SESSION['message'])) {
                        echo $_SESSION['message'];
                        unset($_SESSION['message']);
                    }
                    ?>
                </p>
                <button class="mt-2 btn btn-success" type="submit">Register</button>
            </div>

        </form>
    </div>

    <script src="js/register.js"></script>
    <?php
}