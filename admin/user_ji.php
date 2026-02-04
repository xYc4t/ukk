<?php
require "../global_ji.php";
ensure_auth(true);

$res = db_select("SELECT * FROM user_ji");
$statuses = ["admin", "user", "pending", "disabled"];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST["create"])) {
        db_execute("INSERT INTO user_ji (nis_ji, username_ji, password_ji, status_ji)
                    VALUES (?, ?, ?, ?)",
                    "ssss",
                    $_POST["nnis"], $_POST["nusername"], password_hash($_POST["npassword"], PASSWORD_BCRYPT), $_POST["nstatus"]);
    } elseif (isset($_POST["update"])) {
        db_execute("UPDATE user_ji
                    SET nis_ji = ?, username_ji = ?, status_ji = ?
                    WHERE id_user_ji = ?",
                    "sssi",
                    $_POST["nis"], $_POST["username"], $_POST["status"], $_POST["id"]);
    }

    header("Location: " . $_SERVER["PHP_SELF"]);
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User</title>
</head>
<body>
    <a href="../">&larr; Kembali</a>

    <table border="1">
        <tr>
            <th>ID</th>
            <th>NIS</th>
            <th>Username</th>
            <th>Password</th>
            <th>Status</th>
            <th>Aksi</th>
        </tr>
        <tr>
            <form method="POST">
                <th>Baru</th>
                <td><input type="text" name="nnis" minlength=9 maxlength=9 required></td>
                <td><input type="text" name="nusername" required></td>
                <td><input type="password" name="npassword" required></td>
                <td>
                    <select name="nstatus" required>
                    <?php foreach ($statuses as $status): ?>
                        <option value="<?= $status ?>">
                            <?= $status ?>
                        </option>
                    <?php endforeach; ?>
                    </select>
                </td>
                <td><input type="submit" name="create" value="Tambah"></td>
            </form>
        </tr>
        <?php while ($row = mysqli_fetch_assoc($res)): ?>
        <tr>
            <form method="POST">
                <th><?= htmlspecialchars($row["id_user_ji"]) ?></th>
                <td>
                    <input type="text" name="nis" value="<?= htmlspecialchars($row["nis_ji"]) ?>" minlength=9 maxlength=9 required>
                </td>
                <td>
                    <input type="text" name="username" value="<?= htmlspecialchars($row["username_ji"]) ?>" required>
                </td>
                <td>
                    <input type="password" name="password" value="<?= htmlspecialchars($row["password_ji"]) ?>" required>
                </td>
                <td>
                    <select name="status" required>
                    <?php foreach ($statuses as $status): ?>
                        <option value="<?= $status ?>"<?= $row["status_ji"] === $status ? "selected" : "" ?>>
                            <?= $status ?>
                        </option>
                    <?php endforeach; ?>
                    </select>
                </td>
                <td>
                    <input type="hidden" name="id" value="<?= $row["id_user_ji"] ?>">
                    <input type="submit" name="update" value="Simpan">
                </td>
            </form>
        </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>
