<?php

require "oracle_conn.php";

session_start();

$email = trim(htmlspecialchars($_POST['modify_email']), ENT_SUBSTITUTE);
$username = trim(htmlspecialchars($_POST['modify_username']), ENT_SUBSTITUTE);
$pass = trim(htmlspecialchars($_POST['modify_pass']), ENT_SUBSTITUTE);
$pass_re = trim(htmlspecialchars($_POST['modify_pass_re']), ENT_SUBSTITUTE);
$user_id = $_SESSION['user_id'];

$hibak = [];

if (!empty($email) || !empty($username) || !empty($pass)) {
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

    if (!empty($pass) && strlen($pass) < 6) {
        $hibak[] = "Túl rövid a jelszó (Min 6 karakter)";
    }

    if ((!empty($pass) || !empty($pass_re)) && $pass != $pass_re) {
        $hibak[] = "A két megadott jelszó nem egyezik meg";
    }

    if (count($hibak) === 0) {
        $hashed_pass = password_hash($pass, PASSWORD_DEFAULT);

        $update_statement = "UPDATE FELHASZNALO SET ";
        if (!empty($username)) $update_statement .= "NEV = :username, ";
        if (!empty($email)) $update_statement .= "EMAIL = :email, ";
        if (!empty($pass)) $update_statement .= "JELSZO = :hashed_pass";
        else {
            $update_statement = substr($update_statement, 0, strrpos($update_statement, ','));
        }
        $update_statement .= " WHERE ID = :userid";

        $update_felhasznalo = oci_parse($conn, $update_statement);
        if (!empty($username)) oci_bind_by_name($update_felhasznalo, ':username', $username);
        if (!empty($email)) oci_bind_by_name($update_felhasznalo, ':email', $email);
        if (!empty($pass)) oci_bind_by_name($update_felhasznalo, ':hashed_pass', $hashed_pass);
        oci_bind_by_name($update_felhasznalo, ':userid', $user_id);
        oci_execute($update_felhasznalo);
        
        if (!empty($username)) $_SESSION['user_name'] = $username;
    } else {
        $_SESSION['hibak'] = $hibak;
    }
    header("Location: ./../user_data.php");
}

oci_close($conn);