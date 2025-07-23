<?php
include '../koneksi.php';

$keyword = $_GET['keyword'] ?? '';

$sql = "SELECT * FROM tb_obat 
        WHERE nm_obat LIKE '%$keyword%' 
        OR bentuk_obat LIKE '%$keyword%' 
        ORDER BY nm_obat ASC";
$result = mysqli_query($koneksi, $sql);

if (mysqli_num_rows($result) > 0) {
    while ($obat = mysqli_fetch_assoc($result)) {
        // Escape karakter bermasalah seperti tanda kutip
        $id_obat = $obat['id_obat'];
        $nm_obat = addslashes($obat['nm_obat']);
        $bentuk_obat = addslashes($obat['bentuk_obat']);
        $stok_obat = $obat['stok_obat'];
        $exp_obat = $obat['exp_obat'];

        echo "<tr>
            <td>{$obat['nm_obat']}</td>
            <td>{$obat['bentuk_obat']}</td>
            <td>{$obat['stok_obat']}</td>
            <td>{$obat['exp_obat']}</td>
            <td>
                <button type='button' class='btn btn-success btn-sm'
                    onclick=\"pilihObat('{$id_obat}', '{$nm_obat}', '{$bentuk_obat}', {$stok_obat})\">
                    Pilih
                </button>
            </td>
        </tr>";
    }
} else {
    echo "<tr><td colspan='5' class='text-center text-danger'>❌ Obat tidak ditemukan.</td></tr>";
}
?>
