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
<div id="listofpresentations">
<div id="video">
<a href="viewvideo.html">
<video id="video_control" width="320" height="240" controls="controls" autoplay="autoplay" preload="auto">
  <source src="<?php echo $_GET["id"]; ?>" type="video/mp4" />
  <source src="<?php echo $_GET["id"]; ?>" type="video/ogg" />
  Your browser does not support the video tag.
</video> 
</a>
</div>
<!-- 1. The <div> tag will contain the <iframe> (and video player)
<div id="player"></div>
-->
</div>
<div id="comments">Comments</div>
<div id="write_comment">Write Comment</div>
<div id="clips_controller">Previous Clip | Next Clip</div>
</section>


</div>
</div>
</body>
</html>
