<?php
session_start();
include '../../koneksi.php';

if (!isset($_SESSION["jabatan"])) {
    echo "<script>location='../../login/index.php'</script>";
    exit();
}

$ambil = $koneksi->query("SELECT * FROM tb_user WHERE id_user='$_GET[id_user]'");
$pecah = $ambil->fetch_assoc();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <link rel="shortcut icon" type="image/png" href="../../assets/img/sehat-1.png" />
    <title>Suko Asih | Data Pengguna</title>
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
                                <a class="nav-link active" href="user.php">Data Pengguna</a>
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
                    <h1 class="mt-4">Ubah Data Pengguna</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item"><a href="../../index.php" class="text-decoration-none">Dashboard</a></li>
                        <li class="breadcrumb-item active">Data Master</li>
                        <li class="breadcrumb-item active">Data Pengguna</li>
                        <li class="breadcrumb-item active">Ubah Data Pengguna</li>
                    </ol>
                    <div class="card mb-4">
                        <div class="card-header font-weight-bold">
                            Data Pengguna : <?php echo $pecah['nm_lengkap']; ?>
                        </div>
                        <div class="card-body">
                            <div class="">
                                <form class="ml-4" method="post" enctype="multipart/form-data">
                                    <div class="form-group row">
                                        <div class="col-sm-4">
                                            <label>ID Pengguna</label>
                                            <input type="text" class="form-control" name="id_user" value="<?php echo 'U' . str_pad($pecah['id_user'], 3, '0', STR_PAD_LEFT); ?>" readonly>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <div class="col-sm-4">
                                            <label>Username</label>
                                            <input type="text" class="form-control" name="username" value="<?php echo $pecah['username'] ?>" required>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-sm-4">
                                            <label>Nama Lengkap</label>
                                            <input type="text" class="form-control" name="nm_lengkap" value="<?php echo $pecah['nm_lengkap'] ?>" required>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-sm-4">
                                            <label>Password</label>
                                            <input type="text" class="form-control" name="password" value="<?php echo $pecah['password'] ?>" required>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-sm-4">
                                            <label>Jabatan</label>
                                            <select class="custom-select" name="jabatan" required>

                                            <!-- Tampilkan opsi aktif di atas -->
                                            <option selected value="<?= $pecah['jabatan']; ?>">
                                                <?= ucfirst($pecah['jabatan']); ?>
                                            </option>

                                            <!-- Tampilkan semua jabatan unik lainnya, kecuali yang sudah terpilih -->
                                            <?php
                                            $jabatanSaatIni = $pecah['jabatan'];
                                            $ambilJabatan = $koneksi->query("SELECT DISTINCT jabatan FROM tb_user WHERE jabatan != '$jabatanSaatIni'");
                                            foreach ($ambilJabatan as $user) : ?>
                                                <option value="<?= $user['jabatan']; ?>"><?= ucfirst($user['jabatan']); ?></option>
                                            <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group ">
                                        <button class="btn btn-success font-weight-bold px-3 mr-2" name="ubah"><i class="far fa-save"></i> Simpan</button>
                                        <a href="user.php" class="btn btn-danger font-weight-bold px-3 mr-2"><i class="fas fa-arrow-circle-left"></i> Kembali</a>
                                    </div>
                                </form>

                                <?php
                                if (isset($_POST['ubah'])) {
                                    $koneksi->query("UPDATE tb_user SET username='$_POST[username]', nm_lengkap='$_POST[nm_lengkap]', password='$_POST[password]', 
                                        jabatan='$_POST[jabatan]'
                                    WHERE id_user='$_GET[id_user]'");
                                    echo "<script>alert('Data Pengguna Telah Diubah!');</script>";
                                    echo "<script>location='user.php'</script>";
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