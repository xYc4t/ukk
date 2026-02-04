<?php
require "../global_ji.php";
ensure_auth();

$res = db_select("SELECT * FROM buku_ji WHERE status_ji = 'enabled'");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    db_execute("INSERT INTO peminjaman_ji (id_user_ji, id_buku_ji, dipinjam_ji)
                VALUES (?, ?, CURDATE())",
                "ii",
                $_SESSION["id_user_ji"], $_POST["id_buku"]);

    header("Location: " . $_SERVER['PHP_SELF']);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pinjam</title>
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
    <?php
    while ($row = mysqli_fetch_assoc($res)):
        $cek = db_select("SELECT 1 FROM peminjaman_ji
                            WHERE id_user_ji = ?
                            AND id_buku_ji = ?
                            AND status_ji = ?
                            LIMIT 1",
                            "iis",
                            $_SESSION["id_user_ji"], $row['id_buku_ji'], "dipinjam");
        $sudah = mysqli_num_rows($cek) > 0;
    ?>
        <tr>
            <th><?= htmlspecialchars($row["id_buku_ji"]) ?></th>
            <td><?= htmlspecialchars($row["judul_ji"]) ?></td>
            <td><?= htmlspecialchars($row["jumlah_ji"]) ?></td>
            <td>
                <?php
                    if ($sudah) {
                        echo "Dipinjam";
                    } elseif (!$row["jumlah_ji"] > 0) {
                        echo "Habis";
                    } else {
                ?>
                <form method="POST">
                    <input type="hidden" name="id_buku" value="<?= $row["id_buku_ji"] ?>">
                    <input type="submit" value="Pinjam">
                </form>
                <?php } ?>
            </td>
        </tr>
    <?php endwhile ?>
    </table>
</body>
</html>
