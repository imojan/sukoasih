<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
include '../koneksi.php';

if (!isset($_SESSION["jabatan"])) {
    echo "<script>location='../login/index.php'</script>";
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <link rel="shortcut icon" type="image/png" href="../assets/img/sehat-1.png" />
    <title>Suko Asih | Data Pendaftaran</title>
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

                        <?php if ($_SESSION["jabatan"] == 'pendaftaran') : ?>
                            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#data-master" aria-expanded="false" aria-controls="data-master">
                                <div class="sb-nav-link-icon"><i class="fas fa-columns"></i></div>
                                Data Master
                                <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                            </a>
                            <div class="collapse" id="data-master" aria-labelledby="headingOne" data-parent="#sidenavAccordion">
                                <nav class="sb-sidenav-menu-nested nav">
                                    <a class="nav-link" href="../data-master/data-pasien/pasien.php">Data Pasien</a>
                                    <a class="nav-link" href="../data-master/data-dokter/dokter.php">Data Dokter</a>
                                    <a class="nav-link" href="../data-master/data-poli/poli.php">Data Poliklinik</a>
                                </nav>
                            </div>
                            <a class="nav-link active" href="../data-pendaftaran/pendaftaran.php">
                                <div class="sb-nav-link-icon"><i class="fas fa-clipboard-list"></i></div>
                                Data Pendaftaran
                            </a>
                            <a class="nav-link" href="../data-pemeriksaan/screening.php">
                                <div class="sb-nav-link-icon"><i class="fas fa-clipboard-list"></i></div>
                                Pemeriksaan Petugas
                            </a>
                        
                        <?php elseif ($_SESSION["jabatan"] == 'dokter') : ?>
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
                            <a class="nav-link active" href="../data-pendaftaran/pendaftaran.php">
                                <div class="sb-nav-link-icon"><i class="fas fa-clipboard-list"></i></div>
                                Data Pendaftaran
                            </a>
                            <a class="nav-link" href="../data-pemeriksaan/pemeriksaan.php">
                                <div class="sb-nav-link-icon"><i class="fas fa-address-book"></i></div>
                                Pemeriksaan Dokter
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
                    <h1 class="mt-4">Data Pendaftaran</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item"><a href="../index.php" class="text-decoration-none">Dashboard</a></li>
                        <li class="breadcrumb-item active">Data Pendaftaran</li>
                    </ol>
                    <div class="card mb-4">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <div>
                            <i class="fas fa-table mr-1"></i> Tabel Data Pendaftaran
                            </div>
                        <?php if ($_SESSION["jabatan"] == 'pendaftaran') : ?>
                            <a href="pendaftaran_tambah.php" class="btn btn-success btn-sm font-weight-bold">
                                <i class="fas fa-plus"></i> Tambah Data Pendaftaran
                            </a>
                        <?php endif; ?>
                    </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Kode Daftar</th>
                                            <th>No. RM</th>
                                            <th>Nama Pasien</th>
                                            <th>Tanggal Daftar</th>
                                            <th>Waktu Daftar</th>
                                            <th>Poli</th>
                                            <th>Dokter</th>
                                            <th>Aksi</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $nomor = 1; ?>
                                        <?php
                                        $ambil = mysqli_query($koneksi, "
                                            SELECT 
                                                p.id_pendaftaran,
                                                p.no_rm,
                                                p.id_dokter,
                                                p.id_poli,
                                                p.status AS status_kunjungan,
                                                pm.status AS status_pemeriksaan,
                                                ps.nm_pasien,
                                                ps.nm_pj,
                                                d.nm_dokter,
                                                l.nm_poli,
                                                p.tgl_pendaftaran,
                                                p.jenis_kia

                                            FROM tb_pendaftaran p
                                            JOIN tb_pasien ps ON p.no_rm = ps.no_rm
                                            JOIN tb_dokter d ON p.id_dokter = d.id_dokter
                                            JOIN tb_poli l ON p.id_poli = l.id_poli
                                            LEFT JOIN tb_pemeriksaan pm ON p.id_pendaftaran = pm.id_pendaftaran
                                            ORDER BY p.id_pendaftaran ASC
                                        ");
                                        ?>
                                        <?php while ($pecah = $ambil->fetch_assoc()) { ?>
                                            <tr>
                                                <td><?php echo $nomor++; ?></td>
                                                <td><?php echo $pecah['id_pendaftaran']; ?></td>
                                                <td><?php echo str_pad($pecah['no_rm'], 6, '0', STR_PAD_LEFT); ?></td>
                                                <td><?php echo $pecah['nm_pasien']; ?></td>
                                                <td><?php echo date('d-m-Y', strtotime($pecah['tgl_pendaftaran'])); ?></td>
                                                <td><?php echo date('H:i', strtotime($pecah['tgl_pendaftaran'])); ?></td>
                                                <td>
                                                <?php 
                                                if ($pecah['id_poli'] == 2) {
                                                    echo 'Umum';
                                                } elseif ($pecah['id_poli'] == 1) {
                                                    echo 'Obgyn';
                                                } elseif ($pecah['id_poli'] == 3) {
                                                    echo 'Gigi';
                                                } elseif ($pecah['id_poli'] == 4) {
                                                    echo 'KIA - ' . ucfirst($pecah['jenis_kia']);
                                                } else {
                                                    echo 'Tidak diketahui';
                                                }
                                                ?>
                                            </td>
                                                <td><?php echo $pecah['nm_dokter']; ?></td>
                                                <td>
                                                <?php if ($_SESSION["jabatan"] == 'pendaftaran') : ?>
                                                    <a href="pendaftaran_ubah.php?id_pendaftaran=<?= $pecah['id_pendaftaran']; ?>" class="btn-warning btn-sm btn">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <?php if ($pecah['status_pemeriksaan'] != 3) { ?>
                                                        <a href="pendaftaran_batal.php?id_pendaftaran=<?= $pecah['id_pendaftaran']; ?>" class="btn btn-danger btn-sm">
                                                            <i class="fas fa-times"></i>
                                                        </a>
                                                    <?php } ?>
                                                <?php endif; ?>
                                                </td>
                                                <td>
                                                    <?php
                                                    $status = $pecah['status_kunjungan']; 
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
                                        <?php } ?>
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