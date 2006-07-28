function pasteCode() {
	new Effect.Appear(document.getElementById('SubmitCodeBorder'), { queue: {position:'end', scope: 'effet'}});
}
function showCode() {
	if (document.getElementById('numcode').value!='') {
		SendID(document.getElementById('numcode').value);
	} else 
		document.getElementById('numcode').focus();
	document.getElementById('numcode').value='';
}
function CloseSource() {
	new Effect.Fade(document.getElementById('SubmitCodeBorder'), { queue: {position:'end', scope: 'effet'}});
}
function SendSource() {
	var s=escape(document.getElementById('source').value);
	var l=escape(document.getElementById('language').value);
	if (s=='') {
		alert("Veuillez un code source.")
		return false;
	}
	new Effect.Appear(document.getElementById('Load'), { queue: {position:'end', scope: 'effet'}});
	CloseSource();
	new Effect.Puff(document.getElementById('ParsedCode'), { queue: {position:'end', scope: 'effet'}});
	AJAXRequest('xmlhttprequest.php','SetSource','POST','source='+s+'&language='+l);
}
function SendID(id) {
	if (isNaN(id)) {
		alert("Veuillez saisir un nombre.")
		return false;
	}
	new Effect.Appear(document.getElementById('Load'), { queue: {position:'end', scope: 'effet'}});
	CloseSource();
	new Effect.Puff(document.getElementById('ParsedCode'), { queue: {position:'end', scope: 'effet'}});
	AJAXRequest('xmlhttprequest.php','SetSource','POST','id='+id);
}
var data='';
function SetSource(v) {
	v=v.split("ZQSGDFG65465TESRGSDFGSDFG");
	data=v[1];
	new Effect.Fade(document.getElementById('Load'), { queue: {position:'end', scope: 'effet'},afterFinish:PutData});
	if (document.getElementById('ParsedStyle').styleSheet) {
		document.getElementById('ParsedStyle').styleSheet.cssText=v[0];
		new Effect.Appear(document.getElementById('ParsedCode'), { queue: {position:'end', scope: 'effet'}});
	} else {
		document.getElementById('ParsedStyle').innerHTML=v[0];
		new Effect.BlindDown(document.getElementById('ParsedCode'), { queue: {position:'end', scope: 'effet'}});
	}
}
function PutData() {
	document.getElementById('ParsedCode').innerHTML=data;
	data='';
}