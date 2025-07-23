<?php
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
    <title>Suko Asih | Pemeriksaan Dokter</title>
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
                            <a class="nav-link active" href="../data-pemeriksaan/pemeriksaan.php">
                                <div class="sb-nav-link-icon"><i class="fas fa-address-book"></i></div>
                                Pemeriksaan Dokter
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
                            <a class="nav-link active" href="../data-pemeriksaan/pemeriksaan.php">
                                <div class="sb-nav-link-icon"><i class="fas fa-address-book"></i></div>
                                Pemeriksaan Dokter
                            </a>
                            <a class="nav-link" href="../data-resep/resep.php">
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
                    <h1 class="mt-4">Pemeriksaan Dokter</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item"><a href="../index.php" class="text-decoration-none">Dashboard</a></li>
                        <li class="breadcrumb-item active">Pemeriksaan Dokter</li>
                    </ol>
                    <div class="card mb-4">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-md-9">
                                    <i class="fas fa-table mr-1 mt-2"></i>
                                    Tabel Data Pemeriksaan Umum, Obgyn, KIA, Gigi
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>ID Pemeriksaan</th>
                                            <th>Nama Pasien</th>
                                            <th>Tgl Periksa</th>
                                            <th>Nama Dokter</th>
                                            <th>Petugas Screening</th>
                                            <th>Nama Poli</th>
                                            <th>Keluhan</th>
                                            <th>Diagnosa</th>
                                            <th>Terapi</th>
                                            <th>Tindak Lanjut</th>
                                            <th>Status</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            $nomor = 1;
                                            $query = mysqli_query($koneksi, "
                                                SELECT 
                                                    a.id_pemeriksaan,
                                                    a.tgl_pemeriksaan,
                                                    b.nm_pasien,
                                                    c.id_dokter,
                                                    c.nm_dokter,
                                                    e.nm_lengkap,
                                                    l.nm_poli,
                                                    l.id_poli,
                                                    a.status AS status_pemeriksaan, 
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
                                                    pg.O AS diagnosa_gigi,
                                                    pg.P AS terapi_gigi,
                                                    pg.tindak_lanjut AS tindak_gigi,

                                                    pi.jenis_imunisasi AS keluhan_imunisasi,
                                                    pi.diagnosa AS diagnosa_imunisasi,
                                                    pi.terapi AS terapi_imunisasi,
                                                    pi.tindak_lanjut AS tindak_imunisasi

                                                FROM tb_pemeriksaan a
                                                JOIN tb_pasien b ON a.no_rm = b.no_rm
                                                JOIN tb_dokter c ON a.id_dokter = c.id_dokter
                                                JOIN tb_user e ON a.id_user = e.id_user
                                                JOIN tb_poli l ON a.id_poli = l.id_poli
                                                JOIN tb_pendaftaran f ON a.id_pendaftaran = f.id_pendaftaran
                                                LEFT JOIN tb_pemeriksaan_umum pu ON a.id_pemeriksaan = pu.id_pemeriksaan
                                                LEFT JOIN tb_pemeriksaan_obgyn po ON a.id_pemeriksaan = po.id_pemeriksaan
                                                LEFT JOIN tb_pemeriksaan_kb pk ON a.id_pemeriksaan = pk.id_pemeriksaan
                                                LEFT JOIN tb_pemeriksaan_gigi pg ON a.id_pemeriksaan = pg.id_pemeriksaan
                                                LEFT JOIN tb_pemeriksaan_imunisasi pi ON a.id_pemeriksaan = pi.id_pemeriksaan
                                            ");
                                        ?>
                                        <?php while ($pecah = mysqli_fetch_assoc($query)) : ?>
                                        <tr>
                                            <td><?php echo $nomor++; ?></td>
                                            <td><?php echo $pecah['id_pemeriksaan']; ?></td>
                                            <td><?php echo $pecah['nm_pasien']; ?></td>
                                            <td><?= date('d-m-Y', strtotime($pecah['tgl_pemeriksaan'])) ?? '-'; ?></td>
                                            <td><?php echo $pecah['nm_dokter']; ?></td>
                                            <td><?php echo $pecah['nm_lengkap']; ?></td>
                                            <td>
                                                <?php 
                                                if ($pecah['id_poli'] == 2) {
                                                    echo 'Umum';
                                                } elseif ($pecah['id_poli'] == 1) {
                                                    echo 'Obgyn';
                                                } elseif ($pecah['id_poli'] == 3) {
                                                    echo 'Gigi';
                                                } elseif ($pecah['id_poli'] == 4) {
                                                    echo 'KIA - ' .($pecah['jenis_kia']);
                                                } else {
                                                    echo 'Tidak diketahui';
                                                }
                                                ?>
                                            </td>
                                            <td>
                                                <?php 
                                                if ($pecah['id_poli'] == 2) {
                                                    echo $pecah['keluhan_umum'];
                                                } elseif ($pecah['id_poli'] == 1) {
                                                    echo $pecah['keluhan_obgyn'];
                                                } elseif ($pecah['id_poli'] == 3) {
                                                    echo $pecah['keluhan_gigi'];
                                                } elseif ($pecah['id_poli'] == 4) {
                                                    $jenis = $pecah['jenis_kia'];
                                                    if ($jenis == 'Umum') {
                                                        echo $pecah['keluhan_umum'];
                                                    } elseif ($jenis == 'Obgyn') {
                                                        echo $pecah['keluhan_obgyn'];
                                                    } elseif ($jenis == 'KB') {
                                                        echo $pecah['keluhan_kb'];
                                                    } elseif ($jenis == 'Imunisasi') {
                                                        echo $pecah['keluhan_imunisasi'];
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
                                                    } elseif ($jenis == 'Imunisasi') {
                                                        echo $pecah['diagnosa_imunisasi'];
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
                                                    } elseif ($jenis == 'Imunisasi') {
                                                        echo $pecah['terapi_imunisasi'];
                                                    } else {
                                                        echo '-';
                                                    }
                                                } else {
                                                    echo '-';
                                                }
                                                ?>
                                                <td>
                                                <?php 
                                                if ($pecah['id_poli'] == 2) {
                                                    $tindak = $pecah['tindak_umum'];
                                                } elseif ($pecah['id_poli'] == 1) {
                                                    $tindak = $pecah['tindak_obgyn'];
                                                } elseif ($pecah['id_poli'] == 3) {
                                                    $tindak = $pecah['tindak_gigi'];
                                                } elseif ($pecah['id_poli'] == 4) {
                                                    $jenis = $pecah['jenis_kia'];
                                                    if ($jenis == 'Umum') {
                                                        $tindak = $pecah['tindak_umum'];
                                                    } elseif ($jenis == 'Obgyn') {
                                                        $tindak = $pecah['tindak_obgyn'];
                                                    } elseif ($jenis == 'KB') {
                                                        $tindak = $pecah['tindak_kb'];
                                                    } elseif ($jenis == 'Imunisasi') {
                                                        $tindak = $pecah['tindak_imunisasi'];
                                                    } else {
                                                        $tindak = '-';
                                                    }
                                                } else {
                                                    $tindak = '-';
                                                }
                                                if ($tindak == 'Selesai') {
                                                    echo '<span class="badge badge-primary p-2">Selesai</span>';
                                                } elseif ($tindak == 'Kontrol') {
                                                    echo '<span class="badge badge-success p-2">Kontrol</span>';
                                                } elseif ($tindak == 'Rujuk') {
                                                    echo '<span class="badge badge-warning p-2">Rujuk</span>';
                                                } elseif ($tindak == 'Rawat Inap') {
                                                    echo '<span class="badge badge-danger p-2">Rawat Inap</span>';
                                                } else {
                                                    echo '<span class="badge badge-secondary p-2">Tidak Diketahui</span>';
                                                }
                                                ?>
                                            </td>
                                            <td>
                                                <?php
                                                $status = $pecah['status_pemeriksaan'];
                                                if ($status == 0) {
                                                    echo '<span class="badge badge-primary p-2">Belum Diperiksa</span>';
                                                } elseif ($status == 1) {
                                                    echo '<span class="badge badge-info p-2">Lanjut</span>';
                                                } elseif ($status == 2) {
                                                    echo '<span class="badge badge-success p-2">Selesai</span>';
                                                } elseif ($status == 3) {
                                                    echo '<span class="badge badge-danger p-2">Dibatalkan</span>';
                                                } else {
                                                    echo '<span class="badge badge-secondary p-2">Tidak Diketahui</span>';
                                                }
                                                ?>
                                            </td>
                                            <td>
                                                <?php if ($_SESSION["jabatan"] == 'dokter') : ?>
                                                    <a href="pemeriksaan_ubah.php?id_pemeriksaan=<?php echo $pecah['id_pemeriksaan']; ?>" class="btn btn-sm btn-warning">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                <?php elseif ($_SESSION["jabatan"] == 'apoteker') : ?>
                                                    <a href="pemeriksaan_view.php?id_pemeriksaan=<?php echo $pecah['id_pemeriksaan']; ?>" class="btn btn-sm btn-primary">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                <?php endif; ?>
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