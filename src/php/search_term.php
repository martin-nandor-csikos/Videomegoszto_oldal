<?php
function search_term($conn, $count) {

    $term = $_GET["term"];

    $search = oci_parse($conn,
        "SELECT VIDEO.ID, VIDEO.CIM, VIDEO.THUMBNAIL, FELHASZNALO.NEV, FELTOLTO.DATUM
        FROM VIDEO
        INNER JOIN FELTOLTO
        ON VIDEO.ID = FELTOLTO.VIDEO_ID
        INNER JOIN FELHASZNALO
        ON FELHASZNALO.ID = FELTOLTO.FELHASZNALO_ID
        WHERE VIDEO.CIM LIKE '%" . $term . "%'
        ORDER BY VIDEO.ID
        FETCH FIRST :count ROWS ONLY");
    oci_bind_by_name($search, ":count", $count);
    oci_execute($search);

    while (oci_fetch($search)) {
        echo "
        <div class='search_result' id='" . oci_result($search, "ID") . "_vid'>
            <img src='/media/thumbnails/" . oci_result($search, "THUMBNAIL") . "' height=100 width=100/ ><br />
            " . oci_result($search, "CIM") . "<br />
            Feltöltötte: " . oci_result($search, "NEV") . "<br />
            Ekkor: " . oci_result($search, "DATUM") . "<br />
        </div>
        ";
    }

    echo "
    <script>
    let results = document.getElementsByClassName('search_result');
    Array.from(results).forEach((res) => {
        let video_id = res.id.split('_')[0];
        res.addEventListener('click', function() {
            window.location.href = '/video.php?video_id=' + video_id;
        });
    });
    </script>
    ";
}