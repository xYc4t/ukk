<?php
require "../global_ji.php";
ensure_guest();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $res = db_select("SELECT *
                        FROM user_ji
                        WHERE username_ji= ?",
                        "s",
                        $_POST["user"]);

    $row = mysqli_fetch_assoc($res);

    if ($row &&
        password_verify($_POST["pass"], $row["password_ji"]) &&
        !in_array($row["status_ji"], ["pending", "disabled"], true)
    ) {
        $_SESSION["id_user_ji"] = $row["id_user_ji"];
        $_SESSION["nis_ji"] = $row["nis_ji"];
        $_SESSION["username_ji"] = $row["username_ji"];

        if ($row["status_ji"] === "admin") {
            $_SESSION["admin_ji"] = true;
        }

        header("Location: ../");
        exit;
    } else {
        echo "Login gagal. Silakan periksa kredensial Anda atau hubungi administrator!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>
<body>
    <form method="POST">
        <input type="text" name="user" placeholder="username" required><br>
        <input type="password" name="pass" placeholder="password" required><br>
        <input type="submit" value="Login">
    </form>

    Belum punya akun? <a href="regis_ji.php">Registrasi</a> sekarang!
</body>
</html>
