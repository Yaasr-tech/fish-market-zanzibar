<?php
include '../includes/db.php';
include '../includes/header.php';

$error = '';

// Get fish list
$fish_list = mysqli_query($conn, "SELECT * FROM fish WHERE stock_kg > 0 ORDER BY name");
// Get fishermen list
$fishermen_list = mysqli_query($conn, "SELECT * FROM fishermen ORDER BY full_name");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $fish_id      = intval($_POST['fish_id']);
    $fisherman_id = intval($_POST['fisherman_id']);
    $quantity     = floatval($_POST['quantity_kg']);
    $sale_date    = mysqli_real_escape_string($conn, $_POST['sale_date']);
    $notes        = mysqli_real_escape_string($conn, $_POST['notes']);
    $user_id      = $_SESSION['user_id'];

    // Get fish price
    $fish = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM fish WHERE id = $fish_id"));

    if (!$fish) {
        $error = "Selected fish not found!";
    } elseif ($quantity <= 0) {
        $error = "Quantity must be greater than 0!";
    } elseif ($quantity > $fish['stock_kg']) {
        $error = "Not enough stock! Available: " . $fish['stock_kg'] . " kg";
    } else {
        $price_per_kg = $fish['price_per_kg'];
        $total_price  = $quantity * $price_per_kg;

        // Insert sale
        $sql = "INSERT INTO sales (fish_id, fisherman_id, user_id, quantity_kg, price_per_kg, total_price, sale_date, notes)
                VALUES ($fish_id, $fisherman_id, $user_id, $quantity, $price_per_kg, $total_price, '$sale_date', '$notes')";

        if (mysqli_query($conn, $sql)) {
            // Update stock
            $new_stock = $fish['stock_kg'] - $quantity;
            mysqli_query($conn, "UPDATE fish SET stock_kg = $new_stock WHERE id = $fish_id");
            header("Location: /fish_market/sales/sales_list.php?success=added");
            exit();
        } else {
            $error = "Error: " . mysqli_error($conn);
        }
    }
}
?>

<div class="container">
    <div class="page-header">
        <h2>💰 Record New Sale</h2>
        <a href="/fish_market/sales/sales_list.php" class="btn btn-warning">← Back to List</a>
    </div>

    <?php if ($error): ?>
        <div class="alert alert-error">❌ <?php echo $error; ?></div>
    <?php endif; ?>

    <div class="section">
        <form method="POST">
            <div class="form-group">
                <label>Fish *</label>
                <select name="fish_id" id="fish_select" required onchange="updatePrice()">
                    <option value="">-- Select Fish --</option>
                    <?php while ($f = mysqli_fetch_assoc($fish_list)): ?>
                    <option value="<?php echo $f['id']; ?>" data-price="<?php echo $f['price_per_kg']; ?>" data-stock="<?php echo $f['stock_kg']; ?>">
                        <?php echo $f['name']; ?> — TZS <?php echo number_format($f['price_per_kg']); ?>/kg (Stock: <?php echo $f['stock_kg']; ?> kg)
                    </option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="form-group">
                <label>Fisherman *</label>
                <select name="fisherman_id" required>
                    <option value="">-- Select Fisherman --</option>
                    <?php while ($fm = mysqli_fetch_assoc($fishermen_list)): ?>
                    <option value="<?php echo $fm['id']; ?>"><?php echo $fm['full_name']; ?> — <?php echo $fm['location']; ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="form-group">
                <label>Quantity (kg) *</label>
                <input type="number" name="quantity_kg" id="quantity" placeholder="e.g. 10" min="0.1" step="0.01" required onchange="calcTotal()">
            </div>
            <div class="form-group">
                <label>Price per kg (TZS)</label>
                <input type="text" id="price_display" placeholder="Auto-filled" readonly style="background:#f5f5f5;">
            </div>
            <div class="form-group">
                <label>Total Price (TZS)</label>
                <input type="text" id="total_display" placeholder="Auto-calculated" readonly style="background:#f5f5f5; font-weight:bold; color:#0077b6;">
            </div>
            <div class="form-group">
                <label>Sale Date *</label>
                <input type="date" name="sale_date" value="<?php echo date('Y-m-d'); ?>" required>
            </div>
            <div class="form-group">
                <label>Notes (optional)</label>
                <textarea name="notes" rows="3" placeholder="Any additional notes..."></textarea>
            </div>
            <button type="submit" class="btn btn-primary">💾 Save Sale</button>
            <a href="/fish_market/sales/sales_list.php" class="btn btn-warning">Cancel</a>
        </form>
    </div>
</div>

<script>
function updatePrice() {
    const select = document.getElementById('fish_select');
    const option = select.options[select.selectedIndex];
    const price  = option.getAttribute('data-price') || 0;
    document.getElementById('price_display').value = 'TZS ' + parseFloat(price).toLocaleString();
    calcTotal();
}

function calcTotal() {
    const select   = document.getElementById('fish_select');
    const option   = select.options[select.selectedIndex];
    const price    = parseFloat(option.getAttribute('data-price')) || 0;
    const quantity = parseFloat(document.getElementById('quantity').value) || 0;
    const total    = price * quantity;
    document.getElementById('total_display').value = 'TZS ' + total.toLocaleString();
}
</script>

<?php include '../includes/footer.php'; ?>
