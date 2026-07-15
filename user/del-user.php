<?php

session_start();

if (!isset($_SESSION['ssLoginPOS'])) {
    header("location: ../auth/login.php");
    exit();
}

require "../config/config.php";
require "../config/functions.php";
require "../module/mode-user.php";

$id = $_GET['id'] ?? '';
$foto = $_GET['foto'] ?? '';

if ($id !== '' && delete($id, $foto)) {
    header("location: data-user.php?status=success");
} else {
    header("location: data-user.php?status=error");
}
exit();

?>