<?php
$socket = stream_socket_server("tcp://0.0.0.0:8000", $errno, $errstr);
if (!$socket) {
  echo "$errstr ($errno)<br />\n";
} else {
  while (1) {
    $conn = stream_socket_accept($socket,60000,$remote_address);
    $pid = pcntl_fork();
    if($pid == -1){
      exit('fork fail');
    }else if($pid == 0){
      fclose($socket);
      while (1) {
        var_dump($remote_address);
        $str = fread($conn, 1024);var_dump($str);
        if($str){
          fwrite($conn, $str . "\n");
        }else if(feof($conn)){
          fclose($conn);
          break;
      }else{
          sleep(1);
      }
      }
      exit();
    }else{
      fclose($conn);
    }
    
  }
  
  fclose($socket);
}