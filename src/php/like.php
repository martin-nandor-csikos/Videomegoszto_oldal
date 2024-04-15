<?php

require_once "oracle_conn.php";
session_start();

$video_id = $_POST['video_id'];
$felhasznalo_id = $_SESSION['user_id'];

$insert_kedvenc = oci_parse($conn, "INSERT INTO KEDVENC (VIDEO_ID, FELHASZNALO_ID) VALUES (:video_id, :felhasznalo_id)");
oci_bind_by_name($insert_kedvenc, ":video_id", $video_id);
oci_bind_by_name($insert_kedvenc, ":felhasznalo_id", $felhasznalo_id);
oci_execute($insert_kedvenc);

header('Location: ' . $_SERVER['HTTP_REFERER']);
