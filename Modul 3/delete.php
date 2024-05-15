<?php
session_start();

$conn = new mysqli('localhost', 'root', '', 'bengkel_mobil');

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

if (isset($_POST['submit_delete'])) {
    $id_delete = $_POST['id_delete'];

    // Periksa apakah ID pegawai ada dalam database
    $check_query = "SELECT * FROM Pegawai WHERE ID_Pegawai='$id_delete'";
    $result = $conn->query($check_query);
    if ($result->num_rows == 0) {
        // Jika ID pegawai tidak ada, simpan pesan error ke dalam session
        $_SESSION['error'] = 'id_pegawai_tidak_ditemukan';
    } else {
        // Lakukan penghapusan data pegawai dari database
        $delete_query = "DELETE FROM Pegawai WHERE ID_Pegawai='$id_delete'";

        if ($conn->query($delete_query) === TRUE) {
            // Jika berhasil, simpan pesan sukses ke dalam session
            $_SESSION['success'] = 'pegawai_dihapus';
        } else {
            echo "Error: " . $delete_query . "<br>" . $conn->error;
        }
    }
}

$conn->close();

// Redirect kembali ke halaman index.php
header("Location: index.php");
exit();
