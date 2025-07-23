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

$id_user = $_SESSION['id_user'];

$query_user = $koneksi->query("SELECT * FROM tb_user WHERE id_user = '$id_user'");
$data_user = $query_user->fetch_assoc();

$nm_pengguna = $data_user['nm_lengkap'];

// Generate kode otomatis di sini
$tanggal = date("Ymd");
$query = mysqli_query($koneksi, "SELECT id_pemeriksaan FROM tb_pemeriksaan 
    WHERE LEFT(id_pemeriksaan, 10) = 'PR$tanggal' 
    ORDER BY id_pemeriksaan DESC LIMIT 1");

$data_kode = mysqli_fetch_assoc($query);
$no_akhir = (isset($data_kode['id_pemeriksaan'])) ? (int)substr($data_kode['id_pemeriksaan'], -3) : 0;
$no_baru = $no_akhir + 1;
$kode_baru = "PR" . $tanggal . "-" . str_pad($no_baru, 3, "0", STR_PAD_LEFT);

// Inisialisasi
$nm_poli = '';
$nm_dokter = '';
$jenis_kia = '';
$riwayat_lama = '';
$obgyn_lama = [];
$show_form = false;

// Cek jika ada data yang dipilih dari tabel
if (isset($_POST['pilih_pasien'])) {
    $id_pendaftaran = $_POST['id_pendaftaran'];
    $no_rm = $_POST['no_rm'];
    $nm_pj = $_POST['nm_pj'];
    $id_poli = $_POST['id_poli'];
    $id_dokter = $_POST['id_dokter'];
    $jenis_kia = $_POST['jenis_kia'] ?? '';

    // Gabungkan semua pengambilan data dalam 1 query
    $query = mysqli_query($koneksi, "
        SELECT 
            ps.riwayat_penyakit,
            po.hpht, po.hpl, po.umur_hamil, po.gpa, po.riwayat_persalinan,
            pl.nm_poli,
            dk.nm_dokter,
            dp.jenis_kia,
            pb. jenis_kb,
            pb. tgl_pasang,
            pi. riwayat_imunisasi

        FROM tb_pasien ps
        LEFT JOIN tb_pemeriksaan p ON p.no_rm = ps.no_rm
        LEFT JOIN tb_pemeriksaan_obgyn po ON p.id_pemeriksaan = po.id_pemeriksaan
        LEFT JOIN tb_pemeriksaan_kb pb ON p.id_pemeriksaan = pb.id_pemeriksaan
        LEFT JOIN tb_pemeriksaan_imunisasi pi ON p.id_pemeriksaan = pi.id_pemeriksaan
        LEFT JOIN tb_poli pl ON pl.id_poli = '$id_poli'
        LEFT JOIN tb_dokter dk ON dk.id_dokter = '$id_dokter'
        LEFT JOIN tb_pendaftaran dp ON dp.id_pendaftaran = '$id_pendaftaran'
        WHERE ps.no_rm = '$no_rm'
        ORDER BY p.tgl_pemeriksaan DESC
        LIMIT 1
    ");

    $pecah = mysqli_fetch_assoc($query);

    // Tetapkan variabel yang dibutuhkan dari $pecah
    $riwayat_lama  = $pecah['riwayat_penyakit'] ?? '';
    $nm_poli       = $pecah['nm_poli'] ?? '';
    $nm_dokter     = $pecah['nm_dokter'] ?? '';
    $jenis_kia     = $jenis_kia ?: ($pecah['jenis_kia'] ?? '');

    $obgyn_lama = [
        'hpht'               => $pecah['hpht'] ?? '',
        'hpl'                => $pecah['hpl'] ?? '',
        'umur_hamil'         => $pecah['umur_hamil'] ?? '',
        'gpa'                => $pecah['gpa'] ?? '',
        'riwayat_persalinan' => $pecah['riwayat_persalinan'] ?? ''
    ];

    $show_form = true;
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <link rel="shortcut icon" type="image/png" href="../assets/img/sehat-1.png" />
    <title>Suko Asih | Pemeriksaan Petugas</title>
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
                        <a class="nav-link" href="../data-pendaftaran/pendaftaran.php">
                            <div class="sb-nav-link-icon"><i class="fas fa-clipboard-list"></i></div>
                            Data Pendaftaran
                        </a>
                        <a class="nav-link active" href="../data-pemeriksaan/screening.php">
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
                        <a class="nav-link" href="../data-pendaftaran/pendaftaran.php">
                            <div class="sb-nav-link-icon"><i class="fas fa-clipboard-list"></i></div>
                            Data Pendaftaran
                        </a>
                        <a class="nav-link active" href="../data-pemeriksaan/pemeriksaan.php">
                            <div class="sb-nav-link-icon"><i class="fas fa-clipboard-list"></i></div>
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
                    <h1 class="mt-4">Data Screening</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item"><a href="../index.php" class="text-decoration-none">Dashboard</a></li>
                        <li class="breadcrumb-item active">Pemeriksaan Petugas</li>
                    </ol>
                    <div class="card-body">
                        <div class=""> 
                            <!-- FORM PENCARIAN -->
                            <form method="post">
                                <div class="form-group row">
                                    <div class="col-sm-4">
                                        <input type="text" name="keyword" class="form-control" placeholder="Cari pasien..." value="<?= $_POST['keyword'] ?? '' ?>">
                                    </div>
                                    <div class="col-sm-2">
                                        <button type="submit" name="cari" class="btn btn-primary">Cari</button>
                                    </div>
                                </div>
                            </form>
                            
                            <!-- TABEL PASIEN SELALU TAMPIL -->
                            <div class="card mb-4">
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered" id="tabelPasien">
                                            <thead>
                                                <tr>
                                                    <th>ID Pendaftaran</th>
                                                    <th>No RM</th>
                                                    <th>Nama Pasien</th>
                                                    <th>Penanggung Jawab</th>
                                                    <th>Poli</th>
                                                    <th>Dokter</th>
                                                    <th>Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                    if (isset($_POST['cari'])) {
                                                        $keyword = mysqli_real_escape_string($koneksi, $_POST['keyword']);
                                                        $query = mysqli_query($koneksi, "
                                                            SELECT p.no_rm, ps.nm_pasien, ps.nm_pj, d.id_dokter, d.nm_dokter, l.nm_poli, p.id_pendaftaran, l.id_poli, p.jenis_kia 
                                                            FROM tb_pendaftaran p
                                                            JOIN tb_pasien ps ON p.no_rm = ps.no_rm
                                                            JOIN tb_dokter d ON p.id_dokter = d.id_dokter
                                                            JOIN tb_poli l ON p.id_poli = l.id_poli
                                                            WHERE ps.nm_pasien LIKE '%$keyword%' 
                                                                OR LPAD(p.no_rm, 6, '0') LIKE '%$keyword%' 
                                                                OR p.id_pendaftaran LIKE '%$keyword%'
                                                            ORDER BY p.tgl_pendaftaran DESC
                                                        ");
                                                    if (mysqli_num_rows($query) > 0) {
                                                        while ($row = mysqli_fetch_assoc($query)) {
                                                            echo "<tr>
                                                                <td>{$row['id_pendaftaran']}</td>
                                                                <td>" . str_pad($row['no_rm'], 6, '0', STR_PAD_LEFT) . "</td>
                                                                <td>{$row['nm_pasien']}</td>
                                                                <td>{$row['nm_pj']}</td>
                                                                <td>";
                                                                if ($row['id_poli'] === '4') {
                                                                    echo "{$row['nm_poli']} - " .($row['jenis_kia']);
                                                                } else {
                                                                    echo $row['nm_poli'];
                                                                }
                                                                echo "</td>
                                                                <td>{$row['nm_dokter']}</td>
                                                                <td>
                                                                    <form method='post' style='display:inline;'>
                                                                        <input type='hidden' name='id_pendaftaran' value='{$row['id_pendaftaran']}'>
                                                                        <input type='hidden' name='no_rm' value='" . str_pad($row['no_rm'], 6, '0', STR_PAD_LEFT) . "'>
                                                                        <input type='hidden' name='nm_pj' value='{$row['nm_pj']}'>
                                                                        <input type='hidden' name='id_dokter' value='{$row['id_dokter']}'>
                                                                        <input type='hidden' name='id_poli' value='{$row['id_poli']}'>            
                                                                        <input type='hidden' name='jenis_kia' value='{$row['jenis_kia']}'>                                                            
                                                                        <button type='submit' name='pilih_pasien' class='btn btn-success btn-sm'>Pilih</button>
                                                                    </form>
                                                                </td>
                                                            </tr>";
                                                        }
                                                    } else {
                                                        echo "<tr><td colspan='7' class='text-center text-danger'>❌ Tidak ditemukan data pasien.</td></tr>";
                                                    }
                                                } else {
                                                    echo "<tr><td colspan='7' class='text-center text-muted'>🔍 Masukkan kata kunci dan tekan tombol cari.</td></tr>";
                                                }
                                                ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <!-- FORM UTAMA (UNTUK PENYIMPANAN DATA SCREENING) -->
                            <?php if ($show_form): ?>
                            <div class="alert alert-info">
                                <strong>Pasien Dipilih:</strong> No RM: <?= $no_rm ?> | Poli: <?= $nm_poli . (!empty($pecah['jenis_kia']) ? ' - ' . $pecah['jenis_kia'] : '') ?> | Dokter: <?= $nm_dokter ?>
                            </div>
                            <form method="post" enctype="multipart/form-data">
                                <div class="form-group row">
                                    <div class="col-sm-4">
                                        <label>ID Pemeriksaan</label>
                                        <input type="text" class="form-control" name="id_pemeriksaan" value="<?= $kode_baru; ?>" readonly>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-4">
                                        <label>No. RM</label>
                                        <input type="text" id="no_rm" name="no_rm" class="form-control" value="<?= $no_rm ?>" readonly>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-4">
                                        <label>Tanggal Pemeriksaan</label>
                                        <input type="date" class="form-control" name="tgl_pemeriksaan" value="<?= date("Y-m-d"); ?>" required>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-4">
                                        <label>Petugas</label>
                                        <input type="text" class="form-control" name="nm_lengkap" value="<?= $nm_pengguna; ?>" readonly>
                                        <input type="hidden" name="id_user" value="<?= $id_user; ?>">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-4">
                                        <label>Riwayat Penyakit Pasien</label>
                                        <textarea name="riwayat_penyakit" class="form-control" rows="3" required><?= $riwayat_lama ?></textarea>
                                    </div>
                                </div>
                                
                                <div class="card mb-4">
                                    <div class="card-header">Screening</div>
                                    <div class="card-body">
                                        <h5>Form Pemeriksaan Poli <strong>
                                            <?= ucfirst($nm_poli) . (!empty($pecah['jenis_kia']) ? ' - ' . $pecah['jenis_kia'] : '') ?>
                                        </strong></h5>
                                        
                                        <?php if ($id_poli == '2'): ?>
                                            <div class="form-group row">
                                                <div class="col-sm-6">
                                                    <label>Berat Badan (kg)</label>
                                                    <input type="number" step="0.01" class="form-control" name="bb" placeholder="Contoh: 65.50" required>
                                                </div>
                                                <div class="col-sm-6">
                                                    <label>Tinggi Badan (cm)</label>
                                                    <input type="number" step="0.01" class="form-control" name="tb" placeholder="Contoh: 170.00" required>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <div class="col-sm-6">
                                                    <label>Suhu (°C)</label>
                                                    <input type="number" step="0.1" class="form-control" name="suhu" placeholder="Contoh: 36.5" required>
                                                </div>
                                                <div class="col-sm-6">
                                                    <label>Tekanan Darah</label>
                                                    <input type="text" class="form-control" name="td" placeholder="Contoh: 120/80" required>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label>Keluhan</label>
                                                <textarea name="keluhan" class="form-control" rows="3" required></textarea>
                                            </div>
                                        <?php elseif ($id_poli == '1'): ?>
                                            <div class="form-group row">
                                                <div class="col-sm-6">
                                                    <label>HPHT (Hari Pertama Haid Terakhir)</label>
                                                    <input type="date" name="hpht" class="form-control" value="<?= $obgyn_lama['hpht'] ?? '' ?>" required>
                                                </div>
                                                <div class="col-sm-6">
                                                    <label>HPL (Hari Perkiraan Lahir)</label>
                                                    <input type="date" name="hpl" class="form-control" value="<?= $obgyn_lama['hpl'] ?? '' ?>" required>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <div class="col-sm-4">
                                                    <label>Tekanan Darah</label>
                                                    <input type="text" class="form-control" name="td" placeholder="Contoh: 120/80" required>
                                                </div>
                                                <div class="col-sm-4">
                                                    <label>Berat Badan (kg)</label>
                                                    <input type="number" step="0.01" class="form-control" name="bb" placeholder="Contoh: 65.50" required>
                                                </div>
                                                <div class="col-sm-4">
                                                    <label>Lingkar Lengan Atas (cm)</label>
                                                    <input type="number" step="0.1" class="form-control" name="lila" placeholder="Contoh: 23.5" required>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <div class="col-sm-6">
                                                    <label>Umur Kehamilan (minggu)</label>
                                                    <input type="number" name="umur_hamil" class="form-control" value="<?= $obgyn_lama['umur_hamil'] ?? '' ?>" required>
                                                </div>
                                                <div class="col-sm-6">
                                                    <label>GPA (Gravida-Para-Abortus)</label>
                                                    <input type="text" name="gpa" class="form-control" placeholder="Contoh: G1P0A0" value="<?= $obgyn_lama['gpa'] ?? '' ?>" required>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label>Riwayat Persalinan</label>
                                                <input type="text" id="riwayat_persalinan" name="riwayat_persalinan" class="form-control" value="<?= $obgyn_lama['riwayat_persalinan'] ?? '' ?>" required>
                                            </div>
                                            <div class="form-group">
                                                <label>Keluhan</label>
                                                <textarea name="keluhan" class="form-control" rows="3" required></textarea>
                                            </div>
                                        
                                            <?php elseif ($id_poli == '4'): ?>
                                                <?php if ($jenis_kia == 'Umum'): ?>
                                                    <!-- FORM UNTUK KIA UMUM -->
                                                    <div class="form-group row">
                                                        <div class="col-sm-6">
                                                            <label>Berat Badan (kg)</label>
                                                            <input type="number" step="0.01" class="form-control" name="bb" placeholder="Contoh: 65.50" required>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <label>Tinggi Badan (cm)</label>
                                                            <input type="number" step="0.01" class="form-control" name="tb" placeholder="Contoh: 170.00" required>
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <div class="col-sm-6">
                                                            <label>Suhu (°C)</label>
                                                            <input type="number" step="0.1" class="form-control" name="suhu" placeholder="Contoh: 36.5" required>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <label>Tekanan Darah</label>
                                                            <input type="text" class="form-control" name="td" placeholder="Contoh: 120/80" required>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Keluhan</label>
                                                        <textarea name="keluhan" class="form-control" rows="3" required></textarea>
                                                    </div>
                                                <?php elseif ($jenis_kia == 'Obgyn'): ?>
                                                    <!-- FORM UNTUK KIA OBGYN -->
                                                    <div class="form-group row">
                                                        <div class="col-sm-6">
                                                            <label>HPHT (Hari Pertama Haid Terakhir)</label>
                                                            <input type="date" name="hpht" class="form-control" value="<?= $obgyn_lama['hpht'] ?? '' ?>"  required>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <label>HPL (Hari Perkiraan Lahir)</label>
                                                            <input type="date" name="hpl" class="form-control" value="<?= $obgyn_lama['hpl'] ?? '' ?>" required>
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <div class="col-sm-4">
                                                            <label>Tekanan Darah</label> <!-- PERBAIKAN: pastikan name="tensi" -->
                                                            <input type="text" class="form-control" name="td" placeholder="Contoh: 120/80" required>
                                                        </div>
                                                        <div class="col-sm-4">
                                                            <label>Berat Badan (kg)</label>
                                                            <input type="number" step="0.01" class="form-control" name="bb" placeholder="Contoh: 65.50" required>
                                                        </div>
                                                        <div class="col-sm-4">
                                                            <label>Lingkar Lengan Atas (cm)</label>
                                                            <input type="number" step="0.1" class="form-control" name="lila" placeholder="Contoh: 23.5" required>
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <div class="col-sm-6">
                                                            <label>Umur Kehamilan (minggu)</label>
                                                            <input type="number" name="umur_hamil" class="form-control" value="<?= $obgyn_lama['umur_hamil'] ?? '' ?>" required>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <label>GPA (Gravida-Para-Abortus)</label>
                                                            <input type="text" name="gpa" class="form-control" placeholder="Contoh: G1P0A0" value="<?= $obgyn_lama['gpa'] ?? '' ?>" required>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Riwayat Persalinan</label>
                                                        <input type="text" id="riwayat_persalinan" name="riwayat_persalinan" class="form-control" required>
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Keluhan</label>
                                                        <textarea name="keluhan" class="form-control" rows="3" value="<?= $obgyn_lama['riwayat_persalinan'] ?? '' ?>" required></textarea>
                                                    </div>
                                                <?php elseif ($jenis_kia == 'KB'): ?>
                                                    <!-- FORM UNTUK KIA KB -->
                                                    <div class="form-group row">
                                                        <div class="col-sm-6">
                                                            <label>Jenis KB</label>
                                                            <select name="jenis_kb" class="form-control" required>
                                                                <option value="">Pilih Jenis KB</option>
                                                                <option value="Pil" <?= ($pecah['jenis_kb'] ?? '') == 'Pil' ? 'selected' : '' ?>>Pil</option>
                                                                <option value="Suntik" <?= ($pecah['jenis_kb'] ?? '') == 'Suntik' ? 'selected' : '' ?>>Suntik</option>
                                                                <option value="IUD" <?= ($pecah['jenis_kb'] ?? '') == 'IUD' ? 'selected' : '' ?>>IUD</option>
                                                                <option value="Implant" <?= ($pecah['jenis_kb'] ?? '') == 'Implant' ? 'selected' : '' ?>>Implant</option>
                                                                <option value="Kondom" <?= ($pecah['jenis_kb'] ?? '') == 'Kondom' ? 'selected' : '' ?>>Kondom</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <label>Tanggal Pertama Pemasangan</label>
                                                            <input type="date" name="tgl_pasang" class="form-control" required value="<?= $pecah['tgl_pasang'] ?? '' ?>">
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Anamnesa</label>
                                                        <textarea name="anamnesa" class="form-control" rows="3" required></textarea>
                                                    </div>
                                                <?php elseif ($jenis_kia == 'Imunisasi'): ?>
                                                    <!-- FORM UNTUK KIA IMUNISASI -->
                                                    <div class="form-group row">
                                                        <div class="col-sm-6">
                                                            <label>Riwayat Imunisasi</label>
                                                            <input type="text" name="riwayat_imunisasi" class="form-control" required value="<?= $pecah['riwayat_imunisasi'] ?? '' ?>">
                                                        </div>
                                                    </div>   
                                                    <div class="form-row">
                                                        <div class="form-group col-md-4">
                                                            <label>Jenis Imunisasi</label>
                                                            <select name="jenis_imunisasi" class="custom-select" required>
                                                                <option value="" disabled selected>--Pilih--</option>
                                                                <?php
                                                                $opsi_imunisasi = ['HB-0','BCG','DPT-HB-Hib','OPV','PCV','RV','IPV','MR','JE*','DT','Td','HPV'];
                                                                foreach ($opsi_imunisasi as $opsi) {
                                                                    echo "<option value=\"$opsi\">$opsi</option>";
                                                                }
                                                                ?>
                                                                <option value="Lainnya">Lainnya</option>
                                                            </select>
                                                        </div>
                                                        <div class="form-group col-md-4">
                                                            <label>Tanggal Imunisasi</label>
                                                            <input name="tgl_imunisasi" type="date" class="form-control" required value="<?= date('Y-m-d', strtotime($tanggal)) ?>">
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

                                <!-- Hidden fields untuk data yang dipilih -->
                                <input type="hidden" name="id_pendaftaran" value="<?= $id_pendaftaran ?>">
                                <input type="hidden" name="id_dokter" value="<?= $id_dokter ?>">
                                <input type="hidden" name="id_poli" value="<?= $id_poli ?>">
                                <input type="hidden" name="jenis_kia" value="<?= $jenis_kia ?>">

                                <div class="form-group row">
                                    <div class="col-sm-4">
                                        <button type="submit" name="simpan" class="btn btn-success">Simpan Data Screening</button>
                                        <a href="<?= $_SERVER['PHP_SELF'] ?>" class="btn btn-secondary ml-2">Reset</a>
                                    </div>
                                </div>
                            </form>
                            <?php endif; ?>

                            <?php
                                if (isset($_POST['simpan'])) {
                                    // Ambil data dari form
                                    $id_pemeriksaan   = $_POST['id_pemeriksaan'];
                                    $id_pendaftaran = trim($_POST['id_pendaftaran']);
                                    $id_user          = $_POST['id_user'];
                                    $no_rm            = $_POST['no_rm'];
                                    $id_poli          = $_POST['id_poli'];
                                    $id_dokter        = $_POST['id_dokter'];
                                    $tgl_pemeriksaan  = $_POST['tgl_pemeriksaan'];
                                    $jenis_kia = $_POST['jenis_kia'];
                                    $riwayat_penyakit = trim($_POST['riwayat_penyakit'] ?? '');

                                    // 1. Cek apakah ID pendaftaran sudah dipakai
                                    $cek_id = mysqli_query($koneksi, "
                                        SELECT * FROM tb_pemeriksaan 
                                        WHERE id_pendaftaran = '$id_pendaftaran'
                                    ");
                                    if (mysqli_num_rows($cek_id) > 0) {
                                        $row = mysqli_fetch_assoc($cek_id);
                                        echo "<div class='alert alert-danger'>
                                            ID Pendaftaran <strong>{$row['id_pendaftaran']}</strong> sudah tidak dapat digunakan.
                                        </div>";
                                    } else {
                                        // 2. Cek apakah pasien sudah diperiksa hari ini di poli yang sama
                                        $cek_duplikat = mysqli_query($koneksi, "
                                            SELECT * FROM tb_pemeriksaan 
                                            WHERE no_rm = '$no_rm' 
                                            AND tgl_pemeriksaan = '$tgl_pemeriksaan'
                                            AND id_poli = '$id_poli'
                                        ");
                                    
                                    if (mysqli_num_rows($cek_duplikat) > 0) {
                                        echo "<div class='alert alert-danger'>Pasien sudah diperiksa hari ini di poli ini.</div>";
                                    } else {
                                        // 3. Simpan data pemeriksaan - PERBAIKAN SYNTAX ERROR
                                        $insert = mysqli_query($koneksi, "INSERT INTO tb_pemeriksaan 
                                            (id_pemeriksaan, id_pendaftaran, id_user, no_rm, id_poli, id_dokter, tgl_pemeriksaan) 
                                            VALUES ('$id_pemeriksaan', '$id_pendaftaran', '$id_user', '$no_rm', '$id_poli', '$id_dokter', '$tgl_pemeriksaan')
                                        ");
                                        if (!empty($riwayat_penyakit)) {
                                            $update_pasien = mysqli_query($koneksi, "UPDATE tb_pasien 
                                                SET riwayat_penyakit = '$riwayat_penyakit' 
                                                WHERE no_rm = '$no_rm'
                                            ");
                                        }
                                        
                                        if ($insert) {
                                            // 4. Simpan data spesifik berdasarkan poli
                                            $insert_detail = false;
                                            
                                            if ($id_poli == '2') {
                                                $bb = $_POST['bb']; 
                                                $tb = $_POST['tb']; 
                                                $suhu = $_POST['suhu']; 
                                                $td = $_POST['td']; 
                                                $keluhan = $_POST['keluhan'];
                                                $diagnosa = "-";
                                                $kd_diagnosa = "-";
                                                $terapi = "-";
                                                $tindak_lanjut = "-";

                                                
                                                $insert_detail = mysqli_query($koneksi, "INSERT INTO tb_pemeriksaan_umum 
                                                    (id_pemeriksaan, bb, tb, suhu, td, keluhan, diagnosa, kd_diagnosa, terapi, tindak_lanjut) 
                                                    VALUES ('$id_pemeriksaan', '$bb', '$tb', '$suhu', '$td', '$keluhan', '$diagnosa', '$kd_diagnosa', '$terapi', '$tindak_lanjut')
                                                ");
                                                
                                                } elseif ($id_poli == '1') {
                                                    $hpht = $_POST['hpht']; 
                                                    $hpl = $_POST['hpl']; 
                                                    $td = $_POST['td'];  // PERBAIKAN: ganti $tensi dengan $td
                                                    $bb = $_POST['bb'];
                                                    $lila = $_POST['lila'];
                                                    $umur_hamil = $_POST['umur_hamil']; 
                                                    $gpa = $_POST['gpa']; 
                                                    $riwayat_persalinan = $_POST['riwayat_persalinan'];
                                                    $keluhan = $_POST['keluhan'];
                                                    $diagnosa = "-";
                                                    $kd_diagnosa = "-"; 
                                                    $terapi = "-";
                                                    $tindak_lanjut = "-";
                                    

                                                    // PERBAIKAN: tambahkan id_pemeriksaan di kolom
                                                    $insert_detail = mysqli_query($koneksi, "INSERT INTO tb_pemeriksaan_obgyn 
                                                        (id_pemeriksaan, hpht, hpl, td, bb, lila, umur_hamil, gpa, riwayat_persalinan, keluhan, diagnosa, kd_diagnosa, terapi, tindak_lanjut) 
                                                        VALUES ('$id_pemeriksaan', '$hpht', '$hpl', '$td', '$bb', '$lila', '$umur_hamil', '$gpa', '$riwayat_persalinan', '$keluhan', '$diagnosa', '$kd_diagnosa', '$terapi', '$tindak_lanjut')
                                                    ");
                                                    
                                                } elseif ($id_poli == '3') { // Poli Gigi langsung ke pemeriksaan

                                                    // Simpan detail minimal ke tb_pemeriksaan_gigi (agar bisa muncul di pemeriksaan.php)
                                                    $insert_detail = mysqli_query($koneksi, "INSERT INTO tb_pemeriksaan_gigi 
                                                        (id_pemeriksaan, S, O, kd_diagnosa, A, P, tindak_lanjut) 
                                                        VALUES ('$id_pemeriksaan', '-', '-', '-', '-', '-', '-')");

                                                } elseif ($id_poli == '4') {
                                                    if ($jenis_kia == 'Umum') {
                                                        // INSERT ke tb_pemeriksaan_umum
                                                        $bb = $_POST['bb']; 
                                                        $tb = $_POST['tb']; 
                                                        $suhu = $_POST['suhu']; 
                                                        $td = $_POST['td']; 
                                                        $keluhan = $_POST['keluhan'];
                                                        $diagnosa = "-";
                                                        $kd_diagnosa = "-";
                                                        $terapi = "-";
                                                        $tindak_lanjut = "-";
                                                        
                                                        $insert_detail = mysqli_query($koneksi, "INSERT INTO tb_pemeriksaan_umum 
                                                            (id_pemeriksaan, bb, tb, suhu, td, keluhan, diagnosa, kd_diagnosa, terapi, tindak_lanjut) 
                                                            VALUES ('$id_pemeriksaan', '$bb', '$tb', '$suhu', '$td', '$keluhan', '$diagnosa', '$kd_diagnosa', '$terapi', '$tindak_lanjut')
                                                        ");

                                                    } elseif ($jenis_kia == 'Obgyn') {
                                                        // INSERT ke tb_pemeriksaan_obgyn
                                                        $hpht = $_POST['hpht']; 
                                                        $hpl = $_POST['hpl']; 
                                                        $td = $_POST['td']; 
                                                        $bb = $_POST['bb'];
                                                        $lila = $_POST['lila'];
                                                        $umur_hamil = $_POST['umur_hamil']; 
                                                        $gpa = $_POST['gpa'];
                                                        $riwayat_persalinan = $_POST['riwayat_persalinan'];
                                                        $keluhan = $_POST['keluhan'];

                                                        $insert_detail = mysqli_query($koneksi, "INSERT INTO tb_pemeriksaan_obgyn 
                                                            (id_pemeriksaan, hpht, hpl, td, bb, lila, umur_hamil, gpa, riwayat_persalinan, keluhan, diagnosa, kd_diagnosa, terapi, tindak_lanjut) 
                                                            VALUES ('$id_pemeriksaan', '$hpht', '$hpl', '$td', '$bb', '$lila', '$umur_hamil', '$gpa', '$riwayat_persalinan', '$keluhan', '-', '-', '-', '-')");

                                                    } elseif ($jenis_kia == 'KB') {
                                                        // INSERT ke tb_pemeriksaan_kb
                                                        $jenis_kb = $_POST['jenis_kb']; 
                                                        $tgl_pasang = $_POST['tgl_pasang']; 
                                                        $anamnesa = $_POST['anamnesa'];

                                                        $insert_detail = mysqli_query($koneksi, "INSERT INTO tb_pemeriksaan_kb 
                                                            (id_pemeriksaan, jenis_kb, tgl_pasang, anamnesa, diagnosa, kd_diagnosa, terapi, tindak_lanjut)
                                                            VALUES ('$id_pemeriksaan', '$jenis_kb', '$tgl_pasang', '$anamnesa', '-', '-', '-', '-')");

                                                    } elseif ($jenis_kia == 'Imunisasi') {
                                                        // INSERT ke tb_pemeriksaan_imunisasi
                                                        $riwayat_imunisasi = $_POST['riwayat_imunisasi'];
                                                        $jenis_imunisasi = $_POST['jenis_imunisasi'] ?? '';
                                                        $tgl_imunisasi = $_POST['tgl_imunisasi'] ?? '';

                                                        $insert_detail = mysqli_query($koneksi, "INSERT INTO tb_pemeriksaan_imunisasi 
                                                            (id_pemeriksaan, riwayat_imunisasi, jenis_imunisasi, tgl_imunisasi, diagnosa, kd_diagnosa, terapi, tindak_lanjut)
                                                            VALUES ('$id_pemeriksaan', '$riwayat_imunisasi', '$jenis_imunisasi', '$tgl_imunisasi', '-', '-', '-', '-')");
                                                    }
                                                }
                                            
                                                if ($insert_detail) {
                                                    // 5. Update status pendaftaran
                                                    $update = mysqli_query($koneksi, "UPDATE tb_pemeriksaan SET status = '1' WHERE id_pemeriksaan = '$id_pemeriksaan'");
                                                    
                                                    if ($update) {
                                                        echo "<script>alert('Data Tersimpan! ID Pemeriksaan: $id_pemeriksaan'); window.location='screening.php';</script>";
                                                    } else {
                                                        echo "<div class='alert alert-warning'>Data pemeriksaan tersimpan, tetapi gagal update status pendaftaran.</div>";
                                                    }
                                                } else {
                                                    echo "<div class='alert alert-danger'>Gagal menyimpan detail pemeriksaan: " . mysqli_error($koneksi) . "</div>";
                                                    
                                                    // TAMBAHAN: Hapus data pemeriksaan yang sudah tersimpan jika detail gagal
                                                    mysqli_query($koneksi, "DELETE FROM tb_pemeriksaan WHERE id_pemeriksaan = '$id_pemeriksaan'");
                                                }
                                            }
                                        }
                                    }
                                }
                            ?>
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