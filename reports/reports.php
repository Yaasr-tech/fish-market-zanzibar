<?php
include '../includes/db.php';
include '../includes/header.php';

// Default dates
$from_date = isset($_GET['from_date']) ? $_GET['from_date'] : date('Y-m-01');
$to_date   = isset($_GET['to_date'])   ? $_GET['to_date']   : date('Y-m-d');
$report_type = isset($_GET['report_type']) ? $_GET['report_type'] : 'daily';

// Summary totals
$summary = mysqli_fetch_assoc(mysqli_query($conn,
    "SELECT COUNT(*) as total_transactions,
            SUM(quantity_kg) as total_kg,
            SUM(total_price) as total_revenue
     FROM sales
     WHERE sale_date BETWEEN '$from_date' AND '$to_date'"
));

// Daily breakdown
$daily = mysqli_query($conn,
    "SELECT sale_date,
            COUNT(*) as transactions,
            SUM(quantity_kg) as total_kg,
            SUM(total_price) as total_revenue
     FROM sales
     WHERE sale_date BETWEEN '$from_date' AND '$to_date'
     GROUP BY sale_date
     ORDER BY sale_date DESC"
);

// Top fish in period
$top_fish = mysqli_query($conn,
    "SELECT f.name, f.category,
            COUNT(s.id) as times_sold,
            SUM(s.quantity_kg) as total_kg,
            SUM(s.total_price) as total_revenue
     FROM sales s
     JOIN fish f ON s.fish_id = f.id
     WHERE s.sale_date BETWEEN '$from_date' AND '$to_date'
     GROUP BY f.id
     ORDER BY total_revenue DESC
     LIMIT 5"
);

// Top fishermen in period
$top_fishermen = mysqli_query($conn,
    "SELECT fm.full_name, fm.location,
            COUNT(s.id) as total_sales,
            SUM(s.quantity_kg) as total_kg,
            SUM(s.total_price) as total_revenue
     FROM sales s
     JOIN fishermen fm ON s.fisherman_id = fm.id
     WHERE s.sale_date BETWEEN '$from_date' AND '$to_date'
     GROUP BY fm.id
     ORDER BY total_revenue DESC
     LIMIT 5"
);

// Log report
$user_id = $_SESSION['user_id'];
mysqli_query($conn, "INSERT INTO reports (user_id, report_type, from_date, to_date) VALUES ($user_id, '$report_type', '$from_date', '$to_date')");
?>

<div class="container">
    <div class="page-header">
        <h2>📊 Reports & Analytics</h2>
        <button onclick="window.print()" class="btn btn-primary">🖨️ Print Report</button>
    </div>

    <!-- Filter Form -->
    <div class="section">
        <h3>🔍 Filter Report</h3>
        <form method="GET" style="display:flex; gap:16px; flex-wrap:wrap; align-items:flex-end;">
            <div class="form-group" style="margin:0;">
                <label>Report Type</label>
                <select name="report_type">
                    <option value="daily"   <?php echo $report_type=='daily'  ?'selected':''; ?>>Daily</option>
                    <option value="weekly"  <?php echo $report_type=='weekly' ?'selected':''; ?>>Weekly</option>
                    <option value="monthly" <?php echo $report_type=='monthly'?'selected':''; ?>>Monthly</option>
                </select>
            </div>
            <div class="form-group" style="margin:0;">
                <label>From Date</label>
                <input type="date" name="from_date" value="<?php echo $from_date; ?>">
            </div>
            <div class="form-group" style="margin:0;">
                <label>To Date</label>
                <input type="date" name="to_date" value="<?php echo $to_date; ?>">
            </div>
            <button type="submit" class="btn btn-primary">📊 Generate</button>
        </form>
    </div>

    <!-- Summary Cards -->
    <div class="cards-grid">
        <div class="card card-blue">
            <div class="card-icon">🧾</div>
            <div class="card-info">
                <h3><?php echo $summary['total_transactions'] ?? 0; ?></h3>
                <p>Total Transactions</p>
            </div>
        </div>
        <div class="card card-green">
            <div class="card-icon">⚖️</div>
            <div class="card-info">
                <h3><?php echo number_format($summary['total_kg'] ?? 0, 2); ?> kg</h3>
                <p>Total Fish Sold</p>
            </div>
        </div>
        <div class="card card-teal">
            <div class="card-icon">💰</div>
            <div class="card-info">
                <h3>TZS <?php echo number_format($summary['total_revenue'] ?? 0); ?></h3>
                <p>Total Revenue</p>
            </div>
        </div>
    </div>

    <!-- Daily Breakdown -->
    <div class="section">
        <h3>📅 Daily Breakdown (<?php echo date('d M Y', strtotime($from_date)); ?> — <?php echo date('d M Y', strtotime($to_date)); ?>)</h3>
        <table class="table">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Transactions</th>
                    <th>Total (kg)</th>
                    <th>Revenue (TZS)</th>
                </tr>
            </thead>
            <tbody>
                <?php if (mysqli_num_rows($daily) > 0): ?>
                <?php while ($row = mysqli_fetch_assoc($daily)): ?>
                <tr>
                    <td><?php echo date('d M Y', strtotime($row['sale_date'])); ?></td>
                    <td><?php echo $row['transactions']; ?></td>
                    <td><?php echo number_format($row['total_kg'], 2); ?> kg</td>
                    <td>TZS <?php echo number_format($row['total_revenue']); ?></td>
                </tr>
                <?php endwhile; ?>
                <?php else: ?>
                <tr><td colspan="4" style="text-align:center;">No sales in this period.</td></tr>
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
                    <th>Category</th>
                    <th>Times Sold</th>
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
                    <td><?php echo $row['category']; ?></td>
                    <td><?php echo $row['times_sold']; ?></td>
                    <td><?php echo number_format($row['total_kg'], 2); ?> kg</td>
                    <td>TZS <?php echo number_format($row['total_revenue']); ?></td>
                </tr>
                <?php endwhile; ?>
                <?php else: ?>
                <tr><td colspan="6" style="text-align:center;">No data available.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Top Fishermen -->
    <div class="section">
        <h3>🎣 Top Fishermen</h3>
        <table class="table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Fisherman</th>
                    <th>Location</th>
                    <th>Total Sales</th>
                    <th>Total (kg)</th>
                    <th>Revenue (TZS)</th>
                </tr>
            </thead>
            <tbody>
                <?php if (mysqli_num_rows($top_fishermen) > 0): ?>
                <?php $i = 1; while ($row = mysqli_fetch_assoc($top_fishermen)): ?>
                <tr>
                    <td><?php echo $i++; ?></td>
                    <td><?php echo $row['full_name']; ?></td>
                    <td><?php echo $row['location']; ?></td>
                    <td><?php echo $row['total_sales']; ?></td>
                    <td><?php echo number_format($row['total_kg'], 2); ?> kg</td>
                    <td>TZS <?php echo number_format($row['total_revenue']); ?></td>
                </tr>
                <?php endwhile; ?>
                <?php else: ?>
                <tr><td colspan="6" style="text-align:center;">No data available.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
