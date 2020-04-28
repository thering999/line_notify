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

 $sql_ins = "select d.countpop - p.countperson as total_diff,
 Sum(p.countperson)/Sum(d.countpop)*100 AS per
          
     from (
            select count(dtype13) as countpop
            from s_person_dbpop
          ) as d
     cross join (
             select count(distinct ptype13) as countperson
             from s_person_dbpop) as p";
 $rs_ins = mysql_query($sql_ins);
	        if($rs_ins){		
			$sql_line = "select d.countpop - p.countperson as total_diff,
            Sum(p.countperson)/Sum(d.countpop)*100 AS per
                     
                from (
                       select count(dtype13) as countpop
                       from s_person_dbpop
                     ) as d
                cross join (
                        select count(distinct ptype13) as countperson
                        from s_person_dbpop) as p";
			$rs_line = mysql_query($sql_line);
			$table = '';
			while($arr = mysql_fetch_array($rs_line, MYSQL_ASSOC)){ 
                     foreach ($arr as $val_col){ 
                         $table .= $val_col."\n"; 
                     } 
            }
			#$rs = mysql_fetch_assoc($rs_line);
			$txt = "auto: สวัสดีครับ ขณะนี้มีข้อมูล ความแตกต่างperson(type1,3)เปรียบเทียบdbpop(type1,3) อยู่ที่ (จำนวนคน / คิดเป็นเปอร์เซนต์) =".$table." ตรวจสอบได้ที่ https://mdh.hdc.moph.go.th/";
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