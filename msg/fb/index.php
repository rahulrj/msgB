<?php

   require 'config.php';

   $session = $facebook->getSession();


   $me = null;
   if ($session) {
     try {
       $uid = $facebook->getUser();
       $me = $facebook->api('/me');
     } catch (FacebookApiException $e) {
       error_log($e);
       $facebook->setSession(null);
     }
   }

   if ($me) {
      $logoutUrl = $facebook->getLogoutUrl(array(
         'next'=>'http://adgvit.in/msg/fb/logout.php'
      ));
   } else {
      $loginUrl = $facebook->getLoginUrl(array(
         'next'=>'http://adgvit.in/msg/fb/login.php'
      ));
   }

?>
<!doctype html>
<html xmlns:fb="http://www.facebook.com/2008/fbml">
  <head>
    <title>php-sdk</title>
  </head>
  <body>
    <?php if ($me): ?>
    <?php echo "Welcome, ".$me['first_name']. ".<br />"; ?>
    <a href="<?php echo $logoutUrl; ?>">
      <img src="http://static.ak.fbcdn.net/rsrc.php/z2Y31/hash/cxrz4k7j.gif">
    </a>
    <?php else: ?>
      <a href="<?php echo $loginUrl; ?>">
        <img src="http://static.ak.fbcdn.net/rsrc.php/zB6N8/hash/4li2k73z.gif">
      </a>
    <?php endif ?>
  </body>
</html>
