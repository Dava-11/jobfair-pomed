<?php
session_start();
include('../config/database.php');

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'mahasiswa') {
    header('Location: ../login.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$job_id = $_GET['id'];

$message = "";

// Ambil detail lowongan
$job = mysqli_fetch_assoc(mysqli_query($conn, "SELECT j.*, c.company_name FROM jobs j JOIN companies c ON j.company_id = c.company_id WHERE j.job_id='$job_id'"));

if (isset($_POST['apply'])) {
    $target_dir = "../uploads/cv/";
    $cv_file = $target_dir . basename($_FILES["cv_file"]["name"]);
    $file_type = strtolower(pathinfo($cv_file, PATHINFO_EXTENSION));
    $alasan = mysqli_real_escape_string($conn, $_POST['alasan']);

    if ($file_type != "pdf") {
        $message = "<div class='alert alert-danger'>Hanya file PDF yang diizinkan.</div>";
    } else {
        move_uploaded_file($_FILES["cv_file"]["tmp_name"], $cv_file);
        $query = "INSERT INTO applications (job_id, user_id, cv_file, alasan) VALUES ('$job_id', '$user_id', '$cv_file', '$alasan')";
        if (mysqli_query($conn, $query)) {
            // Redirect langsung ke dashboard setelah lamaran berhasil dikirim
            header('Location: mahasiswa.php?msg=lamaran_berhasil');
            exit;
        } else {
            $message = "<div class='alert alert-danger text-center'>Terjadi kesalahan: " . mysqli_error($conn) . "</div>";
        }
    }
}

$page_title = "Lamar Pekerjaan | JobFair Polmed";
$header_path = "../";
include('../includes/header.php');
?>

<nav class="navbar navbar-expand-lg navbar-light bg-white" style="box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
  <div class="container">
    <a class="navbar-brand fw-bold" href="mahasiswa.php" style="color: #5C1F78;"><i class="bi bi-briefcase me-2"></i>JobFair Polmed</a>
    <div class="d-flex">
      <a href="mahasiswa.php" class="btn btn-sm" style="background: #7B2FA0; border-color: #7B2FA0; color: white; border-radius: 25px; padding: 8px 20px;">
        <i class="bi bi-arrow-left me-1"></i>Kembali
      </a>
    </div>
  </div>
</nav>

<div class="container py-5">
  <div class="card mx-auto shadow-sm" style="max-width: 700px; border-radius: 15px;">
    <div class="card-header text-white fw-bold" style="background: linear-gradient(135deg, #7B2FA0 0%, #5C1F78 100%); border-radius: 15px 15px 0 0;">
      <i class="bi bi-file-earmark-text me-2"></i>Form Lamaran - <?= htmlspecialchars($job['title']); ?>
    </div>
    <div class="card-body p-4">
      <?= $message; ?>
      
      <div class="mb-4">
        <h6 class="fw-bold mb-2"><i class="bi bi-building me-2"></i>Perusahaan</h6>
        <p class="text-muted"><?= htmlspecialchars($job['company_name']); ?></p>
      </div>

      <div class="mb-4">
        <h6 class="fw-bold mb-2"><i class="bi bi-info-circle me-2"></i>Deskripsi Pekerjaan</h6>
        <p class="text-secondary"><?= nl2br(htmlspecialchars($job['description'])); ?></p>
      </div>

      <?php if (!empty($job['requirements'])) { ?>
      <div class="mb-4">
        <h6 class="fw-bold mb-2"><i class="bi bi-list-check me-2"></i>Persyaratan</h6>
        <p class="text-secondary"><?= nl2br(htmlspecialchars($job['requirements'])); ?></p>
      </div>
      <?php } ?>

      <hr>

      <form method="POST" enctype="multipart/form-data">
        <div class="mb-3">
          <label class="form-label fw-bold">
            <i class="bi bi-file-pdf me-2"></i>Upload CV (PDF)
          </label>
          <input type="file" name="cv_file" class="form-control" accept=".pdf" required>
          <small class="text-muted">Format file harus PDF, maksimal 5MB</small>
        </div>

        <div class="mb-4">
          <label class="form-label fw-bold">
            <i class="bi bi-chat-text me-2"></i>Alasan Melamar Pekerjaan
          </label>
          <textarea name="alasan" class="form-control" rows="5" placeholder="Jelaskan mengapa Anda tertarik dengan posisi ini dan bagaimana Anda dapat berkontribusi..." required></textarea>
          <small class="text-muted">Jelaskan alasan Anda melamar dan motivasi Anda untuk posisi ini</small>
        </div>

        <div class="d-flex gap-2">
          <a href="mahasiswa.php" class="btn btn-secondary flex-fill">
            <i class="bi bi-x-circle me-1"></i>Batal
          </a>
          <button type="submit" name="apply" class="btn btn-primary flex-fill" style="background: #7B2FA0; border-color: #7B2FA0;">
            <i class="bi bi-send me-1"></i>Kirim Lamaran
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<?php include('../includes/footer.php'); ?>
