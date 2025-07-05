<?php
session_start();
session_destroy();
header("Location: login.php");
header("Cache-Control: no-cache, no-store, must-revalidate ");
header("Pragma: no-cache");
header("Expires: 0");
exit();
?>