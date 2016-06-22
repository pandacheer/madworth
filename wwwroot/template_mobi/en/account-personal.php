<?php echo $head; ?>
<div role="main" class="ui-content">    
    <form id="postPersonalForm" method="post" action="/personal/update" accept-charset="utf-8">
        <div class="dg-pagetitle">Personal Details</div>
        <div class="ui-grid-a dg-main-form">
            <div class="ui-block-a">
                <label>First Name</label>
                <input type="text" name="firstname" id="firstname" placeholder="" value="<?php echo $member['member_firstName'] ?>" />
            </div>
            <div class="ui-block-b">
                <label>Last Name</label>
                <input type="text" name="lastname" id="lastname" placeholder="" value="<?php echo $member['member_lastName'] ?>" />
            </div>
        </div>
        <label>Email</label>
        <input type="text" id="email" placeholder="" value="<?php echo $this->session->userdata('member_email') ?>" readonly/>
        <label>Birthday</label>
        <input type="text" name="member_birthday" readonly="true" id="member_birthday" placeholder="" <?php echo $memberInfo['member_birthday'] ? 'value="' . date('m/d/Y', $memberInfo['member_birthday']) . '"' : 'class="datepicker"'; ?>  />
        <label>Phone</label>
        <input type="text" value="<?php echo $memberInfo['member_phone'] ?>" class="form-control" id="member_phone" name="member_phone">
        <label>Gender</label>
        <div class="dg-main-check">
            <div class="dg-main-check-list">
                <div class="iradio_square-blue<?php if($memberInfo['member_gender']==1)echo " checked"; ?>" style="float:left;"></div>
                <input type="radio" value="1" data-role="none"  name="member_gender"<?php if($memberInfo['member_gender']==1)echo " class='checked' checked='checked'"; ?> style="opacity: 0;"> 
                <strong>Male</strong>
            </div>
            <div class="dg-main-check-list">
                <div class="iradio_square-blue<?php if($memberInfo['member_gender']==2)echo " checked"; ?>" style="float:left;"></div>
                <input type="radio" value="2" data-role="none"  name="member_gender"<?php if($memberInfo['member_gender']==2)echo " class='checked' checked='checked'"; ?> style="opacity: 0;"> 
                <strong>Female</strong>
            </div>
        </div>

        <a href="/personal/changepassword">Change Your Password</a>                

        <button data-theme="g" type="button" class="dg-account-button" id="btnupdate">Save</button>
    </form>
</div>
<?php echo $foot; ?>
</div>

<script>
    $(function () {
        var dgbirthday = new Object;
        dgbirthday.year = 1980;
        $('.datepicker').datepicker({
            startView: 2,
            defaultViewDate: dgbirthday,
            autoclose: true
        });

        $('.dg-main-check-list').click(function () {
            $(this).siblings().children().removeClass('checked').removeAttr('checked');
            $(this).children().addClass('checked').attr('checked','checked');
        });
    });
    $("#btnupdate").on("click", function () {
        $.ajax({
            type: "POST",
            url: "/personal/update",
            dataType: 'json',
            data: $("#postPersonalForm").serialize(),
            success: function (result) {
                if (result.success) {
                    if($('#member_birthday').val()){$('#member_birthday').attr('disabled', 'true');}
                    $.notifyBar({cssClass: "dg-notify-success", html: result.resultMessage, position: "bottom"});
                } else {
                    $.notifyBar({cssClass: "dg-notify-error", html: result.resultMessage, position: "bottom"});
                }

            }
        });
    });
</script>
</body>
</html>
