<?php 
/* connect to inbox */
$hostname = '{imap.gmail.com:993/imap/ssl}INBOX'; /* inbox*/

$username = $_POST['userid'];
$password = $_POST['password'];

$googleplusUserId = $_POST['googleplususerid'];
$datapath = '../../jsonfiles/googleplus/'.$googleplusUserId.'/'.$googleplusUserId.'.GPInteraction.txt';
$profilefilepath = '../../jsonfiles/googleplus/'.$googleplusUserId.'/'.$googleplusUserId.'.GPProfile.json';

$fromconnection = array();
$toConnection = array();
$i=0;$l=0;
$u=0;
$v=0;
/* try to connect */
$inbox = imap_open($hostname,$username,$password) or die('Cannot connect to mail.google.com: ' . imap_last_error());

/* grab emails from inbox*/

$date = date ( "d M Y", strtotime ( "-7 days" ) );
$emails = imap_search ( $inbox, "SINCE \"$date\"");
//$emails = imap_search($inbox,'ALL');
//$emails = imap_search($inbox,"SINCE \"25 august 2012\"", SE_UID);
//"14 May 2012"
//Start inbox operation
/* if emails are returned, cycle through each... from inbox */
if($emails) {
        /* put the newest emails on top */
        rsort($emails);
		$senderName=" ";
		$email=" ";
		$userData = array();
        /* for every email... */
        foreach($emails as $email_number) 
        {
                /* get information specific to this email */
                $overview = imap_fetch_overview($inbox,$email_number,0);        
                $from=$overview[0]->from;
				
				$subject=$overview[0]->subject;
                preg_replace("/</i", " ", $from);
			    preg_replace("/>/i", " ", $from);
			    $keywords = preg_split("/[\s]+/",$from);
			   
			    foreach ($keywords as $k => $word) { 
        			
        			if (!preg_match("/@/i",$word)) { 
            			 $senderName=$senderName." ".$word;
        			}
					else {
						$email=$word;
					}
			    }
			   
			   preg_match('@^(?:<://)?([^>]+)@i',$from, $matches);
				
				//$senderName = $matches[0];
				
                $host = $matches[1];
				//echo $sender[0].'AAA';
				$key = 'email';
                // get last two segments of host name
                preg_match('/[^<]+\.[^>]+$/', $host, $matches);
                $fromconnection[$u] = $matches[0];
				//echo $fromconnection[$u].'AAA';
				
				$maxEntries = sizeof($userData);
				if( ($pos=isMatch($key, $fromconnection[$u], $userData))>$maxEntries)
				{
					$userData[$pos]['contextkey'] = $userData[$pos]['contextkey'].','.GetContextKey($subject);
				}
				else {
					$userData[$i]['Friendsname'] = $senderName;
					$userData[$i]['username'] = $matches[0];
					$userData[$i]['userid'] = $matches[0];
					$userData[$i]['email'] = $matches[0];
					$userData[$i]['picture'] = 'http://a4360.research.ltu.se/groupper/design/unknowface.jpeg';
					$userData[$i]['contextkey'] = GetContextKey($subject);
					
					
					 $i++;
				}
                ///email separation end
               $u++;
			   $senderName=" ";
			   $email=" ";
        }
        $userData1['friends'] =$userData;

		$userProfileInfo = json_encode($userData1);
		
//write profile info to the profile info file
					$HANDLE=fopen($profilefilepath, "w") or die('cannot open file');
					if($HANDLE!=null)
						fwrite($HANDLE, $userProfileInfo);

					fclose($HANDLE);
       
	   // inbox frequencycount start
	    $t=0;
        $filteredfrom= array();
        $usedfrom= array();
        $tmpfrom=$fromconnection[0];
		
        $result = array();

                // remove duplicates from the inbox & calculates frequency of inbox emails
        for($j=1;$j<sizeof($fromconnection);$j++)
        {
                	
                if (in_array($tmpfrom, $usedfrom))
                {
                        $tmpfrom=$fromconnection[$j];
                }
                else
                {
                        $frequency=findDuplicates($fromconnection,$tmpfrom);
                
                        $filteredfrom[$t]['from']=$tmpfrom;
                        
                        $usedfrom[$t]=$tmpfrom;
                        
                        $filteredfrom[$t]['frequency']=$frequency;
						                        $t++;
                        $tmpfrom=$fromconnection[$j];   
                }
        }
		
		   // inbox frequencycount end
        imap_close($inbox);
}
//end inbox operation   

