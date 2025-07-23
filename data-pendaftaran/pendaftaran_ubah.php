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

$id_pendaftaran = $_GET['id_pendaftaran'];

$query = $koneksi->query("SELECT pendaftaran.*, pasien.nm_pasien, pasien.tgl_lahir FROM tb_pendaftaran AS pendaftaran JOIN tb_pasien AS pasien ON pendaftaran.no_rm = pasien.no_rm WHERE pendaftaran.id_pendaftaran = '$id_pendaftaran'");
$pecah = $query->fetch_assoc();

$id_poli_terpilih = $pecah['id_poli'] ?? '';
$id_dokter_terpilih = $pecah['id_dokter'] ?? '';
$jenis_kia = $pecah['jenis_kia'] ?? '';

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
<script>
    function setCurrentTime() {
        const now = new Date();
        const jam = now.getHours().toString().padStart(2, '0');
        const menit = now.getMinutes().toString().padStart(2, '0');
        document.getElementById('jam_daftar').value = `${jam}:${menit}`;
    }
    window.onload = setCurrentTime;
</script>


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
                                <div class="sb-nav-link-icon"><i class="fas fa-address-book"></i></div>
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
                    <h1 class="mt-4">Ubah Data Pendaftaran</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item"><a href="../index.php" class="text-decoration-none">Dashboard</a></li>
                        <li class="breadcrumb-item active">Data Pendaftaran</li>
                        <li class="breadcrumb-item active">Ubah Data Pendaftaran</li>
                    </ol>
