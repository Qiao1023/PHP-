<?php
session_start();

// 檢查用戶是否已登入
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php");
    exit;
}

$username = $_SESSION['username'];
$member_id = $_SESSION['id'];

$link = mysqli_connect('localhost', 'root', '', 'fitness_hub');

if (!$link) {
    die("資料庫連接失敗: " . mysqli_connect_error());
}

// 獲取可用的會員方案
$available_plans = [];
$sql_plans = "SELECT id, title, price, features, duration FROM plans";
$result = mysqli_query($link, $sql_plans);
while ($row = mysqli_fetch_assoc($result)) {
    $available_plans[] = $row;
}

// 獲取會員已選擇的方案
$sql_member_plan = "SELECT plan_id FROM member_selections WHERE member_id = ?";
$selected_plan_id = null;
if ($stmt_member_plan = mysqli_prepare($link, $sql_member_plan)) {
    mysqli_stmt_bind_param($stmt_member_plan, "i", $member_id);
    mysqli_stmt_execute($stmt_member_plan);
    mysqli_stmt_bind_result($stmt_member_plan, $selected_plan_id);
    mysqli_stmt_fetch($stmt_member_plan);
    mysqli_stmt_close($stmt_member_plan);
}

mysqli_close($link);

// 顯示成功或錯誤訊息
$success = isset($_SESSION['success']) ? $_SESSION['success'] : '';
$error = isset($_SESSION['error']) ? $_SESSION['error'] : '';
unset($_SESSION['success']);
unset($_SESSION['error']);
?>

<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <title>選擇會員方案 - Fitness Hub健身房</title>
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
        nav {
            position: absolute;
            top: 10px;
            right: 20px;
        }
        nav a {
            color: white;
            text-decoration: none;
            margin-left: 15px;
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
        .select-btn, .update-btn {
            background: #5cb85c;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .message {
            text-align: center;
            margin: 10px;
        }
        .message.success {
            color: green;
        }
        .message.error {
            color: red;
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
        <div class="logo">Fitness Hub健身房 - 選擇會員方案</div>
        <nav>
            <a href="member.php">會員專區</a>
            <a href="view_selected_plan.php">查看會員方案</a>
        </nav>
    </header>

    <section>
        <h1>選擇會員方案</h1>
        <?php if ($success): ?>
            <div class="message success"><?php echo $success; ?></div>
            <script>
                alert('<?php echo $success; ?>');
            </script>
        <?php endif; ?>
        <?php if ($error): ?>
            <div class="message error"><?php echo $error; ?></div>
        <?php endif; ?>
        <?php foreach ($available_plans as $plan): ?>
            <div class="plan">
                <div class="plan-title"><?php echo htmlspecialchars($plan['title']); ?></div>
                <div class="plan-price"><?php echo htmlspecialchars($plan['price']); ?></div>
                <div class="plan-features"><?php echo nl2br(htmlspecialchars($plan['features'])); ?></div>
                <div class="plan-duration"><?php echo htmlspecialchars($plan['duration']); ?></div>
                <?php if ($selected_plan_id == $plan['id']): ?>
                    <div class="message success">已選擇此方案</div>
                    <form method="post" action="update_plan.php">
                        <input type="hidden" name="plan_id" value="<?php echo $plan['id']; ?>">
                        <input type="submit" class="update-btn" value="更新此方案">
                    </form>
                <?php else: ?>
                    <form method="post" action="update_plan.php">
                        <input type="hidden" name="plan_id" value="<?php echo $plan['id']; ?>">
                        <input type="submit" class="select-btn" value="選擇此方案">
                    </form>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </section>

    <footer>
        <p>Contact us at Fitness_Hub@gmail.com</p>
    </footer>
</body>
</html>
