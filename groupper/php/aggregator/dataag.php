<?php
header("Access-Control-Allow-Origin:*");

ini_set('memory_limit', '2048M');


aggregate_interaction();

function aggregate_interaction()
{
	$rootfolder="../../jsonfiles/";
	
	$dh  = opendir($rootfolder);
	while (false !== ($dicname = readdir($dh))) {
	    
		
		if ($dicname != "." && $dicname != ".." && $dicname != ".DS_Store" && $dicname != "index.json"){
			$files[] = $dicname;
			print_r($files);
			
				$folder = $rootfolder.$dicname;
				aggregatedata($folder);			
		}	
	}
	}
	
	
	function aggregatedata($folder)
	{
	//insert all directories into array
	$directories = glob($folder . '/*' , GLOB_ONLYDIR);
	
	//read all directories
	for($i=0; $i<count($directories);$i++) 
	{
			//open i th directory
		if($handle=opendir($directories[$i]))
		{
			$m=0;
			$comInteract =array();
		
			//read ith directory  for files
			while(false!==($file=readdir($handle)))
			{
				$subdir = str_replace($folder, '', $directories[$i]); //takes only ith directory
				
				$comInteractionFileName=$directories[$i].$subdir.'.Combined.sar';	//path for aggregated interaction file
		
				//check only files
				if($file!=="." && $file!==".." && $file!==".Combined.txt")
				{		
							$k=0;
							//takes only file extensions
							$fileExt=end(explode(".", $file)); 
							
							if($fileExt==="txt" ) //checks with extension for text files only
							{
								$filePath = $directories[$i].'/'.$file; //takes current text file's path
								
						 		//identify current file type
						 		if (strstr($file,'LNInteraction'))
						 		 	$fileType='LN';
						 		else if (strstr($file,'FBInteraction'))
						 		 	$fileType='FB';
						 		else if (strstr($file,'GMInteraction'))
						   			 $fileType='GM';
								
								//if aggregated file exists, update
								if(file_exists($comInteractionFileName))
								{
									//echo $filePath;
									//convert existing aggregated file into array
									$agregateFileData = array_interaction_from_combinedfile($comInteractionFileName); 
									
									//updated interaction file
									$updateInteract = update_array_interaction_from_file($filePath); // retrieve ith text file's content
									
									//update the existing aggregated file array using new update entry
									$comInteract = combine_interaction($agregateFileData, $updateInteract, $fileType);
									//write to file updated aggregated array
									//writetofile($comInteract, $comInteractionFileName);
									
								}
								else // if no aggregated file exists, create aggregated file
								{ 
									$Interact = array_interaction_from_file($filePath); // retrieve ith text file's content
									//combine all ineraction file's interaction into aggregated array
									$comInteract = combine_interaction($comInteract, $Interact, $fileType);
									//write aggregated array to file
									//writetofile($comInteract, $comInteractionFileName);
								}
						  		
								
								
								$m++;
						  }
						
				}
			}
			writetofile($comInteract, $comInteractionFileName);
		}
	}	
}




