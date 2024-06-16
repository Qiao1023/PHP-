<?php
session_start();

// 檢查用戶是否為管理員
if (!isset($_SESSION['manager_loggedin']) || $_SESSION['manager_loggedin'] !== true) {
    header("Location: manager_login.php");
    exit;
}

// 檢查是否提供了ID
if (!isset($_GET['id'])) {
    die("未提供ID");
}

// 連接數據庫
$link = mysqli_connect('localhost', 'root', '', 'fitness_hub');

// 檢查數據庫連接
if (!$link) {
    die("數據庫連接失敗: " . mysqli_connect_error());
}

$id = $_GET['id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $price = $_POST['price'];
    $features = $_POST['features'];
    $duration = $_POST['duration'];  // 獲取期間值

    // 更新指定ID的會員方案
    $sql = "UPDATE plans SET title = ?, price = ?, features = ?, duration = ? WHERE id = ?";

    if ($stmt = mysqli_prepare($link, $sql)) {
        mysqli_stmt_bind_param($stmt, "ssssi", $title, $price, $features, $duration, $id);
        if (mysqli_stmt_execute($stmt)) {
            echo "更新成功";
            header("Location: manager.php");  // 重定向到管理員專區頁面
        } else {
            echo "更新失敗: " . mysqli_stmt_error($stmt);
        }
        mysqli_stmt_close($stmt);
    } else {
        echo "資料庫查詢失敗：" . mysqli_error($link);
    }
} else {
    // 獲取指定ID的會員方案數據
    $sql = "SELECT * FROM plans WHERE id = ?";

    if ($stmt = mysqli_prepare($link, $sql)) {
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        if ($row = mysqli_fetch_assoc($result)) {
            $title = $row['title'];
            $price = $row['price'];
            $features = $row['features'];
            $duration = $row['duration'];  // 獲取期間值
        } else {
            die("找不到指定的會員方案");
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
    <title>修改會員方案 - Fitness Hub健身房選課系統</title>
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
        input[type="text"], textarea {
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
        <div class="logo">Fitness Hub健身房選課系統 - 修改會員方案</div>
    </header>

    <section class="content">
        <h1>修改會員方案</h1>
        <form action="changePlan.php?id=<?php echo $id; ?>" method="post">
            <div class="form-group">
                <label for="title">標題：</label>
                <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($title); ?>" required>
            </div>
            <div class="form-group">
                <label for="price">價格：</label>
                <input type="text" id="price" name="price" value="<?php echo htmlspecialchars($price); ?>" required>
            </div>
            <div class="form-group">
                <label for="features">特點：</label>
                <textarea id="features" name="features" required><?php echo htmlspecialchars($features); ?></textarea>
            </div>
            <div class="form-group">
                <label for="duration">期間：</label>
                <input type="text" id="duration" name="duration" value="<?php echo htmlspecialchars($duration); ?>" required>
            </div>
            <input type="submit" value="保存修改">
        </form>
    </section>

    <footer>
        <a href="manager.php" class="home-button">返回管理員專區</a>
        <p>Contact us at email@example.com</p>
    </footer>
</body>
</html>
