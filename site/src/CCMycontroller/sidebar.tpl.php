
<?php
	$html = "<h1>Links</h1><ul>";
	foreach($links as $val) {
		if($val->name != '__construct') {
			$url = create_url('mycontroller', $val->name);
			$html .= "<li><a href='{$url}'>{$val->name}</a></li>";
		}
	}
	echo $html . "</ul>";
