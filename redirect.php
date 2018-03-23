<?php 
$key = substr($_SERVER["REQUEST_URI"], 1);
if(file_exists("./url/".$key)){
	$soubor = fopen("./url/".$key,"r");
	$url = fgets($soubor);
	if(substr($url, 0, 9) === "relative:"){
		$url = substr($url, 9);
		$ext = pathinfo($url, PATHINFO_EXTENSION);
		if($ext == "txt"){
			include "text.php";
			display_text($url);
		} else {
			include "image.php";
			display_image($url);
		}
	} else {
		header("HTTP/1.1 301 Moved Permanently");
		header("Location: ".$url);
		header("Connection: close");
	}
} else {
	include "index.php";
}
?>