<?php
// Ez csak nekem kell, hogy le tudjam generáltatni a dummy videókhoz tartozó thumbnaileket, amik már a db-ben vannak
// - Martin

require "php/oracle_conn.php";
require $_SERVER["DOCUMENT_ROOT"] . '/vendor/autoload.php';

session_start();
    $vfname = "22.mp4";
    $tfname = "22.jpg";

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