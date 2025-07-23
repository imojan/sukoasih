<?php
include '../koneksi.php';

$resep_lama = [];

$cek_resep = mysqli_query($koneksi, "SELECT id_resep FROM tb_resep WHERE id_pemeriksaan = '$id_pemeriksaan'");
if (mysqli_num_rows($cek_resep) > 0) {
    $row = mysqli_fetch_assoc($cek_resep);
    $id_resep = $row['id_resep'];

    $queryResepLama = "SELECT 
        dr.id_obat,
        dr.cara_minum,
        dr.jumlah,
        o.nm_obat,
        o.bentuk_obat,
        o.stok_obat
    FROM tb_resep r
    JOIN tb_detail_resep dr ON r.id_resep = dr.id_resep
    JOIN tb_obat o ON dr.id_obat = o.id_obat
    WHERE r.id_resep = '$id_resep'";
    
    $resultResepLama = mysqli_query($koneksi, $queryResepLama);
    while ($row = mysqli_fetch_assoc($resultResepLama)) {
        $resep_lama[] = [
            'id_obat' => $row['id_obat'],
            'nama' => $row['nm_obat'],
            'bentuk' => $row['bentuk_obat'],
            'cara' => $row['cara_minum'],
            'jumlah' => $row['jumlah']
        ];
    }
} else {
    $id_resep = '';
}

// Generate ID Resep
$tanggal = date("Ymd");
$query = mysqli_query($koneksi, "SELECT MAX(id_resep) AS max_id FROM tb_resep WHERE id_resep LIKE 'RO$tanggal%'");
$data = mysqli_fetch_assoc($query);
$kodeTerakhir = $data['max_id'] ?? 'RO' . $tanggal . '000';
$urutan = (int)substr($kodeTerakhir, -3) + 1;
$id_resep = 'RO' . $tanggal . "-" . str_pad($urutan, 3, '0', STR_PAD_LEFT);

?>

<div class="card mb-4">
    <div class="card-header">INPUT RESEP OBAT</div>
    <div class="card-body">
        <form action="../data-resep/resep_proses.php" method="POST">
            <input type="hidden" name="id_pemeriksaan" value="<?= $id_pemeriksaan ?>">
            <input type="hidden" name="id_resep" value="<?= $id_resep ?>">
            <input type="hidden" name="id_pendaftaran" value="<?= $id_pendaftaran ?>">
            <!-- FORM PENCARIAN OBAT -->
            <div class="form-group row">
                <div class="col-sm-4">
                    <input type="text" id="input-keyword" class="form-control" placeholder="Cari nama obat...">
                </div>
                <div class="col-sm-2">
                    <button type="button" id="btn-cari-obat" class="btn btn-primary">Cari</button>
                </div>
            </div>
            <!-- TABEL OBAT -->
            <div class="card mb-4">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered text-center">
                            <thead class="thead-light">
                                <tr>
                                    <th>Nama Obat</th>
                                    <th>Bentuk</th>
                                    <th>Stok</th>
                                    <th>Expired</th>
                                    <th>Pilih</th>
                                </tr>
                            </thead>
                            <tbody id="tabel-hasil-obat">
                                <tr><td colspan='5' class='text-center text-muted'>🔍 Silakan cari obat terlebih dahulu.</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!-- Tambah Obat ke Resep -->
            <div class="form-group row">
                <div class="col-sm-4">
                    <?php $ambil = mysqli_query($koneksi, "SELECT * FROM tb_resep ORDER BY id_resep DESC LIMIT 1"); ?>
                    <?php $data = $ambil->fetch_assoc(); ?>
                    <label>ID Resep Obat</label>
                    <input type="text" class="form-control" name="id_resep" value="<?= $id_resep; ?>" required>
                </div>
                <div class="col-sm-4">
                    <label for="id-obat">Nama Obat</label>
                    <input type="text" class="form-control" id="nama-obat" readonly>
                </div>
                <div class="col-sm-4">
                    <label for="bentuk-obat">Bentuk Obat</label>
                    <input type="text" class="form-control" id="bentuk-obat" readonly>
                </div>
            </div>
            <div class="mb-3">
                <label for="cara-minum">Cara Minum</label>
                <input type="text" class="form-control" id="cara-minum" placeholder="Contoh: 3x1 setelah makan">
            </div>
            <div class="mb-3">
                <label for="jumlah-obat">Jumlah Obat</label>
                <input type="number" class="form-control" id="jumlah-obat" placeholder="Contoh: 10">
            </div>
            
            <button class="btn btn-success" id="btn-tambah-obat">Tambah Obat</button>

            <!-- Daftar Obat yang Sudah Ditambahkan -->
            <div class="card-body">
                <div class="table-responsive">
                            <table class="table table-bordered text-center">
                        <thead>
                            <tr>
                                <th>Nama Obat</th>
                                <th>Bentuk Obat</th>
                                <th>Cara Minum</th>
                                <th>Jumlah</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="daftar-obat-dipilih">
                            <tr><td colspan="5">Belum ada obat ditambahkan</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <input type="hidden" id="id-obat">
            <input type="hidden" id="stok-obat">
            <input type="hidden" name="id_pendaftaran" id="id_pendaftaran">
            <div id="obat-container"></div>
            <button class="btn btn-success font-weight-bold px-3 mr-2" name="simpan_resep">
                <i class="fas fa-save"></i> Simpan
            </button>
        </form>
    </div>
