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
                        <a class="dg-main-account-menu-tab dg-main-account-menu-tab-detail active"><div class="icon"></div><span class="text">Personal Details</span></a>

                        <a class="dg-main-account-menu-tab dg-main-account-menu-tab-orders " href="/personal/order"><div class="icon"></div><span class="text">My Orders</span></a>
                        <a class="dg-main-account-menu-tab dg-main-account-menu-tab-coupon " href="/personal/coupon"><div class="icon"></div><span class="text">My Coupons</span></a>

                        <a class="dg-main-account-menu-tab dg-main-account-menu-tab-address " href="/personal/address"><div class="icon"></div><span class="text">Address</span></a>

                        <a class="dg-main-account-menu-tab dg-main-account-menu-tab-info "href="/pages/faq"><div class="icon"></div><span class="text">Need Some Help?</span></a>
                    </div>
                    <div class="dg-main-account-content dg-main-account-content-personal">
                        <?php echo form_open('personal/update', array('id' => 'uploadForm')) ?>
                        <h4>Personal Details</h4>
                        <input type="hidden" name="mydata" value="<?php echo $myData ?>">
                        <table class="dg-main-account-content-personal-info">
                            <tr>
                                <td width="40%">
                                    <div class="form-group">
                                        <label for="email">First Name</label>
                                        <input type="text"  name="firstname" class="form-control" id="firstname" value="<?php echo $member['member_firstName'] ?>">
                                    </div>                        
                                </td>
                                <td width="10%"></td>
                                <td width="40%">
                                    <div class="form-group">
                                        <label for="phone">Last Name</label>
                                        <input type="text" name="lastname" class="form-control" id="lastname" value="<?php echo $member['member_lastName'] ?>">
                                    </div>                       
                                </td>
                                <td width="10%"></td>
                            </tr>

                            <tr>
                                <td width="40%">
                                    <div class="form-group">
                                        <label for="email">Email</label>
                                        <input type="text" readonly name="member_email" class="form-control" value="<?php echo $this->session->userdata('member_email') ?>">
                                    </div>                        
                                </td>
                                <td width="10%"></td>
                                <td width="40%">
                                    <div class="form-group">
                                        <label for="phone">Phone</label>
                                        <input type="text" name="member_phone" id="member_phone" class="form-control" value="<?php echo $memberInfo['member_phone'] ?>">
                                    </div>                       
                                </td>
                                <td width="10%"></td>
                            </tr>

                            <tr>
                                <td width="40%">
                                    <div class="form-group">
                                        <label>Birthday</label>
                                        <?php if ($memberInfo['member_birthday']): ?>
                                            <div  class="input-group" data-trigger="focus" data-container="body" data-toggle="popover" data-placement="right" data-content="After filling prohibit modification!" >
                                            <input type="text" id="member_birthday" readonly="true" class="form-control" autocomplete="off" name="member_birthday" value="<?php echo date('m/d/Y', $memberInfo['member_birthday']); ?>"><span class="input-group-addon"><i class="glyphicon glyphicon-th"></i></span>
                                        <?php else: ?>
                                            <div id="input-group" class="input-group date" data-trigger="focus" data-container="body" data-toggle="popover" data-placement="right" data-content="Cannot be changed once it is saved" >
                                            <input type="text" id="member_birthday" class="form-control" autocomplete="off" name="member_birthday" value=""><span class="input-group-addon"><i class="glyphicon glyphicon-th"></i></span>
                                        <?php endif; ?>
                                   </div>                        
                                </td>
                                <td width="10%"></td>
                                <td width="40%"></td>
                                <td width="10%"></td>
                            </tr>

                        </table>
                        <div class="dg-main-account-content-personal-gender">
                            <label>Gender</label><br/>
                            <label>
                                <input type="radio" id="inlineCheckbox1" value="1" name="member_gender" <?php echo $memberInfo['member_gender'] == 1 ? "checked='checked'" : '' ?>> Male
                            </label>
                            <label>
                                <input type="radio" id="inlineCheckbox2" value="2" name="member_gender" <?php echo $memberInfo['member_gender'] == 2 ? "checked='checked'" : '' ?>> Female
                            </label>         

                        </div>

                        <div class="dg-main-account-content-personal-password">
                            <a class="dg-main-account-content-personal-password-handler" data-toggle="collapse" href="#passwordpanel" aria-controls="passwordpanel">Change your password</a>  
                            <div class="panel panel-default collapse" id="passwordpanel">
                                <div class="panel-heading">
                                    <h3 class="panel-title">Change your password
                                        <span class="pull-right clickable"><i class="glyphicon glyphicon-remove dg-main-account-content-personal-password-handleron"></i></span>                          
                                    </h3>
                                </div>
                                <div class="panel-body">
                                    <div class="form-group">
                                        <label for="currentPassword">Current Password</label>
                                        <input type="password" id="currentPassword" name="currentPassword" class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <label for="newPassword">New Password</label>
                                        <input type="password" id="newPassword" name="newPassword" class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <label for="verifyPassword">Confirm New Password</label>
                                        <input type="password" id="verifyPassword" name="verifyPassword" class="form-control">
                                    </div>
                                </div>
                            </div>     
                        </div>                  

                        <button type="button" class="btn btn-success btn-lg" id="btnUpdate">Save</button>

                        </form>    
                    </div>
                </div>

            </div>
            <?php echo $shoppingcart ?>
        </div>
    </div>
