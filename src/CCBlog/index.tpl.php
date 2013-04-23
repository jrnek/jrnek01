<h1>The Blog</h1>
<p>Here is all the content marked as 'post'</p>

<?php 
	foreach($contents as $val) {
		$editLink = create_url("content/edit/{$val['id']}");
		$data = filter_data($val['data'], $val['filter']);
		echo "<article id='entry'>";
		echo "<em class='right'>Posted on: {$val['created']} by {$val['owner']}</em>";
		echo "<h2>{$val['title']}</h2>";
		echo "<p>{$data}</p>";
		echo "<p><a href='{$editLink}'>Edit</a></p>";
		echo "</article>";
	}
?>