<?php echo $head; ?>
            <form id="regForm">
            <div role="main" class="ui-content">
                <div class="dg-pagetitle">Sign Up and Join Us</div>
                <label>Email</label>
                <input type="email" data-valid="0" class="form-control" id="email" name="email" onkeypress="if (event.keyCode == 13 || event.which == 13) {
                                                return false;
                                            }">
                
                <label>Password</label>
                <input type="password" data-valid="0" class="form-control" id="password" name="password">
                
                <label>Confirm Password</label>
                <input type="password" data-valid="0" class="form-control" id="verifyPassword" name="verifyPassword">
                
                <div class="form-group" id="verifyCode-div" <?php echo ($this->session->userdata('Verification')) && $this->session->userdata('Verification')['clickTimes'] > 2 ? 'style="display:block"' : 'style="display:none"' ?> >
                	<label for="verifyCode">Verify Code</label>
                    	<input type="text" class="form-control"  id="reg-verifyCode" name="myCode">
                        <div class="dg-main-reg-table-left-code">
                        	<img src="/reg/vcode" onclick="this.src = '/reg/vcode/' + Math.random()"/>
                        </div>
                </div>
                
                <button type="button" data-theme="g" class="dg-account-button" id="btn_reg">Join Now</button>
                <div class="dg-account-description">By providing your email address and signing up, you agree to DrGrab's <a href="/">Terms & Conditions</a></div>
                <?php if($fb_login):?>
                <div class="dg-main-facebook">
                     <hr></hr>
                     <span>or</span>
                     <button style="background-color: #5A6AAD;border: #5A6AAD" onclick="location.href='<?php echo $fb_login;?>';return false;">Login with Facebook</button>
                </div>
                <?php endif;?>
            </div>
            </form>
            <?php echo $foot; ?>
        </div>
        
<script type="text/javascript">
    $('#email').blur(function () {
        if (!checkInput(false)) {
            return false;
        }
        $.post("/reg/validationEmail", {
            email: $('#email').val()
        }, function (result) {
            if (!result) {
                $('#email').css("border-color", "red");
                $.notifyBar({cssClass: "dg-notify-error", html: 'The existing email.', position: "bottom"});

                return false;
            } else {
                $('#email').css("border-color", "green");
            }

        });
    });
    $('#reg-verifyCode').bind('input propertychange', function () {
        checkVerifyCode($('#reg-verifyCode'));
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
    
    $('#btn_reg').click(function () {
        if (!checkInput(true)) {
            return false;
        }
        $.ajax({
            type: "POST",
            url: "<?php echo site_url('reg/add') ?>",
            dataType: 'json',
            data: $("#regForm").serialize(),
            success: function (result) {
                if (result.success) {
                    /* $.notifyBar({cssClass: "dg-notify-success", delay: 1000, html: "success", position: "bottom"}); */
                    self.location = "/home/showSuccess/S2002?jumpUrl=<?php echo $refererUrl ?>";
                } else {
                    if (result.clickTimes > 2) {
                        $("#verifyCode-div").css("display", "block");
                    }
                    $.notifyBar({cssClass: "dg-notify-error", delay: 1000, html: result.errorMessage, position: "bottom"});
                }
            }
        });
    });

    $("#email,#password,#verifyPassword").focus(function () {
        $(this).removeAttr('style');
    });

    function checkInput(all) {
        if ($('#email').val() === '') {
            $.notifyBar({cssClass: "dg-notify-error", html: 'Please enter a valid email address', position: "bottom"});
            $('#email').css("border-color", "red");
            return false;
        }
        if (!(/^^[\w!#$%&'*+/=?^_`{|}~-]+(?:\.[\w!#$%&'*+/=?^_`{|}~-]+)*@(?:[\w](?:[\w-]*[\w])?\.)+[\w](?:[\w-]*[\w])?$/i.test($('#email').val()))) {
            $.notifyBar({cssClass: "dg-notify-error", html: 'Your email address is invalid', position: "bottom"});
            $('#email').css("border-color", "red");
            return false;
        }
        if (all) {
            if (!/^[a-zA-Z0-9_]{5,20}$/i.test($('#password').val()) || ($('#password').val() === '')) {
                $.notifyBar({cssClass: "dg-notify-error", html: 'The password must be at least 5 characters - letters or numbers.', position: "bottom"});
                $('#password').css("border-color", "red");
                return false;
            }

            if ($('#password').val() !== $('#verifyPassword').val()) {
                $.notifyBar({cssClass: "dg-notify-error", html: 'The Confirm Password field does not match the Password field.', position: "bottom"});
                $('#password').css("border-color", "red");
                $('#verifyPassword').css("border-color", "red");
                return false;
            }
        }
        return true;
    }
</script>


    </body>
</html>