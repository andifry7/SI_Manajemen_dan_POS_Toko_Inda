<?php 

function generateNo() {
    global $koneksi;

    $queryNo = mysqli_query($koneksi, "SELECT MAX(no_jual) AS maxno FROM tbl_jual_head");
    $row = mysqli_fetch_assoc($queryNo);
    $maxno = $row['maxno'];

    $noUrut = (int) substr($maxno, 2, 4);
    $noUrut++;
    $maxno = 'PJ' . sprintf("%04s", $noUrut);

    return $maxno;
}

?>