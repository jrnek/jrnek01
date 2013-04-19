<h1>jrnek</h1>
<h2>This is what you can do</h2>
<?php
	$html ="<ul>";
	foreach($menu as $val) {
		$html .= "<li><a href=" . $val .">" . $val . "</a></li>";
	}
	echo $html . "</ul>";
?>