<div class="card mb-4">
        <div class="card-header font-weight-bold">
            Data Pendaftaran : <?php echo $pecah['id_pendaftaran']; ?>
        </div>
        <div class="card-body">
            <form class="mx-4" method="post">
                <div class="form-group row">
                    <div class="col-sm-4">
                        <label>ID Pendaftaran</label>
                        <input type="text" class="form-control" name="id_pendaftaran" value="<?php echo $pecah['id_pendaftaran'] ?>" readonly>
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-sm-4">
                        <label>No. RM</label>
                        <input type="text" class="form-control" id="no_rm" name="no_rm" value="<?php echo str_pad($pecah['no_rm'], 6, '0', STR_PAD_LEFT); ?>" readonly>
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-sm-4">
                        <label>Nama Pasien</label>
                        <input type="text" class="form-control" id="nm_pasien" value="<?php echo $pecah['nm_pasien'] ?>" readonly>
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-sm-4">
                        <label>Tanggal Lahir</label>
                        <input type="text" class="form-control" id="tgl_lahir" value="<?php echo date('d-m-Y', strtotime($pecah['tgl_lahir'])) ?>" readonly>
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-sm-4">
                        <label>Tanggal Pendaftaran</label>
                        <input type="date" class="form-control" name="tgl_pendaftaran" value="<?php echo date('Y-m-d', strtotime($pecah['tgl_pendaftaran'])) ?>" required>
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-sm-4">
                        <label>Jam Pendaftaran</label>
                        <input type="time" class="form-control" name="jam_daftar" id="jam_daftar" value="<?php echo date('H:i', strtotime($pecah['tgl_pendaftaran'])) ?>" required>
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-sm-4">
                        <label>Poli</label>
                        <select class="custom-select" name="id_poli" id="id_poli" required>
                            <option value="">-- Pilih Poli --</option>
                            <?php
                            $query_poli = mysqli_query($koneksi, "SELECT * FROM tb_poli");
                            while ($poli = mysqli_fetch_assoc($query_poli)) {
                                $selected = ($poli['id_poli'] == $pecah['id_poli']) ? 'selected' : '';
                                echo '<option value="' . $poli['id_poli'] . '" ' . $selected . '>' . $poli['nm_poli'] . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="form-group row" id="opsi_kia" style="display: none;">
                    <div class="col-sm-12">
                        <label class="font-weight-bold d-block mb-2">Jenis Poli KIA</label>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="jenis_kia" id="kia_umum" value="umum"
                            <?= ($jenis_kia === 'Umum') ? 'checked' : '' ?>>
                            <label class="form-check-label" for="kia_umum">Poli Umum</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="jenis_kia" id="kia_obgyn" value="obgyn"
                            <?= ($jenis_kia === 'Obgyn') ? 'checked' : '' ?>>
                            <label class="form-check-label" for="kia_obgyn">Poli Obgyn</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="jenis_kia" id="kia_imunisasi" value="imunisasi"
                            <?= ($jenis_kia === 'Imunisasi') ? 'checked' : '' ?>>
                            <label class="form-check-label" for="kia_imunisasi">Poli Imunisasi</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="jenis_kia" id="kia_kb" value="kb"
                            <?= ($jenis_kia === 'KB') ? 'checked' : '' ?>>
                            <label class="form-check-label" for="kia_kb">Poli KB</label>
                        </div>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-sm-4">
                        <label>Dokter</label>
                        <select class="custom-select" name="id_dokter" id="id_dokter" required>
                            <option value="">-- Pilih Dokter --</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <button class="btn btn-success font-weight-bold px-3 mr-2" name="ubah"><i class="far fa-save"></i> Simpan</button>
                    <a href="pendaftaran.php" class="btn btn-danger font-weight-bold px-3 mr-2"><i class="fas fa-arrow-circle-left"></i> Kembali</a>
                </div>
            </form>

            <?php
            if (isset($_POST['ubah'])) {
                $tanggal = $_POST['tgl_pendaftaran']; // format: 2025-06-16
                $jam     = $_POST['jam_daftar'];      // format: 14:35
                $datetime = "$tanggal $jam";
                $jenis_kia = $_POST['jenis_kia'];

                $koneksi->query("UPDATE tb_pendaftaran SET tgl_pendaftaran='$datetime', id_poli='$_POST[id_poli]', id_dokter='$_POST[id_dokter]', jenis_kia= '$_POST[jenis_kia]' WHERE id_pendaftaran='$id_pendaftaran'");
                echo "<script>alert('Data Pendaftaran Telah Diubah!');</script>";
                echo "<script>location='pendaftaran.php'</script>";
            }
            ?>
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

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
    $(document).ready(function () {
        $('#id_poli').change(function () {
            var id_poli = $(this).val();
            if (id_poli != '') {
                $.ajax({
                    url: "get_dokter.php",
                    method: "POST",
                    data: { id_poli: id_poli },
                    success: function (data) {
                        $('#id_dokter').html(data);
                    }
                });
            } else {
                $('#id_dokter').html('<option value="">-- Pilih Dokter --</option>');
            }
            // Deteksi apakah poli KIA dipilih
        const teksPoliTerpilih = $("#id_poli option:selected").text().toLowerCase();
        if (teksPoliTerpilih.includes("kia")) {
            $('#opsi_kia').show();
        } else {
            $('#opsi_kia').hide();
            $('input[name="jenis_pemeriksaan[]"]').prop('checked', false);
        }
    });
    // Jika halaman reload dan pilihan poli sudah ada
    const teksPoliTerpilih = $("#id_poli option:selected").text().toLowerCase();
    if (teksPoliTerpilih.includes("kia")) {
        $('#opsi_kia').show();
    } else {
        $('#opsi_kia').hide();
        $('input[name="jenis_pemeriksaan[]"]').prop('checked', false);
    }

        var idPoliTerpilih = '<?= $id_poli_terpilih ?>';
        var idDokterTerpilih = '<?= $id_dokter_terpilih ?>';
        if (idPoliTerpilih !== '') {
            $.ajax({
                url: "get_dokter.php",
                method: "POST",
                data: { id_poli: idPoliTerpilih },
                success: function (data) {
                    $('#id_dokter').html(data);
                    $('#id_dokter').val(idDokterTerpilih);
                }
            });
        }
    });
</script>
</body>
</html>