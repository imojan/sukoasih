<?php
include '../koneksi.php';

$id_resep = $_GET['id'];
$data = mysqli_fetch_assoc(mysqli_query($koneksi, "
    SELECT r.*, ps.nm_pasien, ps.no_rm, ps.alamat, ps.tgl_lahir,
           p.tgl_pemeriksaan, dktr.nm_dokter
    FROM tb_resep r
    JOIN tb_pemeriksaan p ON r.id_pemeriksaan = p.id_pemeriksaan
    JOIN tb_pendaftaran d ON p.id_pendaftaran = d.id_pendaftaran
    JOIN tb_pasien ps ON d.no_rm = ps.no_rm
    JOIN tb_dokter dktr ON d.id_dokter = dktr.id_dokter
    WHERE r.id_resep = '$id_resep'
"));

$obat_query = mysqli_query($koneksi, "
    SELECT o.nm_obat, dr.cara_minum
    FROM tb_detail_resep dr
    JOIN tb_obat o ON dr.id_obat = o.id_obat
    WHERE dr.id_resep = '$id_resep'
");

$tanggal_lahir = isset($data['tgl_lahir']) ? new DateTime($data['tgl_lahir']) : null;
$umur = $tanggal_lahir ? (new DateTime())->diff($tanggal_lahir)->y : '-';

$nama_dokter = $data['nm_dokter'] ?? '-';
$alamat = $data['alamat'] ?? '-';

?>

<!DOCTYPE html>
<html>
<head>
    <title>Resep Obat - <?= $data['nm_pasien'] ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 20px;
            color: #000;
        }

        .header {
            text-align: center;
            margin-bottom: 10px;
        }

        .header img {
            height: 60px;
            margin-bottom: 5px;
        }

        .header h2, .header p {
            margin: 0;
            padding: 0;
        }

        .info-table, .obat-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        .info-table td {
            padding: 4px 30px;
        }

        .obat-table th, .obat-table td {
            border: 1px solid black;
            padding: 6px;
            text-align: center;
        }

        .obat-table th {
            background-color: #f2f2f2;
        }

        .section-title {
            margin-top: 20px;
            font-weight: bold;
            font-size: 14px;
            text-align: center;
        }

        @media print {
            body {
                margin: 0;
                padding: 10mm;
            }
        }
    </style>
</head>
<body onload="window.print()">

    <div class="header">
        <img src="../login/img/logoutama.png" alt="Logo Klinik" />
        <h2>Klinik Utama Suko Asih</h2>
        <p>Jl. Veteran No. 32, Sukoharjo (Depan SMPN 2 SKH)</p>
        <p>Telp. (0271) 593917</p>
        <hr>
    </div>

    <div class="section-title">RESEP OBAT</div>

    <table class="info-table">
        <tr>
           <td><strong>No. RM</strong></td><td>: <?= str_pad($data['no_rm'], 6, '0', STR_PAD_LEFT) ?></td>
            <td><strong>Dokter</strong></td><td>: <?= $data['nm_dokter'] ?></td>
        </tr>
        <tr>
            <td><strong>Nama</strong></td><td>: <?= $data['nm_pasien'] ?></td>
            <td><strong>Tanggal</strong></td><td>: <?= date('d-m-Y', strtotime($data['tgl_pemeriksaan'])) ?></td>
        </tr>
        <tr>
            <td><strong>Umur</strong></td><td>: <?= $umur ?> tahun</td>
            <td><strong>Alamat</strong></td><td>: <?= $data['alamat'] ?></td>
        </tr>
    </table>

    <table class="obat-table">
        <thead>
            <tr>
                <th>Nama Obat</th>
                <th>Cara Minum</th>
                <th>Keterangan</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($obat = mysqli_fetch_assoc($obat_query)) : ?>
            <tr>
                <td><?= $obat['nm_obat'] ?></td>
                <td><?= $obat['cara_minum'] ?></td>
                <td></td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>

    <p style="text-align: center; font-weight: bold; font-size: 14px;"> ---- Semoga Lekas Sembuh ---- </p>

    <p style="margin-top: 30px; text-align: right;">
        Sukoharjo, <?= date('d-m-Y', strtotime($data['tgl_pemeriksaan'])) ?><br>
    </p>
    <hr style="border: 1px dashed black; margin: 30px 0 10px 0;">
</body>
</html>