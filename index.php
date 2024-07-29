<?php
session_start();

    if( !isset($_SESSION["login"]) ) {
        header("Location: login.php");
        exit;
    }

    require 'functions.php';
    $jumlahDataPerHalaman = 2;
    $jumlahData = count(query("SELECT * FROM anggota"));
    $jumlahHalaman = ceil($jumlahData / $jumlahDataPerHalaman);
    $halamanAktif = ( isset($_GET["halaman"]) ) ? $_GET["halaman"] : 1;
    $awalData = ( $jumlahDataPerHalaman * $halamanAktif ) - $jumlahDataPerHalaman;
    
    $query = "SELECT * FROM anggota LIMIT $jumlahDataPerHalaman OFFSET $awalData";
    $data = query($query);
    

    $siswa = query("SELECT * FROM anggota LIMIT $awalData, $jumlahDataPerHalaman");

    if( isset($_POST["cari"]) ) {
        $siswa = cari($_POST["keyword"]);
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SMKN 40</title>
    <script src="js/jquery-3.7.1.min.js"></script>
    <script src="js/script.js"></script>
    <link rel="stylesheet" href="style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">
</head>
<body>
    
<div class="header-container">
    <a href="index.php" class="link2">
        <H1 class="logo">SMKN 40 Jakarta</H1>
    </a>
    <div class="nav-links">
        <a href="index.php" class="link">
            <h2>Siswa</h2>
        </a>
        <a href="guru.php" class="link">
            <h2>Guru</h2>
        </a>
        <a href="buku.php" class="link">
            <h2>Buku</h2>
        </a>
        <a href="transaksi.php" class="link">
            <h2>Transaksi</h2>
        <a href="laporan.php" class="link">
            <h2>Laporan</h2>
        </a>
    </div>
</div>
    <br><br>
    
    <form action="" method="post" class="form-cari">
        <input type="text" name="keyword" size="40" autofocus placeholder="Masukkan keyword pencarian.." autocomplete="off" id="keyword">
        <button type="submit" name="cari" id="tombol-cari" class="button cari">Cari!</button>
        
        <img src="img/loading.gif" class="loader">
    </form>

    <br>

        <div class="button-container">
            <a href="tambah.php" class="tambah">Tambah data siswa</a>
            <a href="logout.php" class="button logout">Logout</a>
            <a href="cetak.php" target="_blank" class="button cetak">Cetak</a>
            </div>
    <div id="container">
        <table border="1" cellpadding="10" cellspacing="0">
            <tr>
                <th>No.</th>
                <th class="aksi">Aksi</th>
                <th>Gambar</th>
                <th>Nama</th>
                <th>NISN</th>
                <th>Email</th>
                <th>Jurusan</th>
            </tr>
            
        <?php $i = 1; ?>
        <?php foreach( $siswa as $row ) : ?>
        <tr>
            <td><?= $i; ?></td>
            <td class="aksi">
                <a href="ubah.php?id=<?= $row["id"]; ?>">ubah</a> |
                <a href="hapus.php?id=<?= $row["id"]; ?>" onclick="
                    return confirm('yakin?');">hapus</a>
            </td>
            <td><img src="img/<?= $row["gambar"]; ?>" width="100"></td>
            <td><?= $row["nama"]; ?></td>"
            <td><?= $row["nisn"]; ?></td>
            <td><?= $row["email"]; ?></td>
            <td><?= $row["jurusan"]; ?></td>
        </tr>
        <?php $i++; ?>
        <?php endforeach; ?>
    </table>
    </div>
    <div class="nav">
    <?php if( $halamanAktif > 1 ) : ?>
        <a href="?halaman=<?= $halamanAktif - 1; ?>">&laquo;</a>
    <?php endif; ?>
    
    <?php for( $i = 1; $i <= $jumlahHalaman; $i++ ) : ?>
        <?php if( $i == $halamanAktif ) : ?>
            <a href="?halaman=<?= $i; ?>" style="font-weight: bold; color: red;"><?= $i; ?></a>
        <?php else : ?>
            <a href="?halaman=<?= $i; ?>"><?= $i; ?></a>
        <?php endif; ?>
    <?php endfor; ?>
    
    <?php if( $halamanAktif < $jumlahHalaman ) : ?>
        <a href="?halaman=<?= $halamanAktif + 1; ?>">&raquo;</a>
    <?php endif; ?>
    </div>
</body>
</html>
