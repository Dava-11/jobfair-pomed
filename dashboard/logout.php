<?php
session_start();

// Hapus semua sesi
session_unset();
session_destroy();

// Arahkan kembali ke halaman utama (landing page)
header('Location: ../index.php');
exit;
?>
