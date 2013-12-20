

<?php

  const FILE_SAVE_PATH = "/home/xias/share/phpuploadfile/" ;
  const MAX_FILE_SIZE = 1000000;
  $debugmode = true;

  function debug() {
    if ($debugmode == false)
      return;
    if (isset($_GET))
      print_r($_GET);
    if (isset($_POST))
      print_r($_POST);
    if (isset($_FILES))
      print_r($_FILES);
  }

  debug();
  
  /* download */
  if ($_SERVER["REQUEST_METHOD"] == "GET") {
    if (file_exists(FILE_SAVE_PATH . $_GET["id"])) {
       Header("Content-type: application/octet-stream"); 
       Header("Accept-Ranges: bytes"); 
       Header("Accept-Length:". filesize(FILE_SAVE_PATH . $_GET["id"])); 
       Header("Content-Disposition: attachment; filename=". $_GET["id"]); 
       echo file_get_contents(FILE_SAVE_PATH . $_GET["id"]);
      
    } else {
      echo "file not found <br />";
    }
  } /* upload */
  else if ( $_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES) 
    && $_FILES["file"]["size"] > 0 && $_FILES["file"]["size"] < MAX_FILE_SIZE ) {

    $fmd5 = md5_file($_FILES["file"]["tmp_name"]);
    $filename = FILE_SAVE_PATH . $fmd5;
    if (!file_exists($filename)) {
      move_uploaded_file($_FILES["file"]["tmp_name"],  $filename);
    }
    echo "http://" . $_SERVER["SERVER_ADDR"] . "/rest/file/?id=" .$fmd5;
  }

?>
    
    

