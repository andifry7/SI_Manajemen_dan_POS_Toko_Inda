<?php

require "../config/config.php";
require "../config/functions.php";
require('../asset/fpdf/vendor/autoload.php');

$stokBrg = getData("SELECT * FROM tbl_barang");

$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 16);
$pdf->Cell(190, 10, 'Laporan Stok Barang', 0, 1, 'C');

$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(190, 10, '', 'B', 1);
$pdf->Cell(10, 8, 'No', 0, 0, 'C');
$pdf->Cell(25, 8, 'ID Barang', 0, 0, 'C');
$pdf->Cell(35, 8, 'Kode Barang', 0, 0, 'C');
$pdf->Cell(75, 8, 'Nama Barang', 0, 0, 'C');
$pdf->Cell(20, 8, 'Stok', 0, 0, 'C');
$pdf->Cell(25, 8, 'Satuan', 0, 1, 'C');
$pdf->Cell(190, 1, '', 'T', 1);

$pdf->SetFont('Arial', '', 12);
$no = 1;
foreach ($stokBrg as $stok) {
    $pdf->Cell(10, 8, $no++, 0, 0, 'C');
    $pdf->Cell(25, 8, $stok['id_barang'], 0, 0);
    $pdf->Cell(35, 8, $stok['barcode'], 0, 0);
    $pdf->Cell(75, 8, $stok['nama_barang'], 0, 0);
    $pdf->Cell(20, 8, $stok['stock'], 0, 0, 'C');
    $pdf->Cell(25, 8, $stok['satuan'], 0, 1, 'C');
}
$pdf->Cell(190, 1, '', 'T', 1);

$pdf->Output();
?>