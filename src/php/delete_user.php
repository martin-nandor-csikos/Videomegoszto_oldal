<?php

require "oracle_conn.php";

session_start();

$id = $_POST['user_id'];

if (isset($_POST['user_torles'])) {
  $delete_user = oci_parse($conn, "
    DELETE FROM FELHASZNALO
    WHERE ID = :id");
  oci_bind_by_name($delete_user, ":id", $id);

  if (oci_execute($delete_user)) {
    $_SESSION['delete_success'] = $id . " ID-vel rendelkező felhasználó sikeresen törölve lett.";
    header("Location: ./../delete_page.php");
  } else {
    $_SESSION['delete_error'] = "Hiba a felhasználó törlésével: a törlés meghiúsult.";
    header("Location: ./../delete_page.php");
  }
}

oci_close($conn);