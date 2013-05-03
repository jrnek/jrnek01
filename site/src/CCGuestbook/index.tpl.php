<h1>Guestbook Example</h1>
<h2>Current Messages</h2>
<?php
	$html ="";
	foreach($entries as $val) {
		$html .= "<p><article id='entry'>{$val['entry']}</p><p><hr><em>{$val['created']}</em></p></article>";
	}
	echo $html;
?>