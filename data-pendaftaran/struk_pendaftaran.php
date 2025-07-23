<?php
include "../koneksi.php";

$kode = $_GET["id_pendaftaran"];

$ambil = $koneksi->query("SELECT * FROM tb_pendaftaran a
            JOIN tb_poli b ON a.id_poli = b.id_poli WHERE id_pendaftaran='$kode'");

function tgl_indo($tanggal)
{
    $bulan = array(
        1 =>   'Januari',
        'Februari',
        'Maret',
        'April',
        'Mei',
        'Juni',
        'Juli',
        'Agustus',
        'September',
        'Oktober',
        'November',
        'Desember'
    );
    $pecahkan = explode('-', $tanggal);

    // variabel pecahkan 0 = tanggal
    // variabel pecahkan 1 = bulan
    // variabel pecahkan 2 = tahun

    return $pecahkan[2] . ' ' . $bulan[(int)$pecahkan[1]] . ' ' . $pecahkan[0];
}

?>

<!DOCTYPE html>
<html>

<head>
    <title>Struk Pendaftaran</title>
    <style>
    @page {
        size: 80mm 130mm; /* ukuran kertas struk */
        margin: 5mm;
    }

    body {
        width: 80mm;
    }

    img {
        width: 60%;
        display: block;
        margin: auto;
    }

    p {
        margin: 0;
        text-align: center;
    }

    span {
        display: block;
    }
</style>


<body>
    <img src="../login/img/logoutama.png">
    <p>
        <span style="font-size: 16px; font-weight: bold;">Klinik Utama Suko Asih</span><br>
        <span style="font-size: 13px;">Jl. Veteran No. 32, Sukoharjo (Depan SMPN 2 SKH)</span><br>
        <span style="font-size: 13px;">Telp. (0271) 593917</span>
    </p>
    <?php while ($pecah = $ambil->fetch_assoc()) { ?>
        <p>
            <span style="font-size: 50px; margin-top: 0px; font-weight: bold;"><?php echo substr($pecah['id_pendaftaran'], -3); ?></span><br>
            <span style="font-size: 20px; margin-top: 0px;"><?php echo $pecah['nm_poli']; ?></span><br>
            <span style="font-size: 18px; margin-top: 0px; font-weight: bold;">Tanggal: <?php echo tgl_indo(date('Y-m-d', strtotime($pecah['tgl_pendaftaran']))); ?></span>
        </p>
    <?php } ?>

    <script type="text/javascript">
    window.print();

    window.onafterprint = function () {
        window.location.href = "pendaftaran.php";
    };
    </script>

</body>

</html>