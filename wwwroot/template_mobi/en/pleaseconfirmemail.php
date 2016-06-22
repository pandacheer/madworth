<?php echo $head; ?>
<div class="dg-main">
    <div class="container">
        <div class="row">
            <div class="col-xs-10 col-xs-12">
                <div class="dg-main-thankyou">
                    <div class="dg-main-thankyou-ticker">
                        <i class="fa fa-check-circle fa-lg"></i>
                        <div class="dg-main-thankyou-ticker-thankdesc">The email address provided by you is different from your Facebook Account. A verification email has been sent to <?php echo $email;?>, please kindly check your inbox and confirm.</div>
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