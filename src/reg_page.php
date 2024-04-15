<?php
session_start();

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
    <?php
    if (isset($_SESSION['hibak'])) {
        foreach ($_SESSION['hibak'] as $hiba) {
            echo "<p>" . $hiba . "</p>";
        }

        unset($_SESSION['hibak']);
    }
    ?>
    
    <form action="php/reg.php" method="post">
        <label for="reg_email">Email cím</label>
        <input type="email" name="reg_email" id="reg_email" maxlength="50" required>

        <label for="reg_username">Felhasználónév</label>
        <input type="text" name="reg_username" id="reg_username" maxlength="30" required>

        <label for="reg_pass">Jelszó</label>
        <input type="password" name="reg_pass" id="reg_pass" maxlength="50" required>

        <label for="reg_pass_re">Jelszó újra</label>
        <input type="password" name="reg_pass_re" id="reg_pass_re" maxlength="50" required>

        <input type="submit" value="Regisztráció">
    </form>

    <a href="login_page.php">Vissza</a>
</body>

</html>