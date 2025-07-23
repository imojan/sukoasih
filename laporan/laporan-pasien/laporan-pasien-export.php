<?php
require '../../vendor/autoload.php';
include '../../koneksi.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Header kolom
$sheet->setCellValue('A1', 'No');
$sheet->setCellValue('B1', 'No. RM');
$sheet->setCellValue('C1', 'NIK');
$sheet->setCellValue('D1', 'Nama Pasien');
$sheet->setCellValue('E1', 'Jenis Kelamin');
$sheet->setCellValue('F1', 'Tgl. Lahir');
$sheet->setCellValue('G1', 'Usia');
$sheet->setCellValue('H1', 'Alamat');
$sheet->setCellValue('I1', 'Telepon');
$sheet->setCellValue('J1', 'Alergi Obat');
$sheet->setCellValue('K1', 'Penanggung Jawab');
$sheet->setCellValue('L1', 'Telp PJ');

// Ambil data
$data = mysqli_query($koneksi, "SELECT * FROM tb_pasien ORDER BY no_rm ASC");
$rowNum = 2;
$no = 1;

while ($row = mysqli_fetch_assoc($data)) {
    $tglLahir = $row['tgl_lahir'];
    if (!empty($tglLahir)) {
        $lahir = new DateTime($tglLahir);
        $sekarang = new DateTime();
        $diff = $sekarang->diff($lahir);
        $usia = $diff->y . " thn " . $diff->m . " bln";
    } else {
        $usia = "-";
    }

    $sheet->setCellValue("A$rowNum", $no++);
    $sheet->setCellValue("B$rowNum", str_pad($row['no_rm'], 6, '0', STR_PAD_LEFT));
    $sheet->setCellValue("C$rowNum", $row['nik']);
    $sheet->setCellValue("D$rowNum", $row['nm_pasien']);
    $sheet->setCellValue("E$rowNum", $row['jenis_kelamin']);
    $sheet->setCellValue("F$rowNum", $row['tgl_lahir']);
    $sheet->setCellValue("G$rowNum", $usia); // ✔️ ini yang benar
    $sheet->setCellValue("H$rowNum", $row['alamat']);
    $sheet->setCellValue("I$rowNum", $row['telp_psn']);
    $sheet->setCellValue("J$rowNum", $row['alergi_obat']);
    $sheet->setCellValue("K$rowNum", $row['nm_pj']);
    $sheet->setCellValue("L$rowNum", $row['telp_pj']);
    $rowNum++;
}

// Set header supaya browser mendownload file excel
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="Laporan_Data_Pasien.xlsx"');
header('Cache-Control: max-age=0');

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit;
?>
