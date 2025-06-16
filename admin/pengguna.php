<?php
session_start();

// Pastikan pengguna sudah login dan memiliki role admin
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.html");
    exit();
}

// Koneksi ke database
$conn = new mysqli("localhost", "root", "", "labinforpetra_db");
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Ambil ID pengguna yang sedang login
$currentUserId = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

// Query untuk mengambil data pengguna
if ($currentUserId !== null) {
    $sql = "SELECT 
                p.id_pengguna, 
                p.nama_pengguna, 
                p.email, 
                p.role, 
                p.last_login,
                GROUP_CONCAT(CONCAT(l.kode_lab, ' - ', l.nama_lab) SEPARATOR ' <br> ') AS lab_akses
            FROM pengguna p
            LEFT JOIN pengguna_lab pl ON p.id_pengguna = pl.id_pengguna
            LEFT JOIN laboratorium l ON pl.kode_lab = l.kode_lab
            WHERE p.id_pengguna != ?
            GROUP BY p.id_pengguna
            ORDER BY p.id_pengguna";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $currentUserId);
} else {
    $sql = "SELECT 
                p.id_pengguna, 
                p.nama_pengguna, 
                p.email, 
                p.role, 
                p.last_login,
                GROUP_CONCAT(CONCAT(l.kode_lab, ' - ', l.nama_lab) SEPARATOR ' <br> ') AS lab_akses
            FROM pengguna p
            LEFT JOIN pengguna_lab pl ON p.id_pengguna = pl.id_pengguna
            LEFT JOIN laboratorium l ON pl.kode_lab = l.kode_lab
            GROUP BY p.id_pengguna
            ORDER BY p.id_pengguna";
    $stmt = $conn->prepare($sql);
}

$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Pengguna</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        /* Gaya CSS tetap sama seperti sebelumnya */
        * {
            box-sizing: border-box;
            font-family: 'Roboto', sans-serif;
        }

        body {
            margin: 0;
            padding: 0;
            background-color: #fff;
        }

        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px 40px;
            position: relative;
            border-bottom: 4px solid #f7941d;
        }

        .logos {
            display: flex;
            gap: 15px;
            align-items: center;
        }

        .logos img {
            height: 40px;
        }

        .user-box {
            display: flex;
            align-items: center;
            border: 1px solid #ccc;
            border-radius: 6px;
            padding: 8px 12px;
            background-color: #f5f5f5;
            cursor: pointer;
        }

        .user-box span {
            font-size: 14px;
            margin-right: 8px;
        }

        .user-popup {
            display: none;
            position: absolute;
            top: 70px;
            right: 40px;
            background-color: #fff;
            border: 1px solid #ccc;
            border-radius: 8px;
            padding: 15px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            z-index: 999;
            width: 300px;
        }

        .menu-section {
            padding: 20px 40px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            border: 1px solid #ccc;
            padding: 10px;
            text-align: left;
        }

        th {
            background-color: #f7941d;
            color: white;
        }

        .user-popup p {
            margin: 8px 0;
            font-size: 14px;
        }

        .user-popup button {
            margin-top: 10px;
            background-color: #f26522;
            color: white;
            border: none;
            padding: 8px 12px;
            border-radius: 5px;
            cursor: pointer;
        }

        .button-container {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }

        .button-container a {
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
            color: white;
            font-weight: bold;
        }

        .back-button {
            background-color: #007BFF;
            /* Biru untuk tombol Kembali */
        }

        .add-button {
            background-color: #4CAF50;
            /* Hijau untuk tombol Tambah Pengguna */
        }

        .edit-button {
            background-color: #FFC107;
            /* Kuning untuk tombol Edit */
            color: black;
            padding: 5px 10px;
            border-radius: 3px;
            text-decoration: none;
        }

        .delete-button {
            background-color: #F44336;
            /* Merah untuk tombol Delete */
            color: white;
            padding: 5px 10px;
            border-radius: 3px;
            text-decoration: none;
        }

        /* Pop-up konfirmasi */
        .confirm-popup {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: white;
            border: 1px solid #ccc;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            z-index: 1000;
            width: 300px;
        }

        .confirm-popup button {
            margin-top: 10px;
            padding: 8px 12px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .confirm-popup .confirm {
            background-color: #F44336;
            /* Merah untuk tombol Konfirmasi */
            color: white;
        }

        .confirm-popup .cancel {
            background-color: #007BFF;
            /* Biru untuk tombol Batal */
            color: white;
        }

        .overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 999;
        }

        h2 {
            color: #1d3c74;
            border-bottom: 4px solid orange;
            padding-bottom: 5px;
            margin-bottom: 20px;
        }
    </style>
