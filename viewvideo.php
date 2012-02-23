<?php

require("database/strip_quotes.php");

require("database/strip_quotes_addslash.php");

require("main.php");

$instmedialist = &new medialist();
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
<div id="pagecontent">
<div id="mainpage">
<header>
<div id="banner">
<img src="images/cet.gif"/>OPENCAST
</div>
</header>
<div id="search_textfield"><div id="div_back"><input type="button" name="back" id="back" value="Back" onclick="history.go(-1);"/></div> <div id="div_download"><input type="button" id="download" name="download" value="Download" onclick="window.open('<?php echo $_GET['vid'];?>');"/></div></div>
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
<div id="clips_controller"><img src="images/left_images.jpg"/> | <img src="images/right_images.jpg"/></div>
</section>
-->

<section>
<div id="viewpresentation">
<div id="video">
<video id="video_control" controls="controls" autoplay="autoplay">
  <source src="<?php echo $_GET["vid"]; ?>" type="video/mp4" />
  <source src="<?php echo $_GET["vid"]; ?>" type="video/ogg" />
  Your browser does not support the video tag.
</video>
<input type='hidden' name='presentation_url' value="<?php 
if(isset($_GET['vid'])){echo $_GET['vid'];} ?>"/>
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
  <div id="search_textfield"><?php 
$chkcurrentclipurl = &$instmedialist->clipinfo->get('currentclipurl');

//if((!isset($chkcurrentclipurl))&&(isset($_GET['vid']))){
if(isset($_GET['vid'])){
    //$clipinfo = new Session();

  $instmedialist->clipinfo->del('currentclipurl');
  //$instmedialist->clipinfo->destroy();
    $instmedialist->clipinfo->set('currentclipurl', $_GET['vid']);
    $chgcurrentclipurl = $instmedialist->clipinfo->get('currentclipurl');

}
 ?><div id='div_back'><a href=viewvideo.php?vid=<?php echo  $instmedialist->clipinfo->get('previousclipurl'); ?>><img src='images/__left_images.jpg'/>Previous Clip</a></div> <div id='div_download'><a href=viewvideo.php?vid=<?php echo $instmedialist->clipinfo->get('nextclipurl'); ?>><img src='images/__right_images.jpg'/>Next Clip</a></div> </div>
<!--
<div id="search_textfield"><?php $instmedialist->getclips($clipinfo->get("currentclipurl")); ?><div id="div_back"><a href=viewvideo.php?vid=<?php echo $instmedialist->previousclipid; ?>/><img src="images/__left_images.jpg"/>Previous Clip</a></div> <div id="div_download"><a href=viewvideo.php?vid=<?php echo $instmedialist->nextclipid; ?>>Next Clip <img src="images/__right_images.jpg"/></a></div></div>
  //-->
</section>
</div>
</div>
</div>
</form>
</body>
</html>
