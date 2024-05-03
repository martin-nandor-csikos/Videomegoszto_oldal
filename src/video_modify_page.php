<?php

require "php/oracle_conn.php";

session_start();

if (isset($_SESSION['hibak'])) {
    foreach ($_SESSION['hibak'] as $hiba) {
        echo $hiba . "<br>";
    }

    unset($_SESSION['hibak']);
}

$video_id = $_POST["video_id"];

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

<?php require_once "menu.php"; ?>

<?php
    $get_categories = oci_parse($conn, "SELECT * FROM KATEGORIA");
    oci_execute($get_categories);
    $categories = [];
    while (oci_fetch($get_categories)) {
        $categories[oci_result($get_categories, "ID")] = oci_result($get_categories, "CIM");
    }

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
?>

<form action="php/video_modify.php" method="post">
    <label for="title"><span>Új cím:</span></label>
    <input type="text" name="title" id="title" />
    <br />
    <label for="desc"><span>Új leírás:</span></label>
    <input type="text" name="desc" id="desc" />
    <br />
    <label for="category"><span>Új kategória:</span></label>
    <select name="category" id="category">
    <option value="" disabled selected hidden>Válassz kategóriát!</option>
    <?php
        foreach ($categories as $id => $category) {
            echo "<option value='" . $id . "'>". $category . "</option>";
        }
    ?>
    
    </select>
    <br />
    <label for="tags"><span>Címkék hozzáadása (pl.: Kreatív, DIY):</span></label>
    <input type="text" name="tags" id="tags" />
    <br />
    <label for="rtags"><span>Címkék eltávolítása (pl.: Kreatív, DIY):</span></label>
    <input type="text" name="rtags" id="rtags" />
    <br />
    <?php
    echo "<input type='hidden' id='video_id' name='video_id' value='" . $video_id . "' />";
    ?>
    <input type="submit" name="submit" value="Módosítás" />
</form>

<a href="$_SERVER['HTTP_REFERER']">Vissza</a><br />

</body>
</html>