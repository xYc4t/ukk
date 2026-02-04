<?php
require "../global_ji.php";
ensure_auth();

$res = db_select("SELECT p.id_peminjaman_ji, p.id_buku_ji, b.judul_ji, p.dipinjam_ji, p.dikembalikan_ji, p.status_ji FROM peminjaman_ji p
                    INNER JOIN buku_ji b ON p.id_buku_ji = b.id_buku_ji
                    WHERE id_user_ji = ?
                    ORDER BY p.status_ji ASC, p.dipinjam_ji DESC",
                "i",
                $_SESSION["id_user_ji"]);

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    db_execute("UPDATE peminjaman_ji
                SET dikembalikan_ji = CURDATE(), status_ji = ?
                WHERE id_peminjaman_ji = ?",
                "si",
                "dikembalikan", $_POST["id"]);

    header("Location: " . $_SERVER['PHP_SELF']);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kembali</title>
</head>
<body>
    <a href="../">&larr; Kembali</a>
    <table border=1>
        <tr>
            <th>NO</th>
            <th>Judul Buku</th>
            <th>Tanggal Dipinjam</th>
            <th>Tanggal Dikembalikan</th>
        </tr>
    <?php $i = 0; while ($row = mysqli_fetch_assoc($res)): $i++ ?>
        <tr>
            <th><?= htmlspecialchars($i) ?></th>
            <td><?= htmlspecialchars($row["judul_ji"]) ?></td>
            <td><?= htmlspecialchars($row["dipinjam_ji"]) ?></td>
            <td>
                <?php if ($row["status_ji"] === "dikembalikan") { echo htmlspecialchars($row["dikembalikan_ji"]); } else { ?>
                <form method="POST">
                    <input type="hidden" name="id" value="<?= $row["id_peminjaman_ji"] ?>">
                    <input type="submit" value="Kembalikan">
                </form>
                <?php } ?>
            </td>
        </tr>
    <?php endwhile; ?>
</body>
</html>
