<?php

$name = $_POST['name'];
$email = $_POST['email'];
$phone = $_POST['phone'];
$lineid = $_POST['lineid'];
$mesg = $_POST['mesg'];

$message = "\n".'ชื่อเรื่อง:'.$mesg."\n".'จาก: '.$name."\n".'อีเมล์: '.$email."\n".'Phone: '.$phone."\n".'Line ID: '.$lineid;

if($name<>"" || $email <> "" || $mesg <> "") {
	
	sendlinemesg();

	header('Content-Type: text/html; charset=utf-8');
	$res = notify_message($message);
	echo "<center>ส่งข้อความเรียบร้อยแล้ว หากท่านต้องการติดต่อด่วนกรุณา โทรได้ที่เบอร์โทร 042611450 ต่อ 111</center>";
	echo "<center>หรือ ท่านสามารถติดต่อ Line Chat Bot IT.สสจ มุกดาหาร เพื่อตอบปัญหาต่างๆออนไลน์ 24 ซม ได้ที่ QR CODE ด้านล่างต่อไปนี้</center>";
	echo "<center><br /></center>";
	echo "<center><br /></center>";
	echo '<center>Line Chat Bot IT.สสจ มุกดาหาร</center>';
	echo '<center><img src="../line_notify/QR_code.png" /></center>';
} else {
	echo "<center>Error: กรุณากรอกข้อมูลให้ครบถ้วน</center>";
}

function sendlinemesg() {
	
    define('LINE_API',"https://notify-api.line.me/api/notify");
	define('LINE_TOKEN','xxxxxx');

	function notify_message($message){

		$queryData = array('message' => $message);
		$queryData = http_build_query($queryData,'','&');
		$headerOptions = array(
			'http'=>array(
				'method'=>'POST',
				'header'=> "Content-Type: application/x-www-form-urlencoded\r\n"
						."Authorization: Bearer ".LINE_TOKEN."\r\n"
						."Content-Length: ".strlen($queryData)."\r\n",
				'content' => $queryData
			)
		);
		$context = stream_context_create($headerOptions);
		$result = file_get_contents(LINE_API,FALSE,$context);
		$res = json_decode($result);
		return $res;

	}

}

?>