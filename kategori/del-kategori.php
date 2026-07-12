<?php

session_start();

if (!isset($_SESSION['ssLoginPOS'])) {
    header("location: ../auth/login.php");
    exit();
}

require "../config/config.php";
require "../config/functions.php";
require "../module/mode-kategori.php";

$id = $_GET['id'];

if (delete($id)) {
    echo "
        <script>
            document.location.href = 'data-kategori.php?msg=deleted';
        </script>
    ";
} else {
    echo "
        <script>
            document.location.href = 'data-kategori.php?msg=aborted';
        </script>
    ";
}