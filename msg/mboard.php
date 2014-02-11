<?php





require_once('settings.php');


if(empty($_REQUEST['a'])) {
    $a='';
} else {
    $a=htmlspecialchars($_REQUEST['a']);
}







if (($a=='addnew' || $a=='reply')) {

    session_start();

   
   
   
   
   
   
   if (empty($_SESSION['checked'])) {
        $_SESSION['checked']='N';
        $_SESSION['secnum']=rand(10000,99999);
        $_SESSION['checksum']=crypt($_SESSION['secnum'],$settings['filter_sum']);
    }
    
    
    
    
    
    if ($_SESSION['checked'] == 'N')
    {
        print_secimg();
    }
    elseif ($_SESSION['checked'] == $settings['filter_sum'])
    {
        $_SESSION['checked'] = 'N';
        $mysecnum=pj_isNumber($_POST['secnumber']);

        if(empty($mysecnum))
        {
            print_secimg(1);
        }

        require('secimg.inc.php');
        $sc=new PJ_SecurityImage($settings['filter_sum']);
        if (!($sc->checkCode($mysecnum,$_SESSION['checksum']))) {
            print_secimg(2);
        }

        $_SESSION['checked']='';

    }
    else
    {
        problem('Internal script error. Wrong session parameters!');
    }

   

}








printTopHTML();

if ($a) {

   

    if ($a=='delete') {
        $num=pj_isNumber($_REQUEST['num'],'Wrong data type for $num');
        $up=pj_isNumber($_REQUEST['up'],'Wrong data type for $num');
        confirmDelete($num,$up);
    }
    if ($a=='confirmdelete') {
        $pass=pj_input($_REQUEST['pass'],'Please enter your admin password!');
        $num=pj_isNumber($_REQUEST['num'],'Wrong data type for $num');
        $up=pj_isNumber($_REQUEST['up'],'Wrong data type for $num');
        doDelete($pass,$num,$up);
    }

    $name=pj_input($_POST['name'],'Please enter your name!');
    $message=pj_input($_POST['message'],'Please write a message!');

    if(!empty($_POST['email']))
    {
        $email=pj_input($_POST['email']);
            if(!preg_match("/([\w\-]+\@[\w\-]+\.[\w\-]+)/",$email))
            {
                problem('Please enter a valid e-mail address!');
            }
       
    }
    else {$email='NO';}

    
    
    
    
    
    
    
    
    
    
    
    
    

    if ($a=='addnew')
    {
        $subject=pj_input($_POST['subject'],'Please write a subject!');
        addNewTopic($name,$email,$subject,$message);
    }
    elseif ($a=='reply')
    {
        $subject=pj_input($_POST['subject'],'Please write a subject!');
        $orig['id']=pj_isNumber($_POST['orig_id'],'Internal script error: Wrong data type for orig_id');
        $orig['name']=pj_input($_POST['orig_name'],'Internal script error: No orig_name');
        $orig['sub']=pj_input($_POST['orig_subject'],'Internal script error: No orig_subject');
        $orig['date']=pj_input($_POST['orig_date'],'Internal script error: No orig_date');
        addNewReply($name,$email,$subject,$message,$orig['id'],$orig['name'],$orig['sub'],$orig['date']);
    }
   
}

?>
<h3 align="center"><?php echo $settings['mboard_title']; ?></h3>

<div align="center"><center>
<table border="0" width="95%"><tr>
<td>


<ul>







<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
   "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<link rel="stylesheet" href="cssrahul.css" type="text/css" media="screen" />
<script type="text/javascript" src="http://jqueryjs.googlecode.com/files/jquery-1.3.2.js"></script>

<script type="text/javascript">
$(document).ready(function(){
    $(".trigger").click(function(){
    	$(".panel").toggle("fast");
		$(this).toggleClass("active");
		return false;
	});
});
</script>


<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.6.4/jquery.min.js"></script>

<!-----SLIDING PANEL HEIGHT ADJUST TO DOCUMENT HEIGHT----->
<script type="text/javascript">
$(document).ready(function() {
	$("#menu").height($(document).height());
});
</script>

