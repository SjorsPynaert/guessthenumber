<?php

include "inc/functions.php";
include "inc/layout.php";

HTMLheader();
if(isset($_SESSION['game-started']) && $_SESSION['game-started'] == 1 && isset($_SESSION['user-logged-in'])) {
    contentGame();
} else {
    contentIndex();
}
HTMLfooter();

