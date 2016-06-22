<?php echo $head; ?>
<div class="dg-main">
    <div class="container">
        <div class="row">
            <div class="col-xs-10 col-xs-12">
                <div class="dg-main-thankyou">
                    <div class="dg-main-thankyou-ticker">
                        <i class="fa fa-exclamation-circle fa-lg"></i>
                        <div class="dg-main-thankyou-ticker-thanktitle"><?php echo $errorMessage ?></div>
                        <div class="dg-main-thankyou-ticker-order"></div>
                        <div class="dg-main-thankyou-ticker-button">
                            <a href="<?php echo isset($actionUrl) ? $actionUrl : '/' ?>"><button type="button" class="btn btn-default btn-lg"><?php echo isset($actionTitle) ? $actionTitle : 'Continue Shopping' ?></button></a>
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
    cartempty();
</script>
</body>
</html>