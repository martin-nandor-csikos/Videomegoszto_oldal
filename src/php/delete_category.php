<?php

require "oracle_conn.php";

session_start();

$id = $_POST['category_id'];

if (isset($_POST['delete_category'])) {
  $delete_category = oci_parse($conn, "
    DELETE FROM KATEGORIA
    WHERE ID = :id");
  oci_bind_by_name($delete_category, ":id", $id);

  oci_execute($delete_category);
  header('Location: ' . $_SERVER['HTTP_REFERER']);
}

oci_close($conn);