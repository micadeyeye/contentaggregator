<?php

  Class medialist {
  function  medialist(){
//database connection begins

// Include the MySQL class
require_once('database/MySQL.php');

  }
  function loadpresentations(){
$html1 = <<<EOD
<section>
<div id="listofpresentations">
<div id="video">
<a href="viewvideo.php?vid=
EOD;
$html2 = <<<EOD
"><video id="video_control" controls="controls">
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
//comment disable the youtube feed
//$feedurls = Array('index.rss', 'http://www.youtube.com/rss/tag/uct.rss');
$feedurls = Array('index.rss');

require('database/connx.php');
// Connect to MySQL
$db = & new MySQL($host,$dbUser,$dbPass,$dbName);

//clear DB
$sql = "DELETE from mediadetails";
$result=$db->query($sql);

foreach($feedurls as $feedurl){
    //$xml = simplexml_load_file('http://media.uct.ac.za/feeds/rss/2.0/latest/index.rss'); 
    $xml = simplexml_load_file($feedurl);
    
    $titles = $xml->xpath('/rss/channel/item/title');
    $pubDates = $xml->xpath('/rss/channel/item/pubDate');
    if($feedurl == $feedurls[0]){
      $urls = $xml->xpath("/rss/channel/item/enclosure/@url");}
    else{
      $urls = $xml->xpath("/rss/channel/item/link");}

    $n = 0;
    foreach($titles as $title) {
    echo $html1 . $urls[$n] . $html2 . $urls[$n] . $html3 . $urls[$n] . $html4 . $titles[$n] . $html5 . $pubDates[$n] . $html6;

//storing values in DB for search functionality

//instantiating variables begin
$this->titles=safeAddSlashes($titles[$n]);
$this->pubDates=safeAddSlashes($pubDates[$n]);
$this->urls=safeAddSlashes($urls[$n]);
$sql="INSERT mediadetails SET
str_title='$this->titles',
time_pubDate='$this->pubDates',
str_url='$this->urls'";

$result=$db->query($sql);

$n++;
    }//end of foreach - title
   }//end of foreach - feedurls
  }//end of function medialist

  function savecomments(){
//database connection begins
require('database/connx.php');

// Connect to MySQL
$db = & new MySQL($host,$dbUser,$dbPass,$dbName);

//storing values in DB for search functionality

//instantiating variables begin
$this->comment_author = safeAddSlashes($_POST['comment_author']);
$this->pubDate = date("M j, Y, g:i a");
$this->comment = safeAddSlashes($_POST['comment']);
$this->msg_type = $_POST['msg_type'];
if(isset($_POST['vid'])){$this->presentation_url = safeAddSlashes($_POST['vid']);}else{$this->presentation_url = safeAddSlashes($_GET['vid']);}
$sql="INSERT comments SET
str_presentation_url='$this->presentation_url',
str_name='$this->comment_author',
time_pubDate='$this->pubDate',
str_comment='$this->comment',
str_msg_type='$this->msg_type'";
//echo $sql;
$result=$db->query($sql);

  }//end of savecomment function

  function displaycomments(){
if(isset($_POST['vid'])){$this->presentation_url = safeAddSlashes($_POST['vid']);}else{$this->presentation_url = safeAddSlashes($_GET['vid']);}
//database connection begins
require('database/connx.php');

$db = & new MySQL($host,$dbUser,$dbPass,$dbName);
$sql="select str_name,str_comment,time_pubdate,str_msg_type from comments where str_presentation_url='" . $this->presentation_url . "'";
$result=$db->query($sql);
echo "<div><div name='no_of_comments'>" . $result->size() . " comments</div>";
while ($row = $result->fetch()) {
  if($row['str_msg_type'] == 'Posted'){echo "<div id='msg_type_posted'>";}
  else{echo "<div id='msg_type_reply'>";};
    echo "<span id='db_comment'>" . $row['str_comment'] . "</span><br/>";
    echo "<span id='db_comment_author'>" . $row['str_msg_type'];
    echo " by " . $row['str_name'];
    echo " on " . $row['time_pubdate'] . "</span> <span id='reply'><a href='#'>Reply</a></span></div>";
    }
echo "</div>";

  }//end of displaycomment function
}//end of class medialist


?>
