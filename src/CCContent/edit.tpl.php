<?php
	if($content['created']) {
		echo "<h1>Edit Content</h1><p>Here you can edit this content</p>";
	} else {
		echo "<h1>Create Content</h1><p>Create new content to the page</p>";
	}
	if($user['isAuthenticated']) {
		echo $form->GetHTML();
	} else {
		echo "<p class='error'>Please login to create or edit content</p>";
	}
?>

<p><a href='<?=create_url('content')?>'>See all content</a></p>