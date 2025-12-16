<?php
session_start();
include('../config/database.php');

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'perusahaan') {
    header('Location: ../login.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$company = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM companies WHERE user_id='$user_id'"));
$message = "";

if (isset($_POST['update'])) {
    $company_name = mysqli_real_escape_string($conn, $_POST['company_name']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    
    // Handle logo upload
    $logo_path = $company['logo'] ?? ''; // Keep existing logo if not changed
    
    if (isset($_FILES['logo']) && $_FILES['logo']['error'] == 0) {
        $target_dir = "../uploads/company_logos/";
        if (!file_exists($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        
        $file_name = basename($_FILES["logo"]["name"]);
        $file_type = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        $allowed_types = array("jpg", "jpeg", "png", "gif");
        
        if (in_array($file_type, $allowed_types)) {
            $new_file_name = "logo_" . $company['company_id'] . "_" . time() . "." . $file_type;
            $target_file = $target_dir . $new_file_name;
            
            if (move_uploaded_file($_FILES["logo"]["tmp_name"], $target_file)) {
                // Delete old logo if exists
                if (!empty($company['logo']) && file_exists("../" . $company['logo'])) {
                    unlink("../" . $company['logo']);
                }
                $logo_path = "uploads/company_logos/" . $new_file_name;
            } else {
                $message = "<div class='alert alert-danger'>Gagal mengupload logo.</div>";
            }
        } else {
            $message = "<div class='alert alert-danger'>Format file tidak didukung. Gunakan JPG, PNG, atau GIF.</div>";
        }
    }
    
    $sql = "UPDATE companies SET company_name='$company_name', description='$description'";
    if (!empty($logo_path)) {
        $logo_path_escaped = mysqli_real_escape_string($conn, $logo_path);
        $sql .= ", logo='$logo_path_escaped'";
    }
    $sql .= " WHERE user_id='$user_id'";
    
    if (mysqli_query($conn, $sql)) {
        $message = "<div class='alert alert-success text-center'>Profil berhasil diperbarui!</div>";
        // Refresh company data
        $company = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM companies WHERE user_id='$user_id'"));
    } else {
        $message = "<div class='alert alert-danger text-center'>Terjadi kesalahan: " . mysqli_error($conn) . "</div>";
    }
}
?>

<?php
$page_title = "Edit Profil Perusahaan | JobFair Polmed";
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
  <div class="card mx-auto shadow-sm" style="max-width:700px; border-radius: 15px;">
    <div class="card-header text-white fw-bold" style="background: linear-gradient(135deg, #A376A2 0%, #8D5F8C 100%); border-radius: 15px 15px 0 0;">
      <i class="bi bi-pencil-square me-2"></i>Edit Profil Perusahaan
    </div>
    <div class="card-body p-4">
      <?= $message; ?>
      
      <form method="POST" enctype="multipart/form-data">
        <div class="text-center mb-4">
          <div class="mb-3">
            <?php if (!empty($company['logo'])) { ?>
              <img src="../<?= htmlspecialchars($company['logo']); ?>" alt="Logo Perusahaan" 
                   class="img-thumbnail" style="max-width: 200px; max-height: 200px; object-fit: contain;">
            <?php } else { ?>
              <div class="bg-light border rounded d-inline-flex align-items-center justify-content-center" 
                   style="width: 200px; height: 200px;">
                <i class="bi bi-building" style="font-size: 4rem; color: #A376A2;"></i>
              </div>
            <?php } ?>
          </div>
          <label class="form-label fw-bold">
            <i class="bi bi-image me-2"></i>Logo Perusahaan
          </label>
          <input type="file" name="logo" class="form-control" accept="image/*">
          <small class="text-muted">Format: JPG, PNG, atau GIF. Maksimal 2MB</small>
        </div>

        <div class="mb-3">
          <label class="form-label fw-bold">
            <i class="bi bi-building me-2"></i>Nama Perusahaan
          </label>
          <input type="text" name="company_name" class="form-control" 
                 value="<?= htmlspecialchars($company['company_name']); ?>" required>
        </div>

        <div class="mb-4">
          <label class="form-label fw-bold">
            <i class="bi bi-info-circle me-2"></i>Deskripsi Perusahaan
          </label>
          <textarea name="description" class="form-control" rows="5" required><?= htmlspecialchars($company['description']); ?></textarea>
        </div>

        <div class="d-flex gap-2">
          <a href="perusahaan.php" class="btn btn-secondary flex-fill">
            <i class="bi bi-x-circle me-1"></i>Batal
          </a>
          <button type="submit" name="update" class="btn btn-primary flex-fill" style="background: #A376A2; border-color: #A376A2;">
            <i class="bi bi-check-circle me-1"></i>Simpan Perubahan
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<?php include('../includes/footer.php'); ?>

