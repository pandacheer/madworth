<?php echo $head; ?>
<?php echo isset($jumpUrl) ? '<meta http-equiv="refresh" content="3;url=' . urldecode($jumpUrl) . '">' : "" ?>
            <div role="main" class="ui-content">
                <div class="dg-pagetitle">Success</div>
                <div class="dg-main-empty">
                    <div class="dg-main-empty-icon">
                        <span class="icon-right dg-main-empty-icon-success"></span>
                    </div>
                    <div class="dg-main-empty-msg">
                         <span>Thank you for being a Grabber</span><br>
                         <span>Please check your email and verify your address</span>
                         <span>( You will be redirected in <span id="timedown">5</span> seconds )</span>
                    </div>
                </div>
            </div>
            <?php echo $foot; ?>
        </div>
<script>
  var second = document.getElementById('timedown').textContent;

  setInterval("redirect()", 1000); 
  function redirect(){
     if (second < 0){
        location.href = '/'; 
    } else{
      document.getElementById('timedown').textContent = second--; 
    }
  }
</script>
    </body>
</html>