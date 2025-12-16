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
$application_id = $_GET['id'];

// Verify application belongs to a job from this company
$application = mysqli_fetch_assoc(mysqli_query($conn, 
    "SELECT a.*, j.company_id, j.title as job_title, u.name 
     FROM applications a 
     JOIN jobs j ON a.job_id = j.job_id 
     JOIN users u ON a.user_id = u.user_id
     WHERE a.application_id='$application_id' AND j.company_id='$company_id'"
));

if (!$application) {
    header('Location: perusahaan.php');
    exit;
}

if (isset($_POST['confirm_delete'])) {
    // Delete CV file if exists
    if (!empty($application['cv_file']) && file_exists("../" . $application['cv_file'])) {
        unlink("../" . $application['cv_file']);
    }
    
    if (mysqli_query($conn, "DELETE FROM applications WHERE application_id='$application_id'")) {
        header('Location: view_applicants.php?id=' . $application['job_id'] . '&msg=deleted');
        exit;
    } else {
        $error = "Gagal menghapus lamaran.";
    }
}
?>

<?php
$page_title = "Hapus Lamaran | JobFair Polmed";
$header_path = "../";
include('../includes/header.php');
?>

<nav class="navbar navbar-expand-lg navbar-light bg-white" style="box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
  <div class="container">
    <a class="navbar-brand fw-bold" href="perusahaan.php" style="color: #5C1F78;"><i class="bi bi-building me-2"></i>JobFair Polmed</a>
    <div class="d-flex">
      <a href="view_applicants.php?id=<?= $application['job_id']; ?>" class="btn btn-sm" style="background: #7B2FA0; border-color: #7B2FA0; color: white; border-radius: 25px; padding: 8px 20px;">
        <i class="bi bi-arrow-left me-1"></i>Kembali
      </a>
    </div>
  </div>
</nav>

<div class="container py-5">
  <div class="card mx-auto shadow-sm" style="max-width:600px; border-radius: 15px; border: 2px solid #dc3545;">
    <div class="card-header text-white fw-bold" style="background: #dc3545; border-radius: 15px 15px 0 0;">
      <i class="bi bi-exclamation-triangle me-2"></i>Konfirmasi Hapus Lamaran
    </div>
    <div class="card-body p-4">
      <?php if (isset($error)) { ?>
        <div class="alert alert-danger"><?= $error; ?></div>
      <?php } ?>
      
      <div class="alert alert-warning">
        <i class="bi bi-exclamation-triangle me-2"></i>
        <strong>Peringatan!</strong> Tindakan ini tidak dapat dibatalkan.
      </div>
      
      <p class="mb-3">Apakah Anda yakin ingin menghapus lamaran dari:</p>
      <div class="bg-light p-3 rounded mb-4">
        <h6 class="fw-bold mb-1"><?= htmlspecialchars($application['name']); ?></h6>
        <p class="text-muted small mb-0">Untuk posisi: <?= htmlspecialchars($application['job_title']); ?></p>
      </div>
      
      <form method="POST">
        <div class="d-flex gap-2">
          <a href="view_applicants.php?id=<?= $application['job_id']; ?>" class="btn btn-secondary flex-fill">
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

