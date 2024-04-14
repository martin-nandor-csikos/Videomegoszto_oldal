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

$search = oci_parse($conn,
    "SELECT VIDEO.ID, VIDEO.CIM, VIDEO.PATH, FELHASZNALO.NEV, FELTOLTO.DATUM
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

<a href="index.php">Vissza</a>

</body>
</html>