<?php echo $head; ?>
            <div role="main" class="ui-content">
                <form id="sendForm" action="/reg/checkfblogin" method="post">
                    <input type="hidden" name="redirecturl" value="<?php echo $redirecturl;?>"/>
                    <img src="<?php echo $cdn ?>img/facebooklogin.png" style="width: 100%">
                    <label class="dg-main-facebooklogin-dec">Please confirm your email address</label>
                    <label>Email address</label>
                    <input type="text" placeholder="Email Address" value="<?php echo isset($email)?$email:'';?>">
                    <button data-theme="g" class="dg-account-button" data-ajax="false">Submit</button>
                </form>
            </div>
            <?php echo $foot; ?>
        </div>
    </body>
</html>