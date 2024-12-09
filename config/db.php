<?php
// config/db.php
try {
    $conn = new PDO("mysql:host=localhost;dbname=lsp_inventory", "root", "123");
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
