<?php echo $head; ?>
            <div role="main" class="ui-content">
                <form id="sendForm">
                <div class="dg-pagetitle">Forgot Your Password?</div>
                <p>Forgotten your password? Not a worry! <br>Simply enter your email address below and we'll send you some easy to follow instructions to get a new password.</p>
                <label>Email address <span class="red">*</span></label>
                <input type="text" placeholder="" name="forgetEmail" id="forgetEmail">
                <button data-theme="g" class="dg-account-button" id="btnSend" data-ajax="false">Reset Password</button>
                </form>
            </div>
            <?php echo $foot; ?>
        </div>
        
        <script>
            $('#btnSend').click(function () {
                if (!(/^[\w!#$%&'*+/=?^_`{|}~-]+(?:\.[\w!#$%&'*+/=?^_`{|}~-]+)*@(?:[\w](?:[\w-]*[\w])?\.)+[\w](?:[\w-]*[\w])?$/i.test($('#forgetEmail').val()))) {
                    $.notifyBar({ cssClass: "dg-notify-error", html: 'Please enter a valid email address',position: "bottom" });
                    return false;
                }
                $('#btnSend').attr('disabled','disabled');
                $('#btnSend').val(' Send in progress ...');
                $.post("/forget/send", {
                    forgetEmail: $('#forgetEmail').val()
                }, function (result) {
                    var result = $.parseJSON(result);
                    if (result.success) {
                        $.notifyBar({ cssClass: "dg-notify-success", html:result.message,position: "bottom" });
                        setTimeout("history.back()",3000);
                    } else {
                        $('#btnSend').attr("disabled",false); 
                        $('#btnSend').val('Reset Password');
                        $.notifyBar({ cssClass: "dg-notify-error", html:result.message,position: "bottom" });
                    }
                });
            });

        </script>
    </body>
</html>