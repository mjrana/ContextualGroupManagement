// function is executed when var request state changes
			function sendData()
			{
				// if request object received response
				if(request.readyState == 4)
				{
					// parser.php response
					var JSONtext = request.responseText;
					//alert('Your friend list is saved in ' + datafile + ' file!');
					alert('Context data have been generated based on your profile!!\n Thank you!!!');	
				}
			}
			
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
//for writting to json file
			var datafile='';
			function runAjax(JSONstring, userfile, datatyp)
			{
				
				
				datafile=userfile+'.json';
				//datafile='test.json';
			
				var data="json="+JSONstring+"&file="+datafile;
					
				request = CreateXMLHttpRequest();
				request.onreadystatechange = sendData;
				request.open("POST", "./php/ln_write.php", true);
				request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
				//request.setRequestHeader("Content-length", data.length);
				//request.setRequestHeader("Connection", "close");
				request.send(data);
			}
 