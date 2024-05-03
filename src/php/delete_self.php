<?php

require "oracle_conn.php";

session_start();

$id = $_SESSION['user_id'];

if (isset($_POST['self_torles'])) {
  $delete_user = oci_parse($conn, "
    DELETE FROM FELHASZNALO
    WHERE ID = :id");
  oci_bind_by_name($delete_user, ":id", $id);

  oci_execute($delete_user);
  header("Location: ./logout.php");
}

oci_close($conn);