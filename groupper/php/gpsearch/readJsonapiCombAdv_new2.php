<?php

//gpsearch without ranking and sorting algorithm

header("Access-Control-Allow-Origin:*");
ini_set('memory_limit','1024M');

$context = strtolower($_POST['context']);

$userId = $_POST['user'];

$token = $_POST['token'];
$profilename = $_POST['profilename'];
$currentSocialNetwork = $_POST['socialnetwork'];
$livedatasource = $_POST['livedatasource'];

$userGraphfile = $userId.'.Graph.json';
$counter=0;
 
$friends_connection_name=array();
 
$friendInteract_a=array();
//$friend_connection=array();
$friendInteract_b=array();
$friends_connection_merge=array();
$friend_connection_all=array();
$friend_connection_2nd=array();

$allSocialNetworkMatches = array();
$linkedinMatches = array();
$facebookMatches = array();
$googlePlusMatches = array();

$firstdegreefriend=array();
$seconddegreefriend=array();

$useradjacent=array();
$directfriend=array();
$me=array();
$noncontextfriend=array();
 
 
$indexFileContent = file_get_contents("../../jsonfiles/index.json");

                $json_a = json_decode($indexFileContent, true);
                
                //identifying number of entry in index file
                $indexContentLength = count($json_a['index']);
                
                //$rankedFilePath = $dataDirectory.$userId.'.Combined.sar';
                                
                for($j=0; $j<$indexContentLength; $j++)    
                {
                
                     if($json_a['index'][$j]['asguser'] == $userId)  
                     {
                     	
                     	if($livedatasource=='all'||$livedatasource=='default')
						{
                            $dataDirectory = $json_a['index'][$j]['data']['socialDataPaths']['linkedin']."/";
								
                          	$linkedinId = $json_a['index'][$j]['data']['socialIDs']['linkedinId'];
							//$rankedFilePath = $dataDirectory.$userId.'.Combined.sar';
							//$rankedLinkedinFilePath = $dataDirectory.$linkedinId.'.Combined.sar';
                       		if(strlen($dataDirectory)>5)
                            {
                                $SecDataDirectory="../../jsonfiles/linkedin/";  
                                $userGraphfile = $userId.'.linkedin.Graph.json';    
                                       
                                //$linkedinMatches = searchInDirectory($dataDirectory,$rankedLinkedinFilePath,$context,$linkedinId,$token,$profilename,$userGraphfile,$SecDataDirectory);
                                $linkedinMatches = searchInDirectory($dataDirectory,$context,$linkedinId,$token,$profilename,$userGraphfile,$SecDataDirectory);
                                                
                                if (count($linkedinMatches) != null)
                                {
                                                                
                                       $allSocialNetworkMatches =  array_merge($allSocialNetworkMatches,$linkedinMatches);
                                                                
                                        visualizationGraph($useradjacent, $directfriend, $firstdegreefriend, $seconddegreefriend, $user, $noncontextfriend, $me, $userGraphfile);
                                  }
								  else{
										 	
											   $graphFileName = "../../graphfiles/".$userGraphfile;
            								   $HANDLEdeletecontent=fopen($graphFileName, "w") or die('cannot open file');
								               
											   fwrite($HANDLEdeletecontent, "[]");
											   fclose($HANDLEdeletecontent);
							      }
                             }

                             $dataDirectory = $json_a['index'][$j]['data']['socialDataPaths']['facebook']."/";
                             $facebookId = $json_a['index'][$j]['data']['socialIDs']['facebookId'];
							 //$rankedFacebookFilePath = $dataDirectory.$facebookId.'.Combined.sar';
                             if(strlen($dataDirectory)>5)
                             {
                               		$SecDataDirectory="../../jsonfiles/facebook/";
                                    $userGraphfile = $userId.'.facebook.Graph.json';
                                                
                               	   $facebookMatches =searchInDirectory($dataDirectory, $context,$facebookId,$token,$profilename,$userGraphfile,$SecDataDirectory);
                                                
                                    if (count($facebookMatches) != null)
                                    {
                                            $allSocialNetworkMatches = array_merge($allSocialNetworkMatches,$facebookMatches);
                                      		visualizationGraph($useradjacent, $directfriend, $firstdegreefriend, $seconddegreefriend, $user, $noncontextfriend, $me, $userGraphfile);
                                	}
									else{
										 	
											   $graphFileName = "../../graphfiles/".$userGraphfile;
            								   $HANDLEdeletecontent=fopen($graphFileName, "w") or die('cannot open file');
								               
											   fwrite($HANDLEdeletecontent, "[]");
											   fclose($HANDLEdeletecontent);
								     }
                               }
                               
                               $dataDirectory = $json_a['index'][$j]['data']['socialDataPaths']['googleplus']."/";
                               $googlePlusId = $json_a['index'][$j]['data']['socialIDs']['googlePlusId'];
                               if(strlen($dataDirectory)>5)
                               {
                                		$SecDataDirectory="../../jsonfiles/googleplus/";
                                        $userGraphfile = $userId.'.googlePlus.Graph.json';
                                		$googlePlusMatches = searchInDirectory($dataDirectory,$context,$googlePlusId,$token,$profilename,$userGraphfile,$SecDataDirectory);
									     if (count($googlePlusMatches) != null)
                                         {
                                             	$allSocialNetworkMatches = array_merge( $allSocialNetworkMatches,$googlePlusMatches);
												
                                                visualizationGraph($useradjacent, $directfriend, $firstdegreefriend, $seconddegreefriend, $user, $noncontextfriend, $me, $userGraphfile);
                                	      }
										 else{
										 	
											   $graphFileName = "../../graphfiles/".$userGraphfile;
            								   $HANDLEdeletecontent=fopen($graphFileName, "w") or die('cannot open file');
								               
											   fwrite($HANDLEdeletecontent, "[]");
											   fclose($HANDLEdeletecontent);
										  }
                                 }
                          	   }else if($livedatasource=='facebook'){
                          	   	
								
								$dataDirectory = $json_a['index'][$j]['data']['socialDataPaths']['facebook']."/";
                                $facebookId = $json_a['index'][$j]['data']['socialIDs']['facebookId'];
							    //$rankedFacebookFilePath = $dataDirectory.$facebookId.'.Combined.sar';
                                if(strlen($dataDirectory)>5)
                                {
                               		$SecDataDirectory="../../jsonfiles/facebook/";
                                    $userGraphfile = $userId.'.facebook.Graph.json';
                                                
                               	   $facebookMatches =searchInDirectory($dataDirectory, $context,$facebookId,$token,$profilename,$userGraphfile,$SecDataDirectory);
                                                
                                    if (count($facebookMatches) != null)
                                    {
                                            $allSocialNetworkMatches = array_merge($allSocialNetworkMatches,$facebookMatches);
                                      		visualizationGraph($useradjacent, $directfriend, $firstdegreefriend, $seconddegreefriend, $user, $noncontextfriend, $me, $userGraphfile);
                                	}
									else{
										 	
											   $graphFileName = "../../graphfiles/".$userGraphfile;
            								   $HANDLEdeletecontent=fopen($graphFileName, "w") or die('cannot open file');
								               
											   fwrite($HANDLEdeletecontent, "[]");
											   fclose($HANDLEdeletecontent);
										  }
                                }
                          	   	
							  }
							   
                          }
                }
				
				$rankedList = rank($currentSocialNetwork, $userId, $allSocialNetworkMatches);
				
                //echo json_encode($allSocialNetworkMatches);
               echo $rankedList;
                
 

