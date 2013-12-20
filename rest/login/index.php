<?php

  include "../lib/mysql.php";

  
  function genrandomStr($len) {
    $chars= "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz";
    $res = "";  
  
    for ($i = 0; $i < $len; $i++) {
      $pos = mt_rand(0, strlen($chars));
      $res .= substr($chars, $pos, 1); 
    }
    return $res;
  }

  function login($name, $pwd) {
    $mysql = mysql::getInstance();
    $uid = $mysql->check_user($name, $pwd);
    if (-1 == $uid) {
      return array("ret"=>"-1", "info"=>"failed");
    } 
    
    if ($mysql->is_user_online($uid)) {
      $mysql->update_user_online($uid);
      return array("ret"=>"0", "info"=>"already logged in");;
    }else {
      $access_token = genRandomStr(32);
      $refresh_token = genRandomStr(32);
      $mysql->add_user_online($uid, $access_token, $refresh_token);
      return array("ret"=>"0", "info"=>"success", "access_token"=>$access_token, "refresh_token"=>$refresh_token);
    }
  }

  $method = $_SERVER["REQUEST_METHOD"];
  $name = htmlspecialchars($_POST["name"]);
  $pwd = md5($_POST["pwd"]);
  if ($method == "POST" && isset($name) && isset($pwd)) {
    $res = login($name, $pwd);
    echo json_encode($res);
  } else {
      echo "error <br />";
  }

?>

