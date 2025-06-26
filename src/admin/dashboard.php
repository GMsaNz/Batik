<?php
session_start();

include "../proses/connect.php";

// Kalau belum login
if (!isset($_SESSION['username_alomani'])) {
    header('Location: ../login.php');
    exit();
}

// Kalau bukan level 1
if ($_SESSION['level_alomani'] != 1) {
    echo "Anda tidak memiliki akses ke halaman ini.";
    exit();
}
if (!isset($_SESSION['first_login'])) {
    $_SESSION['first_login'] = true;
} else {
    $_SESSION['first_login'] = false;
}




// Query untuk data user
$query_user = mysqli_query($conn, "SELECT * FROM tb_user WHERE username='$_SESSION[username_alomani]'");

// Inisialisasi variabel $result sebagai array kosong
$result = array();

// Query untuk data produk
$query_produk = mysqli_query($conn, "SELECT * FROM tb_produk LEFT JOIN tb_kategori ON tb_kategori.kategori = tb_produk.katagori");
if ($query_produk) {
    while ($record = mysqli_fetch_array($query_produk)) {
        $result[] = $record;
    }
}
$order = mysqli_query($conn, "SELECT o.id_order,o.nama,o.nohp,d.ukuran,o.alamat,o.provinsi,o.kota,o.kecamatan,o.kelurahan,o.kode_pos,o.status_pembayaran,o.status_pengiriman,o.resi,o.grand_total,d.nama_barang,d.ongkir,d.jumlah,d.harga FROM tb_order o LEFT JOIN tb_order_detail d ON o.id_order = d.id_order");
$select_kategori = mysqli_query($conn, "SELECT id_kategori,kategori FROM tb_kategori");
$produk = mysqli_query($conn, "SELECT id_produk,nama_barang,stok FROM tb_produk");
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Batik Alomani</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/styles.css">

</head>

