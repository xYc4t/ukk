<?php
session_start();
mysqli_report(MYSQLI_REPORT_OFF);

$base = "/j";
function ensure_auth(?bool $adminOnly = null)
{
    global $base;

    if (!isset($_SESSION['id_user_ji'])) {
        header("Location: $base/auth/login_ji.php");
        exit;
    }

    $isAdmin = !empty($_SESSION['admin_ji']);

    if ($adminOnly === true && !$isAdmin) {
        header("Location: $base/auth/login_ji.php");
        exit;
    }

    if ($adminOnly === false && $isAdmin) {
        header("Location: $base/auth/login_ji.php");
        exit;
    }
}

function ensure_guest() {
    global $base;

    if (isset($_SESSION['id_user_ji'])) {
        header("Location: $base/home.php");
        exit;
    }
}

function open() {
    $conn = mysqli_connect("localhost", "root", "", "perpus_ji");
    if (!$conn) {
        die("connnection failed: " . mysqli_connect_error());
    }
    return $conn;
}

function db_execute($sql, $types, ...$params) {
    $conn = open();
    $stmt = mysqli_prepare($conn, $sql);
    if ($stmt === false) {
        return ['ok' => false, 'code' => 0, 'error' => mysqli_error($conn)];
    }

    mysqli_stmt_bind_param($stmt, $types, ...$params);

    if (!mysqli_stmt_execute($stmt)) {
        $error = mysqli_stmt_error($stmt);
        $code  = mysqli_stmt_errno($stmt);
        mysqli_stmt_close($stmt);
        return ['ok' => false, 'code' => $code, 'error' => $error];
    }

    mysqli_stmt_close($stmt);
    return ['ok' => true];
}

function db_select($sql, $types = "", ...$params) {
    $conn = open();
    $stmt = mysqli_prepare($conn, $sql);
    if ($stmt === false) return false;

    if ($types) {
        mysqli_stmt_bind_param($stmt, $types, ...$params);
    }

    if (!mysqli_stmt_execute($stmt)) {
        mysqli_stmt_close($stmt);
        return false;
    }

    $result = mysqli_stmt_get_result($stmt);
    mysqli_stmt_close($stmt);

    return $result;
}
