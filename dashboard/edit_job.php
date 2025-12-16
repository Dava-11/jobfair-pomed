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

$message = "";

if (isset($_POST['update'])) {
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $desc = mysqli_real_escape_string($conn, $_POST['description']);
    $req = mysqli_real_escape_string($conn, $_POST['requirements']);
    $loc = mysqli_real_escape_string($conn, $_POST['location']);
    $deadline = $_POST['deadline'];

    $sql = "UPDATE jobs SET title='$title', description='$desc', requirements='$req', location='$loc', deadline='$deadline' WHERE job_id='$job_id' AND company_id='$company_id'";

    if (mysqli_query($conn, $sql)) {
        $message = "<div class='alert alert-success text-center'>Lowongan berhasil diperbarui!</div>";
        $job = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM jobs WHERE job_id='$job_id'"));
    } else {
        $message = "<div class='alert alert-danger text-center'>Terjadi kesalahan: " . mysqli_error($conn) . "</div>";
    }
}
?>

<?php
$page_title = "Edit Lowongan | JobFair Polmed";
$header_path = "../";
include('../includes/header.php');
?>
<style>
body {
  background-color: #fcf7e3;
}
</style>

<nav class="navbar navbar-expand-lg navbar-light bg-white" style="box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
  <div class="container">
    <a class="navbar-brand fw-bold" href="perusahaan.php" style="color: #5C1F78;"><i class="bi bi-building me-2"></i>JobFair Polmed</a>
    <div class="d-flex">
      <a href="perusahaan.php" class="btn btn-sm" style="background: #7B2FA0; border-color: #7B2FA0; color: white; border-radius: 25px; padding: 8px 20px;">
        <i class="bi bi-arrow-left me-1"></i>Kembali
      </a>
    </div>
  </div>
</nav>

<div class="container py-5">
  <div class="card mx-auto shadow-sm" style="max-width:700px; border-radius: 15px;">
    <div class="card-header text-white fw-bold" style="background: linear-gradient(135deg, #7B2FA0 0%, #5C1F78 100%); border-radius: 15px 15px 0 0;">
      <i class="bi bi-pencil-square me-2"></i>Edit Lowongan
    </div>
    <div class="card-body p-4">
      <?= $message; ?>
      <form method="POST">
        <div class="mb-3">
          <label class="form-label fw-bold">
            <i class="bi bi-briefcase me-2"></i>Judul Pekerjaan
          </label>
          <input type="text" name="title" class="form-control" value="<?= htmlspecialchars($job['title']); ?>" required>
        </div>

        <div class="mb-3">
          <label class="form-label fw-bold">
            <i class="bi bi-info-circle me-2"></i>Deskripsi Pekerjaan
          </label>
          <textarea name="description" rows="4" class="form-control" required><?= htmlspecialchars($job['description']); ?></textarea>
        </div>

        <div class="mb-3">
          <label class="form-label fw-bold">
            <i class="bi bi-list-check me-2"></i>Kualifikasi / Persyaratan
          </label>
          <textarea name="requirements" rows="4" class="form-control"><?= htmlspecialchars($job['requirements'] ?? ''); ?></textarea>
        </div>

        <div class="mb-3">
          <label class="form-label fw-bold">
            <i class="bi bi-geo-alt me-2"></i>Lokasi
          </label>
          <input type="text" name="location" class="form-control" value="<?= htmlspecialchars($job['location'] ?? ''); ?>">
        </div>

        <div class="mb-4">
          <label class="form-label fw-bold">
            <i class="bi bi-calendar-event me-2"></i>Batas Waktu (Deadline)
          </label>
          <input type="date" name="deadline" class="form-control" value="<?= $job['deadline']; ?>" required>
        </div>

        <div class="d-flex gap-2">
          <a href="perusahaan.php" class="btn btn-secondary flex-fill">
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

