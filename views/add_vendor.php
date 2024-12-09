<?php
include '../config/db.php';
$title = "Add Vendor";

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama = trim($_POST['nama']);
    $kontak = trim($_POST['kontak']);
    $nama_barang = trim($_POST['nama_barang']);
    
    try {
        // Insert into vendor table
        $stmt = $conn->prepare("INSERT INTO vendor (nama, kontak, nama_barang) VALUES (?, ?, ?)");
        $stmt->execute([$nama, $kontak, $nama_barang]);
        header('Location: vendor_list.php');  // Redirect to the vendor list page
        exit;
    } catch (PDOException $e) {
        $error = "Database error: " . htmlspecialchars($e->getMessage());
    }
}
?>

<?php include '../partials/header.php'; ?>
<?php include '../partials/sidebar.php'; ?>

<div class="container">
    <h1 class="mt-4">Add Vendor</h1>

    <!-- Display error if any -->
    <?php if (isset($error)): ?>
        <div class="alert alert-danger">
            <p><?php echo $error; ?></p>
        </div>
    <?php endif; ?>

    <!-- Form for adding a vendor -->
    <form action="" method="POST">
        <div class="form-group">
            <label for="nama">Nama Vendor</label>
            <input type="text" name="nama" id="nama" class="form-control" value="<?= htmlspecialchars($nama ?? '') ?>" required>
        </div>
        <div class="form-group">
            <label for="kontak">Kontak Vendor</label>
            <input type="text" name="kontak" id="kontak" class="form-control" value="<?= htmlspecialchars($kontak ?? '') ?>" required>
        </div>
        <div class="form-group">
            <label for="nama_barang">Nama Barang</label>
            <input type="text" name="nama_barang" id="nama_barang" class="form-control" value="<?= htmlspecialchars($nama_barang ?? '') ?>" required>
        </div>

        <button type="submit" class="btn btn-primary">Add Vendor</button>
    </form>
</div>

<?php include '../partials/footer.php'; ?>
