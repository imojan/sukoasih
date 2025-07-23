<?php
include '../../koneksi.php';

$data = mysqli_query($koneksi, "SELECT * FROM tb_poli ORDER BY id_poli ASC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Cetak Laporan Data Poliklinik</title>
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
    <h2>Laporan Data Poliklinik</h2>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>ID Poli</th>
                <th>Nama Poli</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $no = 1;
            while($row = mysqli_fetch_assoc($data)): ?>
            <tr>
                <td><?= $no++ ?></td>
                <td><?= str_pad($row['id_poli'], 6, '0', STR_PAD_LEFT) ?></td>
                <td><?= $row['nm_poli'] ?></td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

</body>
</html>
