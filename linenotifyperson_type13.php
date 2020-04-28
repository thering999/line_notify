<?php

$host_takis="localhost"; //ip server
$name_takis="root";// username
$pw_takis="xxxxxx"; //password
$db_takis="hdc"; //database
$connect_takis=mysql_connect($host_takis,$name_takis,$pw_takis);
mysql_select_db($db_takis,$connect_takis);
if(!$connect_takis){
	echo "Connection Server fail !!!!!!";
}
else{
	print("Connected HDC Success !! \n");
}
$sql="SELECT CONCAT('จำนวนประชากร typearea1+3 ในระบบทั้งหมด HDC =',' ',COUNT(CID)) as total FROM person WHERE TYPEAREA in(1,3);";
$query = mysql_query($sql);
$rows = array();
while($r = mysql_fetch_array($query)) {
	$rs=$r[0]."-".$r[1].">";
	echo $rs."<br>";
        }
        
define('LINE_API',"https://notify-api.line.me/api/notify");
 
$token = "xxxxxx"; //ใส่Token ที่copy เอาไว้
$str = $rs; //ข้อความที่ต้องการส่ง สูงสุด 1000 ตัวอักษร
 
$res = notify_message($str,$token);
print_r($res);
function notify_message($message,$token){
 $queryData = array('message' => $message);
 $queryData = http_build_query($queryData,'','&');
 $headerOptions = array( 
         'http'=>array(
            'method'=>'POST',
            'header'=> "Content-Type: application/x-www-form-urlencoded\r\n"
                      ."Authorization: Bearer ".$token."\r\n"
                      ."Content-Length: ".strlen($queryData)."\r\n",
            'content' => $queryData
         ),
 );
 
 $context = stream_context_create($headerOptions);
 $result = file_get_contents(LINE_API,FALSE,$context);
 $res = json_decode($result);
 return $res;
}

        
?>