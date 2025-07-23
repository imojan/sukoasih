<?php
require '../../vendor/autoload.php';
include '../../koneksi.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Header kolom
$sheet->setCellValue('A1', 'No');
$sheet->setCellValue('B1', 'Kode Daftar');
$sheet->setCellValue('C1', 'Nomor Rekam Medis');
$sheet->setCellValue('D1', 'Nama Pasien');
$sheet->setCellValue('E1', 'Tanggal Daftar');
$sheet->setCellValue('F1', 'Waktu Daftar');
$sheet->setCellValue('G1', 'Poliklinik');
$sheet->setCellValue('H1', 'Dokter');
$sheet->setCellValue('I1', 'Status');

// Cek filter tanggal dari GET
$where = '';
if (!empty($_GET['dari']) && !empty($_GET['sampai'])) {
    $dari = $_GET['dari'];
    $sampai = $_GET['sampai'];
    $where = "WHERE DATE(p.tgl_pendaftaran) BETWEEN '$dari' AND '$sampai'";
}

// Ambil data
$data = mysqli_query($koneksi, "
    SELECT 
        p.id_pendaftaran,
        p.no_rm,
        p.id_dokter,
        p.id_poli,
        p.status AS status_pendaftaran,
        ps.nm_pasien,
        ps.nm_pj,
        d.nm_dokter,
        l.nm_poli,
        p.tgl_pendaftaran
    FROM tb_pendaftaran p
    JOIN tb_pasien ps ON p.no_rm = ps.no_rm
    JOIN tb_dokter d ON p.id_dokter = d.id_dokter
    JOIN tb_poli l ON p.id_poli = l.id_poli
    $where
    ORDER BY p.id_pendaftaran ASC
");

$rowNum = 2;
$no = 1;

while ($row = mysqli_fetch_assoc($data)) {
    $tanggal = date('d-m-Y', strtotime($row['tgl_pendaftaran']));
    $waktu   = date('H:i', strtotime($row['tgl_pendaftaran']));
    $status  = $row['status_pendaftaran'];

    switch ($status) {
        case 0:
            $statusText = 'Pasien Lama';
            break;
        case 1:
            $statusText = 'Pasien Baru';
            break;
        default:
            $statusText = 'Status Tidak Diketahui';
            break;
    }

    $sheet->setCellValue("A$rowNum", $no++);
    $sheet->setCellValue("B$rowNum", $row['id_pendaftaran']);
    $sheet->setCellValue("C$rowNum", str_pad($row['no_rm'], 6, '0', STR_PAD_LEFT));
    $sheet->setCellValue("D$rowNum", $row['nm_pasien']);
    $sheet->setCellValue("E$rowNum", $tanggal);
    $sheet->setCellValue("F$rowNum", $waktu);
    $sheet->setCellValue("G$rowNum", $row['nm_poli']);
    $sheet->setCellValue("H$rowNum", $row['nm_dokter']);
    $sheet->setCellValue("I$rowNum", $statusText);

    $rowNum++;
}

// Set header supaya browser mendownload file excel
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="Laporan_Data_Pendaftaran.xlsx"');
header('Cache-Control: max-age=0');

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit;
?>
