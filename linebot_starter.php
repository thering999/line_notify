<?php
  error_reporting(0);
  date_default_timezone_set("Asia/Bangkok");
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
  
  $ACCESS_TOKEN = "oawOaY1ec52PBIUXDTivGmgjOC5y09L9ScZsPs94Zus";
  $POST_HEADER = array('Content-Type: application/json', 'Authorization: Bearer '.$ACCESS_TOKEN);
  $API_URL = "https://api.line.me/v2/bot/message";

  $REQUEST = file_get_contents('php://input'); 
  $REQUEST_ARRAY = json_decode($REQUEST, true);
  $group_id = $REQUEST_ARRAY['events'][0]['source']['groupId'];
  $lineid   = $REQUEST_ARRAY['events'][0]['source']['userId'];
  $reply_token = $REQUEST_ARRAY['events'][0]['replyToken'];

  $message = $REQUEST_ARRAY["events"][0]["message"]["text"];
  if($message=="groupid"){
    $data['replyToken']  = $reply_token;
    $data['messages'][0]['type'] = "text";
    $data['messages'][0]['text'] = $group_id;
    $data['messages'][1]['type'] = "sticker";
    $data['messages'][1]['packageId'] = "2";
    $data['messages'][1]['stickerId'] = "34";
    $POST_BODY = json_encode($data, JSON_UNESCAPED_UNICODE);
    $send_result = sendMessage($API_URL.'/reply', $POST_HEADER, $POST_BODY);
  }else if($message=="myid"){
    $data['replyToken']  = $reply_token;
    $data['messages'][0]['type'] = "text";
    $data['messages'][0]['text'] = $lineid;
    $POST_BODY = json_encode($data, JSON_UNESCAPED_UNICODE);
    $send_result = sendMessage($API_URL.'/reply', $POST_HEADER, $POST_BODY);
  }
  exit;
?>