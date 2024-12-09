<?php
include '../auth/auth.php';
checkAuth();
include '../config/db.php';
$title = "Edit Inventory";

// Mengaktifkan error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

$inventory_id = $_GET['id'] ?? '';
$inventory_data = [];
$vendor_name = '';

// Mengambil data inventory dan vendor berdasarkan ID
if ($inventory_id) {
    $stmt = $conn->prepare("SELECT i.*, v.nama AS vendor_name FROM inventory i 
                            JOIN vendor v ON i.vendor_id = v.id WHERE i.id = :id");
    $stmt->execute(['id' => $inventory_id]);
    $inventory_data = $stmt->fetch(PDO::FETCH_ASSOC);
    $vendor_name = $inventory_data['vendor_name'] ?? 'Vendor tidak ditemukan';
}

// Mengambil data storage unit
$storage_units = $conn->query("SELECT id, nama_gudang FROM storage_unit")->fetchAll(PDO::FETCH_ASSOC);

// Update inventory
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_inventory'])) {
    $stmt = $conn->prepare("UPDATE inventory SET jenis_barang = :jenis_barang, kuantitas_stok = :kuantitas_stok, 
                            storage_unit_id = :storage_unit_id, harga = :harga WHERE id = :id");
    $stmt->execute([
        'jenis_barang' => $_POST['jenis_barang'],
        'kuantitas_stok' => $_POST['kuantitas_stok'],
        'storage_unit_id' => $_POST['storage_unit_id'],
        'harga' => $_POST['harga'],
        'id' => $inventory_id,
    ]);
    header("Location: inventory_list.php");
    exit();
}
?>

<?php include '../partials/header.php'; ?>
<?php include '../partials/sidebar.php'; ?>

<div class="container-fluid">
    <h1 class="mt-4">Edit Inventory</h1>

    <?php if (isset($message)): ?>
        <div class="alert alert-success"><?= $message; ?></div>
    <?php endif; ?>

    <!-- Form untuk mengedit inventory -->
    <form method="POST">
        <div class="form-group">
            <label for="nama_barang">Nama Barang</label>
            <input type="text" class="form-control" value="<?= htmlspecialchars($inventory_data['nama_barang'] ?? '') ?>" disabled>
        </div>

        <div class="form-group">
            <label for="vendor_id">Vendor</label>
            <input type="text" class="form-control" value="<?= htmlspecialchars($vendor_name) ?>" disabled>
        </div>

        <div class="form-group">
            <label for="jenis_barang">Jenis Barang</label>
            <input type="text" name="jenis_barang" class="form-control" required value="<?= htmlspecialchars($_POST['jenis_barang'] ?? $inventory_data['jenis_barang']) ?>">
        </div>

        <div class="form-group">
            <label for="kuantitas_stok">Kuantitas Stok</label>
            <input type="number" name="kuantitas_stok" class="form-control" required value="<?= htmlspecialchars($_POST['kuantitas_stok'] ?? $inventory_data['kuantitas_stok']) ?>">
        </div>

        <div class="form-group">
            <label for="storage_unit_id">Lokasi Gudang</label>
            <select name="storage_unit_id" class="form-control" required>
                <?php foreach ($storage_units as $storage_unit): ?>
                    <option value="<?= $storage_unit['id'] ?>" <?= ($storage_unit['id'] == $inventory_data['storage_unit_id']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($storage_unit['nama_gudang']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label for="harga">Harga</label>
            <input type="number" name="harga" class="form-control" required value="<?= htmlspecialchars($_POST['harga'] ?? $inventory_data['harga']) ?>">
        </div>

        <button type="submit" name="submit_inventory" class="btn btn-primary">Update Inventory</button>
    </form>
</div>

<?php include '../partials/footer.php'; ?>
