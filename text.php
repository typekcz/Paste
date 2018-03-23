<?php
	function display_text($text){
?>
<!DOCTYPE HTML>
<html>
	<head>
		<meta charset="utf-8">
		<title>Paste - Text</title>
	    <meta name="robots" content="all">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link href='https://fonts.googleapis.com/css?family=Inconsolata|Open+Sans&subset=latin,latin-ext' rel='stylesheet' type='text/css'>
	    <link rel="stylesheet" type="text/css" href="style.css">
		<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/highlight.js/9.12.0/styles/default.min.css">
		<script src="//cdnjs.cloudflare.com/ajax/libs/highlight.js/9.12.0/highlight.min.js"></script>
	</head>
	<body>
		<code><?php
			echo htmlentities(file_get_contents($text));
		?></code>
	</body>
	<script type="text/javascript">
		hljs.initHighlightingOnLoad();
		hljs.highlightBlock(document.getElementsByTagName("CODE")[0]);
	</script>
</html>
<?php
}
?>