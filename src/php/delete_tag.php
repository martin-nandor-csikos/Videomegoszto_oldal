<?php

require "oracle_conn.php";

session_start();

$id = $_POST['tag_id'];

if (isset($_POST['delete_tag'])) {
  $delete_tag = oci_parse($conn, "DELETE FROM CIMKE WHERE ID = :id");
  oci_bind_by_name($delete_tag, ":id", $id);
  oci_execute($delete_tag);
}

header('Location: ../delete_page.php');

oci_close($conn);