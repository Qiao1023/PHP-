<?php
session_start();

// 檢查用戶是否已登入
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php");
    exit;
}

// 連接數據庫
$link = mysqli_connect('localhost', 'root', '', 'fitness_hub');

// 檢查數據庫連接
if (!$link) {
    die("數據庫連接失敗: " . mysqli_connect_error());
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $member_id = $_SESSION['id'];
    $plan_id = $_POST['plan_id'];
    $course_id = $_POST['course_id'];
    $trainer_id = $_POST['trainer_id'];
    $facility_id = $_POST['facility_id'];

    // 檢查POST數據是否為空
    if (empty($plan_id) || empty($course_id) || empty($trainer_id) || empty($facility_id)) {
        die("所有選項均為必填項。");
    }

    // 檢查member_id是否存在
    if (empty($member_id)) {
        die("會員ID不存在。");
    }

    // SQL 插入語句
    $sql = "INSERT INTO member_selections (member_id, plan_id, course_id, trainer_id, facility_id) VALUES (?, ?, ?, ?, ?)";

    if ($stmt = mysqli_prepare($link, $sql)) {
        mysqli_stmt_bind_param($stmt, "iiiii", $member_id, $plan_id, $course_id, $trainer_id, $facility_id);

        if (mysqli_stmt_execute($stmt)) {
            mysqli_stmt_close($stmt);
            mysqli_close($link);
            header("Location: cart.php");
            exit;
        } else {
            echo "保存選擇失敗: " . mysqli_stmt_error($stmt);
        }
    } else {
        echo "SQL錯誤: " . mysqli_error($link);
    }

    mysqli_close($link);
}
?>
