<?php

// Csatlakozás az Oracle adatbázishoz
// Kommenteljétek ki a változót, ha más paramétereket akartok

$conn = oci_connect('system', 'oracle', 'video_oracle/XE');
// $conn = oci_connect('system', 'oracle', 'localhost/XE');

if (!$conn) {
    $e = oci_error();
    trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
}