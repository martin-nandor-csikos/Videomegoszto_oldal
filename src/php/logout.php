<?php

session_start();

if (isset($_SESSION['user_id'])) {
    session_unset();
    session_destroy();

    session_start();
    $_SESSION['database_connection_success'] = true;
    header("Location: ./../index.php");
}