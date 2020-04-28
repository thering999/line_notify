<?php
/*

 */



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
$sql_ins = "TRUNCATE TABLE u_count_line_notify;";

 $rs_ins = mysql_query($sql_ins);
	        if($rs_ins){		
			$sql_line = "TRUNCATE TABLE u_count_line_notify;";
			$rs_line = mysql_query($sql_line);
			$table = '';
			while($arr = mysql_fetch_array($rs_line, MYSQL_ASSOC)){ 
                     foreach ($arr as $val_col){ 
                         $table .= $val_col."\n"; 
                     } 
            }
			#$rs = mysql_fetch_assoc($rs_line);
			$txt = "ระบบ Line Notify เริ่มทำงาน";
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
	curl_setopt( $chOne, CURLOPT_POSTFIELDS, "message=ระบบ Line Notify เริ่มทำงาน !!!!
	ท่านสามารถติดต่อ Line Chat Bot IT.สสจ มุกดาหาร เพื่อตอบปัญหาต่างๆออนไลน์ 24 ซม ได้ที่ QR CODE ด้านล่างต่อไปนี้ &imageThumbnail=http://203.157.172.2/line_notify/QR_code.png&imageFullsize=http://203.157.172.2/line_notify/QR_code.png"); 
    
	//อันนี้โชว์ logo hdc curl_setopt( $chOne, CURLOPT_POSTFIELDS, "message=ระบบ Line Notify เริ่มทำงาน ท่านสามารถติดต่อ Line Chat Bot เพื่อตอบปัญหาต่างๆออนไลน์ 24 ซม ได้ที่ QR CODE ต่อไปนี้ &imageThumbnail=https://mdh.hdc.moph.go.th/hdc/themes/img/hdc_menu.png&imageFullsize=https://mdh.hdc.moph.go.th/hdc/themes/img/hdc_menu.png"); 
    
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