<?php 
session_start();

// Kosongkan semua variabel session
$_SESSION = [];

// Hapus cookie session (opsional tapi direkomendasikan)
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Hancurkan session
session_destroy();

// Tampilkan pesan dan redirect
echo "<script>
        alert('Anda telah Logout!');
        window.location.href = 'index.php';
      </script>";
?>