//function for combining interaction
function combine_interaction($comInteract, $Interact, $fileType)
{
		
	//if $Interact holds first file content of a user folder
	if(count($comInteract)===0)
	{
	
		$pos =0;
		for($j=0;$j<sizeof($Interact);$j++)
		{
			if($fileType=='FB')
				{
					$comInteract[$pos]['email'] = $Interact[$j]['email'];
					$comInteract[$pos]['FBSent'] = $Interact[$j]['sent'];
					$comInteract[$pos]['FBReceive'] = $Interact[$j]['receive'];
					$comInteract[$pos]['LNSent'] = 0;
					$comInteract[$pos]['LNReceive'] = 0;
					$comInteract[$pos]['GMSent'] = 0;
					$comInteract[$pos]['GMReceive'] = 0;
				}
				else if($fileType=='LN')
				{
					$comInteract[$pos]['email'] = str_replace (" ", "", $Interact[$j]['email']);
					$comInteract[$pos]['FBSent'] = 0;
					$comInteract[$pos]['FBReceive'] = 0;
					$comInteract[$pos]['LNSent'] = $Interact[$j]['sent'];
					$comInteract[$pos]['LNReceive'] = $Interact[$j]['receive'];
					$comInteract[$pos]['GMSent'] = 0;
					$comInteract[$pos]['GMReceive'] = 0;
				}
				else if($fileType=='GM')
				{
					$comInteract[$pos]['email'] = $Interact[$j]['email'];
					$comInteract[$pos]['FBSent'] = 0;
					$comInteract[$pos]['FBReceive'] = 0;
					$comInteract[$pos]['LNSent'] = 0;
					$comInteract[$pos]['LNReceive'] = 0;
					$comInteract[$pos]['GMSent'] = $Interact[$j]['sent'];
					$comInteract[$pos]['GMReceive'] = $Interact[$j]['receive'];
				}
				$pos++;	
		}
		
	}
	else // if updated data found
	{
		$t = count($comInteract)+1;	
		for($j=0;$j<sizeof($Interact);$j++)
		{
				//if friends email exists update corresponding entry
			if ((in_array_rr($Interact[$j]['email'], $comInteract)===true))
			{
					// search email position
				$pos = email_position_search($Interact[$j]['email'], $comInteract);
				
				if($fileType=='FB')
				{
					//$comInteract[$pos]['email'] = $Interact[$j]['email'];
					$comInteract[$pos]['FBSent'] = $Interact[$j]['sent'];
					$comInteract[$pos]['FBReceive'] = $Interact[$j]['receive'];
				}
				else if($fileType=='LN')
				{
					//$comInteract[$pos]['email'] = $Interact[$j]['email'];
					$comInteract[$pos]['LNSent'] = $Interact[$j]['sent'];
					$comInteract[$pos]['LNReceive'] = $Interact[$j]['receive'];
				}
				else if($fileType=='GM')
				{
					//$comInteract[$pos]['email'] = $Interact[$j]['email'];
					$comInteract[$pos]['GMSent'] = $Interact[$j]['sent'];
					$comInteract[$pos]['GMReceive'] = $Interact[$j]['receive'];
				}			
			}
			else //if email not exists add email at the end of the file
			{
				if($fileType=='FB')
				{
					$comInteract[$t]['email'] = $Interact[$j]['email'];
					$comInteract[$t]['FBSent'] = $Interact[$j]['sent'];
					$comInteract[$t]['FBReceive'] = $Interact[$j]['receive'];
					$comInteract[$t]['LNSent'] = 0;
					$comInteract[$t]['LNReceive'] = 0;
					$comInteract[$t]['GMSent'] = 0;
					$comInteract[$t]['GMReceive'] = 0;
				}
				else if($fileType=='LN')
				{
					$comInteract[$t]['email'] = str_replace (" ", "", $Interact[$j]['email']);
					$comInteract[$t]['FBSent'] = 0;
					$comInteract[$t]['FBReceive'] = 0;
					$comInteract[$t]['LNSent'] = $Interact[$j]['sent'];
					$comInteract[$t]['LNReceive'] = $Interact[$j]['receive'];
					$comInteract[$t]['GMSent'] = 0;
					$comInteract[$t]['GMReceive'] = 0;
				}
				else if($fileType=='GM')
				{
					$comInteract[$t]['email'] = $Interact[$j]['email'];
					$comInteract[$t]['FBSent'] = 0;
					$comInteract[$t]['FBReceive'] = 0;
					$comInteract[$t]['LNSent'] = 0;
					$comInteract[$t]['LNReceive'] = 0;
					$comInteract[$t]['GMSent'] = $Interact[$j]['sent'];
					$comInteract[$t]['GMReceive'] = $Interact[$j]['receive'];
				}	
				$t++;
			}
		}
	}
	return $comInteract;
}


//function for searching an array element into a two dimensional array
function in_array_rr($needle, $haystack) 
{ 
	$found = false;
    foreach ($haystack as $item) {
    if ($item === $needle) { 
            $found = true; 
            break; 
        } elseif (is_array($item)) {
            $found = in_array_rr($needle, $item); 
            if($found) { 
                break; 
            } 
        }    
    }
    return $found;
}

