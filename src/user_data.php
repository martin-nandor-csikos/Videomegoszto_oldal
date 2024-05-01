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
    <title>Fiókom adatai - Videómegosztó</title>

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
    require "php/oracle_conn.php";

    $search = oci_parse($conn, "SELECT NEV, EMAIL FROM FELHASZNALO WHERE ID = :userid");
    oci_bind_by_name($search, ":userid", $_SESSION['user_id']);
    oci_execute($search);

    while (oci_fetch($search)) {
        echo "
        <p>Név: " . oci_result($search, "NEV") . "</p>
        <p>E-mail cím: " . oci_result($search, "EMAIL") . "</p>
        ";
    }
    oci_close($conn);
    ?>

    <br>

</body>

</html>