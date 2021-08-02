<?php
function sig_handle($signo)
{
  switch ($signo) {
    case SIGTERM:
      exit;
      break;
    case SIGHUP:
      
      break;
    case SIGUSR1:
      echo "caught sigusr1 ... \n";
      break;
    default:
      # code...
      break;
  }
}

echo "installing siganl hander ...\n";

pcntl_signal(SIGTERM, "sig_handle");
pcntl_signal(SIGHUP, "sig_handle");
pcntl_signal(SIGUSR1, "sig_handle");

echo "generating signal sigterm to self ...\n";

posix_kill(posix_getpid(), SIGUSR1);

pcntl_signal_dispatch();
echo "done \n";