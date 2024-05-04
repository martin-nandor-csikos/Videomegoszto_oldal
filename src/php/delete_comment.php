<?php

require "oracle_conn.php";

session_start();

$id = $_POST['comment_id'];

if (isset($_POST['comment_delete'])) {
  $delete_comment = oci_parse($conn, "
    DELETE FROM KOMMENT
    WHERE ID = :id");
  oci_bind_by_name($delete_comment, ":id", $id);

  oci_execute($delete_comment);
  header('Location: ' . $_SERVER['HTTP_REFERER']);
}

oci_close($conn);