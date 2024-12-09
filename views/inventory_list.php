<?php
include '../auth/auth.php';
checkAuth();
include '../config/db.php';
$title = "Inventory List";
$message = "";

// Proses hapus inventory
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];

    // Cek apakah ID inventory ada dan hapus
    $stmt = $conn->prepare("SELECT * FROM inventory WHERE id = :id");
    $stmt->execute(['id' => $id]);
    
    if ($stmt->rowCount()) {
        try {
            $conn->prepare("DELETE FROM inventory WHERE id = :id")->execute(['id' => $id]);
            $message = "Inventory deleted successfully!";
        } catch (PDOException $e) {
            $message = "Error deleting inventory: " . htmlspecialchars($e->getMessage());
        }
    } else {
        $message = "Inventory item not found!";
    }
}

// Ambil data inventory
$stmt = $conn->prepare("SELECT i.id, i.jenis_barang, i.kuantitas_stok, i.harga, 
                        v.nama AS vendor_nama, v.nama_barang, su.nama_gudang
                        FROM inventory i
                        JOIN vendor v ON i.vendor_id = v.id
                        JOIN storage_unit su ON i.storage_unit_id = su.id
                        ORDER BY i.id DESC");
$stmt->execute();
$inventories = $stmt->fetchAll();
?>

<?php include '../partials/header.php';?>
<?php include '../partials/sidebar.php';?>

<div class="container-fluid">
    <h1 class="mt-4">Inventory List</h1>

    <?php if ($message): ?>
        <div class="alert alert-success"><?= htmlspecialchars($message); ?></div>
    <?php endif;?>

    <div class="card mt-3">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5>Inventory List</h5>
            <a href="add_inventory.php" class="btn btn-success">Add Inventory</a>
        </div>
        <div class="card-body">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nama Barang</th>
                        <th>Jenis Barang</th>
                        <th>Kuantitas Stok</th>
                        <th>Lokasi Gudang</th>
                        <th>Harga</th>
                        <th>Vendor</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($inventories): ?>
                        <?php foreach ($inventories as $index => $inventory): ?>
                            <?php $is_out_of_stock = $inventory['kuantitas_stok'] == 0; ?>
                            <tr class="<?= $is_out_of_stock ? 'table-danger' : ''; ?>">
                                <td><?= $index + 1; ?></td>
                                <td><?= htmlspecialchars($inventory['nama_barang']); ?></td>
                                <td><?= htmlspecialchars($inventory['jenis_barang']); ?></td>
                                <td><?= htmlspecialchars($inventory['kuantitas_stok']); ?></td>
                                <td><?= htmlspecialchars($inventory['nama_gudang']); ?></td>
                                <td>Rp. <?= number_format($inventory['harga'], 0, ',', '.'); ?></td>
                                <td><?= htmlspecialchars($inventory['vendor_nama']); ?></td>
                                <td>
                                    <a href="edit_inventory.php?id=<?= htmlspecialchars($inventory['id']); ?>" class="btn btn-warning btn-sm">Edit</a>
                                    <a href="?delete=<?= htmlspecialchars($inventory['id']); ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this item?');">Delete</a>
                                </td>
                            </tr>
                            <?php if ($is_out_of_stock): ?>
                                <tr>
                                    <td colspan="8">
                                        <div class="alert alert-danger">
                                            Warning: Stok barang '<?= htmlspecialchars($inventory['nama_barang']); ?>' habis!
                                        </div>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="8" class="text-center">No inventory items found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include '../partials/footer.php';?>
