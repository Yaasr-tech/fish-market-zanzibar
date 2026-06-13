<?php
include '../includes/db.php';
include '../includes/header.php';

if ($_SESSION['role'] != 'admin') {
    header("Location: /fish_market/dashboard.php");
    exit();
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $full_name = mysqli_real_escape_string($conn, $_POST['full_name']);
    $username  = mysqli_real_escape_string($conn, $_POST['username']);
    $password  = $_POST['password'];
    $role      = mysqli_real_escape_string($conn, $_POST['role']);

    if (empty($full_name) || empty($username) || empty($password)) {
        $error = "Please fill in all required fields!";
    } elseif (strlen($password) < 6) {
        $error = "Password must be at least 6 characters!";
    } else {
        // Check if username exists
        $check = mysqli_fetch_assoc(mysqli_query($conn, "SELECT id FROM users WHERE username = '$username'"));
        if ($check) {
            $error = "Username already exists! Please choose another.";
        } else {
            $hashed = password_hash($password, PASSWORD_DEFAULT);
            $sql = "INSERT INTO users (full_name, username, password, role) VALUES ('$full_name', '$username', '$hashed', '$role')";
            if (mysqli_query($conn, $sql)) {
                header("Location: /fish_market/users/users_list.php?success=added");
                exit();
            } else {
                $error = "Error: " . mysqli_error($conn);
            }
        }
    }
}
?>

<div class="container">
    <div class="page-header">
        <h2>👥 Add New User</h2>
        <a href="/fish_market/users/users_list.php" class="btn btn-warning">← Back to List</a>
    </div>

    <?php if ($error): ?>
        <div class="alert alert-error">❌ <?php echo $error; ?></div>
    <?php endif; ?>

    <div class="section">
        <form method="POST">
            <div class="form-group">
                <label>Full Name *</label>
                <input type="text" name="full_name" placeholder="e.g. Fatuma Ali" required>
            </div>
            <div class="form-group">
                <label>Username *</label>
                <input type="text" name="username" placeholder="e.g. fatuma" required>
            </div>
            <div class="form-group">
                <label>Password * (min 6 characters)</label>
                <input type="password" name="password" placeholder="Enter password" required>
            </div>
            <div class="form-group">
                <label>Role *</label>
                <select name="role" required>
                    <option value="staff">Staff</option>
                    <option value="admin">Admin</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">💾 Save User</button>
            <a href="/fish_market/users/users_list.php" class="btn btn-warning">Cancel</a>
        </form>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
