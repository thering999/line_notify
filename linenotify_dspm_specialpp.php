<?php
/*
 * @Author: Sakarin Habusaya 
 * @Date: 2017-07-06 10:23:02 
 * @Last Modified by: Sakarin Habusaya
 * @Last Modified time: 2017-07-06 11:43:01
 */



  //line_text("auto: สวัสดีครับ");
  
 $connect1 = mysql_connect("localhost","root","xxxxxx");
			 mysql_select_db('hdc',$connect1);
	         mysql_query("SET NAMES 'utf8'",$connect1);

if (!$connect1) {
    die("Could not connect to MySQL server : ". mysql_error());
	exit;
}else{
	print("Connected Data Center Success !! \n");
}

$sql_ins = "SELECT  CONCAT(aa.hoscode ,'->',GROUP_CONCAT('HN : ',pid,'  สิ้นสุด ',e_start)) 
FROM (SELECT c.hoscode,pid,date_start,date_end,DATE_ADD(date_start,INTERVAL 15 DAY) e_start
			FROM dspm_ssj_specialpp p 
      INNER JOIN chospital c on c.hoscode = p.hospcode 
			WHERE p.dx_dspm_end IS NULL AND p.date_end <= CURDATE()
						AND p.agemonth IN ('40','30','18','9') ORDER BY birth) aa 
GROUP BY aa.hoscode
";
			
 $rs_ins = mysql_query($sql_ins);
	        if($rs_ins){		
			$sql_line = "SELECT  CONCAT(aa.hoscode ,'->',GROUP_CONCAT('HN : ',pid,'  สิ้นสุด ',e_start)) 
			FROM (SELECT c.hoscode,pid,date_start,date_end,DATE_ADD(date_start,INTERVAL 15 DAY) e_start
						FROM dspm_ssj_specialpp p 
				  INNER JOIN chospital c on c.hoscode = p.hospcode 
						WHERE p.dx_dspm_end IS NULL AND p.date_end <= CURDATE()
									AND p.agemonth IN ('40','30','18','9') ORDER BY birth) aa 
            GROUP BY aa.hoscode
            ";
			$rs_line = mysql_query($sql_line);
			$table = '';
			while($arr = mysql_fetch_array($rs_line, MYSQL_ASSOC)){ 
                     foreach ($arr as $val_col){ 
                         $table .= $val_col."\n"; 
                     } 
            }
			#$rs = mysql_fetch_assoc($rs_line);
			$txt = "แจ้งข้อมูลตรวจ DSPM เกินกำหนดเริ่มตรวจ 15วัน ดังนี้ ".$table." โปรดดำเนินการด้วยครับ.";
			line_text($txt);
			}else{
				echo $sql_ins;
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
                $headers = array( 'Content-type: application/x-www-form-urlencoded', 'Authorization: Bearer xxxxxx', );
            
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