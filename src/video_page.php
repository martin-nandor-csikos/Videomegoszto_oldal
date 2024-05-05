<?php

require "php/oracle_conn.php";

session_start();

$hibak = [];

$video_id = $_GET["video_id"];

// Nézettség frissítése
$update_views = oci_parse($conn, 
    "UPDATE VIDEO
    SET VIEWS = VIEWS + 1
    WHERE ID = :id"
);
oci_bind_by_name($update_views, ":id", $video_id);
oci_execute($update_views);

// Videó adatok lekérése
$search = oci_parse($conn,
    "SELECT VIDEO.CIM, VIDEO.VIEWS, VIDEO.PATH, VIDEO.LEIRAS, FELHASZNALO.NEV, FELHASZNALO.ID, TO_CHAR(FELTOLTO.DATUM, 'YYYY. MM. DD.') AS DATUM
    FROM VIDEO
    INNER JOIN FELTOLTO
    ON VIDEO.ID = FELTOLTO.VIDEO_ID
    INNER JOIN FELHASZNALO
    ON FELHASZNALO.ID = FELTOLTO.FELHASZNALO_ID
    WHERE VIDEO.ID = :video_id");
oci_bind_by_name($search, ":video_id", $video_id);
oci_execute($search);
if (!oci_fetch($search)) {
    echo "Videó nem található!";
}

$video_cim = oci_result($search, "CIM");
$video_nezettseg = oci_result($search, "VIEWS");
$video_path = oci_result($search, "PATH");
$video_leiras = oci_result($search, "LEIRAS");
$felhasznalo_nev = oci_result($search, "NEV");
$feltolto_datum = oci_result($search, "DATUM");
$feltolto_id = oci_result($search, "ID");

// Kategória lekérése
$search_category = oci_parse($conn, "
    SELECT CIM
    FROM KATEGORIA
    INNER JOIN VIDEO_KATEGORIA
    ON KATEGORIA.ID = VIDEO_KATEGORIA.KATEGORIA_ID
    WHERE VIDEO_KATEGORIA.VIDEO_ID = :video_id");
oci_bind_by_name($search_category, "video_id", $video_id);
oci_execute($search_category);
oci_fetch($search_category);
$kategoria = oci_result($search_category, "CIM");

// Címkék lekérése
$search_tags = oci_parse($conn, "
    SELECT CIM
    FROM CIMKE
    INNER JOIN VIDEO_CIMKE
    ON CIMKE.ID = VIDEO_CIMKE.CIMKE_ID
    WHERE VIDEO_CIMKE.VIDEO_ID = :video_id");
oci_bind_by_name($search_tags, "video_id", $video_id);
oci_execute($search_tags);
$cimkek = [];
while (oci_fetch($search_tags)) {
    $cimkek[] = oci_result($search_tags, "CIM");
}

// Kedvenc állapot lekérése
if (isset($_SESSION['user_id'])){
    $felhasznalo_id = $_SESSION['user_id'];

    $check_like = oci_parse($conn,"SELECT *
        FROM KEDVENC
        WHERE VIDEO_ID = :video_id
        AND FELHASZNALO_ID = :felhasznalo_id");
    oci_bind_by_name($check_like, "video_id", $video_id);
    oci_bind_by_name($check_like, "felhasznalo_id", $felhasznalo_id);
    oci_execute($check_like);
    // $kedvenc true, ha a videó a felhasználó kedvencei között van
    $kedvenc = oci_fetch($check_like);
}

// Komment adatok lekérése
$comments = oci_parse($conn,
    "SELECT FELHASZNALO.NEV, FELHASZNALO.ID AS FID, TO_CHAR(IRO.IDO, 'YYYY. MM. DD. HH24:MI:SS') AS IDO, KOMMENT.SZOVEG, KOMMENT.ID
    FROM KOMMENT
    INNER JOIN EREDET
    ON KOMMENT.ID = EREDET.KOMMENT_ID
    INNER JOIN IRO
    ON KOMMENT.ID = IRO.KOMMENT_ID
    INNER JOIN FELHASZNALO
    ON FELHASZNALO.ID = IRO.FELHASZNALO_ID
    WHERE EREDET.VIDEO_ID = :video_id
    ORDER BY IRO.IDO DESC");
oci_bind_by_name($comments, ":video_id", $video_id);
oci_execute($comments);

// Count comments
$count_comments = oci_parse($conn,
    "SELECT COUNT(*) AS COMMENT_COUNT
    FROM KOMMENT
    INNER JOIN EREDET
    ON KOMMENT.ID = EREDET.KOMMENT_ID
    WHERE EREDET.VIDEO_ID = :video_id"
);
oci_bind_by_name($count_comments, ":video_id", $video_id);
oci_execute($count_comments);
oci_fetch($count_comments);
$comment_count = oci_result($count_comments, "COMMENT_COUNT");

// Legtöbb kommentet író csatorna a videó alatt
$most_commented = oci_parse($conn,
    "SELECT COUNT(IRO.KOMMENT_ID) AS KOMMENT_DB, FELHASZNALO.NEV, EREDET.VIDEO_ID
    FROM IRO
    INNER JOIN FELHASZNALO ON IRO.FELHASZNALO_ID = FELHASZNALO.ID
    INNER JOIN EREDET ON EREDET.KOMMENT_ID = IRO.KOMMENT_ID
    WHERE EREDET.VIDEO_ID = :video_id
    GROUP BY FELHASZNALO.NEV, EREDET.VIDEO_ID
    ORDER BY KOMMENT_DB DESC
    FETCH FIRST 3 ROWS ONLY"
);
oci_bind_by_name($most_commented, ":video_id", $video_id);
oci_execute($most_commented);

// Count likes
$count_likes = oci_parse($conn,
    "SELECT COUNT(*) AS LIKE_COUNT
    FROM KEDVENC
    WHERE VIDEO_ID = :video_id"
);
oci_bind_by_name($count_likes, ":video_id", $video_id);
oci_execute($count_likes);
oci_fetch($count_likes);
$like_count = oci_result($count_likes, "LIKE_COUNT");
?>

<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php
        echo "<title>" . $video_cim . " - Videómegosztó</title>"
    ?>
    <script>
        function editComment(video_id, comment_id) {
            console.log(video_id);
            console.log(comment_id);
            let original = document.getElementById(comment_id).innerHTML;
            document.getElementById(comment_id).innerHTML = "" +
            "<form action='php/comment_modify.php' method='post'>" +
            "<label for='comment_text'>Új szöveg:</label>" +
            "<input type='text' name='comment_text' id='comment_text' required />" +
            "<input type='hidden' id='comment_id' name='comment_id' value='" + comment_id + "' />" +
            "<input type='submit' name='comment_submit' value='Küldés' />" +
            "</form>" +
            "<button onclick='location.reload()'>Mégse</button>" +
            "";
        }
    </script>
</head>
<body>

<?php require_once "menu.php"; ?>

<?php
    if (isset($_SESSION['hibak'])) {
        foreach ($_SESSION['hibak'] as $hiba) {
            echo $hiba . "<br>";
        }
        unset($hiba);

        unset($_SESSION['hibak']);
    }

    echo "
    <video height=400 controls>
        <source src='media/videos/" . $video_path . "' type='video/mp4'>
    </video><br />
    " . $video_cim . "<br />
    " . $video_leiras . "<br />";

    if (isset($_SESSION['user_id']) && $feltolto_id === $_SESSION['user_id']) echo "
    <form action='video_modify_page.php' method='post'>
        <input type='submit' name='video_modify' value='Videó adatok módosítása'/><br />
        <input type='hidden' id='video_id' name='video_id' value='" . $video_id . "' />
    </form>
    ";

    echo "Nézettség: " . $video_nezettseg . "<br />
    Feltöltötte: <a href='channel_page.php?user=" . $felhasznalo_nev . "'>" . $felhasznalo_nev . "</a><br />
    Feltöltés dátuma: " . $feltolto_datum . "<br />
    Kategória: " . $kategoria . "<br />";

    if ($cimkek) echo "Címkék: " . implode(", ", $cimkek) . "<br />";
    else echo "A videónak nincs címkéje.<br />";

    echo "Kommentek: " . $comment_count . "<br />";
    echo "Videó alatt a legtöbb kommentet író: ";
    while (oci_fetch($most_commented)) {
        echo "<a href='channel_page.php?user=" . oci_result($most_commented, "NEV") . "'>|" . oci_result($most_commented, "NEV") . " - " . oci_result($most_commented, "KOMMENT_DB") . " | </a>";
    }
    echo "<br>";
    echo "Like-ok: " . $like_count . "<br />";
    
    
    if (isset($_SESSION['user_id'])) {
        if ($kedvenc){
            echo "
            <form action='php/like.php' method='post'>
                <input type='submit' name='unlike_video' value='Törlés kedvencekből'/><br />
                <input type='hidden' id='video_id' name='video_id' value='" . $video_id . "' />
            </form>";
        } else {
            echo "
            <form action='php/like.php' method='post'>
                <input type='submit' name='like_video' value='Kedvenc'/><br />
                <input type='hidden' id='video_id' name='video_id' value='" . $video_id . "' />
            </form>";
        }
    }
?>

<a href="index.php">Vissza</a><br />

<?php
    echo "
    <form action='php/comment.php' method='post'>
        <label for='comment_text'>Komment írás:</label>
        <input type='text' name='comment_text' id='comment_text' required />
        <input type='hidden' id='video_id' name='video_id' value='" . $video_id . "' />
        <input type='submit' name='comment_submit' value='Küldés' />
    </form>";

    echo "Kommentek:<br />";
    while (oci_fetch($comments)) {
        echo "<div class='comment' id='" . oci_result($comments, "ID") . "'>" . oci_result($comments, "NEV") . ": " . oci_result($comments, "SZOVEG") . "<br />
        ". oci_result($comments, "IDO") . "<br />";
        if (isset($_SESSION['user_id']) && oci_result($comments, "FID") === $_SESSION['user_id']) {
            echo "
            <button onclick='editComment(" . $video_id . ", " . oci_result($comments, "ID") . ")'>Módosítás</button>
            <br />";
        }
        if (isset($_SESSION['user_id']) && (oci_result($comments, "FID") === $_SESSION['user_id'] || $_SESSION['user_isadmin'] == 1)) {
            echo "<form action='php/delete_comment.php' method='post' onsubmit=\"return confirm('Biztosan törölni szeretnéd a kommentet?');\">
            <input type='hidden' id='comment_id' name='comment_id' value='" . oci_result($comments, "ID") . "' />
            <input type='submit' name='comment_delete' value='Törlés' />
            <br /></form>";
        }
        echo "</div>";
    }
?>


</body>
</html>