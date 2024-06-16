<?php
session_start();

// 檢查用戶是否已登入
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php");
    exit;
}

$member_id = $_SESSION['id'];
$username = $_SESSION['username'];

$link = mysqli_connect('localhost', 'root', '', 'fitness_hub');

if (!$link) {
    die("資料庫連接失敗: " . mysqli_connect_error());
}

// 獲取會員已選擇的方案
$sql_member_plan = "SELECT plans.title, plans.price, plans.features, plans.duration 
                    FROM member_selections 
                    JOIN plans ON member_selections.plan_id = plans.id 
                    WHERE member_selections.member_id = ?";
$selected_plan = null;
if ($stmt_member_plan = mysqli_prepare($link, $sql_member_plan)) {
    mysqli_stmt_bind_param($stmt_member_plan, "i", $member_id);
    mysqli_stmt_execute($stmt_member_plan);
    mysqli_stmt_bind_result($stmt_member_plan, $title, $price, $features, $duration);
    mysqli_stmt_fetch($stmt_member_plan);
    $selected_plan = [
        'title' => $title,
        'price' => $price,
        'features' => $features,
        'duration' => $duration
    ];
    mysqli_stmt_close($stmt_member_plan);
}

mysqli_close($link);
?>

<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <title>目前選擇的會員方案 - Fitness Hub健身房</title>
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
            display: flex;
            justify-content: center;
            align-items: center;
            position: relative;
        }
        header .logo {
            flex: 1;
            text-align: center;
        }
        nav {
            position: absolute;
            right: 20px;
            display: flex;
            justify-content: flex-end;
            background: #333;
        }
        nav a {
            color: white;
            text-decoration: none;
            padding: 0 10px;
        }
        section {
            margin: 20px;
            padding: 20px;
            background: #fff;
            border: 1px solid #ddd;
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .plan {
            border: 1px solid #ddd;
            padding: 20px;
            margin: 10px;
            width: 80%;
            text-align: left;
        }
        .plan-title {
            font-size: 1.5em;
            margin-bottom: 10px;
        }
        .plan-price {
            color: #f44336;
            font-weight: bold;
            margin-bottom: 10px;
        }
        .plan-features {
            margin-bottom: 10px;
        }
        .plan-duration {
            margin-bottom: 10px;
        }
        .message {
            text-align: center;
            margin: 10px;
        }
        footer {
            text-align: center;
            padding: 10px 0;
            background: #333;
            color: white;
            margin-top: auto;
        }
    </style>
</head>
<body>
    <header>
        <div class="logo">Fitness Hub健身房 - 目前選擇的會員方案</div>
        <nav>
            <a href="member.php">會員專區</a>
            <a href="select_plan.php">挑選會員方案</a>
            <a href="index.php">回首頁</a>
        </nav>
    </header>
    <section>
        <h1><?php echo htmlspecialchars($username); ?>您好，以下是您目前選擇的會員方案</h1>
        <?php if ($selected_plan): ?>
            <div class="plan">
                <div class="plan-title"><?php echo htmlspecialchars($selected_plan['title']); ?></div>
                <div class="plan-price"><?php echo htmlspecialchars($selected_plan['price']); ?></div>
                <div class="plan-features"><?php echo nl2br(htmlspecialchars($selected_plan['features'])); ?></div>
                <div class="plan-duration"><?php echo htmlspecialchars($selected_plan['duration']); ?></div>
            </div>
        <?php else: ?>
            <div class="message error">您尚未選擇任何會員方案。</div>
        <?php endif; ?>
    </section>
    <footer>
        <p>Contact us at Fitness_Hub@gmail.com</p>
    </footer>
</body>
</html>
