<?php
include 'conn.php';

// Tambahkan KRS
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_krs'])) {
    $nim = $_POST['nim'];
    $id_mk = $_POST['id_mk'];

    $stmt = $conn->prepare("CALL input_krs(?, ?)");
    $stmt->bind_param("ss", $nim, $id_mk);

    if ($stmt->execute()) {
        echo "KRS berhasil ditambahkan";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

// Edit KRS
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['edit_krs'])) {
    $id_krs = $_POST['edit_id_krs'];
    $nim = $_POST['edit_nim'];
    $id_mk = $_POST['edit_id_mk'];

    $stmt = $conn->prepare("UPDATE KRS SET NIM=?, ID_MK=? WHERE ID_KRS=?");
    $stmt->bind_param("sss", $nim, $id_mk, $id_krs);

    if ($stmt->execute()) {
        echo "KRS berhasil diperbarui";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

// Hapus KRS
if (isset($_GET['delete'])) {
    $id_krs = $_GET['delete'];
    $conn->query("DELETE FROM KRS WHERE ID_KRS='$id_krs'");
    echo "KRS berhasil dihapus";
}

// Ambil KRS
$result = $conn->query("SELECT * FROM KRS");

// Ambil Mahasiswa
$mahasiswaResult = $conn->query("SELECT * FROM Mahasiswa");

// Ambil Mata Kuliah
$matakuliahResult = $conn->query("SELECT ID_MK, Nama_MK FROM Mata_Kuliah");

?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi KRS</title>
    <link rel="stylesheet" href="regis_mhs.css">
</head>

<body>
    <div class="container">
        <h1>Registrasi KRS</h1>
        <button class="return-btn" onclick="window.location.href='index.php'">Return</button>
        <form id="addKRSForm" method="post" action="">
            <label for="nim">NIM Mahasiswa:</label>
            <select id="nim" name="nim" required>
                <?php while ($row = $mahasiswaResult->fetch_assoc()) : ?>
                    <option value="<?php echo $row['NIM']; ?>"><?php echo $row['NIM']; ?></option>
                <?php endwhile; ?>
            </select>

            <label for="id_mk">Mata Kuliah:</label>
            <select id="id_mk" name="id_mk" required>
                <?php while ($row = $matakuliahResult->fetch_assoc()) : ?>
                    <option value="<?php echo $row['ID_MK']; ?>"><?php echo $row['Nama_MK']; ?></option>
                <?php endwhile; ?>
            </select>


            <button type="submit" name="add_krs">Tambah KRS</button>
        </form>

        <h2>Data KRS Terdaftar</h2>
        <table id="krsTable">
            <thead>
                <tr>
                    <th>ID KRS</th>
                    <th>NIM</th>
                    <th>Kode Mata Kuliah</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()) : ?>
                    <tr>
                        <td><?php echo $row['ID_KRS']; ?></td>
                        <td><?php echo $row['NIM']; ?></td>
                        <td><?php echo $row['ID_MK']; ?></td>
                        <td>
                            <button class="edit-btn" data-id-krs="<?php echo $row['ID_KRS']; ?>" data-nim="<?php echo $row['NIM']; ?>" data-id-mk="<?php echo $row['ID_MK']; ?>">Edit</button>
                            <button class="delete-btn" data-id-krs="<?php echo $row['ID_KRS']; ?>">Hapus</button>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <div id="editModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Edit KRS</h2>
            <form id="editKRSForm" method="post" action="">
                <input type="hidden" id="edit_id_krs" name="edit_id_krs">
                <label for="edit_nim">NIM Mahasiswa:</label>
                <select id="edit_nim" name="edit_nim" required>
                    <?php $mahasiswaResult->data_seek(0); ?>
                    <?php while ($row = $mahasiswaResult->fetch_assoc()) : ?>
                        <option value="<?php echo $row['NIM']; ?>"><?php echo $row['NIM']; ?></option>
                    <?php endwhile; ?>
                </select>
                <label for="edit_id_mk">Mata Kuliah:</label>
                <select id="edit_id_mk" name="edit_id_mk" required>
                    <?php $matakuliahResult->data_seek(0); ?>
                    <?php while ($row = $matakuliahResult->fetch_assoc()) : ?>
                        <option value="<?php echo $row['ID_MK']; ?>"><?php echo $row['Nama_MK']; ?></option>
                    <?php endwhile; ?>
                </select>

                <button type="submit" name="edit_krs">Perbarui KRS</button>
            </form>
        </div>
    </div>

    <script>
        var modal = document.getElementById('editModal');
        var btns = document.getElementsByClassName("edit-btn");
        var span = document.getElementsByClassName("close")[0];

        for (var i = 0; i < btns.length; i++) {
            btns[i].onclick = function() {
                var id_krs = this.getAttribute("data-id-krs");
                var nim = this.getAttribute("data-nim");
                var id_mk = this.getAttribute("data-id-mk");

                document.getElementById("edit_id_krs").value = id_krs;
                document.getElementById("edit_nim").value = nim;
                document.getElementById("edit_id_mk").value = id_mk;
                modal.style.display = "block";
            }
        }

        span.onclick = function() {
            modal.style.display = "none";
        }

        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }

        var deleteBtns = document.getElementsByClassName("delete-btn");

        for (var i = 0; i < deleteBtns.length; i++) {
            deleteBtns[i].onclick = function() {
                var id_krs = this.getAttribute("data-id-krs");
                if (confirm("Apakah Anda yakin ingin menghapus KRS ini?")) {
                    window.location.href = "register_krs.php?delete=" + id_krs;
                }
            }
        }
    </script>
</body>

</html>