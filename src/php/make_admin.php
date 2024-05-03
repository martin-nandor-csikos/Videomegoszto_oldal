<?php

require "oracle_conn.php";

session_start();

$user_id = $_POST['user_id'];

$hashed_pass = password_hash($pass, PASSWORD_DEFAULT);

$update_admin = oci_parse($conn, "UPDATE FELHASZNALO SET ADMIN = 1 WHERE ID = :userid");
oci_bind_by_name($update_admin, ':userid', $user_id);
oci_execute($update_admin);

header("Location: ./../delete_page.php");

oci_close($conn);