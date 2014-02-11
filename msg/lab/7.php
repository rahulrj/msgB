<?php
$url=$_POST["url"];
$html=file_get_contents($url);
$dom=new DOMDocument();
$dom->loadHTML($html);
$xpath=new DOMXpath($dom);
$anchor=$xpath->query("//a[@href]");
foreach($anchor as $element)
echo $element->nodeValue."<br>";
?>