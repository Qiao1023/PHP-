<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <title>註冊 - Fitness Hub健身房選課系統</title>
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
        form {
            margin: 50px auto;
            padding: 20px;
            width: 300px;
            background: #fff;
            border: 1px solid #ddd;
            box-shadow: 0px 0px 10px #aaa;
        }
        input[type=text], input[type=password], input[type=email] {
            width: calc(100% - 22px);
            padding: 10px;
            margin-top: 8px;
            margin-bottom: 8px;
            border: 1px solid #ddd;
            box-sizing: border-box;
        }
        input[type=submit], .back-button {
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
            width: calc(50% - 10px);
            box-sizing: border-box;
        }
        .back-button {
            background-color: #f44336;
            text-align: center;
        }
        .button-group {
            display: flex;
            justify-content: space-between;
        }
        h2 {
            text-align: center;
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
        <div class="logo">Fitness Hub健身房選課系統 - 註冊</div>
    </header>

    <form action="check.php" method="post">
        <input type="hidden" name="action" value="register">
        <h2>註冊新帳戶</h2>
        用戶名稱：<input type="text" id="username" name="username" required><br/>
        帳號：<input type="text" id="user_login" name="user_login" required><br/>
        密碼：<input type="password" id="user_pass" name="user_pass" required><br/>
        確認密碼：<input type="password" id="confirm_pass" name="confirm_pass" required><br/>
        電子郵件：<input type="email" id="email" name="email" required><br/>
        <div class="button-group">
            <input type="submit" value="註冊">
            <a href="login.php" class="back-button">返回登入</a>
        </div>
    </form>

    <?php if ($error): ?>
        <div class="error"><?php echo $error; ?></div>
    <?php endif; ?>
</body>
</html>
