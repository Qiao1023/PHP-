<meta charset='utf8'>

<?php

require_once('TCPDF/tcpdf.php');

ob_start();
// // create new PDF document
$pdf = new TCPDF('L', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
// set font
$pdf->SetFont('msungstdlight', '', 10);
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);
$pdf->AddPage();

$link = @mysqli_connect( 
    'localhost',  // MySQL主機名稱 
    'root',       // 使用者名稱 
    '',  // 密碼
    'fitness_hub');  // 預設使用的資料庫名稱

//SQL語法
$SQL="SELECT * FROM courses";
//送出查詢
$result = mysqli_query($link, $SQL);

$html='<table border="1">';
$html.='<tr><td>編號</td><td>課程</td><td>課程介紹</td><td>日期</td><td>時間</td></tr>';
while($row=mysqli_fetch_assoc($result)){
$html.="<tr>";
$html.="<td>".$row["id"]."</td><td>".$row["title"]."</td><td>".$row["description"]."</td><td>".$row["day"]."</td><td>".$row["time"]."</td>";
$html.="</tr>";
}
$html.="</table>";

// output the HTML content
$pdf->writeHTML($html);
//file send to file address
$path = "/課程介紹.pdf";
//Close and output PDF document
$pdf->Output(__DIR__ .$path, 'D');
//$pdf->Output($No."-tableinfo.pdf", 'D');
ob_end_flush();

?>