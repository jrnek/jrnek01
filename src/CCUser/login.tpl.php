<h1>User Login</h1>
<p>Default users</p>
<ul>
<li>admin:admin</li>
<li>testuser:test01</li>
</ul>
<p><?=$login_form->GetHTML(false)?></p>
<fieldset>
	<?=$login_form['acronym']->GetHTML()?>
	<?=$login_form['password']->GetHTML()?>
	<?=$login_form['login']->GetHTML()?>
	<?php
		if($allow_create_user) {
			echo "<p class='form-action-link'><a href='{$create_user_url}' title='Create a new user account'>Create user</a></p>";
		}
	?>
</fieldset>
</form>