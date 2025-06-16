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

    $id_pengguna = $_GET['id'];
    $sql = "DELETE FROM pengguna WHERE id_pengguna = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id_pengguna);

    if ($stmt->execute()) {
        header("Location: pengguna.php?message=Pengguna berhasil dihapus");
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
?>