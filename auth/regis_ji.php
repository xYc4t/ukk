<?php
require "../global_ji.php";
ensure_guest();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $res = db_execute(
        "INSERT INTO user_ji (nis_ji, username_ji, password_ji) VALUES (?, ?, ?)",
        "sss",
        $_POST["nis"], $_POST["user"], password_hash($_POST["pass"], PASSWORD_BCRYPT)
    );

    if ($res['ok']) {
        header("Location: ../");
        exit;
    }

    if (isset($res['code']) && $res['code'] === 1062) {
        echo "Username atau NIS sudah dipakai!";
    } elseif (!$res['ok']) {
        echo "Terjadi kesalahan!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
</head>
<body>
    <form method="POST">
        <input type="text" name="nis" placeholder="nis" min=9 max=9 required><br>
        <input type="text" name="user" placeholder="username" required><br>
        <input type="password" name="pass" placeholder="password" required><br>
        <input type="submit" value="Register">
    </form>

    Sudah punya akun? <a href="login_ji.php">Login</a> sekarang!
</body>
</html>