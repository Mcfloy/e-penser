var xhr = new XMLHttpRequest();
xhr.open("GET", "https://www.googleapis.com/youtube/v3/search?order=date&part=snippet&channelId=UCcziTK2NKeWtWQ6kB5tmQ8Q&maxResults=50&key=AIzaSyBCt5jbexNc1YbPTx5odURRdOVoxCbgFcc", false);
xhr.send();
console.log(xhr.status);
console.log(xhr.responseText);

var tag = document.createElement('script');
tag.src = "https://www.youtube.com/iframe_api";
var firstScriptTag = document.getElementsByTagName('script')[0];
firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);

var player;
function onYouTubeIframeAPIReady() {
	player = new YT.Player('player', {
		height: '641',
		width: '1140',
		videoId: 'BTaPtvxa_Uo',
		events: {
			'onReady': onPlayerReady,
			'onStateChange': onPlayerStateChange
		}
	});
}

function onPlayerReady(event) {
	event.target.playVideo();
}

var done = false;
function onPlayerStateChange(event) {
	if (event.data == YT.PlayerState.PLAYING && !done) {
		setTimeout(stopVideo, 6000);
		done = true;
	}
}
function stopVideo() {
	player.stopVideo();
}