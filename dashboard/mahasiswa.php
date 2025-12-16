<?php
session_start();
include('../config/database.php');

// Cek apakah user yang login adalah mahasiswa
$is_mahasiswa = isset($_SESSION['user_id']) && isset($_SESSION['role']) && $_SESSION['role'] === 'mahasiswa';

// Cek apakah ada pesan sukses dari redirect (khusus setelah melamar)
$success_message = "";
if ($is_mahasiswa && isset($_GET['msg']) && $_GET['msg'] == 'lamaran_berhasil') {
    $success_message = "<div class='alert alert-success alert-dismissible fade show' role='alert'>
        <i class='bi bi-check-circle me-2'></i><strong>Berhasil!</strong> Lamaran Anda telah dikirim.
        <button type='button' class='btn-close' data-bs-dismiss='alert'></button>
    </div>";
}

// Ambil semua lowongan kerja dari database (bisa dilihat semua pengunjung)
// Jika mahasiswa sudah login, kecualikan lowongan yang sudah dilamar
if ($is_mahasiswa) {
    $user_id = $_SESSION['user_id'];
    $sql = "SELECT j.*, c.company_name, c.logo 
            FROM jobs j 
            JOIN companies c ON j.company_id = c.company_id 
            LEFT JOIN applications a ON j.job_id = a.job_id AND a.user_id = '$user_id'
            WHERE a.application_id IS NULL
            ORDER BY j.created_at DESC";
} else {
    $sql = "SELECT j.*, c.company_name, c.logo 
            FROM jobs j 
            JOIN companies c ON j.company_id = c.company_id 
            ORDER BY j.created_at DESC";
}
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Dashboard Mahasiswa | JobFair Polmed</title>
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
    
    .job-card .card-body {
      padding: 1.5rem;
    }
    
    .application-box {
      border: none;
      border-radius: 15px;
      padding: 1.5rem;
      background: white;
      box-shadow: 0 5px 15px rgba(0,0,0,0.08);
      transition: all 0.3s ease;
      height: 100%;
    }
    
    .application-box:hover {
      transform: translateY(-5px);
      box-shadow: 0 10px 30px rgba(0,0,0,0.15);
    }
    
    .status-badge {
      padding: 8px 15px;
      border-radius: 20px;
      font-size: 0.85rem;
      font-weight: 600;
    }
    
    .status-pending {
      background: linear-gradient(135deg, #FFE7B4 0%, #FFB347 100%);
      color: #4A2700;
    }
    
    .status-review {
      background: linear-gradient(135deg, #B2E2FF 0%, #4A9FFF 100%);
      color: #00315C;
    }
    
    .status-accepted {
      background: linear-gradient(135deg, #B5F3C0 0%, #35B86B 100%);
      color: #03371D;
    }
    
    .status-rejected {
      background: linear-gradient(135deg, #FFB3C2 0%, #E14D62 100%);
      color: #4A0413;
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
    
    .btn-logout {
      border-radius: 25px;
      padding: 8px 20px;
    }
  </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-light bg-white" style="box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
  <div class="container">
    <a class="navbar-brand fw-bold" href="../index.php" style="color: #5C1F78;">
      <i class="bi bi-briefcase me-2"></i>JobFair Polmed
    </a>
    <?php if ($is_mahasiswa) { ?>
      <div class="d-flex">
        <a href="logout.php" class="btn btn-sm btn-logout" style="background: #7B2FA0; border-color: #7B2FA0; color: white; border-radius: 25px; padding: 8px 20px;" onclick="return confirmLogout()">
          <i class="bi bi-box-arrow-right me-1"></i>Keluar
        </a>
      </div>
    <?php } ?>
  </div>
</nav>

<div class="container py-5">
  <?= $success_message; ?>
  <h2 class="text-center section-title mb-5">
    <i class="bi bi-search me-2"></i>Daftar Lowongan Pekerjaan
  </h2>

  <div class="row g-4 mb-5">
    <?php while ($row = mysqli_fetch_assoc($result)) { ?>
      <div class="col-lg-4 col-md-6">
        <div class="card job-card h-100">
          <div class="card-body">
            <div class="d-flex align-items-center mb-3">
              <?php if (!empty($row['logo'])) { ?>
                <img src="../<?= htmlspecialchars($row['logo']); ?>" alt="<?= htmlspecialchars($row['company_name']); ?>" 
                     class="me-3" style="width: 50px; height: 50px; object-fit: contain; border-radius: 8px;">
              <?php } else { ?>
                <div class="bg-light d-flex align-items-center justify-content-center me-3" 
                     style="width: 50px; height: 50px; border-radius: 8px;">
                  <i class="bi bi-building" style="font-size: 1.5rem; color: #7B2FA0;"></i>
                </div>
              <?php } ?>
              <div class="flex-grow-1">
                <h5 class="card-title fw-bold mb-0"><?= htmlspecialchars($row['title']); ?></h5>
                <p class="text-muted small mb-0"><?= htmlspecialchars($row['company_name']); ?></p>
              </div>
            </div>
            <p class="text-secondary small mb-3"><?= substr($row['description'], 0, 100) . '...'; ?></p>
            <p class="small text-muted mb-3">
              <i class="bi bi-calendar-event me-1"></i>Deadline: <?= date('d M Y', strtotime($row['deadline'])); ?>
            </p>
            <?php if ($is_mahasiswa) { ?>
              <a href="apply_job.php?id=<?= $row['job_id']; ?>" class="btn btn-primary w-100" style="background: #7B2FA0; border-color: #7B2FA0;">
                <i class="bi bi-send me-1"></i>Lamar Sekarang
              </a>
            <?php } else { ?>
              <a href="../login.php" class="btn btn-primary w-100" style="background: #7B2FA0; border-color: #7B2FA0;">
                <i class="bi bi-send me-1"></i>Lamar Sekarang
              </a>
            <?php } ?>
          </div>
        </div>
      </div>
    <?php } ?>
  </div>

  <?php if ($is_mahasiswa) { ?>
    <h3 class="section-title mt-5 mb-4">
      <i class="bi bi-file-earmark-text me-2"></i>Status Lamaran Saya
    </h3>
    
    <?php
    $user_id = $_SESSION['user_id'];
    $apps = mysqli_query($conn, "SELECT a.*, j.title, c.company_name 
                                FROM applications a 
                                JOIN jobs j ON a.job_id = j.job_id 
                                JOIN companies c ON j.company_id = c.company_id 
                                WHERE a.user_id='$user_id'
                                ORDER BY a.applied_at DESC");
    if (mysqli_num_rows($apps) > 0) {
        $apps_data = [];
        while ($row = mysqli_fetch_assoc($apps)) {
            $apps_data[] = $row;
        }
    ?>
    <div class="row g-4">
      <?php foreach ($apps_data as $app) { 
        $status_class = 'status-pending';
        $status_text = strtolower($app['status']);
        if (strpos($status_text, 'review') !== false) $status_class = 'status-review';
        elseif (strpos($status_text, 'accept') !== false || strpos($status_text, 'diterima') !== false) $status_class = 'status-accepted';
        elseif (strpos($status_text, 'reject') !== false || strpos($status_text, 'ditolak') !== false) $status_class = 'status-rejected';
      ?>
      <div class="col-lg-3 col-md-6">
        <div class="application-box">
          <h6 class="fw-bold mb-2" style="font-size: 0.95rem;">
            <a href="application_detail.php?id=<?= $app['application_id']; ?>" class="text-decoration-none" style="color:#5C1F78;">
              <?= htmlspecialchars($app['title']); ?>
            </a>
          </h6>
          <p class="text-muted small mb-2">
            <i class="bi bi-building me-1"></i><?= htmlspecialchars($app['company_name']); ?>
          </p>
          <div class="mb-2">
            <span class="status-badge <?= $status_class; ?>">
              <?= htmlspecialchars($app['status']); ?>
            </span>
          </div>
          <p class="small text-secondary mb-0">
            <i class="bi bi-calendar me-1"></i><?= date('d M Y', strtotime($app['applied_at'])); ?>
          </p>
        </div>
      </div>
      <?php } ?>
    </div>
    <?php } else { ?>
      <div class="alert alert-info text-center">
        <i class="bi bi-info-circle me-2"></i>Belum ada lamaran yang dikirim.
      </div>
    <?php } ?>
  <?php } else { ?>
    <div class="alert alert-info text-center mt-5">
      <i class="bi bi-info-circle me-2"></i>Silakan login sebagai mahasiswa untuk melihat status lamaran Anda.
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
