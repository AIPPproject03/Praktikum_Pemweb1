<?php
session_start();

$conn = new mysqli('localhost', 'root', '', 'bengkel_mobil');

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

if (isset($_POST['submit_update'])) {
    $id_update = $_POST['id_update'];
    $nama_baru = $_POST['nama_baru'];
    $alamat_baru = $_POST['alamat_baru'];
    $username_baru = $_POST['username_baru'];
    $password_baru = $_POST['password_baru'];

    // Periksa apakah ID pegawai ada dalam database
    $check_query = "SELECT * FROM Pegawai WHERE ID_Pegawai='$id_update'";
    $result = $conn->query($check_query);
    if ($result->num_rows == 0) {
        // Jika ID pegawai tidak ada, simpan pesan error ke dalam session
        $_SESSION['error'] = 'id_pegawai_tidak_ditemukan';
    } else {
        // Lakukan update data pegawai di dalam database
        $update_query = "UPDATE Pegawai SET Nama_Pegawai='$nama_baru', Alamat='$alamat_baru', Username='$username_baru', PASSWORD='$password_baru' WHERE ID_Pegawai='$id_update'";

        if ($conn->query($update_query) === TRUE) {
            // Jika berhasil, simpan pesan sukses ke dalam session
            $_SESSION['success'] = 'pegawai_diupdate';
        } else {
            echo "Error: " . $update_query . "<br>" . $conn->error;
        }
    }
}

$conn->close();

// Redirect kembali ke halaman index.php
header("Location: index.php");
exit();
