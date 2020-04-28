<!-- #!/usr/bin/php -q -->
<?php
error_reporting(0);
date_default_timezone_set('Asia/Bangkok');
function sendMessage($url, $post_header, $post_body){
  $ch = curl_init($url);
  curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch, CURLOPT_HTTPHEADER, $post_header);
  curl_setopt($ch, CURLOPT_POSTFIELDS, $post_body);
  curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
  $result = curl_exec($ch);
  curl_close($ch);
  return $result;
}
function get_remote_data($url, $post_paramtrs = false) {
  $c = curl_init();
  curl_setopt($c, CURLOPT_URL, $url);
  curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
  if ($post_paramtrs) {
    curl_setopt($c, CURLOPT_POST, TRUE);
    curl_setopt($c, CURLOPT_POSTFIELDS, "var1=bla&" . $post_paramtrs);
  } 
  curl_setopt($c, CURLOPT_SSL_VERIFYHOST, false);
  curl_setopt($c, CURLOPT_SSL_VERIFYPEER, false);
  curl_setopt($c, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.1; rv:33.0) Gecko/20100101 Firefox/33.0");
  curl_setopt($c, CURLOPT_COOKIE, 'CookieName1=Value;');
  curl_setopt($c, CURLOPT_MAXREDIRS, 10);
  $follow_allowed = ( ini_get('open_basedir') || ini_get('safe_mode')) ? false : true;
  if ($follow_allowed) {
    curl_setopt($c, CURLOPT_FOLLOWLOCATION, 1);
  }
  curl_setopt($c, CURLOPT_CONNECTTIMEOUT, 9);
  curl_setopt($c, CURLOPT_REFERER, $url);
  curl_setopt($c, CURLOPT_TIMEOUT, 100);
  curl_setopt($c, CURLOPT_AUTOREFERER, true);
  curl_setopt($c, CURLOPT_ENCODING, 'gzip,deflate');
  $data = curl_exec($c);
  $status = curl_getinfo($c);
  curl_close($c);
  preg_match('/(http(|s)):\/\/(.*?)\/(.*\/|)/si', $status['url'], $link);
  $data = preg_replace('/(src|href|action)=(\'|\")((?!(http|https|javascript:|\/\/|\/)).*?)(\'|\")/si', '$1=$2' . $link[0] . '$3$4$5', $data);
  $data = preg_replace('/(src|href|action)=(\'|\")((?!(http|https|javascript:|\/\/)).*?)(\'|\")/si', '$1=$2' . $link[1] . '://' . $link[3] . '$3$4$5', $data);
  if ($status['http_code'] == 200) {
      return $data;
  } elseif ($status['http_code'] == 301 || $status['http_code'] == 302) {
      if (!$follow_allowed) {
          if (empty($redirURL)) {
              if (!empty($status['redirect_url'])) {
                  $redirURL = $status['redirect_url'];
              }
          } if (empty($redirURL)) {
              preg_match('/(Location:|URI:)(.*?)(\r|\n)/si', $data, $m);
              if (!empty($m[2])) {
                  $redirURL = $m[2];
              }
          } if (empty($redirURL)) {
              preg_match('/href\=\"(.*?)\"(.*?)here\<\/a\>/si', $data, $m);
              if (!empty($m[1])) {
                  $redirURL = $m[1];
              }
          } if (!empty($redirURL)) {
              $t = debug_backtrace();
              return call_user_func($t[0]["function"], trim($redirURL), $post_paramtrs);
          }
      }else{
          echo "else follow_allowed";
      }
  } return "ERRORCODE22 with $url!!<br/>Last status codes<b/>:" . json_encode($status) . "<br/><br/>Last data got<br/>:$data";
}
function getConference($GID,$SITE,$DEPPART,$PROVINCE){
  global $API_URL,$POST_HEADER;
  $datetime = new DateTime('tomorrow');
  $tomorow = $datetime->format('Y-m-d');
  $url = 'http://conf.moph.go.th/showDetailCalenderVDO.php?page=view_detail&day='.$tomorow;
  $page = get_remote_data($url,10);
  $dom = new DOMDocument;
  $dom->loadHTML(mb_convert_encoding($page, 'HTML-ENTITIES', 'UTF-8'));
  $datas = array();
  $T=0;
  foreach( $dom->getElementsByTagName( 'table' ) as $tables ) {
    $R=0;
    foreach( $tables->getElementsByTagName('tr') as $tr ) {
      $ex=explode(":",$tr->getElementsByTagName('td')->item(0)->nodeValue);
      $key = str_replace(" ","",$ex[0]);
      $datas[$T][$key] = $tr->getElementsByTagName('td')->item(1)->nodeValue;
      $R++;
      if($R==20){break;}
    }
    $T++;
  }

  $flex_message = [
    "type"=> "flex",
    "altText"=> "แจ้งเตือน VDO Conference",
    "contents"=> [
      "type"=> "carousel",
      "contents"=>[]
    ]
  ];
 
  foreach( $datas as $tr ){
    if(is_array($tr)){
      if( intval($tr["จำนวนSites"])>=$SITE || intval($tr["จำนวนSites(ส่วนภูมิภาค)"])>=$SITE || preg_match("/(".$DEPPART.")/u",$tr["หน่วยงานผู้จัด"]) || preg_match('/'.$PROVINCE.'/',$tr["หมายเหตุ"])){
        array_push($flex_message["contents"]["contents"],[
          "type"=> "bubble",
          "direction"=> "ltr",
          "header"=> [
            "type"=> "box",
            "layout"=> "vertical",
            "contents"=> [
              [
                "type"=> "text",
                "text"=> "แจ้งเตือน VDO Conference",
                "flex"=> 1,
                "align"=> "center",
                "weight"=> "bold",
                "color"=> "#0D7810"
              ]
            ]
          ],
          "hero"=> [
            "type"=> "image",
            "url"=> "https://www.npm.moph.go.th/fileupload/1/image/it/MOPH_SS.png",
            "size" => "xl",
            "aspectRatio"=> "1.51:1",
            "aspectMode"=> "fit"
          ],
          "body"=> [
            "type"=> "box",
            "layout"=> "vertical",
            "contents"=> [
              [
                "type"=> "text",
                "text"=> $tr["หัวข้อการประชุม"],
                "align"=> "center",
                "size" => "md",
                "weight"=> "bold",
                "color"=> "#126509",
                "wrap" => true
              ],
              [
                "type"=> "separator",
                "color"=> "#CECCCC"
              ],
              [
                "type"=> "text",
                "text"=> $tr["หน่วยงานผู้จัด"],
                "align"=> "center"
              ],
              [
                "type"=> "text",
                "text"=> $tr["วันที่ใช้งาน"],
                "align"=> "center"
              ],
              [
                "type"=> "text",
                "text"=> $tr["ช่วงเวลา"],
                "align"=> "center"
              ],
              [
                "type"=> "text",
                "text"=> "จำนวน Sites ".$tr["จำนวนSites(ส่วนภูมิภาค)"],
                "align"=> "center"
              ],
              [
                "type"=> "text",
                "text"=> "หมายเลขห้อง ".$tr["RoomNumber"],
                "align"=> "center"
              ],
              [
                "type"=>"text",
                "text"=>" ** หากต้องการให้อำเภอเข้าร่วมด้วย กรุณาแจ้งงานไอที เบอร์ภายใน 111 **",
                "align"=> "center",
                "wrap" => true
              ],
              [
                "type"=> "separator",
                "color"=> "#CECCCC"
              ]
            ]
          ],
          "footer"=> [
            "type"=> "box",
            "layout"=> "horizontal",
            "contents"=> [
              [
                "type"=> "button",
                "action"=> [
                  "type"=> "uri",
                  "label"=> "จองห้องประชุม",
                  "uri"=> "http://www.mdo.moph.go.th/calendar/month.php"
                ],
                "color"=> "#217039",
                "height"=> "sm",
                "style"=> "primary"
              ]
            ]
          ]
        ]);
      }
    }
  }
  $data = [
    'to' => $GID,
    'messages' => [$flex_message]
  ];

  $POST_BODY = json_encode($data, JSON_UNESCAPED_UNICODE);
  sendMessage($API_URL.'/push', $POST_HEADER, $POST_BODY);
}

$ACCESS_TOKEN = "J7p9gNqMeuyvou61gkRLDKNj8pFjxfMryaMf14z06n3";
#$ITGROUP_ID = "";
$POST_HEADER = array('Content-Type: application/json', 'Authorization: Bearer '.$ACCESS_TOKEN);
$API_URL = "https://api.line.me/v2/bot/message";
$SITE = 70;
$DEPPART = "เขตสุขภาพที่10|เขตสุขภาพที่ 10|มุกดาหาร|ศรีสระเกษ|ยโสธร|อำนาจเจริญ|อุบลราชธานี";
$PROVINCE = "มุกดาหาร";

#getConference($ITGROUP_ID,$SITE,$DEPPART,$PROVINCE);
getConference($SITE,$DEPPART,$PROVINCE);
?>