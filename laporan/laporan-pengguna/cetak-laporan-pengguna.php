<?php
include '../../koneksi.php';

$data = mysqli_query($koneksi, "SELECT * FROM tb_user ORDER BY id_user ASC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Cetak Laporan Data Pengguna</title>
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
    <h2>Laporan Data Pengguna</h2>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>ID User</th>
                <th>Username</th>
                <th>Nama Lengkap</th>
                <th>Password</th>
                <th>Jabatan</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $no = 1;
            while($row = mysqli_fetch_assoc($data)): ?>
            <tr>
                <td><?= $no++ ?></td>
                <td><?php echo 'U' . str_pad ($row['id_user'], 3, '0', STR_PAD_LEFT); ?></td>
                <td><?= $row['username'] ?></td>
                <td><?= $row['nm_lengkap'] ?></td>
                <td><?= str_repeat('*', strlen($row['password'])) ?></td>
                <td><?= ucfirst($row['jabatan']) ?></td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

</body>
</html>
