<?php
error_reporting(0);

$json_response = Array(
	"error" => 0,
	"phpErrors" => Array()
);

function handle_error( $num, $str, $file, $line, $context = null){
    array_push($json_response["phpErrors"], Array(
		"num" => $num,
		"str" => $str,
		"file" => $file,
		"line" => $line
    ));
}
set_error_handler("handle_error");
ini_set( "display_errors", "off" );
error_reporting( E_ALL );


//error_reporting(E_ALL);
//ini_set('display_errors', 1);

$re_url = "%^((?:(?:https?|ftp|teamspeak|skype|mailto|callto|apt|magnet|view-source)):(//)?)(?:\S+(?::\S*)?@)?(?:(?!(?:10|127)(?:\.\d{1,3}){3})(?!(?:169\.254|192\.168)(?:\.\d{1,3}){2})(?!172\.(?:1[6-9]|2\d|3[0-1])(?:\.\d{1,3}){2})(?:[1-9]\d?|1\d\d|2[01]\d|22[0-3])(?:\.(?:1?\d{1,2}|2[0-4]\d|25[0-5])){2}(?:\.(?:[1-9]\d?|1\d\d|2[0-4]\d|25[0-4]))|(?:(?:[a-z\x{00a1}-\x{ffff}0-9]-*)*[a-z\x{00a1}-\x{ffff}0-9]+)(?:\.(?:[a-z\x{00a1}-\x{ffff}0-9]-*)*[a-z\x{00a1}-\x{ffff}0-9]+)*(?:\.(?:[a-z\x{00a1}-\x{ffff}]{2,}))\.?)(?::\d{2,5})?(?:[/?#]\S*)?$%iuS";
$short = "";

function getNewId(){
	/*if(file_put_contents("diskSpaceTest", "0123456789ABCDEF") == 0){
		$json_response["error"] = 5;
		$json_response["message"] = "Bohužel není místo na disku.";
		die(json_encode($json_response));
	}
	unlink("diskSpaceTest");*/

	$idfile = fopen("id", "r");
	$id = fread($idfile, filesize("id"));
	fclose($idfile);
	
	while(true){
		$id += 1;
		
		$short = base64_encode($id);
		$short = trim($short, "=");
		$short = str_replace("+", "_", $short);
		$short = str_replace("/", "-", $short);
		
		if(!file_exists("url/".$short))
			break;
	}
	$idfile = fopen("id", "w");
	fwrite($idfile, $id);
	fclose($idfile);
	
	return $short;
}

function getShort(){
	if(isset($_REQUEST["short"])){
		if(preg_match("/^((\w){4,32})$/", $_REQUEST["short"])){
			if(file_exists("url/".$_REQUEST["short"])){
				$json_response["error"] = 3;
				die(json_encode($json_response));
			}
		} else {
			$json_response["error"] = 2;
			die(json_encode($json_response));
		}
		
		$short = $_REQUEST["short"];
	} else {
		$short = getNewId();
	}
	return $short;
}

if(isset($_REQUEST["text"])){
	$short = getShort();
	if(preg_match($re_url, $_REQUEST["text"], $matches) == true){
		$file = fopen("url/".$short, "w");
		fwrite($file, $_REQUEST["text"]);
		fclose($file);
		chmod("url/".$short, 0777);
	} else {
		$file = fopen("files/".$short.".txt", "w");
		fwrite($file, $_REQUEST["text"]);
		fclose($file);
		chmod("files/".$short.".txt", 0777);
		
		$file = fopen("url/".$short, "w");
		fwrite($file, "relative:files/".$short.".txt");
		fclose($file);
		chmod("url/".$short, 0777);
	}
} else if(isset($_FILES["image"])){
	$file = $_FILES["image"];
	$short = getShort();
	
	$allowedTypes = array(IMAGETYPE_PNG, IMAGETYPE_JPEG, IMAGETYPE_GIF);
	$detectedType = exif_imagetype($file['tmp_name']);
	if(!in_array($detectedType, $allowedTypes)){
		$json_response["error"] = 4;
		die(json_encode($json_response));
	}
	
	$filename = "files/".$short.".".pathinfo($file["name"], PATHINFO_EXTENSION);
	move_uploaded_file($file["tmp_name"], $filename);
	chmod($filename, 0777);
	
	$file = fopen("url/".$short, "w");
	fwrite($file, "relative:".$filename);
	fclose($file);
	chmod("url/".$short, 0777);
} else {
	$error = 1;
}

$json_response["url"] = @$_REQUEST["url"];
$json_response["short"] = $short;
echo json_encode($json_response);
/*echo "{
\"error\": ".$error.",
\"url\": \"".@$_REQUEST["url"]."\",
\"short\": \"".$short."\"
}";*/
?>
