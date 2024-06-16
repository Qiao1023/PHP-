<?php
session_start();  // 啟動 session

// 檢查用戶是否已登入
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php");
    exit;
}

// 假設用戶名和會員ID存儲在 session 中
$username = $_SESSION['username'];
$member_id = $_SESSION['id']; 

// 連接數據庫
$link = mysqli_connect('localhost', 'root', '', 'fitness_hub');

// 檢查數據庫連接
if (!$link) {
    die("數據庫連接失敗: " . mysqli_connect_error());
}

// 獲取會員選擇的方案、課程、教練、場館
$sql = "SELECT ms.plan_id, ms.course_id, ms.trainer_id, ms.facility_id, 
               p.title AS plan_title, p.price AS plan_price, p.features AS plan_features, 
               c.title AS course_title, 
               t.name AS trainer_name, 
               f.name AS facility_name
        FROM member_selections ms
        LEFT JOIN plans p ON ms.plan_id = p.id
        LEFT JOIN courses c ON ms.course_id = c.id
        LEFT JOIN trainers t ON ms.trainer_id = t.id
        LEFT JOIN facilities f ON ms.facility_id = f.id
        WHERE ms.member_id = ?";

if ($stmt = mysqli_prepare($link, $sql)) {
    mysqli_stmt_bind_param($stmt, "i", $member_id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $plan_id, $course_id, $trainer_id, $facility_id, 
                                      $plan_title, $plan_price, $plan_features, 
                                      $course_title, $trainer_name, $facility_name);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);
}

mysqli_close($link);
?>

<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <title>我的會員方案 - Fitness Hub健身房選課系統</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        header {
            background: #333;
            color: white;
            padding: 10px 20px;
            text-align: center;
            position: relative;
        }
        .cart-button {
            position: absolute;
            right: 20px;
            top: 10px;
            background: #5cb85c;
            padding: 10px 20px;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }
        section {
            margin: 20px;
            padding: 20px;
            background: #fff;
            border: 1px solid #ddd;
            flex: 1;
        }
        .section-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
        }
        .section-half {
            width: calc(50% - 20px);
            margin-bottom: 20px;
            background: #fff;
            border: 1px solid #ddd;
            box-shadow: 0px 0px 10px #aaa;
            padding: 20px;
            box-sizing: border-box;
            text-align: center;
        }
        footer {
            text-align: center;
            padding: 10px 0;
            background: #333;
            color: white;
            margin-top: auto;
        }
        .home-button {
            background: #5cb85c;
            padding: 10px 20px;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            display: inline-block;
            margin-top: 10px;
        }
        h1, h2, h3 {
            text-align: center;
        }
    </style>
</head>
<body>
    <header>
        <div class="logo">Fitness Hub健身房選課系統 - 我的會員方案</div>
        <a href="cart.php" class="cart-button">購物車</a>
    </header>

    <section>
        <h1> <?php echo htmlspecialchars($username); ?>會員您好！</h1>
        <h2>以下是您的會員方案</h2>
        <div class="section-container">
            <div class="section-half">
                <h3>方案</h3>
                <p>標題：<?php echo htmlspecialchars($plan_title); ?></p>
                <p>價格：<?php echo htmlspecialchars($plan_price); ?></p>
                <p>特點：<?php echo htmlspecialchars($plan_features); ?></p>
            </div>
            <div class="section-half">
                <h3>課程</h3>
                <p>標題：<?php echo htmlspecialchars($course_title); ?></p>
            </div>
            <div class="section-half">
                <h3>教練</h3>
                <p>名字：<?php echo htmlspecialchars($trainer_name); ?></p>
            </div>
            <div class="section-half">
                <h3>場館</h3>
                <p>名字：<?php echo htmlspecialchars($facility_name); ?></p>
            </div>
        </div>
    </section>

    <footer>
        <a href="member.php" class="home-button">返回會員專區</a>
        <p>Contact us at email@example.com</p>
    </footer>
</body>
</html>
