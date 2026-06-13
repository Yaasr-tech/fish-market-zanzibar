<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: /fish_market/login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fish Market Management System</title>
    <link rel="stylesheet" href="/fish_market/assets/css/style.css">
</head>
<body>
<nav class="navbar">
    <div class="nav-brand">
        🐟 Fish Market — Zanzibar
    </div>
    <div class="nav-links">
        <a href="/fish_market/dashboard.php">🏠 Dashboard</a>
        <a href="/fish_market/fish/fish_list.php">🐠 Fish</a>
        <a href="/fish_market/sales/sales_list.php">💰 Sales</a>
        <a href="/fish_market/fishermen/fishermen_list.php">🎣 Fishermen</a>
        <a href="/fish_market/reports/reports.php">📊 Reports</a>
        <?php if ($_SESSION['role'] == 'admin'): ?>
        <a href="/fish_market/users/users_list.php">👥 Users</a>
        <?php endif; ?>
        <a href="/fish_market/logout.php" class="btn-logout">🚪 Logout</a>
    </div>
    <div class="nav-user">
        👤 <?php echo $_SESSION['full_name']; ?>
    </div>
</nav>
