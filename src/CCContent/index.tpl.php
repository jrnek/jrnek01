<h1>Content Controller</h1>
<p>Here is all the content</p>
<?php
	if($content == null) {
		echo "<p>No content to show</p>";
	} else {
		echo "<ul>";
		foreach($content as $val) {
			$link  = create_url('content', 'edit', $val['id']);
			echo "<li>{$val['title']} created by: {$val['owner']} <a href='{$link}'>edit</a></li>";
		}
		echo "</ul>";
	}
?>
<p>Actions:</p>
<ul>
	<li><a href='<?=create_url('content/init')?>'>Init database</a></li>
	<li><a href='<?=create_url('content/create')?>'>Create</a></li>
</ul>
