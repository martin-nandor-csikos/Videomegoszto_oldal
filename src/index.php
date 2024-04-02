<?php
session_start();

if (isset($_SESSION['sikeres_login'])) {
    echo "<p>Üdvözlet, " + $_SESSION['user_username'] + "!</p>";
}
?>

<!DOCTYPE html>
<html lang="hu">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Főoldal - Videómegosztó</title>
</head>

<body>

    <?php
    if (isset($_SESSION['user_id'])) {
        // Kedvelt videók gomb
        // Feltöltés gomb
        // Kijelentkezés gomb
    } else {
        echo "<a href=\"login_page.php\">Bejelentkezés</a>";
    }
    ?>

    <!-- Search bar helye -->

    <!-- Kategóriák felsorolva -->

    <!-- Legnépszerűbb videók -->

    <!-- Legújabb videók -->

</body>

</html>