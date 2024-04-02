<?php
session_start();

if (isset($_SESSION['hibak'])) {
    foreach ($_SESSION['hibak'] as $hiba) {
        echo $hiba . "<br>";
    }

    unset($_SESSION['hibak']);
}

if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
}
?>

<!DOCTYPE html>
<html lang="hu">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Regisztráció - Videómegosztó</title>
</head>

<body>

    <form action="php/reg.php" method="post">
        <label for="reg-email">Email cím</label>
        <input type="email" name="reg-email" id="reg-email" maxlength="50" required>

        <label for="reg-username">Felhasználónév</label>
        <input type="text" name="reg-username" id="reg-username" maxlength="30" required>

        <label for="reg-pass">Jelszó</label>
        <input type="password" name="reg-pass" id="reg-pass" maxlength="50" required>

        <label for="reg-pass-re">Jelszó újra</label>
        <input type="password" name="reg-pass-re" id="reg-pass-re" maxlength="50" required>

        <input type="submit" value="Regisztráció">
    </form>

    <a href="login_page.php">Vissza</a>
</body>

</html>