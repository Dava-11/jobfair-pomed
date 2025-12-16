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
    "SELECT a.*, j.company_id, j.title as job_title 
     FROM applications a 
     JOIN jobs j ON a.job_id = j.job_id 
     WHERE a.application_id='$application_id' AND j.company_id='$company_id'"
));

if (!$application) {
    header('Location: perusahaan.php');
    exit;
}

$message = "";

if (isset($_POST['update'])) {
    $status = mysqli_real_escape_string($conn, $_POST['status']);
    
    $sql = "UPDATE applications SET status='$status' WHERE application_id='$application_id'";
    
    if (mysqli_query($conn, $sql)) {
        $message = "<div class='alert alert-success text-center'>Status lamaran berhasil diperbarui!</div>";
        $application = mysqli_fetch_assoc(mysqli_query($conn, 
            "SELECT a.*, j.company_id, j.title as job_title 
             FROM applications a 
             JOIN jobs j ON a.job_id = j.job_id 
             WHERE a.application_id='$application_id'"
        ));
    } else {
        $message = "<div class='alert alert-danger text-center'>Terjadi kesalahan: " . mysqli_error($conn) . "</div>";
    }
}

$user = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE user_id='".$application['user_id']."'"));
?>

<?php
$page_title = "Edit Status Lamaran | JobFair Polmed";
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
  <div class="card mx-auto shadow-sm" style="max-width:700px; border-radius: 15px;">
    <div class="card-header text-white fw-bold" style="background: linear-gradient(135deg, #7B2FA0 0%, #5C1F78 100%); border-radius: 15px 15px 0 0;">
      <i class="bi bi-pencil-square me-2"></i>Edit Status Lamaran
    </div>
    <div class="card-body p-4">
      <?= $message; ?>
      
      <div class="mb-4">
        <h6 class="fw-bold mb-2">Informasi Pelamar</h6>
        <p class="mb-1"><strong>Nama:</strong> <?= htmlspecialchars($user['name']); ?></p>
        <p class="mb-1"><strong>Email:</strong> <?= htmlspecialchars($user['email']); ?></p>
        <p class="mb-1"><strong>Pekerjaan:</strong> <?= htmlspecialchars($application['job_title']); ?></p>
      </div>
      
      <?php if (!empty($application['alasan'])) { ?>
      <div class="mb-4">
        <h6 class="fw-bold mb-2">Alasan Melamar</h6>
        <p class="text-secondary"><?= nl2br(htmlspecialchars($application['alasan'])); ?></p>
      </div>
      <?php } ?>
      
      <form method="POST">
        <div class="mb-4">
          <label class="form-label fw-bold">
            <i class="bi bi-tag me-2"></i>Status Lamaran
          </label>
          <select name="status" class="form-select" required>
            <option value="Pending" <?= ($application['status'] == 'Pending') ? 'selected' : ''; ?>>Pending</option>
            <option value="Under Review" <?= ($application['status'] == 'Under Review') ? 'selected' : ''; ?>>Under Review</option>
            <option value="Accepted" <?= ($application['status'] == 'Accepted') ? 'selected' : ''; ?>>Accepted</option>
            <option value="Rejected" <?= ($application['status'] == 'Rejected') ? 'selected' : ''; ?>>Rejected</option>
          </select>
        </div>

        <div class="d-flex gap-2">
          <a href="view_applicants.php?id=<?= $application['job_id']; ?>" class="btn btn-secondary flex-fill">
            <i class="bi bi-x-circle me-1"></i>Batal
          </a>
          <button type="submit" name="update" class="btn btn-primary flex-fill" style="background: #7B2FA0; border-color: #7B2FA0;">
            <i class="bi bi-check-circle me-1"></i>Simpan Perubahan
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<?php include('../includes/footer.php'); ?>

