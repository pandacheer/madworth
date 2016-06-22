<?php echo $head; ?>
<div role="main" class="ui-content">
    <div class="dg-pagetitle">Login</div>
    <label>Email address</label>
    <input type="email" placeholder=""  id="email" name="myEmail" />

    <label>Password</label>
    <input type="password" placeholder=""  id="password" name="myPassword" />

    <div id="verifyCode-div" <?php echo ($this->session->userdata('Verification')) && $this->session->userdata('Verification')['clickTimes'] > 2 ? 'style="display:block"' : 'style="display:none"' ?>>
        <label for="verifyCode">Verify Code</label>
        <input type="text" class="form-control" id="verifyCode" name="myCode">
        <div class="dg-main-login-code">
            <img id="Login_Img" src="/reg/vcode" onclick="this.src = '/reg/vcode?k=' + Math.random()"/>
        </div>
    </div>

    <button data-theme="g" id="body-Login"  class="dg-account-button">Login</button>
    <a href="/forget" style="float: right;margin-top: 0.5em;">Forget Your Password?</a><br>
    <?php if($fb_login):?>
    <div class="dg-main-facebook">
         <hr></hr>
         <span>or</span>
         <button style="background-color: #5A6AAD;border: #5A6AAD" onclick="location.href='<?php echo $fb_login;?>'">Login with Facebook</button>
    </div>
    <?php endif;?>
</div>
<?php echo $foot; ?>
</div>


<script type="text/javascript">

    $('#verifyCode').bind('input propertychange', function () {
        checkVerifyCode($('#verifyCode'));
    });

    function checkVerifyCode(codeObj) {
        var p = codeObj.val();
        if (p.length === 4) {
            $.post("/reg/comparison", {
                verifyCode: codeObj.val()
            }, function (result) {
                if (!result) {
                    codeObj.css("border-color", "#FF696E");
                    $.notifyBar({cssClass: "dg-notify-error", html: 'Invalid verification Code', position: "bottom"});
                } else {
                    codeObj.css("border-color", "green");
                }
            });
        } else {
            codeObj.css("border-color", "#FF696E");
            if (p.length > 4) {
                $.notifyBar({cssClass: "dg-notify-error", html: 'Invalid verification Code', position: "bottom"});
            }
        }
    }

    $('#body-Login').click(function () {
        if (($('#email').val() === '') || (!(/^[\w!#$%&'*+/=?^_`{|}~-]+(?:\.[\w!#$%&'*+/=?^_`{|}~-]+)*@(?:[\w](?:[\w-]*[\w])?\.)+[\w](?:[\w-]*[\w])?$/i.test($('#email').val())))) {
            $.notifyBar({cssClass: "dg-notify-error", html: 'Please enter a valid email address', position: "bottom"});
            $('#email').css("border-color", "red");
            return false;
        }
        if (($('#password').val() === '') || (!/^[a-zA-Z0-9_]{5,20}$/i.test($('#password').val()))) {
            $.notifyBar({cssClass: "dg-notify-error", html: 'The password must be at least 5 characters - letters or numbers.', position: "bottom"});
            $('#password').css("border-color", "red");
            return false;
        }

        $.post("<?php echo site_url('reg/login'); ?>", {
            myEmail: $('#email').val(),
            myPassword: $('#password').val(),
            verifyCode: $('#verifyCode').val()
        }, function (result) {
            var result = $.parseJSON(result);
            if (result.success) {
                $.notifyBar({cssClass: "dg-notify-success", html: "Login Successful, redirecting ...", position: "bottom"});
                setTimeout("self.location = '<?php echo  isset($refererUrl)? $refererUrl: $this->uri->ruri_string(); ?>'", 2000);
            } else {

                if (result.errorMessage === 'shopify') {
                    window.location.href = "/home/goActivate/" + result.email;
                } else {
                    if ($('#verifyCode-div').css('display') === 'block') {
                        $('#verifyCode').val('');
                        $('#Login_Img').attr('src', '/reg/vcode?k=' + Math.random());
                    } else {
                        if (result.clickTimes > 2) {
                            $("#verifyCode-div").css("display", "block");
                        }
                    }
                    $.notifyBar({cssClass: "dg-notify-error", html: result.errorMessage, position: "bottom"});
                }
            }
        }
//            .fail(function (xhr, errorText, errorType) {
//            $.notifyBar({cssClass: "dg-notify-error", html: "error:404", position: "bottom"});
//        }
        );
    });
</script>
</body>
</html>