<?php

require "php/oracle_conn.php";

session_start();

$hibak = [];

if (isset($_SESSION['hibak'])) {
    foreach ($_SESSION['hibak'] as $hiba) {
        echo $hiba . "<br>";
    }

    unset($_SESSION['hibak']);
}

$video_id = $_GET["video_id"];

// Videó adatok lekérése
$search = oci_parse($conn,
    "SELECT VIDEO.ID, VIDEO.CIM, VIDEO.PATH, VIDEO.LEIRAS, FELHASZNALO.NEV, TO_CHAR(FELTOLTO.DATUM, 'YYYY. MM. DD.') AS DATUM
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
$video_path = oci_result($search, "PATH");
$video_leiras = oci_result($search, "LEIRAS");
$felhasznalo_nev = oci_result($search, "NEV");
$feltolto_datum = oci_result($search, "DATUM");

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
    "SELECT FELHASZNALO.NEV, TO_CHAR(IRO.IDO, 'YYYY. MM. DD. HH24:MI:SS') AS IDO, KOMMENT.SZOVEG
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
?>

<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php
        echo "<title>" . $video_cim . " - Videómegosztó</title>"
    ?>
</head>
<body>
<?php
    echo "
    <video height=400 controls>
        <source src='media/videos/" . $video_path . "' type='video/mp4'>
    </video><br />
    " . $video_cim . "<br />
    " . $video_leiras . "<br />
    Feltöltötte: " . $felhasznalo_nev . "<br />
    Feltöltés dátuma: " . $feltolto_datum . "<br />";
    
    if (isset($_SESSION['user_id'])) {
        if ($kedvenc){
            echo "
            <form action='PHP/like.php' method='post'>
                <input type='submit' name='unlike_video' value='Törlés kedvencekből'/><br />
                <input type='hidden' id='video_id' name='video_id' value='" . $video_id . "' />
            </form>";
        } else {
            echo "
            <form action='PHP/like.php' method='post'>
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
        echo "<div class='comment'>" . oci_result($comments, "NEV") . ": " . oci_result($comments, "SZOVEG") . "<br />
        ". oci_result($comments, "IDO") . "</div><br />";
    }
?>


</body>
</html>