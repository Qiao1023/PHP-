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
    die("數據庫連接失敗: " . mysqli_connect_error());
}

// 處理課程選擇
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['course_id'])) {
    $course_id = $_POST['course_id'];
    
    // 獲取選擇課程的 day 和 time
    $sql_get_course = "SELECT title FROM courses WHERE id = ?";
    if ($stmt_get_course = mysqli_prepare($link, $sql_get_course)) {
        mysqli_stmt_bind_param($stmt_get_course, "i", $course_id);
        mysqli_stmt_execute($stmt_get_course);
        mysqli_stmt_bind_result($stmt_get_course, $title);
        mysqli_stmt_fetch($stmt_get_course);
        mysqli_stmt_close($stmt_get_course);

        // 設定每個課程對應的時間和星期
        $schedule = [
            "瑜珈課程" => ["day" => "星期一", "time" => "18:00-19:00"],
            "健身課程" => ["day" => "星期二", "time" => "17:00-18:00"],
            "重量訓練" => ["day" => "星期三", "time" => "19:00-20:00"],
            "有氧舞蹈" => ["day" => "星期四", "time" => "20:00-21:00"],
            "拳擊訓練" => ["day" => "星期五", "time" => "16:00-17:00"],
            "普拉提" => ["day" => "星期一", "time" => "17:00-18:00"],
        ];

        $day = $schedule[$title]['day'];
        $time = $schedule[$title]['time'];
        
        // 檢查是否已選過此課程
        $sql_check = "SELECT * FROM member_selections WHERE member_id = ? AND course_id = ?";
        if ($stmt_check = mysqli_prepare($link, $sql_check)) {
            mysqli_stmt_bind_param($stmt_check, "ii", $member_id, $course_id);
            mysqli_stmt_execute($stmt_check);
            mysqli_stmt_store_result($stmt_check);
            if (mysqli_stmt_num_rows($stmt_check) > 0) {
                $error = "您已經選過此課程";
            } else {
                // 插入選擇的課程
                $sql_insert = "INSERT INTO member_selections (member_id, course_id, day, time) VALUES (?, ?, ?, ?)";
                if ($stmt_insert = mysqli_prepare($link, $sql_insert)) {
                    mysqli_stmt_bind_param($stmt_insert, "iiss", $member_id, $course_id, $day, $time);
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
}

// 處理課程刪除
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_course_id'])) {
    $delete_course_id = $_POST['delete_course_id'];
    $sql_delete = "DELETE FROM member_selections WHERE member_id = ? AND course_id = ?";
    if ($stmt_delete = mysqli_prepare($link, $sql_delete)) {
        mysqli_stmt_bind_param($stmt_delete, "ii", $member_id, $delete_course_id);
        if (mysqli_stmt_execute($stmt_delete)) {
            $success = "課程已刪除";
        } else {
            $error = "刪除課程失敗：" . mysqli_stmt_error($stmt_delete);
        }
        mysqli_stmt_close($stmt_delete);
    }
}

// 獲取所有課程
$sql_courses = "SELECT id, title, day, time FROM courses";
$result_courses = mysqli_query($link, $sql_courses);
$courses = mysqli_fetch_all($result_courses, MYSQLI_ASSOC);

// 獲取用戶選擇的課程
$sql_schedule = "SELECT c.id, c.title, c.day, c.time FROM member_selections ms
        JOIN courses c ON ms.course_id = c.id
        WHERE ms.member_id = ?";
if ($stmt_schedule = mysqli_prepare($link, $sql_schedule)) {
    mysqli_stmt_bind_param($stmt_schedule, "i", $member_id);
    mysqli_stmt_execute($stmt_schedule);
    $result_schedule = mysqli_stmt_get_result($stmt_schedule);
    $selected_courses = mysqli_fetch_all($result_schedule, MYSQLI_ASSOC);
    mysqli_stmt_close($stmt_schedule);
}

mysqli_close($link);

$days = ["星期一", "星期二", "星期三", "星期四", "星期五"];
$times = [];
for ($i = 10; $i <= 21; $i++) {
    $times[] = sprintf("%02d:00-%02d:59", $i, $i);
}
?>

<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <title>會員專區 - Fitness Hub健身房選課系統</title>
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
        .course-button, .delete-button, .home-button {
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
    </script>
</head>
<body>
    <header>
        <div class="logo">Fitness Hub健身房選課系統 - 會員專區</div>
        <nav>
            <a href="plan1.php">會員方案</a>
            <a href="course1.php">課程介紹</a>
            <a href="coach1.php">教練團隊</a>
            <a href="place1.php">場館介紹</a>
            <a href="select_courses.php">選課</a>
            <a href="select_plan.php">挑選會員方案</a>
        </nav>  
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
                            foreach ($selected_courses as $course) {
                                // 解析課程的開始時間和結束時間
                                list($start_time, $end_time) = explode('-', $course['time']);
                                if ($course['day'] == $day && strpos($time, $start_time) !== false) {
                                    echo htmlspecialchars($course['title']);
                                    echo '<form method="post" action="member.php" onsubmit="return confirmDelete();">
                                            <input type="hidden" name="delete_course_id" value="' . $course['id'] . '">
                                          </form>';
                                }
                            }
                            ?>
                        </td>
                    <?php endforeach; ?>
                </tr>
            <?php endforeach; ?>
        </table>
        <?php if (isset($error)): ?>
            <div class="message"><?php echo $error; ?></div>
        <?php endif; ?>
        <?php if (isset($success)): ?>
            <div class="message" style="color: green;"><?php echo $success; ?></div>
        <?php endif; ?>
    </section>

    <footer>
        <a href="index.php" class="home-button">回首頁</a>
        <p>Contact us at Fitness_Hub@gmail.com</p>
    </footer>
</body>
</html>
