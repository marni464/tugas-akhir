<?php
require_once "../konfigurasi/koneksi.php";

if (isset($_SESSION['pengguna'])) {
    header("Location: ../beranda/index.php");
    exit;
}

$pesan_error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    $stmt = $koneksi->prepare("SELECT id, username, password_hash, nama, role FROM users WHERE username=? LIMIT 1");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $user = $stmt->get_result()->fetch_assoc();

    if ($user && password_verify($password, $user['password_hash'])) {
        $_SESSION['pengguna'] = [
            'id' => $user['id'],
            'username' => $user['username'],
            'nama' => $user['nama'],
            'role' => $user['role'],
        ];
        header("Location: ../beranda/index.php");
        exit;
    } else {
        $pesan_error = "Username atau password salah.";
    }
}
?>
<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <title>Login - Sistem SAMSAT</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: #eef1df;
        }

        .kartu {
            border-radius: 14px;
        }
    </style>
</head>

<body class="d-flex align-items-center" style="min-height:100vh;">
    <div class="container">
        <div class="text-center mb-3">
            <img src="../aset/img/Logo.png" style="height:90px" alt="Logo">
        </div>

        <div class="card shadow mx-auto kartu" style="max-width:720px;">
            <div class="card-body p-5">
                <h2 class="text-center mb-4">SILAKAN LOGIN</h2>

                <?php if ($pesan_error): ?>
                    <div class="alert alert-danger"><?= e($pesan_error) ?></div>
                <?php endif; ?>

                <form method="POST">
                    <div class="mb-3">
                        <label class="form-label">Masukkan Username</label>
                        <input name="username" class="form-control" required>
                    </div>
                    <div class="mb-4">
                        <label class="form-label">Masukkan Password</label>
                        <input name="password" type="password" class="form-control" required>
                    </div>
                    <div class="d-flex justify-content-end">
                        <button class="btn btn-primary px-5">Login</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</body>

</html>