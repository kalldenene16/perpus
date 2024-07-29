<?php
    //koneksi ke db
    $conn = mysqli_connect("localhost", "root", "", "phpdasar");

    function query($query) {
        global $conn;
        $result = mysqli_query($conn, $query);
        $rows = [];
        while( $row = mysqli_fetch_assoc($result) ) {
            $rows[] = $row;
        }
        return $rows;
    }

    function tambah($data) {
        global $conn;

        $nama = htmlspecialchars($data["nama"]);
        $nisn = htmlspecialchars($data["nisn"]);
        $email = htmlspecialchars($data["email"]);
        $jurusan = htmlspecialchars($data["jurusan"]);
        
        $gambar = upload();
        if( !$gambar ) {
            return false;
        }

        $query = "INSERT INTO siswa 
              VALUES
              ('', '$nama', '$nisn', '$email', '$jurusan', '$gambar')
              ";
        mysqli_query($conn, $query);

        return mysqli_affected_rows($conn);
    } 

    // tambah guru
    function tambahGuru($data) {
        global $conn;

        $nama = htmlspecialchars($data["nama"]);
        $nip = htmlspecialchars($data["nip"]);
        $no_hp = htmlspecialchars($data["no_hp"]);
        $alamat = htmlspecialchars($data["alamat"]);
        
        $foto = upload();
        if( !$foto ) {
            return false;
        }

        $query = "INSERT INTO gurusekolah 
              VALUES
              ('', '$nama', '$nip', '$no_hp', '$alamat', '$foto')
              ";
        mysqli_query($conn, $query);

        return mysqli_affected_rows($conn);
    } 

    // tambah buku
  


// upload siswa
    function upload() {
        $namaFile = $_FILES['gambar']['name'];
        $ukuranFile = $_FILES['gambar']['size'];
        $error = $_FILES['gambar']['error'];
        $tmpName = $_FILES['gambar']['tmp_name'];

        if($error === 4 ) {
            echo "<script>
                alert('pilih gambar terlebih dahulu!')
            </script>";
        return false;
        }

        $ekstensiGambarValid = ['jpg', 'jpeg', 'png'];
        $ekstensiGambar = explode('.', $namaFile);
        $ekstensiGambar = strtolower(end($ekstensiGambar));
        if( !in_array($ekstensiGambar, $ekstensiGambarValid) ) {
        echo "<script>
                alert('yang anda upload bukan gambar!')
            </script>";
        return false;
        }

        if( $ukuranFile > 1000000 ) {
            echo "<script>
                alert('ukuran gambar terlalu besar!')
            </script>";
        return false;
        }

        $namaFileBaru = uniqid();
        $namaFileBaru .= '.' ;
        $namaFileBaru .= $ekstensiGambar;

        move_uploaded_file($tmpName, 'img/' . $namaFile);

        return $namaFile;
    }




   

    // hapus siswa
    function hapus($id) {
        global $conn;
        mysqli_query($conn, "DELETE FROM siswa WHERE id='$id'");
        return mysqli_affected_rows($conn);
    }

    // hapus guru
    function hapusGuru($id) {
        global $conn;
        mysqli_query($conn, "DELETE FROM gurusekolah WHERE id='$id'");
        return mysqli_affected_rows($conn);
    }

    // hapus buku
    function hapusBuku($id) {
        global $conn;
        mysqli_query($conn, "DELETE FROM buku WHERE id='$id'");
        return mysqli_affected_rows($conn);
    }

    // ubah siswa
        function ubah($data) {
        global $conn;

        $id = $data["id"];
        $nama = htmlspecialchars($data["nama"]);
        $nisn = htmlspecialchars($data["nisn"]);
        $email = htmlspecialchars($data["email"]);
        $jurusan = htmlspecialchars($data["jurusan"]);
        $gambarLama = htmlspecialchars($data["gambarLama"]);

        if( $_FILES['gambar']['error'] === 4 ) {
            $gambar = $gambarLama;
        } else {
            $gambar = upload();
        }

        $query = "UPDATE siswa SET
                    nama = '$nama',
                    nisn = '$nisn',
                    email = '$email',
                    jurusan = '$jurusan',
                    gambar = '$gambar'
                WHERE id = $id
                ";

        mysqli_query($conn, $query);

        return mysqli_affected_rows($conn);
    }

   

    // ubah guru
    function ubahGuru($data) {
        global $conn;
    
        $id = $data["id"];
        $nama = htmlspecialchars($data["nama"]);
        $nip = htmlspecialchars($data["nip"]);
        $alamat = htmlspecialchars($data["alamat"]);
        $no_hp = htmlspecialchars($data["no_hp"]);
        $gambarLama = htmlspecialchars($data["gambar"]);
    
        if( $_FILES['gambar']['error'] === 4 ) {
            $gambar = $gambarLama;
        } else {
            $gambar = upload();
            if(!$gambar) {
                return "Upload foto gagal.";
            }
        }
    
        $query = "UPDATE gurusekolah SET
                    nama = '$nama',
                    nip = '$nip',
                    no_hp = '$no_hp',
                    alamat = '$alamat',
                    gambar = '$gambar'
                WHERE id = $id
                ";
    
        if(mysqli_query($conn, $query)) {
            return mysqli_affected_rows($conn);
        } else {
            return "Error: " . mysqli_error($conn);
        }
    }
    function ubahBuku($data) {
        global $conn;
    
        $id = $data["id"];
        $judul = htmlspecialchars($data["judul"]);
        $penerbit = htmlspecialchars($data["penerbit"]);
        $tahunterbit = htmlspecialchars($data["tahun_terbit"]);
        $jumlahbuku = htmlspecialchars($data["jumlah_buku"]);
        $gambarLama = htmlspecialchars($data["gambar"]);
    
        if( $_FILES['gambar']['error'] === 4 ) {
            $gambar = $gambarLama;
        } else {
            $gambar = upload();
            if(!$gambar) {
                return "Upload foto gagal.";
            }
        }
    
        $query = "UPDATE buku SET
                    judul = '$judul',
                    penerbit = '$penerbit',
                    tahun_terbit = '$tahunterbit',
                    jumlah_buku = '$jumlahbuku',
                    gambar = '$gambar'
                WHERE id = $id
                ";
    
        if(mysqli_query($conn, $query)) {
            return mysqli_affected_rows($conn);
        } else {
            return "Error: " . mysqli_error($conn);
        }
    }


    // cari siswa
    function cari($keyword) {
        $query = "SELECT * FROM siswa
                  WHERE nama LIKE '%$keyword%'
                  OR nisn LIKE '%$keyword%'
                  OR email LIKE '%$keyword%'
                  OR jurusan LIKE '%$keyword%'";
        return query($query);
    }

    // cari guru
    function cariGuru($keyword) {
        $query = "SELECT * FROM gurusekolah
                  WHERE nama LIKE '%$keyword%'
                  OR nip LIKE '%$keyword%'
                  OR no_hp LIKE '%$keyword%'
                  OR alamat LIKE '%$keyword%'";
        return query($query);
    }

    // cari buku
    function cariBuku($keyword) {
        $query = "SELECT * FROM buku
                  WHERE judul LIKE '%$keyword%'
                  OR penerbit LIKE '%$keyword%'";
        return query($query);
    }

    function registrasi($data) {
        global $conn;

        $username = strtolower(stripslashes($data["username"]));
        $password = mysqli_real_escape_string($conn, $data["password"]);
        $password2 = mysqli_real_escape_string($conn, $data["password2"]);

        $result = mysqli_query($conn, "SELECT username FROM user WHERE username = '$username'");
        if( mysqli_fetch_assoc($result) ) {
            echo "<script>
                alert('username sudah terdaftar!');
              </script>";
            return false;
        }

        if( $password !== $password2 ) {
            echo "<script>
                alert('konfirmasi password tidak sesuai!');
              </script>";
            return false;
        }
        $password = password_hash($password, PASSWORD_DEFAULT);

        mysqli_query($conn, "INSERT INTO user VALUES('', '$username', '$password')");
        return mysqli_affected_rows($conn);
    }




    // upload Buku
