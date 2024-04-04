<?php

require "oracle_conn.php";

session_start();

$email = trim(htmlspecialchars($_POST['reg_email']), ENT_SUBSTITUTE);
$username = trim(htmlspecialchars($_POST['reg_username']), ENT_SUBSTITUTE);
$pass = trim(htmlspecialchars($_POST['reg_pass']), ENT_SUBSTITUTE);
$pass_re = trim(htmlspecialchars($_POST['reg_pass_re']), ENT_SUBSTITUTE);

$hibak = [];

if (isset($email) && isset($username) && isset($pass) && isset($pass_re)) {
    $check_email = oci_parse($conn, "SELECT email FROM felhasznalo WHERE email = :email");
    oci_bind_by_name($check_email, ':email', $email);
    oci_execute($check_email);

    if (oci_fetch_assoc($check_email) > 0) {
        $hibak[] = "Van már ilyen email című felhasználó";
    }

    if (strlen($email) > 50) {
        $hibak[] = "Túl hosszú az email cím (Max 50 karakter)";
    }

    if (strlen($username) > 30) {
        $hibak[] = "Túl hosszú a felhasználónév (Max 30 karakter)";
    }

    if (strlen($pass) > 50) {
        $hibak[] = "Túl hosszú a jelszó (Max 50 karakter)";
    }

    if (strlen($pass) < 6) {
        $hibak[] = "Túl rövid a jelszó (Min 6 karakter)";
    }

    if ($pass != $pass_re) {
        $hibak[] = "A két megadott jelszó nem egyezik meg";
    }

    if (count($hibak) === 0) {
        // A legelső regisztrált user legyen mindig admin
        // Hatalmas security probléma, majd mögnézzük marad-e
        $check_users = oci_parse($conn, "SELECT id FROM felhasznalo");
        oci_execute($check_users);

        $hashed_pass = password_hash($pass, PASSWORD_DEFAULT);

        if (oci_fetch_assoc($check_users) > 0) {
            $insert_felhasznalo = oci_parse($conn, "INSERT INTO felhasznalo (nev, email, jelszo) VALUES (:username, :email, :hashed_pass)");
            oci_bind_by_name($insert_felhasznalo, ':username', $username);
            oci_bind_by_name($insert_felhasznalo, ':email', $email);
            oci_bind_by_name($insert_felhasznalo, ':hashed_pass', $hashed_pass);
            oci_execute($insert_felhasznalo);
        } else {
            $insert_felhasznalo = oci_parse($conn, "INSERT INTO felhasznalo (nev, email, jelszo, admin) VALUES (:username, :email, :hashed_pass, :admin)");
            oci_bind_by_name($insert_felhasznalo, ':username', $username);
            oci_bind_by_name($insert_felhasznalo, ':email', $email);
            oci_bind_by_name($insert_felhasznalo, ':hashed_pass', $hashed_pass);
            oci_bind_by_name($insert_felhasznalo, ':admin', 1);
            oci_execute($insert_felhasznalo);
        }

        $_SESSION['reg_success'] = "Sikeres regisztráció.";
        header("Location: ./../login_page.php");   
    }

    $_SESSION['hibak'] = $hibak;
    header("Location: ./../reg_page.php");
}

oci_close($conn);