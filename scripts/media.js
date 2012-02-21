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
    alert(values);
}
function clearfield(){
if(document.getElementById("searchterm").value == '&nbsp;Search') {document.getElementById("searchterm").value = ''};
}
function postcomment(){
var divcontent = document.getElementById('commentfield');
divcontent.innerHTML = "Name:<br/><input type='text' size='30' name='comment_author'/> <p> Message:<br/><textarea rows='14' cols='38' name='comment'></textarea><br/><input type='submit' name='submit' value='Submit'/><input type='hidden' name='msg_type' value='Posted'/></p>";
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