<!-----SLIDING PANEL DELAY AND HIDE----->
<script type="text/javascript">
$(document).ready(function() {
    setTimeout( function(){$('#menu').css('left','-130px');},10000); <!-- Change 'left' to 'right' for panel to appear to the right -->
});
</script>


</head>

<body>

<div id="menu">
	<div class="arrow"><</div>
        <nav class="nav">
<a class="trigger" href="#">Recent topics</a>
                   </nav>
</div>


<div class="panel">
	<h3>Sliding Panel</h3>
	
    <?php
include_once 'threads.txt';

?>
<div style="clear:both;"></div>

			</div>
	</div>
<div style="clear:both;"></div>

</div>


</body>
</html>
</ul></td>
</tr></table>
</center></div>

<p align="center"><a name="new"></a><b>Add new topic</b></p>
<div align="center"><center>
<table border="0"><tr>
<td>
<form method=post action="mboard.php" name="form" onSubmit="return mboard_checkFields();">
<p><input type="hidden" name="a" value="addnew"><b>Name:</b><br><input type=text name="name" size=30 maxlength=30><br>
E-mail (optional):<br><input type=text name="email" size=30 maxlength=50><br>
<b>Subject:</b><br><input type=text name="subject" size=30 maxlength=100><br><br>
<b>Message:</b><br><textarea cols=50 rows=9 name="message"></textarea><br>


<?php
if ($settings['smileys']) {
    echo '
    <p><a href="javascript:openSmiley(\''.$settings['mboard_url'].'/smileys.htm\')">Insert smileys</a>';

    
}
?>
<p><input type=submit value="Add new topic">
</form>
</td>
</tr></table>
</center></div>
<?php
printDownHTML();
exit();



















function filter_bad_words($text) {
global $settings;
$file = 'badwords/'.$settings['filter_lang'].'.php';

    if (file_exists($file))
    {
        include_once($file);
    }
    else
    {
        problem("not found");
    }

    foreach ($settings['badwords'] as $k => $v)
    {
        $text = preg_replace("/\b$k\b/i",$v,$text);
    }

return $text;
} // END filter_bad_words




















function addNewReply($name,$email,$subject,$comments,$orig_id,$orig_name,$orig_subject,$orig_date) {
global $settings;
$date=date ("d/M/Y");

$comments = str_replace("\'","'",$comments);
$comments = str_replace("\&quot;","&quot;",$comments);

$comments = str_replace("\r\n","<br>",$comments);
$comments = str_replace("\n","<br>",$comments);
$comments = str_replace("\r","<br>",$comments);






$comments = stripslashes($comments);
$subject = stripslashes($subject);
$name = stripslashes($name);
$orig_name = stripslashes($orig_name);
$orig_subject = stripslashes($orig_subject);






if ($_REQUEST['nosmileys'] != "Y") {$comments = processsmileys($comments);}
if ($email != "NO") {$mail = "&lt;<a href=\"mailto:$email\">$email</a>&gt;";}
else {$mail=" ";}





if ($settings['filter']) {
$comments = filter_bad_words($comments);
$name = filter_bad_words($name);
$subject = filter_bad_words($subject);
}








$fp = fopen("count.txt","rb") or problem("Can't open the file");
$count=fread($fp,6);
fclose($fp);
$count++;
$fp = fopen("count.txt","wb") or problem("Can't open the count file");

    fputs($fp,$count);
   

fclose($fp);





$threads = file("threads.txt");





for ($i=0;$i<=count($threads);$i++) {
    if(strstr($threads[$i],'<!--o '.$orig_id.'-->'))
        {
        preg_match("/<\!--(.*)-->\s\((.*)\)/",$threads[$i],$matches);
        $number_of_replies=$matches[2];$number_of_replies++;
        $threads[$i] = "<!--o $orig_id--> ($number_of_replies)\n";
        $threads[$i] .= "<!--z $count-->\n";
        $threads[$i] .= "<!--s $count--><ul><li><a href=\"msg/$count.$settings[extension]\">$subject</a> - <b>$name</b> <i>$date</i>\n";
        $threads[$i] .= "<!--o $count--> (0)\n";
        $threads[$i] .= "</li></ul><!--k $count-->\n";
        break;
        }
}

$newthreads=implode('',$threads);

$fp = fopen("threads.txt","wb") or problem("Couldn't open file");

    fputs($fp,$newthreads);
    

fclose($fp);

$other = "in reply to <a href=\"$orig_id.$settings[extension]\">$orig_subject</a> posted by $orig_name on $orig_date";
createNewFile($name,$mail,$subject,$comments,$count,$date,$other,$orig_id);

$oldfile="msg/".$orig_id.".".$settings['extension'];

$filecontent = file($oldfile);

for ($i=0;$i<=count($filecontent);$i++) {
    if(preg_match("/<!-- zacni -->/",$filecontent[$i]))
        {
        $filecontent[$i] = "<!-- zacni -->\n<!--s $count--><li><a href=\"$count.$settings[extension]\">$subject</a> - <b>$name</b> <i>$date</i></li>\n";
        break;
        }
}

$rewritefile=implode('',$filecontent);

$fp = fopen($oldfile,"wb") or problem("Couldn't open file");

    fputs($fp,$rewritefile);
  

fclose($fp);

?>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p align="center"><b>Your message was successfully added!</b></p>
<p align="center"><a href="mboard.php">Click here to continue</a></p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<?php
printDownHTML();
exit();
}















