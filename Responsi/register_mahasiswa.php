<?php
include 'conn.php';

// Tambahkan Mahasiswa
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add'])) {
    $nama_mahasiswa = $_POST['nama_mahasiswa'];
    $id_jurusan = $_POST['id_jurusan'];
    $id_dospem = $_POST['id_dospem'];

    $stmt = $conn->prepare("CALL input_mahasiswa(?, ?, ?)");
    $stmt->bind_param("sss", $nama_mahasiswa, $id_jurusan, $id_dospem);

    if ($stmt->execute()) {
        echo "Rekaman baru berhasil dibuat";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

// Edit Mahasiswa
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['edit'])) {
    $nim = $_POST['edit_nim'];
    $nama_mahasiswa = $_POST['edit_nama_mahasiswa'];
    $id_jurusan = $_POST['edit_id_jurusan'];
    $id_dospem = $_POST['edit_id_dospem'];

    $stmt = $conn->prepare("UPDATE Mahasiswa SET Nama_Mahasiswa=?, ID_Jurusan=?, ID_Dospem=? WHERE NIM=?");
    $stmt->bind_param("ssss", $nama_mahasiswa, $id_jurusan, $id_dospem, $nim);

    if ($stmt->execute()) {
        echo "Rekaman berhasil diperbarui";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

// Hapus Mahasiswa
if (isset($_GET['delete'])) {
    $nim = $_GET['delete'];
    $conn->query("DELETE FROM Mahasiswa WHERE NIM='$nim'");
    header("Location: register_mahasiswa.php");
}

// Ambil Mahasiswa
$result = $conn->query("SELECT m.NIM, m.Nama_Mahasiswa, j.Nama_Jurusan, d.Nama_Dospem, j.ID_Jurusan, d.ID_Dospem FROM Mahasiswa m JOIN Jurusan j ON m.ID_Jurusan = j.ID_Jurusan JOIN Dospem d ON m.ID_Dospem = d.ID_Dospem");

// Ambil Jurusan dan Dospem
$jurusanResult = $conn->query("SELECT * FROM Jurusan");
$dospemResult = $conn->query("SELECT * FROM Dospem");
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi Mahasiswa</title>
    <link rel="stylesheet" href="regis_mhs.css">
</head>

<body>
    <div class="container">
        <h1>Registrasi Mahasiswa</h1>

        <!-- Tombol Return ke index.php -->
        <button class="return-btn" onclick="window.location.href='index.php'">Return</button>

        <form id="addForm" method="post" action="">
            <label for="nama_mahasiswa">Nama Mahasiswa:</label>
            <input type="text" id="nama_mahasiswa" name="nama_mahasiswa" required>

            <label for="id_jurusan">Jurusan:</label>
            <select id="id_jurusan" name="id_jurusan" required>
                <?php while ($row = $jurusanResult->fetch_assoc()) : ?>
                    <option value="<?php echo $row['ID_Jurusan']; ?>"><?php echo $row['Nama_Jurusan']; ?></option>
                <?php endwhile; ?>
            </select>

            <label for="id_dospem">Dosen Pembimbing:</label>
            <select id="id_dospem" name="id_dospem" required>
                <?php while ($row = $dospemResult->fetch_assoc()) : ?>
                    <option value="<?php echo $row['ID_Dospem']; ?>"><?php echo $row['Nama_Dospem']; ?></option>
                <?php endwhile; ?>
            </select>

            <button type="submit" name="add">Tambah Mahasiswa</button>
        </form>

        <h2>Data Mahasiswa Terdaftar</h2>
        <table id="mahasiswaTable">
            <thead>
                <tr>
                    <th>NIM</th>
                    <th>Nama Mahasiswa</th>
                    <th>Jurusan</th>
                    <th>Dosen Pembimbing</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()) : ?>
                    <tr>
                        <td><?php echo $row['NIM']; ?></td>
                        <td><?php echo $row['Nama_Mahasiswa']; ?></td>
                        <td><?php echo $row['Nama_Jurusan']; ?></td>
                        <td><?php echo $row['Nama_Dospem']; ?></td>
                        <td>
                            <button class="edit-btn" data-nim="<?php echo $row['NIM']; ?>" data-nama="<?php echo $row['Nama_Mahasiswa']; ?>" data-jurusan="<?php echo $row['ID_Jurusan']; ?>" data-dospem="<?php echo $row['ID_Dospem']; ?>">Edit</button>
                            <button class="delete-btn" data-nim="<?php echo $row['NIM']; ?>">Hapus</button>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <div id="editModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Edit Mahasiswa</h2>
            <form id="editForm" method="post" action="">
                <input type="hidden" id="edit_nim" name="edit_nim">
                <label for="edit_nama_mahasiswa">Nama Mahasiswa:</label>
                <input type="text" id="edit_nama_mahasiswa" name="edit_nama_mahasiswa" required>
                <label for="edit_id_jurusan">Jurusan:</label>
                <select id="edit_id_jurusan" name="edit_id_jurusan" required>
                    <?php $jurusanResult->data_seek(0); ?>
                    <?php while ($row = $jurusanResult->fetch_assoc()) : ?>
                        <option value="<?php echo $row['ID_Jurusan']; ?>"><?php echo $row['Nama_Jurusan']; ?></option>
                    <?php endwhile; ?>
                </select>
                <label for="edit_id_dospem">Dosen Pembimbing:</label>
                <select id="edit_id_dospem" name="edit_id_dospem" required>
                    <?php $dospemResult->data_seek(0); ?>
                    <?php while ($row = $dospemResult->fetch_assoc()) : ?>
                        <option value="<?php echo $row['ID_Dospem']; ?>"><?php echo $row['Nama_Dospem']; ?></option>
                    <?php endwhile; ?>
                </select>
                <button type="submit" name="edit">Perbarui Mahasiswa</button>
            </form>
        </div>
    </div>

    <script>
        // Dapatkan modal
        var modal = document.getElementById('editModal');

        // Dapatkan tombol yang membuka modal
        var btns = document.getElementsByClassName("edit-btn");

        // Dapatkan elemen <span> yang menutupi modal
        var span = document.getElementsByClassName("close")[0];

        // Ketika pengguna mengklik tombol, buka modal
        for (var i = 0; i < btns.length; i++) {
            btns[i].onclick = function() {
                var nim = this.getAttribute("data-nim");
                var nama = this.getAttribute("data-nama");
                var jurusan = this.getAttribute("data-jurusan");
                var dospem = this.getAttribute("data-dospem");

                document.getElementById("edit_nim").value = nim;
                document.getElementById("edit_nama_mahasiswa").value = nama;
                document.getElementById("edit_id_jurusan").value = jurusan;
                document.getElementById("edit_id_dospem").value = dospem;
                modal.style.display = "block";
            }
        }

        // Ketika pengguna mengklik <span> (x), tutup modal
        span.onclick = function() {
            modal.style.display = "none";
        }

        // Ketika pengguna mengklik di luar modal, tutup modal
        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }

        // Tambahkan event listener untuk tombol delete
        var deleteButtons = document.querySelectorAll('.delete-btn');
        deleteButtons.forEach(function(button) {
            button.addEventListener('click', function() {
                var nim = this.getAttribute("data-nim");
                if (confirm("Apakah Anda yakin ingin menghapus mahasiswa ini?")) {
                    window.location.href = "register_mahasiswa.php?delete=" + nim;
                }
            });
        });
    </script>
</body>

</html>