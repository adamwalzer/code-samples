<?php
	
$outputtext .= "
				<div id='socialmenu'>
					<script>(function(d, s, id) {
					  var js, fjs = d.getElementsByTagName(s)[0];
					  if (d.getElementById(id)) return;
					  js = d.createElement(s); js.id = id;
					  js.src = '//connect.facebook.net/en_US/all.js#xfbml=1';
					  fjs.parentNode.insertBefore(js, fjs);
					}(document, 'script', 'facebook-jssdk'));</script>
					<div class='fb-like' data-href='https://www.facebook.com/Gottanom' data-layout='button_count' data-action='like' data-show-faces='false' data-share='true'></div>
					
					<a href='https://twitter.com/gottanom' class='twitter-follow-button' data-show-count='false' data-show-screen-name='false'></a>
					<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^https:/.test(d.location)?'https':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>
				</div>
				";

?>