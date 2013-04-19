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