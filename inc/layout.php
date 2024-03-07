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
                        <form action="" method="POST">


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
        <div class="w-50 mt-4 shadow d-flex flex-column">
            <div class="bg-primary p-3 text-white">
                <h2>Guess the number!</h2>
            </div>
            <div class="w-100">
                <h3>Guess the number | play | <?php //echo $_SESSION['playername']?></h3>
                <p>Time left: <?php //echo $_SESSION['maxseconds']?></p>
                <p>Time spend: <span id="remainingtime" class="text-info"></span> </p>
                <label for="">Your guess</label>
            </div>
        </div>
    </div>

    <!-- Script for the timer. (Maybe put this in a separate file as well) -->
    <script>
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
        }, 1000)
    </script>

    <?php
}

function highScoresTable()
{

}