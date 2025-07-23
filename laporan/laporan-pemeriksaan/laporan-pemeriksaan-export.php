<?php
require '../../vendor/autoload.php';
include '../../koneksi.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$dari = $_GET['dari'] ?? '';
$sampai = $_GET['sampai'] ?? '';
$where = '';

if (!empty($dari) && !empty($sampai)) {
    $where = "WHERE a.tgl_pemeriksaan BETWEEN '$dari' AND '$sampai'";
}

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Judul dan header kolom
$sheet->setCellValue('A1', 'Laporan Data Pemeriksaan Dokter');
$sheet->setCellValue('A2', "Tanggal: $dari s.d. $sampai");
$sheet->setCellValue('A3', 'No');
$sheet->setCellValue('B3', 'ID Pemeriksaan');
$sheet->setCellValue('C3', 'Nama Pasien');
$sheet->setCellValue('D3', 'Tanggal Periksa');
$sheet->setCellValue('E3', 'Petugas Screening');
$sheet->setCellValue('F3', 'Keluhan');
$sheet->setCellValue('G3', 'Diagnosa');
$sheet->setCellValue('H3', 'Terapi');
$sheet->setCellValue('I3', 'Tindak Lanjut');

// Ambil data pemeriksaan
$query = mysqli_query($koneksi, "
SELECT 
    a.id_pemeriksaan,
    a.tgl_pemeriksaan,
    a.id_poli,
    b.nm_pasien,
    d.nm_dokter,
    e.nm_lengkap,
    f.jenis_kia,

    pu.keluhan AS keluhan_umum,
    pu.diagnosa AS diagnosa_umum,
    pu.terapi AS terapi_umum,
    pu.tindak_lanjut AS tindak_umum,

    po.keluhan AS keluhan_obgyn,
    po.diagnosa AS diagnosa_obgyn,
    po.terapi AS terapi_obgyn,
    po.tindak_lanjut AS tindak_obgyn,

    pk.anamnesa AS keluhan_kb,
    pk.diagnosa AS diagnosa_kb,
    pk.terapi AS terapi_kb,
    pk.tindak_lanjut AS tindak_kb,

    pg.S AS keluhan_gigi,
    pg.A AS diagnosa_gigi,
    pg.P AS terapi_gigi,
    pg.tindak_lanjut AS tindak_gigi,

    pi.diagnosa AS diagnosa_imunisasi,
    pi.terapi AS terapi_imunisasi,
    pi.tindak_lanjut AS tindak_imunisasi

FROM tb_pemeriksaan a
JOIN tb_pendaftaran f ON a.id_pendaftaran = f.id_pendaftaran
JOIN tb_pasien b ON a.no_rm = b.no_rm
JOIN tb_dokter d ON f.id_dokter = d.id_dokter
JOIN tb_user e ON a.id_user = e.id_user
LEFT JOIN tb_pemeriksaan_umum pu ON a.id_pemeriksaan = pu.id_pemeriksaan
LEFT JOIN tb_pemeriksaan_obgyn po ON a.id_pemeriksaan = po.id_pemeriksaan
LEFT JOIN tb_pemeriksaan_kb pk ON a.id_pemeriksaan = pk.id_pemeriksaan
LEFT JOIN tb_pemeriksaan_gigi pg ON a.id_pemeriksaan = pg.id_pemeriksaan
LEFT JOIN tb_pemeriksaan_imunisasi pi ON a.id_pemeriksaan = pi.id_pemeriksaan
$where
ORDER BY a.tgl_pemeriksaan ASC
");

$rowNum = 4;
$no = 1;

while ($row = mysqli_fetch_assoc($query)) {
    $tanggal = date('d-m-Y', strtotime($row['tgl_pemeriksaan']));
    $id_poli = $row['id_poli'];

    // Tentukan data berdasarkan jenis poli
    switch ($id_poli) {
        case '1': // obgyn
            $keluhan = $row['keluhan_obgyn'];
            $diagnosa = $row['diagnosa_obgyn'];
            $terapi = $row['terapi_obgyn'];
            $tindak_lanjut = $row['tindak_obgyn'];
            break;
        case '2': // umum
            $keluhan = $row['keluhan_umum'];
            $diagnosa = $row['diagnosa_umum'];
            $terapi = $row['terapi_umum'];
            $tindak_lanjut = $row['tindak_umum'];
            break;
        case '3': // gigi
            $keluhan = $row['keluhan_gigi'];
            $diagnosa = $row['diagnosa_gigi'];
            $terapi = $row['terapi_gigi'];
            $tindak_lanjut = $row['tindak_gigi'];
            break;
        case '4': 
            $jenis_kia = $row['jenis_kia'];
            switch ($jenis_kia) {
            case 'Obgyn': // obgyn
                $keluhan = $row['keluhan_obgyn'];
                $diagnosa = $row['diagnosa_obgyn'];
                $terapi = $row['terapi_obgyn'];
                $tindak_lanjut = $row['tindak_obgyn'];
                break;
            case 'Umum': // umum
                $keluhan = $row['keluhan_umum'];
                $diagnosa = $row['diagnosa_umum'];
                $terapi = $row['terapi_umum'];
                $tindak_lanjut = $row['tindak_umum'];
                break;
            case 'Imunisasi': // imunisasi
                $keluhan = '-';
                $diagnosa = $row['diagnosa_imunisasi'];
                $terapi = $row['terapi_imunisasi'];
                $tindak_lanjut = $row['tindak_imunisasi'];
                break;
            case 'KB': // KB
                $keluhan = $row['keluhan_kb'];
                $diagnosa = $row['diagnosa_kb'];
                $terapi = $row['terapi_kb'];
                $tindak_lanjut = $row['tindak_kb'];
                break;
        default:
            $keluhan = $diagnosa = $terapi = $tindak_lanjut = '-';
            break;
        }
    }

    // Interpretasi tindak lanjut
    $statusText = match ($tindak_lanjut) {
        'Selesai' => 'Selesai',
        'Kontrol' => 'Kontrol',
        'Rujuk' => 'Rujuk',
        'Rawat Inap' => 'Rawat Inap',
        default => 'Tidak Diketahui',
    };

    // Isi data Excel
    $sheet->setCellValue("A$rowNum", $no++);
    $sheet->setCellValue("B$rowNum", $row['id_pemeriksaan']);
    $sheet->setCellValue("C$rowNum", $row['nm_pasien']);
    $sheet->setCellValue("D$rowNum", $tanggal);
    $sheet->setCellValue("E$rowNum", $row['nm_lengkap']);
    $sheet->setCellValue("F$rowNum", $keluhan);
    $sheet->setCellValue("G$rowNum", $diagnosa);
    $sheet->setCellValue("H$rowNum", $terapi);
    $sheet->setCellValue("I$rowNum", $statusText);

    $rowNum++;
}

// Set header agar file terunduh sebagai Excel
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="Laporan_Data_Pemeriksaan.xlsx"');
header('Cache-Control: max-age=0');

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit;
?>
