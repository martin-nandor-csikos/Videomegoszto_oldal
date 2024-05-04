<?php

require "oracle_conn.php";

session_start();

$id = $_POST['category_id'];

if (isset($_POST['delete_category'])) {
  $success;
  $delete_category = oci_parse($conn, "BEGIN :r := REMOVECATEGORY(:id); END;");
  oci_bind_by_name($delete_category, ":r", $success, -1, SQLT_INT);
  oci_bind_by_name($delete_category, ":id", $id);
  oci_execute($delete_category);
  if (!$success) {
    $_SESSION['remove_category_fail'] = 1;
  }
}

header('Location: ' . $_SERVER['HTTP_REFERER']);

oci_close($conn);