function createNewFile($name,$mail,$subject,$comments,$count,$date,$other="",$up="0") {
global $settings;
$header=implode('',file('header.txt'));
$footer=implode('',file('footer.txt'));
$content='
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
<title>'.$subject.'</title>
<meta content="text/html; charset=windows-1250">
<link href="'.$settings['mboard_url'].'/style.css" type="text/css" rel="stylesheet">
<META HTTP-EQUIV="Expires" CONTENT="-1">
<META HTTP-EQUIV="Pragma" CONTENT="no-cache">
<script language="Javascript" src="'.$settings['mboard_url'].'/javascript.js"><!--
//-->
</script>
<style>

body{

color:#660000;
}
</style>
</head>
<body>
';
$content.=$header;

$content.='
<h3 align="center">'.$settings['mboard_title'].'</h3>

<div align="center"><center>
<table border="0" width="95%"><tr>
<td>

<p align="center"><a href="#new">Post a reply</a> ||
<a href="'.$settings['mboard_url'].'/mboard.php">Back to '.$settings['mboard_title'].'</a></p>
<hr>
<p align="center"><b>'.$subject.'</b></p>

<p><a href="'.$settings['mboard_url'].'/mboard.php?a=delete&num='.$count.'&up='.$up.'"><img
src="'.$settings['mboard_url'].'/images/delete.gif" width="16" height="14" border="0" alt="Delete this post"></a>
Submitted by '.$name.' '.$mail.' on '.$date.' '.$other;

if ($settings['display_IP']==1) {$content .= '<br><font class="ip">'.$_SERVER['REMOTE_ADDR'].'</font>';}

$content .= '</p>

<p><b>Message</b>:</p>

<p>'.$comments.'</p>

<hr>

<p align="center"><b>Replies to this post</b></p>
<ul>
<!-- zacni --><p>No replies yet</p>
</ul>
<hr></td>
</tr></table>
</center></div>

<p align="center"><a name="new"></a><b>Reply to this post</b></p>
<div align="center"><center>
<table border="0"><tr>
<td>
<form method=post action="'.$settings['mboard_url'].'/mboard.php" name="form" onSubmit="return mboard_checkFields();">
<p><input type="hidden" name="a" value="reply"><b>Name:</b><br><input type=text name="name" size=30 maxlength=30><br>
E-mail (optional):<br><input type=text name="email" size=30 maxlength=50><br>
<b>Subject:</b><br><input type=text name="subject" value="Re: '.$subject.'" size=30 maxlength=100><br><br>
<b>Message:</b><br><textarea cols=50 rows=9 name="message"></textarea>
<input type="hidden" name="orig_id" value="'.$count.'">
<input type="hidden" name="orig_name" value="'.$name.'">
<input type="hidden" name="orig_subject" value="'.$subject.'">
<input type="hidden" name="orig_date" value="'.$date.'"><br>

';

if ($settings['smileys']) {
    $content.='
    <p><a href="javascript:openSmiley(\''.$settings['mboard_url'].'/smileys.htm\')">Insert smileys</a>
    
    ';
}

$content.='
<p><input type=submit value="Submit reply">
</form>
</td>
</tr></table>
</center></div>
';





$content.=$footer;
$content.='
</body>
</html>';

$newfile="msg/".$count.".".$settings['extension'];
$fp = fopen($newfile,"wb") or problem("Couldn't create file");
    fputs($fp,$content);
    

fclose($fp);

unset($content);
unset($header);
unset($footer);







if ($settings['notify'] == 1)
    {
    $message = "Hello!

Someone has just posted a new message on your forum! Visit the below URL to view the message:

$settings[mboard_url]/$newfile


";

    mail($settings['admin_email'],'New forum post',$message);
    }



}
















