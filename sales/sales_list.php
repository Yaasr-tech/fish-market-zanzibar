<?php
include '../includes/db.php';
include '../includes/header.php';

$success = isset($_GET['success']) ? $_GET['success'] : '';
$sales = mysqli_query($conn, "SELECT s.*, f.name as fish_name, fm.full_name as fisherman_name, u.username as recorded_by FROM sales s JOIN fish f ON s.fish_id = f.id JOIN fishermen fm ON s.fisherman_id = fm.id JOIN users u ON s.user_id = u.id ORDER BY s.created_at DESC");
?>

<div class="container">
    <div class="page-header">
        <h2>💰 Sales Management</h2>
        <a href="/fish_market/sales/sales_add.php" class="btn btn-primary">+ Record New Sale</a>
    </div>

    <?php if ($success == 'added'): ?>
        <div class="alert alert-success">✅ Sale recorded successfully!</div>
    <?php elseif ($success == 'deleted'): ?>
        <div class="alert alert-success">✅ Sale deleted successfully!</div>
    <?php endif; ?>

    <div class="section">
        <table class="table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Date</th>
                    <th>Fish</th>
                    <th>Fisherman</th>
                    <th>Quantity (kg)</th>
                    <th>Price/kg (TZS)</th>
                    <th>Total (TZS)</th>
                    <th>Recorded By</th>
                    <?php if ($_SESSION['role'] == 'admin'): ?>
                    <th>Actions</th>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody>
                <?php if (mysqli_num_rows($sales) > 0): ?>
                <?php $i = 1; while ($row = mysqli_fetch_assoc($sales)): ?>
                <tr>
                    <td><?php echo $i++; ?></td>
                    <td><?php echo date('d M Y', strtotime($row['sale_date'])); ?></td>
                    <td><?php echo $row['fish_name']; ?></td>
                    <td><?php echo $row['fisherman_name']; ?></td>
                    <td><?php echo $row['quantity_kg']; ?> kg</td>
                    <td>TZS <?php echo number_format($row['price_per_kg']); ?></td>
                    <td>TZS <?php echo number_format($row['total_price']); ?></td>
                    <td><?php echo $row['recorded_by']; ?></td>
                    <?php if ($_SESSION['role'] == 'admin'): ?>
                    <td>
                        <a href="/fish_market/sales/sales_delete.php?id=<?php echo $row['id']; ?>" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this sale?')">🗑️ Delete</a>
                    </td>
                    <?php endif; ?>
                </tr>
                <?php endwhile; ?>
                <?php else: ?>
                <tr><td colspan="9" style="text-align:center;">No sales recorded yet.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
