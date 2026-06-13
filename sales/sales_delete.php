<?php
include '../includes/db.php';
include '../includes/header.php';

if ($_SESSION['role'] != 'admin') {
    header("Location: /fish_market/sales/sales_list.php");
    exit();
}

$id = intval($_GET['id']);

// Get sale details to restore stock
$sale = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM sales WHERE id = $id"));

if ($sale) {
    // Restore stock
    mysqli_query($conn, "UPDATE fish SET stock_kg = stock_kg + {$sale['quantity_kg']} WHERE id = {$sale['fish_id']}");
    // Delete sale
    mysqli_query($conn, "DELETE FROM sales WHERE id = $id");
}

header("Location: /fish_market/sales/sales_list.php?success=deleted");
exit();
?>
