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
    <title>Feltöltött videóim - Videómegosztó</title>

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
    
    <form action="videos_by_current_user.php" method="get" enctype="multipart/form-data">
        <input type="text" name="term" id="term" required placeholder="Videók keresése a csatornán" style="width: 200px;"/>
        <input type="submit" name="search_term_submit" value="Keresés" />
        <input type="hidden" name="user" value=<?php echo $_SESSION['user_name']; ?>>
    </form>

    <?php
    if (isset($_GET["search_term_submit"])) {
        require "php/oracle_conn.php";
        require("php/search_term.php");

        search_term_by_user($conn, $_SESSION['user_name']);
        unset($_POST['search_term_submit']);
    }

    require "php/get_user_uploaded_videos.php";
    ?>

    <br>

</body>

</html>