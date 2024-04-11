<?php

require "oracle_conn.php";

session_start();

$id = $_POST['video_id'];

if (isset($_POST['video_torles'])) {
  $delete_feltolto = oci_parse($conn, "DELETE FROM feltolto WHERE video_id = :id");
  oci_bind_by_name($delete_feltolto, ':id', $id);

  $delete_eredet = oci_parse($conn, "DELETE FROM eredet WHERE video_id = :id");
  oci_bind_by_name($delete_eredet, ':id', $id);

  $delete_kedvenc = oci_parse($conn, "DELETE FROM kedvenc WHERE video_id = :id");
  oci_bind_by_name($delete_kedvenc, ':id', $id);

  $delete_kedvenc = oci_parse($conn, "DELETE FROM kedvenc WHERE video_id = :id");
  oci_bind_by_name($delete_kedvenc, ':id', $id);

  $delete_video = oci_parse($conn, "DELETE FROM video WHERE id = :id");
  oci_bind_by_name($delete_video, ':id', $id);

  if (oci_execute($delete_feltolto) && oci_execute($delete_eredet) && oci_execute($delete_kedvenc) && oci_execute($delete_video)) {
    $_SESSION['delete_success'] = $id . " ID-vel rendelkező videó sikeresen törölve lett.";
    header("Location: ./../delete_page.php");
  } else {
    $_SESSION['delete_error'] = "Hiba a videó törlésével: a törlés meghiúsult.";
    header("Location: ./../delete_page.php");
  }
}

oci_close($conn);