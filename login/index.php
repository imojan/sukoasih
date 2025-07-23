<?php
session_start();
include "../koneksi.php";
?>

<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="author" content="Gipanda" />
	<title>Login - Klinik Suko Asih</title>
	<link rel="shortcut icon" type="image/png" href="img/sehat-1.png" />
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

	<style>
		body {
			background-color: #f8f9fa;
			height: 100vh;
			display: flex;
			justify-content: center;
			align-items: center;
		}

		.login-container {
			background-color: #ffffff;
			border: 1px solid #dee2e6;
			border-radius: 10px;
			padding: 2rem 3rem;
			box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
			max-width: 500px;
			width: 100%;
		}

		.clinic-header {
			text-align: center;
			margin-bottom: 30px;
		}

		.clinic-header h4 {
			font-weight: bold;
		}

		.clinic-header p {
			margin: 0;
		}

		.form-control::placeholder {
			color: #aaa;
		}

		.login-icon {
			font-size: 1.3rem;
			margin-right: 5px;
		}
		.clinic-header img {
    		max-width: 400px;
    		height: auto;
		}
	</style>
</head>

<body>
	<div class="login-container">
		<div class="clinic-header">
    		<img src="img/logoutama.png" alt="Logo Klinik" width="400" class="mb-3">
    		<h4>KLINIK UTAMA<br>“SUKO ASIH”</h4>
    		<p>Jl. Veteran No. 32, Sukoharjo (Depan SMPN 2 SKH)</p>
    		<p>Telp. (0271) 593917</p>
		</div>

		<form method="POST" class="my-login-validation" novalidate>
			<div class="mb-3">
				<label for="username" class="form-label"><i class="bi bi-person-fill login-icon"></i>Username</label>
				<input id="username" type="text" class="form-control" name="username" required placeholder="Masukkan username">
			</div>
			<div class="mb-3 position-relative">
				<label for="password" class="form-label">
					<i class="bi bi-key-fill login-icon"></i> Password
				</label>
				<input id="password" type="password" class="form-control" name="password" required placeholder="Masukkan password" style="padding-right: 40px;">
				<i class="bi bi-eye-slash position-absolute" id="togglePassword" style="top: 75%; right: 15px; transform: translateY(-50%); cursor: pointer;"></i>
			</div>
			<div class="d-grid mt-4">
				<input type="submit" value="LOGIN" name="submit" class="btn btn-primary btn-lg">
			</div>
		</form>
		<p class="text-center mt-3 text-muted" style="font-size: 0.9rem;">&copy; 2025 Klinik Suko Asih</p>

		<?php
		if (isset($_POST['submit'])) {
			$user = mysqli_real_escape_string($koneksi, trim($_POST['username']));
			$pass = mysqli_real_escape_string($koneksi, trim($_POST['password']));

			$query = mysqli_query($koneksi, "SELECT * FROM tb_user WHERE username='$user' AND password='$pass'");
			$masuk = mysqli_num_rows($query);
			if ($masuk == 0) {
				echo "<div class='alert alert-danger text-center mt-3'>Username atau password anda salah!</div>";
			} else {
				$masuk1 = mysqli_fetch_assoc($query);


				// Simpan ke session
				$_SESSION["user"] = $user;
				$_SESSION["id_user"] = $masuk1['id_user'];
				$_SESSION["nm_lengkap"] = $masuk1['nm_lengkap'];
				$_SESSION["username"] = $masuk1['username'];


				if ($masuk1["jabatan"] == 'pendaftaran') {
					$_SESSION["jabatan"] = 'pendaftaran';
				} else if ($masuk1["jabatan"] == 'dokter') {
					$_SESSION["jabatan"] = 'dokter';
				} else if ($masuk1["jabatan"] == 'pimpinan') {
					$_SESSION["jabatan"] = 'pimpinan';
				} else if ($masuk1["jabatan"] == 'apoteker') {
					$_SESSION["jabatan"] = 'apoteker';
				}
				echo "<script>alert('Anda berhasil Login!'); document.location='../index.php';</script>";
			}
		}
		?>
	</div>
	<script>
  const toggle = document.querySelector('#togglePassword');
  const password = document.querySelector('#password');

  toggle.addEventListener('click', function () {
    // toggle the type attribute
    const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
    password.setAttribute('type', type);

    // toggle the eye icon
    this.classList.toggle('bi-eye');
    this.classList.toggle('bi-eye-slash');
  });
</script>

</body>

</html>
