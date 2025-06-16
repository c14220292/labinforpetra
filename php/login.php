<?php
session_start();
$conn = new mysqli("localhost", "root", "", "labinforpetra_db");

$email = $_POST['email'];
$password = $_POST['password'];

$sql = "SELECT * FROM pengguna WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if ($user) {
    if (password_verify($password, $user['password'])) {
        if ($user['role'] == 'admin') {
            $_SESSION['id_pengguna'] = $user['id_pengguna'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['nama'] = $user['nama_pengguna'];
            $_SESSION['email'] = $user['email'];

            // Update last_login
            $update_sql = "UPDATE pengguna SET last_login = NOW() WHERE id_pengguna = ?";
            $update_stmt = $conn->prepare($update_sql);
            $update_stmt->bind_param("i", $user['id_pengguna']);
            $update_stmt->execute();
            $update_stmt->close();

            header("Location: ../admin/home.php");
            exit();
        } else if ($user['role'] == 'kepalalab') {
            $_SESSION['id_pengguna'] = $user['id_pengguna'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['nama'] = $user['nama_pengguna'];
            $_SESSION['email'] = $user['email'];

            // Update last_login
            $update_sql = "UPDATE pengguna SET last_login = NOW() WHERE id_pengguna = ?";
            $update_stmt = $conn->prepare($update_sql);
            $update_stmt->bind_param("i", $user['id_pengguna']);
            $update_stmt->execute();
            $update_stmt->close();

            header("Location: ../kepalalab/home.php");
            exit();
        } else if ($user['role'] == 'asistenlab') {
            $_SESSION['id_pengguna'] = $user['id_pengguna'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['nama'] = $user['nama_pengguna'];
            $_SESSION['email'] = $user['email'];

            // Update last_login
            $update_sql = "UPDATE pengguna SET last_login = NOW() WHERE id_pengguna = ?";
            $update_stmt = $conn->prepare($update_sql);
            $update_stmt->bind_param("i", $user['id_pengguna']);
            $update_stmt->execute();
            $update_stmt->close();

            header("Location: ../asistenlab/home.php");
            exit();
        } else {
            echo "<script>
                    alert('Akun anda belum kami validasi, harap menunggu manajemen role dari admin.');
                    window.location.href = '../login.html';
                </script>";
        }
    } else {
        echo "<script>
                alert('Email atau password salah!');
                window.location.href = '../login.html';
            </script>";
    }
} else {
    echo "<script>
            alert('Email atau password salah!');
            window.location.href = '../login.html';
        </script>";
}
?>