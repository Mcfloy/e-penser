<!DOCTYPE html>
<html>
<head>
	<title>e-penser.com</title>
	<meta http-equiv="Content-Type" content="text/html;charset=utf-8">
	<link rel="stylesheet" type="text/css" href="css/index.css">
	<link rel="stylesheet" type="text/css" href="css/font-awesome.css">
	<link rel="stylesheet" type="text/css" href="css/owl.carousel.css">
	<link rel="stylesheet" type="text/css" href="css/owl.theme.css">

	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
	<script src="js/owl.carousel.js"></script>
</head>
<body>
	<div id='header_background'>
		<div id='header_content'>
			<div id="logo">
				<a href="/">
					<img src="img/e-penser.jpeg"/>
					<h1>e-penser</h1>
				</a>
			</div>
			<div id="nav">
				<ul>
					<li>
						<a target="_blank" href="https://www.youtube.com/user/epenser1">
							<i class="fa fa-youtube"></i>
						</a>
					</li>
					<li>
						<a target="_blank" href="https://www.facebook.com/epenser">
							<i class="fa fa-facebook"></i>
						</a>
					</li>
					<li>
						<a target="_blank" href="https://twitter.com/epenser">
							<i class="fa fa-twitter"></i>
						</a>
					</li>
					<li>
						<a target="_blank" href="https://plus.google.com/+Epenser1">
							<i class="fa fa-google-plus"></i>
						</a>
					</li>
					<li>
						<a target="_blank" href="https://www.tipeee.com/e-penser">
							<img src="img/tipeee.png">
						</a>
					</li>
				</ul>
			</div>
		</div>
	</div>
	<?php
		$page = file_get_contents("https://gdata.youtube.com/feeds/api/videos?author=epenser1&max-results=50&alt=json&orderby=published&v=2");
		$json = json_decode($page);

		$entry = $json->{"feed"}->{"entry"};
		$background = str_replace("tag:youtube.com,2008:video:", "", $entry[0]->{"id"}->{"\$t"});
		$carousel = "";
 
		$total_results = $json->{"feed"}->{"openSearch\$totalResults"}->{"\$t"};
		$item_per_page = $json->{"feed"}->{"openSearch\$itemsPerPage"}->{"\$t"};

		if ($total_results <= $item_per_page) {
			foreach ($entry as $k) {
				$carousel .="<div class='item'><img src='".$k->{"media\$group"}->{"media\$thumbnail"}[1]->{"url"}."'/><p onclick='load_video(\"".str_replace("tag:youtube.com,2008:video:", "", $k->{"id"}->{"\$t"})."\")'>".$k->{"title"}->{"\$t"}."</p></div>";
			}
		} else {
			$i = 1;
			$cpt = 0;
			while ($i < $total_results) {
				$page2 = file_get_contents("https://gdata.youtube.com/feeds/api/videos?author=epenser1&max-results=50&start-index=".(($cpt * 50) + 1)."&alt=json&orderby=published&v=2");
				$json2 = json_decode($page2);
				$entry2 = $json2->{"feed"}->{"entry"};
				foreach ($entry2 as $k) {
					$i++;
					$carousel .="<div class='item'><img src='".$k->{"media\$group"}->{"media\$thumbnail"}[1]->{"url"}."'/><p onclick='load_video(\"".str_replace("tag:youtube.com,2008:video:", "", $k->{"id"}->{"\$t"})."\")'>".$k->{"title"}->{"\$t"}."</p></div>";
				}
				$cpt++;
			}
		}
	?>
	<div id='video_background' style='background: url("https://img.youtube.com/vi/<?php echo $background; ?>/maxresdefault.jpg"); background-repeat: no-repeat; background-size: cover; background-position: center center'>
		<div id='video_content'>
			<div id="video_player"></div>
			<script type="text/javascript">
				var tag = document.createElement('script');

				tag.src = "https://www.youtube.com/iframe_api";
				var firstScriptTag = document.getElementsByTagName('script')[0];
				firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);

				var player;
				function onYouTubeIframeAPIReady() {
					player = new YT.Player('video_player', {
						height: '720',
						width: '1280',
						videoId: "<?php echo $background; ?>",
						playerVars: {
							'controls': 1,
							'rel': 0,
							'showinfo': 0
						}
					});
				}
			</script>
		</div>
	</div>
	<div id='body_background'>
		<div id='body_content'>
			<button onclick='$("#description").toggle("slow")'>Voir la description</button>
			<div id="description">
				<?php
					$flux = file_get_contents("http://gdata.youtube.com/feeds/api/videos/".$background."?v=2&alt=json");
					$json2 = json_decode($flux);
					$string = nl2br($json2->{"entry"}->{"media\$group"}->{"media\$description"}->{"\$t"});
					$string = preg_replace('/https?:\/\/[\w\-\.!~#?&=+\*\'"(),\/]+/','<a href="$0" target="_blank">$0</a>',$string);
					echo $string;
				?>
			</div>
			<div id="carousel">
				<?php echo $carousel; ?>
			</div>
			<script>
			$(document).ready(function() {
				$("#carousel").owlCarousel({
					items : <?php echo $json->{"feed"}->{"openSearch\$totalResults"}->{"\$t"}; ?>,
					itemsDesktop : [2000, 4],
					navigation : false,
					responsive: true,
				});
			});

			function nl2br (str, is_xhtml) {   
			    var breakTag = (is_xhtml || typeof is_xhtml === 'undefined') ? '<br />' : '<br>';    
			    return (str + '').replace(/([^>\r\n]?)(\r\n|\n\r|\r|\n)/g, '$1'+ breakTag +'$2');
			}

			function load_video(id) {
				player.loadVideoById(id);
				document.getElementById('video_background').style.backgroundImage = 'url(https://img.youtube.com/vi/'+id+'/maxresdefault.jpg)';
				var xhr = new XMLHttpRequest();

				xhr.onreadystatechange = function() {
					if (xhr.readyState == 4 && xhr.status == 200) {
						var json_decoded = JSON.parse(xhr.responseText),
							description_content = nl2br(json_decoded.entry.media$group.media$description.$t);
						document.getElementById('description').innerHTML = description_content.replace(/(\b(https?|ftp):\/\/[-A-Z0-9+&@#\/%?=~_|!:,.;]*[-A-Z0-9+&@#\/%=~_|])/gim, '<a href="$1" target="_blank">$1</a>');

					}
				}

				xhr.open("GET", "https://gdata.youtube.com/feeds/api/videos/" + id + "?v=2&alt=json", false);
				xhr.send();
			}
			</script>
		</div>
	</div>
	<div id='footer_background'>
		<div id='footer_content'>
			<p>Fansite codé par <a href="https://www.github.com/Celousco" target="_blank">Mcfloy</a>, tout droits à <a href="http://www.e-penser.com" target="_blank">e-penser</a></p>
		</div>
	</div>
</body>
</html>