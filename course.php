<?php
$courseDetails = [
    1 => [
        'title' => '瑜伽課程',
        'description' => '瑜伽結合了身體、心靈和精神的運動，適合所有年齡層，課程將幫助你提高靈活性、平衡和整體健康',
        'duration' => '每次課程約60分鐘',
        'image' => '../final project/image/yoga.png'  
    ],
    2 => [
        'title' => '健身操',
        'description' => '透過有趣的舞蹈和節奏，這個健身操課程將提高你的心肺功能和肌肉力量，適合所有健身水平',
        'duration' => '每次課程約60分鐘',
        'image' => '../final project/image/fitness.png'
    ],
    3 => [
        'title' => '重量訓練',
        'description' => '這個重量訓練課程專為想要增強肌肉力量和體態的人設計，包括自由重量和機械訓練',
        'duration' => '每次課程約60分鐘',
        'image' => '../final project/image/weight_training.png'
    ],
    4 => [
        'title' => '有氧舞蹈',
        'description' => '透過有氧舞蹈，你可以燃燒卡路里並享受音樂帶來的樂趣',
        'duration' => '每次課程約60分鐘',
        'image' => '../final project/image/aerobic_dance.png'
    ],
    5 => [
        'title' => '拳擊訓練',
        'description' => '拳擊訓練為一種高強度的全身運動，幫助你提升心肺功能和全身肌肉力量',
        'duration' => '每次課程約60分鐘',
        'image' => '../final project/image/boxing.png'
    ],
    6 => [
        'title' => '普拉提',
        'description' => '普拉提課程強調核心肌群的訓練，有助於改善姿勢和整體肌肉張力',
        'duration' => '每次課程約60分鐘',
        'image' => '../final project/image/pilates.png'
    ]
];
?>

<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <title>所有課程</title>
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
        .course {
            background: #fff;
            border: 1px solid #ddd;
            padding: 20px;
            margin: 10px;
            width: calc(33% - 40px);
            box-sizing: border-box;
            text-align: center;
        }
        .course img {
            max-width: 100%;
            height: auto;
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

    <h1>課程內容及細節</h1>

    <section>
        <?php foreach ($courseDetails as $course): ?>
            <div class="course">
                <h3><?php echo $course['title']; ?></h3>
                <img src="<?php echo $course['image']; ?>" alt="<?php echo $course['title']; ?>">
                <p><?php echo $course['description']; ?></p>
                <p>課程時長：<?php echo $course['duration']; ?></p>
            </div>
        <?php endforeach; ?>
    </section>

    <footer>
        <a href="course_pdf.php" class="home-button">課程下載</a>
        <a href="index.php" class="home-button">返回首頁</a>
        <p>Contact us at Fitness_Hub@gmail.com</p>
    </footer>
</body>
</html>
