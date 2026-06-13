<?php
include '../includes/db.php';
include '../includes/header.php';

if ($_SESSION['role'] != 'admin') {
    header("Location: /fish_market/dashboard.php");
    exit();
}

$success = isset($_GET['success']) ? $_GET['success'] : '';
$users = mysqli_query($conn, "SELECT * FROM users ORDER BY created_at DESC");
?>

<div class="container">
    <div class="page-header">
        <h2>👥 User Management</h2>
        <a href="/fish_market/users/users_add.php" class="btn btn-primary">+ Add New User</a>
    </div>

    <?php if ($success == 'added'): ?>
        <div class="alert alert-success">✅ User added successfully!</div>
    <?php elseif ($success == 'deleted'): ?>
        <div class="alert alert-success">✅ User deleted successfully!</div>
    <?php endif; ?>

    <div class="section">
        <table class="table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Full Name</th>
                    <th>Username</th>
                    <th>Role</th>
                    <th>Date Added</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (mysqli_num_rows($users) > 0): ?>
                <?php $i = 1; while ($row = mysqli_fetch_assoc($users)): ?>
                <tr>
                    <td><?php echo $i++; ?></td>
                    <td><?php echo $row['full_name']; ?></td>
                    <td><?php echo $row['username']; ?></td>
                    <td>
                        <?php if ($row['role'] == 'admin'): ?>
                            <span style="background:#0077b6; color:white; padding:3px 10px; border-radius:12px; font-size:12px;">Admin</span>
                        <?php else: ?>
                            <span style="background:#2dc653; color:white; padding:3px 10px; border-radius:12px; font-size:12px;">Staff</span>
                        <?php endif; ?>
                    </td>
                    <td><?php echo date('d M Y', strtotime($row['created_at'])); ?></td>
                    <td>
                        <?php if ($row['id'] != $_SESSION['user_id']): ?>
                        <a href="/fish_market/users/users_delete.php?id=<?php echo $row['id']; ?>" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this user?')">🗑️ Delete</a>
                        <?php else: ?>
                        <span style="color:#999; font-size:13px;">Current user</span>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endwhile; ?>
                <?php else: ?>
                <tr><td colspan="6" style="text-align:center;">No users found.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
