<?php

require("database/strip_quotes.php");
require("database/strip_quotes_addslash.php");

  Class medialist {

  function  medialist(){
    //extract contents from feeds
//comment disable the youtube feed -  'http://www.youtube.com/rss/tag/uct.rss'
//$feedurls = Array('http://media.uct.ac.za/feeds/rss/2.0/latest/index.rss');
$feedurls = Array('_index.rss');
foreach($feedurls as $feedurl){
    $xml = simplexml_load_file($feedurl);
    if($feedurl == $feedurls[0]){
      $this->urls = $xml->xpath("/rss/channel/item/enclosure/@url");}
    else{
      $this->urls = $xml->xpath("/rss/channel/item/link");}

       }//end of foreach - feedurls
//store urls array in $this->mediaurls
$this->mediaurls = &$this->urls;

//session begins
require_once("database/session.php");
if(!isset($this->clipinfo)){$this->clipinfo = new Session();}
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
<div id="title"><a href="viewvideo.php?vid=
EOD;

$html5 = <<<EOD
">
EOD;

$html6 = <<<EOD
</a></div>
<div id="author">
EOD;

$html7 = <<<EOD
</div>
<div id="date_published">
EOD;

$html8 = <<<EOD
</div>
<div id="total_comments">
EOD;

$html9 = <<<EOD
</div>
</div>
</section>
EOD;
//comment disable the youtube feed - , 'http://www.youtube.com/rss/tag/uct.rss'
//$feedurls = Array('http://media.uct.ac.za/feeds/rss/2.0/latest/index.rss');
$feedurls = Array('_index.rss');

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

 
//instantiating variables begin
      if(isset($titles[$n])){$this->titles=safeAddSlashes($titles[$n]);}else{$this->titles = " ";}
      if(isset($authors[$n])){$this->authors=safeAddSlashes($authors[$n]);}else{$this->authors = " ";}
      if(isset($pubDates[$n])){$this->pubDates=safeAddSlashes($pubDates[$n]);}else{$this->pubDates = " ";}
      if(isset($urls[$n])){$this->urls=safeAddSlashes($urls[$n]);}else{$this->urls = " ";}

      //get comment size
      $this->no_of_comments = medialist::commentsize($this->urls);
      if(isset($this->authors)){
	//echo $html1 . $urls[$n] . $html2 . $urls[$n] . $html3 . $urls[$n] . $html4 . $urls[$n]. $html5 . $titles[$n] . $html6 . $authors[$n] . $html7 . $pubDates[$n] . $html8 . $no_of_comments . $html9;
echo $html1 . $this->urls . $html2 . $this->urls . $html3 . $this->urls . $html4 . $this->urls. $html5 . $this->titles . $html6 . $this->authors . $html7 . $this->pubDates . $html8 . $this->no_of_comments . $html9;
}
      else{      	
	//echo $html1 . $urls[$n] . $html2 . $urls[$n] . $html3 . $urls[$n] . $html4 . $urls[$n] . $html5 . $titles[$n] . $html6 . $html7 . $pubDates[$n] . $html8 .  $no_of_comments . $html9;
	echo $html1 . $this->urls . $html2 . $this->urls . $html3 . $this->urls . $html4 . $this->urls . $html5 . $this->titles . $html6 . $html7 . $this->pubDates . $html8 .  $this->no_of_comments . $html9;
}

/*
$this->titles=safeAddSlashes($titles[$n]);
$this->authors=safeAddSlashes($authors[$n]);
$this->pubDates=safeAddSlashes($pubDates[$n]);
$this->urls=safeAddSlashes($urls[$n]);
*/
//storing values in DB for search functionality


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

  }//end of savecomments function

  function displaycomments(){
if(isset($_POST['vid'])){$this->presentation_url = safeAddSlashes($_POST['vid']);}else{$this->presentation_url = safeAddSlashes($_GET['vid']);}
//database connection begins
require('database/connx.php');

$db = & new MySQL($host,$dbUser,$dbPass,$dbName);
$sql="select str_name,str_comment,time_pubdate,str_msg_type from comments where str_presentation_url='" . $this->presentation_url . "'";
$result=$db->query($sql);
$this->strcommenthtml3 = "";
$this->strcommenthtml1 =  "<div><div name='no_of_comments'>" . $result->size() . " comments</div>";
while ($row = $result->fetch()) {
  if($row['str_msg_type'] == 'Posted'){$this->strcommenthtml2 =  "<div id='msg_type_posted'>";}
  else{$this->strcommenthtml2 =  "<div id='msg_type_reply'>";};
    $this->strcommenthtml3 .= $this->strcommenthtml2 .  "<span id='db_comment'>" . $row['str_comment'] . "</span><br/>" . "<span id='db_comment_author'>" . $row['str_msg_type'] . " by " . $row['str_name'] . " on " . $row['time_pubdate'] . "</span> <span id='reply'><a href='#'>Reply</a></span></div>";
    }
$this->strcommenthtml4 = "</div>";
echo $this->strcommenthtml1 . $this->strcommenthtml3 . $this->strcommenthtml4;

  }//end of displaycomments function

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
function getflashurl($flashurl){
//sample url="http://media.uct.ac.za/static/892fd3b6-7e3e-410a-9d5f-8dbb011d2c22/2c505ca9-96ee-45fd-954f-019d40321c44/Presentation.mp4";
  $this->flashurl = $flashurl;
  $this->flashurlid =  substr($this->flashurl, 30, 36);
  return "http://media.uct.ac.za/engage/ui/embed.html?id=" . $this->flashurlid;
      }//end of getflashurl

}//end of class medialist


$instmedialist = &new medialist();
if(isset($_POST['submit'])){$instmedialist->savecomments();}
if(isset($_POST['getcomments'])){$instmedialist->displaycomments();}

?>
