<?php
session_start();
include('../config/database.php');

// Hanya mahasiswa yang boleh mengakses
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'mahasiswa') {
    header('Location: ../login.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$application_id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

// Ambil detail lamaran milik mahasiswa ini
$sql = "SELECT a.*, 
               j.title, j.description, j.requirements, j.location, j.deadline,
               c.company_name
        FROM applications a
        JOIN jobs j ON a.job_id = j.job_id
        JOIN companies c ON j.company_id = c.company_id
        WHERE a.application_id = '$application_id' AND a.user_id = '$user_id'
        LIMIT 1";

$result = mysqli_query($conn, $sql);
$application = mysqli_fetch_assoc($result);

if (!$application) {
    header('Location: mahasiswa.php');
    exit;
}

$page_title = "Detail Lamaran | JobFair Polmed";
$header_path = "../";
include('../includes/header.php');
?>

<nav class="navbar navbar-expand-lg navbar-light bg-white" style="box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
  <div class="container">
    <a class="navbar-brand fw-bold" href="mahasiswa.php" style="color: #5C1F78;"><i class="bi bi-briefcase me-2"></i>JobFair Polmed</a>
    <div class="d-flex">
      <a href="mahasiswa.php" class="btn btn-sm" style="background: #7B2FA0; border-color: #7B2FA0; color: white; border-radius: 25px; padding: 8px 20px;">
        <i class="bi bi-arrow-left me-1"></i>Kembali ke Dashboard
      </a>
    </div>
  </div>
</nav>

<div class="container py-5">
  <div class="card mx-auto shadow-sm" style="max-width: 800px; border-radius: 15px;">
    <div class="card-header text-white fw-bold" style="background: linear-gradient(135deg, #7B2FA0 0%, #5C1F78 100%); border-radius: 15px 15px 0 0;">
      <i class="bi bi-file-earmark-text me-2"></i>Detail Lamaran
    </div>
    <div class="card-body p-4">
      <h5 class="fw-bold mb-1"><?= htmlspecialchars($application['title']); ?></h5>
      <p class="text-muted mb-3">
        <i class="bi bi-building me-1"></i><?= htmlspecialchars($application['company_name']); ?>
      </p>

      <div class="mb-3">
        <?php
          $status = strtolower($application['status'] ?? 'Pending');
          $status_class = 'status-badge status-pending';
          if (strpos($status, 'review') !== false) $status_class = 'status-badge status-review';
          elseif (strpos($status, 'accept') !== false || strpos($status, 'diterima') !== false) $status_class = 'status-badge status-accepted';
          elseif (strpos($status, 'reject') !== false || strpos($status, 'ditolak') !== false) $status_class = 'status-badge status-rejected';
        ?>
        <span class="<?= $status_class; ?>">
          <?= htmlspecialchars($application['status'] ?? 'Pending'); ?>
        </span>
        <span class="small text-secondary ms-2">
          <i class="bi bi-calendar me-1"></i>Dilamar pada: <?= date('d M Y', strtotime($application['applied_at'])); ?>
        </span>
      </div>

      <hr>

      <div class="mb-3">
        <h6 class="fw-bold mb-2"><i class="bi bi-info-circle me-2"></i>Deskripsi Pekerjaan</h6>
        <p class="text-secondary mb-0"><?= nl2br(htmlspecialchars($application['description'])); ?></p>
      </div>

      <?php if (!empty($application['requirements'])) { ?>
      <div class="mb-3">
        <h6 class="fw-bold mb-2"><i class="bi bi-list-check me-2"></i>Persyaratan</h6>
        <p class="text-secondary mb-0"><?= nl2br(htmlspecialchars($application['requirements'])); ?></p>
      </div>
      <?php } ?>

      <div class="mb-3">
        <h6 class="fw-bold mb-2"><i class="bi bi-geo-alt me-2"></i>Lokasi & Deadline</h6>
        <p class="mb-1 text-secondary">Lokasi: <?= htmlspecialchars($application['location'] ?? '-'); ?></p>
        <p class="mb-0 text-secondary">
          Deadline: <?= $application['deadline'] ? date('d M Y', strtotime($application['deadline'])) : '-'; ?>
        </p>
      </div>

      <?php if (!empty($application['alasan'])) { ?>
      <div class="mb-3">
        <h6 class="fw-bold mb-2"><i class="bi bi-chat-text me-2"></i>Alasan Anda Melamar</h6>
        <p class="text-secondary mb-0"><?= nl2br(htmlspecialchars($application['alasan'])); ?></p>
      </div>
      <?php } ?>

      <div class="mb-4">
        <h6 class="fw-bold mb-2"><i class="bi bi-file-pdf me-2"></i>CV yang Diupload</h6>
        <?php if (!empty($application['cv_file'])) { ?>
          <a href="../jobfair-polmed/<?= htmlspecialchars($application['cv_file']); ?>" target="_blank" class="btn btn-sm btn-outline-primary" style="border-color:#7B2FA0;color:#7B2FA0;">
            <i class="bi bi-file-earmark-pdf me-1"></i>Lihat CV
          </a>
        <?php } else { ?>
          <p class="text-muted mb-0">CV tidak tersedia.</p>
        <?php } ?>
      </div>

      <div class="text-end">
        <a href="mahasiswa.php" class="btn btn-secondary">
          <i class="bi bi-arrow-left me-1"></i>Kembali ke Dashboard
        </a>
      </div>
    </div>
  </div>
</div>

<?php include('../includes/footer.php'); ?>


