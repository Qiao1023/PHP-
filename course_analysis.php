<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

$link = @mysqli_connect(
    'localhost',  // MySQL主機名稱 
    'root',       // 使用者名稱 
    '',  // 密碼
    'fitness_hub'  // 預設使用的資料庫名稱
);

if (!$link) {
    die("無法開啟資料庫!<br/>");
}

// SQL語法
$SQL = "SELECT course_id, COUNT(*) as count FROM member_selections GROUP BY course_id";
// 送出查詢
$result = mysqli_query($link, $SQL);

if (!$result) {
    die("查詢失敗: " . mysqli_error($link));
}

$courseNames = [
    1 => '瑜伽課程',
    2 => '健身課程',
    3 => '重量訓練',
    4 => '有氧舞蹈',
    5 => '拳擊訓練',
    6 => '普拉提'
];
?>

<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <title>課程分析 - Fitness Hub健身房選課系統</title>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawChart);

      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['課程', '選擇次數'],
          <?php
          while($row = mysqli_fetch_assoc($result)){
            $Course = isset($courseNames[$row['course_id']]) ? $courseNames[$row['course_id']] : '未知課程';
            $Count = $row['count'];
            echo "['$Course', $Count],";
          }
          ?>
        ]);

        var options = {
          title: '會員選擇的課程'
        };

        var chart = new google.visualization.PieChart(document.getElementById('piechart'));

        chart.draw(data, options);
      }
    </script>
</head>
<body>
    <div id="piechart" style="width: 900px; height: 500px;"></div>
</body>
</html>
