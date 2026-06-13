<?php
include '../includes/db.php';
include '../includes/header.php';

if ($_SESSION['role'] != 'admin') {
    header("Location: /fish_market/fishermen/fishermen_list.php");
    exit();
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $full_name = mysqli_real_escape_string($conn, $_POST['full_name']);
    $phone     = mysqli_real_escape_string($conn, $_POST['phone']);
    $location  = mysqli_real_escape_string($conn, $_POST['location']);
    $boat_name = mysqli_real_escape_string($conn, $_POST['boat_name']);

    if (empty($full_name) || empty($phone) || empty($location)) {
        $error = "Please fill in all required fields!";
    } else {
        $sql = "INSERT INTO fishermen (full_name, phone, location, boat_name) VALUES ('$full_name', '$phone', '$location', '$boat_name')";
        if (mysqli_query($conn, $sql)) {
            header("Location: /fish_market/fishermen/fishermen_list.php?success=added");
            exit();
        } else {
            $error = "Error: " . mysqli_error($conn);
        }
    }
}
?>

<div class="container">
    <div class="page-header">
        <h2>🎣 Add New Fisherman</h2>
        <a href="/fish_market/fishermen/fishermen_list.php" class="btn btn-warning">← Back to List</a>
    </div>

    <?php if ($error): ?>
        <div class="alert alert-error">❌ <?php echo $error; ?></div>
    <?php endif; ?>

    <div class="section">
        <form method="POST">
            <div class="form-group">
                <label>Full Name *</label>
                <input type="text" name="full_name" placeholder="e.g. Hamisi Juma" required>
            </div>
            <div class="form-group">
                <label>Phone Number *</label>
                <input type="text" name="phone" placeholder="e.g. 0777123456" required>
            </div>
            <div class="form-group">
                <label>Location *</label>
                <input type="text" name="location" placeholder="e.g. Nungwi, Zanzibar" required>
            </div>
            <div class="form-group">
                <label>Boat Name (optional)</label>
                <input type="text" name="boat_name" placeholder="e.g. Bahari Yetu">
            </div>
            <button type="submit" class="btn btn-primary">💾 Save Fisherman</button>
            <a href="/fish_market/fishermen/fishermen_list.php" class="btn btn-warning">Cancel</a>
        </form>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
