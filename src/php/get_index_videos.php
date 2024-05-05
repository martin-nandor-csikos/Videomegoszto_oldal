<?php
function getMostPopular($category) {
    require "oracle_conn.php";

    if ($category == null) {
        $search = oci_parse($conn,
            "SELECT * FROM (
                SELECT VIDEO.ID, VIDEO.CIM, VIDEO.VIEWS, VIDEO.THUMBNAIL, FELHASZNALO.NEV, FELTOLTO.DATUM
                FROM VIDEO
                INNER JOIN FELTOLTO
                ON VIDEO.ID = FELTOLTO.VIDEO_ID
                INNER JOIN FELHASZNALO
                ON FELHASZNALO.ID = FELTOLTO.FELHASZNALO_ID
                ORDER BY VIDEO.VIEWS DESC
            ) WHERE ROWNUM <= 5");
        oci_execute($search);
    } else {
        $search = oci_parse($conn,
            "SELECT * FROM (
                SELECT VIDEO.ID, VIDEO.CIM, VIDEO.VIEWS, VIDEO_KATEGORIA.KATEGORIA_ID, VIDEO.THUMBNAIL, FELHASZNALO.NEV, FELTOLTO.DATUM
                FROM VIDEO
                INNER JOIN FELTOLTO
                ON VIDEO.ID = FELTOLTO.VIDEO_ID
                INNER JOIN FELHASZNALO
                ON FELHASZNALO.ID = FELTOLTO.FELHASZNALO_ID
                INNER JOIN VIDEO_KATEGORIA
                ON VIDEO.ID = VIDEO_KATEGORIA.VIDEO_ID
                WHERE VIDEO_KATEGORIA.KATEGORIA_ID = :kat_id
                ORDER BY VIDEO.VIEWS DESC
            ) WHERE ROWNUM <= 5");
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
            Feltöltötte: <a href='channel_page.php?user=" . oci_result($search, "NEV") . "'>" . oci_result($search, "NEV") . "</a><br />
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
    oci_close($conn);
}

function getLatest($category) {
    require "oracle_conn.php";

    if ($category == null) {
        $search = oci_parse($conn,
            "SELECT * FROM (
                SELECT VIDEO.ID, VIDEO.CIM, VIDEO.VIEWS, VIDEO.THUMBNAIL, FELHASZNALO.NEV, FELTOLTO.DATUM
                FROM VIDEO
                INNER JOIN FELTOLTO
                ON VIDEO.ID = FELTOLTO.VIDEO_ID
                INNER JOIN FELHASZNALO
                ON FELHASZNALO.ID = FELTOLTO.FELHASZNALO_ID
                ORDER BY FELTOLTO.DATUM DESC
            ) WHERE ROWNUM <= 5");
        oci_execute($search);
    } else {
        $search = oci_parse($conn,
            "SELECT * FROM (
                SELECT VIDEO.ID, VIDEO.CIM, VIDEO.VIEWS, VIDEO_KATEGORIA.KATEGORIA_ID, VIDEO.THUMBNAIL, FELHASZNALO.NEV, FELTOLTO.DATUM
                FROM VIDEO
                INNER JOIN FELTOLTO
                ON VIDEO.ID = FELTOLTO.VIDEO_ID
                INNER JOIN FELHASZNALO
                ON FELHASZNALO.ID = FELTOLTO.FELHASZNALO_ID
                INNER JOIN VIDEO_KATEGORIA
                ON VIDEO.ID = VIDEO_KATEGORIA.VIDEO_ID
                WHERE VIDEO_KATEGORIA.KATEGORIA_ID = :kat_id
                ORDER BY FELTOLTO.DATUM DESC
            ) WHERE ROWNUM <= 5");
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
            Feltöltötte: <a href='channel_page.php?user=" . oci_result($search, "NEV") . "'>" . oci_result($search, "NEV") . "</a><br />
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
    oci_close($conn);
}

function getVideosByUser($user) {
    require "oracle_conn.php";

    $search = oci_parse($conn,
        "SELECT VIDEO.ID, VIDEO.CIM, VIDEO.VIEWS, VIDEO.THUMBNAIL, FELHASZNALO.NEV, FELTOLTO.DATUM
        FROM VIDEO
        INNER JOIN FELTOLTO
        ON VIDEO.ID = FELTOLTO.VIDEO_ID
        INNER JOIN FELHASZNALO
        ON FELHASZNALO.ID = FELTOLTO.FELHASZNALO_ID
        WHERE FELHASZNALO.NEV = :username
        ORDER BY FELTOLTO.DATUM DESC
        ");
    oci_bind_by_name($search, ":username", $user);
    oci_execute($search);


    echo "
    <div style='display: flex; margin: 10px; gap: 20px;'>
    ";
    while (oci_fetch($search)) {
        echo "
        <div class='search_result' id='" . oci_result($search, "ID") . "_vid' style='width: 200px;'>
            <img src='/media/thumbnails/" . oci_result($search, "THUMBNAIL") . "' height=100 width=100/ ><br />
            " . oci_result($search, "CIM") . "<br />
            Nézettség: " . oci_result($search, "VIEWS") . "<br />
            Feltöltötte: <a href='channel_page.php?user=" . oci_result($search, "NEV") . "'>" . oci_result($search, "NEV") . "</a><br />
            Ekkor: " . oci_result($search, "DATUM") . "<br />
        </div>
        ";
    }
    echo "
    </div>
    ";
    oci_close($conn);
}

function getMostUploaded() {
    require "oracle_conn.php";

    $search = oci_parse($conn,
        "SELECT COUNT(FELTOLTO.VIDEO_ID) AS FELTOLTOTT_DB, FELHASZNALO.NEV
        FROM FELTOLTO
        INNER JOIN FELHASZNALO
        ON FELTOLTO.FELHASZNALO_ID = FELHASZNALO.ID
        GROUP BY FELHASZNALO.NEV
        ORDER BY FELTOLTOTT_DB DESC
        FETCH FIRST 5 ROWS ONLY
        ");
    oci_execute($search);


    echo "
    <div style='display: flex; margin: 10px; gap: 20px;'>
    ";
    while (oci_fetch($search)) {
        echo "
        <p><a href='channel_page.php?user=" . oci_result($search, "NEV") . "'>" . oci_result($search, "NEV") . "</a> - " . oci_result($search, "FELTOLTOTT_DB") . "</p>
        ";
    }
    echo "
    </div>
    ";
    oci_close($conn);
}

function getMostCommented() {
    require "oracle_conn.php";

    $search = oci_parse($conn,
        "SELECT COUNT(IRO.KOMMENT_ID) AS KOMMENT_DB, FELHASZNALO.NEV
        FROM IRO
        INNER JOIN FELHASZNALO
        ON IRO.FELHASZNALO_ID = FELHASZNALO.ID
        GROUP BY FELHASZNALO.NEV
        ORDER BY KOMMENT_DB DESC
        FETCH FIRST 10 ROWS ONLY
        ");
    oci_execute($search);


    echo "
    <div style='display: flex; margin: 10px; gap: 20px;'>
    ";
    while (oci_fetch($search)) {
        echo "
        <p><a href='channel_page.php?user=" . oci_result($search, "NEV") . "'>" . oci_result($search, "NEV") . "</a> - " . oci_result($search, "KOMMENT_DB") . "</p>
        ";
    }
    echo "
    </div>
    ";
    oci_close($conn);
}

function getMostUploadedToday() {
    require "oracle_conn.php";

    $search = oci_parse($conn,
    "SELECT COUNT(FELTOLTO.VIDEO_ID) AS FELTOLTOTT_DB, FELHASZNALO.NEV
    FROM FELTOLTO
    INNER JOIN FELHASZNALO ON FELTOLTO.FELHASZNALO_ID = FELHASZNALO.ID
    WHERE TRUNC(FELTOLTO.DATUM) = TRUNC(SYSDATE)
    GROUP BY FELHASZNALO.NEV
    HAVING COUNT(FELTOLTO.VIDEO_ID) > 0
    ORDER BY FELTOLTOTT_DB DESC
    FETCH FIRST 5 ROWS ONLY
    ");
    oci_execute($search);



    echo "
    <div style='display: flex; margin: 10px; gap: 20px;'>
    ";
    while (oci_fetch($search)) {
        echo "
        <p><a href='channel_page.php?user=" . oci_result($search, "NEV") . "'>" . oci_result($search, "NEV") . "</a> - " . oci_result($search, "FELTOLTOTT_DB") . "</p>
        ";
    }
    echo "
    </div>
    ";
    oci_close($conn);
}

function getMostCommentedToday() {
    require "oracle_conn.php";

    $search = oci_parse($conn,
        "SELECT COUNT(IRO.KOMMENT_ID) AS KOMMENT_DB, FELHASZNALO.NEV
        FROM IRO
        INNER JOIN FELHASZNALO ON IRO.FELHASZNALO_ID = FELHASZNALO.ID
        WHERE TRUNC(IRO.IDO) = TRUNC(SYSDATE)
        GROUP BY FELHASZNALO.NEV
        ORDER BY KOMMENT_DB DESC
        FETCH FIRST 3 ROWS ONLY
        ");
    oci_execute($search);


    echo "
    <div style='display: flex; margin: 10px; gap: 20px;'>
    ";
    while (oci_fetch($search)) {
        echo "
        <p><a href='channel_page.php?user=" . oci_result($search, "NEV") . "'>" . oci_result($search, "NEV") . "</a> - " . oci_result($search, "KOMMENT_DB") . "</p>
        ";
    }
    echo "
    </div>
    ";
    oci_close($conn);
}

function getMostUploadedByCategory() {
    require "oracle_conn.php";

    $search = oci_parse($conn,
    "SELECT COUNT(FELTOLTO.VIDEO_ID) AS FELTOLTOTT_DB, KATEGORIA.CIM
    FROM FELTOLTO
    INNER JOIN FELHASZNALO ON FELTOLTO.FELHASZNALO_ID = FELHASZNALO.ID
    INNER JOIN VIDEO_KATEGORIA ON VIDEO_KATEGORIA.VIDEO_ID = FELTOLTO.VIDEO_ID
    INNER JOIN KATEGORIA ON VIDEO_KATEGORIA.KATEGORIA_ID = KATEGORIA.ID
    GROUP BY KATEGORIA.CIM
    HAVING COUNT(FELTOLTO.VIDEO_ID) > 0
    ORDER BY COUNT(FELTOLTO.VIDEO_ID) DESC
    FETCH FIRST 5 ROWS ONLY
    ");
    oci_execute($search);

    echo "
    <div style='display: flex; margin: 10px; gap: 20px;'>
    ";
    while (oci_fetch($search)) {
        echo 
        "<p>" . oci_result($search, "CIM") . " - " . oci_result($search, "FELTOLTOTT_DB") . "</p>
        ";
    }
    echo "
    </div>
    ";
    oci_close($conn);
}

function getMostCommentedByCategory() {
    require "oracle_conn.php";

    $search = oci_parse($conn,
        "SELECT COUNT(IRO.KOMMENT_ID) AS KOMMENT_DB, KATEGORIA.CIM
        FROM IRO
        INNER JOIN EREDET ON IRO.KOMMENT_ID = EREDET.KOMMENT_ID
        INNER JOIN VIDEO_KATEGORIA ON VIDEO_KATEGORIA.VIDEO_ID = EREDET.VIDEO_ID
        INNER JOIN KATEGORIA ON VIDEO_KATEGORIA.KATEGORIA_ID = KATEGORIA.ID
        GROUP BY KATEGORIA.CIM
        ORDER BY KOMMENT_DB DESC
        FETCH FIRST 3 ROWS ONLY
        ");
    oci_execute($search);


    echo "
    <div style='display: flex; margin: 10px; gap: 20px;'>
    ";
    while (oci_fetch($search)) {
        echo 
        "<p>" . oci_result($search, "CIM") . " - " . oci_result($search, "KOMMENT_DB") . "</p>
        ";
    }
    echo "
    </div>
    ";
    oci_close($conn);
}