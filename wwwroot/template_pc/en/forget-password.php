<?php echo $head; ?>
        <div class="dg-main">
            <div class="container">
                <div class="row">
                    <div class="col-xs-10 col-xs-12">
                        <div class="dg-main-reg">
                            <div class="dg-title">Forgot Your Password?</div>
                            <table class="dg-main-reg-table">
                                <tr>
                                    <td class="dg-main-for-table-left">
                                        <p>Forgotten your password? Not a worry! Simply enter your email address below and we'll send you some easy to follow instructions to get a new password.</p>
                                        <?php echo form_open('/forget/send', array('id' => 'sendForm')); ?>
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">Email address <span class="danger">*</span></label>
                                            <input type="email" class="form-control" name="forgetEmail" id="forgetEmail">
                                            <div id="resultMessage" style="margin: 5px;"></div>
                                        </div>
                                        <button type="button" class="btn btn-success" id="btnSend" onclick="this.disabled=true;setTimeout('btnSend.disabled=false',5000);">Reset Password</button>

                                <?php echo form_close(); ?>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>

            <?php echo $shoppingcart ?>

        </div>
    </div>
</div>  

<?php echo $foot ?>

<script>
    cartempty();

$('#forgetEmail').keypress(function (e) {
        var keyCode = e.keyCode ? e.keyCode : e.which ? e.which : e.charCode;
        if (keyCode === 13) {
           $('#btnSend').trigger('click');
        };
    });
    
    $('#btnSend').click(function () {
        if (!(/^[\w!#$%&'*+/=?^_`{|}~-]+(?:\.[\w!#$%&'*+/=?^_`{|}~-]+)*@(?:[\w](?:[\w-]*[\w])?\.)+[\w](?:[\w-]*[\w])?$/i.test($('#forgetEmail').val()))) {
            $.notifyBar({cssClass: "dg-notify-error", html: 'Please enter a valid email address', position: "bottom"});
            return false;
        }
        $('#btnSend').attr('disabled', 'disabled');
        $('#btnSend').val(' Send in progress ...');
        $.post("/forget/send", {
            forgetEmail: $('#forgetEmail').val()
        }, function (result) {
            var result = $.parseJSON(result);
            if (result.success) {
                $.notifyBar({cssClass: "dg-notify-success", html: result.message, position: "bottom"});
                setTimeout("history.back()", 3000);
            } else {
                $('#btnSend').attr("disabled", false);
                $('#btnSend').val('Reset Password');
                $.notifyBar({cssClass: "dg-notify-error", html: result.message, position: "bottom"});
            }
        });
    });

</script>
</body>
</html>
