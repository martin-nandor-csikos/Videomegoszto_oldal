<?php
session_start();

if (isset($_SESSION['user_name'])) {
    echo "<p>Üdvözöljük, " . $_SESSION['user_name'] . "!</p>";
}

if (!isset($_SESSION['database_connection_success'])) {
    require "./php/oracle_conn.php";

    if ($conn) {
        echo '
        <script type="text/javascript">
            window.onload = function () { alert("Az adatbázissal való kapcsolat létrejött."); } 
        </script>';

        $_SESSION['database_connection_success'] = true;
    }
}

?>

<!DOCTYPE html>
<html lang="hu">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Főoldal - Videómegosztó</title>
</head>

<body>

    <?php
    if (isset($_SESSION['user_id'])) {
        // Kedvelt videók gomb
        // Feltöltés gomb
        echo "<a href=\"upload_page.php\">Videó feltöltés</a><br />";
        echo "<a href=\"php/logout.php\">Kijelentkezés</a>";
    } else {
        echo "<a href=\"login_page.php\">Bejelentkezés</a>";
    }
    ?>
    <!-- Search bar helye -->

    <!-- Kategóriák felsorolva -->

    <!-- Legnépszerűbb videók -->

    <!-- Legújabb videók -->

</body>

</html>