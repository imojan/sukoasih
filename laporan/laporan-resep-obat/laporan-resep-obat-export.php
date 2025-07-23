<?php
require '../../vendor/autoload.php';
include '../../koneksi.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$where = "";
if (isset($_GET['dari']) && isset($_GET['sampai'])) {
    $dari = $_GET['dari'];
    $sampai = $_GET['sampai'];
    $where = "WHERE p.tgl_pemeriksaan BETWEEN '$dari' AND '$sampai'";
}

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

$sheet->setCellValue('A1', 'No');
$sheet->setCellValue('B1', 'ID Resep');
$sheet->setCellValue('C1', 'Pasien');
$sheet->setCellValue('D1', 'Tanggal');
$sheet->setCellValue('E1', 'Diagnosa');
$sheet->setCellValue('F1', 'Terapi');
$sheet->setCellValue('G1', 'Resep Obat');
$sheet->setCellValue('H1', 'Jumlah Obat');

$query = mysqli_query($koneksi, "
    SELECT 
        r.id_resep,
        p.tgl_pemeriksaan,
        ps.nm_pasien,
        l.id_poli,
        f.jenis_kia,

        pu.diagnosa AS diagnosa_umum,
        pu.terapi AS terapi_umum,

        po.diagnosa AS diagnosa_obgyn,
        po.terapi AS terapi_obgyn,

        pk.diagnosa AS diagnosa_kb,
        pk.terapi AS terapi_kb,

        pg.O AS diagnosa_gigi,
        pg.P AS terapi_gigi,

        pi.diagnosa AS diagnosa_imunisasi,
        pi.terapi AS terapi_imunisasi,

        GROUP_CONCAT(CONCAT(o.nm_obat, ' (', d.cara_minum, ')') SEPARATOR '\n') AS daftar_obat,
        GROUP_CONCAT(CONCAT(d.jumlah, ' ', o.bentuk_obat) SEPARATOR '\n') AS jumlah_obat

    FROM tb_resep r
    JOIN tb_pemeriksaan p ON r.id_pemeriksaan = p.id_pemeriksaan
    JOIN tb_pasien ps ON p.no_rm = ps.no_rm
    JOIN tb_pendaftaran f ON p.id_pendaftaran = f.id_pendaftaran
    JOIN tb_poli l ON p.id_poli = l.id_poli
    JOIN tb_detail_resep d ON r.id_resep = d.id_resep
    JOIN tb_obat o ON d.id_obat = o.id_obat

    LEFT JOIN tb_pemeriksaan_umum pu ON p.id_pemeriksaan = pu.id_pemeriksaan
    LEFT JOIN tb_pemeriksaan_obgyn po ON p.id_pemeriksaan = po.id_pemeriksaan
    LEFT JOIN tb_pemeriksaan_kb pk ON p.id_pemeriksaan = pk.id_pemeriksaan
    LEFT JOIN tb_pemeriksaan_gigi pg ON p.id_pemeriksaan = pg.id_pemeriksaan
    LEFT JOIN tb_pemeriksaan_imunisasi pi ON p.id_pemeriksaan = pi.id_pemeriksaan

    $where
    GROUP BY r.id_resep
    ORDER BY r.id_resep ASC
");

$rowNum = 2;
$no = 1;

while ($row = mysqli_fetch_assoc($query)) {
    $tanggal = date('d-m-Y', strtotime($row['tgl_pemeriksaan']));

    // Tentukan diagnosa dan terapi berdasarkan poli
    $diagnosa = '-';
    $terapi = '-';

    switch ($row['id_poli']) {
        case '1': // obgyn
            $diagnosa = $row['diagnosa_obgyn'] ?? '-';
            $terapi   = $row['terapi_obgyn'] ?? '-';
            break;
        case '2': // umum
            $diagnosa = $row['diagnosa_umum'] ?? '-';
            $terapi   = $row['terapi_umum'] ?? '-';
            break;
        case '3': // gigi
            $diagnosa = $row['diagnosa_gigi'] ?? '-';
            $terapi   = $row['terapi_gigi'] ?? '-';
            break;
        case '4': // kia (perlu lihat jenis kia)
            $jenis = strtolower($row['jenis_kia']);
            if ($jenis == 'umum') {
                $diagnosa = $row['diagnosa_umum'] ?? '-';
                $terapi   = $row['terapi_umum'] ?? '-';
            } elseif ($jenis == 'obgyn') {
                $diagnosa = $row['diagnosa_obgyn'] ?? '-';
                $terapi   = $row['terapi_obgyn'] ?? '-';
            } elseif ($jenis == 'kb') {
                $diagnosa = $row['diagnosa_kb'] ?? '-';
                $terapi   = $row['terapi_kb'] ?? '-';
            } elseif ($jenis == 'imunisasi') {
                $diagnosa = $row['diagnosa_imunisasi'] ?? '-';
                $terapi   = $row['terapi_imunisasi'] ?? '-';
            }
            break;
    }

    $daftarObat = explode("\n", $row['daftar_obat']);
    $jumlahObat = explode("\n", $row['jumlah_obat']);

    $daftarObatNumbered = [];
    $jumlahObatNumbered = [];

    foreach ($daftarObat as $index => $obat) {
        $noItem = $index + 1;
        $daftarObatNumbered[] = "$noItem. $obat";
        $jumlahObatNumbered[] = "$noItem. " . ($jumlahObat[$index] ?? '-');
    }

    $sheet->setCellValue("A$rowNum", $no++);
    $sheet->setCellValue("B$rowNum", $row['id_resep']);
    $sheet->setCellValue("C$rowNum", $row['nm_pasien']);
    $sheet->setCellValue("D$rowNum", $tanggal);
    $sheet->setCellValue("E$rowNum", $diagnosa);
    $sheet->setCellValue("F$rowNum", $terapi);
    $sheet->setCellValue("G$rowNum", implode("\n", $daftarObatNumbered));
    $sheet->getStyle("G$rowNum")->getAlignment()->setWrapText(true);

    $sheet->setCellValue("H$rowNum", implode("\n", $jumlahObatNumbered));
    $sheet->getStyle("H$rowNum")->getAlignment()->setWrapText(true);

    $rowNum++;
}

// Set header agar file langsung diunduh
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="Laporan_Data_Resep_Obat.xlsx"');
header('Cache-Control: max-age=0');

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit;
?>
