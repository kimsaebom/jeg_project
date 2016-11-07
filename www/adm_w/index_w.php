<? include "./inc/header.php"; ?>

<div id="container" <?if($main=="Y"){?>class="index_cont"<?}?>><!-- container start -->

	<? include "./inc/lnb.php"; ?>

	<div id="content"><!-- content start -->

		<?if($main=="Y"){?>
			<?
			if($center_page){
				include $center_page;
			}
			?>
		<?}else{?>
		<h2><?=$sub_font?></h2>

			<div class="sub_start"><!-- sub_start start -->
				<?
				if($center_page){
					include $center_page;
				}
				?>
			</div><!-- sub_start end -->

		<?}?>

	</div><!-- content end -->

	<? //include "./inc/rnb.php"; ?>

	<? include "./inc/footer.php"; ?>

</div><!-- wrap end -->

<iframe name="hidden_frame" style="display:none;"></iframe>
</body>
</html>