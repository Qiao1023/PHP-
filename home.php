<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <title>Fitness Hub健身房選課系統</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            background: url('../final project/image/background.png') no-repeat center center fixed; 
            background-size: cover;
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
            display: flex;
        }
        nav ul li {
            margin-right: 10px;
        }
        nav ul li a {
            color: white;
            text-decoration: none;
            padding: 5px 10px;
        }
        nav ul li a:hover {
            background: #575757;
            border-radius: 5px;
        }
        .main-visual {
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: center;
            text-align: center;
            color: white;
            padding: 20px;
        }
        .main-visual h1 {
            font-size: 48px;
            margin: 0;
        }
        footer {
            text-align: center;
            padding: 10px 20px;
            background: #333;
            color: white;
            position: relative;
        }
        .start-button {
            background: #5cb85c;
            color: white;
            text-decoration: none;
            padding: 10px 20px;
            border-radius: 5px;
            font-size: 20px;
            margin: 20px auto; /* Center the button horizontally */
            display: block;
            width: fit-content; /* Make the button width fit the content */
        }
        .start-button:hover {
            background: #4cae4c;
        }
        .logo-container {
            height: 80px; 
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

    <div class="main-visual">
        <div>
            <h1>Welcome to Fitness Hub</h1>
        </div>
    </div>

    <footer>
        <a href="index.php" class="start-button">Let's Start !</a>
        <p>Contact us at Fitness_Hub@gmail.com</p>
    </footer>
</body>
</html>
