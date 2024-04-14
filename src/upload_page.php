<?php

require "php/oracle_conn.php";

session_start();

if (isset($_SESSION['hibak'])) {
    foreach ($_SESSION['hibak'] as $hiba) {
        echo $hiba . "<br>";
    }

    unset($_SESSION['hibak']);
}

if (isset($_SESSION['success'])) {
    echo "Sikeres feltöltés";

    unset($_SESSION['success']);
}

// Csak bejelentkezett felhasználó tölthet fel videót
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
}

$get_categories = oci_parse($conn, "SELECT * FROM KATEGORIA");
oci_execute($get_categories);
$categories = [];
while (oci_fetch($get_categories)) {
    $categories[oci_result($get_categories, "ID")] = oci_result($get_categories, "CIM");
}
?>

<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Videó feltöltés - Videómegosztó</title>
</head>
<body>

<form action="php/upload.php" method="post" enctype="multipart/form-data">
<label for="file"><span>Videó (.mp4):</span></label>
<input type="file" name="file" id="file" required />
<br />
<label for="title"><span>Cím:</span></label>
<input type="text" name="title" id="title" required />
<br />
<label for="desc"><span>Leírás:</span></label>
<input type="text" name="desc" id="desc" required />
<br />
<label for="category"><span>Kategória:</span></label>
<select name="category" id="category">
<option value="" disabled selected hidden>Válassz kategóriát!</option>
<?php
    foreach ($categories as $id => $category) {
        echo "<option value='" . $id . "'>". $category . "</option>";
    }
?>

</select>
<br />
<label for="tags"><span>Címkék (pl.: Kreatív, DIY):</span></label>
<input type="text" name="tags" id="tags" />
<br />
<input type="submit" name="submit" value="Feltöltés" />
</form>

<a href="index.php">Vissza</a>

</body>
</html>