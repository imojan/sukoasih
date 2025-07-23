<?php
include '../koneksi.php';
header('Content-Type: application/json');

if (isset($_POST['keyword'])) {
    $keyword = mysqli_real_escape_string($koneksi, $_POST['keyword']);

    $query = mysqli_query($koneksi, 
        "SELECT * FROM tb_pasien 
        WHERE nm_pasien LIKE '%$keyword%' OR nik LIKE '%$keyword%' OR LPAD(no_rm, 6, '0') LIKE '%$keyword%' 
        LIMIT 1");

    if (!$query) {
        echo json_encode(['status' => 'error', 'msg' => mysqli_error($koneksi)]);
        exit;
    }

    if ($data = mysqli_fetch_assoc($query)) {
        echo json_encode([
            'status' => 'success',
            'no_rm' => str_pad($data['no_rm'], 6, '0', STR_PAD_LEFT),
            'nm_pasien' => $data['nm_pasien'],
            'tgl_lahir' => $data['tgl_lahir']
        ]);
    } else {
        echo json_encode(['status' => 'error']);
    }
} else {
    echo json_encode(['status' => 'error']);
}
?>
