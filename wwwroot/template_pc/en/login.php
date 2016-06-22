<?php echo $head; ?>
<div class="dg-main">
    <div class="container">
        <div class="row">
            <div class="col-xs-10 col-xs-12">
                <div class="dg-main-reg">
                    <div class="dg-title">Login</div>
                    <table class="dg-main-reg-table">
                        <tr>
                            <td class="dg-main-reg-table-left">
                                <?php // echo validation_errors(); ?>

                                <!--<div id="resultMessage" style="margin: 5px;"></div>-->
                                <div class="form-group">
                                    <label for="email">Email address</label>
                                    <input type="email" class="form-control" id="body_email" name="myEmail">
                                </div>
                                <div class="form-group">
                                    <label for="password">Password</label>
                                    <input type="password" class="form-control" id="body_password" name="myPassword">
                                </div>
                                <div class="form-group" id="body_verifyCode-div" <?php echo ($this->session->userdata('Verification')) && $this->session->userdata('Verification')['clickTimes'] > 2 ? 'style="display:block"' : 'style="display:none"' ?>>
                                    <label for="verifyCode">Verify Code</label>
                                    <input type="text" class="form-control" id="body_verifyCode" name="myCode">
                                    <div class="dg-main-reg-table-left-code">
                                        <img id="body_Login_Img" src="/reg/vcode" onclick="this.src = '/reg/vcode?k=' + Math.random()"/>
                                    </div>
                                </div>

                                <button type="button" id="body-Login" class="btn btn-success btn-lg">Login</button>
                                <a href="/forget" style="margin-top: 20px; float: right">Forget Your Password?</a>
                                <?php if($fb_login):?>
                                <div class="dg-topnav-account-myaccount-login-box-or">
                                    <hr>
                                    <span style="color: black">or</span>
                                </div>
                                <a class="btn btn-facebook dg-topnav-account-myaccount-login-box-fbloginbtn" href="<?php echo $fb_login;?>"><i class="fa fa-facebook-square fa-lg"></i>  Login with Facebook</a>
                                <?php endif;?>
                            </td>
                            <td class="dg-main-reg-table-right">
                                <div class="dg-main-reg-table-right-fb">
                                    <a href="/reg"><button class="btn btn-drgrab btn-lg dg-main-reg-table-right-fb-button">Register</button></a>
                                    <div class="dg-main-reg-table-right-title">New to us? Join now!</div>
                                </div>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
            <?php echo $shoppingcart ?>
        </div>
    </div>
</div>  

<?php echo $foot; ?>

<script>
    $(function () {
        //SideCart animation
        function slidefun() {
            $(".slideimg").animate({left: '+6px'}, 400);
            $(".slideimg").animate({left: '-6px'}, 800);
        }

        window.setInterval(slidefun, 1222);

        //SideCart link
        $(".quick_links-title").click(function () {
            window.location = "/cart";
        })

        //initialize the Scrolling bar on the SideCart
        Ps.initialize(document.getElementById('cartulboxli'));


        //SideCart Fixed
        /*$('#cartflysidebar').scrollToFixed({
            marginTop: $('.navbar-nav').outerHeight(true) + 10,
            limit: function () {
                var limit = 0;
                limit = $('.dg-footads').offset().top - $(this).outerHeight(true) - 40;
                return limit;
            },
            zIndex: 999
        });*/
    })

</script>
<script type="text/javascript">
    $('#body_verifyCode').bind('input propertychange', function () {
        checkVerifyCode($('#body_verifyCode'));
    });

    $('#body_password').keypress(function(e){
        var keycode = e.charCode;
            if(keycode == 13)
            $("#body-Login").click();
    });

    $('#body-Login').click(function () {
//        if (($('#body_email').val() === '') || (!(/^([\w-_]+(?:\.[\w-_]+)*)@((?:[a-z0-9]+(?:-[a-zA-Z0-9]+)*)+\.[a-z]{2,6})$/i.test($('#body_email').val())))) {
        if (($('#body_email').val() === '') || (!(/^^[\w!#$%&'*+/=?^_`{|}~-]+(?:\.[\w!#$%&'*+/=?^_`{|}~-]+)*@(?:[\w](?:[\w-]*[\w])?\.)+[\w](?:[\w-]*[\w])?$/i.test($('#body_email').val())))) {
            $.notifyBar({cssClass: "dg-notify-error", html: 'Your email address is a invalid', position: "bottom"});
            $('#body_email').css("border-color", "red");
            return false;
        }
        if (($('#body_password').val() === '') || (!/^[a-zA-Z0-9_]{5,20}$/i.test($('#body_password').val()))) {
            $.notifyBar({cssClass: "dg-notify-error", html: 'Your password is invalid', position: "bottom"});
            $('#body_password').css("border-color", "red");
            return false;
        }

        $.post("<?php echo site_url('reg/login'); ?>", {
            myEmail: $('#body_email').val(),
            myPassword: $('#body_password').val(),
            verifyCode: $('#body_verifyCode').val()
        }, function (result) {
            var result = $.parseJSON(result);
            if (result.success) {
                $.notifyBar({cssClass: "dg-notify-success", html: "Login Successful, redirecting ...", position: "bottom"});
                setTimeout("self.location = '<?php echo  isset($refererUrl)? $refererUrl: $this->uri->ruri_string(); ?>'", 2000);
            } else {
                if (result.errorMessage === 'shopify') {
                    window.location.href = "/home/goActivate/" + result.email;
                } else {
                    if ($('#body_verifyCode-div').css('display') === 'block') {
                        $('#body_verifyCode').val('');
                        $('#body_Login_Img').attr('src', '/reg/vcode?k=' + Math.random());
                    } else {
                        if (result.clickTimes > 2) {
                            $("#body_verifyCode-div").css("display", "block");
                        }
                    }

                    if (result.clickTimes > 2) {
                        $("#body_verifyCode-div").css("display", "block");
                    }
                    $.notifyBar({cssClass: "dg-notify-error", html: result.errorMessage, position: "bottom"});
                }
            }
        }).fail(function (xhr, errorText, errorType) {
            $.notifyBar({cssClass: "dg-notify-error", html: "Failed to process your request, please try again.", position: "bottom"});
        });
    });


    $("#body_email,#body_password").focus(function () {
        $(this).removeAttr('style');
    });

    $('#body_email').emailpop();
    cartempty();
</script>

</body>
</html>
