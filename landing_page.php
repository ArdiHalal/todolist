<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Todolist Ardi - Homepage</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <style>
    :root {
      --bg-dark: #1e1e2f;
      --bg-secondary: #2b2b3c;
      --primary: #6a5acd; /* Pastel lavender */
      --accent: #4fc3f7;
      --pastel-pink: #ffb6c1;
      --pastel-mint: #b5ead7;
      --pastel-lavender: #e6e6fa;
      --pastel-peach: #ffdac1;
      --text-light: #f8f8f8;
      --text-muted: #cccccc;
    }

    body {
      background-image: url("bglp.jpg");
      color: var(--text-light);
      font-family: 'Poppins', sans-serif;
      background-image: 
        radial-gradient(circle at 10% 20%, rgba(181, 234, 215, 0.1) 0%, transparent 20%),
        radial-gradient(circle at 90% 80%, rgba(230, 230, 250, 0.1) 0%, transparent 20%);
      background-attachment: fixed;
    }

    .navbar {
      background-color: rgba(30, 30, 47, 0.9) !important;
      backdrop-filter: blur(10px);
      border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    }

    .hero {
      padding: 100px 20px;
      text-align: center;
      background: linear-gradient(135deg, rgba(30, 30, 47, 0.9), rgba(43, 43, 60, 0.9));
      border-radius: 16px;
      margin: 20px;
      box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
    }

    .hero h1 {
      font-size: 3rem;
      font-weight: 700;
      background: linear-gradient(90deg, var(--pastel-mint), var(--pastel-lavender));
      -webkit-background-clip: text;
      background-clip: text;
      color: transparent;
    }

    .hero p {
      font-size: 1.2rem;
      color: var(--text-muted);
      max-width: 700px;
      margin: 0 auto;
    }

    .btn-custom {
      background-color: var(--primary);
      border: none;
      color: var(--text-light);
      padding: 12px 28px;
      font-weight: 600;
      border-radius: 50px;
      transition: all 0.3s ease;
      box-shadow: 0 4px 15px rgba(106, 90, 205, 0.3);
    }

    .btn-custom:hover {
      transform: translateY(-3px);
      box-shadow: 0 6px 20px rgba(106, 90, 205, 0.4);
    }

    .btn-outline-light {
      transition: all 0.3s ease;
    }

    .btn-outline-light:hover {
      background-color: rgba(255, 255, 255, 0.1);
    }

    .features {
      background-color: rgba(43, 43, 60, 0.8);
      padding: 60px 20px;
      border-radius: 16px;
      margin: 40px 20px;
      backdrop-filter: blur(10px);
      border: 1px solid rgba(255, 255, 255, 0.1);
      box-shadow: 0 8px 32px rgba(0, 0, 0, 0.2);
    }

    .feature-icon {
      font-size: 2.5rem;
      margin-bottom: 15px;
      background: linear-gradient(45deg, var(--pastel-peach), var(--pastel-pink));
      -webkit-background-clip: text;
      background-clip: text;
      color: transparent;
    }

    .features h5 {
      color: var(--pastel-mint);
      margin-bottom: 15px;
    }

    .features p {
      color: var(--text-muted);
    }

    .features .col-md-3 {
      padding: 20px;
      transition: all 0.3s ease;
    }

    .features .col-md-3:hover {
      transform: translateY(-10px);
    }

    footer {
      margin-top: 60px;
      padding: 20px;
      text-align: center;
      color: var(--text-muted);
      background-color: rgba(30, 30, 47, 0.8);
      backdrop-filter: blur(10px);
    }

    /* Animasi */
    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(20px); }
      to { opacity: 1; transform: translateY(0); }
    }

    .hero, .features {
      animation: fadeIn 0.8s ease-out forwards;
    }

    .features .col-md-3:nth-child(1) { animation-delay: 0.2s; }
    .features .col-md-3:nth-child(2) { animation-delay: 0.4s; }
    .features .col-md-3:nth-child(3) { animation-delay: 0.6s; }
    .features .col-md-3:nth-child(4) { animation-delay: 0.8s; }
  </style>
</head>

<body>
  <nav class="navbar navbar-expand-lg navbar-dark px-3 py-3">
    <div class="container-fluid">
      <a class="navbar-brand fw-bold" href="#">Todo Ardi</a>
      <div>
        <a href="login2.php" class="btn btn-outline-light me-2">Login</a>
        <a href="register1.php" class="btn btn-outline-light">Register</a>
      </div>
    </div>
  </nav>

  <section class="hero">
    <h1>Todo Ardi</h1>
    <p>Rapiin Harimu, Gapai Targetmu! Buat daftar tugas harian jadi lebih terstruktur dan gampang dilacak.</p>
    <a href="login2.php" class="btn btn-custom mt-3">Mulai Sekarang</a>
  </section>

  <section class="features">
    <div class="row text-center">
      <div class="col-md-3">
        <div class="feature-icon">📝</div>
        <h5>Tambah Tugas Cepat</h5>
        <p>Input tugas harian dengan mudah dan cepat tanpa ribet.</p>
      </div>
      <div class="col-md-3">
        <div class="feature-icon">📂</div>
        <h5>Sub-task & Arsip</h5>
        <p>Kelola sub-tugas dan simpan tugas lama di arsip otomatis.</p>
      </div>
      <div class="col-md-3">
        <div class="feature-icon">🎯</div>
        <h5>Prioritas Tugas</h5>
        <p>Tandai tugas penting agar lebih fokus dan produktif.</p>
      </div>
      <div class="col-md-3">
        <div class="feature-icon">🌙</div>
        <h5>Dark Mode</h5>
        <p>Tampilan nyaman dan elegan untuk malam maupun siang hari.</p>
      </div>
    </div>
  </section>

  <footer>
    &copy; 2025 Todolist Ardi. All rights reserved.
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>