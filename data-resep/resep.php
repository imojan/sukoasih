<?php
session_start();
include '../koneksi.php';

if (!isset($_SESSION["jabatan"])) {
    echo "<script>location='../login/index.php'</script>";
    exit();
}

$query = mysqli_query($koneksi, "
    SELECT 
        r.id_resep,
        r.id_pemeriksaan,
        GROUP_CONCAT(CONCAT(o.nm_obat, ' ', ' (', d.cara_minum, ')') SEPARATOR '<br>') AS daftar_obat,
        GROUP_CONCAT(CONCAT(d.jumlah, ' (', o.bentuk_obat, ')') SEPARATOR '<br>') AS jumlah_obat,
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
    GROUP BY r.id_resep
    ORDER BY r.id_resep ASC
");

$id_pemeriksaan = $_GET['id_pemeriksaan'] ?? '';

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
    <link rel="shortcut icon" type="image/png" href="../assets/img/sehat-1.png" />
    <title>Suko Asih | Resep Obat</title>
    <link href="../assets/css/styles.css" rel="stylesheet" />
    <link href="../assets/css/dataTables.bootstrap4.min.css" rel="stylesheet" />
    <script src="../assets/js/all.min.js"></script>
</head>

<body class="sb-nav-fixed">
    <nav class="sb-topnav navbar navbar-expand navbar-dark bg-light">
        <a class="navbar-brand text-center" href="../index.php">
            <img src="../login/img/logodashboard.png" alt="Logo Klinik Suko Asih" style="height: 60px;">
        </a>
        <button class="btn btn-link btn-sm order-1 order-lg-0" id="sidebarToggle" href="#"><i class="fas fa-bars" style="color: black;"></i></button>
        <div class="d-none d-md-inline-block form-inline ml-auto mr-0 mr-md-3 my-2 my-md-0"> </div>
        <!-- Navbar-->
        <ul class="navbar-nav ml-auto ml-md-0">
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" id="userDropdown" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fas fa-user fa-fw" style="color: black;"></i></a>
                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="userDropdown">
                    <a class="dropdown-item" href="../login/logout.php">Logout</a>
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
                        <a class="nav-link" href="../index.php">
                            <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                            Dashboard
                        </a>

                        <?php if ($_SESSION["jabatan"] == 'dokter') : ?>
                            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#data-master" aria-expanded="false" aria-controls="data-master">
                                <div class="sb-nav-link-icon"><i class="fas fa-columns"></i></div>
                                Data Master
                                <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                            </a>
                            <div class="collapse" id="data-master" aria-labelledby="headingOne" data-parent="#sidenavAccordion">
                                <nav class="sb-sidenav-menu-nested nav">
                                    <a class="nav-link" href="../data-master/data-pasien/pasien.php">Data Pasien</a>
                                    <a class="nav-link" href="../data-master/data-obat/obat.php">Data Obat</a>
                                </nav>
                            </div>
                            <a class="nav-link" href="../data-pendaftaran/pendaftaran.php">
                                <div class="sb-nav-link-icon"><i class="fas fa-clipboard-list"></i></div>
                                Data Pendaftaran
                            </a>
                            <a class="nav-link" href="../data-pemeriksaan/pemeriksaan.php">
                                <div class="sb-nav-link-icon"><i class="fas fa-address-book"></i></div>
                                Pemeriksaan Dokter
                            </a>
                            <a class="nav-link active" href="../data-resep/resep.php">
                                <div class="sb-nav-link-icon"><i class="fas fa-scroll"></i></div>
                                Cetak Resep Obat
                            </a>
                            
                        <?php elseif ($_SESSION["jabatan"] == 'apoteker') : ?>
                            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#data-master" aria-expanded="false" aria-controls="data-master">
                                <div class="sb-nav-link-icon"><i class="fas fa-columns"></i></div>
                                Data Master
                                <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                            </a>
                            <div class="collapse" id="data-master" aria-labelledby="headingOne" data-parent="#sidenavAccordion">
                                <nav class="sb-sidenav-menu-nested nav">
                                    <a class="nav-link" href="../data-master/data-obat/obat.php">Data Obat</a>
                                </nav>
                            </div>
                            <a class="nav-link" href="../data-pemeriksaan/pemeriksaan.php">
                                <div class="sb-nav-link-icon"><i class="fas fa-address-book"></i></div>
                                Pemeriksaan Dokter
                            </a>
                            <a class="nav-link active" href="../data-resep/resep.php">
                                <div class="sb-nav-link-icon"><i class="fas fa-scroll"></i></div>
                                Cetak Resep Obat
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
                <p class="text-center mt-3 text-muted" style="font-size: 0.9rem;">&copy; 2025 Klinik Suko Asih</p>
            </nav>
        </div>
        <div id="layoutSidenav_content" class="bg-white text-dark">
            <main>
                <div class="container-fluid">
                    <h1 class="mt-4">Data Resep Obat</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item"><a href="../index.php" class="text-decoration-none">Dashboard</a></li>
                        <li class="breadcrumb-item active">Data Resep Obat</li>
                    </ol>
                    <div class="card mb-4">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <div>
                                <i class="fas fa-table mr-1"></i> Tabel Data Resep Obat
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>ID Resep</th>
                                            <th>Pasien</th>
                                            <th>Tanggal</th>
                                            <th>Diagnosa</th>
                                            <th>Terapi</th>
                                            <th>Resep Obat</th>
                                            <th>Jumlah Obat</th>
                                            <th>Aksi</th>
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
                                                $jenis = $pecah['jenis_kia'];
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
                                                    $jenis = $pecah['jenis_kia'];
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
                                            <td>
                                                <a href="resep_cetak.php?id=<?= $pecah['id_resep'] ?>" class="btn btn-info btn-sm" target="_blank">Cetak</a>
                                            </td>
                                        </tr>
                                        <?php endwhile; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
    <script src="../assets/js/jquery-3.5.1.slim.min.js"></script>
    <script src="../assets/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/js/scripts.js"></script>
    <script src="../assets/js/jquery.dataTables.min.js"></script>
    <script src="../assets/js/dataTables.bootstrap4.min.js"></script>
    <script src="../assets/demo/datatables-demo.js"></script>
</body>

</html>