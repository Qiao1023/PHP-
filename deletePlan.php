<?php
session_start();

// 檢查用戶是否為管理員
if (!isset($_SESSION['manager_loggedin']) || $_SESSION['manager_loggedin'] !== true) {
    header("Location: login.php");
    exit;
}

// 檢查是否提供了ID
if (!isset($_GET['id'])) {
    die("未提供ID");
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

$id = $_GET['id'];

// 刪除指定ID的會員方案
$sql = "DELETE FROM plans WHERE id = ?";

if ($stmt = mysqli_prepare($link, $sql)) {
    mysqli_stmt_bind_param($stmt, "i", $id);
    if (mysqli_stmt_execute($stmt)) {
        echo "刪除成功";
        header("Location: manager.php");  // 重定向到管理員專區頁面
    } else {
        echo "刪除失敗: " . mysqli_stmt_error($stmt);
    }
    mysqli_stmt_close($stmt);
} else {
    echo "資料庫查詢失敗：" . mysqli_error($link);
}

mysqli_close($link);
?>
