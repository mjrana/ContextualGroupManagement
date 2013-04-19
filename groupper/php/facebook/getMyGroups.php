<?php

header("Access-Control-Allow-Origin:*");

$grouppath = $_POST['path'];

if(file_exists($grouppath))
{
		
	showfile($grouppath);
} else {
	echo "NoGroups";
}


//function for delete file
function showfile($grouppath)
{
	$dh  = opendir($grouppath);
	$i=0;
	while (false !== ($filename = readdir($dh))) {
	    
		if ($filename != "." && $filename != ".." && $i=0){
			$files = $filename;		  
		}else
			if ($filename != "." && $filename != ".."){
			$files = $files.";".$filename;		  
		}
			$i++;	
	}
	echo $files;
}
?>