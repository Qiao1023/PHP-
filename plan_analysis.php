<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

$link = @mysqli_connect( 
            'localhost',  // MySQL主機名稱 
            'root',       // 使用者名稱 
            '',  // 密碼
            'fitness_hub');  // 預設使用的資料庫名稱

if (!$link) {
    die("無法開啟資料庫!<br/>");
}

// SQL語法
$SQL = "SELECT plan_id, COUNT(*) as count FROM member_selections GROUP BY plan_id";
// 送出查詢
$result = mysqli_query($link, $SQL);

if (!$result) {
    die("查詢失敗: " . mysqli_error($link));
}

$planNames = [
    1 => '基礎方案',
    2 => '進階方案',
    3 => 'VIP方案'
];
?>

<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <title>會員方案分析 - Fitness Hub健身房選課系統</title>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawChart);

      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['方案', '選擇次數'],
          <?php
          while($row = mysqli_fetch_assoc($result)){
            $Plan = isset($planNames[$row['plan_id']]) ? $planNames[$row['plan_id']] : '未知方案';
            $Count = $row['count'];
            echo "['$Plan', $Count],";
          }
          ?>
        ]);

        var options = {
          title: '顧客選擇的會員方案'
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
