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

$nm_poli = '';
$id_pemeriksaan = $_POST['id_pemeriksaan'] ?? $_GET['id_pemeriksaan'] ?? '';
$id_poli = $_POST['id_poli'] ?? '';
$jenis_kia = $_POST['jenis_kia'] ?? '';

$resep_lama = [];

$pemeriksaan_detail = [];

if ($id_pemeriksaan != '') {
    $ambil = $koneksi->query("
        SELECT
            a.id_pemeriksaan,
            a.tgl_pemeriksaan,
            b.no_rm,
            b.nm_pasien,
            b.nm_pj,
            b.tgl_lahir,
            b.alergi_obat,
            b.riwayat_penyakit,
            d.id_dokter,
            d.nm_dokter,
            e.nm_lengkap,
            c.nm_poli,
            c.id_poli, 
            f.jenis_kia,
            a.status,

            pu.bb,
            pu.tb,
            pu.suhu,
            pu.td,
            pu.keluhan AS keluhan_umum,
            pu.diagnosa AS diagnosa_umum,
            pu.kd_diagnosa AS kd_diagnosa_umum,
            pu.terapi AS terapi_umum,
            pu.tindak_lanjut AS tindak_umum,

            po.hpht,
            po.hpl,
            po.td AS td_obgyn,
            po.bb AS bb_obgyn,
            po.lila,
            po.umur_hamil,
            po.gpa,
            po.riwayat_persalinan,
            po.keluhan AS keluhan_obgyn,
            po.diagnosa AS diagnosa_obgyn,
            po.kd_diagnosa AS kd_diagnosa_obgyn,
            po.terapi AS terapi_obgyn,
            po.tindak_lanjut AS tindak_obgyn,

            pk.jenis_kb,
            pk.tgl_pasang,
            pk.anamnesa AS keluhan_kb,
            pk.diagnosa AS diagnosa_kb,
            pk.kd_diagnosa AS kd_diagnosa_kb,
            pk.terapi AS terapi_kb,
            pk.tindak_lanjut AS tindak_kb,

            pi.riwayat_imunisasi,
            pi.jenis_imunisasi,
            pi.tgl_imunisasi,
            pi.diagnosa AS diagnosa_imunisasi,
            pi.terapi AS terapi_imunisasi,
            pi.tindak_lanjut AS tindak_imunisasi,

            pg.S,
            pg.O,
            pg.kd_diagnosa,
            pg.A,
            pg.p,
            pg.tindak_lanjut

        FROM tb_pemeriksaan a
        JOIN tb_poli c ON a.id_poli = c.id_poli
        JOIN tb_dokter d ON a.id_dokter = d.id_dokter
        JOIN tb_user e ON a.id_user = e.id_user
        JOIN tb_pasien b ON a.no_rm = b.no_rm
        JOIN tb_pendaftaran f ON a.id_pendaftaran = f.id_pendaftaran
        LEFT JOIN tb_pemeriksaan_umum pu ON a.id_pemeriksaan = pu.id_pemeriksaan
        LEFT JOIN tb_pemeriksaan_obgyn po ON a.id_pemeriksaan = po.id_pemeriksaan
        LEFT JOIN tb_pemeriksaan_kb pk ON a.id_pemeriksaan = pk.id_pemeriksaan
        LEFT JOIN tb_pemeriksaan_gigi pg ON a.id_pemeriksaan = pg.id_pemeriksaan
        LEFT JOIN tb_pemeriksaan_imunisasi pi ON a.id_pemeriksaan = pi.id_pemeriksaan
        WHERE a.id_pemeriksaan = '$id_pemeriksaan'
    ");

    $pecah = $ambil->fetch_assoc();

    $nm_pengguna = $pecah['nm_lengkap'] ?? '';
    $nm_poli = $pecah['nm_poli'] ?? '';
    $id_poli = $pecah['id_poli'] ?? '';
    $jenis_kia = $pecah['jenis_kia'] ?? '';

    if (!$id_poli || !$nm_poli) {
    echo "Poli tidak ditemukan atau sudah dihapus.";
    exit;
    }

    // Tentukan detail pemeriksaan sesuai poli
    if ($id_poli === '3') {
        $pemeriksaan_detail = [
            'S' => $pecah['S'] ?? '',
            'O' => $pecah['O'] ?? '',
            'A' => $pecah['A'] ?? '',
            'P' => $pecah['P'] ?? '',
            'kd_diagnosa' => $pecah['kd_diagnosa'] ?? '',
            'tindak_lanjut' => $pecah['tindak_lanjut'] ?? '',
        ];
    } elseif ($id_poli === '2' || ($id_poli === '4' && $jenis_kia === 'Umum')) {
        $pemeriksaan_detail = [
            'diagnosa' => $pecah['diagnosa_umum'] ?? '',
            'kd_diagnosa' => $pecah['kd_diagnosa_umum'] ?? '',
            'terapi' => $pecah['terapi_umum'] ?? '',
            'tindak_lanjut' => $pecah['tindak_umum'] ?? '',
        ];
    } elseif ($id_poli === '1' || ($id_poli === '4' && $jenis_kia === 'Obgyn')) {
        $pemeriksaan_detail = [
            'diagnosa' => $pecah['diagnosa_obgyn'] ?? '',
            'kd_diagnosa' => $pecah['kd_diagnosa_obgyn'] ?? '',
            'terapi' => $pecah['terapi_obgyn'] ?? '',
            'tindak_lanjut' => $pecah['tindak_obgyn'] ?? '',
        ];
    } elseif ($id_poli === '4' && $jenis_kia === 'KB') {
        $pemeriksaan_detail = [
            'diagnosa' => $pecah['diagnosa_kb'] ?? '',
            'kd_diagnosa' => $pecah['kd_diagnosa_kb'] ?? '',
            'terapi' => $pecah['terapi_kb'] ?? '',
            'tindak_lanjut' => $pecah['tindak_kb'] ?? '',
        ];
    } elseif ($id_poli === '4' && $jenis_kia === 'Imunisasi') {
        $pemeriksaan_detail = [
            'diagnosa' => $pecah['diagnosa_imunisasi'] ?? '',
            'kd_diagnosa' => $pecah['kd_diagnosa_imunisasi'] ?? '',
            'terapi' => $pecah['terapi_imunisasi'] ?? '',
            'tindak_lanjut' => $pecah['tindak_imunisasi'] ?? '',
        ];
    }
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
        <a class="navbar-brand text-center" href="index.php">
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
                    <h1 class="mt-4">Ubah Data Pemeriksaan</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item"><a href="../index.php" class="text-decoration-none">Dashboard</a></li>
                        <li class="breadcrumb-item active">Data Pemeriksaan</li>
                        <li class="breadcrumb-item active">Ubah Data Pemeriksaan</li>
                    </ol>
                    <div class="card mb-4">
                        <div class="card-header font-weight-bold">
                            Data Pemeriksaan : <?php echo $pecah['id_pemeriksaan']; ?>
                        </div>
                        <div class="card-body">
                            <div class="">
                                <form class="ml-4" method="post" enctype="multipart/form-data" action="">
                                    <input type="hidden" name="id_pemeriksaan" value="<?= $id_pemeriksaan ?>">
                                    <!-- ID Pemeriksaan -->
                                    <div class="mb-3">
                                        <label>ID Pemeriksaan</label>
                                        <input type="text" class="form-control" name="id_pemeriksaan" value="<?php echo $pecah['id_pemeriksaan']; ?>" readonly>
                                    </div>
                                    <!-- Card: Data Pasien -->
                                    <div class="card mb-4">
                                        <div class="card-header d-flex justify-content-between align-items-center">
                                            <span>Data Pasien</span>
                                            <a href="#" data-toggle="collapse" data-target="#formPanjangA" aria-expanded="false" aria-controls="formPanjangA">
                                                &#9660; <!-- ▼ panah bawah -->
                                            </a>
                                        </div>
                                        <div class="collapse" id="formPanjangA">
                                            <div class="card-body">
                                                <div class="form-row">
                                                    <div class="form-group col-sm-3">
                                                        <label>No. RM</label>
                                                    <input type="text" class="form-control" name="no_rm" value="<?php echo str_pad($pecah['no_rm'], 6, '0', STR_PAD_LEFT); ?>" readonly>
                                                    </div>
                                                    <div class="form-group col-sm-3">
                                                        <label>Nama Pasien</label>
                                                        <input type="text" class="form-control" name="nm_pasien" value="<?php echo $pecah['nm_pasien']; ?>" readonly>
                                                    </div>
                                                    <div class="form-group col-sm-3">
                                                        <label>Tanggal Lahir</label>
                                                        <input type="text" class="form-control" name="tgl_lahir" value="<?php echo $pecah['tgl_lahir']; ?>" readonly>
                                                    </div>
                                                </div>
                                                <div class="form-row">
                                                    <div class="form-group col-sm-3">
                                                        <label>Penanggung Jawab</label>
                                                        <input type="text" class="form-control" name="nm_pj" value="<?php echo $pecah['nm_pj']; ?>" readonly>
                                                    </div>
                                                    <div class="form-group col-sm-3">
                                                        <label>Alergi Obat</label>
                                                        <input type="text" class="form-control" name="alergi_obat" value="<?php echo $pecah['alergi_obat']; ?>" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card mb-4">
                                        <div class="card-header d-flex justify-content-between align-items-center">
                                            <span>Screening Poli <strong><?= ucfirst($nm_poli) . (!empty($jenis_kia) ? ' - ' . $jenis_kia : '') ?></strong></span>
                                            <a href="#" data-toggle="collapse" data-target="#formPanjangB" aria-expanded="false" aria-controls="formPanjangB">
                                                &#9660; <!-- ▼ panah bawah -->
                                            </a>
                                        </div>
                                        <div class="collapse" id="formPanjangB">
                                            <div class="card-body">                                            
                                                <?php if ($id_poli == '2'): ?>
                                                    <div class="form-group row">
                                                        <div class="col-sm-6">
                                                            <label>Berat Badan (kg)</label>
                                                            <input type="number" step="0.01" class="form-control" name="bb" value="<?php echo $pecah['bb']; ?>" readonly>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <label>Tinggi Badan (cm)</label>
                                                            <input type="number" step="0.01" class="form-control" name="tb" value="<?php echo $pecah['tb']; ?>" readonly>
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <div class="col-sm-6">
                                                            <label>Suhu (°C)</label>
                                                            <input type="number" step="0.1" class="form-control" name="suhu" value="<?php echo $pecah['suhu']; ?>" readonly>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <label>Tekanan Darah</label>
                                                            <input type="text" class="form-control" name="td" value="<?php echo $pecah['td']; ?>" readonly>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Keluhan</label>
                                                        <textarea name="keluhan_umum" class="form-control" rows="3" readonly><?php echo $pecah['keluhan_umum']; ?></textarea>
                                                    </div>
                                                <?php elseif ($id_poli == '1'): ?>
                                                    <div class="form-group row">
                                                        <div class="col-sm-6">
                                                            <label>HPHT (Hari Pertama Haid Terakhir)</label>
                                                            <input type="date" name="hpht" class="form-control" value="<?php echo $pecah['hpht']; ?>" readonly>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <label>HPL (Hari Perkiraan Lahir)</label>
                                                            <input type="date" name="hpl" class="form-control" value="<?php echo $pecah['hpl']; ?>" readonly>
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <div class="col-sm-4">
                                                            <label>Tekanan Darah</label>
                                                            <input type="text" class="form-control" name="td" value="<?php echo $pecah['td_obgyn']; ?>" readonly>
                                                        </div>
                                                        <div class="col-sm-4">
                                                            <label>Berat Badan (kg)</label>
                                                            <input type="number" step="0.01" class="form-control" name="bb" value="<?php echo $pecah['bb_obgyn']; ?>" readonly>
                                                        </div>
                                                        <div class="col-sm-4">
                                                            <label>Lingkar Lengan Atas (cm)</label>
                                                            <input type="number" step="0.1" class="form-control" name="lila" value="<?php echo $pecah['lila']; ?>" readonly>
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <div class="col-sm-6">
                                                            <label>Umur Kehamilan (minggu)</label>
                                                            <input type="number" name="umur_hamil" class="form-control" value="<?php echo $pecah['umur_hamil']; ?>" readonly>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <label>GPA (Gravida-Para-Abortus)</label>
                                                            <input type="text" name="gpa" class="form-control" value="<?php echo $pecah['gpa']; ?>" readonly>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Riwayat Persalinan</label>
                                                        <input type="text" name="riwayat_persalinan" class="form-control" value="<?php echo $pecah['riwayat_persalinan']; ?>" readonly>
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Keluhan</label>
                                                        <textarea name="keluhan_obgyn" class="form-control" rows="3" readonly><?php echo $pecah['keluhan_obgyn']; ?></textarea>
                                                    </div>
                                                    <?php elseif ($id_poli == '4'): ?>
                                                        <?php if ($jenis_kia == 'Umum'): ?>
                                                            <!-- FORM UNTUK KIA UMUM -->
                                                            <div class="form-group row">
                                                                <div class="col-sm-6">
                                                                    <label>Berat Badan (kg)</label>
                                                                    <input type="number" step="0.01" class="form-control" name="bb" value="<?php echo $pecah['bb']; ?>" readonly>
                                                                </div>
                                                                <div class="col-sm-6">
                                                                    <label>Tinggi Badan (cm)</label>
                                                                    <input type="number" step="0.01" class="form-control" name="tb" value="<?php echo $pecah['tb']; ?>" readonly>
                                                                </div>
                                                            </div>
                                                            <div class="form-group row">
                                                                <div class="col-sm-6">
                                                                    <label>Suhu (°C)</label>
                                                                    <input type="number" step="0.1" class="form-control" name="suhu" value="<?php echo $pecah['suhu']; ?>" readonly>
                                                                </div>
                                                                <div class="col-sm-6">
                                                                    <label>Tekanan Darah</label>
                                                                    <input type="text" class="form-control" name="td" value=" <?php echo $pecah['td']; ?>" readonly>
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <label>Keluhan</label>
                                                                <textarea name="keluhan_umum" class="form-control" rows="3" readonly><?php echo $pecah['keluhan_umum']; ?></textarea>
                                                            </div>
                                                        <?php elseif ($jenis_kia == 'Obgyn'): ?>
                                                            <!-- FORM UNTUK KIA OBGYN -->
                                                            <div class="form-group row">
                                                                <div class="col-sm-6">
                                                                    <label>HPHT (Hari Pertama Haid Terakhir)</label>
                                                                    <input type="date" name="hpht" class="form-control" value="<?php echo $pecah['hpht']; ?>" readonly>
                                                                </div>
                                                                <div class="col-sm-6">
                                                                    <label>HPL (Hari Perkiraan Lahir)</label>
                                                                    <input type="date" name="hpl" class="form-control" value="<?php echo $pecah['hpl']; ?>" readonly>
                                                                </div>
                                                            </div>
                                                            <div class="form-group row">
                                                                <div class="col-sm-4">
                                                                    <label>Tekanan Darah</label>
                                                                    <input type="text" class="form-control" name="td" value="<?php echo $pecah['td']; ?>" readonly>
                                                                </div>
                                                                <div class="col-sm-4">
                                                                    <label>Berat Badan (kg)</label>
                                                                    <input type="number" step="0.01" class="form-control" name="bb" value="<?php echo $pecah['bb']; ?>" readonly>
                                                                </div>
                                                                <div class="col-sm-4">
                                                                    <label>Lingkar Lengan Atas (cm)</label>
                                                                    <input type="number" step="0.1" class="form-control" name="lila" value="<?php echo $pecah['lila']; ?>" readonly>
                                                                </div>
                                                            </div>
                                                            <div class="form-group row">
                                                                <div class="col-sm-6">
                                                                    <label>Umur Kehamilan (minggu)</label>
                                                                    <input type="number" name="umur_hamil" class="form-control" value=" <?php echo $pecah['umur_hamil']; ?>" readonly>
                                                                </div>
                                                                <div class="col-sm-6">
                                                                    <label>GPA (Gravida-Para-Abortus)</label>
                                                                    <input type="text" name="gpa" class="form-control" value=" <?php echo $pecah['gpa']; ?>" readonly>
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <label>Riwayat Persalinan</label>
                                                                <input type="text" name="riwayat_persalinan" class="form-control" value="<?php echo $pecah['riwayat_persalinan']; ?>" readonly>
                                                            </div>
                                                            <div class="form-group">
                                                                <label>Keluhan</label>
                                                                <textarea name="keluhan_umum" class="form-control" rows="3" readonly><?php echo $pecah['keluhan_umum']; ?></textarea>
                                                            </div>
                                                        <?php elseif ($jenis_kia == 'KB'): ?>
                                                            <!-- FORM UNTUK KIA KB -->
                                                            <div class="form-group row">
                                                                <div class="col-sm-6">
                                                                    <label>Jenis KB</label>
                                                                    <input type="text" class="form-control" name="jenis_kb" value="<?php echo $pecah['jenis_kb'] ?>" readonly>
                                                                </div>
                                                                <div class="col-sm-6">
                                                                    <label>Tanggal Pertama Pemasangan</label>
                                                                    <input type="date" name="tgl_pasang" class="form-control" value="<?php echo $pecah['tgl_pasang'] ?>" readonly>
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <label>Anamnesa</label>
                                                                <textarea name="anamnesa" class="form-control" rows="3" readonly><?php echo $pecah['keluhan_kb']; ?></textarea>
                                                            </div>
                                                        <?php elseif ($jenis_kia == 'Imunisasi'): ?>
                                                            <!-- FORM UNTUK KIA IMUNISASI -->
                                                            <div class="form-group row">
                                                                <div class="col-sm-6">
                                                                    <label>Riwayat Imunisasi</label>
                                                                    <input type="text" name="riwayat_imunisasi" class="form-control" value="<?php echo $pecah['riwayat_imunisasi']; ?>" readonly>
                                                                </div>
                                                            </div> 
                                                            <div class="form-row">
                                                                <div class="form-group col-md-4">
                                                                    <label>Jenis Imunisasi</label>
                                                                    <input type="text" class="form-control" readonly value="<?= $pemeriksaan_detail['jenis_imunisasi'] ?? '' ?>">
                                                                </div>
                                                                <div class="form-group col-md-4">
                                                                    <label>Tanggal Imunisasi</label>
                                                                    <input type="text" class="form-control" readonly value="<?= date('d-m-Y', strtotime($pemeriksaan_detail['tgl_imunisasi'] ?? '')) ?>">
                                                                </div>
                                                            </div>    
                                                        <?php endif; ?>    
                                                    <?php else: ?>
                                                    <div class="alert alert-warning">
                                                        <strong>Poli "<?= ucfirst($nm_poli) ?>" belum didukung.</strong><br>
                                                        Silakan hubungi administrator untuk menambahkan form khusus poli ini.
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card mb-4">
                                        <div class="card-header">Pemeriksaan Dokter</div>
                                        <div class="card-body">
                                            <?php if ($id_poli === '3'): ?>
                                                <!-- Poli Gigi -->
                                                <div class="form-row">
                                                    <div class="form-group col-md-6">
                                                        <label>Subjective</label>
                                                        <textarea name="S" class="form-control" rows="5" required><?= $pemeriksaan_detail['S'] ?? '' ?></textarea>
                                                    </div>
                                                    <div class="form-group col-md-6">
                                                        <label>Objective</label>
                                                        <textarea name="O" class="form-control" rows="5" required><?= $pemeriksaan_detail['O'] ?? '' ?></textarea>
                                                    </div>
                                                </div>
                                                <div class="form-row">
                                                    <div class="form-group col-md-6">
                                                        <label>Assessment</label>
                                                        <textarea name="A" class="form-control" rows="5" required><?= $pemeriksaan_detail['A'] ?? '' ?></textarea>
                                                    </div>
                                                    <div class="form-group col-md-6">
                                                        <label>Plan</label>
                                                        <textarea name="P" class="form-control" rows="5" required><?= $pemeriksaan_detail['P'] ?? '' ?></textarea>
                                                    </div>
                                                </div>
                                                <div class="form-row">
                                                    <div class="form-group col-md-2">
                                                        <label>Kode ICD-10</label>
                                                        <input name="kd_diagnosa" type="text" class="form-control" required value="<?= $pemeriksaan_detail['kd_diagnosa'] ?? '' ?>">
                                                    </div>
                                                    <div class="form-group col-md-6">
                                                        <label>Tindak Lanjut</label>
                                                        <select class="custom-select" name="tindak_lanjut" required>
                                                            <option value="" disabled selected>--Pilih--</option>
                                                            <option value="Selesai" <?php if (($pemeriksaan_detail['tindak_lanjut'] ?? '') == 'Selesai') echo 'selected'; ?>>Selesai</option>
                                                            <option value="Kontrol" <?php if (($pemeriksaan_detail['tindak_lanjut'] ?? '') == 'Kontrol') echo 'selected'; ?>>Kontrol</option>
                                                            <option value="Rujuk" <?php if (($pemeriksaan_detail['tindak_lanjut'] ?? '') == 'Rujuk') echo 'selected'; ?>>Rujuk</option>
                                                            <option value="Rawat Inap" <?php if (($pemeriksaan_detail['tindak_lanjut'] ?? '') == 'Rawat Inap') echo 'selected'; ?>>Rawat Inap</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <?php elseif (in_array($id_poli, ['1', '2', '4']) && $jenis_kia !== 'Imunisasi'): ?>
                                                <!-- Poli Umum / Obgyn / KIA Umum / KIA Obgyn / KIA KB -->
                                                <div class="form-row">
                                                    <div class="form-group col-md-5">
                                                        <label>Diagnosa</label>
                                                        <textarea name="diagnosa" class="form-control" rows="3" required><?= $pemeriksaan_detail['diagnosa'] ?? '' ?></textarea>
                                                    </div>
                                                    <div class="form-group col-md-1">
                                                        <label>Kode ICD-10</label>
                                                        <input name="kd_diagnosa" type="text" class="form-control" required value="<?= $pemeriksaan_detail['kd_diagnosa'] ?? '' ?>">
                                                    </div>
                                                    <div class="form-group col-md-5">
                                                        <label>Terapi</label>
                                                        <textarea name="terapi" class="form-control" rows="3" required><?= $pemeriksaan_detail['terapi'] ?? '' ?></textarea>
                                                    </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Tindak Lanjut</label>
                                                            <select class="custom-select" name="tindak_lanjut" required>
                                                                <option value="" disabled selected>--Pilih--</option>
                                                                <option value="Selesai" <?php if (($pemeriksaan_detail['tindak_lanjut'] ?? '') == 'Selesai') echo 'selected'; ?>>Selesai</option>
                                                                <option value="Kontrol" <?php if (($pemeriksaan_detail['tindak_lanjut'] ?? '') == 'Kontrol') echo 'selected'; ?>>Kontrol</option>
                                                                <option value="Rujuk" <?php if (($pemeriksaan_detail['tindak_lanjut'] ?? '') == 'Rujuk') echo 'selected'; ?>>Rujuk</option>
                                                                <option value="Rawat Inap" <?php if (($pemeriksaan_detail['tindak_lanjut'] ?? '') == 'Rawat Inap') echo 'selected'; ?>>Rawat Inap</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php elseif ($id_poli === '4' && $jenis_kia === 'Imunisasi'): ?>
                                                <!-- Poli KIA Imunisasi -->
                                                <div class="form-row">
                                                    <div class="form-group col-md-5">
                                                        <label>Diagnosa</label>
                                                        <textarea name="diagnosa" class="form-control" rows="3" required><?= $pemeriksaan_detail['diagnosa'] ?? '' ?></textarea>
                                                    </div>
                                                    <div class="form-group col-md-1">
                                                        <label>Kode ICD-10</label>
                                                        <input name="kd_diagnosa" type="text" class="form-control" required value="<?= $pemeriksaan_detail['kd_diagnosa'] ?? '' ?>">
                                                    </div>
                                                    <div class="form-group col-md-5">
                                                        <label>Terapi</label>
                                                        <textarea name="terapi" class="form-control" rows="3" required><?= $pemeriksaan_detail['terapi'] ?? '' ?></textarea>
                                                    </div>
                                                        <div class="form-group col-md-6">
                                                            <label>Tindak Lanjut</label>
                                                            <select class="custom-select" name="tindak_lanjut" required>
                                                                <option value="" disabled selected>--Pilih--</option>
                                                                <option value="Selesai" <?php if (($pemeriksaan_detail['tindak_lanjut'] ?? '') == 'Selesai') echo 'selected'; ?>>Selesai</option>
                                                                <option value="Kontrol" <?php if (($pemeriksaan_detail['tindak_lanjut'] ?? '') == 'Kontrol') echo 'selected'; ?>>Kontrol</option>
                                                                <option value="Rujuk" <?php if (($pemeriksaan_detail['tindak_lanjut'] ?? '') == 'Rujuk') echo 'selected'; ?>>Rujuk</option>
                                                                <option value="Rawat Inap" <?php if (($pemeriksaan_detail['tindak_lanjut'] ?? '') == 'Rawat Inap') echo 'selected'; ?>>Rawat Inap</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php else: ?>
                                            <!-- Poli Tidak Didukung -->
                                            <div class="alert alert-warning">
                                                Data hasil pemeriksaan untuk poli ini belum tersedia atau tidak didukung.
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    <input type="hidden" name="id_poli" value="<?= $id_poli ?>">
                                    <input type="hidden" name="jenis_kia" value="<?= $jenis_kia ?>">
                                    <input type="hidden" name="id_pendaftaran" id="id_pendaftaran">
                                    <div class="form-group">
                                        <button class="btn btn-success font-weight-bold px-3 mr-2" name="simpan">
                                            <i class="fas fa-save"></i> Simpan
                                        </button>
                                        <a href="pemeriksaan.php" class="btn btn-danger font-weight-bold px-3 mr-2">
                                            <i class="fas fa-arrow-circle-left"></i> Kembali
                                        </a>
                                    </div>
                                </form>
                                
                                <?php
                                if (isset($_POST['simpan'])) {
                                    $id_pemeriksaan = $_POST['id_pemeriksaan'] ?? $id_pemeriksaan;
                                    $id_poli = $_POST['id_poli'] ?? '';
                                    $jenis_kia = $_POST['jenis_kia'] ?? '';
                                    $diagnosa = $_POST['diagnosa'] ?? '';
                                    $kd_diagnosa = $_POST['kd_diagnosa'] ?? '';
                                    $terapi = $_POST['terapi'] ?? '';
                                    $tindak_lanjut = $_POST['tindak_lanjut'] ?? '';

                                    mysqli_begin_transaction($koneksi);
                                    
                                    if (empty($id_pemeriksaan)) {
                                        echo "<script>alert('ID Pemeriksaan tidak ditemukan!');</script>";
                                        exit();
                                    }

                                    // Ambil id_pendaftaran
                                    $getPendaftaran = mysqli_query($koneksi, "SELECT id_pendaftaran FROM tb_pemeriksaan WHERE id_pemeriksaan = '$id_pemeriksaan'");
                                    $data = mysqli_fetch_assoc($getPendaftaran);
                                    $id_pendaftaran = $data['id_pendaftaran'] ?? '';

                                    
                                    // Update pemeriksaan berdasarkan poli
                                    if ($id_poli == '1') {
                                        // Poli Obgyn
                                        $update = $koneksi->query("UPDATE tb_pemeriksaan_obgyn SET 
                                            diagnosa='$diagnosa',
                                            kd_diagnosa='$kd_diagnosa',
                                            terapi='$terapi',
                                            tindak_lanjut='$tindak_lanjut'
                                            WHERE id_pemeriksaan='$id_pemeriksaan'");
                                    } elseif ($id_poli == '2') {
                                        // Poli Umum
                                        $update = $koneksi->query("UPDATE tb_pemeriksaan_umum SET 
                                            diagnosa='$diagnosa',
                                            kd_diagnosa='$kd_diagnosa',
                                            terapi='$terapi',
                                            tindak_lanjut='$tindak_lanjut'
                                            WHERE id_pemeriksaan='$id_pemeriksaan'");
                                    } elseif ($id_poli == '3') {
                                        // Poli Gigi
                                        $subjective = $_POST['S'] ?? '';
                                        $objective = $_POST['O'] ?? '';
                                        $assessment = $_POST['A'] ?? '';
                                        $plan = $_POST['P'] ?? '';
                                        $update = $koneksi->query("UPDATE tb_pemeriksaan_gigi SET 
                                            S='$subjective',
                                            O='$objective',
                                            A='$assessment',
                                            P='$plan',
                                            kd_diagnosa='$kd_diagnosa',
                                            tindak_lanjut='$tindak_lanjut'
                                            WHERE id_pemeriksaan='$id_pemeriksaan'");
                                    } elseif ($id_poli == '4') {
                                        // Poli KIA
                                        if ($jenis_kia == 'Umum') {
                                            $update = $koneksi->query("UPDATE tb_pemeriksaan_umum SET 
                                                diagnosa='$diagnosa',
                                                kd_diagnosa='$kd_diagnosa',
                                                terapi='$terapi',
                                                tindak_lanjut='$tindak_lanjut'
                                                WHERE id_pemeriksaan='$id_pemeriksaan'");
                                        } elseif ($jenis_kia == 'Obgyn') {
                                            $update = $koneksi->query("UPDATE tb_pemeriksaan_obgyn SET 
                                                diagnosa='$diagnosa',
                                                kd_diagnosa='$kd_diagnosa',
                                                terapi='$terapi',
                                                tindak_lanjut='$tindak_lanjut'
                                                WHERE id_pemeriksaan='$id_pemeriksaan'");
                                        } elseif ($jenis_kia == 'KB') {
                                            $update = $koneksi->query("UPDATE tb_pemeriksaan_kb SET 
                                                diagnosa='$diagnosa',
                                                kd_diagnosa='$kd_diagnosa',
                                                terapi='$terapi',
                                                tindak_lanjut='$tindak_lanjut'
                                                WHERE id_pemeriksaan='$id_pemeriksaan'");
                                        } elseif ($jenis_kia == 'Imunisasi') {
                                            
                                            $update = $koneksi->query("UPDATE tb_pemeriksaan_imunisasi SET 
                                                diagnosa='$diagnosa',
                                                kd_diagnosa='$kd_diagnosa',
                                                terapi='$terapi',
                                                tindak_lanjut='$tindak_lanjut'
                                                WHERE id_pemeriksaan='$id_pemeriksaan'");
                                        }
                                    }

                                    if ($update) {
                                        $update_status = mysqli_query($koneksi, "UPDATE tb_pemeriksaan SET status = '2' WHERE id_pemeriksaan = '$id_pemeriksaan'");
                                        
                                        if ($update_status) {
                                            mysqli_commit($koneksi);
                                            echo "<script>alert('Data Pemeriksaan berhasil diperbarui!'); window.location='pemeriksaan_ubah.php?id_pemeriksaan=$id_pemeriksaan';</script>";
                                        } else {
                                            mysqli_rollback($koneksi);
                                            echo "<script>alert('Gagal mengubah status!');</script>";
                                        }
                                    } else {
                                        mysqli_rollback($koneksi);
                                        echo "<script>alert('Gagal memperbarui data!');</script>";
                                    }
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                    <?php 
                    include '../data-resep/resep_input.php'; 
                    ?>
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