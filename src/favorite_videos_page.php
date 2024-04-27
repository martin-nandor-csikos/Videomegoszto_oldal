<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
}
?>

<!DOCTYPE html>
<html lang="hu">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kedvenc videók - Videómegosztó</title>

    <style>
        .search_result {
            border: solid black 1px;
            cursor: pointer;
            margin-bottom: 10px;
        }
    </style>
</head>

<body>

    <?php require_once "menu.php"; ?>
    
    <form action="index.php" method="get" enctype="multipart/form-data">
    <input type="text" name="term" id="term" required />
    <input type="submit" name="search_term_submit" value="Keresés" />
    </form>

    <?php
    if (isset($_GET["search_term_submit"])) {
        header("Location: index.php");
    }

    require "php/get_favorite_videos.php";
    ?>

    <br>

</body>

</html>