<body>
    <!-- Halaman Main -->
    <link rel="stylesheet" href="../assets/css/styles.css">

    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3 col-lg-2 sidebar">
                <div class="text-center mb-4">
                    <img src="../assets/images/logo.png" alt="Batik Alomani Logo" style="max-width: 150px;">
                </div>
                <nav class="nav flex-column">
                    <a class="nav-link active" href="#dashboard" onclick="showSection('dashboard')">
                        <i class="fas fa-home"></i> Dashboard
                    </a>
                    <a class="nav-link" href="#orders" onclick="showSection('orders')">
                        <i class="fas fa-shopping-cart"></i> Pesanan
                    </a>
                    <a class="nav-link" href="#products" onclick="showSection('products')">
                        <i class="fas fa-tshirt"></i> Produk
                    </a>
                    <a class="nav-link" href="#stock" onclick="showSection('stock')">
                        <i class="fas fa-boxes"></i> Stok
                    </a>

                </nav>
            </div>

            <!-- Main Content -->
            <div class="col-md-9 col-lg-10 main-content">
                <div class="dashboard-header">
                    <h2>Selamat Datang, <?php echo $_SESSION['nama_alomani']; ?></h2>
                    <form action="../logout.php" method="post">
                        <button type="submit" class="btn-logout">
                            <i class="fas fa-sign-out-alt"></i> Logout
                        </button>
                    </form>
                </div>

                <!-- Dashboard Section -->
                <div id="section-dashboard">
                    <!-- Statistics Cards -->
                    <div class="row">
                        <?php
                        $query_berhasil = "SELECT 
                    COUNT(*) as jumlah_order, 
                    SUM(COALESCE(grand_total, 0)) as total_pendapatan 
                   FROM tb_order 
                   WHERE status_pembayaran = 'Berhasil'";

                        $result_berhasil = mysqli_query($conn, $query_berhasil);
                        $data_berhasil = mysqli_fetch_assoc($result_berhasil);

                        $jumlah_order_berhasil = $data_berhasil['jumlah_order'] ?? 0;
                        $total_pendapatan_berhasil = $data_berhasil['total_pendapatan'] ?? 0;

                        // Hitung total grand_total dari semua order
                        $query_total = "SELECT SUM(COALESCE(grand_total, 0)) as total_semua FROM tb_order";
                        $result_total = mysqli_query($conn, $query_total);
                        $row_total = mysqli_fetch_assoc($result_total);
                        $total_semua = $row_total['total_semua'] ?? 0;
                        ?>
                        <div class="col-md-3">
                            <div class="stat-card">
                                <h3>
                                    <tfoot>
                                        <tr>
                                            <th>Rp <?= number_format($total_semua, 0, ',', '.') ?></th>
                                            <th colspan="4"></th>
                                        </tr>
                                    </tfoot>
                                </h3>
                                <p>Total Pendapatan</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="stat-card">
                                <h3>
                                    <tr>
                                        <th colspan="3"><?= $jumlah_order_berhasil ?></th>
                                        
                                    </tr>
                                </h3>
                                <p>Order Diterima</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="stat-card">
                                <h3>45</h3>
                                <p>Produk Terjual</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="stat-card">
                                <h3>98%</h3>
                                <p>Kepuasan Pelanggan</p>
                            </div>
                        </div>
                    </div>

                    <!-- Sales Chart -->
                    <div class="table-container">
                        <canvas id="salesChart"></canvas>
                    </div>
                </div>

                <!-- Pesanan Section -->

                <div id="section-orders" style="display:none">
                    <div class="table-container">
                        <h4>Daftar Pesanan</h4>
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Pelanggan</th>
                                        <th>Produk</th>
                                        <th>Jumlah</th>
                                        <th>Harga</th>
                                        <th>Status Pembayaran</th>
                                        <th>Pengiriman</th>
                                        <th>Resi</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($order)): ?>
                                        <?php foreach ($order as $row): ?>
                                            <tr>
                                                <td><?php echo $row['nama'] ?> </td>
                                                <td><?php echo $row['nama_barang'] ?> </td>
                                                <td><?php echo $row['jumlah'] ?> </td>
                                                <td>Rp <?= number_format($row['harga'], 0, ',', '.') ?></td>
                                                <td><span class="badge bg-<?php
                                                echo ($row['status_pembayaran'] == 'Berhasil') ? 'success' :
                                                    (($row['status_pembayaran'] == 'Pending') ? 'warning' : 'secondary');
                                                ?>">
                                                        <?php echo htmlspecialchars(ucfirst($row['status_pembayaran'])); ?>
                                                    </span></td>
                                                <td><span class="badge bg-<?php
                                                echo ($row['status_pengiriman'] == 'Dikirim') ? 'success' :
                                                    (($row['status_pengiriman'] == 'Belum Dikirim') ? 'danger' : 'secondary');
                                                ?>">
                                                        <?php echo htmlspecialchars(ucfirst($row['status_pengiriman'])); ?>
                                                    </span></td>
                                                <td><?php echo $row['resi'] ?> </td>
                                                <td>
                                                    <button class="btn btn-primary" data-bs-toggle="modal"
                                                        data-bs-target="#ModalView<?php echo $row['id_order']; ?>">
                                                        Detail Order</button>
                                                    <button class="btn btn-success" data-bs-toggle="modal"
                                                        data-bs-target="#ModalTambahResi<?php echo $row['id_order']; ?>">
                                                        Input Resi</button>
                                                </td>
                                            </tr>

                                            <!-- Modal view -->
                                            <div class="modal fade" id="ModalView<?php echo $row['id_order']; ?>" tabindex="-1"
                                                aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog modal-xl modal-fullscreen-md-down">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h1 class="modal-title fs-5" id="exampleModalLabel">Detail Pesanan
                                                            </h1>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                                aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <form class="needs-validation" novalidate
                                                                action="proses/proses_input_menu.php" method="POST"
                                                                enctype="multipart/form-data">
                                                                <div class="row">

                                                                    <div class="col-lg-6">
                                                                        <form action="">
                                                                            <div class="form-floating mb-3">
                                                                                <input disabled type="text" class="form-control"
                                                                                    id="floatingInput"
                                                                                    value="<?php echo $row['nama'] ?>">
                                                                                <label for="floatingInput">Nama</label>
                                                                                <div class="invalid-feedback">

                                                                                </div>
                                                                            </div>
                                                                    </div>
                                                                    <div class="col-lg-6">
                                                                        <div class="form-floating mb-3">
                                                                            <input disabled type="text" class="form-control"
                                                                                id="floatingInput"
                                                                                value="<?php echo $row['nohp'] ?>">
                                                                            <label for="floatingPassword">No Hp</label>
                                                                        </div>
                                                                    </div>

                                                                </div>
                                                                <div class="row">

                                                                    <div class="col-lg-6">
                                                                        <form action="">
                                                                            <div class="form-floating mb-3">
                                                                                <input disabled type="text" class="form-control"
                                                                                    id="floatingInput"
                                                                                    value="Rp <?= number_format($row['ongkir'], 0, ',', '.') ?>">
                                                                                <label for="floatingInput">Ongkos Kirim</label>
                                                                                <div class="invalid-feedback">

                                                                                </div>
                                                                            </div>
                                                                    </div>
                                                                    <div class="col-lg-6">
                                                                        <div class="form-floating mb-3">
                                                                            <input disabled type="text" class="form-control"
                                                                                id="floatingInput"
                                                                                value="Rp <?= number_format($row['grand_total'], 0, ',', '.') ?>">
                                                                            <label for="floatingPassword">Total Dibayar</label>
                                                                        </div>
                                                                    </div>

                                                                </div>
                                                                <div class="row">
                                                                    <div class="col-lg-12">
                                                                        <div class="form-floating mb-3">
                                                                            <input disabled type="text" class="form-control"
                                                                                id="floatingInput"
                                                                                value="<?php echo $row['alamat'] ?> KEL.<?php echo $row['kelurahan'] ?>, KEC.<?php echo $row['kecamatan'] ?>, KOTA <?php echo $row['kota'] ?>, <?php echo $row['provinsi'] ?>, <?php echo $row['kode_pos'] ?>">
                                                                            <label for="floatingPassword">Alamat</label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary"
                                                                        data-bs-dismiss="modal">Close</button>

                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- Modal akhir view -->

                                            <!-- modal tambah Resi -->
                                            <div class="modal fade" id="ModalTambahResi<?php echo $row['id_order']; ?>"
                                                tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog modal-xl modal-fullscreen-md-down">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h1 class="modal-title fs-5" id="exampleModalLabel">Input Resi</h1>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                                aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <form class="needs-validation" novalidate
                                                                action="../proses/proses_resi.php" method="POST"
                                                                enctype="multipart/form-data">
                                                                <div class="row">
                                                                    <div class="col-lg-6">
                                                                        <div class="form-floating mb-3">
                                                                            <input type="text" class="form-control"
                                                                                id="floatingOrderId" placeholder="Order ID"
                                                                                name="id_order"
                                                                                value="<?php echo $row['id_order'] ?>" readonly>
                                                                            <label for="floatingOrderId">Order ID</label>
                                                                            <div class="invalid-feedback">

                                                                            </div>
                                                                        </div>
                                                                    </div>

                                                                    <div class="col-lg-6">
                                                                        <div class="form-floating mb-3">
                                                                            <input type="text" class="form-control"
                                                                                id="floatingInput" placeholder="Nama Menu"
                                                                                name="resi" required>
                                                                            <label for="floatingInput">Masukkan Resi</label>
                                                                            <div class="invalid-feedback">
                                                                                Masukan Resi
                                                                            </div>
                                                                        </div>
                                                                    </div>

                                                                </div>

                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary"
                                                                        data-bs-dismiss="modal">Close</button>
                                                                    <button type="submit" class="btn btn-primary"
                                                                        name="input_resi_validate"
                                                                        value="1234567">Simpan</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- akhir tambah Resi -->

                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="7" class="text-center">Tidak ada data produk</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Produk Section -->
                <div id="section-products" style="display:none">
                    <div class="table-container">
                        <div class="row">
                            <div class="col d-flex justify-content-end">
                                <button class="btn btn-primary" data-bs-toggle="modal"
                                    data-bs-target="#ModalTambahUser">
                                    Tambah Produk</button>
                            </div>
                        </div>
                        <h4>Daftar Produk</h4>
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>ID Produk</th>
                                        <th>Foto</th>
                                        <th>Nama Produk</th>
                                        <th>Kategori</th>
                                        <th>Harga</th>
                                        <th>Stok</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($result)): ?>
                                        <?php foreach ($result as $row): ?>
                                            <tr>
                                                <td><?= htmlspecialchars($row['id_produk']) ?></td>
                                                <td>
                                                    <div style="width:90px">
                                                        <img src="../assets/images/<?php echo $row['foto'] ?>"
                                                            class="img-thumbnail" alt="...">
                                                    </div>
                                                </td>
                                                <td><?= htmlspecialchars($row['nama_barang']) ?></td>
                                                <td>
                                                    <?php
                                                    if (!empty($row['katagori'])) {
                                                        $categoryParts = explode(':', $row['katagori'], 2);
                                                        echo htmlspecialchars(trim($categoryParts[1] ?? $row['katagori']));
                                                    } else {
                                                        echo 'Tidak ada kategori';
                                                    }
                                                    ?>
                                                </td>
                                                <td>Rp <?= number_format($row['harga'], 0, ',', '.') ?></td>
                                                <td><?= htmlspecialchars($row['stok']) ?></td>
                                                <td>
                                                    <div class="d-flex">
                                                        <button class="btn btn-warning btn-sm me-1" data-bs-toggle="modal"
                                                            data-bs-target="#ModalEdit<?php echo $row['id_produk'] ?>"><i
                                                                class="bi bi-pencil-square">EDIT</i></button>
                                                        <button class="btn btn-danger btn-sm me-1" data-bs-toggle="modal"
                                                            data-bs-target="#ModalDelete<?php echo $row['id_produk'] ?>"><i
                                                                class="bi bi-trash">DELETE</i></button>
                                                    </div>


                                                    <!-- Modal Delete -->
                                                    <div class="modal fade" id="ModalDelete<?php echo $row['id_produk'] ?>"
                                                        tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                        <div class="modal-dialog modal-md modal-fullscreen-md-down">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h1 class="modal-title fs-5" id="exampleModalLabel">Hapus
                                                                        Data User</h1>
                                                                    <button type="button" class="btn-close"
                                                                        data-bs-dismiss="modal" aria-label="Close"></button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <form class="needs-validation" novalidate
                                                                        action="../proses/proses_hapus_produk.php"
                                                                        method="POST">
                                                                        <input type="hidden"
                                                                            value="<?php echo $row['id_produk'] ?>"
                                                                            name="id_produk">
                                                                        <input type="hidden" value="<?php echo $row['foto'] ?>"
                                                                            name="foto">
                                                                        <div class="col-lg-12">
                                                                            Apakah anda ingin menghapus Produk
                                                                            <b><?php echo $row['nama_barang'] ?></b>
                                                                        </div>
                                                                        <div class="modal-footer">
                                                                            <button type="button" class="btn btn-secondary"
                                                                                data-bs-dismiss="modal">Close</button>
                                                                            <button type="submit" class="btn btn-danger"
                                                                                name="input_user_validate"
                                                                                value="1234">Hapus</button>

                                                                        </div>
                                                                    </form>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!-- Modal akhir Delete -->
                                                    <!-- Modal Edit -->
                                                    <div class="modal fade" id="ModalEdit<?php echo $row['id_produk'] ?>"
                                                        tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                        <div class="modal-dialog modal-xl modal-fullscreen-md-down">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h1 class="modal-title fs-5" id="exampleModalLabel">EDIT
                                                                        PRODUK</h1>
                                                                    <button type="button" class="btn-close"
                                                                        data-bs-dismiss="modal" aria-label="Close"></button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <form class="needs-validation" novalidate
                                                                        action="../proses/proses_edit_produk.php" method="POST"
                                                                        enctype="multipart/form-data">
                                                                        <input type="hidden"
                                                                            value="<?php echo $row['id_produk'] ?>"
                                                                            name="id_produk">
                                                                        <div class="row">
                                                                            <div class="col-lg-6">
                                                                                <div class="input-group mb-3">
                                                                                    <input type="file" class="form-control py-3"
                                                                                        id="uploadFoto" placeholder="Your Name"
                                                                                        name="foto" required>
                                                                                    <label class="input-group-text"
                                                                                        for="uploadFoto">Upload Foto
                                                                                        Menu</label>
                                                                                    <div class="invalid-feedback">
                                                                                        Masukan File Foto Menu
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-lg-6">
                                                                                <div class="form-floating mb-3">
                                                                                    <input type="text" class="form-control"
                                                                                        id="floatingInput"
                                                                                        placeholder="Nama Menu"
                                                                                        name="nama_barang" required
                                                                                        value="<?php echo $row['nama_barang'] ?>">
                                                                                    <label for="floatingInput">Nama
                                                                                        Produk</label>
                                                                                    <div class="invalid-feedback">
                                                                                        Masukan Nama Produk.
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="row">
                                                                            <div class="col-lg-12">
                                                                                <div class="form-floating mb-3">
                                                                                    <input type="text" class="form-control"
                                                                                        id="floatingInput"
                                                                                        placeholder="keterangan"
                                                                                        name="deskripsi"
                                                                                        value="<?php echo $row['deskripsi'] ?>">
                                                                                    <label
                                                                                        for="floatingPassword">deskripsi</label>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="row">

                                                                            <div class="col-lg-6">

                                                                                <div class="form-floating mb-3">
                                                                                    <input type="number" class="form-control"
                                                                                        id="floatingInput" placeholder="Harga"
                                                                                        name="harga" required
                                                                                        value="<?php echo $row['harga'] ?>">
                                                                                    <label for="floatingInput">Harga</label>
                                                                                    <div class="invalid-feedback">
                                                                                        Masukkan harga
                                                                                    </div>
                                                                                </div>
                                                                            </div>

                                                                            <div class="col-lg-6">

                                                                                <div class="form-floating mb-3">
                                                                                    <input disabled type="number"
                                                                                        class="form-control" id="floatingInput"
                                                                                        placeholder="Stok" name="stok" required
                                                                                        value="<?php echo $row['stok'] ?>"
                                                                                        readonly>
                                                                                    <label for="floatingInput">Stok</label>
                                                                                    <div class="invalid-feedback">
                                                                                        Masukkan stok
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>

                                                                        <div class="modal-footer">
                                                                            <button type="button" class="btn btn-secondary"
                                                                                data-bs-dismiss="modal">Close</button>
                                                                            <button type="submit" class="btn btn-primary"
                                                                                name="input_menu_validate" value="1234">Save
                                                                                changes</button>
                                                                        </div>
                                                                    </form>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!-- Modal akhir Edit -->
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="7" class="text-center">Tidak ada data produk</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <!-- akhir -->

                <!-- modal tambah produk -->
                <div class="modal fade" id="ModalTambahUser" tabindex="-1" aria-labelledby="exampleModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog modal-xl modal-fullscreen-md-down">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5" id="exampleModalLabel">Tambah Produk</h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form class="needs-validation" novalidate action="../proses/proses_input_produk.php"
                                    method="POST" enctype="multipart/form-data">
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <div class="input-group mb-3">
                                                <input type="file" class="form-control py-3" id="uploadFoto"
                                                    placeholder="Your Name" name="foto" required>
                                                <label class="input-group-text" for="uploadFoto">Upload Foto
                                                    Produk</label>
                                                <div class="invalid-feedback">
                                                    Masukan File Foto Produk
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="form-floating mb-3">
                                                <input type="text" class="form-control" id="kodeProduk"
                                                    placeholder="Kode Produk" name="kode_produk" required value="PK">
                                                <label for="kodeProduk">Kode Produk</label>
                                                <div class="invalid-feedback">
                                                    Masukkan Kode Produk
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class="form-floating mb-3">
                                                    <input type="text" class="form-control" id="floatingInput"
                                                        placeholder="Nama Menu" name="nama_barang" required>
                                                    <label for="floatingInput">Nama Produk</label>
                                                    <div class="invalid-feedback">
                                                        Masukan Nama Produk
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="form-floating mb-3">
                                                <input type="text" class="form-control" id="floatingInput"
                                                    placeholder="keterangan" name="deskripsi">
                                                <label for="floatingPassword">Deskripsi</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-4">
                                            <div class="form-floating mb-3">
                                                <select class="form-select" aria-label="Default select example"
                                                    name="katagori" required>
                                                    <option selected hidden value="">Pilih Kategori Produk</option>
                                                    <?php
                                                    foreach ($select_kategori as $value) {
                                                        echo "<option value=" . $value['id_kategori'] . ">$value[kategori]</option>";
                                                    }
                                                    ?>
                                                </select>
                                                <label for="floatingInput">Kategori Produk</label>
                                                <div class="invalid-feedback">
                                                    Pilih kategori Produk
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-4">

                                            <div class="form-floating mb-3">
                                                <input type="number" class="form-control" id="floatingInput"
                                                    placeholder="Harga" name="harga" required>
                                                <label for="floatingInput">Harga</label>
                                                <div class="invalid-feedback">
                                                    Masukkan harga
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-lg-4">

                                            <div class="form-floating mb-3">
                                                <input type="number" class="form-control" id="floatingInput"
                                                    placeholder="Stok" name="stok" required>
                                                <label for="floatingInput">Stok</label>
                                                <div class="invalid-feedback">
                                                    Masukkan stok
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary"
                                            data-bs-dismiss="modal">Close</button>
                                        <button type="submit" class="btn btn-primary" name="input_menu_validate"
                                            value="1234">Simpan</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- akhir tambah produk -->



                <!-- Stok Section -->
                <div id="section-stock" style="display:none">
                    <div class="table-container">
                        <h4>Manajemen Stok</h4>
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Kode Produk</th>
                                        <th>Nama Produk</th>
                                        <th>Stok Tersedia</th>
                                        <th>Stok Minimum</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($result)): ?>
                                        <?php foreach ($result as $row): ?>
                                            <?php
                                            $stok = (int) ($row['stok'] ?? 0);
                                            $status_class = '';
                                            $status_text = '';

                                            if ($stok < 5) {
                                                $status_class = 'danger';
                                                $status_text = 'Hampir Habis';
                                            } elseif ($stok >= 5 && $stok < 10) {
                                                $status_class = 'warning';
                                                $status_text = 'Menengah';
                                            } elseif ($stok >= 10 && $stok < 15) {
                                                $status_class = 'info';
                                                $status_text = 'Cukup';
                                            } else {
                                                $status_class = 'success';
                                                $status_text = 'Aman';
                                            }
                                            ?>
                                            <tr>
                                                <td><?= htmlspecialchars($row['kode_produk'] ?? '') ?></td>
                                                <td><?= htmlspecialchars($row['nama_barang'] ?? '') ?></td>
                                                <td><?= $stok ?></td>
                                                <td>10</td>
                                                <td><span class="badge bg-<?= $status_class ?>"><?= $status_text ?></span></td>
                                                <td>
                                                    <button class="btn btn-primary btn-sm me-1" data-bs-toggle="modal"
                                                        data-bs-target="#ModalStok"
                                                        data-kode="<?= htmlspecialchars($row['kode_produk'] ?? '') ?>"
                                                        data-nama="<?= htmlspecialchars($row['nama_barang'] ?? '') ?>"
                                                        data-stok="<?= htmlspecialchars($row['stok'] ?? 0) ?>"
                                                        data-id="<?= htmlspecialchars($row['id_produk'] ?? '') ?>">
                                                        Tambah
                                                    </button>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="6" class="text-center text-muted py-4">Tidak ada data stok</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <!-- akhir stok -->

                <!-- Modal Tambah Stok (Single Modal) -->
                <div class="modal fade" id="ModalStok" tabindex="-1" aria-labelledby="ModalStokLabel"
                    aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header bg-primary text-white">
                                <h5 class="modal-title" id="ModalStokLabel">Tambah Stok Produk</h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <form id="formTambahStok" class="needs-validation" novalidate
                                action="../proses/proses_tambah_stok.php" method="POST">
                                <div class="modal-body">
                                    <!-- Input Kode Produk (read-only) -->
                                    <div class="form-floating mb-3">
                                        <input type="text" class="form-control" id="kodeProduk" name="id_produk"
                                            value="<?php echo htmlspecialchars($row['id_produk'] ?? ''); ?>" readonly>
                                        <label for="kodeProduk">Kode Produk</label>
                                    </div>

                                    <!-- Input Nama Produk (read-only, optional) -->
                                    <div class="form-floating mb-3">
                                        <input type="text" class="form-control" id="namaProduk" name="nama_barang"
                                            value="<?php echo htmlspecialchars($row['nama_barang'] ?? ''); ?>" readonly>
                                        <label for="namaProduk">Nama Produk</label>
                                    </div>

                                    <!-- Input Stok Saat Ini (read-only, optional) -->
                                    <div class="form-floating mb-3">
                                        <input type="number" class="form-control" id="stokSekarang"
                                            value="<?php echo htmlspecialchars($row['stok'] ?? 0); ?>" readonly>
                                        <label for="stokSekarang">Stok Saat Ini</label>
                                    </div>

                                    <!-- Input Tambah Stok -->
                                    <div class="form-floating mb-3">
                                        <input type="number" class="form-control" id="jumlahStok" name="stok" min="1"
                                            required>
                                        <label for="jumlahStok">Jumlah Stok Tambahan</label>
                                        <div class="invalid-feedback">
                                            Harap masukkan jumlah stok yang valid (minimal 1)
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary"
                                        data-bs-dismiss="modal">Batal</button>
                                    <button type="submit" class="btn btn-primary" name="input_stok_validate"
                                        value="1234">Simpan</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <!-- akhir modal stok -->

                <!-- Laporan Section -->
                <div id="section-reports" style="display:none">
                    <div class="table-container">
                        <h4>Laporan Penjualan</h4>
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Bulan</th>
                                        <th>Total Penjualan</th>
                                        <th>Produk Terjual</th>
                                        <th>Pendapatan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Januari</td>
                                        <td>120</td>
                                        <td>80</td>
                                        <td>Rp 12.000.000</td>
                                    </tr>
                                    <tr>
                                        <td>Februari</td>
                                        <td>100</td>
                                        <td>70</td>
                                        <td>Rp 10.000.000</td>
                                    </tr>
                                    <tr>
                                        <td>Maret</td>
                                        <td>140</td>
                                        <td>90</td>
                                        <td>Rp 15.000.000</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <!-- Pengaturan Section -->

            </div>
        </div>
    </div>

    <!-- Javascript -->
    <?php include 'livechat.php'; ?>
</body>

</html>