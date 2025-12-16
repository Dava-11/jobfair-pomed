<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Job Fair Online | Politeknik Negeri Medan</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
  <link rel="stylesheet" href="assets/css/style.css">
  <style>
    :root {
      /* Diselaraskan dengan warna ungu pada tema global (gambar almamater) */
      --primary-gradient: linear-gradient(135deg, #7B2FA0 0%, #5C1F78 100%);
      --secondary-gradient: linear-gradient(135deg, #E5CFF5 0%, #7B2FA0 100%);
      --success-gradient: linear-gradient(135deg, #7B2FA0 0%, #4A1A63 100%);
      --color-light: #E5CFF5;
      --color-medium: #7B2FA0;
      --color-dark: #5C1F78;
      --color-darker: #4A1A63;
    }
    
    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }
    
    .hero {
      background: var(--primary-gradient);
      padding: 100px 0;
      position: relative;
      overflow: hidden;
    }
    
    .hero::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background: url('data:image/svg+xml,<svg width="100" height="100" xmlns="http://www.w3.org/2000/svg"><defs><pattern id="grid" width="100" height="100" patternUnits="userSpaceOnUse"><path d="M 100 0 L 0 0 0 100" fill="none" stroke="rgba(255,255,255,0.1)" stroke-width="1"/></pattern></defs><rect width="100" height="100" fill="url(%23grid)"/></svg>');
      opacity: 0.3;
    }
    
    .hero-content {
      position: relative;
      z-index: 1;
    }
    
    .hero h1 {
      font-size: 3.5rem;
      font-weight: 800;
      text-shadow: 2px 2px 4px rgba(0,0,0,0.2);
      margin-bottom: 1.5rem;
    }
    
    .hero .lead {
      font-size: 1.3rem;
      margin-bottom: 2rem;
      opacity: 0.95;
    }
    
    .btn-hero {
      padding: 15px 40px;
      font-size: 1.1rem;
      font-weight: 600;
      border-radius: 50px;
      box-shadow: 0 10px 30px rgba(0,0,0,0.3);
      transition: all 0.3s ease;
    }
    
    .btn-hero:hover {
      transform: translateY(-3px);
      box-shadow: 0 15px 40px rgba(0,0,0,0.4);
    }
    
    .feature-card {
      border: none;
      border-radius: 20px;
      padding: 2.5rem;
      height: 100%;
      transition: all 0.3s ease;
      background: white;
      box-shadow: 0 5px 20px rgba(0,0,0,0.08);
    }
    
    .feature-card:hover {
      transform: translateY(-10px);
      box-shadow: 0 15px 40px rgba(0,0,0,0.15);
    }
    
    .feature-icon {
      width: 80px;
      height: 80px;
      border-radius: 20px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 2.5rem;
      margin: 0 auto 1.5rem;
      color: white;
    }
    
    .feature-icon.student {
      background: var(--primary-gradient);
    }
    
    .feature-icon.company {
      background: var(--secondary-gradient);
    }
    
    .feature-icon.admin {
      background: var(--success-gradient);
    }
    
    .section-title {
      font-size: 2.5rem;
      font-weight: 700;
      margin-bottom: 3rem;
      background: var(--primary-gradient);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
    }
    
    .features-section {
      padding: 80px 0;
      background: linear-gradient(to bottom, #f8f9fa 0%, #ffffff 100%);
    }
    
    .navbar {
      box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }
  </style>
</head>

<body>
  <?php include('includes/navbar.php'); ?>

  <section class="hero text-center text-light">
    <div class="container hero-content">
      <h1 class="display-2 fw-bold">Website Job Fair Online</h1>
      <h2 class="display-6 mb-4">Politeknik Negeri Medan</h2>
      <p class="lead">Menghubungkan mahasiswa dan alumni Polmed dengan perusahaan mitra secara digital dan efisien.</p>
      <a href="dashboard/mahasiswa.php" class="btn btn-light btn-hero">
        <i class="bi bi-rocket-takeoff me-2"></i>Mulai Sekarang
      </a>
    </div>
  </section>

  <section class="features-section">
    <div class="container">
      <h2 class="text-center section-title">Fitur Utama</h2>
      <div class="row g-4">
        <div class="col-md-4">
          <div class="feature-card text-center">
            <div class="feature-icon student">
              <i class="bi bi-person-badge"></i>
            </div>
            <h4 class="fw-bold mb-3">Mahasiswa & Alumni</h4>
            <p class="text-muted">Daftar, unggah CV, dan lamar pekerjaan dari perusahaan mitra kampus dengan mudah dan cepat.</p>
          </div>
        </div>
        <div class="col-md-4">
          <div class="feature-card text-center">
            <div class="feature-icon company">
              <i class="bi bi-building"></i>
            </div>
            <h4 class="fw-bold mb-3">Perusahaan</h4>
            <p class="text-muted">Publikasikan lowongan kerja dan temukan kandidat terbaik dari Polmed dengan sistem yang terintegrasi.</p>
          </div>
        </div>
        <div class="col-md-4">
          <div class="feature-card text-center">
            <div class="feature-icon admin">
              <i class="bi bi-shield-check"></i>
            </div>
            <h4 class="fw-bold mb-3">Admin Career Center</h4>
            <p class="text-muted">Kelola aktivitas job fair digital dan pantau data pelamar serta perusahaan secara real-time.</p>
          </div>
        </div>
      </div>
    </div>
  </section>

  <?php include('includes/footer.php'); ?>
  
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
