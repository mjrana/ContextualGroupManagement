

//2012-08=06 Stable Version
 
 		document.write("<link media=\"all\" type=\"text/css\" href=\"http://developer.linkedinlabs.com/tutorials/css/jqueryui.css\" rel=\"stylesheet\"/>");
  		document.write("<script type=\"text/javascript\" src=\"http://code.jquery.com/jquery-1.5b1.js\"></script>");
  		document.write("<script type=\"text/javascript\" src=\"https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.7/jquery-ui.min.js\"></script>");
    	document.write("<script type=\"text/javascript\" src=\"scripts/json2.js\"></script>");
    	 
		//app id declaration needs to be placed only once in the whole apps lifecycle.
		//document.write("<script type=\"text/javascript\" src=\"http://platform.linkedin.com/in.js\">"+ "api_key" +":" +"j6wc62yghixw"+ "</script>");
		
		 
function parseEducation(streducation)
{
		var interestkey='';
		//if(streducation!=null && streducation != undefined && streducation != "" && streducation.values!=null && streducation.values != undefined && streducation.values != "" && streducation.values.length!=0){
			if(streducation.values!=null && streducation.values != undefined && streducation.values != "" && streducation.values.length!=0){
			for(var i=0;i<streducation.values.length;i++)
			{
				
				if(i == 0){
					interestkey = streducation.values[i].schoolName;
					//k++;
				}
				else {
					interestkey = interestkey+','+streducation.values[i].schoolName;
					//k++;
				}
			}
			return interestkey;
		}
		else return;	
}



	
function parseWork(strWork)
{
		var interestkey=''; 
		if(strWork.values!==null && strWork.values!== undefined && strWork.values.length!=0)
		{
			for(var i=0;i<strWork.values.length;i++)
			{
				if(i == 0){
					interestkey=strWork.values[i].company.name;
					
				}
				else {
					interestkey=interestkey+','+strWork.values[i].company.name;
					
				}
			}
			return interestkey;
		}
		else return ;	
}
			
			
function ParseBirthDate(birthdate)
{
		var formated_date='0';
		if(birthdate!=null && birthdate!= undefined && birthdate.length!=0)
		{
			if(birthdate.month!=null)
			{
				
				formated_date=formated_date.concat(birthdate.month);
				
				formated_date=formated_date.concat('/');
			}
		
			if(birthdate.day!=null)
			{
				formated_date=formated_date.concat(birthdate.day);
				formated_date=formated_date.concat('/');
			}
		
			if(birthdate.year!=null)
			{
				formated_date=formated_date.concat(birthdate.year);
				
			}
			return formated_date;
		}
		else return ;	
	
}

	//create ajax request
function CreateXMLHttpRequest() 
{
		if (typeof XMLHttpRequest != "undefined")
		{
            //All modern browsers (IE7+, Firefox, Chrome, Safari, and Opera) uses XMLHttpRequest object
				return new XMLHttpRequest();
		}
		else if (typeof ActiveXObject != "undefined") 
		{
				// Internet Explorer (IE5 and IE6) uses an ActiveX object
				return new ActiveXObject("Microsoft.XMLHTTP");
		}
		else {
				throw new Error("XMLHttpRequestnot supported");
		}
}
		
		
		
function notification_information(userid)
{
		var data="userId="+userid;
		var request = CreateXMLHttpRequest();
				
		request.onreadystatechange = function()
		{
			if(request.readyState == 4)
			{
				if (request.status == 200) 
				{ 	
					var notificationMsg = request.responseText;
					document.getElementById('notification').innerHTML = notificationMsg;						
				}
			}
		}
		request.open("POST", "http://A4360.research.ltu.se/groupper/php/linkedin/linkedin_notification.php", true);
		request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		request.send(data);
}	
			
//file deletion
function ln_delete_file(baseuserid)
{	
		IN.API.Profile("me")
  			.fields(["id", "firstName", "lastName","headline","location","pictureUrl","publicProfileUrl", "skills", "positions","educations", "dateOfBirth"])
                .result( function(me) 
                {
                      var myprofile = me.values[0];
                      var userId = myprofile.id;
               		  var data = "userId=" + baseuserid;
					  var request = CreateXMLHttpRequest();
				
					  request.onreadystatechange = function()
					  {
							if(request.readyState == 4)
							{
								if (request.status == 200) 
								{ 	
									var notificationMsg = request.responseText;
									alert(notificationMsg);
									
								}
						 	}
						}
						request.open("POST", "http://A4360.research.ltu.se/groupper/php/linkedin/delete_linkedin.php", true);
						request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
						request.send(data);
				});
}

