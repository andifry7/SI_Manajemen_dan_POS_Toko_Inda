<?php

function insert($data) {
    global $koneksi;

    $username = strtolower(mysqli_real_escape_string($koneksi, $data['username']));
    $fullname = mysqli_real_escape_string($koneksi, $data['fullname']);
    $password = mysqli_real_escape_string($koneksi, $data['password']);
    $password2 = mysqli_real_escape_string($koneksi, $data['password2']);
    $level = mysqli_real_escape_string($koneksi, $data['level']);
    $address = mysqli_real_escape_string($koneksi, $data['address']);
    $gambar = mysqli_real_escape_string($koneksi, $_FILES['image']['name']);

    if ($password !== $password2) {
        echo '<script>
            alert("Konfirmasi password tidak sesuai, Pengguna baru gagal diregistrasi!");
            </script>';
        return false;
    }

    $pass = password_hash($password, PASSWORD_DEFAULT);

    $cekUserName = mysqli_query($koneksi, "SELECT username FROM tbl_user WHERE username = '$username'");
    if (mysqli_num_rows($cekUserName) > 0) {
        echo '<script>
            alert("Username sudah digunakan, Pengguna baru gagal diregistrasi!");
            </script>';
        return false;
    }

    if ($gambar != null) {
        $gambar = uploadimg();
    } else {
        $gambar = 'default.png';
    }

    // Gambar tidak sesuai validasi
    if ($gambar == '') {
        return false;
    }

    $sqlUser = "INSERT INTO tbl_user VALUES (null, '$username', '$fullname', '$pass', '$level', '$address', '$gambar')";
    mysqli_query($koneksi, $sqlUser);

    return mysqli_affected_rows($koneksi);
}

?>