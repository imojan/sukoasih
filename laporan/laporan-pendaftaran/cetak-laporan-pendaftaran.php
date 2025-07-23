<?php
include '../../koneksi.php';

// Ambil tanggal dari parameter GET jika ada
$where = "";
if (isset($_GET['dari']) && isset($_GET['sampai']) && $_GET['dari'] && $_GET['sampai']) {
    $dari = $_GET['dari'];
    $sampai = $_GET['sampai'];
    $where = "WHERE DATE(p.tgl_pendaftaran) BETWEEN '$dari' AND '$sampai'";
}

$data = mysqli_query($koneksi, "
    SELECT 
        p.id_pendaftaran,
        p.no_rm,
        p.id_dokter,
        p.id_poli,
        p.status AS status_kunjungan,
        ps.nm_pasien,
        ps.nm_pj,
        d.nm_dokter,
        l.nm_poli,
        p.tgl_pendaftaran
    FROM tb_pendaftaran p
    JOIN tb_pasien ps ON p.no_rm = ps.no_rm
    JOIN tb_dokter d ON p.id_dokter = d.id_dokter
    JOIN tb_poli l ON p.id_poli = l.id_poli
    $where
    ORDER BY p.id_pendaftaran ASC
");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Cetak Laporan Data Pendaftaran</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            color: #000;
            margin: 20px;
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        .header {
            text-align: center;
            margin-bottom: 10px;
        }

        .header img {
            height: 60px;
            margin-bottom: 5px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            border: 1px solid black;
            padding: 6px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
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
        <img src="../../login/img/logoutama.png" alt="Logo Klinik" />
        <h2>Klinik Utama Suko Asih</h2>
        <p>Jl. Veteran No. 32, Sukoharjo (Depan SMPN 2 SKH)</p>
        <p>Telp. (0271) 593917</p>
        <hr>
    </div>

    <h2>Laporan Data Pendaftaran</h2>

    <?php if (isset($dari) && isset($sampai)) : ?>
        <p style="text-align:center; font-weight:bold;">
            Periode: <?= date('d-m-Y', strtotime($dari)) ?> s.d <?= date('d-m-Y', strtotime($sampai)) ?>
        </p>
    <?php endif; ?>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Kode Daftar</th>
                <th>Nomor Rekam Medis</th>
                <th>Nama Pasien</th>
                <th>Tanggal Daftar</th>
                <th>Waktu Daftar</th>
                <th>Poliklinik</th>
                <th>Dokter</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $no = 1;
            while($row = mysqli_fetch_assoc($data)): ?>
            <tr>
                <td><?= $no++ ?></td>
                <td><?= $row['id_pendaftaran'] ?></td>
                <td><?= str_pad($row['no_rm'], 6, '0', STR_PAD_LEFT) ?></td>
                <td><?= $row['nm_pasien'] ?></td>
                <td><?= date('d-m-Y', strtotime($row['tgl_pendaftaran'])) ?></td>
                <td><?= date('H:i', strtotime($row['tgl_pendaftaran'])) ?></td>
                <td><?= $row['nm_poli'] ?></td>
                <td><?= $row['nm_dokter'] ?></td>
                <td>
                    <?php
                        $status = $row['status_kunjungan']; 
                        if ($status == 0) {
                            echo '<span class="badge badge-danger p-2">Pasien Baru</span>';
                        } elseif ($status == 1) {
                            echo '<span class="badge badge-primary p-2">Pasien Lama</span>';
                        } else {
                            echo '<span class="badge badge-secondary p-2">Status Tidak Diketahui</span>';
                        }
                        ?>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

</body>
</html>
