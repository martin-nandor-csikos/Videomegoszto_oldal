<?php

// Csatlakozás az Oracle adatbázishoz
$conn = oci_connect('system', 'oracle', 'video_oracle/XE');
if (!$conn) {
    $e = oci_error();
    trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
}