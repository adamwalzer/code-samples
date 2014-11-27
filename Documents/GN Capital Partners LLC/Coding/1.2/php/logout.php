<?php
	session_start();
	session_destroy();
	
	$outputtext .= "
		<div>
			<script>
				changeURLVariable('loc_id=false&rest_id=false',{'reload':true});
			</script>
		</div>
		";
?>