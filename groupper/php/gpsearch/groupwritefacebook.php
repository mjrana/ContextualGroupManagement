<?php


$decoded = $_POST['mail_data'];
$userid = $_POST['userid'];


$addr = stripslashes($decoded);



//$addr = stripslashes($addr);

//$NAME= "ftp://msjmo:KHE5Y3ECl014@s11group.com/attachfiles/".$_POST['file'];

$NAME= "../../jsonfiles/facebook/".$userid."/mygroups/".$_POST['file'];
echo $NAME;

$HANDLE=fopen($NAME, "w") or die('cannot open file');
if($HANDLE!=null)
	fwrite($HANDLE, $addr);

fclose($HANDLE);
?>
