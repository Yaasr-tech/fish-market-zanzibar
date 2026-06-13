<?php
include '../includes/db.php';
include '../includes/header.php';

if ($_SESSION['role'] != 'admin') {
    header("Location: /fish_market/fish/fish_list.php");
    exit();
}

$id = intval($_GET['id']);
$fish = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM fish WHERE id = $id"));

if (!$fish) {
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
        $sql = "UPDATE fish SET name='$name', category='$category', price_per_kg='$price', stock_kg='$stock' WHERE id=$id";
        if (mysqli_query($conn, $sql)) {
            header("Location: /fish_market/fish/fish_list.php?success=updated");
            exit();
        } else {
            $error = "Error: " . mysqli_error($conn);
        }
    }
}
?>

<div class="container">
    <div class="page-header">
        <h2>✏️ Edit Fish</h2>
        <a href="/fish_market/fish/fish_list.php" class="btn btn-warning">← Back to List</a>
    </div>

    <?php if ($error): ?>
        <div class="alert alert-error">❌ <?php echo $error; ?></div>
    <?php endif; ?>

    <div class="section">
        <form method="POST">
            <div class="form-group">
                <label>Fish Name *</label>
                <input type="text" name="name" value="<?php echo $fish['name']; ?>" required>
            </div>
            <div class="form-group">
                <label>Category *</label>
                <select name="category" required>
                    <option value="Sea Fish" <?php echo $fish['category']=='Sea Fish'?'selected':''; ?>>Sea Fish</option>
                    <option value="Marine Creatures" <?php echo $fish['category']=='Marine Creatures'?'selected':''; ?>>Marine Creatures</option>
                    <option value="Small Fish" <?php echo $fish['category']=='Small Fish'?'selected':''; ?>>Small Fish</option>
                    <option value="Fresh Water Fish" <?php echo $fish['category']=='Fresh Water Fish'?'selected':''; ?>>Fresh Water Fish</option>
                </select>
            </div>
            <div class="form-group">
                <label>Price per kg (TZS) *</label>
                <input type="number" name="price_per_kg" value="<?php echo $fish['price_per_kg']; ?>" min="1" required>
            </div>
            <div class="form-group">
                <label>Stock (kg)</label>
                <input type="number" name="stock_kg" value="<?php echo $fish['stock_kg']; ?>" min="0" step="0.01">
            </div>
            <button type="submit" class="btn btn-primary">💾 Update Fish</button>
            <a href="/fish_market/fish/fish_list.php" class="btn btn-warning">Cancel</a>
        </form>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
