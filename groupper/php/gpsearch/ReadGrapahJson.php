<?php
header("Access-Control-Allow-Origin:*");

$NAME = '../../graphfiles/'.$_POST['file'];


$jsonstr_a=array();


$HANDLE=fopen($NAME, "r") or die('cannot open file');

if($HANDLE!=null)
	$alljson=fread(($HANDLE), filesize($NAME));

$jsonstr_a=json_encode($alljson);

fclose($HANDLE);
echo $alljson;
//echo $alljson;
?>