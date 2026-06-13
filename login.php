<?php
session_start();
if (isset($_SESSION['user_id'])) {
    header("Location: /fish_market/dashboard.php");
    exit();
}

include 'includes/db.php';
$error = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE username = '$username'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) == 1) {
        $user = mysqli_fetch_assoc($result);
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id']   = $user['id'];
            $_SESSION['username']  = $user['username'];
            $_SESSION['full_name'] = $user['full_name'];
            $_SESSION['role']      = $user['role'];
            header("Location: /fish_market/dashboard.php");
            exit();
        } else {
            $error = "❌ Incorrect password!";
        }
    } else {
        $error = "❌ Username not found!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — Fish Market Zanzibar</title>
    <link rel="stylesheet" href="/fish_market/assets/css/style.css">
</head>
<body class="login-body">
<div class="login-container">
    <div class="login-box">
        <h1>🐟 Fish Market</h1>
        <h3>Zanzibar Management System</h3>
        <?php if ($error): ?>
            <div class="alert alert-error"><?php echo $error; ?></div>
        <?php endif; ?>
        <form method="POST">
            <div class="form-group">
                <label>Username</label>
                <input type="text" name="username" placeholder="Enter username" required>
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" placeholder="Enter password" required>
            </div>
            <button type="submit" class="btn-login">🔐 Login</button>
        </form>
    </div>
</div>
</body>
</html>