//function for writting file
function writetofile($interactionInfo, $filename)
{		
	// set the default timezone to use. 
	//date_default_timezone_set('UTC');
	//$updatetime=date(DATE_RFC822);
	
	$updatetime=time();
	
	ob_start();
	print_r( 'Update Time: '.$updatetime);
	echo "\n";
	print_r( 'userid');
	print_r ("\t\t\t\t\t");
	print_r( 'FBSent');
	print_r ("\t");
	print_r ('FBreceive');
	print_r ("\t");
	print_r( 'GMSent');
	print_r ("\t");
	print_r ('GMreceive');
	print_r ("\t");
	print_r( 'LNSent');
	print_r ("\t");
	print_r ('LNreceive');
	print_r ("\n");
	for($j=0;$j<sizeof($interactionInfo);$j++)
	{
	   if($interactionInfo[$j]['email']!=null)
	   {
				print_r( $interactionInfo[$j]['email']);
				print_r ("\t");
				print_r( $interactionInfo[$j]['FBSent']);
				print_r ("\t");
				print_r ($interactionInfo[$j]['FBReceive']);
				print_r ("\t");
				print_r( $interactionInfo[$j]['GMSent']);
				print_r ("\t");
				print_r ($interactionInfo[$j]['GMReceive']);
				print_r ("\t");
				print_r( $interactionInfo[$j]['LNSent']);
				print_r ("\t");
				print_r ($interactionInfo[$j]['LNReceive']);
				
				print_r ("\n");
	   }
	}
	$output = ob_get_clean();
	file_put_contents( $filename,$output );
}

//funtion that returns multi-dimensional array from interaction file
function array_interaction_from_file($userFilePath)
{
	
	$connectionInfo=array();
	$j=0;
	$array = explode("\n", file_get_contents($userFilePath)); //get file contents line by line
	for($i=1; $i<count($array);$i++) 
	{
		
		$tokens = explode("\t\t", $array[$i]); //tokenize by "\t\t"
		
			 $connectionInfo[$j]['email']= trim($tokens[0]);   
			 $connectionInfo[$j]['sent']= trim($tokens[1]); 
		 	 $connectionInfo[$j]['receive']= trim($tokens[2]);
		 	  $j++; 	
		
			
	}
		return $connectionInfo;
}


//funtion that returns updated interaction from interaction file
function update_array_interaction_from_file($userFilePath)
{
	//echo $userFilePath;
	$connectionInfo=array();
	$j=0;
	$array = explode("\n", file_get_contents($userFilePath)); //get file contents line by line
	for($i=1; $i<count($array);$i++) 
	{
		
		$tokens = explode("\t\t", $array[$i]); //tokenize by "\t\t"
		if($tokens[1]!=='0' || $tokens[2]!=='0')
		{
			 
			 $connectionInfo[$j]['email']= trim($tokens[0]);   
			 $connectionInfo[$j]['sent']= trim($tokens[1]); 
		 	 $connectionInfo[$j]['receive']= trim($tokens[2]);
			 
		 	  $j++; 	
		}
			
	}
		return $connectionInfo;
}

//funtion that returns multi-dimensional array from combined interaction file
function array_interaction_from_combinedfile($userFilePath)
{
		//echo $userFilePath;
	$connectionInfo=array();
	$j=0;
	$array = explode("\n", file_get_contents($userFilePath)); //get file contents line by line
	for($i=2; $i<count($array)-1;$i++) 
	{
		
		$tokens = explode("\t", $array[$i]); //tokenize by "\t\t"
		
			 $connectionInfo[$j]['email']= trim($tokens[0]); 
			
			 $connectionInfo[$j]['FBSent']= trim($tokens[1]); 
			 
		 	 $connectionInfo[$j]['FBReceive']= trim($tokens[2]);
			 $connectionInfo[$j]['GMSent']= trim($tokens[3]); 
		 	 $connectionInfo[$j]['GMReceive']= trim($tokens[4]);
			 $connectionInfo[$j]['LNSent']= trim($tokens[5]); 
			 
		 	 $connectionInfo[$j]['LNReceive']= trim($tokens[6]);
		 	  $j++; 		
	}
	
		return $connectionInfo;
}


//find position of an element from two dimensional array
function recursive_array_search($needle,$haystack) {
    foreach($haystack as $key=>$value) {
        $current_key=$key;
        if($needle===$value || (is_array($value) && recursive_array_search($needle,$value) !== false)) {
        	echo $current_key;
            return $current_key;
        }
    }
   // return $current_key;
}

//find position of an element from two dimensional array
function email_position_search($needle,$haystack) {
    for($i=0;$i<=count($haystack); $i++) {
       
        if($needle===$haystack[$i]['email']) {
            return $i;
			break;
        }
    }
   // return $current_key;
}


?>