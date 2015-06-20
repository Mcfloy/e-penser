var xhr = new XMLHttpRequest();
xhr.open("GET", "https://www.googleapis.com/youtube/v3/search?order=date&part=snippet&channelId=UCcziTK2NKeWtWQ6kB5tmQ8Q&maxResults=5&key=AIzaSyBCt5jbexNc1YbPTx5odURRdOVoxCbgFcc", false);
xhr.send();

if (xhr.readyState == 4 && xhr.status == 200) {
	var json = JSON.parse(xhr.responseText);
} else {
	console.log("Error on receiving youtube informations !");
}

if (json !== undefined) {
	var lastVideo = json.items[0];
	console.log(lastVideo);
	$('#title').html(lastVideo.snippet.title);
	$('#description').html(lastVideo.snippet.description);
}

var tag = document.createElement('script');
tag.src = "https://www.youtube.com/iframe_api";
var firstScriptTag = document.getElementsByTagName('script')[0];
firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);

var player;
function onYouTubeIframeAPIReady() {
	player = new YT.Player('player', {
		height: '641',
		width: '1140',
		videoId: lastVideo.id,
		events: {
			'onReady': onPlayerReady,
			'onStateChange': onPlayerStateChange
		}
	});
}

function onPlayerReady(event) {
}