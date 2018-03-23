<?php
	function display_image($image){
?>
<!DOCTYPE HTML>
<html>
	<head>
		<meta charset="utf-8">
		<title>Paste - Obrázek</title>
	    <meta name="robots" content="all">
		<meta name="viewport" content="width=device-width, initial-scale=1">
	    <link rel="stylesheet" type="text/css" href="style.css">
	</head>
	<body>
		<img class="image" src="<?php echo $image; ?>" alt="Obrázek">
	</body>
</html>
<?php
}
?>