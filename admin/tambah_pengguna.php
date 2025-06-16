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

// Ambil data dari form
$nama_pengguna = $_POST['nama_pengguna'];
$email = $_POST['email'];
$password = $_POST['password'];
$role = $_POST['role'];
$lab_akses = isset($_POST['lab_akses']) ? $_POST['lab_akses'] : []; // Ambil lab akses sebagai array

// Hash password
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// Query untuk menambahkan pengguna
$sql = "INSERT INTO pengguna (nama_pengguna, email, password, role) VALUES (?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssss", $nama_pengguna, $email, $hashed_password, $role);

if ($stmt->execute()) {
    $id_pengguna = $stmt->insert_id; // Ambil ID pengguna yang baru ditambahkan

    // Tambahkan akses lab ke tabel pengguna_lab
    if (!empty($lab_akses)) {
        $lab_stmt = $conn->prepare("INSERT INTO pengguna_lab (id_pengguna, kode_lab) VALUES (?, ?)");
        foreach ($lab_akses as $kode_lab) {
            $lab_stmt->bind_param("is", $id_pengguna, $kode_lab);
            $lab_stmt->execute();
        }
        $lab_stmt->close();
    }

    // Redirect ke halaman pengguna setelah berhasil ditambahkan
    header("Location: pengguna.php?message=Pengguna berhasil ditambahkan");
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close(); // Tutup koneksi database
?>
