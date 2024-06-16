<?php
$membershipPlans = [
    1 => [
        'title' => '基礎方案',
        'price' => 'NT$1,200 / 月',
        'features' => '無限次數瑜伽和有氧課程參加權限',
        'duration' => '每年續約',
        'image' => '../final project/image/agan.jpg'
    ],
    2 => [
        'title' => '進階方案',
        'price' => 'NT$2,200 / 月',
        'features' => '包括基礎方案所有權益，加上健身指導和私人教練服務',
        'duration' => '每年續約',
        'image' => '../final project/image/ada.jpg'
    ],
    3 => [
        'title' => 'VIP方案',
        'price' => 'NT$3,200 / 月',
        'features' => '全方位健身服務，包括營養諮詢和個人化健康管理計劃',
        'duration' => '每年續約',
        'image' => '../final project/image/anan.jpg'
    ]
];
?>

<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <title>會員方案</title>
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
        nav ul {
            list-style: none;
            padding: 0;
        }
        nav ul li {
            display: inline;
            margin-right: 10px;
        }
        a {
            color: white;
            text-decoration: none;
        }
        section {
            display: flex;
            justify-content: space-around;
            flex-wrap: wrap;
            margin: 20px;
        }
        .plan {
            background: #fff;
            border: 1px solid #ddd;
            padding: 20px;
            margin: 10px;
            width: calc(33% - 40px);
            box-sizing: border-box;
            text-align: center;
            box-shadow: 0px 0px 10px #aaa;
        }
        .plan img {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            margin-bottom: 10px;
        }
        footer {
            text-align: center;
            padding: 10px 0;
            background: #333;
            color: white;
        }
        .login-button, .home-button {
            background: #5cb85c;
            padding: 10px 20px;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }
        .logo {
            float: left;
        }
        h1 {
            text-align: center;
            width: 100%;
        }
    </style>
</head>
<body>
    <header>
        <div class="logo">Fitness Hub健身房選課系統</div>
    </header>

    <h1>會員方案</h1>

    <section>
        <?php foreach ($membershipPlans as $plan): ?>
            <div class="plan">
                <img src="<?php echo $plan['image']; ?>" alt="<?php echo $plan['title']; ?>">
                <h3><?php echo $plan['title']; ?></h3>
                <p>價格：<?php echo $plan['price']; ?></p>
                <p>特色：<?php echo $plan['features']; ?></p>
                <p>期限：<?php echo $plan['duration']; ?></p>
            </div>
        <?php endforeach; ?>
    </section>

    <footer>
        <a href="plan_pdf.php" class="home-button">會員方案下載</a>
        <a href="member.php" class="home-button">返回會員專區</a>
        <p>Contact us at Fitness_Hub@gmail.com</p>
    </footer>
</body>
</html>