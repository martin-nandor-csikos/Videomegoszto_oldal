<?php
session_start();

echo "<a href=\"index.php\">Főoldal</a><br />";

if (isset($_SESSION['user_id'])) {
    echo "<p>Üdvözöljük, " . $_SESSION['user_name'] . "!</p>";
    echo "<a href=\"user_data.php\">Fiókom adatai</a><br />";
    if ($_SESSION['user_isadmin'] == 0) {
        echo "<a href=\"videos_by_current_user.php\">Feltöltött videóim</a><br />";
        echo "<a href=\"upload_page.php\">Videó feltöltés</a><br />";
        echo "<a href=\"favorite_videos_page.php\">Kedvenc videók</a><br />";
    } else {
        echo "<a href=\"delete_page.php\">Videó törlése</a><br />";
    }
    echo "<a href=\"php/logout.php\">Kijelentkezés</a>";
} else {
    echo "<a href=\"login_page.php\">Bejelentkezés</a>";
}

echo "<br>";