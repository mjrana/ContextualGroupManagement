<?php
header("Access-Control-Allow-Origin:*");

ini_set('memory_limit', '2048M');

$unrankedList = $_POST['unrankedList'];
$unrankedList = json_decode($unrankedList, true);

$userId = $_POST['userId'];
$socialNetwork = $_POST['socialnetwork'];

$indexFileContent = file_get_contents("../../jsonfiles/index.json");

$indexFileContent = json_decode($indexFileContent, true);
                
//identifying number of entry in index file
$indexContentLength = count($indexFileContent['index']);
                                
for($j=0; $j<$indexContentLength; $j++)    
{
      if($indexFileContent['index'][$j]['asguser'] == $userId)  
      {
         	 $strengthFileDirectory = $indexFileContent['index'][$j]['data']['socialDataPaths'][$socialNetwork]."/".$userId.'.strg';
			 break;
	  }
}

//read data from strength file
$strengthFileData = StrengthFileData($strengthFileDirectory);
//// nHfmLVp97O    746411380

// rank friendlist based on strength
$rankedList = CombinedRankedOrder($strengthFileData, $unrankedList);


//remove duplicate from rankelist
$rankedList = RemoveIndexBasedDuplicate($indexFileContent, $rankedList);
//remove current user if found in his 1st degree search
$rankedList = RemoveUser($rankedList, $userId);

echo json_encode($rankedList);
								

//check duplication based on indexfile
function RemoveIndexBasedDuplicate($indexFileContent, $rankedList)
{
	$tempuserId = array();
	$tempfacebookId = array();
	$copyrankedList = array();
	$templinkedinId = array();
	$tempgoogleplusId = array();
	$copyrankedList = $rankedList;
	$k=0; $l = 0;$m = 0;
	
	for($i = 0; $i < count($copyrankedList); $i++)
    {
    	 	//$tcopyrankedList[$i]['userid'] = $copyrankedList[$i]['userid'];
    	 for($j = 0; $j < count($indexFileContent['index']); $j++)
    	 {
     			 //if any social id of indexfile user mmatches with the rankedlist userid, add other social ids with rankedlist
     		if(($indexFileContent['index'][$j]['data']['socialIDs']['facebookId']== $copyrankedList[$i]["userid"]) || ($indexFileContent['index'][$j]['data']['socialIDs']['linkedinId']== $copyrankedList[$i]["userid"]) || ($indexFileContent['index'][$j]['data']['socialIDs']['googlePlusId']== $copyrankedList[$i]["userid"]))
             {
                     $copyrankedList[$i]['facebook'] = $indexFileContent['index'][$j]['data']['socialIDs']['facebookId'];
				
					 $copyrankedList[$i]['linkedin'] = $indexFileContent['index'][$j]['data']['socialIDs']['linkedinId'];
					
					 $copyrankedList[$i]['googleplus'] = $indexFileContent['index'][$j]['data']['socialIDs']['googlePlusId'];	
            }
		 }
	}
	
	
	
	for($i = 0; $i < count($copyrankedList); $i++)
    {
    	
    	$userExistingFrequency = 1;
		
		if($copyrankedList[$i]['facebook']!='' || $copyrankedList[$i]['linkedin']!='' || $copyrankedList[$i]['googleplus']!='')
		{
    		$strength = 0; //initialize strength for each user
    		//if facebookid of current ith user in ranked list already exist
    		if(in_array($copyrankedList[$i]['facebook'], $tempfacebookId))
			{
				//if duplicate user found in ranked list increase user existing frequency  
				$userExistingFrequency++;
				//search existing userid position in ranked list matching with facebook id
				
				$key = 'facebook';
				$userExistingPosition = ExistingUserPositionSearch($key, $copyrankedList[$i]['facebook'], $copyrankedList);
				
				//add user's facebook social strength with already existing social strength in rankedlist
				$strength = $rankedList[$userExistingPosition]['strength'] + $copyrankedList[$i]['strength'];
				//echo $rankedList[$userExistingPosition]['strength'];
				
			}
			else
				$tempfacebookId[$l] = $copyrankedList[$i]['facebook'];
				
				//if linkedinid of current ith user in ranked list already exist
			if(in_array($copyrankedList[$i]['linkedin'], $templinkedinId))
			{
				//search existing userid position in ranked list matching with linkedin id	
				$key = 'linkedin';
				$userExistingPosition = ExistingUserPositionSearch($key, $copyrankedList[$i]['linkedin'], $copyrankedList);
				
				//add user's linkedin social strength with already existing social strength in rankedlist
				$strength = $strength + $rankedList[$userExistingPosition]['strength'] + $copyrankedList[$i]['strength'];
				$userExistingFrequency++;
			}
			else
				$templinkedinId[$l] = $copyrankedList[$i]['linkedin'];
			
			//if googleplusid of current ith user in ranked list already exist
			if(in_array($copyrankedList[$i]['googleplus'], $tempgoogleplusId)) 
			{
				//search existing userid position in ranked list matching with googleplus id
				$key = 'googleplus';
				$userExistingPosition = ExistingUserPositionSearch($key, $copyrankedList[$i]['googleplus'], $copyrankedList);
				
				//add user's googleplus social strength with already existing social strength in rankedlist
				$strength = $strength + $rankedList[$userExistingPosition]['strength'] + $copyrankedList[$i]['strength'];
				$userExistingFrequency++;
			}	
			else
				$tempgoogleplusId[$l] = $copyrankedList[$i]['googleplus'];
		
			$l++;
			
			//if duplication of any user found remove user from ranked list
			if($userExistingFrequency>1)
			{
					//assign total strength (existing+duplicate) to the existing userid strength
				$rankedList[$userExistingPosition]['strength'] =  $strength;
				unset($rankedList[$i]);
			}
		}
	}
	return array_values($rankedList);	 
}

