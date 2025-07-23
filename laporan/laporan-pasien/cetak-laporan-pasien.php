<?php
include '../../koneksi.php';

$data = mysqli_query($koneksi, "SELECT * FROM tb_pasien ORDER BY no_rm ASC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Cetak Laporan Data Pasien</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            color: #000;
            margin: 20px;
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        
        .header {
            text-align: center;
            margin-bottom: 10px;
        }

        .header img {
            height: 60px;
            margin-bottom: 5px;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            border: 1px solid black;
            padding: 3px;
            text-align: center;
            word-wrap: break-word;
        }

        th {
            background-color: #f2f2f2;
        }

        @media print {
            body {
                margin: 0;
                padding: 10mm;
            }
        }
    </style>
</head>
<body onload="window.print()">
    <div class="header">
        <img src="../../login/img/logoutama.png" alt="Logo Klinik" />
        <h2>Klinik Utama Suko Asih</h2>
        <p>Jl. Veteran No. 32, Sukoharjo (Depan SMPN 2 SKH)</p>
        <p>Telp. (0271) 593917</p>
        <hr>
    </div>
    <h2>Laporan Data Pasien</h2>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>No. RM</th>
                <th>NIK</th>
                <th>Nama Pasien</th>
                <th>Jenkel</th>
                <th>Tgl. Lahir</th>
                <th>Usia</th>
                <th>Alamat</th>
                <th>Telepon</th>
                <th>Alergi Obat</th>
                <th>Penanggung Jawab</th>
                <th>Telp PJ</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $no = 1;
            while($row = mysqli_fetch_assoc($data)): ?>
            <tr>
                <td><?= $no++ ?></td>
                <td><?= str_pad($row['no_rm'], 6, '0', STR_PAD_LEFT) ?></td>
                <td><?= $row['nik'] ?></td>
                <td><?= $row['nm_pasien'] ?></td>
                <td><?= $row['jenis_kelamin'] == 'Perempuan' ? 'P' : ($row['jenis_kelamin'] == 'Laki-laki' ? 'L' : '-') ?></td>
                <td><?= $row['tgl_lahir'] ?></td>
               <td>
                    <?php 
                    $tgl_lahir = $row['tgl_lahir'];
                    if (!empty($tgl_lahir)) {
                        $hari_lahir = new DateTime($tgl_lahir);
                        $hari_ini = new DateTime();
                        $usia = $hari_ini->diff($hari_lahir);
                        echo $usia->y . " thn " . $usia->m . " bln";
                    } else {
                        echo "-";
                    }
                    ?>
                </td>
                <td><?= $row['alamat'] ?></td>
                <td><?= $row['telp_psn'] ?></td>
                <td><?= $row['alergi_obat'] ?></td>
                <td><?= $row['nm_pj'] ?></td>
                <td><?= $row['telp_pj'] ?></td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

</body>
</html>
