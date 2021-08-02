<?php
$pid = pcntl_fork();
if($pid == -1){
    exit("fork fail");
}elseif($pid){
    $id = getmypid();   
    echo "Parent process,pid {$id}, child pid {$pid}\n";   
    
    while (1) {
      sleep(3);
    }
}else{

    $id = getmypid();   
    echo "Child process,pid {$id}\n";   
    sleep(2); 
    exit();
}