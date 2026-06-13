<?php
include '../includes/db.php';
include '../includes/header.php';

if ($_SESSION['role'] != 'admin') {
    header("Location: /fish_market/fish/fish_list.php");
    exit();
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name     = mysqli_real_escape_string($conn, $_POST['name']);
    $category = mysqli_real_escape_string($conn, $_POST['category']);
    $price    = floatval($_POST['price_per_kg']);
    $stock    = floatval($_POST['stock_kg']);

    if (empty($name) || empty($category) || $price <= 0) {
        $error = "Please fill in all required fields!";
    } else {
        $sql = "INSERT INTO fish (name, category, price_per_kg, stock_kg) VALUES ('$name', '$category', '$price', '$stock')";
        if (mysqli_query($conn, $sql)) {
            header("Location: /fish_market/fish/fish_list.php?success=added");
            exit();
        } else {
            $error = "Error: " . mysqli_error($conn);
        }
    }
}
?>

<div class="container">
    <div class="page-header">
        <h2>🐠 Add New Fish</h2>
        <a href="/fish_market/fish/fish_list.php" class="btn btn-warning">← Back to List</a>
    </div>

    <?php if ($error): ?>
        <div class="alert alert-error">❌ <?php echo $error; ?></div>
    <?php endif; ?>

    <div class="section">
        <form method="POST">
            <div class="form-group">
                <label>Fish Name *</label>
                <input type="text" name="name" placeholder="e.g. Kingfish (Nguru)" required>
            </div>
            <div class="form-group">
                <label>Category *</label>
                <select name="category" required>
                    <option value="">-- Select Category --</option>
                    <option value="Sea Fish">Sea Fish</option>
                    <option value="Marine Creatures">Marine Creatures</option>
                    <option value="Small Fish">Small Fish</option>
                    <option value="Fresh Water Fish">Fresh Water Fish</option>
                </select>
            </div>
            <div class="form-group">
                <label>Price per kg (TZS) *</label>
                <input type="number" name="price_per_kg" placeholder="e.g. 12000" min="1" required>
            </div>
            <div class="form-group">
                <label>Stock (kg)</label>
                <input type="number" name="stock_kg" placeholder="e.g. 50" min="0" step="0.01" value="0">
            </div>
            <button type="submit" class="btn btn-primary">💾 Save Fish</button>
            <a href="/fish_market/fish/fish_list.php" class="btn btn-warning">Cancel</a>
        </form>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
