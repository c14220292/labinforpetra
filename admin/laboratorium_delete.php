<?php
session_start();
// Pastikan pengguna sudah login
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.html");
    exit();
}

// Koneksi ke database
$conn = new mysqli("localhost", "root", "", "labinforpetra_db");
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Ambil ID laboratorium yang akan dihapus
$id_lab = $_GET['id'] ?? '';
if (!$id_lab) {
    header("Location: laboratorium.php?error=ID laboratorium tidak ditemukan");
    exit();
}

// Hapus laboratorium dari database
$sql = "DELETE FROM laboratorium WHERE id_lab = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $id_lab);

if ($stmt->execute()) {
    header("Location: laboratorium.php?message=Laboratorium berhasil dihapus");
} else {
    header("Location: laboratorium.php?error=Gagal menghapus laboratorium");
}

$conn->close();
?>