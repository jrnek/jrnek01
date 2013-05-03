<?php
	$html = "<h1>Blog</h1><h2>Entries</h2>";
	foreach($contents as $val) {
		$html .= "<p class='right'>Created {$val['created']} by {$val['owner']}</p>";
		$html .= "<p><strong>{$val['title']}</strong></p>";
		$html .= "<p>{$val['data']}</p><hr>";
		
	}
	echo $html;
?>