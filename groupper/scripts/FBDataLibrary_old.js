
		document.write("<script src= \"scripts/json2.js\" type= \"text/javascript\" > </script>");
		
		
		document.write("<script src=\"http://code.jquery.com/jquery-latest.js\"></script>");
	    document.write("<script type=\"text/javascript\" src=\"https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.7/jquery-ui.min.js\"></script>");
      
        //document.write("<div id=\"fb-root\"></div>");
           
            var userid;
            var userInfo;
            var userName;
            var userprofileurl;
            var notificationMsg;

    		window.fbAsyncInit = function() 
     		{
            	FB.init({ appId: '442535435766762',
                    status: true,
                    cookie: true,
                    xfbml: true,
                    oauth: true
           		});
           		
           		 function updateButton(response) {
                    button       =   document.getElementById('fb-auth');
                    userInfo     =   document.getElementById('user-info');
					var activelogout =   document.getElementById('activelogout');
                    if (response.authResponse) {
                        //user is already logged in and connected
                        FB.api('/me', function(info) {
                            login(response, info);
                        });
						
                        activelogout.onclick = function() {
                        	
                            FB.logout(function(response) {
                                logout(response);
                                 //window.location.reload(true); 
                                updateButton(response);
                                
                            });
                        };
                        
                    } else {
                        //user is not connected to your app or logged out
                       button.innerHTML = 'Facebook Login';
                        button.onclick = function() {
                           
                            FB.login(function(response) {
                                if (response.authResponse) {
									FB.api('/me', function(info) {
                                        login(response, info);
                                    });
                                } else {
                                    //user cancelled login or did not grant authorization
                                    //showLoader(false);
                                }
                            }, {scope:'email, user_birthday, friends_birthday, user_hometown, read_mailbox, friends_work_history, user_work_history, user_location, friends_location, friends_status, friends_activities, friends_education_history, user_groups, friends_groups, friends_about_me, user_about_me'});
							
                        }
                    }
                }
				
                  // run once with current status and whenever the status changes
                FB.getLoginStatus(updateButton);
                //FB.Event.subscribe('auth.statusChange', updateButton);

            };
            (function() {
                var e = document.createElement('script'); e.async = true;
                e.src = document.location.protocol + '//connect.facebook.net/en_US/all.js';
                //document.getElementById('fb-root').appendChild(e);
            }());
           
           
            function login(response, info){
           
                    var userMetaData=new Object();
                    var userMetaObj;
                    var accessToken = response.authResponse.accessToken;
                    userInfo = document.getElementById('user-info');
					userprofileurl=info.profile_url;
					userName=info.username;
					userid = info.id;
                    if(info.name!=null)
                    	profilename=info.name;
                    else
						profilename=info.id;
						
                    userInfo.innerHTML = '<img src="https://graph.facebook.com/' + info.id +'/picture" width="100" height="100">  &nbsp;&nbsp;&nbsp;&nbsp;' + info.name+ '<br />';
                 
                                //users meta data processing
                    userMetaData.userid =  userid;
                    userMetaData.username =  userName;
                   	 	
          			metaJS = JSON.stringify(userMetaData, null, '\t');
          			
          			notification_information(userid);
                    document.getElementById('login').style.display = 'none';
					
                    document.getElementById('content').style.display = "block";
                    document.getElementById('other').style.display = "block";
                    
                    var request = CreateXMLHttpRequest();
                    
                    var sentData = "userId=" + userid;  
                	request.open("POST", "http://A4360.research.ltu.se/groupper/php/facebook/fb_indexing.php", true);
                         
                	request.onreadystatechange = function() 
                	{
                       if (request.readyState == 4) 
                       { 
                            if (request.status == 200) 
                            {                                               
                                  retMessage= request.responseText;
                                  alert(retMessage);                             
                            }                                               
                        }
                	}
                	request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
               	 	request.send(sentData);         
            }

            function logout(response)
            {
                userInfo.innerHTML = "";
                button.innerHTML = 'Facebook Login';
                document.getElementById('login').style.display = "block";
               	document.getElementById('content').style.display = "none";
                // showLoader(false);
            }

			// function is executed when var request state changes
			function sendData()
			{
				// if request object received response
			
				if(request.readyState == 4)
				{
					// parser.php response
					var JSONtext = request.responseText;
					alert('Your contacts list is updated!!! Thank you');	
					
				}
				
			}
			
			function showLoader(status){
      		if (status)
          		document.getElementById('loader').style.display = 'block';
      		else
          		document.getElementById('loader').style.display = 'none';
  			}
			
			//Create XHTTP Ajax request
			function CreateXMLHttpRequest() {
			if (typeof XMLHttpRequest != "undefined"){
            //All modern browsers (IE7+, Firefox, Chrome, Safari, and Opera) uses XMLHttpRequest object
				return new XMLHttpRequest();
			}
			else if (typeof ActiveXObject != "undefined") {
				// Internet Explorer (IE5 and IE6) uses an ActiveX object
				return new ActiveXObject("Microsoft.XMLHTTP");
			}
			else {
				throw new Error("XMLHttpRequestnot supported");
			}
		}
		
			//for writting to json file for all data collection
			
			function runAjaxFB(InteractionData, JSONstring, userid, metaDataJS)
			{
				
				var data = "profileInfo=" + JSONstring + "&interactionData=" + InteractionData +  "&userId=" + userid ;
			
				request = CreateXMLHttpRequest();
				//request.onreadystatechange = sendData;
				request.onreadystatechange = function()
				{
					if(request.readyState == 4)
					{
						if (request.status == 200) 
						{ 	
							// parser.php response
							showLoader(false);
							var JSONtext = request.responseText;
							//setTimeout(function() {notification_information(userid);},1000);//update notification information after profile creation 
							//alert('Your contacts list is updated!!! Thank you');
							runAggregator(userid);	
						}
					 }
				}
				request.open("POST", "http://A4360.research.ltu.se/groupper/php/facebook/fb_write.php", true);
				request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
				request.send(data);
			}	
				
			function runAggregator(userid)
	        {
				
				var data = "userId=" + userid ;
				request = CreateXMLHttpRequest();
			
				request.onreadystatechange = function()
				{
					if(request.readyState == 4)
					{
						if (request.status == 200) 
						{ 	
							// parser.php response
							var JSONtext = request.responseText;
							//alert("Your Social Data is generated!!!");
							//window.location.reload(true);	
						}
					 }
				}
				request.open("POST", "http://A4360.research.ltu.se/groupper/php/aggregator/dataag_new.php", true);
				request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
			
				request.send(data);
	        }
	        
	        	//file deletion
		function fb_delete_file()
		{
				var notificationMsg;
				//alert(userid);
				var data = "userId=" + userid;
				request = CreateXMLHttpRequest();
				//request.onreadystatechange = sendData;
				request.onreadystatechange = function()
				{
					if(request.readyState == 4)
					{
						if (request.status == 200) 
						{ 	
							// parser.php response
							notificationMsg = request.responseText;
							alert(notificationMsg);
							
						}
					 }
				}
				request.open("POST", "http://A4360.research.ltu.se/groupper/php/facebook/delete_facebook.php", true);
				request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
				request.send(data);
		}
		
		
		
		function gp_delete_file()
		{
				var notificationMsg;
				//alert(userid);
				var data = "userId=" + userid;
				request = CreateXMLHttpRequest();
				//request.onreadystatechange = sendData;
				request.onreadystatechange = function()
				{
					if(request.readyState == 4)
					{
						if (request.status == 200) 
						{ 	
							// parser.php response
							notificationMsg = request.responseText;
							alert(notificationMsg);
							
						}
					 }
				}
				request.open("POST", "http://A4360.research.ltu.se/groupper/php/googleplus/delete_googleplus.php", true);
				request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
				request.send(data);
		}
		
		
		//file show
		function fb_show_file(userIdd)
		{	
	          		  var data = "userId=" + userIdd;
					  var request = CreateXMLHttpRequest();
				
					  request.onreadystatechange = function()
					  {
							if(request.readyState == 4)
							{
								if (request.status == 200) 
								{ 	
									var notificationMsg = request.responseText;
									
									if(notificationMsg=="noFile")
										alert("Please generate social data file!!!");
									else
										window.open(notificationMsg,'Download');
									
								}
						 	}
						}
						request.open("POST", "http://A4360.research.ltu.se/groupper/php/facebook/show_facebook.php", true);
						request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
						request.send(data);
		
		}
		
		
		function gp_show_file(userIdd)
		{	
	          		  var data = "userId=" + userIdd;
					  var request = CreateXMLHttpRequest();
				
					  request.onreadystatechange = function()
					  {
							if(request.readyState == 4)
							{
								if (request.status == 200) 
								{ 	
									var notificationMsg = request.responseText;
									
									if(notificationMsg=="noFile")
										alert("Please generate social data file!!!");
									else
										window.open(notificationMsg,'Download');
									
								}
						 	}
						}
						request.open("POST", "http://A4360.research.ltu.se/groupper/php/googleplus/show_googleplus.php", true);
						request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
						request.send(data);		
		}	
			
			function ParseBirthDate(strBirthdate)
			{
				var birthdate = "";
				
				if(strBirthdate != undefined && strBirthdate!== "" && strBirthdate!==null && strBirthdate.length!=0)
				{
					var splitBirthDate = strBirthdate.split('/');
					var day, month;
					
					if(splitBirthDate[1]>9)
						day = splitBirthDate[1];
					else 
						day = splitBirthDate[1][1];
					
					if(splitBirthDate[0]>9)
						month = splitBirthDate[0];
					else 
						month = splitBirthDate[0][1];
						
					if(strBirthdate.length>6)
					{
						birthdate = {
							"day": day,
							"month": month,
							"year": splitBirthDate[2]
						};
					}
					else 
					{
						birthdate = {
							"day": day,
							"month": month
						};
					}
					return birthdate;
				}
				else return ;
			}
			
			function parseEmployer(strWork)
			{
				var interestkey=[];var k=0; var str='""';
				if(strWork!==undefined && strWork!==null && strWork.length!=0){
					for(var i=0;i<strWork.length;i++)
					{
						//if(k==0){
							interestkey[i]=strWork[i].employer.name;
					
					}
					return interestkey;
				}
				else return ;	
			}
			
			
			
		function parseLocation(strLocation)
		{
				var location = "";
				if(strLocation!==undefined && strLocation!==null && strLocation!== "" && strLocation.length!=0){
					location = strLocation.name;
					return location;
				}
				else return ;	
		}
			
			//find a place in the array
		function find_arrayelement(arr, obj)
		{
			var retval;
			for(var i=0; i<arr.length; i++)
			{
        			if (arr[i] === obj)
        			{ 
        				retval= i;
        				break;
        			}
        			else retval= null;
        	}
   			return retval;

		}
		//create profile & interaction files if data found 
			function facebook_lastupdate()
			{
			//lastUpdateTime is either last facebook profile data creation time by this app or from the start of users facebook time
				var data="username="+userName;
				request = CreateXMLHttpRequest();
				//request.onreadystatechange = sendData;
				request.onreadystatechange = function()
				{
					if(request.readyState == 4)
					{
						if (request.status == 200) 
						{ 	
							 var lastUpdateTime = request.responseText;
							 fqlQuery_json(lastUpdateTime);
							 
				 		}
							
					 }
				}
				request.open("POST", "http://A4360.research.ltu.se/groupper/php/facebook/fb_lastupdate_time.php", true);
				request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
				request.send(data);
		}	
		
		function fqlQuery_json(lastUpdateTime){
		
			showLoader(true);
			FB.api('/me', function(response) {
				
                var noFriends=0;
				var fFrequency;
				var tFrequency;
				var tmpThreadId=0;
				var msgCount=0, sent=0, received=0, n=0;
				var fromFrequency=[];
				var toFrequency=[];
				var sender=[];
				var connection=[];
				
				
				//create message interaction
				//FB.api({method: "fql.query", query: "SELECT..."},callback) 
				//var query1 =  FB.Data.query('select thread_id, message_id, created_time, viewer_id, author_id from message where created_time>1222819200 and thread_id in (select thread_id from thread where viewer_id=me() and folder_id=0)', response.id);
				var query1 =  FB.Data.query('select thread_id, message_id, created_time, viewer_id, author_id from message where created_time>' + lastUpdateTime +'  and thread_id in (select thread_id from thread where viewer_id=me() and folder_id=0)', response.id);
				//var query1 =  FB.Data.query('select thread_id, message_id, created_time, viewer_id, author_id from message where thread_id in (select thread_id from thread where viewer_id=me() and folder_id=0)', response.id);
				
						query1.wait(function(rows) {
							//if new data is found in messagebox
						if(rows[0])
						{	
								//assign first row of query resulset as temporary thread
								tmpThreadId=rows[0].thread_id;
							
							for(var i=0;i<rows.length;i++)
							{
								//compare temporary thread id with the each rows of the query resultset
								if(tmpThreadId==rows[i].thread_id )
								{
									//if apps (current) user sent message
									if(rows[i].author_id ==rows[i].viewer_id)
									{
										sent++;
									}
									else if(rows[i].author_id !==rows[i].viewer_id)//if user receives message
									{
										received++;
										sender[msgCount]=rows[i].author_id;
										fromFrequency[msgCount]=sent;
										toFrequency[msgCount]=received;
										
									}
								}
								
								else //for multiple message send and receive
									//if different thread id found
								{
										// if there is no received message or if apps user sent message only
										if(sent!=0 && received==0)
											sender[msgCount]=rows[i].viewer_id;
									
										//inserts sent frequency and receive frequency into array
										fromFrequency[msgCount]=sent;
										
										toFrequency[msgCount]=received;
										
										//since thread id different
										sent=1;
										received=1;
										msgCount++;
										n++;
										tmpThreadId=rows[i++].thread_id;
									
								}
								
						  	}
						 }	
					//user basic profile info retrive...start
					
						var query =  FB.Data.query('select uid, username, name, email, profile_url, education, music,interests,birthday_date, hometown_location,  movies, activities, tv, political, current_location, books, games, contact_email, work, birthday_date, pic_small from  user where uid in (select uid2 from friend where uid1 = {0})', response.id);
				
						query.wait(function(rows) {
						var JSONObject=new Object();
						var JSONObjectEvaluation=new Object();
						var userMetaData=new Object();
						var user = response.username;
						
						
						JSONObject.userid = response.id;
						JSONObject.userprofilename=response.name;
						JSONObject.username=response.username;
						JSONObject.profileurl=userprofileurl;
						JSONObject.usereducation=response.education;
						
						JSONObject.userwork=response.work;
						
						//var userWork=parseWork(response.work);
						
						//var userEducation=parseEducation(response.education);
						
						var metaData=response.name +','+ response.birthday +','+ hometown;
						
						var fobj = [];
						var fobjEvaluation = [];
						var metaJS;
						var hometown = "";
						var hometowndemo = response.hometown_location;
						if(hometowndemo!=null )
							hometown = hometowndemo;
							
						// user meta data
						userMetaObj={
                        	"name": response.name,
                        	"birth_date": response.birthday,
                        	"city": hometown
                        };
                                //users meta data processing
                    userMetaData.username =  response.username;
                   
                    userMetaData.usermetadata =  userMetaObj;
          			metaJS = JSON.stringify(userMetaData, null, '\t');
          			
						
						var cemail="testing@ltu.se"
									
						for(var i=0;i<rows.length;i++)

						{		
								
								var context = '';
								var country;
								var hometown = [];
								var	totalInteract=0;
								var demousername=rows[i].username;
								var demouname=rows[i].uid;
								if(demousername!==null && demousername.length!==0)
									cemail=rows[i].username+'@facebook.com';
								else
									cemail=rows[i].uid+'@facebook.com';
									
								var  edulist='';
								if(rows[i].education!='' && rows[i].education!=null && rows[i].education!=undefined)
								for(var j=0;j<rows[i].education.length;j++)
					            { 
					            
									if(j==0)
										edulist=rows[i].education[j].school.name;
									else
										edulist=edulist +','+ rows[i].education[j].school.name;
								}	
								
								if(edulist!='')
									context=edulist;
									
								var iWork= '';
								var interestkey='';
								var  employerlist='';								
								if(rows[i].work!='' && rows[i].work!=null && rows[i].work!=undefined)
								
								for(var j=0;j<rows[i].work.length;j++)
					            { 
					            	if(j==0)
										employerlist=rows[i].work[j].employer.name;
									else
										employerlist=employerlist +','+ rows[i].work[j].employer.name;
								}	
								
								if(employerlist!='')
									context=context+','+ employerlist;
								
								
								//alert(rows[i].work);
								
								var iLocation = parseLocation(rows[i].current_location);
								if(iLocation!='' && iLocation!=null && iLocation!=undefined)
									context = context+','+ iLocation;
									
								if(rows[i].activities!=''||rows[i].activities.length!=0)
									context=context+','+rows[i].activities.replace(/"/gi," ");
								
								if(rows[i].books!=''||rows[i].books.length!=0)
									context=context+','+rows[i].books.replace(/"/gi," ");
								
								if(rows[i].tv!=''||rows[i].tv.length!=0)
									context=context+','+rows[i].tv.replace(/"/gi," ");
								
								if(rows[i].music!=''||rows[i].music.length!=0)
									context=context+','+rows[i].music.replace(/"/gi," ");
								
								if(rows[i].movies!=''||rows[i].movies.length!=0)
									context = context + ',' + rows[i].movies.replace(/"/gi," ");
									
							    context = context.replace(/"/gi," ");
							  
								fobj[i]=
								{
									"Friendsname":rows[i].name,
									"userid":(rows[i].uid).toString(),
									"username":rows[i].username,
									"birthdate":ParseBirthDate(rows[i].birthday_date),
									//"birthday_date":rows[i].birthday_date,
									"email":cemail,
									"profileurl":rows[i].profile_url,
									//"education": iEdu,
									//"work": rows[i].work,
									//"music":rows[i].music,
									"movies":rows[i].movies.toString().replace(/"/gi," "),
									//"activities":rows[i].activities,
									//"books":rows[i].books,
									//"games":rows[i].games,
									//"tv":rows[i].tv,
									"location": iLocation,
									
									//"hometown": hometown,
									//"political":rows[i].political,
									//"contacemail":rows[i].contact_email,
									"interests":rows[i].interests,
									"picture":rows[i].pic_small,
									"contextkey":context
								};
								
								JSONObject.friends=fobj;
															
								// for calculating interaction
								var retval=find_arrayelement(sender, rows[i].uid);
								if (retval!=null)
								{
									emailAdd=cemail;
									fFrequency=fromFrequency[retval];
									tFrequency=toFrequency[retval];
									
								}
								else 
								{
									//cemail='no';
									emailAdd=cemail;
									fFrequency=0;
									tFrequency=0;
								}
								
								fobjEvaluation[i]=
								{
									"email":emailAdd,
									"fromFrequency": fFrequency,
									"toFrequency": tFrequency
								};
								
								JSONObjectEvaluation.friends=fobjEvaluation;
															
						}						
						
						//for all data collection
						var JSONstring = JSON.stringify(JSONObject, null, '\t');
						var JSONstringEvaluation = JSON.stringify(JSONObjectEvaluation, null, '\t');
				
						runAjaxFB(encodeURIComponent(JSONstringEvaluation), encodeURIComponent(JSONstring),response.id, metaJS);	
							
					});
			});
		});
	}
