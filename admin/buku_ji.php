<?php
require "../global_ji.php";
ensure_auth(true);

$res = db_select("SELECT * FROM buku_ji");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST["create"])) {
        db_execute("INSERT INTO buku_ji (judul_ji, jumlah_ji)
                    VALUES (?, ?)",
                    "si",
                    $_POST["njudul"], $_POST["njumlah"]);
    } elseif (isset($_POST["delete"])) {
        db_execute("UPDATE buku_ji
                    SET status_ji = ?
                    WHERE id_buku_ji = ?",
                    "si",
                    ($_POST["status"] == "enabled") ? "disabled" : "enabled", $_POST["id"]);
    } elseif (isset($_POST["update"])) {
        db_execute("UPDATE buku_ji
                    SET judul_ji = ?, jumlah_ji = ?
                    WHERE id_buku_ji = ?",
                    "sii",
                    $_POST["judul"], $_POST["jumlah"], $_POST["id"]);
    }

    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buku</title>
</head>
<body>
    <a href="../">&larr; Kembali</a>
    <table border=1>
        <tr>
            <th>ID</th>
            <th>Judul Buku</th>
            <th>Jumlah Tersedia</th>
            <th>Aksi</th>
        </tr>
        <tr>
            <form method="POST">
                <th>Baru</th>
                <td><input type="text" name="njudul" required></td>
                <td><input type="number" name="njumlah" min=0 required></td>
                <td><input type="submit" name="create" value="Tambah"></td>
            </form>
        </tr>
    <?php while ($row = mysqli_fetch_assoc($res)): ?>
        <tr>
            <form method="POST">
                <th><?= htmlspecialchars($row["id_buku_ji"]) ?></th>
                <td>
                    <input type="text" name="judul" value="<?= htmlspecialchars($row["judul_ji"]) ?>" required>
                </td>
                <td>
                    <input type="number" name="jumlah" value="<?= $row["jumlah_ji"] ?>" min=0 required>
                </td>
                <td>
                    <input type="hidden" name="id" value="<?= $row["id_buku_ji"] ?>">
                    <input type="hidden" name="status" value="<?= $row["status_ji"] ?>">
                    <input type="submit" name="update" value="Simpan">
                    <input type="submit" name="delete" value="<?= ($row['status_ji'] === 'enabled') ? 'Disable' : 'Enable' ?>">
                </td>
            </form>
        </tr>
    <?php endwhile ?>
    </table>
</body>
</html>
