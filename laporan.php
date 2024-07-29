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

// Fungsi untuk mengambil data peminjaman
function getPeminjaman($conn, $search = '') {
    $sql = "SELECT peminjaman.*, anggota.nama AS nama_anggota, buku.judul AS judul_buku 
            FROM peminjaman
            JOIN anggota ON peminjaman.id_anggota = anggota.id
            JOIN buku ON peminjaman.id_buku = buku.id";

    if ($search) {
        $sql .= " WHERE anggota.nama LIKE '%$search%' OR buku.judul LIKE '%$search%'";
    }

    $result = $conn->query($sql);

    if ($result === false) {
        // Jika query gagal, tampilkan pesan kesalahan
        echo "<p class='error'>Error: " . $conn->error . "</p>";
        return [];
    }

    $peminjaman = [];

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $peminjaman[] = $row;
        }
    }

    return $peminjaman;
}

// Mengambil data pencarian jika ada
$search = '';
if (isset($_GET['search'])) {
    $search = $_GET['search'];
}

$peminjaman = getPeminjaman($conn, $search);

?>

<!DOCTYPE html>
<html>
<head>
    <title>Laporan Peminjaman Buku</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f0f0f0;
        }
        h1 {
            text-align: center;
            margin-bottom: 20px;
        }
        form.search-form {
            max-width: 600px;
            margin: 22px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        form.search-form input {
            width: calc(100% - 50px);
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            margin-right: 10px;
        }
        form.search-form button {
            padding: 10px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        form.search-form button:hover {
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
        .status-belum {
            color: red;
        }
        .status-sudah {
            color: green;
        }
        .error {
            color: red;
            text-align: center;
        }
    </style>
</head>
<body>
    <h1>Laporan Peminjaman Buku</h1>

    <!-- Form Pencarian -->
    <form class="search-form" method="get" action="">
        <input type="text" name="search" placeholder="Cari anggota atau buku..." value="<?php echo htmlspecialchars($search); ?>">
        <button type="submit">Cari</button>
    </form>

    <!-- Tabel daftar transaksi peminjaman -->
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Anggota</th>
                <th>Buku</th>
                <th>Tanggal Peminjaman</th>
                <th>Tanggal Pengembalian</th>
                <th>Status Pengembalian</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($peminjaman) > 0): ?>
                <?php foreach ($peminjaman as $item): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($item['id']); ?></td>
                        <td><?php echo htmlspecialchars($item['nama_anggota']); ?></td>
                        <td><?php echo htmlspecialchars($item['judul_buku']); ?></td>
                        <td><?php echo htmlspecialchars($item['tanggal_peminjaman']); ?></td>
                        <td><?php echo htmlspecialchars($item['tanggal_pengembalian']); ?></td>
                        <td class="<?php echo (isset($item['status_pengembalian']) && $item['status_pengembalian'] == 'Sudah Dikembalikan') ? 'status-sudah' : 'status-belum'; ?>">
                            <?php echo isset($item['status_pengembalian']) ? htmlspecialchars($item['status_pengembalian']) : 'Belum Dikembalikan'; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6">Belum ada transaksi peminjaman.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</body>
</html>


<?php $conn->close(); ?>
