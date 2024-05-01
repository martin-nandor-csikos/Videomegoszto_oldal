<?php
require "oracle_conn.php";
$search = oci_parse($conn,
    "SELECT VIDEO.ID, VIDEO.CIM, KEDVENC.VIDEO_ID, KEDVENC.FELHASZNALO_ID, VIDEO.VIEWS, VIDEO.THUMBNAIL, FELHASZNALO.NEV, FELTOLTO.DATUM
    FROM VIDEO
    INNER JOIN FELTOLTO
    ON VIDEO.ID = FELTOLTO.VIDEO_ID
    INNER JOIN FELHASZNALO
    ON FELHASZNALO.ID = FELTOLTO.FELHASZNALO_ID
    INNER JOIN KEDVENC
    ON KEDVENC.VIDEO_ID = VIDEO.ID
    WHERE KEDVENC.FELHASZNALO_ID = :id
    ORDER BY VIDEO.ID DESC
    ");
oci_bind_by_name($search, ":id", $_SESSION['user_id']);
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
        Feltöltötte: " . oci_result($search, "NEV") . "<br />
        Ekkor: " . oci_result($search, "DATUM") . "<br />
    </div>
    ";
}
echo "
</div>
";


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
oci_close($conn);