<?php
require '../../vendor/autoload.php';
include '../../koneksi.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Header kolom
$sheet->setCellValue('A1', 'No');
$sheet->setCellValue('B1', 'ID Poliklinik');
$sheet->setCellValue('C1', 'Nama Poliklinik');

// Ambil data
$data = mysqli_query($koneksi, "SELECT * FROM tb_poli ORDER BY id_poli ASC");
$rowNum = 2;
$no = 1;

while ($row = mysqli_fetch_assoc($data)) {
    $sheet->setCellValue("A$rowNum", $no++);
    $sheet->setCellValue("B$rowNum", str_pad($row['id_poli'], 6, '0', STR_PAD_LEFT));
    $sheet->setCellValue("C$rowNum", $row['nm_poli']);
    $rowNum++;
}

// Set header supaya browser mendownload file excel
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="Laporan_Data_Poli.xlsx"');
header('Cache-Control: max-age=0');

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit;
?>
