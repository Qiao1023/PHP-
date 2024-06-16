<?php
session_start();

// 檢查用戶是否已登入
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php");
    exit;
}

$member_id = $_SESSION['id'];

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['plan_id'])) {
    $plan_id = $_POST['plan_id'];

    $link = mysqli_connect('localhost', 'root', '', 'fitness_hub');

    if (!$link) {
        die("資料庫連接失敗: " . mysqli_connect_error());
    }

    // 更新會員方案
    $sql = "UPDATE member_selections SET plan_id = ? WHERE member_id = ?";
    if ($stmt = mysqli_prepare($link, $sql)) {
        mysqli_stmt_bind_param($stmt, "ii", $plan_id, $member_id);
        if (mysqli_stmt_execute($stmt)) {
            $_SESSION['success'] = "會員方案更新成功";
        } else {
            $_SESSION['error'] = "會員方案更新失敗：" . mysqli_stmt_error($stmt);
        }
        mysqli_stmt_close($stmt);
    } else {
        $_SESSION['error'] = "SQL準備失敗：" . mysqli_error($link);
    }

    mysqli_close($link);

    header("Location: select_plan.php");
    exit;
} else {
    $_SESSION['error'] = "無效的請求";
    header("Location: select_plan.php");
    exit;
}
?>
