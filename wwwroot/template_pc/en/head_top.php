<!-- <div class="dg-maintenance" style="background:#FF666C;color:white;font-size:16px;padding-top:10px;">
    <div class="container" >
        <div class="row">
            <div class="col-xs-12">
                <div>
                   <p>We will be running a maintenance in <span data-countdownlv="1454313000000" id="down"></span> <br>We do not anticipate more than 10 minutes of downtime, and we apologize for any impact these activities may have.</p>
                </div>
            </div>
        </div>
    </div>
</div> -->
<div class="dg-topnav">
    <div class="container">
        <div class="row">
            <div class="col-xs-6">
                <div class="dg-topnav-country">
                    <?php foreach ($flag_sort as $country_code) : ?>
                        <?php if (strpos(strtolower(uri_string()), 'collections/') === 0 || strpos(strtolower(uri_string()), 'products/') === 0): ?>
                            <a href="http://<?php echo $countryList[$country_code]['domain'] . '/' . uri_string(); ?>" >
                            <?php else: ?>
                                <a href="http://<?php echo $countryList[$country_code]['domain']; ?>" >
                                <?php endif; ?>
                                <img src="<?php echo $cdn ?>image/flag/<?php echo $country_code ?>.png" data-toggle="tooltip" data-placement="bottom" title="<?php echo $countryList[$country_code]['name'] ?>">
                            </a>

                        <?php endforeach; ?>
                </div>
            </div>
            <div class="col-xs-6">
                <div class="dg-topnav-account pull-right">
                    <span class="dg-topnav-account-myaccount">
                        <a href="<?=$domain ?>/pages/faq"><i class="fa fa-question-circle"></i> Help</a>
                    </span>
                    <span class="dg-topnav-account-myaccount">
                        <a href="<?=$domain ?>/personal/order"><i class="fa fa-plane"></i> Track Order</a>
                    </span>
                    <?php if ($this->session->userdata('member_email')): ?>
                        <span class="dg-topnav-account-myaccount">
                            <a href="/cart"><i class="fa fa-shopping-cart"></i> My Cart</a>
                        </span>
                        <span class="dg-topnav-account-myaccount" id="dg-topnav-account-myaccount-myaccount">
                            <a href="<?=$domain ?>/personal" class="dropdown-toggle" data-hover="dropdown"><i class="glyphicon glyphicon-user"></i><a href="<?=$domain ?>/personal">My Account</a></a>
                            <ul class="dropdown-menu">
                                <li><a href="<?=$domain ?>/personal" class="dg-topnav-account-myaccount-tab dg-topnav-account-myaccount-tab-detail"><div class="icon"></div><span class="text">Personal Detail</span></a></li>
                                <!--<li class="divider"></li>-->
                                <!--<li><a class="dg-topnav-account-myaccount-tab dg-topnav-account-myaccount-tab-wishlist " href="javascript:void(0);"><div class="icon"></div><span class="text">My Wishlist</span></a></li>-->
                                <li class="divider"></li>
                                <li><a class="dg-topnav-account-myaccount-tab dg-topnav-account-myaccount-tab-orders " href="<?=$domain ?>/personal/order"><div class="icon"></div><span class="text">My Orders</span></a></li>
                                <li class="divider"></li>
                                <li><a class="dg-topnav-account-myaccount-tab dg-topnav-account-myaccount-tab-coupon" href="<?=$domain ?>/personal/coupon"><div class="icon"></div><span class="text">My Coupons</span></a></li>
                                <li class="divider"></li>
                                <!--<li><a class="dg-topnav-account-myaccount-tab dg-topnav-account-myaccount-tab-rewards " href="javascript:void(0);"><div class="icon"></div><span class="text">Rewards</span></a></li>-->
                                <!--<li class="divider"></li>-->
                                <li><a class="dg-topnav-account-myaccount-tab dg-topnav-account-myaccount-tab-address" href="<?=$domain ?>/personal/address"><div class="icon"></div><span class="text">Address</span></a></li>
                                <!--<li class="divider"></li>-->
                                <!--<li><a class="dg-topnav-account-myaccount-tab dg-topnav-account-myaccount-tab-giftcard " href="javascript:void(0);"><div class="icon"></div><span class="text">Gift Card</span></a></li>-->
                                <!--<li class="divider"></li>-->
                                <!--<li><a class="dg-topnav-account-myaccount-tab dg-topnav-account-myaccount-tab-info " href="javascript:void(0);"><div class="icon"></div><span class="text">Support</span></a></li>-->
                            </ul>
                        </span>
                        <span class="dg-topnav-account-myaccount"><a href="<?=$domain ?>/reg/logOut"><i class="glyphicon glyphicon-log-out"></i>Log Out</a></span>
                    <?php else : ?>
                        <span class="dg-topnav-account-myaccount">
                            <a href="/cart"><i class="fa fa-shopping-cart"></i> My Cart</a>
                        </span>
                        <span class="dg-topnav-account-myaccount dg-topnav-account-myaccount-login" id="dg-topnav-account-myaccount-login">
                            <a href="<?=$domain ?>/login" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown"><i class="glyphicon glyphicon-log-in"></i><a href="<?=$domain ?>/login">Log In</a></a>
                            <ul class="dropdown-menu">
                                <li>
                                    <div class="dg-topnav-account-myaccount-login-box">
                                        <h4>Login</h4>
                                        <form method="post" action="/reg/login">
                                            <div class="form-group">
                                                <input type="email" class="form-control" id="head-email" name="myEmail" placeholder="Email address">
                                            </div>
                                            <div class="form-group">
                                                <input type="password" class="form-control" id="head-password"  name="myPassword" placeholder="Password">
                                            </div>
                                            <div class="form-group" id="head-verifyCode-div" <?php echo ($this->session->userdata('Verification')) && $this->session->userdata('Verification')['clickTimes'] > 2 ? 'style="display:block"' : 'style="display:none"' ?>>
                                                <label for="verifyCode">Verify Code</label>
                                                <input type="text" class="form-control" id="head-verifyCode"  name="verifyCode"><br/>
                                                <span id="Login_code" ><img id="head_Login_Img" src="/reg/vcode" onclick="this.src = '/reg/vcode/' + Math.random()"/></span>
                                            </div>
                                            <button type="button" class="btn btn-success dg-topnav-account-myaccount-login-box-loginbtn" id="head_Login">Login</button>
                                            <span><a href="<?=$domain ?>/forget">Forgot Your Password?</a></span>
                                            <div class="alert alert-danger alert-dismissible" id="waring" role="alert" style="display:none;margin-top: 15px;">
                                                <div id="error_error"></div>
                                            </div>
                                        </form>
                                        <?php if($fb_login):?>
                                        <div class="dg-topnav-account-myaccount-login-box-or">
                                            <hr>
                                            <span>or</span>
                                        </div>
                                        <a class="btn btn-facebook dg-topnav-account-myaccount-login-box-fbloginbtn" href="<?php echo $fb_login;?>"><i class="fa fa-facebook-square fa-lg"></i>  Login with Facebook</a>
                                        <?php endif;?>
                                    </div>
                                </li>
                            </ul>
                        </span>
                        <span class="dg-topnav-account-myaccount"><a href="<?=$domain ?>/reg"><i class="glyphicon glyphicon-user"></i>Sign Up</a></span>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

