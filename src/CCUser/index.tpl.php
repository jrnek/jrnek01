<h1>User Profile</h1>
<p>This is what is known on the current user.</p>

<?php if($is_auth): ?>
	<p>User is authenticated.</p>
	<pre><?=print_r($user, true)?></pre>
	<?php else: ?>
	<p>User is anonymous and not authenticated.</p>
<?php endif; ?>