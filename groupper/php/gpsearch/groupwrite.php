<?php


$decoded = $_POST['mail_data'];
$userid = $_POST['userid'];


$addr = stripslashes($decoded);

$NAME= "../../jsonfiles/linkedin/".$userid."/mygroups/".$_POST['file'];
echo $NAME;

$HANDLE=fopen($NAME, "w") or die('cannot open file');
if($HANDLE!=null)
	fwrite($HANDLE, $addr);

fclose($HANDLE);
?>
