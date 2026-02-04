<?php
require "../global_ji.php";
ensure_auth(true);

$res = db_select("
    SELECT
        p.id_peminjaman_ji,
        p.id_user_ji,
        u.nis_ji,
        u.username_ji,
        p.id_buku_ji,
        b.judul_ji,
        p.dipinjam_ji,
        p.dikembalikan_ji,
        p.status_ji
    FROM peminjaman_ji p
    INNER JOIN user_ji u ON p.id_user_ji = u.id_user_ji
    INNER JOIN buku_ji b ON p.id_buku_ji = b.id_buku_ji
    ORDER BY p.id_peminjaman_ji DESC
");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    db_execute(
        "UPDATE peminjaman_ji
         SET dipinjam_ji = ?,
             dikembalikan_ji = ?,
             status_ji = ?
         WHERE id_peminjaman_ji = ?",
        "sssi",
        $_POST["dipinjam"],
        $_POST["dikembalikan"] ?: null,
        $_POST["status"],
        $_POST["id"]
    );

    header("Location: " . $_SERVER["PHP_SELF"]);
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Peminjaman</title>
</head>
<body>
    <a href="../">&larr; Kembali</a>

    <table border="1">
        <tr>
            <th>ID</th>
            <th>NIS</th>
            <th>Username</th>
            <th>Judul Buku</th>
            <th>Tanggal Pinjam</th>
            <th>Tanggal Kembali</th>
            <th>Status</th>
            <th>Aksi</th>
        </tr>

        <?php while ($row = mysqli_fetch_assoc($res)): ?>
        <tr>
            <form method="POST">
                <th><?= $row["id_peminjaman_ji"] ?></th>

                <td><?= htmlspecialchars($row["nis_ji"]) ?></td>
                <td><?= htmlspecialchars($row["username_ji"]) ?></td>
                <td><?= htmlspecialchars($row["judul_ji"]) ?></td>

                <td>
                    <input type="date"
                           name="dipinjam"
                           value="<?= $row["dipinjam_ji"] ?>"
                           required>
                </td>

                <td>
                    <input type="date"
                           name="dikembalikan"
                           value="<?= $row["dikembalikan_ji"] ?>">
                </td>

                <td>
                    <select name="status" required>
                        <?php
                        $statuses = ["dipinjam", "dikembalikan", "dibatalkan"];
                        foreach ($statuses as $status):
                        ?>
                            <option value="<?= $status ?>"
                                <?= $row["status_ji"] === $status ? "selected" : "" ?>>
                                <?= ucfirst($status) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </td>

                <td>
                    <input type="hidden"
                           name="id"
                           value="<?= $row["id_peminjaman_ji"] ?>">
                    <input type="submit" value="Simpan">
                </td>
            </form>
        </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>
