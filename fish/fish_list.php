<?php
include '../includes/db.php';
include '../includes/header.php';

$success = isset($_GET['success']) ? $_GET['success'] : '';
$fish_list = mysqli_query($conn, "SELECT * FROM fish ORDER BY created_at DESC");
?>

<div class="container">
    <div class="page-header">
        <h2>🐠 Fish Management</h2>
        <?php if ($_SESSION['role'] == 'admin'): ?>
        <a href="/fish_market/fish/fish_add.php" class="btn btn-primary">+ Add New Fish</a>
        <?php endif; ?>
    </div>

    <?php if ($success == 'added'): ?>
        <div class="alert alert-success">✅ Fish added successfully!</div>
    <?php elseif ($success == 'updated'): ?>
        <div class="alert alert-success">✅ Fish updated successfully!</div>
    <?php elseif ($success == 'deleted'): ?>
        <div class="alert alert-success">✅ Fish deleted successfully!</div>
    <?php endif; ?>

    <div class="section">
        <table class="table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Fish Name</th>
                    <th>Category</th>
                    <th>Price per kg (TZS)</th>
                    <th>Stock (kg)</th>
                    <th>Date Added</th>
                    <?php if ($_SESSION['role'] == 'admin'): ?>
                    <th>Actions</th>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody>
                <?php if (mysqli_num_rows($fish_list) > 0): ?>
                <?php $i = 1; while ($row = mysqli_fetch_assoc($fish_list)): ?>
                <tr>
                    <td><?php echo $i++; ?></td>
                    <td><?php echo $row['name']; ?></td>
                    <td><?php echo $row['category']; ?></td>
                    <td>TZS <?php echo number_format($row['price_per_kg']); ?></td>
                    <td>
                        <?php if ($row['stock_kg'] <= 10): ?>
                            <span style="color:red; font-weight:bold;"><?php echo $row['stock_kg']; ?> kg ⚠️</span>
                        <?php else: ?>
                            <?php echo $row['stock_kg']; ?> kg
                        <?php endif; ?>
                    </td>
                    <td><?php echo date('d M Y', strtotime($row['created_at'])); ?></td>
                    <?php if ($_SESSION['role'] == 'admin'): ?>
                    <td>
                        <a href="/fish_market/fish/fish_edit.php?id=<?php echo $row['id']; ?>" class="btn btn-warning">✏️ Edit</a>
                        <a href="/fish_market/fish/fish_delete.php?id=<?php echo $row['id']; ?>" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this fish?')">🗑️ Delete</a>
                    </td>
                    <?php endif; ?>
                </tr>
                <?php endwhile; ?>
                <?php else: ?>
                <tr><td colspan="7" style="text-align:center;">No fish records found.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
