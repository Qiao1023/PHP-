<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <title>登入 - Fitness Hub健身房選課系統</title>
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
        input[type=text], input[type=password] {
            width: calc(100% - 22px);
            padding: 10px;
            margin-top: 8px;
            margin-bottom: 8px;
            border: 1px solid #ddd;
            box-sizing: border-box;
        }
        input[type=submit], input[type=reset], .link-button {
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
            width: calc(50% - 8px);
        }
        input[type=reset] {
            background-color: #f44336;
        }
        .links {
            text-align: center;
            margin-top: 20px;
        }
        .link-button {
            display: block;
            width: calc(100% - 22px);
            margin: 8px auto;
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
        <div style="font-size: 24px;">Fitness Hub健身房選課系統</div>
    </header>

    <form action="check.php" method="post">
        <input type="hidden" name="action" value="login">
        <h2 style="text-align: center;">會員登入</h2>
        帳號：<input type="text" name="user_login" value="" placeholder="請輸入你的帳號！" required><br/>
        密碼：<input type="password" name="user_pass" value="" placeholder="請輸入你的密碼!" required><br/>
        <input type="submit" value="登入">
        <input type="reset" value="清除">
    </form>
    <div class="links">
        <a href="register.php" class="link-button">還不是會員？註冊新帳號</a>
        <a href="index.php" class="link-button">返回首頁</a>
    </div>
    <?php
    session_start();
    if (isset($_SESSION['error'])) {
        echo '<div class="error">' . $_SESSION['error'] . '</div>';
        unset($_SESSION['error']);
    }
    ?>
</body>
</html>
