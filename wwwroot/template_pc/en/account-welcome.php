<?php echo $head; ?>
    <div class="dg-main">
      <div class="container">
        <div class="row">
          <div class="col-xs-10 col-xs-12">
            <div class="dg-main-account clearfix">
              <div class="dg-main-account-hander clearfix">
                  <div class="dg-main-account-hander-title">My Account</div>
                  <!-- <div class="dg-main-account-hander-balance"><b>Store Credit : </b>$0.00</div> -->
              </div>
              <div class="dg-main-account-menu">
                  <a href="javascript:void(0)" class="dg-main-account-menu-tab dg-main-account-menu-tab-detail active"><div class="icon"></div><span class="text">Personal Details</span></a>
                  <a href="javascript:void(0)" class="dg-main-account-menu-tab dg-main-account-menu-tab-orders " ><div class="icon"></div><span class="text">My Orders</span></a>
                  <a href="javascript:void(0)" class="dg-main-account-menu-tab dg-main-account-menu-tab-coupon " ><div class="icon"></div><span class="text">My Coupons</span></a>
                  <a href="javascript:void(0)" class="dg-main-account-menu-tab dg-main-account-menu-tab-address"><div class="icon"></div><span class="text">Address</span></a>
                  <a href="javascript:void(0)" class="dg-main-account-menu-tab dg-main-account-menu-tab-info "><div class="icon"></div><span class="text">Need Some Help?</span></a>
              </div>
              <div class="dg-main-account-welcome">
                <div class="row">
                  <div class="col-xs-4">
                    <div class="dg-main-account-welcome-icon">
                        <i class="fa fa-expeditedssl fa-lg"></i>
                    </div>
                  </div>
                  <div class="col-xs-8 dg-main-account-welcome-title">
                     <p>Welcome To DrGrab's New Site!</p>
                     <p>Please Activate Your Account</p>
                  </div>
                </div>
                <div class="row">
                  <div class="col-xs-12 dg-main-account-welcome-content">
                    <p>An email has been sent to <a href="javascript:void(0);" style="color: #00B6C6"><?php echo $email ?></a> </p>
                    <p>Please Click the activation Link in the email to reactivate your account!</p>
                    <p>(If you do NOT receive the Email, please check your Junk and Spam folders)</p>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>  
  
<?php echo $foot ?>

  </body>
</html>