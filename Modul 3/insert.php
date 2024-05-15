<?php
session_start();

$conn = new mysqli('localhost', 'root', '', 'bengkel_mobil');

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

if (isset($_POST['submit_insert'])) {
    $id_pegawai = $_POST['id_pegawai'];
    $nama = $_POST['nama'];
    $alamat = $_POST['alamat'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $jabatan = $_POST['jabatan'];

    // Periksa apakah ID pegawai sudah ada dalam database
    $check_query = "SELECT * FROM Pegawai WHERE ID_Pegawai='$id_pegawai'";
    $result = $conn->query($check_query);
    if ($result->num_rows > 0) {
        // Jika ID pegawai sudah ada, simpan pesan error ke dalam session
        $_SESSION['error'] = 'id_pegawai_sudah_ada';
    } else {
        // Lakukan penyimpanan data pegawai ke dalam database
        $insert_query = "INSERT INTO Pegawai (ID_Pegawai, ID_Jabatan, Nama_Pegawai, Alamat, Username, PASSWORD)
                         VALUES ('$id_pegawai', '$jabatan', '$nama', '$alamat', '$username', '$password')";

        if ($conn->query($insert_query) === TRUE) {
            // Jika berhasil, simpan pesan sukses ke dalam session
            $_SESSION['success'] = 'pegawai_ditambahkan';
        } else {
            echo "Error: " . $insert_query . "<br>" . $conn->error;
        }
    }
}

$conn->close();

// Redirect kembali ke halaman index.php
header("Location: index.php");
exit();
