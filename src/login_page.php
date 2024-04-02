<?php
session_start();

// Hibák kiiratása, ha vannak
if (isset($_SESSION['login_error'])) {
    echo $_SESSION['login_error'];
    unset($_SESSION['login_error']);
}

if (isset($_SESSION['reg_success'])) {
    echo $_SESSION['reg_success'];
    unset($_SESSION['reg_success']);
}

// Ha már be van lépve a user, akkor ne tudja megnyitni újra az oldalt
if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
}

if (isset($_SESSION['sikeres_reg'])) {
    echo "<p>Sikeres regisztráció!</p>";
    unset($_SESSION['sikeres_reg']);
}
?>

<!DOCTYPE html>
<html lang="hu">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bejelentkezés - Videómegosztó</title>
</head>

<body>

    <form action="php/login.php" method="post">
        <label for="user_email">Email cím</label>
        <input type="email" name="user_email" id="user_email" maxlength="50" required>

        <label for="user_pass">Jelszó</label>
        <input type="password" name="user_pass" id="user_pass" maxlength="50" required>

        <input type="submit" value="Belépés">
    </form>

    <!-- Ezek majd átalakulhatnak gombra, ha kell -->
    <a href="index.php">Vissza</a>
    <a href="reg_page.php">Regisztráció</a>

</body>

</html>