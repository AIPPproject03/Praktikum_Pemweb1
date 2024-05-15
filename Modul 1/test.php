<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Hitung Nama Keluarga Random</title>
</head>

<body>
    <h1>Hitung Nama Keluarga Random</h1>

    <form method="post">
        <input type="submit" value="Generate">
    </form>

    <?php
    // Array nama keluarga
    $keluarga = ["Adi", "Budi", "Caca", "Dedi", "Eka"];

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Generate indeks random
        $indeksRandom = rand(0, count($keluarga) - 1);

        // Ambil nama keluarga random
        $namaKeluargaRandom = $keluarga[$indeksRandom];

        // Hitung jumlah kata
        $jumlah_kata = count(explode(" ", $namaKeluargaRandom));

        // Hitung jumlah huruf
        $jumlah_huruf = strlen($namaKeluargaRandom);

        // Hitung kebalikan nama
        $kebalikan = strrev($namaKeluargaRandom);

        // Hitung jumlah konsonan dan vokal
        $jumlah_konsonan = 0;
        $jumlah_vokal = 0;
        for ($i = 0; $i < $jumlah_huruf; $i++) {
            $huruf = strtolower($namaKeluargaRandom[$i]);
            if (ctype_alpha($huruf)) {
                if (in_array($huruf, ["a", "i", "u", "e", "o"])) {
                    $jumlah_vokal++;
                } else {
                    $jumlah_konsonan++;
                }
            }
        }
    }
    ?>

    <h2>Nama Keluarga: <?php echo isset($namaKeluargaRandom) ? $namaKeluargaRandom : ""; ?></h2>

    <?php if (isset($namaKeluargaRandom)) : ?>
        <p>Jumlah Kata: <?php echo $jumlah_kata; ?></p>
        <p>Jumlah Huruf: <?php echo $jumlah_huruf; ?></p>
        <p>Kebalikan: <?php echo $kebalikan; ?></p>
        <p>Jumlah Konsonan: <?php echo $jumlah_konsonan; ?></p>
        <p>Jumlah Vokal: <?php echo $jumlah_vokal; ?></p>
    <?php endif; ?>
</body>

</html>