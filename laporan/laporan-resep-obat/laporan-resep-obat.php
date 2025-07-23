<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
include '../../koneksi.php';

if (!isset($_SESSION["jabatan"])) {
    echo "<script>location='../../login/index.php'</script>";
    exit();
}

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
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <link rel="shortcut icon" type="image/png" href="../../assets/img/sehat-1.png" />
    <title>Suko Asih | Laporan Resep Obat</title>
    <link href="../../assets/css/styles.css" rel="stylesheet" />
    <link href="../../assets/css/dataTables.bootstrap4.min.css" rel="stylesheet" />
    <script src="../../assets/js/all.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <!-- <style>
        table.dataTable td, table.dataTable th {
            border: 1px solid #dee2e6 !important;
        }
    </style> -->

</head>

<body class="sb-nav-fixed">
    <nav class="sb-topnav navbar navbar-expand navbar-dark bg-light">
        <a class="navbar-brand text-center" href="../../index.php">
            <img src="../../login/img/logodashboard.png" alt="Logo Klinik Suko Asih" style="height: 60px;">
        </a>
        <button class="btn btn-link btn-sm order-1 order-lg-0" id="sidebarToggle" href="#"><i class="fas fa-bars" style="color: black;"></i></button>
        <div class="d-none d-md-inline-block form-inline ml-auto mr-0 mr-md-3 my-2 my-md-0"> </div>
        <!-- Navbar-->
        <ul class="navbar-nav ml-auto ml-md-0">
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" id="userDropdown" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fas fa-user fa-fw" style="color: black;"></i></a>
                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="userDropdown">
                    <a class="dropdown-item" href="../../login/logout.php">Logout</a>
                </div>
            </li>
        </ul>
    </nav>
    <div id="layoutSidenav">
        <div id="layoutSidenav_nav">
            <nav class="sb-sidenav accordion sb-sidenav-light" id="sidenavAccordion">
                <div class="sb-sidenav-menu">
                    <div class="nav">
                        <div class="sb-sidenav-menu-heading">Rekam Medis</div>
                        <a class="nav-link" href="../../index.php">
                            <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                            Dashboard
                        </a>
                        <?php if ($_SESSION["jabatan"] == 'pimpinan') : ?>
                            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#data-master" aria-expanded="false" aria-controls="data-master">
                                <div class="sb-nav-link-icon"><i class="fas fa-columns"></i></div>
                                Data Master
                                <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                            </a>
                            <div class="collapse" id="data-master" aria-labelledby="headingOne" data-parent="#sidenavAccordion">
                                <nav class="sb-sidenav-menu-nested nav">
                                    <a class="nav-link" href="../../data-master/data-pasien/pasien.php">Data Pasien</a>
                                    <a class="nav-link" href="../../data-master/data-dokter/dokter.php">Data Dokter</a>
                                    <a class="nav-link" href="../../data-master/data-poli/poli.php">Data Poliklinik</a>
                                    <a class="nav-link" href="../../data-master/data-user/user.php">Data Pengguna</a>
                                </nav>
                            </div>
                            <a class="nav-link collapsed active" href="#" data-toggle="collapse" data-target="#laporan" aria-expanded="false" aria-controls="laporan">
                                <div class="sb-nav-link-icon"><i class="fas fa-columns"></i></div>
                                Laporan
                                <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                            </a>
                            <div class="collapse show" id="laporan" aria-labelledby="headingOne" data-parent="#sidenavAccordion">
                                <nav class="sb-sidenav-menu-nested nav">
                                    <a class="nav-link" href="../laporan-pasien/laporan-pasien.php">Laporan Pasien</a>
                                    <a class="nav-link" href="../laporan-dokter/laporan-dokter.php">Laporan Dokter</a>
                                    <a class="nav-link" href="../laporan-obat/laporan-obat.php">Laporan Obat</a>
                                    <a class="nav-link" href="../laporan-poliklinik/laporan-poliklinik.php">Laporan Poliklinik</a>
                                    <a class="nav-link" href="../laporan-pengguna/laporan-pengguna.php">Laporan Pengguna</a>
                                    <a class="nav-link" href="../laporan-pendaftaran/laporan-pendaftaran.php">Laporan Pendaftaran</a>
                                    <a class="nav-link" href="../laporan-pemeriksaan/laporan-pemeriksaan.php">Laporan Pemeriksaan</a>
                                    <a class="nav-link active" href="../laporan-resep-obat/laporan-resep-obat.php">Laporan Resep Obat</a>
                                </nav>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                <p class="text-center mt-3 text-muted" style="font-size: 0.9rem;">&copy; 2025 Klinik Suko Asih</p>
            </nav>
        </div>
        <div id="layoutSidenav_content" class="bg-white text-dark">
            <main>
                <div class="container-fluid">
                    <h1 class="mt-4">Laporan Data Resep Obat</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item"><a href="../../index.php" class="text-decoration-none">Dashboard</a></li>
                        <li class="breadcrumb-item active">Laporan</li>
                        <li class="breadcrumb-item active">Laporan Data Resep Obat</li>
                    </ol>
                    <div class="card mb-4">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <div>
                                <i class="fas fa-table mr-1"></i> Tabel Data Resep Obat
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <form method="GET" class="mb-3">
                                    <div class="row">
                                        <div class="col-md-5">
                                            <label>Dari Tanggal:</label>
                                            <input type="date" name="dari" class="form-control" value="<?php echo isset($_GET['dari']) ? $_GET['dari'] : '' ?>">
                                        </div>
                                        <div class="col-md-5">
                                            <label>Sampai Tanggal:</label>
                                            <input type="date" name="sampai" class="form-control" value="<?php echo isset($_GET['sampai']) ? $_GET['sampai'] : '' ?>">
                                        </div>
                                        <div class="col-md-2 d-flex align-items-end">
                                            <button type="submit" class="btn btn-primary w-100">Tampilkan</button>
                                        </div>
                                    </div>
                                </form>
                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
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
                                                if ($jenis == 'Umum') {
                                                    echo $pecah['diagnosa_umum'];
                                                } elseif ($jenis == 'Obgyn') {
                                                    echo $pecah['diagnosa_obgyn'];
                                                } elseif ($jenis == 'KB') {
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
                                                    if ($jenis == 'Umum') {
                                                        echo $pecah['terapi_umum'];
                                                    } elseif ($jenis == 'Obgyn') {
                                                        echo $pecah['terapi_obgyn'];
                                                    } elseif ($jenis == 'KB') {
                                                        echo $pecah['terapi_kb'];
                                                    } else {
                                                        echo '-';
                                                    }
                                                } else {
                                                    echo '-';
                                                }
                                                ?>
                                            </td>
                                            <td><?= $pecah['daftar_obat'] ?></td>
                                            <td><?= $pecah['jumlah_obat'] ?></td>
                                        </tr>
                                        <?php endwhile; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                       <div class="row mb-3 mx-2"> <!-- mx-2 kasih margin kanan-kiri -->
                            <div class="col-md-6 pe-1 mb-3">
                                <a 
                                    href="cetak-laporan-resep-obat.php<?= (isset($_GET['dari']) && isset($_GET['sampai'])) ? '?dari=' . $_GET['dari'] . '&sampai=' . $_GET['sampai'] : '' ?>" 
                                    target="_blank" 
                                    class="btn btn-danger w-100">
                                    <i class="fas fa-file-pdf"></i> Cetak PDF
                                </a>
                            </div>
                            <div class="col-md-6 ps-1">
                                <a 
                                    href="laporan-resep-obat-export.php<?= (isset($_GET['dari']) && isset($_GET['sampai'])) ? '?dari=' . $_GET['dari'] . '&sampai=' . $_GET['sampai'] : '' ?>" 
                                    class="btn btn-success w-100">
                                    <i class="fas fa-file-excel"></i> Export Excel
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
    <script src="../../assets/js/jquery-3.5.1.min.js"></script>
    <script src="../../assets/js/bootstrap.bundle.min.js"></script>
    <script src="../../assets/js/scripts.js"></script>
    <script src="../../assets/js/jquery.dataTables.min.js"></script>
    <script src="../../assets/js/dataTables.bootstrap4.min.js"></script>
    <script src="../../assets/demo/datatables-demo.js"></script>



</body>

</html>