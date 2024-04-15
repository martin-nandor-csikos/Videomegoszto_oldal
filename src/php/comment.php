<?php

require_once "oracle_conn.php";
session_start();

$comment_text = $_POST['comment_text'];
$video_id = $_POST['video_id'];

$get_next_id = oci_parse($conn, "SELECT KOMMENT_SEQ.NEXTVAL FROM DUAL");
oci_execute($get_next_id);
oci_fetch($get_next_id);
$id = oci_result($get_next_id, "NEXTVAL");

$felhasznalo_id = $_SESSION['user_id'];

$insert_new_comment = oci_parse($conn, "INSERT INTO KOMMENT (ID, SZOVEG) VALUES (:id, :szoveg)");
oci_bind_by_name($insert_new_comment, ':id', $id);
oci_bind_by_name($insert_new_comment, ':szoveg', $comment_text);
oci_execute($insert_new_comment);

$link_user = oci_parse($conn, "INSERT INTO IRO (FELHASZNALO_ID, KOMMENT_ID, IDO) VALUES (:felhasznalo_id, :komment_id, sysdate)");
oci_bind_by_name($link_user, ':felhasznalo_id', $felhasznalo_id);
oci_bind_by_name($link_user, ':komment_id', $id);
oci_execute($link_user);
    
$link_video = oci_parse($conn, "INSERT INTO EREDET (KOMMENT_ID, VIDEO_ID) VALUES (:komment_id, :video_id)");
oci_bind_by_name($link_video, ':komment_id', $id);
oci_bind_by_name($link_video, ':video_id', $video_id);
oci_execute($link_video);

header('Location: ' . $_SERVER['HTTP_REFERER']);