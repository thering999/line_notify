<?
include("includes/dbconn.php");   


$sql = "SELECT * from cockpit62.line_notify 
ORDER BY days DESC
limit 120";

?>
<style type="text/css">
<!--
.style1 {
	color: #0000FF;
	font-weight: bold;
}
-->

</style>
<meta http-equiv='Content-Type' content='text/html; charset=utf-8' />
<h2 align="center"><img src="./images/Header.jpg" width="970" height="160" border="2" /></h2>
<h2 align="center" class="style1">ตรวจสอบการบันทึก COCKPIT</h2>
<p align="center"><a href="index.php">กลับไปยังหน้าหลัก</a></p>
<table border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#000000">
  <tr>

    <td><div align="center"><abbr title="kpi ลำดับที่">kpi ลำดับที่</abbr><img src="./images/b_views.png" border="0" /></a></div></td>
    <td><div align="center"><abbr title="ชื่อตัวชี้วัด">ชื่อตัวชี้วัด<img src="./images/b_views.png" border="0" /></a></abbr></div></td>
    <td><div align="center"><abbr title="ฝ่ายที่รับผิดชอบ">ฝ่ายที่รับผิดชอบ<img src="./images/b_views.png" border="0" /></a></abbr></div></td>
    <td><div align="center"><abbr title="วัน เดือน ปี ที่บันทึกล่าสุด">วัน เดือน ปี ที่บันทึกล่าสุด<img src="./images/b_views.png" border="0" /></a></abbr></div></td>
    <td><div align="center"><abbr title="จำนวนวันที่ขาดการบันทึก">จำนวนวันที่ขาดการบันทึก<img src="./images/b_views.png" border="0" /></a></abbr></div></td>

	
  </tr>

  <?php
 $objQuery = mysql_query($sql) or die(mysql_error());
 while($row = mysql_fetch_array($objQuery))
{
 ?>
  <tr>
    
    
    <td><div align="center"><? echo ($row['kpi_id']); ?></div></td>
    <td><div align="center"><? echo ($row['kpi_detail']); ?></div></td>
    <td><div align="center"><? echo ($row['fname']); ?></div></td>
   <td><div align="center"><? echo ($row['kpi_update']); ?></div></td>
   <td><div align="center"><? echo ($row['DAYS']); ?></div></td>
    
	 
  <?
 }
 ?>

</table>


<div id="example1" class="target" title="The content of this tooltip is provided by the 'title' attribute of the target element.">DESIGN BY SAKARIN HABUSAYA </div>