//file deletion
function ln_show_file(baseuserid)
{	
	                  
                     
               		  var data = "userId=" + baseuserid;
					  var request = CreateXMLHttpRequest();
				      //alert(userIdd);
					  request.onreadystatechange = function()
					  {
							if(request.readyState == 4)
							{
								if (request.status == 200) 
								{ 	
									var notificationMsg = request.responseText;
									//alert(notificationMsg);
									if(notificationMsg.toString()=="0")
										alert("Please generate your data first!!!");
									else
										window.open(notificationMsg,'Download');
									
								}
						 	}
						}
						request.open("POST", "http://A4360.research.ltu.se/groupper/php/linkedin/show_linkedin.php", true);
						request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
						request.send(data);
		
}	
			
		
function runAjaxLN(JSONstring, metajs, myuserId)
{
		var data = "profileInfo=" + JSONstring + "&userId="+ myuserId +"&context_data="+metajs;
		request = CreateXMLHttpRequest();
		request.onreadystatechange = function()
		{
			if(request.readyState == 4)
			{
				if (request.status == 200) 
				{ 	
					var JSONtext = request.responseText;
					//alert('Your profile and interaction file is updated! Thank you');
					showLoader(false);
					runLNAggregator(myuserId);	
				}
			}
		}
		request.open("POST", "http://A4360.research.ltu.se/groupper/php/linkedin/ln_write.php", true);
		request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		request.send(data);
}


