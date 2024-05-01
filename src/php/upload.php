<?php

require "oracle_conn.php";
require $_SERVER["DOCUMENT_ROOT"] . '/vendor/autoload.php';

session_start();

$hibak = [];
$allowedExts = array("mp4");
$extension = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);

$title = $_POST['title'];
$desc = $_POST['desc'];
$category = $_POST['category'];
if (!empty($_POST['tags'])) $tags = explode(", ", $_POST['tags']);
else $tags = [];

// Kötelező adatok ellenőrzése
if (empty($title)) $hibak[] = "Add meg a videó címét!";
else if (empty($desc)) $hibak[] = "Add meg a videó leírását!";
else if (empty($category)) $hibak[] = "Add meg a videó kategóriáját!";

// Max 100MB és mp4
else if (($_FILES["file"]["size"] > 104857600) || !in_array($extension, $allowedExts)) {
    $hibak[] = "Helytelen fájl!";
} else if ($_FILES["file"]["error"] > 0) {
    $hibak[] = "Fájl feltöltés hibakód: " . $_FILES["file"]["error"] . "<br />";
} else {
    // Nincs hiba
    $get_next_id = oci_parse($conn, "SELECT VIDEO_SEQ.NEXTVAL FROM DUAL");
    oci_execute($get_next_id);
    oci_fetch($get_next_id);
    $id = oci_result($get_next_id, "NEXTVAL");
    $vfname = $id . ".mp4";
    $tfname = $id . ".jpg";
    $insert_new_video = oci_parse($conn, "INSERT INTO VIDEO (ID, CIM, LEIRAS, PATH, THUMBNAIL) VALUES (:id, :title, :descript, :vfname, :tfname)");
    oci_bind_by_name($insert_new_video, ':id', $id);
    oci_bind_by_name($insert_new_video, ':title', $title);
    oci_bind_by_name($insert_new_video, ':descript', $desc);
    oci_bind_by_name($insert_new_video, ':vfname', $vfname);
    oci_bind_by_name($insert_new_video, ':tfname', $tfname);
    oci_execute($insert_new_video);
    
    $link_category = oci_parse($conn, "INSERT INTO VIDEO_KATEGORIA (VIDEO_ID, KATEGORIA_ID) VALUES (:video_id, :kategoria_id)");
    oci_bind_by_name($link_category, ':video_id', $id);
    oci_bind_by_name($link_category, ':kategoria_id', $category);
    oci_execute($link_category);
    
    $link_user = oci_parse($conn, "INSERT INTO FELTOLTO (FELHASZNALO_ID, VIDEO_ID, DATUM) VALUES (:felhasznalo_id, :video_id, sysdate)");
    oci_bind_by_name($link_user, ':felhasznalo_id', $_SESSION['user_id']);
    oci_bind_by_name($link_user, ':video_id', $id);
    oci_execute($link_user);

    foreach ($tags as $tag) {
        $check_tag = oci_parse($conn, "SELECT ID FROM CIMKE WHERE CIM LIKE :cim");
        oci_bind_by_name($check_tag, ':cim', $tag);
        oci_execute($check_tag);

        if (oci_fetch($check_tag)) {
            // Ha létező tag, felhasználjuk az ID-jét
            $tag_id = oci_result($check_tag, "ID");
        } else {
            // Ha nem létező tag, létrehozunk egyet, és annak használjuk az ID-jét
            $get_next_tag_id = oci_parse($conn, "SELECT CIMKE_SEQ.NEXTVAL FROM DUAL");
            oci_execute($get_next_tag_id);
            oci_fetch($get_next_tag_id);
            $tag_id = oci_result($get_next_tag_id, "NEXTVAL");

            $insert_new_tag = oci_parse($conn, "INSERT INTO CIMKE (ID, CIM) VALUES (:id, :cim)");
            oci_bind_by_name($insert_new_tag, ':id', $tag_id);
            oci_bind_by_name($insert_new_tag, ':cim', $tag);
            oci_execute($insert_new_tag);
        }

        $link_tag = oci_parse($conn, "INSERT INTO VIDEO_CIMKE (VIDEO_ID, CIMKE_ID) VALUES (:video_id, :cimke_id)");
        oci_bind_by_name($link_tag, ':video_id', $id);
        oci_bind_by_name($link_tag, ':cimke_id', $tag_id);
        oci_execute($link_tag);
    }

    move_uploaded_file($_FILES["file"]["tmp_name"],
    $_SERVER["DOCUMENT_ROOT"] . "/media/videos/" . $vfname);

    $movie = $_SERVER["DOCUMENT_ROOT"] . "/media/videos/" . $vfname;
    $ffprobe = FFMpeg\FFProbe::create();
    $sec = intdiv($ffprobe
           ->streams($movie)
           ->videos()                   
           ->first()                  
           ->get('duration'), 10);
    
    $ffmpeg = FFMpeg\FFMpeg::create();
    $video = $ffmpeg->open($movie);
    $frame = $video->frame(FFMpeg\Coordinate\TimeCode::fromSeconds($sec));
    $frame->save($_SERVER["DOCUMENT_ROOT"] . "/media/thumbnails/" . $tfname);
    
    $_SESSION['success'] = TRUE;
}

$_SESSION['hibak'] = $hibak;

header("Location: ../upload_page.php");
oci_close($conn);