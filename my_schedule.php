<?php
session_start();

// 檢查用戶是否已登入
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['id'];

// 連接數據庫
$link = mysqli_connect('localhost', 'root', '', 'fitness_hub');
if (!$link) {
    die("數據庫連接失敗: " . mysqli_connect_error());
}

// 獲取用戶選擇的課程
$sql = "SELECT c.title, c.day, c.time FROM member_selections ms
        JOIN courses c ON ms.course_id = c.id
        WHERE ms.member_id = ?";
if ($stmt = mysqli_prepare($link, $sql)) {
    mysqli_stmt_bind_param($stmt, "i", $user_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $courses = mysqli_fetch_all($result, MYSQLI_ASSOC);
    mysqli_stmt_close($stmt);
} else {
    echo "資料庫查詢失敗：" . mysqli_error($link);
}
mysqli_close($link);

$days = ["星期一", "星期二", "星期三", "星期四", "星期五"];
$times = [];
for ($i = 9; $i <= 22; $i++) {
    $times[] = sprintf("%02d:00", $i);
}
?>

<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <title>我的課表 - Fitness Hub健身房選課系統</title>
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
            padding: 10px 20
            text-align: center;
            position: relative;
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
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: center;
        }
        th {
            background: #f4f4f4;
        }
        footer {
            text-align: center;
            padding: 10px 0;
            background: #333;
            color: white;
            margin-top: auto;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .nav-button {
            background: #5cb85c;
            padding: 10px 20px;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin: 10px;
        }
    </style>
</head>
<body>
    <header>
        <div class="logo">Fitness Hub健身房選課系統 - 我的課表</div>
    </header>

    <section>
        <h1>我的課表</h1>
        <table>
            <tr>
                <th></th>
                <?php foreach ($days as $day): ?>
                    <th><?php echo $day; ?></th>
                <?php endforeach; ?>
            </tr>
            <?php foreach ($times as $time): ?>
                <tr>
                    <td><?php echo $time; ?></td>
                    <?php foreach ($days as $day): ?>
                        <td>
                            <?php
                            foreach ($courses as $course) {
                                if ($course['day'] == $day && $course['time'] == $time) {
                                    echo htmlspecialchars($course['title']);
                                }
                            }
                            ?>
                        </td>
                    <?php endforeach; ?>
                </tr>
            <?php endforeach; ?>
        </table>
        <a href="member.php" class="nav-button">返回會員專區</a>
    </section>

    <footer>
        <p>Contact us at Fitness_Hub@gmail.com</p>
    </footer>
</body>
</html>
