<?php
session_start();

// 檢查用戶是否為管理員
if (!isset($_SESSION['manager_loggedin']) || $_SESSION['manager_loggedin'] !== true) {
    header("Location: manager_login.php");
    exit;
}

// 連接數據庫
$link = mysqli_connect(
    'localhost', 
    'root', 
    '', 
    'fitness_hub');

// 檢查數據庫連接
if (!$link) {
    die("數據庫連接失敗: " . mysqli_connect_error());
}

// 獲取課程ID
$course_id = $_GET['id'];

// 刪除課程
$sql = "DELETE FROM courses WHERE id = ?";

if ($stmt = mysqli_prepare($link, $sql)) {
    mysqli_stmt_bind_param($stmt, "i", $course_id);
    if (mysqli_stmt_execute($stmt)) {
        mysqli_stmt_close($stmt);
        mysqli_close($link);
        header("Location: manager.php");  // 重定向到管理員專區頁面
        exit;
    } else {
        echo "刪除失敗: " . mysqli_stmt_error($stmt);
        mysqli_stmt_close($stmt);
        mysqli_close($link);
        exit;
    }
} else {
    echo "資料庫查詢失敗：" . mysqli_error($link);
    mysqli_close($link);
    exit;
}
?>
