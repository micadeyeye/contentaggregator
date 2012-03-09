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
<div>

<?php
class searchformedia {	  
//constructor function
function searchformedia(){

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



//database connection begins

// Include the MySQL class
require_once('database/MySQL.php');

require('database/connx.php');
require('database/strip_quotes.php');

// Connect to MySQL
$db = & new MySQL($host,$dbUser,$dbPass,$dbName);
$searchterm=$_POST['searchterm'];
$this->searchterm=$searchterm;
/*
$p = 0;
$n = 6;
$this->n = $n;
$sql= "select str_title, match(str_title) against('".$searchterm."') as relevance from mediadetails where match(str_title) against('".$searchterm."') LIMIT $p,$this->n";
$sql= "select str_title, str_author, time_pubDate, str_url, match(str_title, str_author) against('".$searchterm."') as relevance from mediadetails where match(str_title, str_author, time_pubDate, str_url) against('".$searchterm."')";
*/
$sql= "select str_title,str_author,time_pubDate,str_url, match(str_title) against('".$searchterm."') as relevance from mediadetails where match(str_title,str_author,time_pubDate,str_url) against('".$searchterm."')";
$result=$db->query($sql);


echo "<span class='fontstyle1'><strong>Search Result</strong></span><hr class='dashline' width='100%' size='1'>";
	  while($row=$result->fetch()){
             if(isset($row['str_author'])){
	       //ignore $no_of_comments between $html7 and $html8 for both echos
	       echo $html1 . $row['str_url'] . $html2 . $row['str_url'] . $html3 . $row['str_url'] . $html4 . $row['str_title'] . $html5 . $row['str_author'] . $html6 . $row['time_pubDate'] . $html7 . $html8;}
      else{      	
	echo $html1 . $row['str_url'] . $html2 . $row['str_url'] . $html3 . $row['str_url'] . $html4 . $row['str_title'] . $html5 . $html6 . $row['time_pubDate'] . $html7 . $html8;
}
// echo $html1 . $row['str_url'] . $html2 . $row['str_url'] . $html3 . $row['str_url'] . $html4 . $row['str_title'] . $html5 . $row['time_pubDate'] . $html6;
 
}

   }//close of function searchformedia
   
   
}//end of searchformedia
   
new searchformedia();    
 	  
?>


</div>
</div>
</body>
</html>
