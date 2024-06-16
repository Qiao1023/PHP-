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

// 獲取課程ID
$course_id = $_GET['id'];

// 獲取該課程的詳細信息
$sql = "SELECT * FROM courses WHERE id = ?";
if ($stmt = mysqli_prepare($link, $sql)) {
    mysqli_stmt_bind_param($stmt, "i", $course_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $course = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);
} else {
    echo "資料庫查詢失敗：" . mysqli_error($link);
    exit;
}

// 獲取所有教練和場館
$trainers = mysqli_query($link, "SELECT id, name FROM trainers");
$facilities = mysqli_query($link, "SELECT id, name FROM facilities");

// 處理表單提交
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $trainer_id = $_POST['trainer_id'];
    $facility_id = $_POST['facility_id'];

    // 更新課程信息到資料庫
    $sql = "UPDATE courses SET title = ?, trainer_id = ?, facility_id = ? WHERE id = ?";

    if ($stmt = mysqli_prepare($link, $sql)) {
        mysqli_stmt_bind_param($stmt, "siii", $title, $trainer_id, $facility_id, $course_id);
        if (mysqli_stmt_execute($stmt)) {
            echo "更新成功";
            mysqli_stmt_close($stmt);
            mysqli_close($link);
            header("Location: manager.php");  // 重定向到管理員專區頁面
            exit;
        } else {
            echo "更新失敗: " . mysqli_stmt_error($stmt);
        }
        mysqli_stmt_close($stmt);
    } else {
        echo "資料庫查詢失敗：" . mysqli_error($link);
    }
}

mysqli_close($link);
?>

<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <title>修改課程 - Fitness Hub健身房選課系統</title>
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
        }
        .content {
            margin: 20px auto;
            padding: 20px;
            background: #fff;
            border: 1px solid #ddd;
            width: 90%;
            max-width: 800px;
            box-shadow: 0px 0px 10px #aaa;
        }
        footer {
            text-align: center;
            padding: 10px 0;
            background: #333;
            color: white;
        }
        .home-button {
            background: #5cb85c;
            padding: 10px 20px;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 10px;
            display: inline-block;
        }
        .logo {
            float: left;
        }
        h1 {
            text-align: center;
        }
        .form-group {
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-bottom: 5px;
        }
        input[type="text"], select {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        input[type="submit"] {
            background: #5cb85c;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <header>
        <div class="logo">Fitness Hub健身房選課系統 - 修改課程</div>
    </header>

    <section class="content">
        <h1>修改課程</h1>
        <form action="changeCourse.php?id=<?php echo $course_id; ?>" method="post">
            <div class="form-group">
                <label for="title">課程名稱：</label>
                <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($course['title']); ?>" required>
            </div>
            <div class="form-group">
                <label for="trainer_id">教練：</label>
                <select id="trainer_id" name="trainer_id" required>
                    <option value="">請選擇</option>
                    <?php while ($trainer = mysqli_fetch_assoc($trainers)): ?>
                        <option value="<?php echo $trainer['id']; ?>" <?php if ($trainer['id'] == $course['trainer_id']) echo 'selected'; ?>>
                            <?php echo $trainer['name']; ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="facility_id">場館：</label>
                <select id="facility_id" name="facility_id" required>
                    <option value="">請選擇</option>
                    <?php while ($facility = mysqli_fetch_assoc($facilities)): ?>
                        <option value="<?php echo $facility['id']; ?>" <?php if ($facility['id'] == $course['facility_id']) echo 'selected'; ?>>
                            <?php echo $facility['name']; ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>
            <input type="submit" value="保存更改">
        </form>
    </section>

    <footer>
        <a href="manager.php" class="home-button">返回管理員專區</a>
        <p>Contact us at email@example.com</p>
    </footer>
</body>
</html>
