<?php echo $head; ?>
            <div role="main" class="ui-content">
                <div class="dg-pagetitle">Reset your password!</div>
                <?php echo form_open('/forget/update', array('id' => 'updateForm')); ?>
                <input type="hidden" name="check_Token" value="<?php echo $forget_email ?>">
                <input type="hidden" name="check_id" value="<?php echo $forget_id ?>">
                <label>Email address<span class="red">*</span></label>
                <input type="email" class="form-control" id="forget_email" name="forget_email" value="<?php echo $true_email ?>" readonly="readonly">
                <label>Password<span class="red">*</span></label>
                <input type="password" class="form-control" id="password" name="password">
                <label>Confirm Password<span class="red">*</span></label>
                <input type="password" class="form-control" id="verifyPassword" name="verifyPassword">
                <button id="btnReset" data-mini="true" data-theme="g">Reset Now</button>
                <?php echo form_close(); ?>
            </div>
            <?php echo $foot; ?>
        </div>
        <script>
            $('#btnReset').click(function () {
                if (!(/^[\w!#$%&'*+/=?^_`{|}~-]+(?:\.[\w!#$%&'*+/=?^_`{|}~-]+)*@(?:[\w](?:[\w-]*[\w])?\.)+[\w](?:[\w-]*[\w])?$/i.test($('#forget_email').val()))) {
                    $.notifyBar({ cssClass: "dg-notify-error", html: 'Please enter a valid email address' ,position: "bottom" });
                    return false;
                }
                if (!/^[a-zA-Z0-9_]{6,16}$/i.test($('#password').val())) {
                    $.notifyBar({ cssClass: "dg-notify-error", html: 'The password must be at least 5 characters - letters or numbers.' ,position: "bottom" });
                    return false;
                }
                if ($('#verifyPassword').val() !== $('#password').val()) {
                    $.notifyBar({ cssClass: "dg-notify-error", html: 'The Confirm Password field does not match the Password field.' ,position: "bottom" });
                    return false;
                }
                $('#updateForm').submit();
            });

        </script>
    </body>
</html>