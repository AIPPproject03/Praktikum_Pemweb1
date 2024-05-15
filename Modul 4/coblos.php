<?php
session_start();

// Cek apakah pengguna sudah login, jika belum arahkan ke halaman login.php
if (!isset($_SESSION["id_pemilih"])) {
    header("location: login.php");
    exit;
}

// Cek apakah pengguna sudah mencoblos, jika sudah arahkan ke halaman index.php
if (isset($_SESSION["sudah_coblos"])) {
    header("location: index.php");
    exit;
}

// Proses coblos
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Menerima data dari formulir
    $id_kandidat = $_POST["kandidat"];

    // Koneksi ke database
    $host = "localhost";
    $username = "root";
    $password_db = "";
    $database = "Pemilu";

    $koneksi = mysqli_connect($host, $username, $password_db, $database);

    if (!$koneksi) {
        die("Koneksi gagal: " . mysqli_connect_error());
    }

    // Ambil id_pemilih dari session
    $id_pemilih = $_SESSION["id_pemilih"];

    // Query untuk memasukkan data coblos ke dalam tabel Hasil_Pemilihan
    $query = "INSERT INTO Hasil_Pemilihan (id_pemilih, id_kandidat) VALUES ('$id_pemilih', '$id_kandidat')";

    if (mysqli_query($koneksi, $query)) {
        // Set session sudah_coblos menjadi true
        $_SESSION["sudah_coblos"] = true;
        header("location: index.php");
        exit;
    } else {
        echo "Error: " . $query . "<br>" . mysqli_error($koneksi);
    }

    mysqli_close($koneksi);
}
?>
<!DOCTYPE html>
<html>

<head>
    <title>Coblos</title>
    <link rel="stylesheet" href="coblos.css">
</head>

<body>
    <div id="container">
        <div id="coblos-form" class="form-container">
            <h2>Coblos</h2>
            <?php
            // Koneksi ke database
            $host = "localhost";
            $username = "root";
            $password_db = "";
            $database = "Pemilu";

            $koneksi = mysqli_connect($host, $username, $password_db, $database);

            if (!$koneksi) {
                die("Koneksi gagal: " . mysqli_connect_error());
            }

            // Query untuk mengambil data kandidat
            $query = "SELECT * FROM Kandidat";
            $result = mysqli_query($koneksi, $query);

            // Tampilkan opsi kandidat dalam dropdown
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<div class='kandidat-info'>";
                echo "<h3>" . $row['nama_kandidat'] . "</h3>";
                echo "<p>Partai: " . $row['partai_kandidat'] . "</p>";
                echo "<p>Motto: " . $row['motto_kandidat'] . "</p>";
                echo "<form action='coblos.php' method='post'>";
                echo "<button type='submit' class='btn' name='kandidat' value='" . $row['id_kandidat'] . "'>Coblos</button>";
                echo "</form>";
                echo "</div>";
            }

            mysqli_close($koneksi);
            ?>
        </div>
    </div>
</body>

</html>