function searchInDirectory($dataDirectory,$context,$userId,$token,$profilename,$userGraphfile,$SecDataDirectory)
{                               
	    global $useradjacent;
        global $directfriend;
         
        if($userId!=''&& $token!='')
        {  
           		$i=0;
                $l=0;
                $k=0;
                //echo $profilename;    
            if($handle=opendir($dataDirectory))
            {
                        while(false!==($file=readdir($handle)))
                        {
                                if($file!=="." && $file!=="..")
                                {
                                        //if(strcmp($file, $user)==0)
                                        $fileExt=end(explode(".", $file)); 
                                                        
                                        if($fileExt==="json" ) //checks with extension for text files only
                                        {
                                                $HANDLE = file_get_contents($dataDirectory.$file);
                                                //echo $HANDLE;
                                                if($HANDLE!=null)
                                                        $jsonstr_a= json_decode($HANDLE,true);
                                                
                                                if($jsonstr_a[friends]!=null)
                                                {
                                                        foreach($jsonstr_a[friends] as $f)
                                                        {
                                                                 $friend_connection_all[$l]=$f[userid];
																 $friend_connection_user[$l]=$f[Friendsname];   //////change this line sarwar today
                                                                 $l++;  
                                                        }       
                                                
                                                        foreach($jsonstr_a[friends] as $f)
                                                        {  
                                                                $match_a= $f[contextkey];
                                                                //echo $match_a;
                                                                
                                                                $tokens = explode(",", $match_a);

                                                                foreach ($tokens as $count => $token) 
                                                                {                                                                       
                                                                        $cleantoken=trim($token);
                                                                        $dif= abs(strlen($token)-strlen($context));
                                                                        $cleanmatch=strcmp(strtolower($cleantoken), $context);
                                                                        $match=strcmp(strtolower($token), $context);
                                                                        $patternmatch=strlen(strstr(strtolower($token), $context));
                                                                        
                                                                        if($cleanmatch==0 || $match==0 || ($patternmatch>0 && $dif<4))
                                                                        {
                                                                                //echo $token;  
                                                                                $useradjacent[$k]=$profilename;
                                                                                
                                                                                $directfriend[$k]=$f[Friendsname];
                                                                                
                                                                                $friendInteract_a[$i]["name"]=$f[Friendsname];
                                                                                $friendInteract_a[$i]["username"]=$f[username];
                                                                                if($f[email]!=null)
                                                                                        $friendInteract_a[$i]["email"]=$f[email];
                                                                                else $friendInteract_a[$i]["email"]="N/A";
                                                                                if($f[country]!=null)
                                                                                {
                                                                                        $friendInteract_a[$i]["location"]=$f[country];
                                                                                        $friendInteract_a[$i]["city"]=$f[city];
                                                                                }
                                                                                else
                                                                                {
                                                                                        $friendInteract_a[$i]["location"]="location unavailable";
                                                                                        $friendInteract_a[$i]["city"]="City unavailable";
                                                                                }
                                                                                if($f[picture]!=null)
                                                                                        $friendInteract_a[$i]["picture"]=$f[picture];
                                                                                
                                                                                $friendInteract_a[$i]["userid"]=$f[userid];
                                                                                $friendInteract_a[$i]["profileurl"]=$f[profileurl];
																				$friendInteract_a[$i]["connection"]= "1";
                                                                                
                                                                                $i++;
                                                                                $k++;
                                                                                break;
                                                                                                                                
                                                                        }                                                                                                                               
                                                                
                                                                }                                               
                                                                
                                                        } 
                                                }
                                        }
                                }
                        }
                        closedir($handle);
                }
        
                $friends_connection=rem_duplicate($friendInteract_a); //based on email address
                $friends_connection_a1=first_degreesearch($friend_connection_user,$friend_connection_all, $context, $profilename, $SecDataDirectory);   
                if (count($friends_connection_a1)!=null)
                {
                        $friends_connection=array_merge($friends_connection, $friends_connection_a1);
                }
                else ;
        
                $friends_connection_merge = rem_duplicate($friends_connection);
               
                return $friends_connection_merge; //without ranked
                
      } 
}

        function visualizationGraph($useradjacent, $directfriend, $firstdegreefriend, $seconddegreefriend, $user, $noncontextfriend, $me, $userGraphfile)
        {
                                global $firstdegreefriend;
                                global $seconddegreefriend;
                                
                                global $useradjacent;
                                global $directfriend; 
                                
                                global $user;
                                global $noncontextfriend;
                                global $me;
                                
                for($j=0;$j<count($noncontextfriend);$j++)
                {
                                
                        $noncontextheader[$j] =array('adjacencies'=> $adjacensis= array (
                        $nonheaders = array ('nodeTo' => $noncontextfriend[$j],
                                        'nodeFrom' => $me[$j],
                                        'data' => array ('$color' => '#557EAA')
                        )
                        
                         ),
                        'data' => $inheader = array ('$color' => '#C74243',
                                        '$type' => 'triangle',
                                        '$dim' => 8),
                        'id' => $me[$j],
                 		'name' => $me[$j]
                        
                         
                        );
                }       
                        
                        
                for($j=0;$j<count($useradjacent);$j++)
                {
                        $firstheader[$j] =array('adjacencies'=> $adjacensis= array (
                        $theaders = array ('nodeTo' => $directfriend[$j],
                                        'nodeFrom' => $useradjacent[$j],
                                        'data' => array ('$color' => '#557EAA')
                        )
                
                         ),
                        'data' => $inheader = array ('$color' => '#C74243',
                                        '$type' => 'circle',
                                        '$dim' => 10),
                        'id' => $useradjacent[$j],
                 'name' => $useradjacent[$j]
                        
                         
                        );
                }
                
                for($j=0;$j<count($firstdegreefriend);$j++)
                {
                        
                        //$mainheader[$j] =array(       'adjacensis'=> $adjacensis= array (
                        $mainheader[$j] =       array('adjacencies'=> $adjacensis= array (
                        $fheaders = array ('nodeTo' => $seconddegreefriend[$j],
                                        'nodeFrom' => $firstdegreefriend[$j],
                                        'data' => array ('$color' => '#557EAA')
                        )
                        
                        ) ,
                        'data' => $inheader = array ('$color' => '#5858FA',
                                        '$type' => 'triangle',
                                        '$dim' => 8),
                        'id' => $firstdegreefriend[$j],
                 'name' => $firstdegreefriend[$j]
                        
                         
                        );
                }
                
                if($firstheader!=null && $mainheader!=null)
                        $treeheader = array_merge( (array)$firstheader, (array)$mainheader);
                else if($mainheader==null)
                        $treeheader = $firstheader;
                else if($firstheader==null)
                
                        $treeheader = $mainheader;
                
                $treeheader = array_merge( (array)$treeheader, (array)$noncontextheader);
                
                $treeheader=json_encode($treeheader);
                
                $NAME = "../../graphfiles/".$userGraphfile;
                

                $HANDLE=fopen($NAME, "w") or die('cannot open file');
                if($HANDLE!=null)
                        fwrite($HANDLE, $treeheader);			

                fclose($HANDLE);
                
                $useradjacent="";
                $directfriend="";
                $firstdegreefriend="";
                $seconddegreefriend="";
            
            $noncontextfriend="";
            $me="";
                                
        }


        function first_degreesearch($friend_connection_user, $friends_connection_name, $context, $profilename, $SecDataDirectory){
                
                global $firstdegreefriend;
                global $seconddegreefriend;
                
                global $useradjacent;
                global $directfriend; 
 
                $friend_connection_2nd=array();
                
                $datafolder=$SecDataDirectory;
                $i=0;$k=0;      $p=0;
                
                
                global $me;
                global $noncontextfriend;
                
                
        for($j=0;$j<count($friends_connection_name);$j++)
        {
                if($handle=opendir($datafolder))
                {               
                        while(false!==($file=readdir($handle)))
                    {
                                        
                                if($file!=="." && $file!==".." && $friends_connection_name[$j]!==null)
                                {
                                       
                                        if (strcmp($file,$friends_connection_name[$j])==0)                                 
                                        {
                                                if($handle1=opendir($datafolder.$file))
                                                {               
                                                        while(false!==($file1=readdir($handle1)))
                                                        {
                                                        
                                                                        $fileExt=end(explode(".", $file1)); 
                                                        
                                                                        if($fileExt==="json" ) //checks with extension for text files only
                                                                        {
                                                                                 $HANDLE = file_get_contents($datafolder.$file.'/'.$file1);
                                                                                 if($HANDLE!=null)
                                                                                                $jsonstr_a= json_decode($HANDLE,true);
                                                                
                                                                                  if($jsonstr_a[friends]!=null)
                                                                                  {
                                                                
                                                                                                foreach($jsonstr_a[friends] as $f)
                                                                                                {
                                                                                                        $match_a= $f[contextkey];
                                                                                                        $tokens = explode(",", $match_a);

                                                                                                        foreach ($tokens as $count => $token) 
                                                                                                        {
                                                                                                                        $cleantoken=trim($token);
                                                                                                                        $dif= abs(strlen($token)-strlen($context));
                                                                                                                        $cleanmatch=strcmp(strtolower($cleantoken), $context);
                                                                                                                        $match=strcmp(strtolower($token), $context);
                                                                                                                        $patternmatch=strlen(strstr(strtolower($token), $context));
                                                                        
                                                                                                                        if($cleanmatch==0 || $match==0 || ($patternmatch>0 && $dif<4))
                                                                                                                        {
                                                                                                
                                                                                                                                if(in_array($friends_connection_name[$j], $noncontextfriend) ){
                                                                                                                                                ;
                                                                                                                         }
                                                                                                                         else {
                                                                                                                                        $me[$p]=$profilename;   
                                                                                                                                      $noncontextfriend[$p] = $friend_connection_user[$j];
                                                                                                                                        $p++;
                                                                                                                        }
                								   
												                                                                        $firstdegreefriend[$k]=$friend_connection_user[$j];
                                                                                                                        
                                                                                                                        $seconddegreefriend[$k]=$f[Friendsname];
                                                                                                      
                                                                                                                        $friendInteract_a[$i]["name"]=$f[Friendsname];
                                                                                                                        $friendInteract_a[$i]["username"]=$f[username];
                                                                                                                        if($f[email]!=null)
                                                                                                                                $friendInteract_a[$i]["email"]=$f[email];
                                                                                                                        else $friendInteract_a[$i]["email"]="N/A";
                                                                                                                        if($f[country]!=null)
                                                                                                                        {
                                                                                                                                $friendInteract_a[$i]["location"]=$f[country];
                                                                                                                                $friendInteract_a[$i]["city"]=$f[city];
                                                                                                                        }
                                                                                                                        else
                                                                                                                        {
                                                                                                                                $friendInteract_a[$i]["location"]="location unavailable";
                                                                                                                                $friendInteract_a[$i]["city"]="City unavailable";
                                                                                                                        }
                                                                                                                        if($f[picture]!=null || $f[picture].length!=0)
                                                                                                                                $friendInteract_a[$i]["picture"]=$f[picture];
                                                                                                                        $f[picture]=null;
                                                                                                                        $friendInteract_a[$i]["userid"]=$f[userid];
                                                                                                                        $friendInteract_a[$i]["profileurl"]=$f[profileurl];
																														$friendInteract_a[$i]["connection"]= "2";
                                                                                
                                                                                                                        $i++;
                                                                                                                        $k++;
                                                                                        
                                                                                                                        break;                                          
                                                                                                        }                                                                                                                               
                                                                
                                                                                                }
                                                                        
                                                                                        }
                                                        
                                                                                        if(count($friendInteract_a)!=null)
                                                                                                        $friend_connection_2nd=array_merge($friend_connection_2nd,$friendInteract_a);
                                                                                        else ;
                                                                
                                                                                }
                                                                                else ; 
                                                                        }
                                                                }
                                                        }
                                                }
                                                else ;
                                        }else ;

                                }
                                closedir($handle);
                }else echo "cannot open";
        } 
   return $friend_connection_2nd;
}

        //removes duplicate
