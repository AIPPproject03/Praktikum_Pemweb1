<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Pegawai AIPP</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <div class="container">
        <h2>AIPP Employee Management</h2>
        <!-- Form input pegawai -->
        <form action="insert.php" method="POST">
            <input type="text" name="id_pegawai" placeholder="ID Pegawai (Manual)">
            <input type="text" name="nama" placeholder="Nama Pegawai" required>
            <input type="text" name="alamat" placeholder="Alamat" required>
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <select name="jabatan" required>
                <option value="">Pilih Jabatan</option>
                <?php
                // Koneksi ke database localhost PHPMyAdmin
                $conn = new mysqli('localhost', 'root', '', 'bengkel_mobil');

                if ($conn->connect_error) {
                    die("Koneksi gagal: " . $conn->connect_error);
                }

                // Ambil data jabatan dari tabel jabatan
                $sql = "SELECT * FROM Jabatan";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<option value='" . $row["ID_Jabatan"] . "'>" . $row["Nama_Jabatan"] . "</option>";
                    }
                }

                $conn->close();
                ?>
            </select>
            <button type="submit" name="submit_insert">Simpan Pegawai</button>
        </form>

        <!-- Form hapus pegawai -->
        <form action="delete.php" method="POST">
            <input type="text" name="id_delete" placeholder="ID Pegawai yang akan dihapus" required>
            <button type="submit" name="submit_delete">Hapus Pegawai</button>
        </form>

        <!-- Form update pegawai -->
        <form action="update.php" method="POST">
            <input type="text" name="id_update" placeholder="ID Pegawai yang akan diubah" required>
            <input type="text" name="nama_baru" placeholder="Nama Pegawai Baru">
            <input type="text" name="alamat_baru" placeholder="Alamat Baru">
            <input type="text" name="username_baru" placeholder="Username Baru">
            <input type="password" name="password_baru" placeholder="Password Baru">
            <button type="submit" name="submit_update">Update Pegawai</button>
        </form>

        <!-- Tabel pegawai -->
        <h3>Data Pegawai</h3>
        <table border="1" cellspacing="0" cellpadding="5">
            <tr>
                <th>ID Pegawai</th>
                <th>Nama</th>
                <th>Alamat</th>
                <th>Username</th>
                <th>Password</th>
                <th>Jabatan</th>
            </tr>
            <?php
            // Koneksi ke database localhost PHPMyAdmin
            $conn = new mysqli('localhost', 'root', '', 'bengkel_mobil');

            if ($conn->connect_error) {
                die("Koneksi gagal: " . $conn->connect_error);
            }

            // Ambil data pegawai dari tabel pegawai dengan join ke tabel jabatan
            $sql = "SELECT Pegawai.ID_Pegawai, Pegawai.Nama_Pegawai, Pegawai.Alamat, Pegawai.Username, Pegawai.Password, Jabatan.Nama_Jabatan 
                    FROM Pegawai 
                    JOIN Jabatan ON Pegawai.ID_Jabatan = Jabatan.ID_Jabatan";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row["ID_Pegawai"] . "</td>";
                    echo "<td>" . $row["Nama_Pegawai"] . "</td>";
                    echo "<td>" . $row["Alamat"] . "</td>";
                    echo "<td>" . $row["Username"] . "</td>";
                    echo "<td>" . $row["Password"] . "</td>";
                    echo "<td>" . $row["Nama_Jabatan"] . "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='6'>Tidak ada data pegawai</td></tr>";
            }

            $conn->close();
            ?>
        </table>

        <!-- Pesan peringatan -->
        <?php
        session_start();
        if (isset($_SESSION['error'])) {
            $error_message = $_SESSION['error'];
            if ($error_message == 'id_pegawai_sudah_ada') {
                echo "<p style='color: red;'>ID Pegawai sudah ada dalam database!</p>";
            } elseif ($error_message == 'id_pegawai_tidak_ditemukan') {
                echo "<p style='color: red;'>ID Pegawai tidak ditemukan dalam database!</p>";
            }
            // Hapus pesan dari session setelah ditampilkan
            unset($_SESSION['error']);
        }

        // Pesan sukses
        if (isset($_SESSION['success'])) {
            $success_message = $_SESSION['success'];
            if ($success_message == 'pegawai_ditambahkan') {
                echo "<p style='color: green;'>Pegawai berhasil ditambahkan!</p>";
            } elseif ($success_message == 'pegawai_dihapus') {
                echo "<p style='color: green;'>Pegawai berhasil dihapus!</p>";
            } elseif ($success_message == 'pegawai_diupdate') {
                echo "<p style='color: green;'>Pegawai berhasil diupdate!</p>";
            }
            // Hapus pesan dari session setelah ditampilkan
            unset($_SESSION['success']);
        }
        ?>
    </div>
</body>

</html>