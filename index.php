<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <title>Fitness Hub健身房選課系統</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
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
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        nav ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        nav ul li {
            display: inline;
            margin-right: 10px;
        }
        a {
            color: white;
            text-decoration: none;
        }
        .main-content {
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .sections-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            width: 100%;
            box-sizing: border-box;
        }
        .section, #about {
            flex: 1 1 calc(50% - 40px);
            margin: 10px;
            padding: 20px;
            background: #fff;
            border: 1px solid #ddd;
            box-shadow: 0px 0px 10px #aaa;
            box-sizing: border-box;
        }
        #about {
            width: calc(100% - 40px);
            text-align: center;
        }
        footer {
            text-align: center;
            padding: 10px 0;
            background: #333;
            color: white;
        }
        .login-button, .register-button {
            background: #5cb85c;
            padding: 10px 20px;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin: 0 5px;
        }
        .logo-container {
            height: 80px; /* 根據需要調整高度 */
            display: flex;
            align-items: center;
        }
        .logo-container img {
            height: 100%;
        }
        .header-title {
            margin-left: 20px;
            font-size: 24px;
            white-space: nowrap;
        }
        .nav-container {
            display: flex;
            align-items: center;
        }
        .more-link {
            color: black;
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <header>
        <div class="nav-container">
            <div class="logo-container">
                <img src="../final project/image/Logo.png" alt="Fitness Hub Logo">
            </div>
            <div class="header-title">Fitness Hub健身房選課系統</div>
        </div>
        <nav>
            <ul>
                <li><a href="about.php">關於我們</a></li>
                <li><a href="course.php">課程介紹</a></li>
                <li><a href="plan.php">會員方案</a></li>
                <li><a href="coach.php">教練團隊</a></li>
                <li><a href="place.php">場館介紹</a></li>
            </ul>
        </nav>
    </header>

    <div class="main-content">
        <section id="about">
            <h1><i class="fas fa-bullseye"></i> Fitness Hub企業理念</h1>
            <p>我們的使命是提供最專業的健身指導和環境，讓每位會員都能達到健康的目標</p>
        </section>

        <div class="sections-container">
            <section id="courses" class="section">
                <h2><i class="fas fa-dumbbell"></i> 課程介紹</h2>
                <p>提供瑜伽、健身操等多樣化課程，適合各種不同的健身需求</p>
                <a href="course.php" class="more-link">點擊這裡 查看所有課程！</a>
            </section>

            <section id="plans" class="section">
                <h2><i class="fas fa-clipboard-list"></i> 會員方案</h2>
                <p>多種會員方案，滿足您的健身計畫和預算</p>
                <a href="plan.php" class="more-link">點擊這裡 查看所有會員方案！</a>
            </section>

            <section id="trainers" class="section">
                <h2><i class="fas fa-user-friends"></i> 教練團隊</h2>
                <p>專業教練團隊，提供一對一指導，確保您的訓練安全有效</p>
                <a href="coach.php" class="more-link">點擊這裡 查看所有教練介紹！</a>
            </section>

            <section id="facility" class="section">
                <h2><i class="fas fa-building"></i> 場館介紹</h2>
                <p>場館配備先進的健身設備，寬敞的訓練空間，讓您舒適地運動</p>
                <a href="place.php" class="more-link">點擊這裡 查看所有場館介紹！</a>
            </section>
        </div>
    </div>

    <footer>
        <a href="login.php" class="login-button">登入</a>
        <a href="register.php" class="register-button">註冊</a>
        <p>Contact us at Fitness_Hub@gmail.com</p>
    </footer>
</body>
</html>
