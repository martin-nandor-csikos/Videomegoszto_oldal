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
if (isset($_POST['comment_submit'])) {
    require "php/comment.php";
    
    $comment_text = $_POST["comment_text"];
    comment_submit($conn, $comment_text, $video_id);
    unset($_POST['comment_submit']);
}


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
    Feltöltés dátuma: " . $feltolto_datum . "<br />
    <form action='video_page.php?video_id=" . $video_id . "' method='post'>
        <label for='comment_text'><span>Komment írás:</span></label>
        <input type='text' name='comment_text' id='comment_text' required />
        <br />
        <input type='submit' name='comment_submit' value='Küldés' />
    </form>
    Kommentek:<br />";
?>

<a href="index.php">Vissza</a>

<?php
    while (oci_fetch($comments)) {
        echo "<div class='comment'>" . oci_result($comments, "NEV") . ": " . oci_result($comments, "SZOVEG") . "<br />
        ". oci_result($comments, "IDO") . "</div><br />";
    }
?>


</body>
</html>