function uploadBuku() {
    $namaFile = $_FILES['gambar']['name'];
    $ukuranFile = $_FILES['gambar']['size'];
    $error = $_FILES['gambar']['error'];
    $tmpName = $_FILES['gambar']['tmp_name'];

    if($error === 4 ) {
        echo "<script>
            alert('pilih gambar terlebih dahulu!');
        </script>";
        return false;
    }

    $ekstensiGambarValid = ['jpg', 'jpeg', 'png'];
    $ekstensiGambar = explode('.', $namaFile);
    $ekstensiGambar = strtolower(end($ekstensiGambar));
    if( !in_array($ekstensiGambar, $ekstensiGambarValid) ) {
        echo "<script>
            alert('yang anda upload bukan gambar!');
        </script>";
        return false;
    }

    if( $ukuranFile > 1000000 ) {
        echo "<script>
            alert('ukuran gambar terlalu besar!');
        </script>";
        return false;
    }

    $namaFileBaru = uniqid();
    $namaFileBaru .= '.';
    $namaFileBaru .= $ekstensiGambar;

    move_uploaded_file($tmpName, 'img/' . $namaFileBaru);

    return $namaFileBaru;
}

function tambahBuku($data) {
    global $conn;

    $judul = htmlspecialchars($data["judul"]);
    $penerbit = htmlspecialchars($data["penerbit"]);
    $tahun_terbit = htmlspecialchars($data["tahun_terbit"]);
    $jumlah_buku = htmlspecialchars($data["jumlah_buku"]);

    $gambar = uploadBuku();
    if( !$gambar ) {
        return false;
    }

    $query = "INSERT INTO buku 
              VALUES
              ('', '$judul', '$penerbit', '$tahun_terbit', '$jumlah_buku', '$gambar')";
    mysqli_query($conn, $query);

    return mysqli_affected_rows($conn);
}

    ?>