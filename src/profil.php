<?php
include "proses/connect.php";
if (empty($_SESSION['id_alomani'] ?? null)) {
    $_SESSION['redirect_url'] = $_SERVER['REQUEST_URI'];
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['id_alomani'];
$query = mysqli_query($conn, "SELECT * FROM tb_user WHERE id = '$user_id'");
$user = mysqli_fetch_assoc($query);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil - Batik Alomani</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .profile-pic {
            width: 150px;
            height: 150px;
            object-fit: cover;
            border-radius: 50%;
        }
    </style>
</head>
<body>
    <?php include "component/header.php"; ?>

    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0">Edit Profil</h4>
                    </div>
                    <div class="card-body">
                        <?php if (isset($_GET['error'])): ?>
                            <div class="alert alert-danger"><?= htmlspecialchars($_GET['error']) ?></div>
                        <?php endif; ?>
                        
                        <?php if (isset($_GET['success'])): ?>
                            <div class="alert alert-success"><?= htmlspecialchars($_GET['success']) ?></div>
                        <?php endif; ?>

                        <form action="proses_ubah_profil.php" method="POST" enctype="multipart/form-data">
                            <div class="text-center mb-4">
                                <img src="../assets/images/profil/<?= $user['foto'] ?? 'default.jpg' ?>" 
                                     class="profile-pic mb-2" id="previewFoto">
                                <input type="file" name="foto" id="foto" class="d-none" accept="image/*">
                                <label for="foto" class="btn btn-sm btn-primary">
                                    <i class="fas fa-camera me-1"></i> Ganti Foto
                                </label>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Nama Lengkap</label>
                                <input type="text" class="form-control" name="name" 
                                       value="<?= htmlspecialchars($user['nama']) ?>" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Username (Email)</label>
                                <input type="email" class="form-control" name="username" 
                                       value="<?= htmlspecialchars($user['username']) ?>" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Nomor HP</label>
                                <input type="text" class="form-control" name="nohp" 
                                       value="<?= htmlspecialchars($user['nohp']) ?>" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Alamat</label>
                                <textarea class="form-control" name="alamat" rows="3"><?= htmlspecialchars($user['alamat']) ?></textarea>
                            </div>

                            <hr class="my-4">

                            <h5>Ganti Password</h5>
                            <div class="mb-3">
                                <label class="form-label">Password Saat Ini</label>
                                <input type="password" class="form-control" name="current_password">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Password Baru</label>
                                <input type="password" class="form-control" name="new_password">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Konfirmasi Password</label>
                                <input type="password" class="form-control" name="confirm_password">
                            </div>

                            <div class="d-flex justify-content-between">
                                <a href="index.php" class="btn btn-outline-secondary">Kembali</a>
                                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Preview foto
        document.getElementById('foto').addEventListener('change', function(e) {
            const reader = new FileReader();
            reader.onload = function(event) {
                document.getElementById('previewFoto').src = event.target.result;
            };
            reader.readAsDataURL(e.target.files[0]);
        });
    </script>
</body>
</html>