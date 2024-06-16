<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

$link = mysqli_connect('localhost', 'root', '', 'fitness_hub');

if (!$link) {
    die("無法打開數據庫!<br/>");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $verification_code = mysqli_real_escape_string($link, $_POST['verification_code']);
    $user_id = $_SESSION['id'];

    $sql = "SELECT id FROM member WHERE id = ? AND verification_code = ?";
    if ($stmt = mysqli_prepare($link, $sql)) {
        mysqli_stmt_bind_param($stmt, "is", $user_id, $verification_code);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);

        if (mysqli_stmt_num_rows($stmt) == 1) {
            $sql_update = "UPDATE member SET verified = 1 WHERE id = ?";
            if ($stmt_update = mysqli_prepare($link, $sql_update)) {
                mysqli_stmt_bind_param($stmt_update, "i", $user_id);
                mysqli_stmt_execute($stmt_update);
                mysqli_stmt_close($stmt_update);

                $_SESSION['success'] = "驗證成功，您現在可以登入。";
                header("Location: login.php");
                exit;
            } else {
                $_SESSION['error'] = "驗證失敗：" . mysqli_error($link);
                header("Location: verify.php");
                exit;
            }
        } else {
            $_SESSION['error'] = "驗證碼不正確，請重新輸入。";
            header("Location: verify.php");
            exit;
        }
        mysqli_stmt_close($stmt);
    } else {
        $_SESSION['error'] = "資料庫查詢失敗：" . mysqli_error($link);
        header("Location: verify.php");
        exit;
    }

    mysqli_close($link);
}
