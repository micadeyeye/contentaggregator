<?php

  Class medialist {

  function  medialist(){
    //extract contents from feeds
//comment disable the youtube feed
//$feedurls = Array('http://media.uct.ac.za/feeds/rss/2.0/latest/index.rss', 'http://www.youtube.com/rss/tag/uct.rss');
$feedurls = Array('_index.rss');
foreach($feedurls as $feedurl){
    $xml = simplexml_load_file($feedurl);
    if($feedurl == $feedurls[0]){
      $this->urls = $xml->xpath("/rss/channel/item/enclosure/@url");}
    else{
      $this->urls = $xml->xpath("/rss/channel/item/link");}

       }//end of foreach - feedurls
//store urls array in $this->mediaurls
$this->mediaurls = $this->urls;

//session begins
require_once("database/session.php");
$this->clipinfo = new Session();
//$this->currentclipurl = $this->clipinfo->get('currentclipurl');
//  if(!isset($this->currentclipurl)){$this->clipinfo->set("currentclipurl", $this->urls[0]);}


//database connection begins

// Include the MySQL class
require_once('database/MySQL.php');

//set the next and previous links
if(isset($_GET['vid'])){
$this->clipinfo->set('currentclipurl', $_GET['vid']);
$this->currentclipurl = $this->clipinfo->get('currentclipurl');
medialist::getclips($this->currentclipurl);}

/*
//set the next and previous links
//if(isset($_GET['vid'])){echo $_GET['vid'];}
if(isset($_GET['vid'])){
  //$this->clipinfo->destroy();
medialist::getclips($_GET['vid']);
}
*/

  }
  function loadpresentations(){
$html1 = <<<EOD
<section>
<div id="listofpresentations">
<div id="video">
<a href="viewvideo.php?vid=
EOD;
$html2 = <<<EOD
"><video id="video_preview" controls="controls">
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
<div id="author">
EOD;

$html6 = <<<EOD
</div>
<div id="date_published">
EOD;

$html7 = <<<EOD
</div>
<div id="total_comments">
EOD;

$html8 = <<<EOD
</div>
</div>
</section>
EOD;
//comment disable the youtube feed
$feedurls = Array('http://media.uct.ac.za/feeds/rss/2.0/latest/index.rss', 'http://www.youtube.com/rss/tag/uct.rss');
//$feedurls = Array('_index.rss');

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
    $authors = $xml->xpath('/rss/channel/item/dc:creator');
    if($feedurl == $feedurls[0]){
      $urls = $xml->xpath("/rss/channel/item/enclosure/@url");}
    else{
      $urls = $xml->xpath("/rss/channel/item/link");}

    $n = 0;
    foreach($titles as $title) {
      //get comment size
      $no_of_comments = medialist::commentsize($urls[$n]);
      if(isset($authors[$n])){
	echo $html1 . $urls[$n] . $html2 . $urls[$n] . $html3 . $urls[$n] . $html4 . $titles[$n] . $html5 . $authors[$n] . $html6 . $pubDates[$n] . $html7 .       $no_of_comments . $html8;}
      else{      	
	echo $html1 . $urls[$n] . $html2 . $urls[$n] . $html3 . $urls[$n] . $html4 . $titles[$n] . $html5 . $html6 . $pubDates[$n] . $html7 .  $no_of_comments . $html8;
}

//storing values in DB for search functionality

//instantiating variables begin
$this->titles=safeAddSlashes($titles[$n]);
$this->authors=safeAddSlashes($authors[$n]);
$this->pubDates=safeAddSlashes($pubDates[$n]);
$this->urls=safeAddSlashes($urls[$n]);
$sql="INSERT mediadetails SET
str_title='$this->titles',
str_author='$this->authors',
time_pubDate='$this->pubDates',
str_url='$this->urls'";

$result=$db->query($sql);

$n++;
    }//end of foreach - title
   }//end of foreach - feedurls
//store urls array in $this->mediaurls
$this->mediaurls = $urls;
  }//end of function loadpresentations

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

  function commentsize($vid){
$this->presentation_url = safeAddSlashes($vid);
//database connection begins
require('database/connx.php');

$db = & new MySQL($host,$dbUser,$dbPass,$dbName);
$sql="select str_name,str_comment,time_pubdate,str_msg_type from comments where str_presentation_url='" . $this->presentation_url . "'";
$result=$db->query($sql);
return "<div><div name='no_of_comments'>" . $result->size() . " comments</div>";
}//end of commentsize

  function getclips(&$curentclipurl){
$vid = &$this->currentclipurl;

//search $mediaurls for the current url
$mediaurls_size = count($this->mediaurls);
foreach($this->mediaurls as $key => $value){
  if($value == $vid){

$this->clipinfo->del('previousclipurl');
$this->clipinfo->del('nextclipurl');
if($key == 0){
      $this->clipinfo->set("previousclipurl", $this->mediaurls[$key]);
}else{
     $this->clipinfo->set("previousclipurl", $this->mediaurls[$key - 1]);

}
if($key == $mediaurls_size - 1){
            $this->clipinfo->set("nextclipurl", $this->mediaurls[$key]);
}else{
                  $this->clipinfo->set("nextclipurl", $this->mediaurls[$key + 1]);

}
  }//end of if value==vid
 }//end of foreach
  }//close of getclips function
}//end of class medialist


?>
