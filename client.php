<?php
<?php
$fp = stream_socket_client("tcp://192.168.0.42:8000", $errno, $errstr, 30);
if (!$fp) {
    echo "$errstr ($errno)<br />\n";
} else {
  stream_set_blocking($fp,false);
  while (1) {
    $str = fread(STDIN,1024);
    // $len = pack('N',strlen($str));
    // $res = fwrite($fp, $len);
    fwrite($fp, $str);
    echo '====';

      $str = fread($fp, 1024);
      if($str === false || feof($fp)){
        break;
      }
  }
    
    fclose($fp);
}