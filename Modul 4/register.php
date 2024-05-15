<!DOCTYPE html>
<html>

<head>
    <title>Register</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <div id="container">
        <div id="register-form" class="form-container">
            <h2>Register</h2>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <div class="form-group">
                    <input type="text" name="nama" placeholder="Nama" required>
                </div>
                <div class="form-group">
                    <input type="email" name="email" placeholder="Email" required>
                </div>
                <div class="form-group">
                    <input type="password" name="password" placeholder="Password" required>
                </div>
                <div class="form-group">
                    <input type="text" name="alamat" placeholder="Alamat" required>
                </div>
                <div class="form-group">
                    <input type="tel" name="no_telp" placeholder="Nomor Telepon" required>
                </div>
                <button type="submit" class="btn" name="register">Register</button>
            </form>
            <p>Sudah punya akun? <a href="login.php">Login</a></p>
        </div>
    </div>

    <?php
    // Proses registrasi
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Menerima data dari formulir
        $nama = $_POST["nama"];
        $email = $_POST["email"];
        $password = $_POST["password"];
        $alamat = $_POST["alamat"];
        $no_telp = $_POST["no_telp"];

        // Koneksi ke database
        $host = "localhost";
        $username = "root";
        $password_db = "";
        $database = "Pemilu";

        $koneksi = mysqli_connect($host, $username, $password_db, $database);

        if (!$koneksi) {
            die("Koneksi gagal: " . mysqli_connect_error());
        }

        // Query untuk memasukkan data pemilih ke database
        $query = "INSERT INTO Pemilih (nama_pemilih, email_pemilih, password_pemilih, alamat_pemilih, no_telp) VALUES ('$nama', '$email', '$password', '$alamat', '$no_telp')";

        if (mysqli_query($koneksi, $query)) {
            echo "<p>Registrasi berhasil. Silakan <a href='login.php'>login</a>.</p>";
        } else {
            echo "Error: " . $query . "<br>" . mysqli_error($koneksi);
        }

        mysqli_close($koneksi);
    }
    ?>
</body>

</html>