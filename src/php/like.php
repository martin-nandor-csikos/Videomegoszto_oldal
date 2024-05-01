<?php

require_once "oracle_conn.php";
session_start();
$hibak = [];

$video_id = $_POST['video_id'];
$felhasznalo_id = $_SESSION['user_id'];

if (empty($video_id)) {
    $hibak[] = "Hiba kedvencekhez adás közben: üres videó azonosító";
    $_SESSION['hibak'] = $hibak;
    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit;
}

if (isset($_POST['like_video'])) {
    $insert_kedvenc = oci_parse($conn, "INSERT INTO KEDVENC (VIDEO_ID, FELHASZNALO_ID) VALUES (:video_id, :felhasznalo_id)");
    oci_bind_by_name($insert_kedvenc, ":video_id", $video_id);
    oci_bind_by_name($insert_kedvenc, ":felhasznalo_id", $felhasznalo_id);
    @$execute = oci_execute($insert_kedvenc);
    if (!$execute && oci_error($insert_kedvenc)['code'] == 2291) {
        $hibak[] = "Hiba kedvencekhez adás közben: nem létező videó azonosító";
        $_SESSION['hibak'] = $hibak;
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit;
    }
} else if (isset($_POST['unlike_video'])) {
    $delete_kedvenc = oci_parse($conn, "
        DELETE FROM KEDVENC
        WHERE VIDEO_ID = :video_id
        AND FELHASZNALO_ID = :felhasznalo_id");
    oci_bind_by_name($delete_kedvenc, "video_id", $video_id);
    oci_bind_by_name($delete_kedvenc, "felhasznalo_id", $felhasznalo_id);
    oci_execute($delete_kedvenc);
}

header('Location: ' . $_SERVER['HTTP_REFERER']);
oci_close($conn);