<!DOCTYPE html>
<html lang="sv">
<head>
	<meta charset="utf-8">
	<title><?=$title?></title>
	<link rel='shortcut icon' href='<?=theme_parent_url('img/' . $favicon)?>'/>
	<link rel="stylesheet" href="<?=theme_url($stylesheet)?>">
	<?php if(isset($inline_style)): ?><style><?=$inline_style?></style><?php endif?>
</head>
<body>

<div id="outer-wrap-header">
	<div id="inner-wrap-header">
		<div id="header">
			<a href='<?=base_url()?>'><img id='logo' src='<?=theme_parent_url('img/'. $logo)?>' alt='logo' width='<?=$logo_width?>' height='<?=$logo_height?>' /></a>
			<?=login_menu()?>
			<h1 id='title'><?=$header?></h1>
			<nav id='head-nav'><?=render_views('navbar')?></nav>
		</div>
	</div>
</div>

<?php if(region_has_content('flash')): ?>
<div id="outer-wrap-flash">
	<div id="inner-wrap-flash">
		<div id="flash">
			<?=render_views('flash')?>
		</div>
	</div>
</div>
<?php endif; ?>

<?php if(region_has_content('featured-first', 'featured-middle', 'featured-last')): ?>
<div id="outer-wrap-featured">
	<div id="inner-wrap-featured">
		<div id="featured">
			<div id="featured-first">
				&nbsp;<?=render_views('featured-first')?>
			</div>
			<div id="featured-middle">
				&nbsp;<?=render_views('featured-middle')?>
			</div>
			<div id="featured-last">
				&nbsp;<?=render_views('featured-last')?>
			</div>
		</div>
	</div>
</div>
<?php endif; ?>

<?php if(region_has_content('primary', 'secondary')): ?>
<div id="outer-wrap-main">
	<div id="inner-wrap-main">
		<div id="main">
			<div id="primary">
				<?=get_messages_from_session()?>
				&nbsp;<?=render_views('primary')?> 
			</div>
			<div id="secondary">
				&nbsp;<?=render_views('secondary')?>
			</div>
		</div>
	</div>
</div>
<?php endif; ?>

<?php if(region_has_content('triptych-first', 'triptych-middle', 'triptych-last')): ?>
<div id="outer-wrap-triptych">
	<div id="inner-wrap-triptych">
		<div id="triptych">
			<div id="triptych-first">
				&nbsp;<?=render_views('triptych-first')?>
			</div>
			<div id="triptych-middle">
				&nbsp;<?=render_views('triptych-middle')?>
			</div>
			<div id="triptych-last">
				&nbsp;<?=render_views('triptych-last')?>
			</div>
		</div>
	</div>
</div>
<?php endif; ?>

<div id="outer-wrap-bottom">
<?php if(region_has_content('footer-one', 'footer-two', 'footer-three', 'footer-four')): ?>
<div id="inner-wrap-footer">
	<div id="footer">
		<div id="footer-one">
			&nbsp;<?=render_views('footer-one')?>
		</div>
		<div id="footer-two">
			&nbsp;<?=render_views('footer-two')?>
		</div>
		<div id="footer-three">
			&nbsp;<?=render_views('footer-three')?>
		</div>
		<div id="footer-four">
			&nbsp;<?=render_views('footer-four')?>
		</div>
	</div>
</div>
<?php endif; ?>

	<div id="inner-wrap-bottom">
		<div id="bottom">
			<?=$footer?>
			<?=render_views('bottom')?>
		</div>
	</div>
</div>

</body>
</html>