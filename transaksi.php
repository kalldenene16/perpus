<?php
// Koneksi ke database
$host = 'localhost';
$dbname = 'phpdasar';
$username = 'root';
$password = '';

$conn = new mysqli($host, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Fungsi untuk menambah transaksi peminjaman
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_anggota = $_POST['id_anggota'];
    $id_buku = $_POST['id_buku'];
    $tanggal_peminjaman = $_POST['tanggal_peminjaman'];
    $tanggal_pengembalian = $_POST['tanggal_pengembalian'];

    $sql = "INSERT INTO peminjaman (id_anggota, id_buku, tanggal_peminjaman, tanggal_pengembalian) 
            VALUES ('$id_anggota', '$id_buku', '$tanggal_peminjaman', '$tanggal_pengembalian')";
    
    if ($conn->query($sql) === TRUE) {
        echo "<p class='success'>Transaksi peminjaman berhasil ditambahkan</p>";
    } else {
        echo "<p class='error'>Error: " . $sql . "<br>" . $conn->error . "</p>";
    }
}

// Fungsi untuk mengambil data peminjaman
function getPeminjaman($conn) {
    $sql = "SELECT peminjaman.*, anggota.nama AS nama_anggota, buku.judul AS judul_buku 
            FROM peminjaman
            JOIN anggota ON peminjaman.id_anggota = anggota.id
            JOIN buku ON peminjaman.id_buku = buku.id";
    $result = $conn->query($sql);

    if ($result === false) {
        // Jika query gagal, tampilkan pesan kesalahan
        echo "<p class='error'>Error: " . $conn->error . "</p>";
        return [];
    }

    $peminjaman = [];

    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $peminjaman[] = $row;
        }
    }

    return $peminjaman;
}

$peminjaman = getPeminjaman($conn);

// Fungsi untuk mengambil data anggota
function getAnggota($conn) {
    $sql = "SELECT * FROM anggota";
    $result = $conn->query($sql);
    $anggota = [];

    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $anggota[] = $row;
        }
    }

    return $anggota;
}

$anggota = getAnggota($conn);

// Fungsi untuk mengambil data buku
function getBuku($conn) {
    $sql = "SELECT * FROM buku";
    $result = $conn->query($sql);
    $buku = [];

    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $buku[] = $row;
        }
    }

    return $buku;
}

$buku = getBuku($conn);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Transaksi Peminjaman Buku</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f0f0f0;
        }
        h1, h2 {
            text-align: center;
        }
        form {
            background-color: #fff;
            padding: 20px;
            margin: 20px auto;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            max-width: 600px;
        }
        label, select, input, button {
            display: block;
            width: 100%;
            margin-bottom: 10px;
        }
        select, input {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        button {
            padding: 10px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        button:hover {
            background-color: #45a049;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background-color: #fff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 12px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .success {
            color: green;
            text-align: center;
        }
        .error {
            color: red;
            text-align: center;
        }
    </style>
</head>
<body>
    <h1>Transaksi Peminjaman Buku</h1>

    <!-- Form input transaksi peminjaman -->
    <form action="" method="post">
        <label for="id_anggota">Anggota:</label>
        <select name="id_anggota" id="id_anggota" required>
            <?php foreach ($anggota as $item): ?>
                <option value="<?php echo $item['id']; ?>"><?php echo $item['nama']; ?></option>
            <?php endforeach; ?>
        </select>

        <label for="id_buku">Buku:</label>
        <select name="id_buku" id="id_buku" required>
            <?php foreach ($buku as $item): ?>
                <option value="<?php echo $item['id']; ?>"><?php echo $item['judul']; ?></option>
            <?php endforeach; ?>
        </select>

        <label for="tanggal_peminjaman">Tanggal Peminjaman:</label>
        <input type="date" name="tanggal_peminjaman" id="tanggal_peminjaman" required>

        <label for="tanggal_pengembalian">Tanggal Pengembalian:</label>
        <input type="date" name="tanggal_pengembalian" id="tanggal_pengembalian" required>

        <button type="submit">Tambah Transaksi</button>
    </form>

</body>
</html>

<?php $conn->close(); ?>
