<?php
$host = "localhost";
$username = "root";
$password = "";
$database = "Pemilu";

$koneksi = mysqli_connect($host, $username, $password, $database);

if (!$koneksi) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

$query = "SELECT K.nama_kandidat, JS.jumlah_suara 
          FROM Kandidat K INNER JOIN Jumlah_Suara JS ON K.id_kandidat = JS.id_kandidat";
$result = mysqli_query($koneksi, $query);

$data = [];
while ($row = mysqli_fetch_assoc($result)) {
    $data[] = $row;
}

echo json_encode($data);

mysqli_close($koneksi);
