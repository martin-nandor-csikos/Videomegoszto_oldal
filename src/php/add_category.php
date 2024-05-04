<?php

require "oracle_conn.php";

session_start();

$category = $_POST['category_name'];

if (isset($_POST['add_category'])) {
  $add_category = oci_parse($conn, "
    INSERT INTO KATEGORIA (CIM) VALUES (:category)");
  oci_bind_by_name($add_category, ":category", $category);

  oci_execute($add_category);
  header('Location: ' . $_SERVER['HTTP_REFERER']);
}

oci_close($conn);