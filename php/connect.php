<?php
    $nama_pengguna = $_POST['nama_pengguna'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $role = $_POST['role'];
    $lab_akses = isset($_POST['lab_akses']) ? $_POST['lab_akses'] : [];
    $lab_akses_json = json_encode($lab_akses);
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $conn = new mysqli('localhost', 'root', '', 'labinforpetra_db');
    if ($conn->connect_error) {
        die("Koneksi gagal: " . $conn->connect_error);
    }

    $stmt = $conn->prepare("INSERT INTO pengguna (nama_pengguna, lab_akses, email, password, role) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $nama_pengguna, $lab_akses_json, $email, $hashed_password, $role);
    if ($stmt->execute()) {
        echo "Registrasi pengguna berhasil!";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
?>