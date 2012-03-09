      // 2. This code loads the IFrame Player API code asynchronously.
      var tag = document.createElement('script');
      tag.src = "http://www.youtube.com/player_api";
      var firstScriptTag = document.getElementsByTagName('script')[0];
      firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);

      // 3. This function creates an <iframe> (and YouTube player)
      //    after the API code downloads.
      var player;
      function onYouTubePlayerAPIReady() {
        player = new YT.Player('player', {
          height: '240',
          width: '320',
          videoId: 'u1zgFlCw8Aw',
          events: {
            'onReady': onPlayerReady,
            'onStateChange': onPlayerStateChange
          }
        });
      }

      // 4. The API will call this function when the video player is ready.
      function onPlayerReady(event) {
        event.target.playVideo();
      }

      // 5. The API calls this function when the player's state changes.
      //    The function indicates that when playing a video (state=1),
      //    the player should play for six seconds and then stop.
      var done = false;
      function onPlayerStateChange(event) {
        if (event.data == YT.PlayerState.PLAYING && !done) {
          setTimeout(stopVideo, 0);
          done = true;
        }
      }
      function stopVideo() {
        player.stopVideo();
      }
function getValuesfromopencast(){
    objOpencastvalues = new hiddenInputElement();
    values = objOpencastvalues.getHiddenElementString();
   //alert(values);
}
function clearfield(){
if(document.getElementById("searchterm").value == '&nbsp;Search') {document.getElementById("searchterm").value = ''};
}
function postcomment(){
var divcontent = document.getElementById('commentfield');
    divcontent.innerHTML = "Name:<br/><input type='text' size='30' name='comment_author'/> <p> Message:<br/><textarea rows='14' cols='38' name='comment'></textarea><br/><input type='button' name='submit' value='Submit' onclick='submitForm();'/><input type='hidden' name='msg_type' value='Posted'/></p>";
}



function WriteToDB(url, content)	// url is the script and data is a string of parameters
	{ 
		var xhr = createXHR();

		xhr.onreadystatechange=function()
		{ 
			if(xhr.readyState == 4)
			{
			    if(xhr.status == 200 || xhr.status == 0)
				{
					//append the new comment and the link "add comment here"
					var doc = xhr.responseText;
				    var divcontent = document.getElementById('commentfield');
				    divcontent.innerHTML = doc + "<br/><div id='write_comment'><a href='#postcomment' onclick='postcomment();'>Write a Comment</a></div>";
					//var element = doc.getElementsByTagName('root').item(0);
					//document.ajax.dyn.value= element.firstChild.data;

				}	
				else	
				{
					//document.ajax.dyn.value="Error: returned status code " + xhr.status + " " + xhr.statusText;
				}	
			}
		}; 
	    //this is a synchronous call; wait for response
		xhr.open("POST", url, false);		
		xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		xhr.send(content); 
	} 


function submitForm()
	{ 
		var strcomment = document.commentform.comment.value;
		var strcomment_author = document.commentform.comment_author.value;
	    var strpresentation_url = document.commentform.presentation_url.value;
	    var strmsg_type = document.commentform.msg_type.value;
	//write comment to DB
	WriteToDB("http://www.ngportal.com/opencast/main.php", "submit=true&comment=" + strcomment + "&comment_author=" + strcomment_author + "&vid=" + strpresentation_url + "&msg_type=" + strmsg_type);
	    //retrieve comments
	    WriteToDB("http://www.ngportal.com/opencast/main.php", "getcomments=true&vid=" + strpresentation_url);

	    //var storing = document.getElementById("storage");
		//storing.innerHTML = "<p>Now you can view what you sent in the file <b><a href='ajax-post-text.txt' target='_parent'>ajax-post-text.txt</a></b>. ";
	} 



function hiddenInputElement(){};
				hiddenInputElement.prototype = {
							inputStr: '',
							getHiddenElementString: function (){
										//var someXMLDoc = content.document; - commented by Mike
							    var someXMLDoc = document.location("http://media.uct.ac.za/feeds/rss/2.0/latest");
							    //var hiddenInput = someXMLDoc.evaluate("//input[@type='hidden']", someXMLDoc, null, XPathResult.ANY_TYPE,null); - commented by Mike
	  									var titleInfo = document.evaluate("//title", someXMLDoc, null, XPathResult.ANY_TYPE,null);
	  									var hiddenInput = document.evaluate("//enclosure[@type='video/avi']", someXMLDoc, null, XPathResult.ANY_TYPE,null);
										var thisHiddenInput = hiddenInput.iterateNext();

										while (thisHiddenInput) {
											//inputName = thisHiddenInput.name; - commented by Mike
											//inputId = thisHiddenInput.id; - commented by Mike
											//first encode the input value
										    //inputValue = encodeURIComponent(thisHiddenInput.value); - commented by Mike
											inputUrl = thisHiddenInput.url;
											
											/* Input Concatenation */
											eachHiddenInput = "<input type=\"hidden\" name = \"" + inputName + "\" id = \"" + inputId + "\">" + inputValue + "</input>";
											this.inputStr = this.inputStr + eachHiddenInput;

											thisHiddenInput = hiddenInput.iterateNext();
										}


var thistitleInfo = titleInfo.iterateNext();

										while (thistitleInfo) {
											//inputName = thistitleInfo.name; - commented by Mike
											//inputId = thistitleInfo.id; - commented by Mike
											//first encode the input value
										    //inputValue = encodeURIComponent(thistitleInfo.value); - commented by Mike
											inputTitle = thistitleInfo;
											
											/* Input Concatenation */
											eachtitleInfo = "<input type=\"hidden\" name = \"" + inputName + "\" id = \"" + inputId + "\">" + inputTitle + "</input>";
											this.inputStr = this.inputStr + eachtitleInfo;

											thistitleInfo = titleInfo.iterateNext();
										}


										return this.inputStr;
											} 

								};