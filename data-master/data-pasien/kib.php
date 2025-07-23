<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include "../../koneksi.php";
$kode = $_GET['no_rm'];
$ambil = $koneksi->query("SELECT * FROM tb_pasien WHERE no_rm='$kode'");

function tgl_indo($tanggal)
{
    $bulan = [
        1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
        'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
    ];
    $pecahkan = explode('-', $tanggal);
    return $pecahkan[2] . ' ' . $bulan[(int)$pecahkan[1]] . ' ' . $pecahkan[0];
}
?>

<!DOCTYPE html>
<html>
<head>
   <title>Kartu Identitas Berobat</title>
    <style>

    .title {
        text-align: center;
        font-weight: bold;
        font-size: 18px;
        margin-bottom: 4px;
    }

    .subtitle {
        text-align: center;
        font-size: 14px;
        margin: 2px 0;
    }

    @page {
        size: 85mm 55mm;
        margin: 0;
    }
    
    body {
        margin: 0;
        padding: 5mm;
        font-family: Arial, sans-serif;
        width: 85mm;
        height: 55mm;
        box-sizing: border-box;
    }

    .header {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 15px; /* Jarak antara logo dan tulisan */
        margin-bottom: 5px;
    }

    .logo {
        width: 70px;
        height: auto;
    }

    .title-container {
        text-align: center;
    }

    .title {
        font-weight: bold;
        font-size: 12px;
        margin: 0;
    }

    .subtitle {
        font-size: 10px;
        margin: 0;
    }

    .line {
        border-top: 1px solid #000;
        margin: 5px 0;
    }
    
    table {
        font-size: 10px;
        width: 70%;
    }
    
    td {
        vertical-align: top;
    }
    </style>
</head>
<body>
    <div class="header">
        <img class="logo" src="../../login/img/logoutama.png" alt="Logo Klinik" />
        <div class="container">
        <div class="title">KARTU IDENTITAS BEROBAT</div>
        <div class="subtitle">Klinik Utama Suko Asih</div>
        <div class="subtitle">Jl. Veteran No. 32, Sukoharjo</div>
        <div class="subtitle">(Depan SMPN 2 SKH) - Telp. (0271) 593917</div>    
    </div>
    </div>
        <div class="line"></div>
    <table>
    <?php while ($pecah = $ambil->fetch_assoc()) { ?>
        <tr><td><strong>No.RM</strong></td><td>: <?php echo str_pad($pecah['no_rm'], 6, '0', STR_PAD_LEFT); ?></td></tr>
        <tr><td><strong>Nama</strong></td><td>: <?= $pecah['nm_pasien']; ?></td></tr>
        <tr><td><strong>Jenis Kelamin</strong></td><td>: <?= $pecah['jenis_kelamin']; ?></td></tr>
        <tr><td><strong>Tanggal Lahir</strong></td><td>: <?= tgl_indo($pecah['tgl_lahir']); ?></td></tr>
        <tr><td><strong>Telepon</strong></td><td>: <?= $pecah['telp_psn']; ?></td></tr>
        <tr><td><strong>Alamat</strong></td><td>: <?= $pecah['alamat']; ?></td></tr>
        <tr><td><strong>Keterangan</strong></td><td>:</td></tr>
    <?php } ?>
    </table>

    <script type="text/javascript">
    window.print();
    </script>
</body>
</html>
