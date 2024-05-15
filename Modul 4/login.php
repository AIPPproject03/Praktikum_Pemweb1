<?php
session_start();

// Cek apakah pengguna sudah login, jika sudah arahkan ke coblos.php
if (isset($_SESSION["id_pemilih"])) {
    header("location: coblos.php");
    exit;
}

// Proses login
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Menerima data dari formulir
    $email = $_POST["email"];
    $password = $_POST["password"];

    // Koneksi ke database
    $host = "localhost";
    $username = "root";
    $password_db = "";
    $database = "Pemilu";

    $koneksi = mysqli_connect($host, $username, $password_db, $database);

    if (!$koneksi) {
        die("Koneksi gagal: " . mysqli_connect_error());
    }

    // Query untuk mencari pemilih berdasarkan email dan password
    $query = "SELECT * FROM Pemilih WHERE email_pemilih = '$email' AND password_pemilih = '$password'";
    $result = mysqli_query($koneksi, $query);

    if (mysqli_num_rows($result) == 1) {
        // Jika data ditemukan, cek status pemilih
        $row = mysqli_fetch_assoc($result);
        if ($row["status_pemilih"] == "Sudah Memilih") {
            // Jika pemilih sudah memilih, tampilkan pesan dan arahkan kembali ke index.php
            $error = "Anda sudah memilih!";
        } else {
            // Jika belum memilih, set session dan arahkan ke halaman coblos.php
            $_SESSION["id_pemilih"] = $row["id_pemilih"];
            header("location: coblos.php");
            exit;
        }
    } else {
        // Jika data tidak ditemukan, tampilkan pesan error
        $error = "Email atau password salah!";
    }

    mysqli_close($koneksi);
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Login</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <div id="container">
        <div id="login-form" class="form-container">
            <h2>Login</h2>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <div class="form-group">
                    <input type="email" name="email" placeholder="Email" required>
                </div>
                <div class="form-group">
                    <input type="password" name="password" placeholder="Password" required>
                </div>
                <button type="submit" class="btn" name="login">Login</button>
            </form>
            <?php
            // Tampilkan pesan error jika login gagal atau pemilih sudah memilih
            if (isset($error)) {
                echo "<p>$error</p>";
            }
            ?>
            <p>Belum punya akun? <a href="register.php">Register</a></p>
        </div>
    </div>
</body>

</html>