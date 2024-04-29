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
        <input type="submit" value="Film és animáció" name="1">
        <input type="submit" value="Autók és járművek" name="2">
        <input type="submit" value="Zene" name="3">
        <input type="submit" value="Művészet" name="4">
        <input type="submit" value="Sport" name="5">
        <input type="submit" value="Utazás" name="6">
        <input type="submit" value="Kultúra" name="7">
        <input type="submit" value="Videójáték" name="8">
        <input type="submit" value="Személyes vagy vlog" name="9">
        <input type="submit" value="Komédia" name="10">
        <input type="submit" value="Szórakozás" name="11">
        <input type="submit" value="Hírek és politika" name="12">
        <input type="submit" value="Oktatás" name="13">
        <input type="submit" value="Tudomány" name="14">
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

        for ($i = 0; $i < 14; $i++) { 
            if (isset($_POST[strval($i)])) {
                getMostPopular($i);
                break;
            }
        }

        foreach ($_POST as $name => $val)
        {
            echo "<p>Legújabb '" . htmlspecialchars($val) . "' videók</p>";
        }

        for ($i = 0; $i < 14; $i++) { 
            if (isset($_POST[strval($i)])) {
                getLatest($i);
                break;
            }
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
    ?>

</body>

</html>