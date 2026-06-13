<?php
include '../includes/db.php';
include '../includes/header.php';

$success = isset($_GET['success']) ? $_GET['success'] : '';
$fishermen = mysqli_query($conn, "SELECT * FROM fishermen ORDER BY created_at DESC");
?>

<div class="container">
    <div class="page-header">
        <h2>🎣 Fishermen Management</h2>
        <?php if ($_SESSION['role'] == 'admin'): ?>
        <a href="/fish_market/fishermen/fishermen_add.php" class="btn btn-primary">+ Add New Fisherman</a>
        <?php endif; ?>
    </div>

    <?php if ($success == 'added'): ?>
        <div class="alert alert-success">✅ Fisherman added successfully!</div>
    <?php elseif ($success == 'updated'): ?>
        <div class="alert alert-success">✅ Fisherman updated successfully!</div>
    <?php elseif ($success == 'deleted'): ?>
        <div class="alert alert-success">✅ Fisherman deleted successfully!</div>
    <?php endif; ?>

    <div class="section">
        <table class="table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Full Name</th>
                    <th>Phone</th>
                    <th>Location</th>
                    <th>Boat Name</th>
                    <th>Date Added</th>
                    <?php if ($_SESSION['role'] == 'admin'): ?>
                    <th>Actions</th>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody>
                <?php if (mysqli_num_rows($fishermen) > 0): ?>
                <?php $i = 1; while ($row = mysqli_fetch_assoc($fishermen)): ?>
                <tr>
                    <td><?php echo $i++; ?></td>
                    <td><?php echo $row['full_name']; ?></td>
                    <td><?php echo $row['phone']; ?></td>
                    <td><?php echo $row['location']; ?></td>
                    <td><?php echo $row['boat_name'] ?? '—'; ?></td>
                    <td><?php echo date('d M Y', strtotime($row['created_at'])); ?></td>
                    <?php if ($_SESSION['role'] == 'admin'): ?>
                    <td>
                        <a href="/fish_market/fishermen/fishermen_edit.php?id=<?php echo $row['id']; ?>" class="btn btn-warning">✏️ Edit</a>
                        <a href="/fish_market/fishermen/fishermen_delete.php?id=<?php echo $row['id']; ?>" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this fisherman?')">🗑️ Delete</a>
                    </td>
                    <?php endif; ?>
                </tr>
                <?php endwhile; ?>
                <?php else: ?>
                <tr><td colspan="7" style="text-align:center;">No fishermen records found.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
