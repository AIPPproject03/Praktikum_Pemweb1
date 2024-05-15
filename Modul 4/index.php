<?php
session_start();

// Hapus semua data sesi
session_unset();

// Akhiri sesi
session_destroy();
?>
<!DOCTYPE html>
<html>

<head>
    <title>Hasil Pemilu</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <div id="container">
        <div id="menu">
            <button onclick="location.href='login.php'" class="btn">Login</button>
            <button onclick="location.href='register.php'" class="btn">Register</button>
        </div>
        <div id="chart-container">
            <h1>Hasil Pemilu</h1>
            <canvas id="myChart" width="400" height="400"></canvas>
        </div>
    </div>

    <script>
        var ctx = document.getElementById('myChart').getContext('2d');
        var myChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: [], // Label calon akan diisi dari data yang didapatkan melalui AJAX
                datasets: [{
                    label: 'Jumlah Suara',
                    data: [], // Data suara akan diisi dari data yang didapatkan melalui AJAX
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(255, 206, 86, 0.2)',
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(153, 102, 255, 0.2)'
                    ],
                    borderColor: [
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        $(document).ready(function() {
            $.ajax({
                url: 'koneksi.php',
                type: 'GET',
                success: function(data) {
                    var hasil = JSON.parse(data);
                    var labels = [];
                    var suara = [];
                    for (var key in hasil) {
                        labels.push(hasil[key]['nama_kandidat']);
                        suara.push(hasil[key]['jumlah_suara']);
                    }
                    myChart.data.labels = labels;
                    myChart.data.datasets[0].data = suara;
                    myChart.update();
                }
            });
        });
    </script>
</body>

</html>