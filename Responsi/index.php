<?php
include 'conn.php';

if (isset($_POST['getChartData'])) {
    $query = "SELECT Nama_MK, COUNT(KRS.NIM) as Jumlah_Mahasiswa FROM KRS 
              JOIN Mata_Kuliah ON KRS.ID_MK = Mata_Kuliah.ID_MK 
              GROUP BY Nama_MK";
    $result = $conn->query($query);

    $labels = [];
    $counts = [];
    $colors = [];
    $borderColors = [];
    $total = 0;

    function random_color_part()
    {
        return str_pad(dechex(mt_rand(0, 255)), 2, '0', STR_PAD_LEFT);
    }

    function random_color()
    {
        return random_color_part() . random_color_part() . random_color_part();
    }

    while ($row = $result->fetch_assoc()) {
        $labels[] = $row['Nama_MK'];
        $counts[] = $row['Jumlah_Mahasiswa'];
        $color = random_color();
        $colors[] = '#' . $color;
        $borderColors[] = '#' . $color;
        $total += $row['Jumlah_Mahasiswa'];
    }

    echo json_encode([
        'labels' => $labels,
        'counts' => $counts,
        'colors' => $colors,
        'borderColors' => $borderColors,
        'total' => $total
    ]);
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal Admin Universitas Agustinus</title>
    <link rel="stylesheet" href="style.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body>
    <nav class="navbar">
        <div class="container">
            <h1>Portal Admin Universitas Agustinus</h1>
            <ul>
                <li><a href="register_mahasiswa.php">Registrasi Mahasiswa</a></li>
                <li><a href="register_krs.php">Registrasi KRS</a></li>
            </ul>
        </div>
    </nav>

    <div class="content">
        <h2>Data Mahasiswa Terdaftar</h2>
        <table>
            <thead>
                <tr>
                    <th>Nama Mahasiswa</th>
                    <th>Jurusan</th>
                    <th>Dosen Pembimbing</th>
                    <th>Total SKS</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Query untuk mengambil data mahasiswa
                $sql = "SELECT * FROM v_mahasiswa";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    // Output data setiap baris
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr><td>" . $row["Nama_Mahasiswa"] . "</td><td>" . $row["Nama_Jurusan"] . "</td><td>" . $row["Nama_Dospem"] . "</td><td>" . $row["Total_SKS"] . "</td></tr>";
                    }
                } else {
                    echo "<tr><td colspan='4'>No data available</td></tr>";
                }
                ?>
            </tbody>
        </table>

        <h2>Mata Kuliah Terpopuler</h2>
        <table>
            <thead>
                <tr>
                    <th>Nama Mata Kuliah</th>
                    <th>SKS</th>
                    <th>Jumlah Mahasiswa</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Query untuk mengambil data mata kuliah terpopuler
                $sql = "SELECT * FROM v_mata_kuliah";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    // Output data setiap baris
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr><td>" . $row["Nama_MK"] . "</td><td>" . $row["SKS"] . "</td><td>" . $row["Jumlah_Mahasiswa"] . "</td></tr>";
                    }
                } else {
                    echo "<tr><td colspan='3'>No data available</td></tr>";
                }
                ?>
            </tbody>
        </table>

        <div class="chart-container">
            <canvas id="popularCoursesChart"></canvas>
        </div>

        <h2>Hitung Total SKS dan Tampilkan Mata Kuliah Berdasarkan NIM</h2>
        <form method="post" action="">
            <label for="nim">Pilih NIM Mahasiswa:</label>
            <select id="nim" name="nim" required>
                <?php
                // Query untuk mengambil data NIM mahasiswa
                $mahasiswaResult = $conn->query("SELECT NIM FROM Mahasiswa");
                while ($row = $mahasiswaResult->fetch_assoc()) {
                    echo "<option value='" . $row['NIM'] . "'>" . $row['NIM'] . "</option>";
                }
                ?>
            </select>
            <button type="submit" name="calculate_sks">Hitung Total SKS</button>
        </form>

        <?php
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['calculate_sks'])) {
            $nim = $_POST['nim'];

            // Panggil fungsi total_sks
            $stmt = $conn->prepare("SELECT total_sks(?) AS TotalSKS");
            $stmt->bind_param("s", $nim);
            $stmt->execute();
            $result = $stmt->get_result();
            $totalSKS = $result->fetch_assoc()['TotalSKS'];
            $stmt->close();

            // Panggil fungsi mahasiswa_mk
            $stmt = $conn->prepare("SELECT mahasiswa_mk(?) AS MataKuliah");
            $stmt->bind_param("s", $nim);
            $stmt->execute();
            $result = $stmt->get_result();
            $mataKuliah = $result->fetch_assoc()['MataKuliah'];
            $stmt->close();

            // Dapatkan nama mahasiswa
            $stmt = $conn->prepare("SELECT Nama_Mahasiswa FROM Mahasiswa WHERE NIM = ?");
            $stmt->bind_param("s", $nim);
            $stmt->execute();
            $result = $stmt->get_result();
            $namaMahasiswa = $result->fetch_assoc()['Nama_Mahasiswa'];
            $stmt->close();
        ?>
            <h3>Hasil untuk NIM: <?php echo $nim; ?></h3>
            <p>Nama Mahasiswa: <?php echo $namaMahasiswa; ?></p>
            <p>Total SKS yang diambil: <?php echo $totalSKS; ?></p>
            <p>Mata Kuliah yang diambil: <?php echo $mataKuliah; ?></p>
        <?php
        }
        ?>
    </div>

    <script>
        $(document).ready(function() {
            $.ajax({
                url: 'index.php',
                method: 'POST',
                data: {
                    getChartData: true
                },
                success: function(response) {
                    var data = JSON.parse(response);
                    var ctx = document.getElementById('popularCoursesChart').getContext('2d');
                    var popularCoursesChart = new Chart(ctx, {
                        type: 'pie',
                        data: {
                            labels: data.labels,
                            datasets: [{
                                label: 'Jumlah Mahasiswa',
                                data: data.counts,
                                backgroundColor: data.colors,
                                borderColor: data.borderColors,
                                borderWidth: 1
                            }]
                        },
                        options: {
                            responsive: true,
                            plugins: {
                                tooltip: {
                                    callbacks: {
                                        label: function(tooltipItem) {
                                            var label = data.labels[tooltipItem.dataIndex] || '';
                                            var value = data.counts[tooltipItem.dataIndex] || '';
                                            var percentage = ((value / data.total) * 100).toFixed(2) + '%';
                                            return label + ': ' + value + ' mahasiswa (' + percentage + ')';
                                        }
                                    }
                                }
                            }
                        }
                    });
                }
            });
        });
    </script>
</body>

</html>