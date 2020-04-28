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
			 mysql_select_db('hdc',$connect1);
	         mysql_query("SET NAMES 'utf8'",$connect1);

if (!$connect1) {
    die("Could not connect to MySQL server : ". mysql_error());
	exit;
}else{
	print("Connected HDC Success !! \n");
}

 $sql_ins = "insert into u_count_line_notify(SELECT NOW() DDATE,'8' DAYS,a.ampurcodefull,a.ampurname,count(hospcode) RS,GROUP_CONCAT(x.hospcode ORDER BY x.hospcode) hospcodezero
			FROM (SELECT concat(c.provcode,c.distcode) ampcode,c.hoscode hospcode,c.hosname hospname,IFNULL(t.RS,0) ss 
			FROM chospital c
			LEFT JOIN (SELECT hospcode,COUNT(*) RS FROM service 
			WHERE date_serv BETWEEN DATE_SUB(CURDATE(),INTERVAL 9 DAY) AND DATE_FORMAT(NOW(),'%Y-%m-%d') 
			GROUP BY hospcode) t ON c.hoscode = t.hospcode 
			WHERE provcode = '49' AND hdc_regist = 1 AND c.hoscode NOT IN ('12290','77674')) x
			INNER JOIN campur a ON x.ampcode = a.ampurcodefull
			WHERE x.ss < 9 GROUP BY a.ampurcodefull);";
 $rs_ins = mysql_query($sql_ins);
	        if($rs_ins){		
			$sql_line = "SELECT concat(ampurname,'มี ',rs,' หน่วยบริการ : ',hospzero) hzero FROM u_count_line_notify where days = 8 and DATE_FORMAT(ddate,'%Y%m%d') = DATE_FORMAT(NOW(),'%Y%m%d')";
			$rs_line = mysql_query($sql_line);
			$table = '';
			while($arr = mysql_fetch_array($rs_line, MYSQL_ASSOC)){ 
                     foreach ($arr as $val_col){ 
                         $table .= $val_col."\n"; 
                     } 
            }
			#$rs = mysql_fetch_assoc($rs_line);
			$txt = "auto: สวัสดีครับ ขณะนี้ ".$table." ยังไม่ได้ส่งข้อมูลแฟ้ม  service ติดต่อกันเป็นเวลา 8 วันหรือต่ำกว่า 5 record ภายใน 8 วัน  ตรวจสอบได้ที่ https://mdh.hdc.moph.go.th/";
			line_text($txt);
			}else{
				//echo $sql_ins;
			}
 
$sql_ins = "insert into u_count_line_notify(SELECT NOW() DDATE,'5' DAYS,a.ampurcodefull,a.ampurname,count(hospcode) RS,GROUP_CONCAT(x.hospcode ORDER BY x.hospcode) hospcodezero
			FROM (SELECT concat(c.provcode,c.distcode) ampcode,c.hoscode hospcode,c.hosname hospname,IFNULL(t.RS,0) ss 
			FROM chospital c
			LEFT JOIN (SELECT hospcode,COUNT(*) RS FROM service 
			WHERE date_serv BETWEEN DATE_SUB(CURDATE(),INTERVAL 6 DAY) AND DATE_FORMAT(NOW(),'%Y-%m-%d') 
			GROUP BY hospcode) t ON c.hoscode = t.hospcode 
			WHERE provcode = '49' AND hdc_regist = 1 AND c.hoscode NOT IN ('12290','77674')) x
			INNER JOIN campur a ON x.ampcode = a.ampurcodefull
			WHERE x.ss < 6 GROUP BY a.ampurcodefull);";
			
 $rs_ins = mysql_query($sql_ins);
	        if($rs_ins){		
			$sql_line = "SELECT concat(ampurname,'มี ',rs,' หน่วยบริการ : ',hospzero) hzero FROM u_count_line_notify where days = 5 and DATE_FORMAT(ddate,'%Y%m%d') = DATE_FORMAT(NOW(),'%Y%m%d')";
			$rs_line = mysql_query($sql_line);
			$table = '';
			while($arr = mysql_fetch_array($rs_line, MYSQL_ASSOC)){ 
                     foreach ($arr as $val_col){ 
                         $table .= $val_col."\n"; 
                     } 
            }
			#$rs = mysql_fetch_assoc($rs_line);
			$txt = "auto: สวัสดีครับ ขณะนี้ ".$table." ยังไม่ได้ส่งข้อมูลแฟ้ม  service ติดต่อกันเป็นเวลา 5 วันหรือต่ำกว่า 5 record ภายใน 5 วัน  ตรวจสอบได้ที่ https://mdh.hdc.moph.go.th/";
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