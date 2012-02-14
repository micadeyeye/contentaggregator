<?php
require("database/strip_quotes.php");

require("database/strip_quotes_addslash.php");

?>


<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>UCT OpenCast</title>
<meta name="description" content="UCT OpenCast">
<meta name="author" content="Olutayo Boyinbode">
<link rel="stylesheet" href="css/styles.css?v=1.0">
<script language="javascript" src="scripts/media.js">
</script>
</head>
<body>
<div id="mainpage">
<header>
<div id="banner">
OpenCast
</div>
</header>
<form action="search_result.php" method="post" enctype="multipart/form-data"><div id="search_textfield"><input type='text' size='25' name='searchterm' value=""/> <input type='submit' name='submit' Value='Submit'></div></form>
<div>
  <?php require("main.php");
$instmedialist = new medialist();
$instmedialist->loadpresentations();
 ?>
</div>
</div>
</body>
</html>
