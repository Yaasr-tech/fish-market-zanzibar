<?php
include 'includes/db.php';
include 'includes/header.php';

$total_fish = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM fish"))['total'];
$total_fishermen = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM fishermen"))['total'];
$today = date('Y-m-d');
$today_sales = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total, SUM(total_price) as revenue FROM sales WHERE sale_date = '$today'"));
$total_revenue = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(total_price) as total FROM sales"))['total'];
$recent_sales = mysqli_query($conn, "SELECT s.*, f.name as fish_name, fm.full_name as fisherman_name FROM sales s JOIN fish f ON s.fish_id = f.id JOIN fishermen fm ON s.fisherman_id = fm.id ORDER BY s.created_at DESC LIMIT 5");
$top_fish = mysqli_query($conn, "SELECT f.name, SUM(s.quantity_kg) as total_kg, SUM(s.total_price) as total_revenue FROM sales s JOIN fish f ON s.fish_id = f.id GROUP BY f.id ORDER BY total_revenue DESC LIMIT 5");
?>

<div class="container">
    <h2>🏠 Dashboard</h2>
    <p class="subtitle">Welcome, <?php echo $_SESSION['full_name']; ?>! 👋</p>

    <!-- KPI Cards -->
    <div class="cards-grid">
        <div class="card card-blue">
            <div class="card-icon">🐠</div>
            <div class="card-info">
                <h3><?php echo $total_fish; ?></h3>
                <p>Fish Types</p>
            </div>
        </div>
        <div class="card card-green">
            <div class="card-icon">🎣</div>
            <div class="card-info">
                <h3><?php echo $total_fishermen; ?></h3>
                <p>Fishermen</p>
            </div>
        </div>
        <div class="card card-orange">
            <div class="card-icon">💰</div>
            <div class="card-info">
                <h3><?php echo $today_sales['total']; ?></h3>
                <p>Today's Sales</p>
            </div>
        </div>
        <div class="card card-teal">
            <div class="card-icon">📈</div>
            <div class="card-info">
                <h3>TZS <?php echo number_format($total_revenue ?? 0); ?></h3>
                <p>Total Revenue</p>
            </div>
        </div>
    </div>

    <!-- Recent Sales -->
    <div class="section">
        <h3>📋 Recent Sales</h3>
        <table class="table">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Fish</th>
                    <th>Fisherman</th>
                    <th>Quantity (kg)</th>
                    <th>Total (TZS)</th>
                </tr>
            </thead>
            <tbody>
                <?php if (mysqli_num_rows($recent_sales) > 0): ?>
                <?php while ($row = mysqli_fetch_assoc($recent_sales)): ?>
                <tr>
                    <td><?php echo $row['sale_date']; ?></td>
                    <td><?php echo $row['fish_name']; ?></td>
                    <td><?php echo $row['fisherman_name']; ?></td>
                    <td><?php echo $row['quantity_kg']; ?> kg</td>
                    <td>TZS <?php echo number_format($row['total_price']); ?></td>
                </tr>
                <?php endwhile; ?>
                <?php else: ?>
                <tr><td colspan="5" style="text-align:center;">No sales recorded yet.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Top Fish -->
    <div class="section">
        <h3>🏆 Top Selling Fish</h3>
        <table class="table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Fish Name</th>
                    <th>Total (kg)</th>
                    <th>Revenue (TZS)</th>
                </tr>
            </thead>
            <tbody>
                <?php if (mysqli_num_rows($top_fish) > 0): ?>
                <?php $i = 1; while ($row = mysqli_fetch_assoc($top_fish)): ?>
                <tr>
                    <td><?php echo $i++; ?></td>
                    <td><?php echo $row['name']; ?></td>
                    <td><?php echo $row['total_kg']; ?> kg</td>
                    <td>TZS <?php echo number_format($row['total_revenue']); ?></td>
                </tr>
                <?php endwhile; ?>
                <?php else: ?>
                <tr><td colspan="4" style="text-align:center;">No data available yet.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
