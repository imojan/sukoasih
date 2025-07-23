<?php
require '../../vendor/autoload.php';
include '../../koneksi.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Header kolom
$sheet->setCellValue('A1', 'No');
$sheet->setCellValue('B1', 'ID Obat');
$sheet->setCellValue('C1', 'Nama Obat');
$sheet->setCellValue('D1', 'Bentuk Obat');
$sheet->setCellValue('E1', 'Stok Obat');
$sheet->setCellValue('F1', 'Kadaluarsa');
$sheet->setCellValue('G1', 'Status');

// Ambil data
$data = mysqli_query($koneksi, "SELECT * FROM tb_obat ORDER BY id_obat ASC");
$rowNum = 2;
$no = 1;

while ($row = mysqli_fetch_assoc($data)) {
$tanggal = date('d-m-Y', strtotime($row['exp_obat']));

    $sheet->setCellValue("A$rowNum", $no++);
    $sheet->setCellValue("B$rowNum", 'O' . str_pad($row['id_obat'], 3, '0', STR_PAD_LEFT)); // ✅ jadi D001, D002
    $sheet->setCellValue("C$rowNum", $row['nm_obat']);
    $sheet->setCellValue("D$rowNum", $row['bentuk_obat']);
    $sheet->setCellValue("E$rowNum", $row['stok_obat']);
    $sheet->setCellValue("F$rowNum", $tanggal);
    $status = ($row['stok_obat'] <= 0) ? 'Kosong' : 'Tersedia = ' . $row['stok_obat'];
    $sheet->setCellValue("G$rowNum", $status);  // Status stok obat
    $rowNum++;
}

// Set header supaya browser mendownload file excel
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="Laporan_Data_Obat.xlsx"');
header('Cache-Control: max-age=0');

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit;
?>
