<?php
session_start();
include('config/database.php');

$message = "";

if (isset($_POST['login'])) {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];

    $query = "SELECT * FROM users WHERE email='$email'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);

        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['role'] = $user['role'];

            if ($user['role'] == 'mahasiswa') {
                header('Location: dashboard/mahasiswa.php');
            } elseif ($user['role'] == 'perusahaan') {
                header('Location: dashboard/perusahaan.php');
            } else {
                header('Location: dashboard/admin.php');
            }
            exit;
        } else {
            $message = "<div class='alert alert-danger text-center'>Kata sandi salah.</div>";
        }
    } else {
        $message = "<div class='alert alert-danger text-center'>Akun tidak ditemukan.</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Login | JobFair Polmed</title>
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
    
    .login-container {
      min-height: 100vh;
      display: flex;
      align-items: center;
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
    
    .form-control:focus {
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

<div class="login-container">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-5">
        <div class="card shadow-sm">
          <div class="card-header text-white text-center fw-bold">
            <i class="bi bi-box-arrow-in-right me-2"></i>Login Pengguna
          </div>
          <div class="card-body p-4">
            <?= $message; ?>
            <form method="POST" action="">
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

              <div class="d-grid mb-3">
                <button type="submit" name="login" class="btn btn-primary">
                  <i class="bi bi-box-arrow-in-right me-2"></i>Masuk
                </button>
              </div>

              <div class="text-center">
                Belum punya akun? <a href="register.php" class="fw-bold">Daftar Sekarang</a>
              </div>
            </form>
          </div>

          <div class="card-footer text-center bg-white">
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
</body>
</html>
