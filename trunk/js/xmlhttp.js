function AJAXRequest(page,retfonc,methode,data) {
	var xhr_object = null;
	if(window.XMLHttpRequest) // Firefox
	   xhr_object = new XMLHttpRequest();
	else if(window.ActiveXObject) // Internet Explorer
	   xhr_object = new ActiveXObject("Microsoft.XMLHTTP");
	else { // XMLHttpRequest non supporté par le navigateur
	   alert("Votre navigateur ne supporte pas les objets XMLHTTPRequest...");
	   return;
	}
	if (data=="")
		data=null;
	if(methode == "GET" && data != null) { 
	   page += "?"+data; 
	   data = null; 
	}
	xhr_object.open(methode, page, true);
	xhr_object.onreadystatechange = function() {
		if(xhr_object.readyState == 4) {
			var RetAjax=xhr_object.responseText;
			eval(retfonc+'(RetAjax);');
                }
	}
	if(methode == "POST")
	   xhr_object.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	xhr_object.send(data);

}