<?php
include "connect.php";
$id_produk = isset($_POST['id_produk']) ? htmlentities($_POST['id_produk']) : "";
$foto =isset($_POST['foto']) ? htmlentities($_POST['foto']) : "";
if (!empty($_POST['input_user_validate'])) {
    $query = mysqli_query($conn,"DELETE FROM tb_produk WHERE id_produk = '$id_produk'");
    if($query) {
        unlink("../assets/images/$foto");
        $message = '<script>alert("Data berhasil dihapus");
        window.location="../admin/dashboard.php#products"</script>';
    }else{
        $message = '<script>alert("Data gagal dihapus");
        window.location="../../admin/dashboard.php#products"</script>';
    }
}echo $message;
?>