//funtion that returns strength from strength file
function StrengthFileData($strengthFilePath)
{
          
        $strengthInfo=array();
        $j=0;
        $array = explode("\n", file_get_contents($strengthFilePath)); //get file contents line by line
        if(count($array)==null)
			echo 'file is null';
        for($i=2; $i<count($array)-1;$i++) 
        {
             $tokens = explode("\t", $array[$i]); //tokenize by "\t\t"
             $strengthInfo[$j]['email']= trim($tokens[0]);   
             $strengthInfo[$j]['strength']= trim($tokens[1]);
			
             $j++;  
        }
        return $strengthInfo;
}


//function for ranked friend's order

function CombinedRankedOrder($strengthFileData, $unrankedList)
{
       $rankedArray = array();
       $i=0;
        
       for($k = 0; $k < count($unrankedList); $k++)
       {
            
              if ((in_array_rr($unrankedList[$k]["email"], $strengthFileData)===true))
                 {
                          // search email position
                         $key = 'email';
                         $pos = ExistingUserPositionSearch($key, $unrankedList[$k]["email"], $strengthFileData);
          
                       
							 $rankedArray[$i]['userid'] = $unrankedList[$k]["userid"];
						
						 
                         $rankedArray[$i]['name'] = $unrankedList[$k]["name"];
                         $rankedArray[$i]['email'] = $unrankedList[$k]["email"];
                         $rankedArray[$i]['picture'] = $unrankedList[$k]["picture"];
                         $rankedArray[$i]['location'] = $unrankedList[$k]["location"];
                         $rankedArray[$i]['city'] = $unrankedList[$k]["city"];
                         $rankedArray[$i]['profileurl'] = $unrankedList[$k]["profileurl"];
						 $rankedArray[$i]['connection'] = $unrankedList[$k]["connection"];
                         $rankedArray[$i]['strength'] = $strengthFileData[$pos]["strength"];
						 
               	}
                else 
                {
					     
							 $rankedArray[$i]['userid'] = $unrankedList[$k]["userid"];
						
					     $rankedArray[$i]['name'] = $unrankedList[$k]["name"];
                         $rankedArray[$i]['email'] = $unrankedList[$k]["email"];
                         $rankedArray[$i]['picture'] = $unrankedList[$k]["picture"];
                         $rankedArray[$i]['location'] = $unrankedList[$k]["location"];
                         $rankedArray[$i]['city'] = $unrankedList[$k]["city"];
                         $rankedArray[$i]['profileurl'] = $unrankedList[$k]["profileurl"];
						 $rankedArray[$i]['connection'] = $unrankedList[$k]["connection"];
                         $rankedArray[$i]['strength'] = 0;
                }
                
                $i++;
        }
    	$rankedArray = SortRankedList($rankedArray);
        
    	return $rankedArray;
}


//callback function for usort
function cmp($a, $b)
{
    
    if ($a['strength'] == $b['strength']) {
        return 0;
    }
    return ($a['strength'] > $b['strength']) ? -1 : 1;
}

function SortRankedList($rankedArray)
{
	
	 $directFriends = array();
	 $indirectFriends = array();
	 $sortedFriends= array();
	 $tempStrength = $rankedArray[0]['strength'];
	 for($i=0;$i<count($rankedArray); $i++) 
	 {
        if($rankedArray[$i]['connection'] == "1") 
        {
           $directFriends[$i] = $rankedArray[$i];
        }
		else
			$indirectFriends[$i] = $rankedArray[$i];
    }
	usort($directFriends, "cmp");
	usort($indirectFriends, "cmp");
	$sortedFriends = $directFriends;
	$sortedFriends = array_merge($sortedFriends, $indirectFriends);
	return $sortedFriends;
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

//remove user if found his friend list
function RemoveUser($rankedArray, $userId) {
	
    for($i=0;$i<count($rankedArray); $i++) 
    {
       
        if($userId == $rankedArray[$i]['userid']) {
           unset($rankedArray[$i]);
            break;
        }
    }
	return array_values($rankedArray);
}

//find position of an element from two dimensional array
function ExistingUserPositionSearch($key, $needle,$haystack) {
    for($i=0;$i<count($haystack); $i++) {
       
        if($needle===$haystack[$i][$key]) {
            return $i;
            break;
        }
    }
}


?>