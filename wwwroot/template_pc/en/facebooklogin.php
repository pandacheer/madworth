<?php echo $head; ?>
        <div class="dg-main">
            <div class="container">
                <div class="row">
                    <div class="col-xs-10 col-xs-12">
                        <div class="dg-main-reg" align="center">
                            <?php echo form_open('/reg/checkfblogin'); ?>
                                <input type="hidden" name="redirecturl" value="<?php echo $redirecturl;?>"/>
                                <img src="<?php echo $cdn ?>image/facebooklogin.png" style="width: 350px">
                                <div class="dg-main-facebooklogin">
                                    <p class="dg-main-facebooklogin-dec">Please confirm your email address</p>
                                    <p><strong>Email Address</strong></p>
                                    <input type="text" name="email" class="form-control" value="<?php echo isset($email)?$email:'';?>"><br>
                                    <button class="btn btn-lg btn-success" type="submit">Submit</button>
                                </div>
                            <?php echo form_close(); ?>
                        </div>
                    </div>

            <?php echo $shoppingcart ?>

        </div>
    </div>
</div>  

<?php echo $foot ?>

<script>
    cartempty();
</script>
</body>
</html>