</div>

<script>
    const daftarObatDipilih = <?= json_encode($resep_lama); ?>;

    document.addEventListener("DOMContentLoaded", function () {
        renderTabelObat();

        document.getElementById("btn-cari-obat").addEventListener("click", function () {
            const keyword = document.getElementById("input-keyword").value.trim();

        if (!keyword) {
            alert("Masukkan kata kunci obat!");
            return;
        }

        fetch('../data-resep/cari_obat.php?keyword=' + keyword)
            .then(response => response.text())
            .then(data => {
                document.getElementById("tabel-hasil-obat").innerHTML = data;
            })
            .catch(error => {
                console.error("Gagal fetch:", error);
                document.getElementById("tabel-hasil-obat").innerHTML = `
                    <tr><td colspan="5" class="text-danger text-center">❌ Terjadi kesalahan saat mencari obat.</td></tr>`;
            });
        });

        document.getElementById("btn-tambah-obat").addEventListener("click", function (e) {
            e.preventDefault();
            
            const namaObat = document.getElementById("nama-obat").value.trim();
            const bentukObat = document.getElementById("bentuk-obat").value.trim();
            const caraMinum = document.getElementById("cara-minum").value.trim();
            const jumlahObat = document.getElementById("jumlah-obat").value.trim();
            const stokObat = document.getElementById("stok-obat").value.trim();
            const idObat = document.getElementById("id-obat").value;        

            if (!idObat || !namaObat || !jumlahObat || !caraMinum || !bentukObat) {
                alert("Semua field harus diisi terlebih dahulu!");
                return;
            }

            if (parseInt(jumlahObat) > parseInt(stokObat)) {
                alert("Jumlah obat melebihi stok yang tersedia!");
                return;
            }

            daftarObatDipilih.push({
                id_obat: idObat,
                nama: namaObat,
                bentuk: bentukObat,
                cara: caraMinum,
                jumlah: jumlahObat
            });

            renderTabelObat();

            document.getElementById("stok-obat").value = '';
            document.getElementById("id-obat").value = '';
            document.getElementById("nama-obat").value = '';
            document.getElementById("bentuk-obat").value = '';
            document.getElementById("cara-minum").value = '';
            document.getElementById("jumlah-obat").value = '';
        });
    });

    function renderTabelObat() {
        const tbody = document.getElementById("daftar-obat-dipilih");
        tbody.innerHTML = "";

        if (daftarObatDipilih.length === 0) {
            tbody.innerHTML = "<tr><td colspan='5'>Belum ada obat ditambahkan</td></tr>";
            return;
        }

        daftarObatDipilih.forEach((obat, index) => {
            const tr = document.createElement("tr");
            tr.innerHTML = `
                <td>
                    ${obat.nama} (ID: ${obat.id_obat})
                    <input type="hidden" name="id_obat[]" value="${obat.id_obat}">
                    <input type="hidden" name="cara_minum[]" value="${obat.cara}">
                    <input type="hidden" name="jumlah[]" value="${obat.jumlah}">
                </td>
                <td>${obat.bentuk}</td>
                <td>${obat.cara}</td>
                <td>${obat.jumlah}</td>
                <td>
                    <button type="button" class="btn btn-warning btn-sm" onclick="editBarisIni(this)">Edit</button>
                    <button type="button" class="btn btn-danger btn-sm" onclick="hapusBarisIni(this)">Hapus</button>
                </td>
            `;
            tbody.appendChild(tr);
        });
    }

    function hapusBarisIni(el) {
        const row = el.closest('tr');
        const index = Array.from(row.parentNode.children).indexOf(row);
        daftarObatDipilih.splice(index, 1);
        renderTabelObat();
    }

    function editBarisIni(el) {
        const row = el.closest('tr');
        const index = Array.from(row.parentNode.children).indexOf(row);
        const obatData = daftarObatDipilih[index];

        document.getElementById("id-obat").value = obatData.id_obat;
        document.getElementById("nama-obat").value = obatData.nama;
        document.getElementById("bentuk-obat").value = obatData.bentuk;
        document.getElementById("cara-minum").value = obatData.cara;
        document.getElementById("jumlah-obat").value = obatData.jumlah;

        daftarObatDipilih.splice(index, 1);
        renderTabelObat();
    }

    function pilihObat(idObat, namaObat, bentukObat, stokObat) {
        document.getElementById("id-obat").value = idObat;
        document.getElementById("nama-obat").value = namaObat;
        document.getElementById("bentuk-obat").value = bentukObat;
        document.getElementById("stok-obat").value = stokObat;
    }
    
    </script>
