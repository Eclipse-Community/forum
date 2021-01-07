<?php

$ua = $_SERVER['HTTP_USER_AGENT'];

$lastKnownBrowser .= "<!-- ".htmlspecialchars($ua)." -->";

if ($_COOKIE['forcelayout'] == 1) $mobileLayout = true;
else if ($_COOKIE['forcelayout'] == -1) $mobileLayout = false;

?>
