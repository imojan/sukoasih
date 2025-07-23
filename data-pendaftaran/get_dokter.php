<?php
include '../koneksi.php';

if (isset($_POST['id_poli'])) {
    $id_poli = $_POST['id_poli'];
    $query = mysqli_query($koneksi, "SELECT * FROM tb_dokter WHERE id_poli = '$id_poli'");
    
    echo '<option value="">-- Pilih Dokter --</option>';
    while ($data = mysqli_fetch_assoc($query)) {
        echo '<option value="' . $data['id_dokter'] . '">' . $data['nm_dokter'] . '</option>';
    }
}
?>