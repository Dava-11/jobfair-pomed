<?php
include('config/database.php');

$message = "";

if (isset($_POST['register'])) {
    $name     = mysqli_real_escape_string($conn, $_POST['name']);
    $email    = mysqli_real_escape_string($conn, $_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role     = $_POST['role'];

    $query = "INSERT INTO users (name, email, password, role) VALUES ('$name', '$email', '$password', '$role')";
    if (mysqli_query($conn, $query)) {
        $user_id = mysqli_insert_id($conn);

        if ($role == 'perusahaan') {
            $company_name = mysqli_real_escape_string($conn, $_POST['company_name']);
            $desc = mysqli_real_escape_string($conn, $_POST['description']);
            $sql_company = "INSERT INTO companies (user_id, company_name, description) VALUES ('$user_id', '$company_name', '$desc')";
            mysqli_query($conn, $sql_company);
        }

        $message = "<div class='alert alert-success text-center'>Pendaftaran berhasil! Silakan login.</div>";
    } else {
        $message = "<div class='alert alert-danger text-center'>Terjadi kesalahan: " . mysqli_error($conn) . "</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Daftar Akun | JobFair Polmed</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
  <style>
    :root {
      /* Warna ungu baru mengikuti tema gambar almamater */
      --color-light: #E5CFF5;
      --color-medium: #7B2FA0;
      --color-dark: #5C1F78;
      --color-darker: #4A1A63;
      --primary-gradient: linear-gradient(135deg, #7B2FA0 0%, #5C1F78 100%);
    }
    
    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background-color: #fcf7e3;
      min-height: 100vh;
    }
    
    .register-container {
      min-height: 100vh;
      display: flex;
      align-items: center;
      padding: 2rem 0;
    }
    
    .card {
      border: none;
      border-radius: 15px;
      box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    }
    
    .card-header {
      background: var(--primary-gradient);
      border-radius: 15px 15px 0 0 !important;
      padding: 1.5rem;
    }
    
    .btn-primary {
      background: var(--primary-gradient);
      border: none;
      border-radius: 25px;
      padding: 10px 30px;
      font-weight: 600;
      transition: all 0.3s ease;
    }
    
    .btn-primary:hover {
      transform: translateY(-2px);
      box-shadow: 0 5px 15px rgba(123, 47, 160, 0.4);
      background: linear-gradient(135deg, #5C1F78 0%, #4A1A63 100%);
    }
    
    .form-control:focus, .form-select:focus {
      border-color: #7B2FA0;
      box-shadow: 0 0 0 0.2rem rgba(123, 47, 160, 0.25);
    }
    
    a {
      color: #7B2FA0;
      text-decoration: none;
    }
    
    a:hover {
      color: #5C1F78;
    }
  </style>
</head>
<body>

<?php include('includes/navbar.php'); ?>

<div class="register-container">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-6">
        <div class="card shadow-sm">
          <div class="card-header text-white text-center fw-bold">
            <i class="bi bi-person-plus me-2"></i>Form Pendaftaran
          </div>
          <div class="card-body p-4">
            <?= $message; ?>
            <form method="POST" action="">
              <div class="mb-3">
                <label class="form-label fw-bold">
                  <i class="bi bi-person me-2"></i>Nama Lengkap
                </label>
                <input type="text" name="name" class="form-control" placeholder="Masukkan nama lengkap" required>
              </div>

              <div class="mb-3">
                <label class="form-label fw-bold">
                  <i class="bi bi-envelope me-2"></i>Email
                </label>
                <input type="email" name="email" class="form-control" placeholder="nama@email.com" required>
              </div>

              <div class="mb-3">
                <label class="form-label fw-bold">
                  <i class="bi bi-lock me-2"></i>Kata Sandi
                </label>
                <input type="password" name="password" class="form-control" placeholder="Masukkan kata sandi" required>
              </div>

              <div class="mb-3">
                <label class="form-label fw-bold">
                  <i class="bi bi-person-badge me-2"></i>Daftar Sebagai
                </label>
                <select name="role" id="role" class="form-select" required>
                  <option value="">-- Pilih Role --</option>
                  <option value="mahasiswa">Mahasiswa / Alumni</option>
                  <option value="perusahaan">Perusahaan</option>
                </select>
              </div>

              <div id="companyFields" style="display:none;">
                <div class="mb-3">
                  <label class="form-label fw-bold">
                    <i class="bi bi-building me-2"></i>Nama Perusahaan
                  </label>
                  <input type="text" name="company_name" class="form-control" placeholder="Masukkan nama perusahaan">
                </div>
                <div class="mb-3">
                  <label class="form-label fw-bold">
                    <i class="bi bi-info-circle me-2"></i>Deskripsi Singkat
                  </label>
                  <textarea name="description" class="form-control" rows="3" placeholder="Deskripsi perusahaan..."></textarea>
                </div>
              </div>

              <div class="d-grid mb-3">
                <button type="submit" name="register" class="btn btn-primary">
                  <i class="bi bi-check-circle me-2"></i>Daftar Sekarang
                </button>
              </div>
            </form>
          </div>

          <div class="card-footer text-center bg-white">
            <p class="mb-2">Sudah punya akun? <a href="login.php" class="fw-bold">Masuk</a></p>
            <a href="index.php" class="btn btn-outline-secondary btn-sm" style="border-color: #7B2FA0; color: #7B2FA0;">
              <i class="bi bi-arrow-left me-1"></i>Kembali ke Beranda
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.getElementById('role').addEventListener('change', function() {
  var role = this.value;
  document.getElementById('companyFields').style.display = (role === 'perusahaan') ? 'block' : 'none';
});
</script>

</body>
</html>
