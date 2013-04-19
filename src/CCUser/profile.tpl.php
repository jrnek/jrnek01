<h1>User Profile</h1>
<?php if($is_auth): ?>
	<p>User is authenticated.</p>
	<?=$profile_form?>
<?php else: ?>
	<p>User is anonymous and not authenticated.</p>
<?php endif; ?>