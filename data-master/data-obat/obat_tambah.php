<?php
session_start();
include '../../koneksi.php';

if (!isset($_SESSION["jabatan"])) {
    echo "<script>location='../../login/index.php'</script>";
    exit();
}

$query = mysqli_query($koneksi, "SELECT id_obat FROM tb_obat ORDER BY id_obat DESC LIMIT 1");
$data_kode = mysqli_fetch_assoc($query);

$no_akhir = (isset($data_kode['id_obat'])) ? (int)substr($data_kode['id_obat'], -3) : 0;
$no_baru = $no_akhir + 1;
$kode_baru = str_pad($no_baru, 3, "0", STR_PAD_LEFT);
$id_obat_baru = "O" . $kode_baru;

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <link rel="shortcut icon" type="image/png" href="../../assets/img/sehat-1.png" />
    <title>Suko Asih | Data Obat</title>
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
                        <a class="nav-link collapsed active" href="#" data-toggle="collapse" data-target="#data-master" aria-expanded="false" aria-controls="data-master">
                            <div class="sb-nav-link-icon"><i class="fas fa-columns"></i></div>
                            Data Master
                            <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                        </a>
                        <div class="collapse show" id="data-master" aria-labelledby="headingOne" data-parent="#sidenavAccordion">
                            <nav class="sb-sidenav-menu-nested nav show">
                                <a class="nav-link active" href="obat.php">Data Obat</a>
                            </nav>
                        </div>
                    </div>
                </div>
                <p class="text-center mt-3 text-muted" style="font-size: 0.9rem;">&copy; 2025 Klinik Suko Asih</p>
            </nav>
        </div>
        <div id="layoutSidenav_content" class="bg-white text-dark">
            <main>
                <div class="container-fluid">
                    <h1 class="mt-4">Tambah Data Obat</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item"><a href="../../index.php" class="text-decoration-none">Dashboard</a></li>
                        <li class="breadcrumb-item active">Data Master</li>
                        <li class="breadcrumb-item active">Data Obat</li>
                        <li class="breadcrumb-item active">Tambah Data Obat</li>
                    </ol>
                    <div class="card mb-4">
                        <div class="card-header font-weight-bold">
                            Data Obat
                        </div>
                        <div class="card-body">
                            <div class="">
                                <form class="ml-4" method="post" enctype="multipart/form-data">
                                    <div class="form-group row">
                                        <div class="col-sm-4">
                                            <label>ID Obat</label>
                                            <input type="text" class="form-control" value="<?= $id_obat_baru; ?>" name="id_obat" readonly>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-sm-4">
                                            <label>Nama Obat</label>
                                            <input type="text" class="form-control" name="nm_obat" required>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-sm-4">
                                            <label>Bentuk Obat</label>
                                            <select class="custom-select" name="bentuk_obat" required>
                                                <option value="" disabled selected>Pilih Bentuk Obat</option>
                                                <option value="Tablet">Tablet</option>
                                                <option value="Kapsul">Kapsul</option>
                                                <option value="Sirup">Sirup</option>
                                                <option value="Salep">Salep</option>
                                                <option value="Injeksi">Injeksi</option>
                                                <option value="Strip">Strip</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-sm-4">
                                            <label>Stok Obat</label>
                                            <input type="text" class="form-control" name="stok_obat" required>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-sm-4">
                                            <label>Kadaluarsa Obat</label>
                                            <input type="date" class="form-control" name="exp_obat" required>
                                        </div>
                                    </div>
                                    <div class="form-group ">
                                        <button class="btn btn-success font-weight-bold px-3 mr-2" name="save"><i class="far fa-save"></i> Simpan</button>
                                        <a href="obat.php" class="btn btn-danger font-weight-bold px-3 mr-2"><i class="fas fa-arrow-circle-left"></i> Kembali</a>
                                    </div>
                                </form>

                                <?php
                                    if (isset($_POST['save'])) {
                                        if ($_POST['bentuk_obat'] == 'obat') {
                                            echo "<script>alert('Pilih Obat dengan Benar!');</script>";
                                        } else {
                                            // Perhatikan: id_obat tidak dimasukkan
                                            $koneksi->query("INSERT INTO tb_obat (nm_obat, bentuk_obat, stok_obat, exp_obat) 
                                                VALUES ('$_POST[nm_obat]', '$_POST[bentuk_obat]', '$_POST[stok_obat]', '$_POST[exp_obat]')");

                                            $id_obat = $koneksi->insert_id;
                                            $formatted_id_obat = str_pad($id_obat, 3, '0', STR_PAD_LEFT);

                                            echo "<script>alert('Data Tersimpan! ID Obat: O$formatted_id_obat');</script>";
                                            echo "<script>location='obat.php'</script>";
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
    <script src="../../assets/js/jquery-3.5.1.slim.min.js"></script>
    <script src="../../assets/js/bootstrap.bundle.min.js"></script>
    <script src="../../assets/js/scripts.js"></script>
    <script src="../../assets/js/jquery.dataTables.min.js"></script>
    <script src="../../assets/js/dataTables.bootstrap4.min.js"></script>
    <script src="../../assets/demo/datatables-demo.js"></script>
</body>

</html>