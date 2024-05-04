<?php

require_once "oracle_conn.php";
session_start();

$hibak = [];

$comment_text = $_POST['comment_text'];
$comment_id = $_POST['comment_id'];

if (empty($comment_text)) {
    $hibak[] = "Hiba komment írása közben: üres komment szöveg";
    $_SESSION['hibak'] = $hibak;
    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit;
} else if (empty($comment_id)) {
    $hibak[] = "Hiba komment írása közben: üres komment id";
    $_SESSION['hibak'] = $hibak;
    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit;
}

$update_comment = oci_parse($conn, "UPDATE KOMMENT SET SZOVEG = :comment_text WHERE ID = :comment_id");
oci_bind_by_name($update_comment, ':comment_text', $comment_text);
oci_bind_by_name($update_comment, ':comment_id', $comment_id);
oci_execute($update_comment);

header('Location: ' . $_SERVER['HTTP_REFERER']);
oci_close($conn);