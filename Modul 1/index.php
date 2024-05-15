<!DOCTYPE html>
<html>
<head>
    <title>Pengolahan Nama Keluarga</title>
    <link rel="stylesheet" href="asset/style.css">
</head>
<body>
    <div class="container">
        <h1>A.Irwin Putra Pangesti</h1>
        <h2>Form Input Nama Keluarga</h2>
        <form method="post">
            <label for="names">Masukkan nama anggota keluarga (pisahkan dengan koma):</label><br>
            <input type="text" name="names" id="names"><br>
            <input type="submit" value="Submit">
        </form>

        <?php
        session_start();
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $names = explode(",", $_POST['names']);
            $_SESSION['names'] = $names;
        }

        if (isset($_SESSION['names'])) {
            $names = $_SESSION['names'];
            $totalNames = count($names);
            $totalLetters = 0;
            $totalConsonants = 0;
            $totalVowels = 0;

            echo "<table class='result-table'>";
            echo "<tr><th>Nama</th><th>Jumlah huruf</th><th>Nama terbalik</th><th>Jumlah konsonan</th><th>Jumlah vokal</th></tr>";

            foreach ($names as $index => $name) {
                $name = trim($name);
                $reversedName = strrev($name);
                $totalLetters += strlen(str_replace(' ', '', $name));
                

                // Menghitung jumlah konsonan dan vokal
                $totalConsonants += preg_match_all('/[bcdfghjklmnpqrstvwxyzBCDFGHJKLMNPQRSTVWXYZ]/', $name);
                $totalVowels += preg_match_all('/[aeiouAEIOU]/', $name);

                echo "<tr>";
                echo "<td>$name</td>";
                echo "<td>" . strlen(str_replace(' ', '', $name)) . "</td>";
                echo "<td>$reversedName</td>";
                echo "<td>" . preg_match_all('/[bcdfghjklmnpqrstvwxyzBCDFGHJKLMNPQRSTVWXYZ]/', $name) . "</td>";
                echo "<td>" . preg_match_all('/[aeiouAEIOU]/', $name) . "</td>";
                echo "</tr>";
            }

            echo "</table>";

            echo "<div class='total'>";
            echo "<h3>Total</h3>";
            echo "<p>Jumlah nama: $totalNames</p>";
            echo "<p>Jumlah huruf: $totalLetters</p>";
            echo "<p>Jumlah konsonan: $totalConsonants</p>";
            echo "<p>Jumlah vokal: $totalVowels</p>";
            echo "</div>";
        }
        ?>
    </div>
</body>
</html>
