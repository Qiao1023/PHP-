<?php
session_start();  // 啟動 session

// 檢查用戶是否已登入
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php");
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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $member_id = $_SESSION['id']; // 獲取會員ID
    $plan_id = $_POST['plan_id'];
    $course_id = $_POST['course_id'];

    // 更新會員選擇
    $sql = "UPDATE member_selections SET plan_id = ?, course_id = ? WHERE member_id = ?";
    if ($stmt = mysqli_prepare($link, $sql)) {
        mysqli_stmt_bind_param($stmt, "iii", $plan_id, $course_id, $member_id);
        if (mysqli_stmt_execute($stmt)) {
            echo "選擇已更新";
        } else {
            echo "更新失敗：" . mysqli_stmt_error($stmt);
        }
        mysqli_stmt_close($stmt);
    } else {
        echo "SQL錯誤：" . mysqli_error($link);
    }
}

mysqli_close($link);
header("Location: member.php");
exit;
?>
