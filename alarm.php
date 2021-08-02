<?php

pcntl_signal(SIGALRM, function(){

  echo "SIGALRM\n";
  pcntl_alarm(5);
 
});

pcntl_alarm(5);


echo "run ..... \n";

while (1) {
  sleep(1);
  pcntl_signal_dispatch();
}