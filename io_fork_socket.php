<?php
$pids = [];
$MAX_PROCESS = 3;

$pid = pcntl_fork();
if($pid < 0){
  exit('fork fail');
}else if($pid > 0){

}else{
  if(posix_setsid() == -1){
    exit('could not detach from terminal');
  }
  $id = getmygid();
  echo time().'master process, pid '.$id.'\n';

  umask(0);
  $socket = stream_socket_server('tcp://0.0.0.0:8000',$errno,$errstr);

  for ($i = 0; $i < $MAX_PROCESS; $i++) { 
    start_worker_process();
  }

  while (1) {
    foreach ($pids as $pid) {
      if($pid){
        $res = pcntl_waitpid($pid, $status,WNOHANG);
        if($res == -1 || $res > 0){
          echo time().'worker process'.$pid.'exit';
          start_worker_process();
          unset($pids[$pid]);
        }
      }
    }
  }
}

function start_worker_process(){
  $pid = pcntl_fork();
  if($pid){
    $pids[] = $pid;
  }else if($pid < 0){
    exit('fork fail');
  }else{
    while (1) {
      $conn = stream_socket_accept($socket,-1);

    }
  }
}