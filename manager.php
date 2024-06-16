<?php
session_start();

// 檢查用戶是否為管理員
if (!isset($_SESSION['manager_loggedin']) || $_SESSION['manager_loggedin'] !== true) {
    header("Location: manager_login.php");
    exit;
}

// 連接數據庫
$link = mysqli_connect('localhost', 'root', '', 'fitness_hub');

// 檢查數據庫連接
if (!$link) {
    die("數據庫連接失敗: " . mysqli_connect_error());
}

// 獲取所有會員方案
$plans = mysqli_query($link, "SELECT * FROM plans");

// 使用 JOIN 查詢整合課程、教練和場館的資料
$courses = mysqli_query($link, "SELECT c.id, c.title, c.duration, t.name AS trainer_name, f.name AS facility_name 
                                FROM courses c
                                LEFT JOIN trainers t ON c.trainer_id = t.id
                                LEFT JOIN facilities f ON c.facility_id = f.id");

mysqli_close($link);
?>

<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <title>管理員專區 - Fitness Hub健身房選課系統</title>
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
        .nav {
            display: flex;
            justify-content: center;
            gap: 20px;
            position: absolute;
            right: 20px;
            top: 50%;
            transform: translateY(-50%);
        }
        .nav a {
            color: white;
            text-decoration: none;
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
        footer {
            text-align: center;
            padding: 20px 0;
            background: #333;
            color: white;
            margin-top: auto;
            position: relative;
        }
        .logout-button {
            background: #f44336;
            padding: 10px 20px;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            display: inline-block;
            margin-bottom: 10px;
        }
        a {
            color: black;
            text-decoration: none;
        }
        .button {
            background: #5cb85c;
            padding: 10px 20px;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            display: inline-block;
            margin-top: 10px;
        }
        .delete-button {
            background: #f44336;
            padding: 10px 20px;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            display: inline-block;
            margin-top: 10px;
        }
        h1, h2 {
            text-align: center;
        }
        .content {
            width: 100%;
            max-width: 800px;
            margin: 0 auto;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        .mt-20 {
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <header>
        <div class="logo">Fitness Hub健身房選課系統 - 管理員專區</div>
        <div class="nav">
            <a href="plan_analysis.php">會員方案分析</a>
            <a href="course_analysis.php">課程偏好分析</a>
        </div>
    </header>

    <section class="content">
        <h1>管理員專區</h1>

        <h2>會員方案</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>標題</th>
                    <th>價格</th>
                    <th>特點</th>
                    <th>期間</th>
                    <th>操作</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($plan = mysqli_fetch_assoc($plans)): ?>
                <tr>
                    <td><?php echo $plan['id']; ?></td>
                    <td><?php echo $plan['title']; ?></td>
                    <td><?php echo $plan['price']; ?></td>
                    <td><?php echo $plan['features']; ?></td>
                    <td><?php echo $plan['duration']; ?></td>
                    <td>
                        <a href="changePlan.php?id=<?php echo $plan['id']; ?>" class="button">修改</a>
                        <a href="deletePlan.php?id=<?php echo $plan['id']; ?>" class="delete-button" onclick="return confirm('確定要刪除此方案嗎？')">刪除</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        <a href="addPlan.php" class="button">新增會員方案</a>

        <h2 class="mt-20">課程</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>標題</th>
                    <th>期間</th>
                    <th>教練</th>
                    <th>場館</th>
                    <th>操作</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($course = mysqli_fetch_assoc($courses)): ?>
                <tr>
                    <td><?php echo $course['id']; ?></td>
                    <td><?php echo $course['title']; ?></td>
                    <td><?php echo $course['duration']; ?></td>
                    <td><?php echo $course['trainer_name']; ?></td>
                    <td><?php echo $course['facility_name']; ?></td>
                    <td>
                        <a href="changeCourse.php?id=<?php echo $course['id']; ?>" class="button">修改</a>
                        <a href="deleteCourse.php?id=<?php echo $course['id']; ?>" class="delete-button" onclick="return confirm('確定要刪除此課程嗎？')">刪除</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        <a href="addCourse.php" class="button">新增課程</a>
    </section>

    <footer>
        <a href="logout.php" class="logout-button">登出</a>
        <p>Contact us at Fitness_Hub@gamil.com</p>
    </footer>
</body>
</html>
