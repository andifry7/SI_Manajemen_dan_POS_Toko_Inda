<?php

date_default_timezone_set('Asia/Jakarta');

$host = 'localhost';
$user = 'root';
$pass = '';
$dbname = 'db_tokoindapos';

$koneksi = mysqli_connect($host, $user, $pass, $dbname);

// if (mysqli_connect_errno()) {
//     echo "Koneksi database gagal";
//     exit();
// } else {
//     echo "berhasil koneksi ke database";
// }

$main_url = "http://localhost/tokoinda-pos/";
?>