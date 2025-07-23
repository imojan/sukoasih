<?php
require '../../vendor/autoload.php';
include '../../koneksi.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Header kolom
$sheet->setCellValue('A1', 'No');
$sheet->setCellValue('B1', 'ID Dokter');
$sheet->setCellValue('C1', 'Nama Dokter');
$sheet->setCellValue('D1', 'Spesialis');
$sheet->setCellValue('E1', 'No. Telepon');

// Ambil data
$data = mysqli_query($koneksi, "SELECT * FROM tb_dokter JOIN tb_poli ON tb_dokter.id_poli = tb_poli.id_poli ORDER BY id_dokter ASC");
$rowNum = 2;
$no = 1;

while ($row = mysqli_fetch_assoc($data)) {
    $sheet->setCellValue("A$rowNum", $no++);
    $sheet->setCellValue("B$rowNum", 'D' . str_pad($row['id_dokter'], 3, '0', STR_PAD_LEFT)); // ✅ jadi D001, D002
    $sheet->setCellValue("C$rowNum", $row['nm_dokter']);
    $sheet->setCellValue("D$rowNum", $row['nm_poli']);
    $sheet->setCellValue("E$rowNum", $row['telp_dokter']);
    $rowNum++;
}

// Set header supaya browser mendownload file excel
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="Laporan_Data_Dokter.xlsx"');
header('Cache-Control: max-age=0');

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit;
?>
