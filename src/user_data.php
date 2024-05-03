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

    if (isset($_SESSION['hibak'])) {
        foreach ($_SESSION['hibak'] as $hiba) {
            echo "<p>" . $hiba . "</p>";
        }

        unset($_SESSION['hibak']);
    }

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

    <form action="php/user_modify.php" method="post">
        <label for="modify_email">Új email cím</label>
        <input type="email" name="modify_email" id="modify_email" maxlength="50" />
        <br />

        <label for="modify_username">Új felhasználónév</label>
        <input type="text" name="modify_username" id="modify_username" maxlength="30" />
        <br />

        <label for="modify_pass">Új jelszó</label>
        <input type="password" name="modify_pass" id="modify_pass" maxlength="50" />
        <br />

        <label for="modify_pass_re">Új jelszó újra</label>
        <input type="password" name="modify_pass_re" id="modify_pass_re" maxlength="50" />
        <br />

        <input type="submit" value="Mentés">
        <br />
    </form>

    <br>

</body>

</html>