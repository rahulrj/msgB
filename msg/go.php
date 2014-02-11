<?php


preg_match("/^url=(.*)/",$_SERVER['QUERY_STRING'],$matches);
Header("Location: $matches[1]");
exit();
?>