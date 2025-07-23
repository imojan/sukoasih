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

$id_poli_terpilih = $_POST['id_poli'] ?? '';
$id_dokter_terpilih = $_POST['id_dokter'] ?? '';

$tanggal_hari_ini = date('Y-m-d'); // format tgl untuk disimpan
$prefix = "PD" . date('Ymd');      // contoh: PD20250617

// Cek kode terakhir di tanggal hari ini
$query = mysqli_query($koneksi, "SELECT MAX(id_pendaftaran) AS maxKode 
    FROM tb_pendaftaran 
    WHERE id_pendaftaran LIKE '$prefix%'");

$data = mysqli_fetch_assoc($query);

if ($data['maxKode']) {
    $lastKode = $data['maxKode'];
    $lastNumber = (int)substr($lastKode, -3); // Ambil 3 digit terakhir
    $nextNumber = str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
} else {
    $nextNumber = '001';
}

$kode_baru = $prefix . '-' . $nextNumber;

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
        const waktu = `${jam}:${menit}`;

        document.getElementById('jam_daftar').value = waktu;
    }

    // Set waktu saat halaman dimuat
    window.onload = function () {
        setCurrentTime();

    };
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
                    <h1 class="mt-4">Tambah Data Pendaftaran</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item"><a href="../index.php" class="text-decoration-none">Dashboard</a></li>
                        <li class="breadcrumb-item active">Data Pendaftaran</li>
                        <li class="breadcrumb-item active">Tambah Data Pendaftaran</li>
                    </ol>
                    <div class="card mb-4">
                        <div class="card-header font-weight-bold">
                            Data Pendaftaran
                        </div>
                        <div class="card-body">
                            <div class="">
                            <form class="mx-4" method="post" enctype="multipart/form-data">
                                <div class="form-group row">
                                    <div class="col-sm-4">
                                        <?php $ambil = mysqli_query($koneksi, "SELECT * FROM tb_pendaftaran ORDER BY id_pendaftaran DESC LIMIT 1"); ?>
                                        <?php $data = $ambil->fetch_assoc(); ?>
                                        <label>ID Pendaftaran</label>
                                        <input type="text" class="form-control" name="id_pendaftaran" value="<?php echo $kode_baru; ?>" readonly>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-4">
                                        <label>Cari Pasien</label>
                                        <div class="input-group">
                                            <input type="text" id="keyword_pasien" class="form-control" placeholder="Nama / No. RM / NIK">
                                            <div class="input-group-append">
                                                <button type="button" id="btnCariPasien" class="btn btn-primary">Cari</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-4">
                                        <label>No. RM</label>
                                        <input type="text" class="form-control" id="no_rm" name="no_rm" readonly>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col-sm-4">
                                        <label>Nama Pasien</label>
                                        <input type="text" class="form-control" id="nm_pasien" readonly>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col-sm-4">
                                        <label>Tanggal Lahir</label>
                                        <input type="text" class="form-control" id="tgl_lahir" readonly>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col-sm-4">
                                            <label>Tanggal Pendaftaran</label>
                                            <input type="date" class="form-control" name="tgl_pendaftaran" value="<?php echo date("Y-m-d"); ?>" required>
                                    </div>
                                </div>
                                    <div class="form-group row">
                                        <div class="col-sm-4">
                                            <label>Jam Pendaftaran</label>
                                                <!-- Jam Pendaftaran -->
                                            <input type="time" class="form-control" name="jam_daftar" id="jam_daftar" required>
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
                                                    echo '<option value="' . $poli['id_poli'] . '">' . $poli['nm_poli'] . '</option>';
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group row" id="opsi_kia" style="display: none;">
                                        <div class="col-sm-12">
                                            <label class="font-weight-bold d-block mb-2">Jenis Poli KIA</label>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="jenis_kia" id="kia_umum" value="umum">
                                                <label class="form-check-label" for="kia_umum">Poli Umum</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="jenis_kia" id="kia_obgyn" value="obgyn">
                                                <label class="form-check-label" for="kia_obgyn">Poli Obgyn</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="jenis_kia" id="kia_imunisasi" value="imunisasi">
                                                <label class="form-check-label" for="kia_imunisasi">Poli Imunisasi</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="jenis_kia" id="kia_kb" value="kb">
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
                                        <button class="btn btn-success font-weight-bold px-3 mr-2" name="save"><i class="far fa-save"></i> Simpan</button>
                                        <a href="pendaftaran.php" class="btn btn-danger font-weight-bold px-3 mr-2"><i class="fas fa-arrow-circle-left"></i> Kembali</a>
                                    </div>
                                </form>

                                <?php
                                if (isset($_POST['save'])) {
                                    $id_pendaftaran = $_POST['id_pendaftaran'];
                                    $no_rm = $_POST['no_rm'];
                                    $tgl_pendaftaran = $_POST['tgl_pendaftaran'] . ' ' . $_POST['jam_daftar'];
                                    $id_poli = $_POST['id_poli'];
                                    $id_dokter = $_POST['id_dokter'];
                                    $jenis_kia = $_POST['jenis_kia'] ?? null;

                                    if (empty($id_pendaftaran) || empty($no_rm) || empty($_POST['tgl_pendaftaran']) || empty($_POST['jam_daftar']) || empty($id_poli) || empty($_POST['id_dokter'])){
                                    echo "<script>alert('Harap lengkapi semua data!');</script>";
                                    return;
                                }
                                    
                                    $cek = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM tb_pendaftaran WHERE no_rm = '$no_rm'");
                                    $data = mysqli_fetch_assoc($cek);
                                    $status_kunjungan = ($data['total'] > 0) ? 1 : 0;

                                    // Cek apakah pasien sudah mendaftar di poli yang sama pada hari yang sama
                                    $cek = mysqli_query($koneksi, "SELECT * FROM tb_pendaftaran 
                                        WHERE no_rm = '$no_rm' 
                                        AND tgl_pendaftaran = '$tgl_pendaftaran' 
                                        AND id_poli = '$id_poli'");

                                    if (mysqli_num_rows($cek) > 0) {
                                        echo "<script>alert('Pasien ini sudah terdaftar di poli yang sama hari ini!');</script>";
                                    } else {
                                        $koneksi->query("INSERT INTO tb_pendaftaran (id_pendaftaran, no_rm, tgl_pendaftaran,
                                        id_poli, id_dokter, status, jenis_kia) 
                                        VALUES (
                                            '$id_pendaftaran', 
                                            '$no_rm', 
                                            '$tgl_pendaftaran', 
                                            '$id_poli', 
                                            '$id_dokter',
                                            '$status_kunjungan',
                                            '$jenis_kia')");


                                        echo "<script>alert('Data Tersimpan! ID Pendaftaran: $kode_baru');</script>";
                                        echo "<script>location='struk_pendaftaran.php?id_pendaftaran=$kode_baru'</script>";
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

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
    function tampilkanOpsiKIA() {
        const teksPoliTerpilih = $("#id_poli option:selected").text().toLowerCase();
        if (teksPoliTerpilih.includes("kia")) {
            $('#opsi_kia').show();
        } else {
            $('#opsi_kia').hide();
            $('input[name="jenis_pemeriksaan[]"]').prop('checked', false);
        }
    }

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

            tampilkanOpsiKIA(); // dipanggil setelah ganti poli
        });

        tampilkanOpsiKIA(); // dipanggil saat reload halaman

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
    <input type="text" id="keyword_pasien" placeholder="Ketik nama atau no RM">
    <button type="button" id="btnCariPasien">Cari</button>

    <input type="text" id="no_rm" readonly>
    <input type="text" id="nm_pasien" readonly>
    <input type="text" id="tgl_lahir" readonly>

    <script>
        $(document).ready(function () {
            $('#btnCariPasien').click(function () {
                let keyword = $('#keyword_pasien').val();
                console.log("Keyword yang dikirim: ", keyword);
                if (keyword.trim() === '') {
                    alert('Ketik Nama / No. RM / NIK terlebih dahulu!');
                    return;
                }

                $.ajax({
                    url: 'cari_pasien.php',
                    method: 'POST',
                    dataType: 'json',
                    data: { keyword: keyword },
                    success: function (response) {
                        console.log(response); // Debug output
                        if (response.status === 'success') {
                            $('#no_rm').val(response.no_rm);
                            $('#nm_pasien').val(response.nm_pasien);
                            $('#tgl_lahir').val(response.tgl_lahir);
                            $('input[name="no_rm"]').val(response.no_rm);
                        } else {
                            alert('Pasien tidak ditemukan!');
                        }
                    },
                    error: function () {
                        alert('Terjadi kesalahan saat mencari pasien.');
                    }
                });
            });

            $('#keyword_pasien').keypress(function (e) {
                if (e.which == 13) {
                    $('#btnCariPasien').click();
                }
            });
        });
    </script>

</body>
</html>