//start sent mail box operation
$hostname1 = '{imap.gmail.com:993/imap/ssl}[Gmail]/Sent Mail'; /* connect sent mail box*/
$outbox = imap_open($hostname1,$username,$password) or die('Cannot connect to mail.google.com: ' . imap_last_error());

/* grab emails from sentmail box*/
//$sentemails = imap_search($outbox,'ALL');
//$sentemails = imap_search($outbox,"SINCE \"25 august 2012\"", SE_UID);
$date = date ( "d M Y", strtotime ( "-7 days" ) );
$sentemails = imap_search ( $outbox, "SINCE \"$date\"");

/* if emails are returned, cycle through each... from inbox */
if($sentemails) {

        /* put the newest emails on top */
        rsort($sentemails);
		$userReceiveData = array();
        /* for every email... */
        foreach($sentemails as $email_number) 
        {
                /* get information specific to this email */
                $overview = imap_fetch_overview($outbox,$email_number,0);       
                $to=$overview[0]->to;
				
				$subject=$overview[0]->subject;
                //$from=$overview[0]->to;
                ///email separation from whole from .....start
               $receiver=preg_split('/(<[^>])/i', $to);
                //$sender=preg_split('\<+', $from);
				if ($receiver[0]!=null)
					$receiverName = $receiver[0];
				else 
					$receiverName = '';
				
                ///email separation start
                preg_match('@^(?:<://)?([^>]+)@i',$to, $matches);
                $host = $matches[1];
				$key = 'email';
                // get last two segments of host name
                
                preg_match('/[^<]+\.[^>]+$/', $host, $matches);
                $toConnection[$v]=$matches[0];
				
				
				//email separation end
                $v++;
        }
   // outbox frequencycount start
        $n=0;
        $filteredTo= array();
        $usedTo= array();
        $tmpTo=$toConnection[0];
        $result = array();
        
        //removes duplicates & calculates frequency of sent emails
        for($j=1;$j<sizeof($toConnection);$j++)
        {
                if (in_array($tmpTo, $usedTo))
                {
                        $tmpTo=$toConnection[$j++];
                }
                else
                {
                        $frequency=findDuplicates($toConnection,$tmpTo);//count number of frequency
                
                        $filteredTo[$n]['to']=$tmpTo;
                        
                        $usedTo[$n]=$tmpTo;
                        
                        $filteredTo[$n]['frequency']=$frequency;
						//echo $filteredTo[$n]['frequency'];
                        
                        $n++;
                        $tmpTo=$toConnection[$j];       
                }
        }
             // outbox frequencycount end      
				//end sent mail box operation
        
        // set the default timezone to use. 
        date_default_timezone_set('UTC');
        $updatetime=date(DATE_RFC822);
        
		
		///combined inbox & outbox frequency start
		
        //compare filteredfrom array (stores inbox email connections) & 
        //filteredTo array (stores outbox email connections)
        //and take common one & stores them in combindConnection array
        $g=0;
		$default = 0;
        for($j=0;$j<sizeof($filteredfrom);$j++)
        {
                $key = 'from';
                $maxEntries= sizeof($filteredTo);

               if(($pos=isMatch($key, $filteredfrom[$j]['from'], $filteredTo))<$maxEntries)//if matches
                {       
                        $combindConnection[$g]['connection']=$filteredfrom[$j]['from'];
                        $combindConnection[$g]['fromfrequency']=$filteredfrom[$j]['frequency'];
                        $combindConnection[$g]['tofrequency']= $filteredTo[$pos]['frequency'];
						
                }
                else
                {
                        $combindConnection[$g]['connection']=$filteredfrom[$j]['from'];
                        $combindConnection[$g]['fromfrequency']=$filteredfrom[$j]['frequency'];
                        $combindConnection[$g]['tofrequency']=$default;
                }
				$g++;
        }
		$len=sizeof($combindConnection);
		
      //stores remaining connections from filteredTo into combindConnection array
       for($j=0;$j<sizeof($filteredTo);$j++)
        {
                $maxEntries=sizeof($combindConnection);
				
				$key = 'connection';
				
				if(($pos=isMatch($key, $filteredTo[$j]['to'], $combindConnection))>= $maxEntries)
                {
                        	
                        $combindConnection[$len]['connection']=$filteredTo[$j]['to'];
                        $combindConnection[$len]['fromfrequency']= $default;
						
                        $combindConnection[$len]['tofrequency']=$filteredTo[$j]['frequency'];
						
						
                        $len++;
                }
                else
                {
                        $combindConnection[$pos]['connection']=$filteredTo[$j]['to'];
						$combindConnection[$pos]['tofrequency']=$filteredTo[$j]['frequency'];
						
                }
				
        }
	///combined inbox & outbox frequency end
	
        writetofile($combindConnection, $datapath, $updatetime);
        
        /* close the connection */
        imap_close($outbox);
        echo 'Frequency Log have been created successfully!!!';
        
} 


