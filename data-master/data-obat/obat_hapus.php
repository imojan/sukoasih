<?php
session_start();
include '../../koneksi.php';

if (!isset($_SESSION["jabatan"])) {
    echo "<script>location='../../login/index.php'</script>";
    exit();
}

$id_obat = $_GET['id_obat'];

$cek = mysqli_query($koneksi, "SELECT * FROM tb_resep WHERE daftar_obat LIKE '%\"id_obat\":\"$id_obat\"%'");
if (mysqli_num_rows($cek) > 0) {
    echo "<script>alert('Obat ini digunakan dalam resep, tidak bisa dihapus.'); location='obat.php';</script>";
    exit();
}

// Baru hapus data obat
$hapus_obat = $koneksi->query("DELETE FROM tb_obat WHERE id_obat='$id_obat'");

if ($hapus_obat) {
    echo "<script>alert('Data Obat Terhapus!');</script>";
} else {
    echo "<script>alert('Gagal Menghapus Data Obat!');</script>";
}


echo "<script>location='obat.php'</script>";

?>