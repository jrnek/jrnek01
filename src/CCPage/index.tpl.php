<?php
	if(isset($pages)) {
		echo "<h1>Page Controller</h1><p>Here is all pages</p><ul>";
		foreach($pages as $val) {
			$link = create_url("page/view/{$val['id']}");
			echo "<li><a href='{$link}'>{$val['title']}</a></li>";
		}
		echo "</ul>";
	} elseif(isset($content)) {
		echo "<h1>{$content['title']}</h1>";
		echo "<p>{$content->GetFiltredData()}</p>";
		echo "<p><em>This was created on {$content['created']} by {$content['owner']}</em></p>";

	}