<?php echo $head; ?>
<div role="main" class="ui-content dg-pages" >
    <p class="dg-main-pages-title"><?php echo $data['pages_title']; ?></p>
	<?php echo $data['pages_content']; ?>
</div>
<?php echo $foot; ?>
<script>

	fbq('track', 'ViewContent');

</script>