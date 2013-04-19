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
         else 
         {
                 throw new Error("XMLHttpRequestnot supported");
         }
 }

//file deletion
function gp_delete_file(userId)
{	
        var data = "userId=" + userId;
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
		request.open("POST", "http://a4647.research.ltu.se/~satin/advuev/php/googleplus/googleplus_delete.php", true);
		request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		request.send(data);
}
				
//file show
function gp_show_file(userIdd)
{	
	                  
                     
               		  var data = "userId=" + userIdd;
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
						request.open("POST", "http://a4647.research.ltu.se/~satin/advuev/php/googleplus/googleplus_show.php", true);
						request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
						request.send(data);
		
}

//file deletion
function ln_delete(userId)
{	
		
               		  var data = "userId=" + userId;
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
						request.open("POST", "http://a4647.research.ltu.se/~satin/advuev/php/linkedin/delete_linkedin.php", true);
						request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
						request.send(data);
			
}