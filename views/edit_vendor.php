<?php
include '../config/db.php';
$title = "Edit Vendor";

// Mendapatkan ID vendor dari URL
$id = $_GET['id'] ?? '';

if (!$id) {
    echo "ID vendor tidak ditemukan!";
    exit;
}

// Mendapatkan data vendor berdasarkan ID
$stmt = $conn->prepare("SELECT * FROM vendor WHERE id = :id");
$stmt->execute(['id' => $id]);
$vendor = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$vendor) {
    echo "Vendor tidak ditemukan!";
    exit;
}

// Handle form submission to update the vendor details
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama = trim($_POST['nama']);
    $kontak = trim($_POST['kontak']);
    $nama_barang = trim($_POST['nama_barang']);
    
    try {
        // Update vendor table
        $stmt = $conn->prepare("UPDATE vendor SET nama = ?, kontak = ?, nama_barang = ? WHERE id = ?");
        $stmt->execute([$nama, $kontak, $nama_barang, $id]);

        // Redirect to vendor list page after success
        header('Location: vendor_list.php');
        exit;
    } catch (PDOException $e) {
        $error = "Database error: " . htmlspecialchars($e->getMessage());
    }
}
?>

<?php include '../partials/header.php'; ?>
<?php include '../partials/sidebar.php'; ?>

<div class="container mt-5">
    <h1>Edit Vendor</h1>

    <!-- Display error if any -->
    <?php if (isset($error)): ?>
        <div class="alert alert-danger">
            <p><?php echo $error; ?></p>
        </div>
    <?php endif; ?>

    <!-- Form for editing the vendor -->
    <form action="" method="POST">
        <div class="form-group">
            <label for="nama">Nama Vendor</label>
            <input type="text" name="nama" id="nama" class="form-control" value="<?= htmlspecialchars($vendor['nama']) ?>" required>
        </div>
        <div class="form-group">
            <label for="kontak">Kontak Vendor</label>
            <input type="text" name="kontak" id="kontak" class="form-control" value="<?= htmlspecialchars($vendor['kontak']) ?>" required>
        </div>
        <div class="form-group">
            <label for="nama_barang">Nama Barang</label>
            <input type="text" name="nama_barang" id="nama_barang" class="form-control" value="<?= htmlspecialchars($vendor['nama_barang']) ?>" required>
        </div>

        <button type="submit" class="btn btn-primary">Update Vendor</button>
    </form>
</div>

<?php include '../partials/footer.php'; ?>
