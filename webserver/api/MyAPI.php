<?php

require_once 'lib/common_api.php';
require_once 'user.php';

class MyAPI extends CommonAPI
{

    public function __construct($request) {
        parent::__construct($request);
        
    }

    public function user($args) {
        // echo "user " . $this->verb;
        $ret = Array("status"=>405);
        $user = new User();        
        if ($this->verb == 'register') {
            return $user->register($args);
        } else if ($this->verb == 'login') {
            return $user->login($args);
        } else if ($this->verb == 'logout') {
            return $user->logout($args);
        } else {
            return $ret;
        } 
     }
     
     public function file($args) {
     
       // echo "file " . $this->verb;
     
        if ($this->verb == 'upload') {
            
        } else if ($this->verb == 'download') {
            
        } else if ($this->verb == 'delete') {
        
        } else {
        
        } 
     }
     
 }

  try {
      $API = new MyAPI($_REQUEST['request']);
      echo $API->processAPI();
  } catch (Exception $e) {
      echo json_encode(Array('error' => $e->getMessage()));
  }

?>
