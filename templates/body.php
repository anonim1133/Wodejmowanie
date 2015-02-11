<?php global $settings; ?>

<body>
<div class="container">
	<div class="header">
		<div class="brand_container">
			<img src="assets/wykop.png" class="img-brand">
			<span class="tag"><a href="http://www.wykop.pl/tag/<?php echo $settings->getTag(); ?>/">/#<?php echo $settings->getTag(); ?></a></span>
		</div>
		<div class="avatar_container">
			<a href="?wyloguj">
				<img style="background-image: url('<?php echo $avatar; ?>');" class="img-avatar"alt="">
			</a>
			<span class="user">Witaj, <?php echo $login; ?></span>
		</div>
	</div>
	<div class="content">

		<?php include 'content.php'; ?>

	</div><!-- content -->
</div>
</body>