function addNewTopic($name,$email,$subject,$comments) {
global $settings;
$date=date ("d/M/Y");

$comments = str_replace("\'","'",$comments);
$comments = str_replace("\&quot;","&quot;",$comments);
$comments = MakeUrl($comments);
$comments = str_replace("\r\n","<br>",$comments);
$comments = str_replace("\n","<br>",$comments);
$comments = str_replace("\r","<br>",$comments);




$comments = stripslashes($comments);
$subject = stripslashes($subject);
$name = stripslashes($name);




if ($_REQUEST['nosmileys'] != "Y") {$comments = processsmileys($comments);}
if ($email != "NO") {$mail = "&lt;<a href=\"mailto&#58;$email\">$email</a>&gt;";}
else {$mail=" ";}

if ($settings['filter']) {
$comments = filter_bad_words($comments);
$name = filter_bad_words($name);
$subject = filter_bad_words($subject);
}





$fp = fopen("count.txt","rb") or problem("Can't open the file");
$count=fread($fp,6);
fclose($fp);
$count++;
$fp = fopen("count.txt","wb") or problem("Can't open the file");

    fputs($fp,$count);
   

fclose($fp);

$addline = "<!--z $count-->\n";
$addline .= "<!--s $count--><p><li><a href=\"msg/$count.$settings[extension]\">$subject</a> - <b>$name</b> <i>$date</i>\n";
$addline .= "<!--o $count--> (0)\n";
$addline .= "</li><!--k $count-->\n";



$fp = @fopen("threads.txt","rb") or problem("Can't open the file");
$threads = @fread($fp,filesize("threads.txt"));
fclose($fp);




$addline .= $threads;
$fp = fopen("threads.txt","wb") or problem("Couldn't open file");



    fputs($fp,$addline);
   
 
  
fclose($fp);
createNewFile($name,$mail,$subject,$comments,$count,$date);

?>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p align="center"><b>Your message was successfully added!</b></p>
<p align="center"><a href="mboard.php">Click here to continue</a></p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<?php
printDownHTML();
exit();
}















function deleteOld($num,$file) {
global $settings;

    if ($settings['keepoldmsg'] == 0) {unlink($file);}


$keep = 'YES';
$threads = file('threads.txt');

$newthreads='';
foreach ($threads as $mythread) {
    if (strstr($mythread,'<!--z '.$num.'-->')) {$keep = 'NO'; continue;}
    elseif (strstr($mythread,'<!--k '.$num.'-->')) {$keep = 'YES'; continue;}
    elseif ($keep == 'NO') {continue;}
    else {$newthreads.=$mythread;}
}

$fp = fopen("threads.txt","wb") or problem("Couldn't open file");



    fputs($fp,$newthreads);
   

fclose($fp);

}
















