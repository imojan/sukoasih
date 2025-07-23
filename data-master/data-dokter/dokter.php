<?php
session_start();
include '../../koneksi.php';

if (!isset($_SESSION["jabatan"])) {
    echo "<script>location='../../login/index.php'</script>";
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <link rel="shortcut icon" type="image/png" href="../../assets/img/sehat-1.png" />
    <title>Suko Asih | Data Dokter</title>
    <link href="../../assets/css/styles.css" rel="stylesheet" />
    <link href="../../assets/css/dataTables.bootstrap4.min.css" rel="stylesheet" />
    <script src="../../assets/js/all.min.js"></script>
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
                        
                        <?php if ($_SESSION["jabatan"] == 'pendaftaran') : ?>
                            <a class="nav-link collapsed active" href="#" data-toggle="collapse" data-target="#data-master" aria-expanded="false" aria-controls="data-master">
                                <div class="sb-nav-link-icon"><i class="fas fa-columns"></i></div>
                                Data Master
                                <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                            </a>
                            <div class="collapse show" id="data-master" aria-labelledby="headingOne" data-parent="#sidenavAccordion">
                                <nav class="sb-sidenav-menu-nested nav">
                                    <a class="nav-link" href="../data-pasien/pasien.php">Data Pasien</a>
                                    <a class="nav-link active" href="../data-dokter/dokter.php">Data Dokter</a>
                                    <a class="nav-link" href="../data-poli/poli.php">Data Poliklinik</a>
                                </nav>
                            </div>
                        
                            <a class="nav-link" href="../../data-pendaftaran/pendaftaran.php">
                                <div class="sb-nav-link-icon"><i class="fas fa-clipboard-list"></i></div>
                                Data Pendaftaran
                            </a>
                            <a class="nav-link" href="../../data-pemeriksaan/screening.php">
                                <div class="sb-nav-link-icon"><i class="fas fa-address-book"></i></div>
                                Pemeriksaan Petugas
                            </a>

                            <?php elseif ($_SESSION["jabatan"] == 'pimpinan') : ?>
                                <a class="nav-link collapsed active" href="#" data-toggle="collapse" data-target="#data-master" aria-expanded="false" aria-controls="data-master">
                                        <div class="sb-nav-link-icon"><i class="fas fa-columns"></i></div>
                                        Data Master
                                        <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                                    </a>
                                    <div class="collapse show" id="data-master" aria-labelledby="headingOne" data-parent="#sidenavAccordion">
                                        <nav class="sb-sidenav-menu-nested nav">
                                            <a class="nav-link" href="../data-pasien/pasien.php">Data Pasien</a>
                                            <a class="nav-link active" href="../data-dokter/dokter.php">Data Dokter</a>
                                            <a class="nav-link" href="../data-poli/poli.php">Data Poliklinik</a>
                                            <a class="nav-link" href="../data-user/user.php">Data Pengguna</a>
                                        </nav>
                                    </div>
                                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#laporan" aria-expanded="false" aria-controls="laporan">
                                    <div class="sb-nav-link-icon"><i class="fas fa-columns"></i></div>
                                    Laporan
                                    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                                </a>
                                <div class="collapse" id="laporan" aria-labelledby="headingOne" data-parent="#sidenavAccordion">
                                    <nav class="sb-sidenav-menu-nested nav">
                                        <a class="nav-link" href="../../laporan/laporan-pasien/laporan-pasien.php">Laporan Pasien</a>
                                        <a class="nav-link" href="../../laporan/laporan-dokter/laporan-dokter.php">Laporan Dokter</a>
                                        <a class="nav-link" href="../../laporan/laporan-obat/laporan-obat.php">Laporan Obat</a>
                                        <a class="nav-link" href="../../laporan/laporan-poliklinik/laporan-poliklinik.php">Laporan Poliklinik</a>
                                        <a class="nav-link" href="../../laporan/laporan-pengguna/laporan-pengguna.php">Laporan Pengguna</a>
                                        <a class="nav-link" href="../../laporan/laporan-pendaftaran/laporan-pendaftaran.php">Laporan Pendaftaran</a>
                                        <a class="nav-link" href="../../laporan/laporan-pemeriksaan/laporan-pemeriksaan.php">Laporan Pemeriksaan</a>
                                        <a class="nav-link" href="../../laporan/laporan-resep-obat/laporan-resep-obat.php">Laporan Resep Obat</a>
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
                    <h1 class="mt-4">Data Dokter</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item"><a href="../../index.php" class="text-decoration-none">Dashboard</a></li>
                        <li class="breadcrumb-item active">Data Master</li>
                        <li class="breadcrumb-item active">Data Dokter</li>
                    </ol>
                    <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                    <div>
                        <i class="fas fa-table mr-1"></i> Tabel Data Dokter
                    </div>
                    <?php if ($_SESSION["jabatan"] == 'pimpinan') : ?>
                        <a href="dokter_tambah.php" class="btn btn-success btn-sm font-weight-bold">
                            <i class="fas fa-plus"></i> Tambah Data Dokter
                        </a>
                    <?php endif; ?>
                    </div>

                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>ID Dokter</th>
                                            <th>Nama Dokter</th>
                                            <th>Spesialis</th>
                                            <th>No. Telepon</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $nomor = 1; ?>
                                        <?php $ambil = $koneksi->query("SELECT * FROM tb_dokter JOIN tb_poli ON tb_dokter.id_poli = tb_poli.id_poli"); ?>
                                        <?php while ($pecah = $ambil->fetch_assoc()) { ?>
                                            <tr>
                                                <td><?php echo $nomor; ?></td>
                                                <td><?php echo 'D' . str_pad($pecah['id_dokter'], 3, '0', STR_PAD_LEFT); ?></td>
                                                <td><?php echo $pecah['nm_dokter']; ?></td>
                                                <td><?php echo $pecah['nm_poli']; ?></td>
                                                <td><?php echo $pecah['telp_dokter']; ?></td>
                                                <td>
                                                    <?php if ($_SESSION["jabatan"] == 'pendaftaran') : ?>
                                                        <a href="dokter_view.php?&id_dokter=<?php echo $pecah['id_dokter']; ?>" class="btn-primary btn-sm btn">
                                                                <i class="fas fa-eye"></i></i>
                                                        </a>
                                                    <?php elseif ($_SESSION["jabatan"] == 'pimpinan') : ?>
                                                    <a href="dokter_ubah.php?&id_dokter=<?php echo $pecah['id_dokter']; ?>" class="btn-warning btn-sm btn">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <a href="dokter_hapus.php?&id_dokter=<?php echo $pecah['id_dokter']; ?>" class="btn-danger btn-sm btn">
                                                        <i class="fas fa-trash"></i>
                                                    </a>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                            <?php $nomor++; ?>
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
    <script src="../../assets/js/jquery-3.5.1.slim.min.js"></script>
    <script src="../../assets/js/bootstrap.bundle.min.js"></script>
    <script src="../../assets/js/scripts.js"></script>
    <script src="../../assets/js/jquery.dataTables.min.js"></script>
    <script src="../../assets/js/dataTables.bootstrap4.min.js"></script>
    <script src="../../assets/demo/datatables-demo.js"></script>
</body>

</html>