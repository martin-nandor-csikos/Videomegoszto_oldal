<?php

require "oracle_conn.php";

session_start();

$id = $_POST['video_id'];

if (isset($_POST['video_torles'])) {
  // Videó törlése adatbázisból

  $_SESSION['delete_success'] = $id . " ID-vel rendelkező videó sikeresen törölve lett."; 
}

oci_close($conn);