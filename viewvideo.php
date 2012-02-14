<?php
require("database/strip_quotes.php");

require("database/strip_quotes_addslash.php");

require("main.php");

$instmedialist = new medialist();
if(isset($_POST['submit'])){$instmedialist->savecomments();}
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
<form name='commentform' method='post'>
<div id="mainpage">
<header>
<div id="banner">
OpenCast
</div>
</header>
<div id="search_textfield">Back | Download</div>
<div>

<!--
<section>
<div id="listofpresentations">
<div id="video">
-->
<!--The <video> tag is changed to the <div> tag, which now loads the video -->
<!--
<div id="player" width="640" height="480" controls="controls">
</div> 
</div>
</div>
<div id="comments">Comments</div>
<div id="write_comment">Write a Comment</div>
<div id="clips_controller">Previous Clip | Next Clip</div>
</section>
-->

<section>
<div id="viewpresentation">
<div id="video">
<video id="video_control" width="480" height="360" controls="controls" autoplay="autoplay" preload="auto">
  <source src="<?php echo $_GET["vid"]; ?>" type="video/mp4" />
  <source src="<?php echo $_GET["vid"]; ?>" type="video/ogg" />
  Your browser does not support the video tag.
</video>
<input type='hidden' name='presentation_url' value='<?php echo $_GET["vid"]; ?>'/>
</div>
<!-- 1. The <div> tag will contain the <iframe> (and video player)
<div id="player"></div>
-->
</div>
<div id="commentfield">
<div id="comments">
<?php
$instmedialist->displaycomments();
?>
</div>
<div id="write_comment"><a href="#postcomment" onclick="postcomment();">Write a Comment</a></div>
</div>
<div id="clips_controller">Previous Clip | Next Clip</div>
</section>


</div>
</div>
</form>
</body>
</html>
