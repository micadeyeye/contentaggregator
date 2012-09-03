<?php

require("database/strip_quotes.php");
require("database/strip_quotes_addslash.php");

  Class medialist {

  function  medialist(){
    //extract contents from feeds
//overwrite youtube feed (youtube.xml) on server; on error, use existing youtube.xml
libxml_use_internal_errors(true);
$ytxml = @simplexml_load_file("http://www.youtube.com/rss/tag/uct.rss");
if($ytxml){ $ytxml->asXML('youtube.xml'); }

/* echo friendly error message
if($ytxml === false) {
    echo "<p> Sorry, the current Youtube XML contains some errors </p>";
    foreach(libxml_get_errors() as $error) {
        echo "\t", $error->message;
    }
    echo "<p>You can hit the refresh button to try again </p>";
    echo "<p>Or continue with the old Youtube XML by clicking <a href='http://ngportal.com/opencast/index.php'>here</a></p>";
}
*/


$feedurls = Array('http://media.uct.ac.za/feeds/atom/0.3/latest/index.xml', 'youtube.xml');

foreach($feedurls as $feedurl){
$xml = @simplexml_load_file($feedurl);
    if($feedurl == $feedurls[0]){
      $xml->registerXPathNamespace('atom', 'http://purl.org/atom/ns#');
      $this->urls =  $xml->xpath("/atom:feed/atom:entry/atom:link[attribute::rel='enclosure' and attribute::type='video/avi']/@href");

            //A tweak to display only presentation.mp4
      $newurl = array();
      $v = 0;
        for($y = 0; $y<=count($this->urls); $y++){
	if(substr($this->urls[$y], -16, 16) == "Presentation.mp4"){
	  $newurl[$v] = $this->urls[$y];
	  $v++;
	}
      }//close of for loop
      //overwrite the array urls[]      
	  $this->urls = &$newurl;
      //end of tweak

    }
    else{
      $this->urls = $xml->xpath("/rss/channel/item/link");
     }

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
EOD;
$html2 = <<<EOD
'><video id="video_preview" controls="controls">
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
'>
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
<p>
<div id="view_all_comments">
EOD;

$html10 = <<<EOD
</div>
</p>
</div>
</section>
EOD;
//comment disable the youtube feed - , 'http://www.youtube.com/rss/tag/uct.rss'
//$feedurls = Array('http://media.uct.ac.za/feeds/atom/0.3/latest/index.xml');
$feedurls = Array('http://media.uct.ac.za/feeds/atom/0.3/latest/index.xml', 'youtube.xml');
//$feedurls = Array('http://media.uct.ac.za/feeds/atom/0.3/latest/index.xml', 'http://www.youtube.com/rss/tag/uct.rss');

require('database/connx.php');
// Connect to MySQL
$db = & new MySQL($host,$dbUser,$dbPass,$dbName);

//clear DB
$sql = "DELETE from mediadetails";
$result=$db->query($sql);

foreach($feedurls as $feedurl){

libxml_use_internal_errors(true);
$xml = @simplexml_load_file($feedurl);
if ($xml === false) {
    echo "Sorry, the Youtube XML contains some errors\n";
    foreach(libxml_get_errors() as $error) {
        echo "\t", $error->message;
    }
    echo "You can hit the refresh button to try again\n";
    echo "Or continue without Youtube XML by clicking <a href='http://ngportal.com/opencast/_index.php'>here</a>\n";
}

    //$xml = simplexml_load_file('http://media.uct.ac.za/feeds/rss/2.0/latest/index.rss'); 
    //$xml = simplexml_load_file($feedurl);


    
    if($feedurl == $feedurls[0]){
      $xml->registerXPathNamespace('atom', 'http://purl.org/atom/ns#');
      $titles = $xml->xpath('/atom:feed/atom:entry/atom:title');
      $pubDates = $xml->xpath('/atom:feed/atom:entry/atom:issued');
      $authors = $xml->xpath('/atom:feed/atom:entry/atom:author/atom:name');
      $urls =  $xml->xpath("/atom:feed/atom:entry/atom:link[attribute::rel='enclosure' and attribute::type='video/avi']/@href");
      $this->source = "";
      $this->vdisplayurl = "<a href='viewvideo.php?vid=";
      $this->viewallcommentsurl = "<a href='viewallcomments.php?vid=";

      //A tweak to display only presentation.mp4
      $newurl = array();
      $v = 0;
      // echo count($urls) . "<br>";
      for($y = 0; $y<=count($urls); $y++){
	if(substr($urls[$y], -16, 16) == "Presentation.mp4"){
	  $newurl[$v] = $urls[$y];
	   //echo "urls[" . $y . "]=" . $urls[$y] . "<br>";
	   $v++;
	}
      }//close of for loop
      //overwrite the array urls[]      
      $urls = &$newurl;
      $this->source = "Source: Opencast";
      //end of tweak

 }
    else{
      $authors = $xml->xpath('/rss/channel/item/author');
      $titles = $xml->xpath('/rss/channel/item/title');
      $pubDates = $xml->xpath('/rss/channel/item/pubDate');
      $urls = $xml->xpath("/rss/channel/item/link");
      $this->source = "Source: Youtube";
      $this->vdisplayurl = "<a href='ytvideo.php?vid=";
      $this->viewallcommentsurl = "<a href='viewallcomments.php?vid=";
 }

    $n = 0;

foreach($titles as $title) {

 
//instantiating variables begin
      if(isset($titles[$n])){$this->titles=safeAddSlashes($titles[$n]);}else{$this->titles = " ";}
      if(isset($authors[$n])){$this->authors=safeAddSlashes($authors[$n]);}else{$this->authors = " ";}
      if(isset($pubDates[$n])){$this->pubDates=safeAddSlashes($pubDates[$n]);}else{$this->pubDates = " ";}
      if(isset($urls[$n])){$this->urls=safeAddSlashes($urls[$n]);}else{$this->urls = " ";}
       //echo "url[".$n."]=". $urls[$n] . "<p>";
       //echo "url[".$n."]=". $this->urls . "<p>";

      //get comment size
      $this->no_of_comments = medialist::commentsize($this->urls);

      //echo $this->no_of_comments ."<p>";
 //set comment-only link
      $this->view_all_comments = $this->viewallcommentsurl . $this->urls . "#allcomments' id='back'  style='text-decoration : none; -webkit-border-radius: 5px; -moz-border-radius: 5px; border-radius: 5px;'>View All Comments</a>";

      if(isset($this->authors)){
	//echo $html1 . $urls[$n] . $html2 . $urls[$n] . $html3 . $urls[$n] . $html4 . $urls[$n]. $html5 . $titles[$n] . $html6 . $authors[$n] . $html7 . $pubDates[$n] . $html8 . $no_of_comments . $html9;
	echo $html1 . $this->vdisplayurl . $urls[$n] . $html2 . $urls[$n] . $html3  . $urls[$n] . $html4 . $this->vdisplayurl . $urls[$n]. $html5 . $this->titles . $html6 . $this->authors . $html7 . $this->pubDates . $html8 . $this->no_of_comments . $this->source . $html9 . $this->view_all_comments . $html10;
}
      else{      	
	//echo $html1 . $urls[$n] . $html2 . $urls[$n] . $html3 . $urls[$n] . $html4 . $urls[$n] . $html5 . $titles[$n] . $html6 . $html7 . $pubDates[$n] . $html8 .  $no_of_comments . $html9;
	echo $html1 . $this->displayurl . $urls[$n] . $html2 . $urls[$n] . $html3 . $urls[$n] . $html4 . $this->displayurl . $urls[$n] . $html5 . $this->titles . $html6 . $html7 . $this->pubDates . $html8 .  $this->no_of_comments . $this->source . $html9 . $this->view_all_comments . $html10;
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
$this->lead_comment_author = safeAddSlashes($_POST['lead_comment_author']);
$this->msg_type = $_POST['msg_type'];
if(isset($_POST['vid'])){$this->presentation_url = safeAddSlashes($_POST['vid']);}else{$this->presentation_url = safeAddSlashes($_GET['vid']);}
$sql="INSERT comments SET
str_presentation_url='$this->presentation_url',
str_name='$this->comment_author',
str_lead_name='$this->lead_comment_author',
time_pubDate='$this->pubDate',
str_comment='$this->comment',
str_msg_type='$this->msg_type'";
//echo "<p>" . $sql . "</p>";
$result=$db->query($sql);

  }//end of savecomments function

  function displaycomments(){
if(isset($_POST['vid'])){$this->presentation_url = safeAddSlashes($_POST['vid']);}else{$this->presentation_url = safeAddSlashes($_GET['vid']);}
if(isset($_POST['lead_comment_author'])){$this->lead_comment_author = safeAddSlashes($_POST['lead_comment_author']);}else{$this->lead_comment_author = safeAddSlashes($_GET['lead_comment_author']);}
if(isset($_POST['comment_author'])){$this->comment_author = safeAddSlashes($_POST['comment_author']);}else{$this->comment_author = safeAddSlashes($_GET['comment_author']);}
//database connection begins
require('database/connx.php');

$db = & new MySQL($host,$dbUser,$dbPass,$dbName);
$sql1="select str_name,str_comment,time_pubdate,str_msg_type from comments where str_presentation_url='" . $this->presentation_url . "' and str_msg_type='Posted'" ;
$result1=$db->query($sql1);
$this->strcommenthtml3 = "";
$this->strcommenthtml1 =  "<div><div name='no_of_comments'>" . $result1->size() . " comments</div>";
while ($row1 = $result1->fetch()) {
  if($row1['str_msg_type'] == 'Posted'){$this->strcommenthtml2 =  "<div id='msg_type_posted'>";}
  else{$this->strcommenthtml2 =  "<div id='msg_type_reply'>";};
  $this->strcommenthtml3 .= $this->strcommenthtml2 .  "<span id='db_comment'>" . $row1['str_comment'] . "</span><br/>" . "<span id='db_comment_author'>" . $row1['str_msg_type'] . " by " . $row1['str_name'] . " on " . $row1['time_pubdate'] . "</span> <span id='reply'><a href='#postcomment' onclick=\"postcomment('reply', '" . $row1['str_name'] . "');\">Reply</a></span></div>";

//comment out the reply link
//$this->strcommenthtml3 .= $this->strcommenthtml2 .  "<span id='db_comment'>" . $row1['str_comment'] . "</span><br/>" . "<span id='db_comment_author'>" . $row1['str_msg_type'] . " by " . $row1['str_name'] . " on " . $row1['time_pubdate'] . "</span></div>";

//for each comment, display corresponding replies
$sql2="select str_name,str_comment,time_pubdate,str_msg_type from comments where str_lead_name = '" . $row1['str_name'] . "' and str_presentation_url='" . $this->presentation_url . "' and str_msg_type='Replied'";
//echo $sql2 . "<p>";
$result2=$db->query($sql2);
$this->strcommenthtml5 = "";
$this->strcommenthtml_1 =  "<div>";
while ($row2 = $result2->fetch()) {
  if($row2['str_msg_type'] == 'Replied'){$this->strcommenthtml6 =  "<div id='msg_type_reply'>";}
  else{$this->strcommenthtml7 =  "<div id='msg_type_posted'>";};
  $this->strcommenthtml5 .= $this->strcommenthtml6 .  "<span id='db_comment'>" . $row2['str_comment'] . "</span><br/>" . "<span id='db_comment_author'>" . $row2['str_msg_type'] . " by " . $row2['str_name'] . " on " . $row2['time_pubdate'] . "</span> <span id='reply'><a href='#postcomment' onclick=\"postcomment('reply', '"   . $row1['str_name'] .  "');\">Reply</a></span></div>";

//comment out the reply link
//$this->strcommenthtml5 .= $this->strcommenthtml6 .  "<span id='db_comment'>" . $row2['str_comment'] . "</span><br/>" . "<span id='db_comment_author'>" . $row2['str_msg_type'] . " by " . $row2['str_name'] . " on " . $row2['time_pubdate'] . "</span></div>";
    }
$this->strcommenthtml4 = "</div>";
$this->strcommenthtml3 .= $this->strcommenthtml_1 . $this->strcommenthtml5 . $this->strcommenthtml4;
//end of display of replies
    }
$this->strcommenthtml4 = "</div>";
echo $this->strcommenthtml1 . $this->strcommenthtml3 . $this->strcommenthtml4;

  }//end of displaycomments function

  function commentsize($vid){
//get the exact youtube URL used to save comments
$this->strvid =substr($vid, 0, 22);
if($this->strvid == "http://www.youtube.com"){$vid = substr($vid, 0, 42);}

$this->presentation_url = safeAddSlashes($vid);
//database connection begins
require('database/connx.php');

$db = & new MySQL($host,$dbUser,$dbPass,$dbName);
$sql="select str_name,str_comment,time_pubdate,str_msg_type from comments where str_presentation_url='" . $this->presentation_url . "'";
$result=$db->query($sql);
return "<div><div name='no_of_comments'>" . $result->size() . " comments</div>";
}//end of commentsize


    function gettitle($vid){
$this->presentation_url = safeAddSlashes($vid);
	//append "&feature=youtube_gdata" to the youtube URL in order to fetch its title
	$this->strvid = substr($vid, 0, 22);
	if($this->strvid == "http://www.youtube.com"){
	$this->strvid = substr($vid, 0, 42);
	$this->presentation_url = $this->strvid . "&feature=youtube_gdata";
		}

//database connection begins
require('database/connx.php');

$db = & new MySQL($host,$dbUser,$dbPass,$dbName);
$sql="select str_title from mediadetails where str_url='" . $this->presentation_url . "'";

$result=$db->query($sql);
$row = $result->fetch();
return $row['str_title'];
}//end of gettitle



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
