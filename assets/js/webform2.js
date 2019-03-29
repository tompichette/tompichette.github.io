var mndFileds = new Array('Company', 'First Name', 'Last Name', 'Email', 'Phone');
var fldLangVal = new Array('Company', 'First Name', 'Last Name', 'Email', 'Phone Number');
var name = '';
var email = '';

/* Do not remove this code. */
function reloadImg() {
	if (document.getElementById('imgid').src.indexOf('&d') !== -1) {
		document.getElementById('imgid').src = document.getElementById('imgid').src.substring(0, document.getElementById('imgid').src.indexOf('&d')) + '&d' + new Date().getTime();
	} else {
		document.getElementById('imgid').src = document.getElementById('imgid').src + '&d' + new Date().getTime();
	}
}

function checkMandatory3490434000001872023() {
	for (i = 0; i < mndFileds.length; i++) {
		var fieldObj = document.forms['WebToLeads3490434000001872023'][mndFileds[i]];
		if (fieldObj) {
			if (((fieldObj.value).replace(/^\s+|\s+$/g, '')).length == 0) {
				if (fieldObj.type == 'file') {
					alert('Please select a file to upload.');
					fieldObj.focus();
					return false;
				}
				alert(fldLangVal[i] + ' cannot be empty.');
				fieldObj.focus();
				return false;
			} else if (fieldObj.nodeName == 'SELECT') {
				if (fieldObj.options[fieldObj.selectedIndex].value == '-None-') {
					alert(fldLangVal[i] + ' cannot be none.');
					fieldObj.focus();
					return false;
				}
			} else if (fieldObj.type == 'checkbox') {
				if (fieldObj.checked == false) {
					alert('Please accept  ' + fldLangVal[i]);
					fieldObj.focus();
					return false;
				}
			}
			try {
				if (fieldObj.name == 'Last Name') {
					name = fieldObj.value;
				}
			} catch (e) {}
		}
	}
}
