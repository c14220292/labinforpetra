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

// Ambil data laboratorium berdasarkan ID
$id_lab = $_GET['id'] ?? '';
if (!$id_lab) {
    echo "ID laboratorium tidak ditemukan.";
    exit();
}

$sql = "SELECT * FROM laboratorium WHERE id_lab = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $id_lab);
$stmt->execute();
$result = $stmt->get_result();
$laboratorium = $result->fetch_assoc();

if (!$laboratorium) {
    echo "Laboratorium tidak ditemukan.";
    exit();
}

// Proses pembaruan laboratorium
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $kode_lab = $_POST['kode_lab'];
    $nama_lab = $_POST['nama_lab'];
    $gedung = $_POST['gedung'];

    // Query untuk memperbarui laboratorium
    $sql = "UPDATE laboratorium SET kode_lab = ?, nama_lab = ?, gedung = ? WHERE id_lab = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssd", $kode_lab, $nama_lab, $gedung, $id_lab);

    if ($stmt->execute()) {
        header("Location: laboratorium.php?message=Laboratorium berhasil diperbarui");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Edit Laboratorium</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
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

        main {
            padding: 30px 40px;
        }

        h2 {
            color: #1d3c74;
            border-bottom: 4px solid orange;
            padding-bottom: 5px;
            margin-bottom: 20px;
        }

        form {
            display: flex;
            flex-direction: column;
            max-width: 400px;
            gap: 15px;
        }

        label {
            font-weight: bold;
        }

        input,
        select {
            padding: 10px;
            font-size: 14px;
            border-radius: 4px;
            border: 1px solid #ccc;
        }

        button[type="submit"] {
            background-color: #f26522;
            color: white;
            border: none;
            padding: 10px;
            border-radius: 6px;
            font-weight: bold;
            cursor: pointer;
        }

        .back-button {
            background-color: lightgray;
            color: black;
            border: none;
            padding: 10px;
            margin-bottom: 24px;
            border-radius: 6px;
            font-weight: bold;
            cursor: pointer;
        }
    </style>
</head>

<body>
    <header>
        <div class="logos">
            <img src="https://upload.wikimedia.org/wikipedia/id/thumb/4/4d/UK_PETRA_LOGO.svg/1200px-UK_PETRA_LOGO.svg.png"
                alt="Petra Logo" />
            <img src="https://petra.ac.id/img/logo-text.2e8a4502.png" alt="PCU Logo" />
        </div>
        <div class="user-box" onclick="togglePopup()">
            <span><?php echo $_SESSION['nama']; ?></span>
            <i class="fas fa-user-circle"></i>
        </div>
        <div class="user-popup" id="userPopup">
            <p><strong>Nama:</strong> <?php echo $_SESSION['nama']; ?></p>
            <p><strong>Email:</strong> <?php echo $_SESSION['email']; ?></p>
            <p><strong>Role:</strong> <?php echo $_SESSION['role']; ?></p>
            <form id="logoutForm" action="../php/logout.php" method="post" style="display: inline;">
                <button type="button" onclick="confirmLogout()">Logout</button>
            </form>
        </div>
    </header>

    <main>
        <a href="laboratorium.php">
            <button class="back-button">
                Kembali
            </button>
        </a>
        <h2>Edit Laboratorium</h2>
        <form method="POST" action="" onsubmit="return confirm('Apakah Anda yakin ingin menambahkan laboratorium ini?');">
            <label for="kode_lab">Kode Lab</label>
            <input type="text" id="kode_lab" name="kode_lab" value="<?php echo htmlspecialchars($laboratorium['kode_lab']); ?>" required>

            <label for="nama_lab">Nama Lab</label>
            <input type="text" id="nama_lab" name="nama_lab" value="<?php echo htmlspecialchars($laboratorium['nama_lab']); ?>" required>

            <label for="gedung">Gedung</label>
            <select id="gedung" name="gedung" required>
                <option value="P" <?php echo ($laboratorium['gedung'] == 'P') ? 'selected' : ''; ?>>P</option>
                <option value="T" <?php echo ($laboratorium['gedung'] == 'T') ? 'selected' : ''; ?>>T</option>
            </select>

            <button type="submit">Perbarui Lab</button>
        </form>
    </main>

    <script>
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
$conn->close(); // Tutup koneksi database
?>