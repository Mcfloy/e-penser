var xhr = new XMLHttpRequest();
xhr.open("GET", "https://www.googleapis.com/youtube/v3/search?order=date&part=id&channelId=UCcziTK2NKeWtWQ6kB5tmQ8Q&maxResults=5&key=AIzaSyBCt5jbexNc1YbPTx5odURRdOVoxCbgFcc", false);
xhr.send();

if (xhr.readyState == 4 && xhr.status == 200) {
	var uploads = JSON.parse(xhr.responseText);
} else {
	console.log("Error on receiving e-penser's uploads !");
}

if (uploads !== undefined) {
	var firstItem = uploads.items[0];
	console.log(firstItem);
	var xhrVideo = new XMLHttpRequest();
	xhrVideo.open("GET", "https://www.googleapis.com/youtube/v3/videos?part=snippet&id=" + firstItem.id.videoId + "&key=AIzaSyBCt5jbexNc1YbPTx5odURRdOVoxCbgFcc", false);
	xhrVideo.send();
	if (xhrVideo.readyState == 4 && xhrVideo.status == 200) {
		var videoInformation = JSON.parse(xhrVideo.responseText);
		console.log(videoInformation);
		$('#title').html(videoInformation.items[0].snippet.title);
		$('#description').html(videoInformation.items[0].snippet.description.replace(/\n/g, "<br/>").replace(/\b(?:https?|ftp):\/\/[a-z0-9-+&@#\/%?=~_|!:,.;]*[a-z0-9-+&@#\/%=~_|]/gim, '<a href="$&" target="_blank">$&</a>'));
	} else {
		console.log("Error on receiving the lastest video informations !");
	}
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
		videoId: videoInformation.items[0].id,
		events: {
			'onReady': onPlayerReady,
			'onStateChange': onPlayerStateChange
		}
		controls: 2,
		rel: 0,
		showinfo: 0
	});
}

function onPlayerReady(event) {
}