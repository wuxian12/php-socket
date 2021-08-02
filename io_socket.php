<?php
$socket = stream_socket_server("tcp://0.0.0.0:8000", $errno, $errstr);
if (!$socket) {
  echo "$errstr ($errno)<br />\n";
} else {
  stream_set_blocking($socket, 0);
  $connections = [];
  $read = [];
  $write = null;
  $except = null;
  $buf = '';
  while (1) {
    if($c = @stream_socket_accept($socket, empty($connections) ? -1 :0,$peer)){
      echo $peer.'connected'.PHP_EOL;
      fwrite($c, 'hello'.$peer.PHP_EOL);
      $connections[$peer] = $c;
    }
    $read = $connections;
     
    if (stream_select($read, $write, $except, 5)) {
      foreach ($read as $c) {
        $peer = stream_socket_get_name($c, true);
        if(feof($c)){
          echo 'conncetion closed'.$peer.PHP_EOL;
          fclose($c);
          unset($connections[$peer]);
        }else{
          // $len = fread($c, 4);
          // $contents = fread($c, unpack('N', $len)[1]);
          $contents = '';
          $str = fread($c, 4);
          $buf .= $str;
          if(($index = stripos($buf, 'qu')) !== false){
            $contents = substr($buf, 0,$index);
            $buf = substr($buf, $index + 2);
            echo $peer.':'.trim($contents).PHP_EOL;
            fwrite($c, $contents);
          }
          
          
        }
      }
    }
     
    
  }
  
  fclose($socket);
}