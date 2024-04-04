<?php

session_start();

if (isset($_SESSION['user_id'])) {
    session_unset();
    session_destroy();

    // Kijelentkezésnél ne legyen újra popup alert, csak akkor, ha bezárod a böngészőt
    session_start();
    $_SESSION['database_connection_success'] = true;
    header("Location: ./../index.php");
}