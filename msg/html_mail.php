<?php
   $from ='anthonirajaa@india.com';
   $to = 'rahul.110392@gmail.com';
   $subject = 'From Anthoniraj - Your Participation Mark';
   
   $headers = "From: $from\r\n";   
   $headers .= "MIME-Version: 1.0\r\n";
   $headers.="CC:anshul.jain010@gmail.com\r\n";
   

   $message= "<table border=1>
                 <tr><td>Marks</td></tr>
                 <tr><td>100</td></tr>
              <table>";   

   if(mail($to,$subject,$message,$headers)){
   	  echo "Success";
   }else{
    	echo "Failure";
   }

?>