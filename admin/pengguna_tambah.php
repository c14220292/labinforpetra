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
// Ambil semua lab
$lab_query = $conn->query("SELECT kode_lab, nama_lab FROM laboratorium");
$semua_lab = [];
while ($row = $lab_query->fetch_assoc()) {
    $semua_lab[] = $row;
}

$lab_akses = []; // Untuk halaman tambah, tidak ada lab akses default
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Tambah Pengguna - Petra Informatics Lab</title>
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

        .menu-section {
            padding: 20px 40px;
        }

        h2 {
            color: #1d3c74;
            border-bottom: 4px solid orange;
            padding-bottom: 5px;
            margin-bottom: 20px;
        }

        input[type="text"],
        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 12px;
            margin-bottom: 12px;
            border: none;
            background-color: #d3d3d3;
            font-size: 16px;
            border-radius: 4px;
        }

        select {
            width: 100%;
            padding: 12px;
            margin-top: 12px;
            margin-bottom: 12px;
            border: none;
            background-color: #d3d3d3;
            font-size: 16px;
            border-radius: 4px;
        }

        .checkbox-group {
            margin-bottom: 12px;
        }

        .lab-buttons {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            margin: 20px 0;
        }

        .lab-chip {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            width: 120px;
            height: 65px;
            border-radius: 12px;
            color: white;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.25s ease;
            user-select: none;
            text-align: center;
            padding: 5px;
        }

        .lab-chip .kode {
            font-size: 16px;
        }

        .lab-chip .nama {
            font-size: 13px;
        }

        .lab-chip input[type="checkbox"] {
            display: none;
        }

        .lab-chip.orange {
            background: linear-gradient(145deg, #f7941d, #f26522);
        }

        .lab-chip.blue {
            background: linear-gradient(145deg, #3b4b79, #24345c);
        }

        .lab-chip.active {
            border: 2px solid white;
            box-shadow: 0 0 0 3px black;
        }

        .button-row {
            display: flex;
            gap: 44px;
        }

        .button-row a,
        .button-row button {
            flex: 1;
            text-align: center;
            font-weight: bold;
            font-size: 16px;
            padding: 12px;
            border-radius: 4px;
            cursor: pointer;
        }

        .back-button {
            background-color: #d9d9d9;
            border: 1px solid #333;
            color: black;
            text-decoration: none;
        }

        .confirm-button {
            background-color: #1d2f5c;
            color: white;
            border: none;
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

    <div class="menu-section">
        <h2>Tambah Pengguna</h2>
        <form action="tambah_pengguna.php" method="POST" onsubmit="return validateForm()">
            <input type="text" name="nama_pengguna" placeholder="Nama Lengkap" required />
            <input type="email" name="email" placeholder="Email" required />
            <input type="password" name="password" id="password" placeholder="Password" required />
            <input type="password" name="confirm_password" id="confirm_password" placeholder="Konfirmasi Password"
                required />
            <label><strong>Pilih Akses Lab:</strong></label><br>
            <div class="lab-buttons">
                <?php
                $index = 0;
                foreach ($semua_lab as $lab):
                    $kode = htmlspecialchars($lab['kode_lab']);
                    $nama = htmlspecialchars($lab['nama_lab']);
                    $isChecked = in_array($lab['kode_lab'], $lab_akses);
                    $warna = ($index % 2 === 0) ? 'orange' : 'blue'; // Warna bergantian
                ?>
                    <label class="lab-chip <?= $warna ?> <?= $isChecked ? 'active' : '' ?>">
                        <input type="checkbox" name="lab_akses[]" value="<?= $lab['kode_lab'] ?>" <?= $isChecked ? 'checked' : '' ?> hidden>
                        <div class="kode"><?= $kode ?></div>
                        <div class="nama"><?= $nama ?></div>
                    </label>
                <?php
                    $index++;
                endforeach;
                ?>
            </div>

            <br><br>
            <div class="button-row">
                <a href="pengguna.php" class="back-button">Kembali</a>
                <button class="confirm-button" type="submit">Simpan Pengguna</button>
            </div>
        </form>
    </div>

    <script>
        function togglePopup() {
            const popup = document.getElementById("userPopup");
            popup.style.display = popup.style.display === "block" ? "none" : "block";
        }

        document.querySelectorAll('.lab-chip').forEach(chip => {
            chip.addEventListener('click', (e) => {
                e.preventDefault(); // Mencegah perilaku default label/input
                const checkbox = chip.querySelector('input[type="checkbox"]');
                checkbox.checked = !checkbox.checked;
                chip.classList.toggle('active');
            });
        });

        window.onclick = function(event) {
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

        function validateForm() {
            const password = document.getElementById("password").value;
            const confirm_password = document.getElementById("confirm_password").value;

            if (password !== confirm_password) {
                alert("Password dan Konfirmasi Password tidak sama!");
                return false; // Mencegah form untuk disubmit
            }

            return confirm('Apakah Anda yakin ingin menyimpan pengguna ini?'); // Konfirmasi setelah validasi
        }
    </script>
</body>

</html>

<?php
$conn->close(); // Tutup koneksi database
?>
