<?php

require "oracle_conn.php";
require $_SERVER["DOCUMENT_ROOT"] . '/vendor/autoload.php';

session_start();

$hibak = [];

$id = $_POST['video_id'];
$title = $_POST['title'];
$desc = $_POST['desc'];
if (isset($_POST['category'])) $category = $_POST['category'];
else $category = "";
if (!empty($_POST['tags'])) $tags = explode(", ", $_POST['tags']);
else $tags = [];
if (!empty($_POST['rtags'])) $rtags = explode(", ", $_POST['rtags']);
else $rtags = [];

if (!empty($title) || !empty($desc)){
    $update_statement = "UPDATE VIDEO SET ";
    if (!empty($title)) $update_statement .= "CIM = :title, ";
    if (!empty($desc)) $update_statement .= "LEIRAS = :descript";
    else {
        $update_statement = substr($update_statement, 0, strrpos($update_statement, ','));
    }
    $update_statement .= " WHERE ID = :video_id";
    $update_video = oci_parse($conn, $update_statement);
    if (!empty($title)) oci_bind_by_name($update_video, ':title', $title);
    if (!empty($desc)) oci_bind_by_name($update_video, ':descript', $desc);
    oci_bind_by_name($update_video, ':video_id', $id);
    oci_execute($update_video);
}

if (!empty($category)) {
    $update_category = oci_parse($conn, "UPDATE VIDEO_KATEGORIA SET KATEGORIA_ID = :kategoria_id WHERE VIDEO_ID = :video_id");
    oci_bind_by_name($update_category, ':kategoria_id', $category);
    oci_bind_by_name($update_category, ':video_id', $id);
    oci_execute($update_category);
}

foreach ($tags as $tag) {
    $add_tag = oci_parse($conn, "BEGIN ADDTAG(:video_id, :tag); END;");
    oci_bind_by_name($add_tag, ':video_id', $id);
    oci_bind_by_name($add_tag, ':tag', $tag);
    oci_execute($add_tag);
}

$_SESSION['hibak'] = $hibak;

header("Location: /video_page.php?video_id=" . $id);
oci_close($conn);