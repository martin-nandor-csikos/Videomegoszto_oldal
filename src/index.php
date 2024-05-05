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
    require_once "menu.php";

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

    <br>

    <form method="post">
        <?php
            require "php/oracle_conn.php";
            $list_categories = oci_parse($conn, "SELECT * FROM KATEGORIA");
            oci_execute($list_categories);
            while (oci_fetch($list_categories)) {
                echo "<input type='submit' value='" . oci_result($list_categories, "CIM") . "' name='" . oci_result($list_categories, "ID") . "'>";
            }
        ?>
        <br>
        <input type="submit" value="Összes kategória" name="all">
    </form>

    <?php
    // Legnézettebb + legújabb videók
    include_once "php/get_index_videos.php";

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && !isset($_POST['all'])) {

        // Mivel nem tudjuk melyik lett kiválasztva, ezért így oldjuk meg gyorsan, hogy kiirassuk az értékét
        foreach ($_POST as $name => $val)
        {
            echo "<p>Legnézettebb '" . htmlspecialchars($val) . "' videók</p>";
        }

        foreach ($_POST as $name => $val) {
            getMostPopular($name);
        }

        foreach ($_POST as $name => $val)
        {
            echo "<p>Legújabb '" . htmlspecialchars($val) . "' videók</p>";
        }

        foreach ($_POST as $name => $val) {
            getLatest($name);
        }
    } else {
        echo "<p>Legnézettebb videók</p>";
        getMostPopular(null);
        echo "<p>Legújabb videók</p>";
        getLatest(null);
    }

    // Itt kívül echozzuk, mert csak így működik az eventlistener a legnézettebb, és a legújabb videókra egyszerre
    echo "
    <script>
    let results = document.getElementsByClassName('search_result');
    Array.from(results).forEach((res) => {
        let video_id = res.id.split('_')[0];
        res.addEventListener('click', function() {
            window.location.href = '/video_page.php?video_id=' + video_id;
        });
    });
    </script>
    ";
    
    echo "<p>Legtöbb videót feltöltő felhasználók</p>";
    getMostUploaded();

    echo "<p>Legtöbb kommentet író felhasználók</p>";
    getMostCommented();

    echo "<p>Mai napon a legtöbb videót feltöltő felhasználók</p>";
    getMostUploadedToday();

    echo "<p>Mai napon a legtöbb kommentet író felhasználók</p>";
    getMostCommentedToday();

    echo "<p>Legtöbb videóval rendelkező kategóriák</p>";
    getMostUploadedByCategory();

    echo "<p>Legtöbb kommenttel rendelkező kategóriák</p>";
    getMostCommentedByCategory();
    ?>

</body>

</html>