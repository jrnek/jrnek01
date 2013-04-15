<h1>Guestbook Example</h1>
<p>Guestbook example with jrnek Framwork</p>

<form method="post" action="<?=$action?>">
	<p><label>Comment:</label></p>
	<p><textarea name="entry"></textarea></p>
<p>
	<input type="submit" name="postEntry" value="Post comment" />
	<input type="submit" name="clear" value="Clear comments" />
	<input type="submit" name="create" value="Create database" />
</p>
</form>

<h2>Current Messages</h2>
<?php
	$html ="";
	foreach($entries as $val) {
		$html .= "<p><article id='entry'>{$val['entry']}</p><p><hr><em>{$val['created']}</em></p></article>";
	}
	echo $html;
?>