<?php
require "global_ji.php";
ensure_auth();

if (isset($_GET["logout"])) {
    session_destroy();
    header("Location: auth/");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
</head>
<body>
    User: <?= $_SESSION["username_ji"] ?> (<?= $_SESSION["nis_ji"] ?>)<br>
    <a href="?logout=1">Logout</a><br><br>
    <?php if (isset($_SESSION["admin_ji"])): ?>
        <a href="admin/buku_ji.php">Kelola data buku</a><br>
        <a href="admin/transaksi_ji.php">Transaksi</a><br>
        <a href="admin/user_ji.php">Kelola anggota</a>
    <?php else: ?>
        <a href="user/pinjam_ji.php">Peminjaman buku</a><br>
        <a href="user/kembali_ji.php">Pengembalian buku</a>
    <?php endif; ?>
</body>
</html>