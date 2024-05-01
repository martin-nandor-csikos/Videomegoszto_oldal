<?php
session_start();
if (!isset($_GET['user'])) {
    //header("Location: index.php");
}
?>

<!DOCTYPE html>
<html lang="hu">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php echo "<title>" . $_GET['user'] . " - Videómegosztó</title>"; ?>
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
    echo "<p>" . $_GET['user'] . " videói</p>";
    ?>

    <form action="channel_page.php" method="get" enctype="multipart/form-data">
        <input type="text" name="term" id="term" required placeholder="Videók keresése a csatornán" style="width: 200px;"/>
        <input type="submit" name="search_term_submit" value="Keresés" />
        <input type="hidden" name="user" value=<?php echo $_GET['user']; ?>>
    </form>

    <?php
    if (isset($_GET["search_term_submit"])) {
        require "php/oracle_conn.php";
        require("php/search_term.php");

        search_term_by_user($conn, $_GET['user']);
        unset($_POST['search_term_submit']);
    }
    ?>

    <?php
    require_once "php/get_index_videos.php";

    getVideosByUser($_GET['user']);

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
    ?>

</body>

</html>