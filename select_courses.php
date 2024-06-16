<?php
session_start();

// 檢查用戶是否已登入
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['id'];

$link = mysqli_connect('localhost', 'root', '', 'fitness_hub');
if (!$link) {
    die("數據庫連接失敗: " . mysqli_connect_error());
}

$success = '';
$error = '';

// 處理課程選擇
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['course_id'])) {
    $course_id = $_POST['course_id'];

    // 檢查是否已選過此課程
    $sql_check = "SELECT * FROM member_selections WHERE member_id = ? AND course_id = ?";
    if ($stmt_check = mysqli_prepare($link, $sql_check)) {
        mysqli_stmt_bind_param($stmt_check, "ii", $user_id, $course_id);
        mysqli_stmt_execute($stmt_check);
        mysqli_stmt_store_result($stmt_check);
        if (mysqli_stmt_num_rows($stmt_check) > 0) {
            $error = "您已經選過此課程";
        } else {
            // 插入選擇的課程
            $sql_insert = "INSERT INTO member_selections (member_id, course_id) VALUES (?, ?)";
            if ($stmt_insert = mysqli_prepare($link, $sql_insert)) {
                mysqli_stmt_bind_param($stmt_insert, "ii", $user_id, $course_id);
                if (mysqli_stmt_execute($stmt_insert)) {
                    $success = "選課成功";
                } else {
                    $error = "選課失敗：" . mysqli_stmt_error($stmt_insert);
                }
                mysqli_stmt_close($stmt_insert);
            }
        }
        mysqli_stmt_close($stmt_check);
    }
}

// 處理課程刪除
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_course_id'])) {
    $delete_course_id = $_POST['delete_course_id'];
    $sql_delete = "DELETE FROM member_selections WHERE member_id = ? AND course_id = ?";
    if ($stmt_delete = mysqli_prepare($link, $sql_delete)) {
        mysqli_stmt_bind_param($stmt_delete, "ii", $user_id, $delete_course_id);
        if (mysqli_stmt_execute($stmt_delete)) {
            $success = "課程已刪除";
        } else {
            $error = "刪除課程失敗：" . mysqli_stmt_error($stmt_delete);
        }
        mysqli_stmt_close($stmt_delete);
    }
}

// 獲取所有課程
$sql = "SELECT id, title, day, time, duration FROM courses";
$result = mysqli_query($link, $sql);
$courses = mysqli_fetch_all($result, MYSQLI_ASSOC);

// 獲取用戶選擇的課程
$sql_selected = "SELECT c.id, c.title, c.day, c.time, c.duration FROM member_selections ms
        JOIN courses c ON ms.course_id = c.id
        WHERE ms.member_id = ?";
if ($stmt_selected = mysqli_prepare($link, $sql_selected)) {
    mysqli_stmt_bind_param($stmt_selected, "i", $user_id);
    mysqli_stmt_execute($stmt_selected);
    $result_selected = mysqli_stmt_get_result($stmt_selected);
    $selected_courses = mysqli_fetch_all($result_selected, MYSQLI_ASSOC);
    mysqli_stmt_close($stmt_selected);
}

mysqli_close($link);
?>

<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <title>選課 - Fitness Hub健身房選課系統</title>
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
        .course-button, .delete-button {
            background: #5cb85c;
            padding: 5px 10px;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .delete-button {
            background: #f44336;
        }
        .message {
            text-align: center;
            margin: 10px;
            color: red;
        }
        .success {
            color: green;
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
    </style>
    <script>
        function confirmDelete() {
            return confirm('確定要刪除此課程嗎？');
        }
        function alertSuccess(message) {
            return confirm(message);
        }
    </script>
</head>
<body>
    <header>
        <div class="logo">Fitness Hub健身房選課系統 - 選課</div>
    </header>

    <section>
        <h1>選擇課程</h1>
        <?php if ($error): ?>
            <div class="message"><?php echo $error; ?></div>
        <?php endif; ?>
        <?php if ($success): ?>
            <script>alertSuccess("<?php echo $success; ?>");</script>
        <?php endif; ?>
        <table>
            <tr>
                <th>課程名稱</th>
                <th>星期</th>
                <th>時間</th>
                <th>操作</th>
            </tr>
            <?php foreach ($courses as $course): ?>
                <tr>
                    <td><?php echo htmlspecialchars($course['title']); ?></td>
                    <td><?php echo htmlspecialchars($course['day']); ?></td>
                    <td><?php echo htmlspecialchars($course['time']); ?></td>
                    <td>
                        <form method="post" action="select_courses.php">
                            <input type="hidden" name="course_id" value="<?php echo $course['id']; ?>">
                            <input type="submit" class="course-button" value="選課">
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    </section>

    <section>
        <h1>已選擇的課程</h1>
        <table>
            <tr>
                <th>課程名稱</th>
                <th>星期</th>
                <th>時間</th>
                <th>操作</th>
            </tr>
            <?php foreach ($selected_courses as $course): ?>
                <tr>
                    <td><?php echo htmlspecialchars($course['title']); ?></td>
                    <td><?php echo htmlspecialchars($course['day']); ?></td>
                    <td><?php echo htmlspecialchars($course['time']); ?></td>
                    <td>
                        <form method="post" action="select_courses.php" onsubmit="return confirmDelete();">
                            <input type="hidden" name="delete_course_id" value="<?php echo $course['id']; ?>">
                            <input type="submit" class="delete-button" value="刪除">
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    </section>

    <footer>
        <a href="member.php" class="course-button">回到我的課表</a>
        <p>Contact us at Fitness_Hub@gmail.com</p>
    </footer>
</body>
</html>
