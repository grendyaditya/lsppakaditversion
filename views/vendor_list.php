<?php
include '../auth/auth.php';
checkAuth();
include '../config/db.php';
$title = "Vendor List";

// Proses hapus vendor
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];

    // Hapus vendor
    $stmt = $conn->prepare("DELETE FROM vendor WHERE id = :id");
    $stmt->execute(['id' => $id]);

    $message = "Vendor deleted successfully!";
}

// Ambil daftar vendor
$stmt = $conn->query("SELECT * FROM vendor ORDER BY id DESC");
$vendors = $stmt->fetchAll();
?>

<?php include '../partials/header.php'; ?>
<?php include '../partials/sidebar.php'; ?>

<div class="container mt-4">
    <h1>Vendor List</h1>

    <?php if (isset($message)): ?>
        <div class="alert alert-success">
            <?= htmlspecialchars($message); ?>
        </div>
    <?php endif; ?>

    <div class="card mt-3">
        <div class="card-header">
            <h5>Vendor List</h5>
            <a href="add_vendor.php" class="btn btn-primary float-right">Add Vendor</a>
        </div>
        <div class="card-body">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Vendor</th>
                        <th>Kontak</th>
                        <th>Nama Barang</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($vendors as $i => $vendor): ?>
                        <tr>
                            <td><?= $i + 1; ?></td>
                            <td><?= htmlspecialchars($vendor['nama']); ?></td>
                            <td><?= htmlspecialchars($vendor['kontak']); ?></td>
                            <td><?= htmlspecialchars($vendor['nama_barang']); ?></td>
                            <td>
                                <a href="edit_vendor.php?id=<?= $vendor['id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                                <a href="?delete=<?= $vendor['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this vendor?')">Delete</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include '../partials/footer.php'; ?>
