<?php
require '../../vendor/autoload.php';
include '../../koneksi.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Header kolom
$sheet->setCellValue('A1', 'No');
$sheet->setCellValue('B1', 'ID User');
$sheet->setCellValue('C1', 'Username');
$sheet->setCellValue('D1', 'Nama Lengkap');
$sheet->setCellValue('E1', 'Password');
$sheet->setCellValue('F1', 'Jabatan');

// Ambil data
$data = mysqli_query($koneksi, "SELECT * FROM tb_user ORDER BY id_user ASC");
$rowNum = 2;
$no = 1;

while ($row = mysqli_fetch_assoc($data)) {
    $sheet->setCellValue("A$rowNum", $no++);
    $sheet->setCellValue("B$rowNum", 'U' . str_pad($row['id_user'], 3, '0', STR_PAD_LEFT));
    $sheet->setCellValue("C$rowNum", $row['username']);
    $sheet->setCellValue("D$rowNum", $row['nm_lengkap']);
    $passwordStars = str_repeat('*', strlen($row['password']));
    $sheet->setCellValue("E$rowNum", $passwordStars);
    $sheet->setCellValue("F$rowNum", ucfirst($row['jabatan']));
    $rowNum++;
}

// Set header supaya browser mendownload file excel
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="Laporan_Data_Pengguna.xlsx"');
header('Cache-Control: max-age=0');

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit;
?>
