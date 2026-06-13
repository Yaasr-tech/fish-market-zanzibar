<?php
include '../includes/db.php';
include '../includes/header.php';

if ($_SESSION['role'] != 'admin') {
    header("Location: /fish_market/fishermen/fishermen_list.php");
    exit();
}

$id = intval($_GET['id']);
$fisherman = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM fishermen WHERE id = $id"));

if (!$fisherman) {
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
        $sql = "UPDATE fishermen SET full_name='$full_name', phone='$phone', location='$location', boat_name='$boat_name' WHERE id=$id";
        if (mysqli_query($conn, $sql)) {
            header("Location: /fish_market/fishermen/fishermen_list.php?success=updated");
            exit();
        } else {
            $error = "Error: " . mysqli_error($conn);
        }
    }
}
?>

<div class="container">
    <div class="page-header">
        <h2>✏️ Edit Fisherman</h2>
        <a href="/fish_market/fishermen/fishermen_list.php" class="btn btn-warning">← Back to List</a>
    </div>

    <?php if ($error): ?>
        <div class="alert alert-error">❌ <?php echo $error; ?></div>
    <?php endif; ?>

    <div class="section">
        <form method="POST">
            <div class="form-group">
                <label>Full Name *</label>
                <input type="text" name="full_name" value="<?php echo $fisherman['full_name']; ?>" required>
            </div>
            <div class="form-group">
                <label>Phone Number *</label>
                <input type="text" name="phone" value="<?php echo $fisherman['phone']; ?>" required>
            </div>
            <div class="form-group">
                <label>Location *</label>
                <input type="text" name="location" value="<?php echo $fisherman['location']; ?>" required>
            </div>
            <div class="form-group">
                <label>Boat Name (optional)</label>
                <input type="text" name="boat_name" value="<?php echo $fisherman['boat_name']; ?>">
            </div>
            <button type="submit" class="btn btn-primary">💾 Update Fisherman</button>
            <a href="/fish_market/fishermen/fishermen_list.php" class="btn btn-warning">Cancel</a>
        </form>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
