<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama_pengguna = $_POST['nama_pengguna'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    if (empty($nama_pengguna) || empty($email) || empty($password) || empty($confirm_password)) {
        echo "<script>
                alert('Semua field harus diisi!');
                window.history.back();
                </script>";
        exit;
    }

    if ($password !== $confirm_password) {
        echo "<script>
                alert('Password dan konfirmasi tidak cocok!');
                window.history.back();
                </script>";
        exit;
    }
    $role = null;
    $lab_akses_json = null;
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $conn = new mysqli('localhost', 'root', '', 'labinforpetra_db');
    if ($conn->connect_error) {
        die("Koneksi gagal: " . $conn->connect_error);
    }

    $stmt = $conn->prepare("INSERT INTO pengguna (nama_pengguna, email, password, role) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $nama_pengguna, $email, $hashed_password, $role);


    if ($stmt->execute()) {
        echo "<script>
                alert('Registrasi berhasil, harap menunggu manajemen role dari admin.');
                window.location.href = '../login.html';
            </script>";
    } else {
        echo "Terjadi kesalahan: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
} else {
    echo "Metode tidak valid.";
}
?>