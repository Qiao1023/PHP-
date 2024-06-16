<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

$link = mysqli_connect('localhost', 'root', '', 'fitness_hub');

if (!$link) {
    die("無法打開數據庫!<br/>");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $verification_code = mysqli_real_escape_string($link, $_POST['verification_code']);
    $user_id = $_SESSION['id'];

    $sql = "SELECT verification_code FROM member WHERE id = ? AND verification_code = ?";
    if ($stmt = mysqli_prepare($link, $sql)) {
        mysqli_stmt_bind_param($stmt, "ii", $user_id, $verification_code);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);

        if (mysqli_stmt_num_rows($stmt) == 1) {
            $sql_update = "UPDATE member SET verified = 1 WHERE id = ?";
            if ($stmt_update = mysqli_prepare($link, $sql_update)) {
                mysqli_stmt_bind_param($stmt_update, "i", $user_id);
                mysqli_stmt_execute($stmt_update);
                mysqli_stmt_close($stmt_update);
            }

            $_SESSION['success'] = "驗證成功，請登入。";
            header("Location: login.php");
            exit;
        } else {
            $_SESSION['error'] = "驗證碼錯誤，請重新輸入。";
            header("Location: verify.php");
            exit;
        }
        mysqli_stmt_close($stmt);
    } else {
        $_SESSION['error'] = "資料庫查詢失敗：" . mysqli_error($link);
        header("Location: verify.php");
        exit;
    }
}

mysqli_close($link);
?>

<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <title>驗證 - Fitness Hub健身房選課系統</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        header {
            background: #333;
            color: white;
            padding: 10px 20px;
            text-align: center;
        }
        form {
            margin: 50px auto;
            padding: 20px;
            width: 300px;
            background: #fff;
            border: 1px solid #ddd;
            box-shadow: 0px 0px 10px #aaa;
        }
        input[type=text] {
            width: calc(100% - 22px);
            padding: 10px;
            margin-top: 8px;
            margin-bottom: 8px;
            border: 1px solid #ddd;
            box-sizing: border-box;
        }
        input[type=submit] {
            background-color: #5cb85c;
            color: white;
            border: none;
            padding: 10px 20px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            margin: 4px 2px;
            cursor: pointer;
            border-radius: 5px;
            width: calc(100% - 22px);
            box-sizing: border-box;
        }
        .error {
            color: red;
            text-align: center;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <header>
        <div style="font-size: 24px;">Fitness Hub健身房選課系統 - 驗證</div>
    </header>

    <form action="verify.php" method="post">
        <h2 style="text-align: center;">輸入驗證碼</h2>
        驗證碼：<input type="text" name="verification_code" value="" placeholder="請輸入您收到的驗證碼！" required><br/>
        <input type="submit" value="驗證">
    </form>
    <?php
    if (isset($_SESSION['error'])) {
        echo '<div class="error">' . $_SESSION['error'] . '</div>';
        unset($_SESSION['error']);
    }
    ?>
</body>
</html>
