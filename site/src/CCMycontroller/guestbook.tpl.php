<h1>Guestbook</h1>
<p><?=$form->GetHTML()?></p>

<h2>Entries</h2>
<?php
	foreach($entries as $val) {
		echo '<div class="info">';
		echo "<p>{$val['entry']}</p><p>Created: {$val['created']}</p>";
		echo "</div>";
	}
