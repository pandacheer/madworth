<?php echo $head; ?>
<div role="main" class="ui-content">
    <form action="/personal/updatepassword" method="post">
        <div class="dg-pagetitle">Change your password</div>
        <label>Current Password</label>
        <input name="current" id="current" type="password" placeholder="" />

        <label>New Password</label>
        <input name="new" id="new" type="password" placeholder="" />

        <label>Confirm New Password</label>
        <input name="confirm" id="confirm" type="password" placeholder="" />

        <button type="button" data-theme="g" class="dg-account-button">Save Change</button>
        <a href="/personal"><button  type="button" data-theme="c" id="close">Cancel</button></a>
    </form>
</div>
<?php echo $foot; ?>
</div>
</body>
<script>
    $('.dg-account-button').click(function () {
        if ($('#current').val() === '' && $('#new').val() === '' && $('#confirm').val() === '') {
            $.notifyBar({cssClass: "dg-notify-error", html: 'Data has not changed', position: "bottom"});
            return false;
        } else {
            if ($('#new').val() !== $('#confirm').val()) {
                $.notifyBar({cssClass: "dg-notify-error", html: 'The Confirm Password field does not match the Password field.', position: "bottom"});
                return false;
            }
        }
        $.post("/personal/updatepassword", {
            current: $('#current').val(),
            new: $('#new').val(),
            confirm: $('#confirm').val()
        }, function (result) {
            var result = $.parseJSON(result);
            if (result.success) {
                $.notifyBar({cssClass: "dg-notify-success", html: result.message, position: "bottom"});
                setTimeout("history.back()", 3000);
            } else {
                $.notifyBar({cssClass: "dg-notify-error", html: result.message, position: "bottom"});
            }
        });
    });

</script>
</html>