<?php
session_start();
session_destroy();
header("Location: /fish_market/login.php");
exit();
?>
