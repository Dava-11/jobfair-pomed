<?php
session_start();
include('../config/database.php');

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'perusahaan') {
    header('Location: ../login.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$company = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM companies WHERE user_id='$user_id'"));
$company_id = $company['company_id'];
$job_id = $_GET['id'];

// Verify job belongs to this company
$job = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM jobs WHERE job_id='$job_id' AND company_id='$company_id'"));
if (!$job) {
    header('Location: perusahaan.php');
    exit;
}

if (isset($_POST['confirm_delete'])) {
    // Delete all applications for this job first
    mysqli_query($conn, "DELETE FROM applications WHERE job_id='$job_id'");
    
    // Delete the job
    if (mysqli_query($conn, "DELETE FROM jobs WHERE job_id='$job_id' AND company_id='$company_id'")) {
        header('Location: perusahaan.php?msg=deleted');
        exit;
    } else {
        $error = "Gagal menghapus lowongan.";
    }
}
?>

<?php
$page_title = "Hapus Lowongan | JobFair Polmed";
$header_path = "../";
include('../includes/header.php');
?>

<nav class="navbar navbar-expand-lg navbar-light bg-white" style="box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
  <div class="container">
    <a class="navbar-brand fw-bold" href="perusahaan.php" style="color: #6B3F69;"><i class="bi bi-building me-2"></i>JobFair Polmed</a>
    <div class="d-flex">
      <a href="perusahaan.php" class="btn btn-sm" style="background: #A376A2; border-color: #A376A2; color: white; border-radius: 25px; padding: 8px 20px;">
        <i class="bi bi-arrow-left me-1"></i>Kembali
      </a>
    </div>
  </div>
</nav>

<div class="container py-5">
  <div class="card mx-auto shadow-sm" style="max-width:600px; border-radius: 15px; border: 2px solid #dc3545;">
    <div class="card-header text-white fw-bold" style="background: #dc3545; border-radius: 15px 15px 0 0;">
      <i class="bi bi-exclamation-triangle me-2"></i>Konfirmasi Hapus Lowongan
    </div>
    <div class="card-body p-4">
      <?php if (isset($error)) { ?>
        <div class="alert alert-danger"><?= $error; ?></div>
      <?php } ?>
      
      <div class="alert alert-warning">
        <i class="bi bi-exclamation-triangle me-2"></i>
        <strong>Peringatan!</strong> Tindakan ini tidak dapat dibatalkan.
      </div>
      
      <p class="mb-3">Apakah Anda yakin ingin menghapus lowongan berikut?</p>
      <div class="bg-light p-3 rounded mb-4">
        <h5 class="fw-bold"><?= htmlspecialchars($job['title']); ?></h5>
        <p class="text-muted small mb-0"><?= substr($job['description'], 0, 100) . '...'; ?></p>
      </div>
      
      <p class="text-danger small">
        <i class="bi bi-info-circle me-1"></i>
        Semua lamaran yang terkait dengan lowongan ini juga akan dihapus.
      </p>
      
      <form method="POST">
        <div class="d-flex gap-2">
          <a href="perusahaan.php" class="btn btn-secondary flex-fill">
            <i class="bi bi-x-circle me-1"></i>Batal
          </a>
          <button type="submit" name="confirm_delete" class="btn btn-danger flex-fill">
            <i class="bi bi-trash me-1"></i>Ya, Hapus
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<?php include('../includes/footer.php'); ?>

