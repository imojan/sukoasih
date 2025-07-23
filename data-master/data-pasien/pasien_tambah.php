<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
// Paksa MySQLi melempar Exception saat error
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
session_start();
include '../../koneksi.php';

if (!isset($_SESSION["jabatan"])) {
    echo "<script>location='../../login/index.php'</script>";
    exit();
}

$query = mysqli_query($koneksi, "SELECT no_rm FROM tb_pasien ORDER BY no_rm DESC LIMIT 1");
$data_kode = mysqli_fetch_assoc($query);

$no_akhir = (isset($data_kode['no_rm'])) ? (int)substr($data_kode['no_rm'], -6) : 0;
$no_baru = $no_akhir + 1;
$kode_baru = str_pad($no_baru, 6, "0", STR_PAD_LEFT);


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <link rel="shortcut icon" type="image/png" href="../../assets/img/sehat-1.png" />
    <title>Suko Asih | Data Pasien</title>
    <link href="../../assets/css/styles.css" rel="stylesheet" />
    <link href="../../assets/css/dataTables.bootstrap4.min.css" rel="stylesheet" />
    <script src="../../assets/js/all.min.js"></script>
</head>

<body class="sb-nav-fixed">
    <nav class="sb-topnav navbar navbar-expand navbar-dark bg-light">
        <a class="navbar-brand text-center" href="index.php">
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
                        <?php if ($_SESSION["jabatan"] == 'pimpinan') : ?>
                            <a class="nav-link collapsed active" href="#" data-toggle="collapse" data-target="#data-master" aria-expanded="false" aria-controls="data-master">
                                <div class="sb-nav-link-icon"><i class="fas fa-columns"></i></div>
                                Data Master
                                <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                            </a>
                            <div class="collapse show" id="data-master" aria-labelledby="headingOne" data-parent="#sidenavAccordion">
                                <nav class="sb-sidenav-menu-nested nav show">
                                    <a class="nav-link active" href="pasien.php">Data Pasien</a>
                                </nav>
                            </div>
                        <?php elseif ($_SESSION["jabatan"] == 'dokter') : ?>
                            <a class="nav-link collapsed active" href="#" data-toggle="collapse" data-target="#data-master" aria-expanded="false" aria-controls="data-master">
                                <div class="sb-nav-link-icon"><i class="fas fa-columns"></i></div>
                                Data Master
                                <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                            </a>
                            <div class="collapse show" id="data-master" aria-labelledby="headingOne" data-parent="#sidenavAccordion">
                                <nav class="sb-sidenav-menu-nested nav">
                                    <a class="nav-link active" href="../data-pasien/pasien.php">Data Pasien</a>
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
                    <h1 class="mt-4">Tambah Data Pasien</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item"><a href="../../index.php" class="text-decoration-none">Dashboard</a></li>
                        <li class="breadcrumb-item active">Data Master</li>
                        <li class="breadcrumb-item active">Data Pasien</li>
                        <li class="breadcrumb-item active">Tambah Data Pasien</li>
                    </ol>
                    <div class="card mb-4">
                        <div class="card-header font-weight-bold">
                            Data Pasien
                        </div>
                        <div class="card-body">
                            <div class="">
                                <form class="mx-4" method="post" enctype="multipart/form-data">
                                    <div class="form-group row">
                                        <div class="col-sm-4">
                                            <label>No. RM</label>
                                            <input type="text" class="form-control" name="no_rm" value="<?= $kode_baru; ?>" readonly>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-sm-4">
                                            <label>NIK</label>
                                            <input type="text" class="form-control" name="nik" id="nik"
                                            minlength="16" maxlength="16"
                                            pattern="\d{16}"
                                            required
                                            title="NIK harus 16 digit angka.">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-sm-4">
                                            <label>Nama Pasien</label>
                                            <input type="text" class="form-control" name="nm_pasien" required>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                    <div class="col-sm-4">
                                        <label>Jenis Kelamin</label>
                                        <select class="custom-select" name="jenis_kelamin" required>
                                            <option value="" disabled selected>--Pilih--</option>
                                            <option value="Laki-laki">Laki-laki</option>
                                            <option value="Perempuan">Perempuan</option>
                                        </select>
                                    </div>
                                </div>
                                    <div class="form-group row">
                                        <div class="col-sm-4">
                                            <label>Tanggal Lahir</label>
                                            <input type="date" class="form-control" name="tgl_lahir" required>
                                        </div>
                                    </div>
                                        <div class="form-group row">
                                        <div class="col-sm-4">
                                            <label>Alamat</label>
                                            <textarea class="form-control" name="alamat" rows="3" required></textarea>
                                        </div>
                                    </div>
                                        <div class="form-group row">
                                            <div class="col-sm-4">
                                                <label>Telepon Pasien</label>
                                                <input type="text" class="form-control" name="telp_psn" required>
                                            </div>
                                        </div>
                                    <div class="form-group row">
                                        <div class="col-sm-4">
                                            <label>Alergi Obat</label>
                                            <textarea class="form-control" name="alergi_obat" rows="3" required></textarea>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-sm-4">
                                            <label>Nama Penanggung Jawab</label>
                                            <input type="text" class="form-control" name="nm_pj" required>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-sm-4">
                                            <label>Telepon Penanggung Jawab</label>
                                            <input type="text" class="form-control" name="telp_pj" required>
                                        </div>
                                    </div>
                                    <div class="form-group ">
                                        <button class="btn btn-success font-weight-bold px-3 mr-2" name="save"><i class="far fa-save"></i> Simpan</button>
                                        <a href="pasien.php" class="btn btn-danger font-weight-bold px-3 mr-2"><i class="fas fa-arrow-circle-left"></i> Kembali</a>
                                    </div>
                                </form>

                                <?php
                                    if (isset($_POST['save'])) {
                                            // Simpan data ke database TANPA menyebutkan no_rm karena auto_increment
                                            $koneksi->query("INSERT INTO tb_pasien (
                                                nik, nm_pasien, jenis_kelamin, tgl_lahir, alamat, telp_psn, alergi_obat, nm_pj, telp_pj
                                            ) VALUES (
                                                '$_POST[nik]',
                                                '$_POST[nm_pasien]',
                                                '$_POST[jenis_kelamin]',
                                                '$_POST[tgl_lahir]',
                                                '$_POST[alamat]',
                                                '$_POST[telp_psn]',
                                                '$_POST[alergi_obat]',
                                                '$_POST[nm_pj]',
                                                '$_POST[telp_pj]'
                                            )");
                        
                                            // Ambil nilai no_rm terakhir (yang baru saja dibuat oleh AUTO_INCREMENT)
                                            $no_rm = $koneksi->insert_id;

                                            // Format ke 6 digit
                                            $formatted_no_rm = str_pad($no_rm, 6, '0', STR_PAD_LEFT);

                                            echo "<script>alert('Data Tersimpan! Nomor RM: $formatted_no_rm');</script>";
                                            echo "<script>location='pasien.php'</script>";
                                        }
                                ?>

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