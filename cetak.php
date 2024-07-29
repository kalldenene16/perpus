<?php
require_once __DIR__ . '/vendor/autoload.php';
require 'functions.php';
$siswa = query("SELECT * FROM siswa");

$mpdf = new \Mpdf\Mpdf();
$html = '<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Siswa</title>
    <link rel="stylesheet" href="print.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">
</head>
<body>
    <h1>Daftar Siswa</h1>
    <table border="1" cellpadding="10" cellspacing="0">
        <tr>
            <th>No.</th>
            <th>Gambar</th>
            <th>Nama</th>
            <th>NISN</th>
            <th>Email</th>
            <th>Jurusan</th>
        </tr>';

$i = 1;
foreach ($siswa as $row) {
    $html .= '<tr>
        <td>' . $i++ . '</td>
        <td><img src="img/' . $row["gambar"] . '" style="width: 70px;"></td>
        <td>' . $row["nama"] . '</td>
        <td>' . $row["nisn"] . '</td>
        <td>' . $row["email"] . '</td>
        <td>' . $row["jurusan"] . '</td>
    </tr>';
}

$html .= '</table>
</body>
</html>';
$mpdf->WriteHTML($html);
$mpdf->Output('daftar-siswa.pdf', \Mpdf\Output\Destination::INLINE);
?>