<?php
session_start();
?>

<!DOCTYPE html>
<html lang="hu">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Főoldal - Videómegosztó</title>

    <style>
        .search_result {
            border: solid black 1px;
            cursor: pointer;
            margin-bottom: 10px;
        }
    </style>
</head>

<body>

    <?php
    if (isset($_SESSION['user_name'])) {
        echo "<p>Üdvözöljük, " . $_SESSION['user_name'] . "!</p>";
    }

    // Database connection popup alert. Csak akkor jön elő, ha legelső alkalommal nyílik meg az oldal
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
    
    if (isset($_SESSION['user_id'])) {
        if ($_SESSION['user_isadmin'] == 0) {
            // Kedvelt videók gomb
            // Feltöltés gomb
            echo "<a href=\"upload_page.php\">Videó feltöltés</a><br />";
        } else {
            echo "<a href=\"delete_page.php\">Videó törlése</a><br />";
        }
        echo "<a href=\"php/logout.php\">Kijelentkezés</a>";
    } else {
        echo "<a href=\"login_page.php\">Bejelentkezés</a>";
    }
    ?>
    
    <form action="index.php" method="get" enctype="multipart/form-data">
    <input type="text" name="term" id="term" required />
    <input type="submit" name="search_term_submit" value="Keresés" />
    </form>

    <?php
    if (isset($_GET["search_term_submit"])) {
        require "php/oracle_conn.php";
        require("php/search_term.php");

        // Maximum megjelenített találatok száma
        $result_count = 10;
        search_term($conn, $result_count);
        unset($_POST['search_term_submit']);
    }
    ?>
    <!-- Kategóriák felsorolva -->

    <!-- Legnépszerűbb videók -->

    <!-- Legújabb videók -->

</body>

</html>