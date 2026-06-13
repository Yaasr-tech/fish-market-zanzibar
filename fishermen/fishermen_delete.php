<?php
include '../includes/db.php';
include '../includes/header.php';

if ($_SESSION['role'] != 'admin') {
    header("Location: /fish_market/fishermen/fishermen_list.php");
    exit();
}

$id = intval($_GET['id']);
mysqli_query($conn, "DELETE FROM fishermen WHERE id = $id");
header("Location: /fish_market/fishermen/fishermen_list.php?success=deleted");
exit();
?>
