<?php
#$icd10 = $_GET["disease"] ;
//$icd10 = 10 ;
if(empty($icd10)){
    //echo "<h1>icd10 = $icd10</h1>" ;
}else{
    //echo "<h1>icd10 = $icd10</h1>" ;
$servername = "localhost";


$username = "root";
$password = "xxxxxx";

$database = "hdc" ;
//$database = "b_notify_506_2" ;
$table1 = "p4461_1_31673" ;

$province = "มุกดาหาร" ;
$today = date("Y-m-d");   //
$year_real =  date("Y") + "543"; 
$icd10 = 'TEDA4i';//$_GET["disease"] ;
$name_alert ="ติดตามข้อมูล [$icd10]"; 


$conn = mysqli_connect("$servername","$username","$password","$database") or die("Error " . mysqli_error($link)); 
mysqli_set_charset($conn,"utf8");
$query = "
select *,c.hoscode as hospitalcode from t_tida4i a 
LEFT JOIN t_person_cid b on a.cid=b.CID
LEFT JOIN chospital c on b.check_hosp=c.hoscode
where a.f_date is NULL and  a.l_date is null - 
" or die("Error in the consult.." . mysqli_error($link)); 

$result = $conn->query($query); 
mysqli_num_rows($result);

$result2 = $conn->query($query); 
$alldata = mysqli_num_rows($result2);

function sendToLine($message){       

        $line_api = 'https://notify-api.line.me/api/notify';
        $line_token = 'xxxxxx'; //TEDA4i
//ครอบครัวของฉัน


        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,"https://notify-api.line.me/api/notify");
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, 'message='.$message);
        // follow redirects
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-type: application/x-www-form-urlencoded',
            'Authorization: Bearer '.$line_token,
        ]);
        // receive server response ...
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $server_output = curl_exec ($ch);

        curl_close ($ch);
}
    sendToLine($name_alert .' ทั้งหมด=' .$alldata .' คน');

while($row2 = mysqli_fetch_array($result)) { 
    $hospitalcode = ($row2['hospitalcode']);
    $HOSNAME = ($row2['hosname']);
    $cid = ($row2['cid']);
    $NAME = ($row2['NAME']);
    $LNAME = ($row2['LNAME']);
    $HN = ($row2['HN']);
    $SEX = ($row2['SEX']);
    $vhid = ($row2['vhid']);
    $addr = ($row2['addr']);
    $age_y = ($row2['age_y']);
    $sp_code = ($row2['sp_code']);
    echo " $NAME $LNAME ".' ไม่ผ่านการติดตาม/ขาดการติดตาม :'.$sp_code ."<br>" ;

    sendToLine($name_alert .'['.$hospitalcode . ']' . $HOSNAME .
        ' ชื่อ :' . $NAME .' ' . $LNAME . ' HN:'.$HN .
        ' ไม่ผ่านการติดตาม/ขาดการติดตาม :'.
        'รหัสคัดกรอง : '.$sp_code .
        //' บ้านเลขที่ : '.$addr .
        " ") ;
        // ' ม.'.$moo.
        // ' ต.'.$tambonname.
        // ' อ.'.$ampurname.
        // ' จ.'.$changwatname);

}







}

?>