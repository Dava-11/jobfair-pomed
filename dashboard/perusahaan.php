<?php
session_start();
include('../config/database.php');

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'perusahaan') {
    header('Location: ../login.php');
    exit;
}

$user_id = $_SESSION['user_id'];

// Ambil company_id dari tabel companies
$company = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM companies WHERE user_id='$user_id'"));
$company_id = $company['company_id'];

// Ambil semua lowongan milik perusahaan ini
$result = mysqli_query($conn, "SELECT * FROM jobs WHERE company_id='$company_id' ORDER BY created_at DESC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Dashboard Perusahaan | JobFair Polmed</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
  <style>
    :root {
      /* Warna ungu baru mengikuti tema global */
      --primary-gradient: linear-gradient(135deg, #7B2FA0 0%, #5C1F78 100%);
      --secondary-color: #E5CFF5;
      --accent-color: #4A1A63;
    }
    
    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background-color: #fcf7e3;
    }
    
    .navbar {
      background: white !important;
      box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }
    
    .job-card {
      border: none;
      border-radius: 15px;
      transition: all 0.3s ease;
      box-shadow: 0 5px 15px rgba(0,0,0,0.08);
      height: 100%;
    }
    
    .job-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 10px 30px rgba(0,0,0,0.15);
    }
    
    .section-title {
      font-size: 2rem;
      font-weight: 700;
      margin-bottom: 2rem;
      background: var(--primary-gradient);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
    }
    
    .btn-add {
      border-radius: 25px;
      padding: 10px 25px;
      font-weight: 600;
    }
    
    .btn-logout {
      border-radius: 25px;
      padding: 8px 20px;
    }
  </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-light bg-white" style="box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
  <div class="container">
    <a class="navbar-brand fw-bold" href="perusahaan.php" style="color: #5C1F78;"><i class="bi bi-building me-2"></i>JobFair Polmed</a>
    <div class="d-flex gap-2">
      <a href="edit_profile.php" class="btn btn-sm" style="background: #7B2FA0; border-color: #7B2FA0; color: white; border-radius: 25px; padding: 8px 20px;">
        <i class="bi bi-person-circle me-1"></i>Profil
      </a>
      <a href="add_job.php" class="btn btn-sm btn-add" style="background: #7B2FA0; border-color: #7B2FA0; color: white; border-radius: 25px; padding: 10px 25px; font-weight: 600;">
        <i class="bi bi-plus-circle me-1"></i>Tambah Lowongan
      </a>
      <a href="logout.php" class="btn btn-outline-secondary btn-sm btn-logout" style="border-color: #7B2FA0; color: #7B2FA0; border-radius: 25px; padding: 8px 20px;" onclick="return confirmLogout()">
        <i class="bi bi-box-arrow-right me-1"></i>Keluar
      </a>
    </div>
  </div>
</nav>

<div class="container py-5">
  <h2 class="text-center section-title mb-5">
    <i class="bi bi-briefcase me-2"></i>Lowongan Kerja Saya
  </h2>

  <?php if (mysqli_num_rows($result) > 0) { ?>
  <div class="row g-4">
    <?php while ($row = mysqli_fetch_assoc($result)) { ?>
    <div class="col-lg-4 col-md-6">
      <div class="card job-card h-100">
        <div class="card-body">
          <h5 class="card-title fw-bold mb-3"><?= htmlspecialchars($row['title']); ?></h5>
          <p class="text-secondary small mb-2">
            <?= substr($row['description'], 0, 100) . '...'; ?>
          </p>
          <div class="mb-3">
            <p class="small text-muted mb-1">
              <i class="bi bi-geo-alt me-1"></i><?= htmlspecialchars($row['location']); ?>
            </p>
            <p class="small text-muted mb-0">
              <i class="bi bi-calendar-event me-1"></i>Deadline: <?= date('d M Y', strtotime($row['deadline'])); ?>
            </p>
          </div>
          <div class="d-flex gap-2">
            <a href="view_applicants.php?id=<?= $row['job_id']; ?>" class="btn btn-primary flex-fill" style="background: #7B2FA0; border-color: #7B2FA0;">
              <i class="bi bi-people me-1"></i>Pelamar
            </a>
            <a href="edit_job.php?id=<?= $row['job_id']; ?>" class="btn btn-outline-primary" style="border-color: #7B2FA0; color: #7B2FA0;">
              <i class="bi bi-pencil"></i>
            </a>
            <a href="delete_job.php?id=<?= $row['job_id']; ?>" class="btn btn-outline-danger" onclick="return confirm('Yakin ingin menghapus lowongan ini?')">
              <i class="bi bi-trash"></i>
            </a>
          </div>
        </div>
      </div>
    </div>
    <?php } ?>
  </div>
  <?php } else { ?>
    <div class="alert alert-info text-center">
      <i class="bi bi-info-circle me-2"></i>Belum ada lowongan yang Anda unggah.
      <br>
      <a href="add_job.php" class="btn btn-primary mt-3">
        <i class="bi bi-plus-circle me-1"></i>Tambah Lowongan Pertama
      </a>
    </div>
  <?php } ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
function confirmLogout() {
  if (confirm('Apakah Anda yakin ingin logout dari akun?')) {
    return true;
  }
  return false;
}
</script>
</body>
</html>
