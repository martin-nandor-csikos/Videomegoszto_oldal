<?php
session_start();

if (!isset($_SESSION['user_isadmin']) || $_SESSION['user_isadmin'] == 0) {
    header("Location: index.php");
}

if (isset($_SESSION['delete_success'])) {
  echo $_SESSION['delete_success'];
  unset($_SESSION['delete_success']);
}
?>

<!DOCTYPE html>
<html lang="hu">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Videó törlése - Videómegosztó</title>
</head>

<body>

<form action="./delete_page.php" method="get">
  <label for="video_cim">Videó címe:</label>
  <input type="text" name="video_cim" id="video_cim">
  <input type="submit" value="Keresés" name="video_kereses" id="video_kereses">
</form>

<table>
  <th>ID</th>
  <th>Cím</th>
  <th>Videó törlése</th>
<?php
  $cim = trim($_GET['video_cim']);

  // User keres-e cím alapján
  if (isset($_GET['video_kereses']) && $cim != "") {
    $list_videos = oci_parse($conn, "SELECT video.id, video.cim felhasznalo.nev FROM video
    INNER JOIN feltolto ON video.id = feltolto.video_id 
    INNER JOIN felhasznalo ON feltolto.felhasznalo_id = felhasznalo.id
    WHERE video.cim LIKE :cim");
    oci_bind_by_name($list_videos, ':cim', $_GET['video_cim']);
    oci_execute($list_videos);
  } else {
    $list_videos = oci_parse($conn, "SELECT video.id, video.cim felhasznalo.nev FROM video
    INNER JOIN feltolto ON video.id = feltolto.video_id 
    INNER JOIN felhasznalo ON feltolto.felhasznalo_id = felhasznalo.id");
    oci_execute($list_videos);
  }

  // Undorító HTML kiiratás
  while (oci_fetch($list_videos)) {
    echo "<tr><td>" . oci_result($list_videos, 'ID') . "</td>";
    echo "<td>" . oci_result($list_videos, 'CIM') . "</td>";
    echo '<td>
      <form action="./php/delete.php" method="POST">
        <input type="hidden" id="video_id" name="video_id" value="' . oci_result($list_videos, 'ID') . '">
        <input type="submit" value="Törlés" name="video_torles" id="video_torles">
      </form>
    </td></tr>';
  }

?>
</table>

</body>

</html>