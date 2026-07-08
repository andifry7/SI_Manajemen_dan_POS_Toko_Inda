<?php

function insert($data) {
    global $koneksi;

    $id = mysqli_real_escape_string($koneksi, $data['kodeBrg']);
    $tgl = mysqli_real_escape_string($koneksi, $data['tglKeluar']);
    $qty = (int) mysqli_real_escape_string($koneksi, $data['qty']);
    $keterangan = mysqli_real_escape_string($koneksi, $data['keterangan']);

    $success = false;
    $stmtBarang = mysqli_prepare($koneksi, "SELECT id_barang, nama_barang, kategori, satuan, stock FROM tbl_barang WHERE id_barang = ?");
    mysqli_stmt_bind_param($stmtBarang, "s", $id);
    mysqli_stmt_execute($stmtBarang);
    $queryBrg = mysqli_stmt_get_result($stmtBarang);

    if ($queryBrg && mysqli_num_rows($queryBrg)) {
        $barang = mysqli_fetch_assoc($queryBrg);
        $nama = $barang['nama_barang'];
        $kategori = $barang['kategori'];
        $satuan = $barang['satuan'];
        $stock = (int) $barang['stock'];

        if ($qty > 0 && $qty <= $stock) {
            $stmtKeluar = mysqli_prepare($koneksi, "SELECT id_barang FROM tbl_barang_keluar WHERE id_barang = ?");
            mysqli_stmt_bind_param($stmtKeluar, "s", $id);
            mysqli_stmt_execute($stmtKeluar);
            $cekKeluar = mysqli_stmt_get_result($stmtKeluar);

            if (!$cekKeluar || !mysqli_num_rows($cekKeluar)) {
                $sqlKeluar = "INSERT INTO tbl_barang_keluar (id_barang, nama_barang, kategori, satuan, stock, tgl_keluar, qty, keterangan) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
                $stmtInsert = mysqli_prepare($koneksi, $sqlKeluar);
                mysqli_stmt_bind_param($stmtInsert, "ssssisis", $id, $nama, $kategori, $satuan, $stock, $tgl, $qty, $keterangan);
                mysqli_stmt_execute($stmtInsert);

                $stmtUpdate = mysqli_prepare($koneksi, "UPDATE tbl_barang SET stock = stock - ? WHERE id_barang = ?");
                mysqli_stmt_bind_param($stmtUpdate, "is", $qty, $id);
                mysqli_stmt_execute($stmtUpdate);

                $success = mysqli_stmt_affected_rows($stmtUpdate) > 0;
            } else {
                echo "<script>
                        alert('Data stok keluar untuk barang ini sudah ada, hapus data lama terlebih dahulu jika ingin input ulang');
                    </script>";
            }
        } else {
            echo "<script>
                    alert('Jumlah stok keluar tidak valid atau stok barang tidak mencukupi');
                </script>";
        }
    } else {
        echo "<script>
                alert('Barang tidak ditemukan');
            </script>";
    }

    return $success;
}

function delete($id) {
    global $koneksi;

    $success = false;
    $stmtKeluar = mysqli_prepare($koneksi, "SELECT qty FROM tbl_barang_keluar WHERE id_barang = ?");
    mysqli_stmt_bind_param($stmtKeluar, "s", $id);
    mysqli_stmt_execute($stmtKeluar);
    $queryKeluar = mysqli_stmt_get_result($stmtKeluar);

    if ($queryKeluar && mysqli_num_rows($queryKeluar)) {
        $data = mysqli_fetch_assoc($queryKeluar);
        $qty = (int) $data['qty'];

        $stmtUpdate = mysqli_prepare($koneksi, "UPDATE tbl_barang SET stock = stock + ? WHERE id_barang = ?");
        mysqli_stmt_bind_param($stmtUpdate, "is", $qty, $id);
        mysqli_stmt_execute($stmtUpdate);

        $stmtDelete = mysqli_prepare($koneksi, "DELETE FROM tbl_barang_keluar WHERE id_barang = ?");
        mysqli_stmt_bind_param($stmtDelete, "s", $id);
        mysqli_stmt_execute($stmtDelete);

        $success = mysqli_stmt_affected_rows($stmtDelete) > 0;
    }

    return $success;
}