function doDelete($pass,$num,$up) {
global $settings;
if ($pass != $settings[apass]) {problem("Wrong password! The entry hasn't been deleted.");}

    if ($settings['keepoldmsg'] == 0)
    {
        unlink("msg/$num.$settings[extension]") or problem("Can't delete this post,
        access denied or post doesn't exist!");
    }

// Delete input from threads.txt
$keep = 'YES';
$threads = file('threads.txt');

$newthreads='';
foreach ($threads as $mythread) {
    if (!empty($up) && strstr($mythread,'<!--o '.$up.'-->'))
    {
        preg_match("/<\!--(.*)-->\s\((.*)\)/",$mythread,$matches);
        $number_of_replies=$matches[2];$number_of_replies--;
        $newthreads.= '<!--o '.$up.'--> ('.$number_of_replies.")\n";
        continue;
    }
    elseif (strstr($mythread,'<!--z '.$num.'-->')) {$keep = 'NO'; continue;}
    elseif (strstr($mythread,'<!--k '.$num.'-->')) {$keep = 'YES'; continue;}
    elseif ($keep == 'NO') {continue;}
    else {$newthreads.=$mythread;}
}

$fp = fopen('threads.txt','wb') or problem("Couldn't open links file");

    fputs($fp,$newthreads);
    
    
    
    

fclose($fp);

// Delete input from upper file if any
$upfile="msg/$up.$settings[extension]";
if(!empty($up) && file_exists($upfile)) {
    $threads = file($upfile);
    $newthreads='';
    foreach ($threads as $mythread) {
        if (strstr($mythread,'<!--s '.$num.'-->')) {continue;}
        else {$newthreads.=$mythread;}
    }
    $fp = fopen($upfile,"wb") or problem("Couldn't open file");
    
        fputs($fp,$newthreads);
        
   
    fclose($fp);
}
?>
<hr>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p align="center"><b>Selected post and all replies to it were successfully removed!</b></p>
<p align="center"><a href="<?php echo($settings[mboard_url]); ?>/mboard.php">Click here to continue</a></p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<?php
printDownHTML();
exit();
}



















function confirmDelete($num,$up) {
global $settings;
?>
<hr>
<p>&nbsp;</p>
<p>&nbsp;</p>
<form action="<?php echo($settings[mboard_url]); ?>/mboard.php" method="POST"><input type="hidden" name="a" value="confirmdelete">
<input type="hidden" name="num" value="<?php echo($num); ?>"><input type="hidden" name="up" value="<?php echo($up); ?>">
<p align="center"><b>Please enter your administration password:</b><br>
<input type="password" name="pass" size="20"></p>
<p align="center"><b>Are you sure you want to delete this post and all replies to it? This action cannot be undone!</b></p>
<p align="center"><input type="submit" value="YES, delete this entry and replies to it"> | <a href="<?php echo($settings[mboard_url]); ?>/mboard.php">NO, I changed my mind</a></p>
</form>
<p>&nbsp;</p>
<p>&nbsp;</p>
<?php
printDownHTML();
exit();
}



































function MakeUrl($strUrl)
{

}







function call()
{
     
}











function processsmileys($text) {
$text = str_replace(':)','<img src="../images/icon_smile.gif" border="0" alt="">',$text);
$text = str_replace(':(','<img src="../images/icon_frown.gif" border="0" alt="">',$text);
$text = str_replace(':D','<img src="../images/icon_biggrin.gif" border="0" alt="">',$text);
$text = str_replace(';)','<img src="../images/icon_wink.gif" border="0" alt="">',$text);
$text = preg_replace("/\:o/i",'<img src="../images/icon_redface.gif" border="0" alt="">',$text);
$text = preg_replace("/\:p/i",'<img src="../images/icon_razz.gif" border="0" alt="">',$text);
$text = str_replace(':cool:','<img src="../images/icon_cool.gif" border="0" alt="">',$text);
$text = str_replace(':rolleyes:','<img src="../images/icon_rolleyes.gif" border="0" alt="">',$text);
$text = str_replace(':mad:','<img src="../images/icon_mad.gif" border="0" alt="">',$text);
$text = str_replace(':eek:','<img src="../images/icon_eek.gif" border="0" alt="">',$text);
$text = str_replace(':clap:','<img src="../images/yelclap.gif" border="0" alt="">',$text);
$text = str_replace(':bonk:','<img src="../images/bonk.gif" border="0" alt="">',$text);
$text = str_replace(':chased:','<img src="../images/chased.gif" border="0" alt="">',$text);
$text = str_replace(':crazy:','<img src="../images/crazy.gif" border="0" alt="">',$text);
$text = str_replace(':cry:','<img src="../images/cry.gif" border="0" alt="">',$text);
$text = str_replace(':curse:','<img src="../images/curse.gif" border="0" alt="">',$text);
$text = str_replace(':err:','<img src="../images/errr.gif" border="0" alt="">',$text);
$text = str_replace(':livid:','<img src="../images/livid.gif" border="0" alt="">',$text);
$text = str_replace(':rotflol:','<img src="../images/rotflol.gif" border="0" alt="">',$text);
$text = str_replace(':love:','<img src="../images/love.gif" border="0" alt="">',$text);
$text = str_replace(':nerd:','<img src="../images/nerd.gif" border="0" alt="">',$text);
$text = str_replace(':nono:','<img src="../images/nono.gif" border="0" alt="">',$text);
$text = str_replace(':smash:','<img src="../images/smash.gif" border="0" alt="">',$text);
$text = str_replace(':thumbsup:','<img src="../images/thumbup.gif" border="0" alt="">',$text);
$text = str_replace(':toast:','<img src="../images/toast.gif" border="0" alt="">',$text);
$text = str_replace(':welcome:','<img src="../images/welcome.gif" border="0" alt="">',$text);
$text = str_replace(':ylsuper:','<img src="../images/ylsuper.gif" border="0" alt="">',$text);
return $text;
}









function problem($myproblem) {
echo '<p>&nbsp;</p>
<p>&nbsp;</p>
<p align="center"><b>Error</b></p>
<p align="center">'.$myproblem.'</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>';
printDownHTML();
exit();
}







function printTopHTML() {

global $settings;
echo '<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
<title>'.$settings['mboard_title'].'</title>
<meta content="text/html; charset=windows-1250">
<link href="style.css" type="text/css" rel="stylesheet">
<script language="Javascript" src="javascript.js" type="text/javascript"><!--
//-->
</script>
</head>
<body>
';
include_once 'header.txt';
}

function printDownHTML() {


}









function pj_input($in,$error=0) {
    $in = trim($in);
    if (strlen($in))
    {
        $in = htmlspecialchars($in);
    }
    elseif ($error)
    {
        problem($error);
    }
    return stripslashes($in);
}









function pj_isNumber($in,$error=0) {
    $in = trim($in);
    if (preg_match("/\D/",$in) || $in=="")
    {
        if ($error)
        {
            problem($error);
        }
        else
        {
            return '0';
        }
    }
    return $in;
}








function print_secimg($message=0) {
global $settings;
printTopHTML();
$_SESSION['checked']=$settings['filter_sum'];
?>
<p>&nbsp;</p>
<p>&nbsp;</p>

<p align="center"><b>Anti-SPAM check</b></p>
<div align="center"><center>
<table border="0"><tr>
<td>
<hr>
<form method=post action="<?php echo $settings['mboard_url']; ?>/mboard.php?<?php echo strip_tags(SID); ?>" method="POST" name="form">
<?php
if ($message == 1) {echo '<p align="center"><font color="#FF0000"><b>Please type in the security number</b></font></p>';}
elseif ($message == 2) {echo '<p align="center"><font color="#FF0000"><b>Wrong security number. Please try again</b></font></p>';}
?>
<p>&nbsp;</p>
<p>This is a security check that prevents automated signups of this forum (SPAM).
Please enter the security number displayed below into the input field and click
the continue button.</p>
<p>&nbsp;</p>
<p>Security number: <b><?php
if ($settings['autosubmit']==1) {
    echo '<img src="print_sec_img.php" width="100" height="20" alt="Security image" border="1">';
} elseif ($settings['autosubmit']==2) {
    echo $_SESSION['secnum'];
}
?></b><br>
Please type in the security number displayed above:
<input type="text" size="7" name="secnumber" maxlength="5"></p>
<p>&nbsp;
<?php
foreach ($_POST as $k=>$v) {
    if ($k == 'secnumber') {continue;}
    echo '<input type="hidden" name="'.htmlspecialchars($k).'" value="'.htmlspecialchars(stripslashes($v)).'">';
}
?>
</p>
<p align="center"><input type="submit" value=" Continue "></p>
<hr>
</form>
</td>
</tr>
</table>

<p>&nbsp;</p>
<p>&nbsp;</p>

<?php
printDownHTML();
exit();
}







?>