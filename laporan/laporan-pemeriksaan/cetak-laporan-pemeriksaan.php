<?php
include '../../koneksi.php';
$dari = $_GET['dari'] ?? '';
$sampai = $_GET['sampai'] ?? '';

?>

<!DOCTYPE html>
<html>
<head>
    <title>Cetak Laporan Data Pemeriksaan</title>
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
            padding: 3px;
            text-align: center;
            word-wrap: break-word;
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
    <h2>Laporan Data Pemeriksaan</h2>
    <?php if (isset($dari) && isset($sampai)) : ?>
        <p style="text-align:center; font-weight:bold;">
            Periode: <?= date('d-m-Y', strtotime($dari)) ?> s.d <?= date('d-m-Y', strtotime($sampai)) ?>
        </p>
    <?php endif; ?>

    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
        <thead>
            <tr>
                <th>No</th>
                <th>ID Periksa</th>
                <th>Nama Pasien</th>
                <th>Tgl Periksa</th>
                <th>Nama Dokter</th>
                <th>Petugas Screening</th>
                <th>Keluhan</th>
                <th>Diagnosa</th>
                <th>Terapi</th>
                <th>Tindak Lanjut</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $nomor = 1; ?>
            <?php
            $query = "
                SELECT 
                    a.id_pemeriksaan,
                    b.nm_pasien,
                    a.tgl_pemeriksaan,
                    d.nm_dokter,
                    e.nm_lengkap,
                    l.id_poli,
                    f.jenis_kia,

                    pu.keluhan AS keluhan_umum,
                    pu.diagnosa AS diagnosa_umum,
                    pu.terapi AS terapi_umum,
                    pu.tindak_lanjut AS tindak_umum,

                    po.keluhan AS keluhan_obgyn,
                    po.diagnosa AS diagnosa_obgyn,
                    po.terapi AS terapi_obgyn,
                    po.tindak_lanjut AS tindak_obgyn,

                    pk.anamnesa AS keluhan_kb,
                    pk.diagnosa AS diagnosa_kb,
                    pk.terapi AS terapi_kb,
                    pk.tindak_lanjut AS tindak_kb,


                    pg.S AS keluhan_gigi,
                    pg.A AS diagnosa_gigi,
                    pg.P AS terapi_gigi,
                    pg.tindak_lanjut AS tindak_gigi,

                    pi.diagnosa AS diagnosa_imunisasi,
                    pi.terapi AS terapi_imunisasi,
                    pi.tindak_lanjut AS tindak_imunisasi

                FROM tb_pemeriksaan a
                JOIN tb_pendaftaran f ON a.id_pendaftaran = f.id_pendaftaran
                JOIN tb_pasien b ON a.no_rm = b.no_rm
                JOIN tb_dokter d ON f.id_dokter = d.id_dokter
                JOIN tb_poli l ON a.id_poli = l.id_poli
                JOIN tb_user e ON a.id_user = e.id_user
                LEFT JOIN tb_pemeriksaan_umum pu ON a.id_pemeriksaan = pu.id_pemeriksaan
                LEFT JOIN tb_pemeriksaan_obgyn po ON a.id_pemeriksaan = po.id_pemeriksaan
                LEFT JOIN tb_pemeriksaan_kb pk ON a.id_pemeriksaan = pk.id_pemeriksaan
                LEFT JOIN tb_pemeriksaan_gigi pg ON a.id_pemeriksaan = pg.id_pemeriksaan
                LEFT JOIN tb_pemeriksaan_imunisasi pi ON a.id_pemeriksaan = pi.id_pemeriksaan
            ";
            if (isset($_GET['dari']) && isset($_GET['sampai']) && $_GET['dari'] && $_GET['sampai']) {
                $dari = $_GET['dari'];
                $sampai = $_GET['sampai'];
                $query .= " WHERE a.tgl_pemeriksaan BETWEEN '$dari' AND '$sampai' ";
            }
            $query .= "ORDER BY a.tgl_pemeriksaan ASC";
            $ambil = mysqli_query($koneksi, $query);
            
            ?>
            <?php while ($pecah = $ambil->fetch_assoc()) :
                $keluhan = $diagnosa = $terapi = $tindak = '-';
                if ($pecah['id_poli'] == 2) {
                    $keluhan = $pecah['keluhan_umum'] ?? '-';
                    $diagnosa = $pecah['diagnosa_umum'] ?? '-';
                    $terapi = $pecah['terapi_umum'] ?? '-';
                    $tindak = $pecah['tindak_umum'] ?? '-';
                } elseif ($pecah['id_poli'] == 1) {
                    $keluhan = $pecah['keluhan_obgyn'] ?? '-';
                    $diagnosa = $pecah['diagnosa_obgyn'] ?? '-';
                    $terapi = $pecah['terapi_obgyn'] ?? '-';
                    $tindak = $pecah['tindak_obgyn'] ?? '-';
                } elseif ($pecah['id_poli'] == 3) {
                    $keluhan = $pecah['keluhan_gigi'] ?? '-';
                    $diagnosa = $pecah['diagnosa_gigi'] ?? '-';
                    $terapi = $pecah['terapi_gigi'] ?? '-';
                    $tindak = $pecah['tindak_gigi'] ?? '-';
                } elseif ($pecah['id_poli'] == 4) {
                    if ($pecah['jenis_kia'] == 'Umum') {
                        $keluhan = $pecah['keluhan_umum'] ?? '-';
                        $diagnosa = $pecah['diagnosa_umum'] ?? '-';
                        $terapi = $pecah['terapi_umum'] ?? '-';
                        $tindak = $pecah['tindak_umum'] ?? '-';
                    } elseif ($pecah['jenis_kia'] == 'Obgyn') {
                        $keluhan = $pecah['keluhan_obgyn'] ?? '-';
                        $diagnosa = $pecah['diagnosa_obgyn'] ?? '-';
                        $terapi = $pecah['terapi_obgyn'] ?? '-';
                        $tindak = $pecah['tindak_obgyn'] ?? '-';
                    } elseif ($pecah['jenis_kia'] == 'Imunisasi') {
                        $keluhan = $pecah['keluhan_imunisasi'] ?? '-';
                        $diagnosa = $pecah['diagnosa_imunisasi'] ?? '-';
                        $terapi = $pecah['terapi_imunisasi'] ?? '-';
                        $tindak = $pecah['tindak_imunisasi'] ?? '-';
                    } elseif ($pecah['jenis_kia'] == 'KB') {
                        $keluhan = $pecah['keluhan_kb'] ?? '-';
                        $diagnosa = $pecah['diagnosa_kb'] ?? '-';
                        $terapi = $pecah['terapi_kb'] ?? '-';
                        $tindak = $pecah['tindak_kb'] ?? '-';
                    }
                }
            ?>
            <tr>
                <td><?= $nomor++; ?></td>
                <td><?= $pecah['id_pemeriksaan']; ?></td>
                <td><?= $pecah['nm_pasien']; ?></td>
                <td><?= date('d-m-Y', strtotime($pecah['tgl_pemeriksaan'])) ?? '-'; ?></td>
                <td><?= $pecah['nm_dokter']; ?></td>
                <td><?= $pecah['nm_lengkap']; ?></td>
                <td><?= $keluhan; ?></td>
                <td><?= $diagnosa; ?></td>
                <td><?= $terapi; ?></td>
                <td>
                    <?php
                    switch (strtolower($tindak)) {
                        case 'selesai':
                            echo '<span class="badge badge-primary p-2">Selesai</span>';
                            break;
                        case 'kontrol':
                            echo '<span class="badge badge-success p-2">Kontrol</span>';
                            break;
                        case 'rujuk':
                            echo '<span class="badge badge-warning p-2">Rujuk</span>';
                            break;
                        case 'rawat inap':
                            echo '<span class="badge badge-danger p-2">Rawat Inap</span>';
                            break;
                        default:
                            echo '<span class="badge badge-secondary p-2">Tidak Diketahui</span>';
                            break;
                    }
                    ?>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>    
    </table>

</body>
</html>
