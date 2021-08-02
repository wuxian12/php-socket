<?php
$receive = [];
$master = [];
$buffers = [];
$socket = stream_socket_server('tcp://0.0.0.0:8000', $errno, $errstr);
stream_set_blocking($socket, 0);
$id = intval($socket);
$master[$id] = $socket;

echo 'waiting client .....\n';

function ev_accept($socket, $flag, $base){
  global $receive;
  global $master;
  global $buffers;

  $conncetion = stream_socket_accept($socket);
  stream_set_blocking($conncetion, 0);
  $id = intval($conncetion);
  echo 'new client '.$id.'\n';

  $event = event_new();
  event_base_set($event, EV_READ | EV_PERSIST, 'ev_read', $id);
  event_base_set($event,$base);
  event_add($event);

  $master[$id] = $conncetion;
  $receive[$id] = '';
  $buffers[$id] = $event;
}

function ev_read($buffer, $flag, $id){
  global $receive;
  global $master;
  global $buffers;

  while (1) {
    $read = @fread($buffer, 1024);
    if($read === '' || $read === false){
      break;
    }
    $pos = strpos($read, '\n');
    if($pos === false){
      $receive[$id] .= $read;
    }else{
      $receive[$id] .= trim(substr($read, 0, $pos + 1));
      $read = substr($read, $pos + 1);
      switch ($receive['$id']) {
        case 'quit':
          echo 'client close conn\n';
          
          unset($master[$id],$buffers[$id]);
          break;
        default:
          echo $receive[$id];
          break;
      }
      $receive[$id] = '';
    }
  }
}

$base = event_base_new();
$event = event_new();
event_set($event, $socket, EV_READ | EV_PERSIST, 'ev_accept',$base);
event_base_set($event, $base);
event_add($event);

echo 'start run ...\n';

event_base_loop($base);
echo 'this code will not be executed \n';

