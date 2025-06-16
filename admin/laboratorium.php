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

// Ambil data laboratorium
$sql = "SELECT * FROM laboratorium";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Laboratorium</title>
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

        .main {
            padding: 20px 40px;
        }

        .top-buttons {
            display: flex;
            gap: 24px;
            margin-bottom: 30px;
        }

        .top-buttons .btn {
            width: 240px;
            height: 140px;
            color: white;
            border: none;
            border-radius: 5px;
            font-weight: bold;
            cursor: pointer;
            font-size: 24px;
        }

        .btn-all {
            background: linear-gradient(135deg, #f7941d, #f26522);
        }

        .btn-tambah {
            background: #999;
        }

        .btn-hapus {
            background: #111;
        }

        .btn-edit {
            background: linear-gradient(135deg, #24345C, #44547C);
        }

        h2 {
            border-bottom: 4px solid orange;
            padding-bottom: 5px;
            margin-bottom: 15px;
            color: #1d3c74;
        }

        .lab-container {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
            gap: 20px;
        }

        .lab-box {
            width: 240px;
            height: 140px;
            background: linear-gradient(135deg, #f7941d, #f26522);
            color: white;
            padding: 40px;
            border-radius: 10px;
            text-align: center;
            cursor: pointer;
            transition: transform 0.3s, box-shadow 0.3s;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        }

        .lab-title {
            font-size: 36px;
            font-weight: bold;
            margin-bottom: 6px;
        }

        /* CSS untuk semua popup */
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

        .edit-popup,
        .delete-popup {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.7);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 1000;
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.3s, visibility 0.3s;
        }

        .edit-popup.show,
        .delete-popup.show {
            opacity: 1;
            visibility: visible;
            display: flex;
        }

        .popup-content {
            background: white;
            padding: 25px;
            border-radius: 10px;
            text-align: center;
            width: 400px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
            transform: translateY(-20px);
            transition: transform 0.3s;
        }

        .edit-popup.show .popup-content,
        .delete-popup.show .popup-content {
            transform: translateY(0);
        }


        .popup-content {
            background: white;
            padding: 25px;
            border-radius: 10px;
            text-align: center;
            width: 400px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
            animation: popupFadeIn 0.3s ease-out;
        }

        @keyframes popupFadeIn {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .popup-content h3 {
            color: #1d3c74;
            margin-top: 0;
            margin-bottom: 20px;
            font-size: 1.5em;
        }

        .popup-content label {
            font-weight: bold;
            margin-bottom: 10px;
            display: block;
            color: #333;
            text-align: left;
        }

        .popup-content select {
            padding: 12px;
            font-size: 14px;
            border-radius: 6px;
            border: 1px solid #ddd;
            width: 100%;
            margin-bottom: 20px;
            background-color: #f9f9f9;
            transition: border 0.3s;
        }

        .popup-content select:focus {
            border-color: #f7941d;
            outline: none;
        }

        .popup-buttons {
            display: flex;
            gap: 10px;
            margin-top: 15px;
        }

        .popup-content button {
            padding: 12px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            flex: 1;
            font-weight: bold;
            transition: all 0.3s;
        }

        .popup-content button:first-child {
            background-color: #f26522;
            color: white;
        }

        .popup-content button:last-child {
            background-color: #e0e0e0;
            color: white;
        }

        .popup-content button:hover {
            color: white;
            opacity: 0.9;
            transform: translateY(-2px);
        }

        .delete-popup-btn, .edit-popup-btn {
            background:  linear-gradient(135deg, #f7941d, #f26522);
            color: white;
        }

        .back-popup-btn {
            background: linear-gradient(135deg, #24345C, #44547C);
            color: white;
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
    
    <main class="main">
        <a href="home.php">
            <button class="back-button">
                Kembali
            </button>
        </a>

        <div class="top-buttons">
            <button class="btn btn-tambah" onclick="window.location.href='laboratorium_tambah.php'"><i
                    class="fas fa-folder-plus"></i> Tambah Lab</button>
            <button class="btn btn-hapus" onclick="toggleDeletePopup()"><i class="fas fa-folder-minus"></i> Hapus
                Lab</button>
            <button class="btn btn-edit" onclick="toggleEditPopup()"><i class="fas fa-folder-minus"></i> Edit
                Lab</button>
        </div>

        <h2>PILIH LABORATORIUM</h2>

        <div class="lab-container">
            <?php if ($result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <div class="lab-box">
                        <div class="lab-title"><?php echo $row['kode_lab']; ?></div>
                        <div><?php echo $row['nama_lab']; ?></div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p>Tidak ada data laboratorium.</p>
            <?php endif; ?>
        </div>
    </main>

    <!-- Popup untuk memilih lab -->
    <div class="edit-popup" id="editPopup">
        <div class="popup-content">
            <h3>Pilih Laboratorium untuk Diedit</h3>
            <label for="labSelect">Laboratorium:</label>
            <select id="labSelect">
                <option value="">-- Pilih Laboratorium --</option>
                <?php
                // Reset result pointer to fetch data again
                $result->data_seek(0); // Reset pointer
                if ($result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <option value="<?php echo $row['id_lab']; ?>"><?php echo $row['kode_lab'] . ' - ' . $row['nama_lab']; ?>
                        </option>
                    <?php endwhile; ?>
                <?php else: ?>
                    <option value="">Tidak ada laboratorium</option>
                <?php endif; ?>
            </select>
            <button class="back-popup-btn" onclick="toggleEditPopup()">Tutup</button>
            <button class="edit-popup-btn" onclick="editLab()">Edit Lab</button>
        </div>
    </div>

    <!-- Popup untuk memilih lab yang akan dihapus -->
    <div class="delete-popup" id="deletePopup">
        <div class="popup-content">
            <h3>Pilih Laboratorium untuk Dihapus</h3>
            <label for="deleteLabSelect">Laboratorium:</label>
            <select id="deleteLabSelect">
                <option value="">-- Pilih Laboratorium --</option>
                <?php
                // Reset result pointer to fetch data again
                $result->data_seek(0); // Reset pointer
                if ($result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <option value="<?php echo $row['id_lab']; ?>"><?php echo $row['kode_lab'] . ' - ' . $row['nama_lab']; ?>
                        </option>
                    <?php endwhile; ?>
                <?php else: ?>
                    <option value="">Tidak ada laboratorium</option>
                <?php endif; ?>
            </select>
            <button class="back-popup-btn" onclick="toggleDeletePopup()">Tutup</button>
            <button class="delete-popup-btn" onclick="confirmDeleteLab()">Hapus Lab</button>
        </div>
    </div>

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

        function toggleDeletePopup() {
            const popup = document.getElementById("deletePopup");
            popup.classList.toggle("show"); // Gunakan class toggle bukan style.display
        }

        function toggleEditPopup() {
            const popup = document.getElementById("editPopup");
            popup.classList.toggle("show");
        }

        function confirmDeleteLab() {
            const labSelect = document.getElementById("deleteLabSelect");
            const selectedId = labSelect.value;
            if (selectedId) {
                if (confirm("Apakah Anda yakin ingin menghapus laboratorium ini?")) {
                    window.location.href = 'laboratorium_delete.php?id=' + selectedId;
                }
            } else {
                alert("Silakan pilih laboratorium untuk dihapus.");
            }
        }

        function editLab() {
            const labSelect = document.getElementById("labSelect");
            const selectedId = labSelect.value;
            if (selectedId) {
                window.location.href = 'laboratorium_edit.php?id=' + selectedId;
            } else {
                alert("Silakan pilih laboratorium untuk diedit.");
            }
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
$conn->close(); // Tutup koneksi database
?>