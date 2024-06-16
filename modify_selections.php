<?php
session_start();  // 啟動 session

// 檢查用戶是否已登入
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php");
    exit;
}

// 假設用戶名存儲在 session 中
$username = $_SESSION['username'];
$member_id = $_SESSION['id']; // 獲取會員ID

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

// 獲取會員選擇
$sql = "SELECT ms.plan_id, ms.course_id, p.title AS plan_title, c.title AS course_title, t.name AS trainer_name, f.name AS facility_name
        FROM member_selections ms
        LEFT JOIN plans p ON ms.plan_id = p.id
        LEFT JOIN courses c ON ms.course_id = c.id
        LEFT JOIN trainers t ON c.trainer_id = t.id
        LEFT JOIN facilities f ON c.facility_id = f.id
        WHERE ms.member_id = ?";
$memberSelection = [];
if ($stmt = mysqli_prepare($link, $sql)) {
    mysqli_stmt_bind_param($stmt, "i", $member_id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $plan_id, $course_id, $plan_title, $course_title, $trainer_name, $facility_name);
    if (mysqli_stmt_fetch($stmt)) {
        $memberSelection = [
            'plan_id' => $plan_id,
            'course_id' => $course_id,
            'plan_title' => $plan_title,
            'course_title' => $course_title,
            'trainer_name' => $trainer_name,
            'facility_name' => $facility_name
        ];
    }
    mysqli_stmt_close($stmt);
}

// 獲取所有會員方案
$plans = mysqli_query($link, "SELECT * FROM plans");

// 獲取所有課程
$courses = mysqli_query($link, "SELECT c.id, c.title, t.name AS trainer_name, f.name AS facility_name
                                FROM courses c
                                LEFT JOIN trainers t ON c.trainer_id = t.id
                                LEFT JOIN facilities f ON c.facility_id = f.id");

mysqli_close($link);
?>

<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <title>修改會員方案和課程</title>
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
            padding: 10px 0;
            background: #333;
            color: white;
            margin-top: auto;
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
        h1, h2 {
            text-align: center;
        }
        .content {
            width: 100%;
            max-width: 800px;
            margin: 0 auto;
        }
        label {
            display: block;
            margin-bottom: 5px;
        }
        select {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <header>
        <div class="logo">Fitness Hub健身房選課系統 - 修改會員方案和課程</div>
    </header>

    <section class="content">
        <h1>修改會員方案和課程</h1>
        <form action="update_selections.php" method="post">
            <div class="form-group">
                <label for="plan">選擇會員方案：</label>
                <select id="plan" name="plan_id" required>
                    <?php while ($plan = mysqli_fetch_assoc($plans)): ?>
                        <option value="<?php echo $plan['id']; ?>" <?php if ($plan['id'] == $memberSelection['plan_id']) echo 'selected'; ?>>
                            <?php echo $plan['title']; ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="course">選擇課程：</label>
                <select id="course" name="course_id" required>
                    <?php while ($course = mysqli_fetch_assoc($courses)): ?>
                        <option value="<?php echo $course['id']; ?>" <?php if ($course['id'] == $memberSelection['course_id']) echo 'selected'; ?>>
                            <?php echo $course['title']; ?> - <?php echo $course['trainer_name']; ?> - <?php echo $course['facility_name']; ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>

            <input type="submit" value="保存修改" class="button">
        </form>
    </section>

    <footer>
        <p>Contact us at Fitness_Hub@gmail.com</p>
    </footer>
</body>
</html>
