<?php
session_start();
include('../config/database.php');

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header('Location: ../login.php');
    exit;
}

$jobs = mysqli_query($conn, "SELECT j.*, c.company_name 
                             FROM jobs j 
                             JOIN companies c ON j.company_id = c.company_id 
                             ORDER BY j.created_at DESC");
?>

<?php
$page_title = "Kelola Lowongan | JobFair Polmed";
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
    <i class="bi bi-briefcase me-2"></i>Daftar Semua Lowongan
  </h3>

  <div class="table-responsive">
    <table class="table table-striped align-middle">
      <thead class="table-info">
        <tr>
          <th>Judul</th>
          <th>Perusahaan</th>
          <th>Lokasi</th>
          <th>Deadline</th>
          <th>Tanggal Dibuat</th>
        </tr>
      </thead>
      <tbody>
        <?php while ($j = mysqli_fetch_assoc($jobs)) { ?>
        <tr>
          <td><strong><?= htmlspecialchars($j['title']); ?></strong></td>
          <td><?= htmlspecialchars($j['company_name']); ?></td>
          <td><?= htmlspecialchars($j['location'] ?? '-'); ?></td>
          <td><?= date('d M Y', strtotime($j['deadline'])); ?></td>
          <td><?= date('d M Y', strtotime($j['created_at'])); ?></td>
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
