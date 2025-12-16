<?php
session_start();
include('../config/database.php');

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'perusahaan') {
    header('Location: ../login.php');
    exit;
}

$job_id = $_GET['id'];

$job = mysqli_fetch_assoc(mysqli_query($conn, 
    "SELECT j.*, c.company_name FROM jobs j 
     JOIN companies c ON j.company_id = c.company_id 
     WHERE j.job_id='$job_id'"
));

$applicants = mysqli_query($conn, 
    "SELECT a.application_id, a.*, u.name, u.email, u.phone 
     FROM applications a 
     JOIN users u ON a.user_id = u.user_id 
     WHERE a.job_id='$job_id'
     ORDER BY a.applied_at DESC"
);
?>

<?php
$page_title = "Daftar Pelamar | JobFair Polmed";
$header_path = "../";
include('../includes/header.php');
?>

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
  <h3 class="mb-4 text-center">
    <i class="bi bi-people me-2"></i>Pelamar untuk: <?= htmlspecialchars($job['title']); ?>
  </h3>

  <?php if (mysqli_num_rows($applicants) > 0) { ?>
    <div class="table-responsive">
      <table class="table table-striped align-middle">
        <thead class="table-primary">
          <tr>
            <th>Nama Pelamar</th>
            <th>Email</th>
            <th>No. HP</th>
            <th>Alasan Melamar</th>
            <th>CV</th>
            <th>Status</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php while ($row = mysqli_fetch_assoc($applicants)) { ?>
          <tr>
            <td><strong><?= htmlspecialchars($row['name']); ?></strong></td>
            <td><?= htmlspecialchars($row['email']); ?></td>
            <td><?= htmlspecialchars($row['phone'] ?? '-'); ?></td>
            <td>
              <?php if (!empty($row['alasan'])) { ?>
                <button type="button" class="btn btn-sm btn-outline-info" data-bs-toggle="modal" data-bs-target="#alasanModal<?= $row['application_id']; ?>">
                  <i class="bi bi-eye me-1"></i>Lihat Alasan
                </button>
                
                <!-- Modal untuk Alasan -->
                <div class="modal fade" id="alasanModal<?= $row['application_id']; ?>" tabindex="-1">
                  <div class="modal-dialog">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title">Alasan Melamar - <?= htmlspecialchars($row['name']); ?></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                      </div>
                      <div class="modal-body">
                        <p><?= nl2br(htmlspecialchars($row['alasan'])); ?></p>
                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                      </div>
                    </div>
                  </div>
                </div>
              <?php } else { ?>
                <span class="text-muted">-</span>
              <?php } ?>
            </td>
            <td>
              <a href="../jobfair-polmed/<?= htmlspecialchars($row['cv_file']); ?>" target="_blank" class="btn btn-sm btn-outline-primary" style="border-color:#7B2FA0;color:#7B2FA0;">
                <i class="bi bi-file-pdf me-1"></i>Lihat CV
              </a>
            </td>
            <td>
              <?php
              $status = strtolower($row['status'] ?? 'pending');
              $badge_class = 'bg-secondary';
              if (strpos($status, 'review') !== false) $badge_class = 'bg-info';
              elseif (strpos($status, 'accept') !== false || strpos($status, 'diterima') !== false) $badge_class = 'bg-success';
              elseif (strpos($status, 'reject') !== false || strpos($status, 'ditolak') !== false) $badge_class = 'bg-danger';
              ?>
              <span class="badge <?= $badge_class; ?>"><?= htmlspecialchars($row['status'] ?? 'Pending'); ?></span>
            </td>
            <td>
              <div class="d-flex gap-1">
                <a href="edit_application.php?id=<?= $row['application_id']; ?>" class="btn btn-sm btn-outline-primary" style="border-color: #7B2FA0; color: #7B2FA0;">
                  <i class="bi bi-pencil"></i>
                </a>
                <a href="delete_application.php?id=<?= $row['application_id']; ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Yakin ingin menghapus lamaran ini?')">
                  <i class="bi bi-trash"></i>
                </a>
              </div>
            </td>
          </tr>
          <?php } ?>
        </tbody>
      </table>
    </div>
  <?php } else { ?>
    <div class="alert alert-info text-center">
      <i class="bi bi-info-circle me-2"></i>Belum ada pelamar untuk lowongan ini.
    </div>
  <?php } ?>

  <div class="text-center mt-4">
    <a href="perusahaan.php" class="btn btn-secondary">
      <i class="bi bi-arrow-left me-1"></i>Kembali ke Dashboard
    </a>
  </div>
</div>

<?php include('../includes/footer.php'); ?>
