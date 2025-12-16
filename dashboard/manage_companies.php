<?php
session_start();
include('../config/database.php');

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header('Location: ../login.php');
    exit;
}

$companies = mysqli_query($conn, "SELECT * FROM companies");
?>

<?php
$page_title = "Kelola Perusahaan | JobFair Polmed";
$header_path = "../";
include('../includes/header.php');
?>

<nav class="navbar navbar-expand-lg navbar-light bg-white" style="box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
  <div class="container">
    <a class="navbar-brand fw-bold" href="admin.php" style="color: #6B3F69;"><i class="bi bi-shield-check me-2"></i>Admin Panel</a>
    <div class="d-flex">
      <a href="admin.php" class="btn btn-sm" style="background: #A376A2; border-color: #A376A2; color: white; border-radius: 25px; padding: 8px 20px;">
        <i class="bi bi-arrow-left me-1"></i>Kembali
      </a>
    </div>
  </div>
</nav>

<div class="container py-5">
  <h3 class="mb-4 text-center">
    <i class="bi bi-building me-2"></i>Daftar Perusahaan Terdaftar
  </h3>

  <div class="table-responsive">
    <table class="table table-striped align-middle">
      <thead class="table-success">
        <tr>
          <th>Nama Perusahaan</th>
          <th>Deskripsi</th>
          <th>Email Pengelola</th>
        </tr>
      </thead>
      <tbody>
        <?php while ($c = mysqli_fetch_assoc($companies)) { 
          $user = mysqli_fetch_assoc(mysqli_query($conn, "SELECT email FROM users WHERE user_id='".$c['user_id']."'"));
        ?>
        <tr>
          <td><strong><?= htmlspecialchars($c['company_name']); ?></strong></td>
          <td><?= substr($c['description'], 0, 80) . '...'; ?></td>
          <td><?= htmlspecialchars($user['email'] ?? '-'); ?></td>
        </tr>
        <?php } ?>
      </tbody>
    </table>
  </div>

  <div class="text-center mt-4">
    <a href="admin.php" class="btn btn-secondary">
      <i class="bi bi-arrow-left me-1"></i>Kembali ke Dashboard
    </a>
  </div>
</div>

<?php include('../includes/footer.php'); ?>
