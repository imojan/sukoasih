<?php
include '../koneksi.php';

$id_pemeriksaan = $_POST['id_pemeriksaan'] ?? '';
$id_resep = $_POST['id_resep'] ?? '';
$id_obat_arr = $_POST['id_obat'] ?? [];
$cara_minum_arr = $_POST['cara_minum'] ?? [];
$jumlah_arr = $_POST['jumlah'] ?? [];

if (empty($id_pemeriksaan) || count($id_obat_arr) == 0) {
    echo "<script>alert('Data tidak lengkap!'); window.history.back();</script>";
    exit;
}

// Cek apakah resep sudah ada
$cek_resep = mysqli_query($koneksi, "SELECT id_resep FROM tb_resep WHERE id_pemeriksaan = '$id_pemeriksaan'");
if (mysqli_num_rows($cek_resep) > 0) {
    $row = mysqli_fetch_assoc($cek_resep);
    $id_resep = $row['id_resep'];

    // Kembalikan stok lama
    $get_lama = mysqli_query($koneksi, "SELECT id_obat, jumlah FROM tb_detail_resep WHERE id_resep = '$id_resep'");
    while ($lama = mysqli_fetch_assoc($get_lama)) {
        mysqli_query($koneksi, "UPDATE tb_obat SET stok_obat = stok_obat + {$lama['jumlah']} WHERE id_obat = '{$lama['id_obat']}'");
    }

    // Hapus detail lama
    mysqli_query($koneksi, "DELETE FROM tb_detail_resep WHERE id_resep = '$id_resep'");
} else {
    // Jika belum ada, buat baru
    mysqli_query($koneksi, "INSERT INTO tb_resep (id_resep, id_pemeriksaan) VALUES ('$id_resep', '$id_pemeriksaan')");
}

// Validasi stok obat
foreach ($id_obat_arr as $i => $id_obat) {
    $jumlah = (int)$jumlah_arr[$i];
    $q = mysqli_query($koneksi, "SELECT stok_obat, nm_obat FROM tb_obat WHERE id_obat = '$id_obat'");
    $r = mysqli_fetch_assoc($q);

    if ($r['stok_obat'] < $jumlah) {
        echo "<script>alert('Stok obat {$r['nm_obat']} tidak mencukupi!'); window.history.back();</script>";
        exit;
    }
}

// Simpan detail resep baru dan update stok
foreach ($id_obat_arr as $i => $id_obat) {
    $jumlah = (int)$jumlah_arr[$i];
    $cara = mysqli_real_escape_string($koneksi, $cara_minum_arr[$i]);

    mysqli_query($koneksi, "INSERT INTO tb_detail_resep (id_resep, id_obat, cara_minum, jumlah)
        VALUES ('$id_resep', '$id_obat', '$cara', '$jumlah')");

    mysqli_query($koneksi, "UPDATE tb_obat SET stok_obat = stok_obat - $jumlah WHERE id_obat = '$id_obat'");
}

echo "<script>alert('✅ Resep berhasil disimpan!'); window.location.href = '../data-pemeriksaan/pemeriksaan.php';</script>";
?>