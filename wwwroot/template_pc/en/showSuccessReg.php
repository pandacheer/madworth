<?php echo $head; ?>
<div class="dg-main">
    <div class="container">
        <div class="row">
            <div class="col-xs-10 col-xs-12">
                <div class="dg-main-thankyou">
                    <div class="dg-main-thankyou-ticker">
                        <i class="fa fa-check-circle fa-lg"></i>
                        <div class="dg-main-thankyou-ticker-thanktitle">Thank you for being a Grabber</div>
                        <div class="dg-main-thankyou-ticker-thankdesc">Please check your email and verify your address</div>
                        <div class="dg-main-thankyou-ticker-estimated">
                            ( You will be redirected in <span id="timedown">3</span> seconds )
                        </div>
                    </div>
                </div>
            </div>
            <?php echo $shoppingcart ?>

        </div>
    </div>
</div>  

<?php echo $foot; ?>
<script>
  var second = document.getElementById('timedown').textContent;

  setInterval("redirect()", 1000); 
  function redirect(){
     if (second < 0){
        location.href = '<?php echo urldecode($jumpUrl) ?>'; 
    } else{
      document.getElementById('timedown').textContent = second--; 
    }
  }
</script>
<script>
    cartempty();
</script>
</body>
</html>