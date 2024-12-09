<?php
include '../auth/auth.php';
checkAuth();
include '../config/db.php';
$title = "Add Storage Unit";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama_gudang = trim($_POST['nama_gudang']);
    $lokasi = trim($_POST['lokasi']);
    
    try {
        $stmt = $conn->prepare("INSERT INTO storage_unit (nama_gudang, lokasi) VALUES (:nama_gudang, :lokasi)");
        $stmt->execute(['nama_gudang' => $nama_gudang, 'lokasi' => $lokasi]);
        $message = "Storage Unit berhasil ditambahkan!";
        header("Location: storage_unit.php");
        exit();
    } catch (PDOException $e) {
        $message = "Error: " . $e->getMessage();
    }
}
?>

<?php include '../partials/header.php';?>
<?php include '../partials/sidebar.php';?>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-9">
            <h1 class="mt-4">Add Storage Unit</h1>
            <?php if (isset($message)): ?>
                <div class="alert alert-success"><?= htmlspecialchars($message); ?></div>
            <?php endif;?>

            <div class="card mt-3">
                <div class="card-header"><h5>Add Storage Unit</h5></div>
                <div class="card-body">
                    <form method="POST">
                        <div class="form-group">
                            <label for="nama_gudang">Nama Gudang</label>
                            <input type="text" name="nama_gudang" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="lokasi">Lokasi</label>
                            <input type="text" name="lokasi" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Add Storage Unit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../partials/footer.php';?>
