<?php echo $head; ?>
 <!--pages content-->
 	  <div class="dg-breadcrumb">
      <div class="container">
        <div class="row">
          <div class="col-xs-12">
            <ol class="breadcrumb">
              <?=$breadcrumb?>
            </ol>
          </div>
        </div>
      </div>
    </div>
 
 
    <div class="dg-main-pages">
        <div class="dg-main-pages-con">
        <p class="dg-main-pages-con-title"><?php echo $data['pages_title']; ?></p>
        <div class="container-fluid">
            <?php echo $data['pages_content']; ?>
        </div>
            <div style="display: none"><?php echo $shoppingcart ?></div>  
        </div>
    </div>

			  
<?php echo $foot; ?>
<script>

  fbq('track', 'ViewContent');

</script>
  </body>
</html>
