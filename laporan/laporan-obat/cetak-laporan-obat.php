<?php
include '../../koneksi.php';

$data = mysqli_query($koneksi, "SELECT * FROM tb_obat ORDER BY id_obat ASC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Cetak Laporan Data Obat</title>
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
    <h2>Laporan Data Obat</h2>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>ID Obat</th>
                <th>Nama Obat</th>
                <th>Bentuk Obat</th>
                <th>Stok Obat</th>
                <th>Kadaluarsa</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $no = 1;
            while($row = mysqli_fetch_assoc($data)): ?>
            <tr>
                <td><?= $no++ ?></td>
                <td><?= 'O' . str_pad($row['id_obat'], 3, '0', STR_PAD_LEFT) ?></td>
                <td><?= $row['nm_obat'] ?></td>
                <td><?= $row['bentuk_obat'] ?></td>
                <td><?= $row['stok_obat'] ?></td>
                <td><?= date('d-m-Y', strtotime($row['exp_obat'])) ?? '-'; ?></td>
                <td>
                    <?php if ($row['stok_obat'] <= 0) { ?>
                        <span class="badge badge-danger p-2">Kosong</span>
                    <?php } else { ?>
                        <span class="badge badge-success p-2">Tersedia = <?= $row['stok_obat']; ?> </span>
                    <?php } ?>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

</body>
</html>
