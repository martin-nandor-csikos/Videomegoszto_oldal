<?php

require "oracle_conn.php";

session_start();

$email = trim(htmlspecialchars($_POST['user_email']), ENT_SUBSTITUTE);
$pass = trim(htmlspecialchars($_POST['user_pass']), ENT_SUBSTITUTE);

if (isset($email) && isset($pass)) {
    $check_credentials = oci_parse($conn, "SELECT id, email, nev, jelszo, admin FROM felhasznalo WHERE email = :email");
    oci_bind_by_name($check_credentials, ':email', $email);
    oci_execute($check_credentials);

    while ($row = oci_fetch_assoc($check_credentials)) {
        $passwordResult = oci_result($check_credentials, 'JELSZO');
        $idResult = oci_result($check_credentials, 'ID');
        $nameResult = oci_result($check_credentials, 'NEV');
        $adminResult = oci_result($check_credentials, 'ADMIN');

        if (password_verify($pass, $passwordResult)) {
            $_SESSION['user_id'] = $idResult;
            $_SESSION['user_name'] = $nameResult;
            $_SESSION['user_isadmin'] = $adminResult;
            header("Location: ./../index.php");
        }
    }
    
    $_SESSION['login_error'] = "Hibás felhasználónév, vagy jelszó.";
    header("Location: ./../login_page.php");
}

oci_close($conn);