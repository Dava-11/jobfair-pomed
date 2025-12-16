<?php
session_start();
include('../config/database.php');

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header('Location: ../login.php');
    exit;
}

// Statistik
$total_users = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM users WHERE role='mahasiswa'"))['total'];
$total_companies = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM companies"))['total'];
$total_jobs = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM jobs"))['total'];
$total_applications = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM applications"))['total'];
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Dashboard Admin | JobFair Polmed</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
  <style>
    :root {
      --primary-gradient: linear-gradient(135deg, #A376A2 0%, #8D5F8C 100%);
      --success-gradient: linear-gradient(135deg, #8D5F8C 0%, #6B3F69 100%);
      --warning-gradient: linear-gradient(135deg, #DDC3C3 0%, #A376A2 100%);
      --danger-gradient: linear-gradient(135deg, #6B3F69 0%, #8D5F8C 100%);
      --color-light: #DDC3C3;
      --color-medium: #A376A2;
      --color-dark: #8D5F8C;
      --color-darker: #6B3F69;
    }
    
    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background: linear-gradient(to bottom, #f8f9fa 0%, #ffffff 100%);
    }
    
    .navbar {
      background: var(--primary-gradient) !important;
      box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }
    
    .stat-card {
      border: none;
      border-radius: 15px;
      transition: all 0.3s ease;
      box-shadow: 0 5px 15px rgba(0,0,0,0.08);
      height: 100%;
      overflow: hidden;
    }
    
    .stat-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 10px 30px rgba(0,0,0,0.15);
    }
    
    .stat-card.student {
      background: var(--primary-gradient);
      color: white;
    }
    
    .stat-card.company {
      background: var(--success-gradient);
      color: white;
    }
    
    .stat-card.job {
      background: var(--warning-gradient);
      color: white;
    }
    
    .stat-card.application {
      background: var(--danger-gradient);
      color: white;
    }
    
    .stat-icon {
      font-size: 3rem;
      opacity: 0.8;
    }
    
    .stat-number {
      font-size: 2.5rem;
      font-weight: 800;
      margin: 0.5rem 0;
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
    
    .btn-action {
      border-radius: 25px;
      padding: 12px 30px;
      font-weight: 600;
      transition: all 0.3s ease;
    }
    
    .btn-action:hover {
      transform: translateY(-2px);
      box-shadow: 0 5px 15px rgba(0,0,0,0.2);
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
    <a class="navbar-brand fw-bold" href="#" style="color: #6B3F69;"><i class="bi bi-shield-check me-2"></i>Admin Panel - JobFair Polmed</a>
    <div class="d-flex">
      <a href="logout.php" class="btn btn-sm btn-logout" style="background: #A376A2; border-color: #A376A2; color: white; border-radius: 25px; padding: 8px 20px;" onclick="return confirmLogout()">
        <i class="bi bi-box-arrow-right me-1"></i>Keluar
      </a>
    </div>
  </div>
</nav>

<div class="container py-5">
  <h2 class="text-center section-title mb-5">
    <i class="bi bi-speedometer2 me-2"></i>Selamat Datang, Admin
  </h2>

  <div class="row g-4 mb-5">
    <div class="col-lg-3 col-md-6">
      <div class="stat-card student text-center p-4">
        <i class="bi bi-people stat-icon"></i>
        <div class="stat-number"><?= $total_users; ?></div>
        <h5 class="fw-bold">Mahasiswa</h5>
      </div>
    </div>
    <div class="col-lg-3 col-md-6">
      <div class="stat-card company text-center p-4">
        <i class="bi bi-building stat-icon"></i>
        <div class="stat-number"><?= $total_companies; ?></div>
        <h5 class="fw-bold">Perusahaan</h5>
      </div>
    </div>
    <div class="col-lg-3 col-md-6">
      <div class="stat-card job text-center p-4">
        <i class="bi bi-briefcase stat-icon"></i>
        <div class="stat-number"><?= $total_jobs; ?></div>
        <h5 class="fw-bold">Lowongan</h5>
      </div>
    </div>
    <div class="col-lg-3 col-md-6">
      <div class="stat-card application text-center p-4">
        <i class="bi bi-file-earmark-text stat-icon"></i>
        <div class="stat-number"><?= $total_applications; ?></div>
        <h5 class="fw-bold">Lamaran</h5>
      </div>
    </div>
  </div>

  <div class="text-center mt-5">
    <a href="manage_users.php" class="btn btn-outline-primary btn-action m-2">
      <i class="bi bi-people me-2"></i>Kelola Mahasiswa
    </a>
    <a href="manage_companies.php" class="btn btn-outline-success btn-action m-2">
      <i class="bi bi-building me-2"></i>Kelola Perusahaan
    </a>
    <a href="manage_jobs.php" class="btn btn-outline-info btn-action m-2">
      <i class="bi bi-briefcase me-2"></i>Kelola Lowongan
    </a>
  </div>
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
