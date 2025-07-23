<?php
include '../../koneksi.php';

$id_pemeriksaan = $_GET['id_pemeriksaan'] ?? '';

$where = "";
if (isset($_GET['dari']) && isset($_GET['sampai'])) {
    $dari = $_GET['dari'];
    $sampai = $_GET['sampai'];
    $where = "WHERE p.tgl_pemeriksaan BETWEEN '$dari' AND '$sampai'";
}

$query = mysqli_query($koneksi, "
    SELECT 
        r.id_resep,
        r.id_pemeriksaan,
        GROUP_CONCAT(CONCAT(o.nm_obat, ' ', ' (', d.cara_minum, ')') SEPARATOR '<br>') AS daftar_obat,
        GROUP_CONCAT(CONCAT(d.jumlah, ' ',' (', o.bentuk_obat, ')') SEPARATOR '<br>') AS jumlah_obat,
        p.tgl_pemeriksaan,
        ps.nm_pasien,
        l.nm_poli,
        l.id_poli,
        c.id_dokter,
        c.nm_dokter,
        f.jenis_kia,

        pu.diagnosa AS diagnosa_umum,
        pu.terapi AS terapi_umum,
        pu.tindak_lanjut AS tindak_umum,

        po.diagnosa AS diagnosa_obgyn,
        po.terapi AS terapi_obgyn,
        po.tindak_lanjut AS tindak_obgyn,

        pk.diagnosa AS diagnosa_kb,
        pk.terapi AS terapi_kb,
        pk.tindak_lanjut AS tindak_kb,

        pg.O AS diagnosa_gigi,
        pg.P AS terapi_gigi,
        pg.tindak_lanjut AS tindak_gigi,

        pi.diagnosa AS diagnosa_imunisasi,
        pi.terapi AS terapi_imunisasi,
        pi.tindak_lanjut AS tindak_imunisasi

    FROM tb_resep r
    JOIN tb_pemeriksaan p ON r.id_pemeriksaan = p.id_pemeriksaan
    JOIN tb_pasien ps ON p.no_rm = ps.no_rm
    JOIN tb_dokter c ON p.id_dokter = c.id_dokter
    JOIN tb_poli l ON p.id_poli = l.id_poli
    JOIN tb_pendaftaran f ON p.id_pendaftaran = f.id_pendaftaran
    JOIN tb_detail_resep d ON r.id_resep = d.id_resep
    JOIN tb_obat o ON d.id_obat = o.id_obat
    LEFT JOIN tb_pemeriksaan_imunisasi pi ON p.id_pemeriksaan = pi.id_pemeriksaan
    LEFT JOIN tb_pemeriksaan_umum pu ON p.id_pemeriksaan = pu.id_pemeriksaan
    LEFT JOIN tb_pemeriksaan_obgyn po ON p.id_pemeriksaan = po.id_pemeriksaan
    LEFT JOIN tb_pemeriksaan_kb pk ON p.id_pemeriksaan = pk.id_pemeriksaan
    LEFT JOIN tb_pemeriksaan_gigi pg ON p.id_pemeriksaan = pg.id_pemeriksaan

    $where
    GROUP BY r.id_resep
    ORDER BY r.id_resep ASC
");

$ambil_resep = mysqli_query($koneksi, "SELECT * FROM tb_resep WHERE id_pemeriksaan = '$id_pemeriksaan'");
$data_resep = mysqli_fetch_assoc($ambil_resep);

$daftar_obat = json_decode($data_resep['daftar_obat'] ?? '[]', true);
$jumlah_obat = json_decode($data_resep['jumlah_obat'] ?? '[]', true);

?>

<!DOCTYPE html>
<html>
<head>
    <title>Cetak Laporan Data Resep Obat</title>
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
            margin-top: 10px;
        }

        th, td {
        border: 1px solid black;
        text-align: center;
        vertical-align: center; 
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
    <h2>Laporan Data Resep Obat</h2>
    <?php if (isset($_GET['dari']) && isset($_GET['sampai'])): ?>
        <p style="text-align: center;">
            Periode: <?= date('d-m-Y', strtotime($_GET['dari'])) ?> sampai <?= date('d-m-Y', strtotime($_GET['sampai'])) ?>
        </p>
    <?php endif; ?>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>ID Resep</th>
                <th>Nama Pasien</th>
                <th>Tanggal Pemeriksaan</th>
                <th>Diagnosa</th>
                <th>Terapi</th>
                <th>Resep Obat</th>
                <th>Jumlah Obat</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $no = 1;
            if (mysqli_num_rows($query) > 0) {
                while ($pecah = mysqli_fetch_assoc($query)) :
            ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td><?= $pecah['id_resep'] ?></td>
                    <td><?= $pecah['nm_pasien'] ?></td>
                    <td><?= date('d-m-Y', strtotime($pecah['tgl_pemeriksaan'])) ?? '-'; ?></td>
                    <td>
                        <?php 
                        if ($pecah['id_poli'] == 2) {
                            echo $pecah['diagnosa_umum'];
                        } elseif ($pecah['id_poli'] == 1) {
                            echo $pecah['diagnosa_obgyn'];
                        } elseif ($pecah['id_poli'] == 3) {
                            echo $pecah['diagnosa_gigi'];
                        } elseif ($pecah['id_poli'] == 4) {
                            $jenis = strtolower($pecah['jenis_kia']);
                            if ($jenis == 'umum') {
                                echo $pecah['diagnosa_umum'];
                            } elseif ($jenis == 'obgyn') {
                                echo $pecah['diagnosa_obgyn'];
                            } elseif ($jenis == 'kb') {
                                echo $pecah['diagnosa_kb'];
                            } else {
                                echo '-';
                            }
                        } else {
                            echo '-';
                        }
                        ?>
                    </td>
                    <td>
                        <?php 
                        if ($pecah['id_poli'] == 2) {
                            echo $pecah['terapi_umum'];
                        } elseif ($pecah['id_poli'] == 1) {
                            echo $pecah['terapi_obgyn'];
                        } elseif ($pecah['id_poli'] == 3) {
                            echo $pecah['terapi_gigi'];
                        } elseif ($pecah['id_poli'] == 4) {
                            $jenis = strtolower($pecah['jenis_kia']);
                            if ($jenis == 'umum') {
                                echo $pecah['terapi_umum'];
                            } elseif ($jenis == 'obgyn') {
                                echo $pecah['terapi_obgyn'];
                            } elseif ($jenis == 'kb') {
                                echo $pecah['terapi_kb'];
                            } else {
                                echo '-';
                            }
                        } else {
                            echo '-';
                        }
                        ?>
                    </td>
                    <td><?= $pecah['daftar_obat'] ?? '-' ?></td>
                    <td><?= $pecah['jumlah_obat'] ?? '-' ?></td>
                </tr>
            <?php 
                endwhile;
            } else {
            ?>
                <tr>
                    <td colspan="8" class="text-center">Tidak ada data resep ditemukan.</td>
                </tr>
            <?php } ?>
        </tbody>
    </table>

</body>
</html>
