<?php
function getMostPopular($category) {
    require "oracle_conn.php";

    if ($category == null) {
        $search = oci_parse($conn,
            "SELECT VIDEO.ID, VIDEO.CIM, VIDEO.VIEWS, VIDEO.THUMBNAIL, FELHASZNALO.NEV, FELTOLTO.DATUM
            FROM VIDEO
            INNER JOIN FELTOLTO
            ON VIDEO.ID = FELTOLTO.VIDEO_ID
            INNER JOIN FELHASZNALO
            ON FELHASZNALO.ID = FELTOLTO.FELHASZNALO_ID
            ORDER BY VIDEO.VIEWS DESC
            FETCH FIRST 10 ROWS ONLY");
        oci_execute($search);
    } else {
        $search = oci_parse($conn,
            "SELECT VIDEO.ID, VIDEO.CIM, VIDEO.VIEWS, VIDEO_KATEGORIA.KATEGORIA_ID, VIDEO.THUMBNAIL, FELHASZNALO.NEV, FELTOLTO.DATUM
            FROM VIDEO
            INNER JOIN FELTOLTO
            ON VIDEO.ID = FELTOLTO.VIDEO_ID
            INNER JOIN FELHASZNALO
            ON FELHASZNALO.ID = FELTOLTO.FELHASZNALO_ID
            INNER JOIN VIDEO_KATEGORIA
            ON VIDEO.ID = VIDEO_KATEGORIA.VIDEO_ID
            WHERE VIDEO_KATEGORIA.KATEGORIA_ID = :kat_id
            ORDER BY VIDEO.VIEWS DESC
            FETCH FIRST 10 ROWS ONLY");
        oci_bind_by_name($search, ":kat_id", $category);
        oci_execute($search);
    } 

    echo "
    <div style='display: flex; margin: 10px; gap: 20px;'>
    ";
    while (oci_fetch($search)) {
        echo "
        <div class='search_result' id='" . oci_result($search, "ID") . "_vid' style='width: 200px;'>
            <img src='/media/thumbnails/" . oci_result($search, "THUMBNAIL") . "' height=100 width=100/ ><br />
            " . oci_result($search, "CIM") . "<br />
            Nézettség: " . oci_result($search, "VIEWS") . "<br />
            Feltöltötte: " . oci_result($search, "NEV") . "<br />
            Ekkor: " . oci_result($search, "DATUM") . "<br />
        </div>
        ";
    }
    echo "
    </div>
    ";

    /*
    echo "
    <script>
    let results = document.getElementsByClassName('search_result');
    Array.from(results).forEach((res) => {
        let video_id = res.id.split('_')[0];
        res.addEventListener('click', function() {
            window.location.href = '/video_page.php?video_id=' + video_id;
        });
    });
    </script>
    ";
    */
}

function getLatest($category) {
    require "oracle_conn.php";

    if ($category == null) {
        $search = oci_parse($conn,
            "SELECT VIDEO.ID, VIDEO.CIM, VIDEO.VIEWS, VIDEO.THUMBNAIL, FELHASZNALO.NEV, FELTOLTO.DATUM
            FROM VIDEO
            INNER JOIN FELTOLTO
            ON VIDEO.ID = FELTOLTO.VIDEO_ID
            INNER JOIN FELHASZNALO
            ON FELHASZNALO.ID = FELTOLTO.FELHASZNALO_ID
            ORDER BY FELTOLTO.DATUM DESC
            FETCH FIRST 10 ROWS ONLY");
        oci_execute($search);
    } else {
        $search = oci_parse($conn,
            "SELECT VIDEO.ID, VIDEO.CIM, VIDEO.VIEWS, VIDEO_KATEGORIA.KATEGORIA_ID, VIDEO.THUMBNAIL, FELHASZNALO.NEV, FELTOLTO.DATUM
            FROM VIDEO
            INNER JOIN FELTOLTO
            ON VIDEO.ID = FELTOLTO.VIDEO_ID
            INNER JOIN FELHASZNALO
            ON FELHASZNALO.ID = FELTOLTO.FELHASZNALO_ID
            INNER JOIN VIDEO_KATEGORIA
            ON VIDEO.ID = VIDEO_KATEGORIA.VIDEO_ID
            WHERE VIDEO_KATEGORIA.KATEGORIA_ID = :kat_id
            ORDER BY FELTOLTO.DATUM DESC
            FETCH FIRST 10 ROWS ONLY");
        oci_bind_by_name($search, ":kat_id", $category);
        oci_execute($search);
    } 

    echo "
    <div style='display: flex; margin: 10px; gap: 20px;'>
    ";
    while (oci_fetch($search)) {
        echo "
        <div class='search_result' id='" . oci_result($search, "ID") . "_vid' style='width: 200px;'>
            <img src='/media/thumbnails/" . oci_result($search, "THUMBNAIL") . "' height=100 width=100/ ><br />
            " . oci_result($search, "CIM") . "<br />
            Nézettség: " . oci_result($search, "VIEWS") . "<br />
            Feltöltötte: " . oci_result($search, "NEV") . "<br />
            Ekkor: " . oci_result($search, "DATUM") . "<br />
        </div>
        ";
    }
    echo "
    </div>
    ";

    /*
    echo "
    <script>
    let results = document.getElementsByClassName('search_result');
    Array.from(results).forEach((res) => {
        let video_id = res.id.split('_')[0];
        res.addEventListener('click', function() {
            window.location.href = '/video_page.php?video_id=' + video_id;
        });
    });
    </script>
    ";
    */
}