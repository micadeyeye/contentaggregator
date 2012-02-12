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
<?php

  Class medialist {

  function medialist(){
$html1 = <<<EOD
<section>
<div id="listofpresentations">
<div id="video">
<a href="viewvideo.php?id=
EOD;
$html2 = <<<EOD
"><video id="video_control" width="320" height="240" controls="controls">
  <source src="
EOD;

$html3 = <<<EOD
" type="video/mp4" />
  <source src="
EOD;

$html4 = <<<EOD
" type="video/ogg" />
  Your browser does not support the video tag.
</video> 
</a>
</div>
<!-- 1. The <div> tag will contain the <iframe> (and video player)
<div id="player"></div>
-->
<div id="title">
EOD;

$html5 = <<<EOD
</div>
<div id="date_published">
EOD;

$html6 = <<<EOD
</div>
<div id="total_comments">38 comments</div>
<div id="view_comments">View Comments</div>
</div>
</section>
EOD;


    //$xml = simplexml_load_file('http://media.uct.ac.za/feeds/rss/2.0/latest/index.rss'); 
    $xml = simplexml_load_file('index.rss');
    
    $titles = $xml->xpath('/rss/channel/item/title');
    $pubDates = $xml->xpath('/rss/channel/item/pubDate');
    $urls = $xml->xpath("/rss/channel/item/enclosure/@url");

//database connection begins

// Include the MySQL class
require_once('database/MySQL.php');

require('database/connx.php');

// Connect to MySQL
$db = & new MySQL($host,$dbUser,$dbPass,$dbName);

//clear DB
$sql = "DELETE from mediadetails";
$result=$db->query($sql);

    $n = 0;
    foreach($titles as $title) {
    echo $html1 . $urls[$n] . $html2 . $urls[$n] . $html3 . $urls[$n] . $html4 . $titles[$n] . $html5 . $pubDates[$n] . $html6;

//storing values in DB for search functionality

//instantiating variables begin
$this->titles=$titles[$n];
$this->pubDates=$pubDates[$n];
$this->urls=$urls[$n];
$sql="INSERT mediadetails SET
str_title='$this->titles',
time_pubDate='$this->pubDates',
str_url='$this->urls'";

$result=$db->query($sql);

$n++;
    }
  }//end of function medialist
}//end of class medialist

new medialist();

?>

</div>
</div>
</body>
</html>