function rem_duplicate($friendInteract_a){

        $copy = array();
        $copy=$friendInteract_a;
        $usedemails=array();
        $usedname=array();
        
        $j=0;$l=0;
        for($k=0; $k<count($copy);$k++)
        {
                if(in_array($copy[$k]["email"], $usedemails) && in_array($copy[$k]["name"], $usedname)){
                                unset($friendInteract_a[$k]);
                }
                        
                else{
                        $usedemails[$j]=$copy[$k]["email"];
                        $usedname[$j]=$copy[$k]["name"];
                        $j++;$l++;
                }
        }
        return $friendInteract_a;
}       

//callback function for usort
function cmp($a, $b)
{
    
    if ($a['connection'] == $b['connection']) {
        return 0;
    }
    return ($a['connection'] > $b['connection']) ? -1 : 1;
}

///renking part added till down

function rank($socialNetwork, $userId, $unrankedList)
{
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
}


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
                         $unrankedList[$i]['strength'] = $strengthFileData[$pos]["strength"];
						 
               	}
                else 
                {
					$unrankedList[$i]['strength'] = 0;
                }
                
                $i++;
        }
    	//$rankedArray = SortRankedList($rankedArray);
    	$rankedArray = SortRankedList($unrankedList);
        
    	return $rankedArray;
}


//callback function for usort
function cmpstrength($a, $b)
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
	usort($directFriends, "cmpstrength");
	usort($indirectFriends, "cmpstrength");
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