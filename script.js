var baseUrl = "http://paste.kotrzena.eu/";
var $paste = document.getElementsByName("paste")[0];
var pastedBlob = null;
//$paste.pattern = re_weburl.source;

var $random = document.getElementsByName("random")[0];
$random.waveOffset = 0;
$random.asciiWave = function(){
	var wave = "¸,ø¤º°`°º¤ø,¸";
	$random.value = wave.substring($random.waveOffset%13);
	for(var i = 0; i < 4; i++)
		$random.value += wave;
	$random.waveOffset++;
	//setInterval($random.asciiWave, 500);
};
$random.onclick = function(){
	if($random.type != "button")
		return;
	$random.style.width = "100%";
	$custom.style.width = "0";
	$custom.style.paddingLeft = "0";
	$custom.style.paddingRight = "0";
	$random.type = "text";
	$random.readOnly = true;
	$random.interval = setInterval($random.asciiWave, 100);
	setTimeout(function(){
		$custom.style.display = "none";
	}, 500);
	var xhr = new XMLHttpRequest();
	xhr.onreadystatechange = function() {
		if (xhr.readyState == 4 && xhr.status == 200) {
			clearInterval($random.interval);
			console.log(xhr.responseText);
			var response = JSON.parse(xhr.responseText);
			if(response.error != 0){
				if(typeof(response.message) == "undefined")
					alert("Bohužel nastala chyba, zkuste to později.");
				else
					alert(response.message);
				return;
			}
			$random.value = baseUrl+response.short;
			$random.select();
		}
	};
	xhr.open("POST", "core.php", true);
	//xhr.setRequestHeader("Content-type", "multipart/form-data");
	var fd = new FormData();
	if($paste.tagName == "TEXTAREA"){
		fd.append("text", $paste.value);
		$paste.readOnly = true;
		if($paste.value.split(/\r\n|\r|\n/).length > 1){
			var pastedCode = document.createElement("CODE");
			pastedCode.innerText = $paste.value;
			pastedCode.className = "paste";
			$paste.parentNode.insertBefore(pastedCode, $paste);
			$paste.parentNode.removeChild($paste);
			$paste = pastedCode;
			hljs.highlightBlock($paste);
		}
	} else if($paste.tagName == "IMG"){
		fd.append("image", pastedBlob);
	}
	xhr.send(fd);
};

var $custom = document.getElementsByName("custom")[0];
$custom.pattern = "^[a-zA-Z0-9]{4,16}$";
$custom.submitFunction = function(){
	if($custom.value == ""){
		$custom.required = true;
		submit();
	} else if(!$custom.validity.valid){
		submit();
	} else {
		$custom.readOnly = true;
		var xhr = new XMLHttpRequest();
		xhr.onreadystatechange = function() {
			if (xhr.readyState == 4 && xhr.status == 200) {
				console.log(xhr.responseText);
				var response = JSON.parse(xhr.responseText);
				if(response.error == 0){
					$custom.value = baseUrl+response.short;
					$custom.select();
				} else {
					if(typeof(response.message) == "undefined"){
						$custom.validity.customError = true;
						$custom.readOnly = false;
						//alert("Tato adresa je již zabraná, zvolte prosím jinou.");
						$custom.setCustomValidity("Tato adresa je již zabraná, zvolte prosím jinou.");
						submit();
						$custom.setCustomValidity("");
						$custom.validity.customError = false;
						$form.onsubmit = $custom.submitFunction;
					} else {
						alert(response.message);
					}
				}
			}
		};
		xhr.open("POST", "core.php", true);
		var fd = new FormData();
		fd.append("short", $custom.value);
		if($paste.tagName == "TEXTAREA"){
			fd.append("text", $paste.value);
			$paste.readOnly = true;
			if($paste.value.split(/\r\n|\r|\n/).length > 1){
				var pastedCode = document.createElement("CODE");
				pastedCode.innerText = $paste.value;
				pastedCode.className = "paste";
				$paste.parentNode.insertBefore(pastedCode, $paste);
				$paste.parentNode.removeChild($paste);
				$paste = pastedCode;
				hljs.highlightBlock($paste);
			}
		} else if($paste.tagName == "IMG"){
			fd.append("image", pastedBlob);
		}
		xhr.send(fd);
		$form.onsubmit = function(){
			return false;
		};
	}
	return false;
};
$custom.onclick = function(){
	if($custom.type != "button")
		return;
	$custom.style.width = "100%";
	$random.style.width = "0";
	$random.style.paddingLeft = "0";
	$random.style.paddingRight = "0";
	$custom.type = "text";
	$custom.placeholder = $custom.value;
	$custom.value = "";
	setTimeout(function(){
		$random.style.display = "none";
	}, 500);
	$form.onsubmit = $custom.submitFunction;
};

var $expand = document.getElementById("expand");
// $expand.style.overflow = "hidden";
// $expand.style.maxHeight = "0px";
// $expand.style.opacity = "0";
$expand.expand = function(){
	this.style.overflow = "unset";
	this.style.maxHeight = "66px";
	setTimeout(function(){
		$expand.style.opacity = "1";
	},500);
};
$expand.collapse = function(){
	this.style.opacity = "0";
	setTimeout(function(){
		$expand.style.overflow = "hidden";
		$expand.style.maxHeight = "0px";
	},500);
};

var $submit = document.getElementsByName("submit")[0];
function submit(){
	$submit.click();
};

var $form = document.getElementsByTagName("form")[0];
//$form.onsubmit =
$paste.onkeyup = function(){
	if($custom.value)
	if($paste.value == ""){
		$paste.required = true;
		submit();
	//} else if(!$paste.validity.valid){
	//	submit();
	} else {
		$expand.expand();
		//$paste.readOnly = true;
	}
	$form.onsubmit = function(){
		return false;
	};
	return false;
};

$paste.onkeydown = function(event){
	if(event.keyCode == 13)
		$paste.classList.add("extended");
}

$paste.oninput = function(event){
	if($paste.value.split(/\r\n|\r|\n/).length > 1)
		$paste.classList.add("extended");
}

$paste.onpaste = function(event){
	// use event.originalEvent.clipboard for newer chrome versions
	var items = (event.clipboardData  || event.originalEvent.clipboardData).items;
	// find pasted image among pasted items
	processTransferItems(items);
}

$paste.ondragover = function(event){
	event.preventDefault();
}

$paste.ondrop = function(event){
	event.preventDefault();
	
	var items = event.dataTransfer.items;
	
	processTransferItems(items);
}

function processTransferItems(items){
	var blob = null;
	for (var i = 0; i < items.length; i++) {
		if (items[i].type.indexOf("image") === 0) {
			blob = items[i].getAsFile();
		}
	}
	// load image if there is a pasted image
	if (blob !== null) {
		var reader = new FileReader();
		reader.onload = function(event){
			var pastedImg = document.createElement("IMG");
			pastedImg.src = event.target.result;
			pastedImg.className = "paste";
			$paste.parentNode.insertBefore(pastedImg, $paste);
			$paste.parentNode.removeChild($paste);
			$paste = pastedImg;
			
			$expand.expand();
		};
		reader.readAsDataURL(blob);
		pastedBlob = blob;
	}
}
