
<?php

class mysql {

  private static $_instance;
  
  private function __construct() {
    mysql_connect("localhost","root","root");
    mysql_select_db("test");
  }

  static public function getInstance() {
    if (is_null(self::$_instance) || !isset(self::$_instance)) {
        self::$_instance = new self();
    }
    return self::$_instance;
  }

  // ok: return userid  nok: return -1
  public function check_user($name, $pwd) {
    $result = array();
    $cmd = "select id from user where name='$name' and pwd='$pwd'";
    $res = mysql_query($cmd);
    while($rows=mysql_fetch_array($res)){
      $result[]=$rows;
    }
    
    if (sizeof($result) == 1 && isset($result[0]["id"])) {
      return $result[0]["id"];  
    }else {
      return -1;
    }  
  }
  
  public function is_user_online($uid) {
    $result = array();
    $cmd = "select id from user_online where user_id='$uid'";
    $res = mysql_query($cmd);
    while($rows=mysql_fetch_array($res)){
      $result[]=$rows;
    }
    
    if (sizeof($result) == 1) {
      return true;  
    }else {
      return false;
    } 
  }  
  
  public function add_user_online($uid, $access_token, $refresh_token) {
    $cmd = "insert into user_online (user_id, login_time, access_token, refresh_token) values($uid, now(), '$access_token', '$refresh_token')";
    return mysql_query($cmd);
  }  
  
  public function delete_user_online($access_token) {
    $cmd = "delete from user_online where access_token='$access_token'";
    return mysql_query($cmd);
  } 
  
  public function update_user_online($uid) {
    $cmd = "update user_online set login_time=now() where user_id='$uid'";
    return mysql_query($cmd);
  }  
  
  public function is_user_exist($username) {
    $result = array();
    $cmd = "select name from user where name='$username'";
    $res = mysql_query($cmd);
    while($rows=mysql_fetch_array($res)){
      $result[]=$rows;
    }
    
    if (sizeof($result) == 1) {
      return true;  
    }else {
      return false;
    } 
  }   
  
  public function register_user($username, $pwd, $email) {
    $cmd = "insert into user(name, pwd, email, create_time) values('$username', '$pwd', '$email', now())";
    return mysql_query($cmd);
  }   
  
}

?>    
    
    
