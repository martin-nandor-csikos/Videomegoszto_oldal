<?php

require_once "oracle_conn.php";
session_start();

$hibak = [];

$category_name = $_POST['category_name'];
$category_id = $_POST['category_id'];

if (empty($category_name)) {
    $hibak[] = "Hiba kategória módosítása közben: üres kategória név";
    $_SESSION['hibak'] = $hibak;
    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit;
}

$update_category = oci_parse($conn, "UPDATE KATEGORIA SET CIM = :category_name WHERE ID = :category_id");
oci_bind_by_name($update_category, ':category_name', $category_name);
oci_bind_by_name($update_category, ':category_id', $category_id);
oci_execute($update_category);

header('Location: ' . $_SERVER['HTTP_REFERER']);
oci_close($conn);