//find position of an element from two dimensional array
function isMatch($key, $needle,$haystack) {
    for($i=0;$i<count($haystack); $i++) {
       
        if($needle===$haystack[$i][$key]) {
            return $i;
            break;
        }
		else if($i == count($haystack))
			return $i;
    }
}

//Process subject to context keys
function GetContextKey($subject)
{
		
		
	$contextKeyArray = explode(' ', $subject);
	
	for($j=0;$j<sizeof($contextKeyArray);$j++)
    {
    	if($j == 0)
			$contextkey = $contextKeyArray[$j];
		else {
			$contextkey = $contextkey.','.$contextKeyArray[$j];
		}
	}
	return $contextkey;
}      
//function to find frequency
function findDuplicates($data,$dupval) 
{
        $nb= 0;
        foreach($data as $key => $val)
                if ($val==$dupval) 
                        $nb++;
        return $nb;
}

//function for writting file
function writetofile($combindConnection, $datapath, $updatetime)
{
       
       ob_start();
       print_r( 'Update Time: '.$updatetime);
       echo "\n";
       
               //started from 1 since first position refers to the first user
       for($j=0;$j<sizeof($combindConnection);$j++)
       {
            if($j!=sizeof($combindConnection)-1)
                        {
                           print_r( $combindConnection[$j]['connection']);
               echo "\t\t";
                               if($combindConnection[$j]['fromfrequency']!=0)
                       print_r( $combindConnection[$j]['fromfrequency']);
                               else print_r(0);
               echo "\t\t";
                               if($combindConnection[$j]['tofrequency']!=0)
                       echo $combindConnection[$j]['tofrequency'];
                               else print_r(0);
               echo "\n";
                        }
                        else if($j==sizeof($combindConnection)-1)
                        {
                                print_r( $combindConnection[$j]['connection']);
               echo "\t\t";
                               if($combindConnection[$j]['fromfrequency']!=0)
                       print_r( $combindConnection[$j]['fromfrequency']);
                               else print_r(0);
               echo "\t\t";
                               if($combindConnection[$j]['tofrequency']!=0)
                       echo $combindConnection[$j]['tofrequency'];
                               else print_r(0);
                        }
       }
       $output = ob_get_clean();
       file_put_contents( $datapath,$output );
	   
}


?>
				
