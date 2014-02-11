<?php
//
// uses the PHP SDK. Download from https://github.com/facebook/php-sdk
include("sdk/src/facebook.php");
 
//
// from the facebook app page
define('YOUR_APP_ID', '463436413732814');
define('YOUR_APP_SECRET', '4e4a5d08ff369107d68491d83dff66f5');
 
 $userId=0;
//
// new facebook object to interact with facebook
$facebook = new Facebook(array(
 'appId' => YOUR_APP_ID,
 'secret' => YOUR_APP_SECRET,
));
//
// if user is logged in on facebook and already gave permissions
// to your app, get his data:
$userId = $facebook->getUser();
 


if ($userId) {
    
 //
 // already logged? show some data
 $userInfo = $facebook->api('/'.$userId);
 
//echo "<p>YOU ARE: <strong>". $userInfo['name'] ."</strong><br/>";
//echo "Your birth date is: ".$userInfo['birthday']."</p>";

header('Location: http://www.adgvit.in/msg/new_page.html');
 
 
 
} else {
 //
 // use javaascript api to open dialogue and perform
 // the facebook connect process by inserting the fb:login-button
 ?>
 <div id="fb-root"></div>
 <fb:login-button scope='email,user_birthday'></fb:login-button>
 <?php
}
?>


<html>
<head>
 <style>body { text-align:center; font-size: 40px }</style>
</head>
<body>




 <script>
 window.fbAsyncInit = function() {
 FB.init({
 appId : <?=YOUR_APP_ID?>,
 status : true,
 cookie : true,
 xfbml : true,
 oauth : true,
 });
 
FB.Event.subscribe('auth.login', function(response) {
 // ------------------------------------------------------
 // This is the callback if everything is ok
 window.location.reload();
 });
 };
 
(function(d){
 var js, id = 'facebook-jssdk'; if (d.getElementById(id)) {return;}
 js = d.createElement('script'); js.id = id; js.async = true;
 js.src = "//connect.facebook.net/en_US/all.js";
 d.getElementsByTagName('head')[0].appendChild(js);
 }(document));
</script>
</body>
</html>