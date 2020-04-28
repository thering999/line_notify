<?php
/*
 * @Author: Sakarin Habusaya 
 * @Date: 2017-07-06 10:23:02 
 * @Last Modified by: Sakarin Habusaya
 * @Last Modified time: 2017-07-06 11:43:01
 */

//  group note -> xxxxxx

  //line_text("auto: สวัสดีครับ");
  
 $connect1 = mysql_connect("localhost","root","xxxxxx","TRUE");
			 mysql_select_db('cockpit62',$connect1);
	         mysql_query("SET NAMES 'utf8'",$connect1);

if (!$connect1) {
    die("Could not connect to MySQL server : ". mysql_error());
	exit;
}else{
	print("Connected Success !! \n");
}
$sql_ins = "TRUNCATE TABLE line_notify;";

 $sql_ins = "insert into line_notify
 SELECT NOW() DDATE,k.kpi_update,DATEDIFF(NOW(),kpi_update) as DAYS,('Line_notify_auto :')as error,
 k.kpi_id,k.kpi_detail,u.username,u.fname
 FROM kpi k 
 INNER JOIN user u ON u.username=k.kpi_own
 where k.kpi_status =1   
 and k.kpi_source =1;
 #and k.kpi_update = '0000-00-00';";

 $rs_ins = mysql_query($sql_ins);
	        if($rs_ins){		
			$sql_line = "
            SELECT 'ฝ่าย',
            line_notify.username,
            'จำนวน :',
            count(line_notify.kpi_id) AS count_kpi,
            'ข้อ,'
            FROM line_notify 
            INNER JOIN user u ON u.username=line_notify.username
            where days > 30
            and DATE_FORMAT(ddate,'%Y%m%d') = DATE_FORMAT(NOW(),'%Y%m%d')
            GROUP BY line_notify.username;
                        ";
			$rs_line = mysql_query($sql_line);
			$table = '';
			while($arr = mysql_fetch_array($rs_line, MYSQL_ASSOC)){ 
                     foreach ($arr as $val_col){ 
                         $table .= $val_col."\n"; 
                     } 
            }
			#$rs = mysql_fetch_assoc($rs_line);
			$txt = "auto: สวัสดีครับ ขณะนี้รายละเอียดตัวชี้วัด ยังไม่ได้บันทึกข้อมูลในระบบ cockpit เป็นเวลามากกว่า 30 วัน  ได้แก่ ".$table."   ตรวจสอบได้ที่ http://203.157.172.2/cockpit62/find_show_line_notify.php";
			line_text($txt);
			}else{
				//echo $sql_ins;
			}
 

			
 function line_text($text){
    $chOne = curl_init(); 
    curl_setopt( $chOne, CURLOPT_URL, "https://notify-api.line.me/api/notify"); 
    // SSL USE 
    curl_setopt( $chOne, CURLOPT_SSL_VERIFYHOST, 0); 
    curl_setopt( $chOne, CURLOPT_SSL_VERIFYPEER, 0); 
    //POST 
    curl_setopt( $chOne, CURLOPT_POST, 1); 
    // Message 
    curl_setopt( $chOne, CURLOPT_POSTFIELDS, "message=".$text.""); 
    //ถ้าต้องการใส่รุป ให้ใส่ 2 parameter imageThumbnail และimageFullsize
    //curl_setopt( $chOne, CURLOPT_POSTFIELDS, "message=hi&imageThumbnail=http://www.wisadev.com/wp-content/uploads/2016/08/cropped-wisadevLogo.png&imageFullsize=http://www.wisadev.com/wp-content/uploads/2016/08/cropped-wisadevLogo.png"); 
    // follow redirects 
    curl_setopt( $chOne, CURLOPT_FOLLOWLOCATION, 1); 
    //ADD header array 
    $headers = array( 'Content-type: application/x-www-form-urlencoded', 'Authorization: Bearer tCTp2AN1WFB9AN2UlmwlDnqwlCwZqbDu1wdtQgjPY1c', );

	//$headers = array( 'Content-type: application/x-www-form-urlencoded', 'Authorization: Bearer QCk4eJmlaxGMDBnLVLqHVllEsdQnXXXXXXXXXXXX', );
	
    curl_setopt($chOne, CURLOPT_HTTPHEADER, $headers); 
    //RETURN 
    curl_setopt( $chOne, CURLOPT_RETURNTRANSFER, 1); 
    $result = curl_exec( $chOne ); 
    //Check error 
    if(curl_error($chOne)) { echo 'error:' . curl_error($chOne); } 
    else { $result_ = json_decode($result, true); 
    echo "status : ".$result_['status']; echo "message : ". $result_['message']; } 
    //Close connect 
    curl_close( $chOne ); 

 }

?>