<?php
$trainerDetails = [
    1 => [
        'name' => '花花',
        'specialty' => '瑜伽專家',
        'experience' => '10年',
        'description' => '擁有國際瑜伽教練證書，專注於幫助初學者掌握基礎和提高靈活性',
        'image' => '../final project/image/huahua.jpeg'
    ],
    2 => [
        'name' => '泡泡',
        'specialty' => '健身教練',
        'experience' => '8年',
        'description' => '專業健身教練，提供一對一健身訓練，專注於肌力訓練和體重管理',
        'image' => '../final project/image/paopao.jpeg'
    ],
    3 => [
        'name' => '毛毛',
        'specialty' => '重量訓練專家',
        'experience' => '12年',
        'description' => '專長於重量訓練和肌肉增長策略，幫助客戶達到其健康和健身目標',
        'image' => '../final project/image/maomao.jpeg'
    ],
    4 => [
        'name' => '飛哥',
        'specialty' => '有氧舞蹈教練',
        'experience' => '5年',
        'description' => '擁有有氧舞蹈教練資格，專注於通過舞蹈提升心肺功能和身體協調性',
        'image' => '../final project/image/feige.jpeg'
    ],
    5 => [
        'name' => '小佛',
        'specialty' => '拳擊訓練教練',
        'experience' => '7年',
        'description' => '專業拳擊訓練教練，擅長幫助學員提高爆發力和耐力，增強身體素質',
        'image' => '../final project/image/xiaofu.jpeg'
    ],
    6 => [
        'name' => '泰瑞',
        'specialty' => '普拉提教練',
        'experience' => '6年',
        'description' => '擁有普拉提教練資格，專注於核心力量和靈活性的提升',
        'image' => '../final project/image/tairui.jpg'
    ]
];
?>

<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <title>教練團隊</title>
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
            flex-wrap: wrap;
            justify-content: space-around;
            margin: 20px;
        }
        .trainer {
            background: #fff;
            border: 1px solid #ddd;
            padding: 20px;
            margin: 10px;
            width: calc(33% - 40px);
            box-sizing: border-box;
            text-align: center;
            box-shadow: 0px 0px 10px #aaa;
        }
        .trainer img {
            width: 100%;
            height: auto;
            max-width: 200px;
            border-radius: 50%;
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
            display: inline-block;
            margin-top: 10px;
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

    <h1>教練團隊</h1>

    <section>
        <?php foreach ($trainerDetails as $trainer): ?>
            <div class="trainer">
                <img src="<?php echo $trainer['image']; ?>" alt="<?php echo $trainer['name']; ?>">
                <h3><?php echo $trainer['name']; ?></h3>
                <p>專長：<?php echo $trainer['specialty']; ?></p>
                <p>經驗：<?php echo $trainer['experience']; ?></p>
                <p>介紹：<?php echo $trainer['description']; ?></p>
            </div>
        <?php endforeach; ?>
    </section>

    <footer>
        <a href="index.php" class="home-button">返回首頁</a>
        <p>Contact us at Fitness_Hub@gmail.com</p>
    </footer>
</body>
</html>
