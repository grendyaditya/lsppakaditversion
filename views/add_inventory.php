<?php
session_start();
include '../auth/auth.php';
checkAuth();
include '../config/db.php';

$title = "Add Inventory";
error_reporting(E_ALL);
ini_set('display_errors', 1);

$nama_barang = $_POST['nama_barang'] ?? '';
$vendor_options = [];
$storage_units = $conn->query("SELECT id, nama_gudang FROM storage_unit")->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['submit_inventory'])) {
        // Menambahkan inventory
        $stmt = $conn->prepare("INSERT INTO inventory (vendor_id, jenis_barang, kuantitas_stok, storage_unit_id, harga) 
                               VALUES (:vendor_id, :jenis_barang, :kuantitas_stok, :storage_unit_id, :harga)");
        $stmt->execute([
            'vendor_id' => $_POST['vendor_id'],
            'jenis_barang' => $_POST['jenis_barang'],
            'kuantitas_stok' => $_POST['kuantitas_stok'],
            'storage_unit_id' => $_POST['storage_unit_id'],
            'harga' => $_POST['harga']
        ]);

        header("Location: inventory_list.php");
        exit();
    }

    if ($nama_barang) {
        // Ambil vendor berdasarkan nama barang
        $stmt = $conn->prepare("SELECT id AS vendor_id, nama FROM vendor WHERE nama_barang = :nama_barang");
        $stmt->execute(['nama_barang' => $nama_barang]);
        $vendor_options = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>

<?php include '../partials/header.php'; ?>
<?php include '../partials/sidebar.php'; ?>

<div class="container-fluid">
    <h1 class="mt-4">Add Inventory</h1>

    <?php if (isset($message)): ?>
        <div class="alert alert-success"><?= $message; ?></div>
    <?php endif; ?>

    <!-- Form untuk menambah inventory -->
    <form method="POST">
        <div class="form-group">
            <label for="nama_barang">Nama Barang</label>
            <select name="nama_barang" id="nama_barang" class="form-control" onchange="this.form.submit()">
                <option value="">Pilih Nama Barang</option>
                <?php
                // Daftar nama barang
                foreach ($conn->query("SELECT DISTINCT nama_barang FROM vendor") as $barang) {
                    echo "<option value='" . htmlspecialchars($barang['nama_barang']) . "' " . ($barang['nama_barang'] == $nama_barang ? 'selected' : '') . ">" . htmlspecialchars($barang['nama_barang']) . "</option>";
                }
                ?>
            </select>
        </div>

        <?php if ($vendor_options): ?>
            <div class="form-group">
                <label for="vendor_id">Vendor</label>
                <select name="vendor_id" id="vendor_id" class="form-control">
                    <?php foreach ($vendor_options as $option): ?>
                        <option value="<?= $option['vendor_id']; ?>"><?= htmlspecialchars($option['nama']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        <?php endif; ?>

        <div class="form-group">
            <label for="jenis_barang">Jenis Barang</label>
            <input type="text" name="jenis_barang" class="form-control" required value="<?= htmlspecialchars($_POST['jenis_barang'] ?? '') ?>">
        </div>

        <div class="form-group">
            <label for="kuantitas_stok">Kuantitas Stok</label>
            <input type="number" name="kuantitas_stok" class="form-control" required value="<?= htmlspecialchars($_POST['kuantitas_stok'] ?? '') ?>">
        </div>

        <div class="form-group">
            <label for="storage_unit_id">Lokasi Gudang</label>
            <select name="storage_unit_id" class="form-control" required>
                <?php foreach ($storage_units as $storage_unit): ?>
                    <option value="<?= $storage_unit['id']; ?>" <?= (isset($_POST['storage_unit_id']) && $_POST['storage_unit_id'] == $storage_unit['id']) ? 'selected' : ''; ?>>
                        <?= htmlspecialchars($storage_unit['nama_gudang']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label for="harga">Harga</label>
            <input type="number" name="harga" class="form-control" required value="<?= htmlspecialchars($_POST['harga'] ?? '') ?>">
        </div>

        <button type="submit" name="submit_inventory" class="btn btn-primary">Add Inventory</button>
    </form>
</div>

<?php include '../partials/footer.php'; ?>
