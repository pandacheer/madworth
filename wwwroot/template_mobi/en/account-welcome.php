<?php echo $head; ?>

            <div role="main" class="ui-content">
                <div class="dg-pagetitle">Welcome Back</div>
                <div class="dg-main-empty">
                    <div class="dg-main-empty-icon">
                        <span class="icon-right dg-main-empty-icon-success"></span>
                    </div>
                    <br>
                    <div class="dg-main-personal-welcome-content">
                        <p>Welcome To DrGrab's New Site!<br>
                            Please Activate Your Account</p>
                    </div>
                    <div class="dg-main-personal-welcome-bottom">
                       <p>A email has been sent to <a href="javascript:void(0);" style="color: #00B6C6"><?php echo $email ?></a></p>
                       <p>Please Click the activation Link in the email to reactivate your account!</p> 
                    </div>  
                    <a href="/"><button data-role="none">Continue Shopping</button></a>
                </div>
            </div>
            
            <?php echo $foot; ?>
        </div>
        
    </body>
</html>