function runLNAggregator(myuserId)
	    {
				var data = "userId="+ myuserId;
				request = CreateXMLHttpRequest();
			
				request.onreadystatechange = function()
				{
					if(request.readyState == 4)
					{
						if (request.status == 200) 
						{ 	
							// parser.php response
							var JSONtext = request.responseText;
							alert("Your Data File Is Generated!!!");	
						}
					 }
				}
				request.open("POST", "http://A4360.research.ltu.se/groupper/php/aggregator/dataag_new.php", true);
				request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
			
				request.send(data);
	        }

	//Linkedin data graber
 function loadData() {
  showLoader(true);
  var userMetaData = new Object();
  var jsonObject = new Object();
  var jsonObjectEvaluation = new Object();
  var userMetaObj;
  var lobj_a = [];
  var lobjEvaluation = [];

  var metaJS;

IN.Event.on(IN, "systemReady", function() 
     { 
        IN.User.refresh();
	
		if(IN.User.isAuthorized()==true){
  
  IN.API.Connections("me")
    .fields(["id", "firstName", "lastName","headline","location","pictureUrl","publicProfileUrl", "skills", "interests", "positions", "educations", "dateOfBirth"])
    .params({"count":100})
    .result(function(result) {
      
      for (var index in result.values) 
      {
         	 var hometown = "";
  			 var country = "";
  			 var profilepicture = "";
  			 var workposition= "";
  			 
  			 var educations;
			var friends_userid;  			 
  			 
         	profile = result.values[index]
        
          	friends_userid = profile.id;
          	//checks whether the fields are null or not
          	if(profile.location!=null)
          			hometown=String(profile.location.name).toString().replace(/"/gi," ");
          	if(profile.positions!=null)
          			workposition = parseWork(profile.positions);
          			workposition = String(workposition).toString().replace(/"/gi," ");
          			
          	if(profile.educations!=null)
          			educations = parseEducation(profile.educations);
        			educations = String(educations).toString().replace(/"/gi," ");
          			
          			//alert(workposition);
          	if(profile.location!=null)
          			country=String(profile.location.country.code).toString().replace(/"/gi," ");
          	if(profile.pictureUrl!=null)
          			profilepicture=profile.pictureUrl;
          
                    //all linkedin data
             lobj_a[index] = {
                             	"Friendsname": profile.firstName + " " + profile.lastName,
                             	"username": profile.id,
                             	"userid":profile.id,
                             	"email":profile.id+"@linkedin.com",
                                "currentjob": String(profile.headline).toString().replace(/"/gi," "),
                                "work": workposition,
                                "birthdate": profile.dateOfBirth,
								"educations": educations,
                                "country": String(country).toString().replace(/"/gi," "),
                                "location":String(hometown).toString().replace(/"/gi," "),
                                "picture": profilepicture,
                                "profileurl": profile.publicProfileUrl,
                                "contextkey":  String(profile.headline).toString().replace(/"/gi," ") +","+ hometown  +","+ country +","+ educations +","+ workposition  
               };
               
               jsonObject.friends=lobj_a;
                       
               //evaluation data
                lobjEvaluation[index]={
                                "email": profile.id+'.'+profile.firstName+"@linkedin.com",
                                "fromFrequency": 0,
                                "toFrequency": 0
                };
                jsonObjectEvaluation.friends=lobjEvaluation;
         
      }
          //all linkedin data processing
          var JSONstring = JSON.stringify(jsonObject, null, '\t');
          var JSONstringEvaluation = JSON.stringify(jsonObjectEvaluation, null, '\t');
          
          IN.API.Profile("me")
  			.fields(["id"])
                .result( function(me) {
                       	var myprofile = me.values[0];
                        var myId = myprofile.id;
                         runAjaxLN(encodeURIComponent(JSONstring), encodeURIComponent(JSONstringEvaluation), myId);
            }); 
   });
 }
 else alert("You Need to Authorize First!!");
	});  
}

function loginChecker()
{
	IN.Event.on(IN, "systemReady", function() 
     { 
         IN.User.refresh();
	
		if(IN.User.isAuthorized()==true){
			Update_linkedin_IndexFile();
			logged();
		}else
		{
			document.getElementById('content').style.display = "none";
			document.getElementById('login').style.display = "block";
		}
	});                 	
}


//linkedin login & logout function
function linklogin()
{
	   IN.Event.on(IN, "systemReady", function() 
       { 
          	 IN.User.refresh();
           	if(!IN.User.isAuthorized())
                 	IN.User.authorize();
           	IN.Event.on(IN, "auth", function() 
          	{
           		 Update_linkedin_IndexFile();
           		
           		document.getElementById('login').style.display = "none";
           		document.getElementById('content').style.display = "block";
           		document.getElementById('other').style.display = "block";
           		
           		 IN.API.Profile("me")
  						.fields(["id", "firstName", "lastName","headline","location","pictureUrl","publicProfileUrl", "skills", "positions","educations", "dateOfBirth"])
                		.result( function(me) {
                       		 var myprofile = me.values[0];
                        	username=myprofile.firstName + " " + myprofile.lastName;
                        	var userid= myprofile.id;
                        	document.getElementById('userinfo').innerHTML='<img src="' + myprofile.pictureUrl +'" width="100" height="100">'+ username +'</br>';
                   		
          				    notification_information(userid);
                  });           		
         
      		 });
   		});
 }	
 
 
function logged()
{
	   IN.Event.on(IN, "systemReady", function() 
       { 
           	IN.Event.on(IN, "auth", function() 
          	{
           		
           		document.getElementById('login').style.display = "none";
           		document.getElementById('content').style.display = "block";
           		document.getElementById('other').style.display = "block";
           		
           		 IN.API.Profile("me")
  						.fields(["id", "firstName", "lastName","headline","location","pictureUrl","publicProfileUrl", "skills", "positions","educations", "dateOfBirth"])
                		.result( function(me) {
                       		var myprofile = me.values[0];
                        	username=myprofile.firstName + " " + myprofile.lastName;
                        	var userid= myprofile.id;
                        	document.getElementById('userinfo').innerHTML='<img src="' + myprofile.pictureUrl +'" width="100" height="100"> &nbsp;&nbsp;&nbsp;&nbsp;'+ username +'<br />';
                			notification_information(userid);
          	       });
                
      		 });
   		});
 }

		
function logout()
{
        if ( typeof IN === 'object' && typeof IN.User === 'object' && IN.User.isAuthorized() ) 
        {
        		IN.User.logout();
        		IN.User.refresh();
               	document.getElementById('login').style.display = "block";
               	document.getElementById('content').style.display = "none";
        }          
}
