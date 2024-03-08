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
        Guess the number!
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
                            <input type="text" class="d-block form-text w-100 mb-2 form-control" name="yourname"/>

                            <label for="" class="form-label">Set minimum:</label>
                            <input type="text" class="d-block form-text w-100 mb-2 form-control" name="minnumber"/>

                            <label for="" class="form-label">Set maximum:</label>
                            <input type="text" class="d-block form-text w-100 mb-2 form-control" name="maxnumber"/>

                            <label for="" class="form-label">Max number of tries:</label>
                            <input type="text" class="d-block form-text w-100 mb-2 form-control" name="maxtries"/>

                            <label for="" class="form-label">Max number of seconds:</label>
                            <input type="text" class="d-block form-text w-100 mb-2 form-control" name="maxseconds"/>

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
                <h3>Guess the number | play | <?php //echo $_SESSION['playername']?></h3>
                <div class="border border-dark mb-2 opacity-50"></div>

                <p>Time left: <?php //echo $_SESSION['maxseconds']?></p>
                <p>Time spend: <span id="remainingtime" class="text-info"></span></p>

                <label for="" class="form-label">Your guess</label>
                <input id="userguess" type="text" class="d-block form-text w-100 mb-2 form-control" name="yourguess">
                <p>Min number: <span id="minmnumber"></span></p>
                <p>Max number: <span id="maxnumber"></span></p>

                <button id="guessbutton" class="btn btn-success d-block mb-2" onclick="handleUserInput();">Make your guess</button>
                <button id="quitorresetbutton" class="btn btn-danger d-block mb-2" onclick="showDialogueBox(`Do you want to quit or reset?`);">Quit or reset</button>

                <h2>Message: <span id="message"></span></h2>

                <p>Number of guesses: <span id="numberofguesses"></span></p>
                <p>Previously guessed: <span id="previouslyguessed"></span></p>
                <div class="border border-dark mb-2 opacity-50"></div>

                <h2>High Scores:</h2>
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
                    <tbody>
                        <!-- Insert some kind of foreach that fetches the scores from the database. -->
                        <tr>

                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Create dialogue box inside the main container with a z index of 1 and a absolute position.
        This will cause the dialogue box to be forced to the center due to justify content center and align
        content center. -->
        <div id="popupquitorreset" class="z-1 position-fixed h-auto w-auto shadow d-flex flex-column mt-5">
            <div class="bg-primary text-white p-3">
                <h4 id="messagequitorreset">Do you want to quit or reset?</h4>
            </div>
            <div class="bg-white p-3">
                <div class="d-flex justify-content-between w-100">
                    <button onclick="reset();" id="confirmbutton" class="btn btn-success">Reset</button>
                    <button onclick="cancel();" id="cancelbutton" type="button" class="btn btn-warning">Cancel</button>
                    <button onclick="quit();" id="quitbutton" class="btn btn-danger">Quit</button>
                </div>
                <p onclick="reset();" id="testparaf">test click me!</p>
            </div>
        </div>
    </div>


    <!-- Script for the timer. Required to be here because we need to make use of php to
    correctly set the variables. -->
    <script>
        //Some elements.
        let userInput = document.getElementById("userguess").value;
        let quitOrResetPopUp = document.getElementById("popupquitorreset");
        let quitOrResetMessage = document.getElementById("messagequitorreset")

        let maxTime = 10;
        let timeElement = document.getElementById("remainingtime");
        timeElement.innerHTML = maxTime.toString();

        //Timer that activates every second.
        let interval = setInterval(function (){
            maxTime--;
            if(maxTime === 0)
            {
                clearInterval(interval);
                timeElement.innerHTML = "Time is up!";
            }
            else
            {
                timeElement.innerHTML = maxTime.toString();
            }
        }, 1000);

        function guess()
        {
            //Generate a new number and reset the timer via ajax.
            let xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function() {
                if(this.readyState == 4 && this.status == 200) {
                    document.getElementById("testparaf").innerHTML = this.responseText;
                }
            }
            xhttp.open("GET", "inc/functions.php?" + "function=guess&userguess=" + userInput, true);
            xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xhttp.send();
        }

        //Function for showing dialogue box.
        function showDialogueBox(message)
        {
            quitOrResetMessage.innerHTML = message;
            quitOrResetPopUp.style.display = "block";
        }

        function reset()
        {
            //Generate a new number and reset the timer via ajax.
            let xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function() {
                if(this.readyState == 4 && this.status == 200) {
                    document.getElementById("testparaf").innerHTML = this.responseText;
                }
            }
            xhttp.open("GET", "inc/functions.php?" + "function=resetgame", true);
            xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xhttp.send();
        }

        function cancel()
        {
            quitOrResetMessage.style.display = "none";
        }
        function quit()
        {


        }
    </script>

    <?php
}

function highScoresTable()
{

}