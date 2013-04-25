<!DOCTYPE html>
<html lang="sv">
<head>
	<meta charset="utf-8">
	<title><?=$title?></title>
	<link rel="stylesheet" href="<?=$stylesheet?>">
</head>
<body>
<header id="above">
	<nav id="abovemenu"> 
		<?=getMainNav()?>
		<span class="right">
			<?=login_menu()?>
		</span>
	</nav>
</header> 
<header id="top">
	<img class='logo' src='<?=$logo?>' alt='logo' width='<?=$logo_width?>' height='<?=$logo_height?>' />
	<?=$header?>
</header>
<div id="content">
<article id="main">
	<?=get_messages_from_session()?>
	<?=@$main?>
	<?=render_views()?>
	<?=get_debug()?>
</article>
</div>
<footer id="bottom">
	<?=$footer?>
</footer>
</body>
</html>