<?php

   require 'config.php';

   //ovewrites the cookie
   $facebook->setSession(null);

   header('Location: http://adgvit.in/msg/fb/index.php');
?>
