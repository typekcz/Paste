<!DOCTYPE HTML>
<html>
	<head>
		<meta charset="utf-8">
		<title>Paste</title>
		<meta name="description" content="Vlož URL, text nebo obrázek.">
		<meta name="author" content="Lukáš Kotržena">
	    <meta name="robots" content="all">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link href='https://fonts.googleapis.com/css?family=Inconsolata|Open+Sans&subset=latin,latin-ext' rel='stylesheet' type='text/css'>
	    <link rel="stylesheet" type="text/css" href="style.css">
		<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/highlight.js/9.12.0/styles/default.min.css">
		<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/highlight.js/9.12.0/highlight.min.js"></script>
	</head>
	<body>
		<input type="file" name="fileselect" hidden>
		<form onsubmit="formsubmit(); return false;" style="position: relative;">
			<textarea name="paste" class="paste" autocomplete="off" autofocus placeholder="Vlož URL, text nebo obrázek."></textarea>
			<img id="upload" src="cloud-upload.svg" alt="Upload" title="Select file">
			<br>
			<div id="expand" style="overflow: hidden; max-height: 0px; opacity: 0;">
				<label>Jaký chcete název zkrácené adresy?</label><br>
				<input name="custom" type="button" class="custom" autocomplete="off" value="Vlastní"><input name="random" type="button" class="random" value="Náhodná">
			</div>
			<input type="submit" name="submit" style="display: none">
		</form>
		<script type="text/javascript" src="regex-weburl.js"></script>
		<script type="text/javascript" src="script.js"></script>
	</body>
</html>