</head>

<body>
    <header>
        <div class="logos">
            <img src="https://upload.wikimedia.org/wikipedia/id/thumb/4/4d/UK_PETRA_LOGO.svg/1200px-UK_PETRA_LOGO.svg.png"
                alt="Petra Logo">
            <img src="https://petra.ac.id/img/logo-text.2e8a4502.png" alt="PCU Logo">
        </div>
        <div class="user-box" onclick="togglePopup()">
            <span>
                <?php echo $_SESSION['nama']; ?>
            </span>
            <i class="fas fa-user-circle"></i>
        </div>
        <div class="user-popup" id="userPopup">
            <p><strong>Nama:</strong>
                <?php echo $_SESSION['nama']; ?>
            </p>
            <p><strong>Email:</strong>
                <?php echo $_SESSION['email']; ?>
            </p>
            <p><strong>Role:</strong>
                <?php echo $_SESSION['role']; ?>
            </p>
            <form id="logoutForm" action="../php/logout.php" method="post" style="display: inline;">
                <button type="button" onclick="confirmLogout()">Logout</button>
            </form>
        </div>
    </header>

    <div class="menu-section">
        <div class="button-container">
            <a href="home.php" class="back-button">Kembali</a>
            <a href="pengguna_tambah.php" class="add-button">Tambah Pengguna</a>
        </div>
        <h2>Daftar Pengguna</h2>
        <table>
            <thead>
                <tr>
                    <th>ID Pengguna</th>
                    <th>Nama Pengguna</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Last Login</th>
                    <th>Akses Lab</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td>
                                <?php echo $row['id_pengguna']; ?>
                            </td>
                            <td>
                                <?php echo $row['nama_pengguna']; ?>
                            </td>
                            <td>
                                <?php echo $row['email']; ?>
                            </td>
                            <td>
                                <?php echo $row['role']; ?>
                            </td>
                            <td>
                                <?php echo $row['last_login']; ?>
                            </td>
                            <td>
                                <?php echo $row['lab_akses']; ?>
                            </td>
                            <td>
                                <a href="pengguna_edit.php?id=<?php echo $row['id_pengguna']; ?>"
                                    class="edit-button">Edit</a>
                                <a href="#" class="delete-button"
                                    onclick="confirmDelete('<?php echo $row['nama_pengguna']; ?>', '<?php echo $row['email']; ?>', '<?php echo $row['id_pengguna']; ?>')">Delete</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7">Tidak ada data pengguna.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Overlay untuk pop-up -->
    <div class="overlay" id="overlay"></div>

    <!-- Pop-up konfirmasi -->
    <div class="confirm-popup" id="confirmPopup">
        <p>Apakah Anda yakin ingin menghapus pengguna <span id="userName"></span> (Email: <span
                id="userEmail"></span>)?</p>
        <button class="confirm" onclick="deleteUser()">Ya, Hapus</button>
        <button class="cancel" onclick="closeConfirmPopup()">Batal</button>
    </div>

    <script>
        // Fungsi JavaScript yang sudah ada
        function togglePopup() {
            const popup = document.getElementById("userPopup");
            popup.style.display = popup.style.display === "block" ? "none" : "block";
        }
        window.onclick = function (event) {
            const popup = document.getElementById("userPopup");
            if (!event.target.closest(".user-box") && !event.target.closest("#userPopup")) {
                popup.style.display = "none";
            }
        };

        function confirmDelete(name, email, id) {
            document.getElementById("userName").textContent = name;
            document.getElementById("userEmail").textContent = email;
            document.getElementById("confirmPopup").style.display = "block";
            document.getElementById("overlay").style.display = "block";
            window.currentUserId = id; // Menyimpan ID pengguna yang akan dihapus
        }

        function closeConfirmPopup() {
            document.getElementById("confirmPopup").style.display = "none";
            document.getElementById("overlay").style.display = "none";
        }

        function deleteUser() {
            window.location.href = "pengguna_delete.php?id=" + window.currentUserId; // Redirect ke halaman delete
        }

        function confirmLogout() {
            const confirmation = confirm("Apakah Anda yakin ingin logout?");
            if (confirmation) {
                document.getElementById("logoutForm").submit();
            }
        }
    </script>
</body>

</html>

<?php
$stmt->close();
$conn->close(); // Tutup koneksi database
?>