</div>  

<?php echo $foot ?>


<script>
    $(function () {
        $('input').iCheck({
            checkboxClass: 'icheckbox_square-blue',
            radioClass: 'iradio_square-blue',
            increaseArea: '20%' // optional
        });

        $('.selectpicker').selectpicker();
    });
    
    //Enter not submit the form
    $('#btnUpdate').keypress(function(e){
        var keycode = e.charCode;
            if(keycode == 13)
            return false;
    });

    $(".dg-main-account-content-personal-password-handler").click(
            function () {
                $(".dg-main-account-content-personal-password-handler").toggle();
            }
    );

    $(".dg-main-account-content-personal-password-handleron").click(
            function () {
                $('#passwordpanel').collapse('toggle');
                $('.panel-body input').val('');
            }
    );

    $('#passwordpanel').on('hidden.bs.collapse', function () {
        $(".dg-main-account-content-personal-password-handler").toggle();
    })

    $('#verifyPassword').keypress(function(e){
        var keycode = e.charCode;
            if(keycode == 13)
            $("#btnUpdate").click();
    });
    
    $('#btnUpdate').click(function () {
        $("#btnUpdate").prop('disabled', true);
        $("#btnUpdate").text('Saving');
        if ($('#currentPassword').val() === '' && $('#newPassword').val() === '' && $('#verifyPassword').val() === '') {
            if ($('#member_birthday').val() === '<?php echo date('m/d/Y', $memberInfo['member_birthday']); ?>' && $('#member_phone').val() === '<?php echo $memberInfo['member_phone']; ?>' && $(".dg-main-account-content-personal-gender input[name='member_gender']:checked").val() ===<?php echo $memberInfo['member_gender']; ?> && $('#firstname').val() === '<?php echo $member['member_firstName']; ?>' && $('#lastname').val() === '<?php echo $member['member_lastName']; ?>') {
                $.notifyBar({cssClass: "dg-notify-error", html: 'There is no change in the data', position: "bottom"});
                $("#btnUpdate").prop('disabled', false);
                $("#btnUpdate").text('Save');
                return false;
            }
        } else {
            if ($('#newPassword').val() !== $('#verifyPassword').val()) {
                $.notifyBar({cssClass: "dg-notify-error", html: 'The Confirm Password field does not match the Password field.   ', position: "bottom"});
                $("#btnUpdate").prop('disabled', false);
                $("#btnUpdate").text('Save');
                return false;
            }
        }

        if ($('#member_phone').val() === '') {
            $.ajax({
                type: "POST",
                url: "/personal/update",
                dataType: 'json',
                data: $('#uploadForm').serialize(),
                success: function (result) {
                    if (result.success) {
                        $("#btnUpdate").prop('disabled', false);
                        $("#btnUpdate").text('Save');
                        $.notifyBar({cssClass: "dg-notify-success", html: result.resultMessage, position: "bottom"});
                        if($("#passwordpanel").hasClass('in')){
                            $('#passwordpanel').collapse('hide');
                            $("#passwordpanel input").val("");
                        }
                        if($('#member_birthday').val()){$('#member_birthday').attr('disabled', 'true');}

                        //$('.date').datetimepicker('hide');
                        //location.reload();

                    } else {
                        $.notifyBar({cssClass: "dg-notify-error", html: result.resultMessage, position: "bottom"});
                        $("#btnUpdate").prop('disabled', false);
                        $("#btnUpdate").text('Save');
                    }
                }
            });
        } else {
            if (!(/^[\d\s]+$/).test($('#member_phone').val())) {
                $.notifyBar({cssClass: "dg-notify-error", html: 'Please enter a valid phone number', position: "bottom"});
                $("#btnUpdate").prop('disabled', false);
                $("#btnUpdate").text('Save');
            } else {
                $.ajax({
                    type: "POST",
                    url: "/personal/update",
                    dataType: 'json',
                    data: $('#uploadForm').serialize(),
                    success: function (result) {

                        if (result.success) {
                            $("#btnUpdate").prop('disabled', false);
                            $("#btnUpdate").text('Save');
                            $.notifyBar({cssClass: "dg-notify-success", html: result.resultMessage, position: "bottom"});
                            if($("#passwordpanel").hasClass('in')){
                                $('#passwordpanel').collapse('hide');
                                $("#passwordpanel input").val("");
                                $("#btnUpdate").prop('disabled', false);
                                $("#btnUpdate").text('Save');
                            }
                            if($('#member_birthday').val()){$('#member_birthday').attr('disabled', 'true');}
                            //$('.date').datetimepicker('hide');
                            //location.reload();
                        } else {
                            $.notifyBar({cssClass: "dg-notify-error", html: result.resultMessage, position: "bottom"});
                            $("#btnUpdate").prop('disabled', false);
                            $("#btnUpdate").text('Save');
                        }
                    }
                });
            }
        }



    });
    $('#input-group').popover();
    cartempty();

    var dgbirthday = new Object;
        dgbirthday.year = 1980;
        $('.date').datepicker({
            startView: 2,
            defaultViewDate: dgbirthday,
            autoclose: true
        }); 

</script>
</body>
</html>
