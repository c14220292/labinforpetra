<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.html");
    exit();
}

$conn = new mysqli("localhost", "root", "", "labinforpetra_db");
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

$id_pengguna    = $_POST['id_pengguna'];
$nama_pengguna  = $_POST['nama_pengguna'];
$email          = $_POST['email'];
$password       = $_POST['password'];
$confirm        = $_POST['confirm_password'];
$role           = $_POST['role'];
$lab_akses      = isset($_POST['lab_akses']) ? $_POST['lab_akses'] : [];

// Validasi password (jika diisi)
if (!empty($password) && $password !== $confirm) {
    header("Location: pengguna_edit.php?id=$id_pengguna&error=Password dan konfirmasi tidak cocok");
    exit();
}

// Update data pengguna (tanpa password dulu)
if (empty($password)) {
    $sql = "UPDATE pengguna SET nama_pengguna = ?, email = ?, role = ? WHERE id_pengguna = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssi", $nama_pengguna, $email, $role, $id_pengguna);
} else {
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $sql = "UPDATE pengguna SET nama_pengguna = ?, email = ?, password = ?, role = ? WHERE id_pengguna = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssi", $nama_pengguna, $email, $hashed_password, $role, $id_pengguna);
}

if (!$stmt->execute()) {
    echo "Gagal mengupdate pengguna: " . $stmt->error;
    exit();
}
$stmt->close();

// Update akses lab: hapus dulu, lalu insert ulang
$conn->query("DELETE FROM pengguna_lab WHERE id_pengguna = $id_pengguna");

if (!empty($lab_akses)) {
    $insert_stmt = $conn->prepare("INSERT INTO pengguna_lab (id_pengguna, kode_lab) VALUES (?, ?)");
    foreach ($lab_akses as $kode_lab) {
        $insert_stmt->bind_param("is", $id_pengguna, $kode_lab);
        $insert_stmt->execute();
    }
    $insert_stmt->close();
}

$conn->close();
header("Location: pengguna.php?message=Pengguna berhasil diperbarui");
exit();
?>
