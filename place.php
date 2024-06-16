<?php
$facilityDetails = [
    1 => [
        'name' => '主訓練室',
        'location' => '一樓',
        'description' => '寬敞的訓練空間，配備各類健身器材，適合進行各種團體課程及個人訓練',
        'image' => '../final project/image/main_training_room.png'
    ],
    2 => [
        'name' => '瑜伽室',
        'location' => '二樓',
        'description' => '提供安靜舒適的瑜伽練習環境，地面鋪設專業瑜伽墊，設有調光系統以營造放鬆氛圍',
        'image' => '../final project/image/yoga_room.png'
    ],
    3 => [
        'name' => '重量訓練區',
        'location' => '一樓',
        'description' => '專為重量訓練設計，配有高品質的自由重量和機械訓練器材，適合各個水平的健身愛好者',
        'image' => '../final project/image/weight_training_area.png'
    ],
    4 => [
        'name' => '有氧舞蹈教室',
        'location' => '三樓',
        'description' => '寬敞明亮的有氧舞蹈教室，配備鏡子和音響設備，適合各類有氧舞蹈課程',
        'image' => '../final project/image/aerobic_dance_room.png'
    ],
    5 => [
        'name' => '拳擊訓練室',
        'location' => '地下室',
        'description' => '專業的拳擊訓練設施，提供擂台和各類拳擊訓練器材，適合各個水平的拳擊愛好者',
        'image' => '../final project/image/boxing_training_room.png'
    ],
    6 => [
        'name' => '普拉提訓練室',
        'location' => '二樓',
        'description' => '舒適的普拉提訓練室，配備專業普拉提設備，適合進行普拉提練習和核心訓練',
        'image' => '../final project/image/pilates_training_room.png'
    ]
];
?>

<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <title>場館介紹</title>
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
        .facility {
            background: #fff;
            border: 1px solid #ddd;
            padding: 20px;
            margin: 10px;
            width: calc(33% - 40px);
            box-sizing: border-box;
            text-align: center;
            box-shadow: 0px 0px 10px #aaa;
        }
        .facility img {
            width: 100%;
            height: auto;
            max-width: 300px;
            margin-bottom: 10px;
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

    <h1>場館介紹</h1>

    <section>
        <?php foreach ($facilityDetails as $facility): ?>
            <div class="facility">
                <img src="<?php echo $facility['image']; ?>" alt="<?php echo $facility['name']; ?>">
                <h3><?php echo $facility['name']; ?></h3>
                <p>位置：<?php echo $facility['location']; ?></p>
                <p>描述：<?php echo $facility['description']; ?></p>
            </div>
        <?php endforeach; ?>
    </section>

    <footer>
        <a href="index.php" class="home-button">返回首頁</a>
        <p>Contact us at Fitness_Hub@gmail.com</p>
    </footer>
</body>
</html>
