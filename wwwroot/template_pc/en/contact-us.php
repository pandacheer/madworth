<?php echo $head; ?>
<div class="dg-main">
    <div class="container">
        <div class="row">
            <div class="col-xs-10 col-xs-12">
                <div class="dg-main-contact-us">
                    <div class="dg-contactus-title">How can we help you?</div>
                    <div class="row">
                        <div class="col-xs-8">
                            <p>
                                <label>
                                    <input type="radio" id="about-us" value="1" class="contack_type" name="contack_type">
                                    <button class="dg-main-contact-us-button">
                                        I want to know something about DrGrab.
                                    </button>
                                </label>        
                            </p>
<!--
                            <p>
                                <label >
                                    <input type="radio" id="payment" value="2" class="contack_type" name="contack_type">
                                    <button class="dg-main-contact-us-button">
                                        I am having trouble in making the payment.
                                    </button>
                                </label>
                            </p>
-->
                            <p>
                                <label>
                                    <input type="radio" id="delivery" value="3" class="contack_type" name="contack_type">
                                    <button class="dg-main-contact-us-button">
                                        I want to know more about the delivery process.
                                    </button>
                                </label>
                            </p>
                            <p>
                                <label>
                                    <input type="radio" id="problem" value="4" class="contack_type" name="contack_type">
                                    <button class="dg-main-contact-us-button">
                                        I want to report a problem about my order.
                                    </button>
                                </label>
                            </p>
                            <p>
                                <label>
                                    <input type="radio" id="vendor" value="5" class="contack_type" name="contack_type">
                                    <button class="dg-main-contact-us-button">
                                        I want to be a DrGrab vendor.
                                    </button>
                                </label>
                            </p>
                            <p>
                                <label>
                                    <input type="radio" id="advice" value="6" class="contack_type" name="contack_type">
                                    <button class="dg-main-contact-us-button">
                                        I would like to make a suggestion.
                                    </button>
                                </label>
                            </p>
                            <p>
                                <label>
                                    <input type="radio" id="mentioned" value="7" class="contack_type" name="contack_type">
                                    <button class="dg-main-contact-us-button">
                                       None of the above.
                                    </button>
                                </label>
                            </p>
                        </div>
                        <div class="col-xs-4">
<!--                             <img src="http://static.catchoftheworld.com:1234/product/PRO-10393/PRO-10393.jpg" style="width: 100%"> -->
                        </div>
                    </div>
                    
                    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content" style="margin-top: 200px;">
                              <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                <h4 class="modal-title" id="myModalLabel">Redirecting..</h4>
                              </div>
                              <div class="modal-body" style="font-size: 18px;">
                                We are taking you to a different page to find out the answer.<br> Do you want to continue?
                              </div>
                              <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                <button type="button" class="btn btn-primary" id="confirm">Continue</button>
                              </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="dg-main-contact-us-form">
                        <br>
                        <div class="form-group" style="display: none" id="vendor-form">
                            <label for="email">Please leave your email address and we shall get back to you.</label>
                            <input type="text"  id="email" class="form-control" placeholder="Email Address" value="<?php echo $this->session->userdata('member_email') ?>">     
                            <label for="email">I want to say..</label>
                            <textarea class="form-control" rows="3" id="content" maxlength="1000"></textarea>
                            <div class="form-group" id="verifyCode-div" <?php echo ($this->session->userdata('Verification')) && $this->session->userdata('Verification')['clickTimes'] > 2 ? 'style="display:block"' : 'style="display:none"' ?> >
                            	<label for="verifyCode">Verify Code</label>
                                <input type="text" class="form-control"  id="verifyCode">
                                <div class="dg-main-reg-table-left-code">
                                	<img src="/reg/vcode" id="randVcode" onclick="this.src = '/reg/vcode/' + Math.random()"/>
                                </div>
                            </div>
                            <button type="button" class="btn btn-success btn-lg" id="addContact">Submit</button>   
                        </div>
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

    	$('#addContact').click(function () {
    	if (($('#email').val() === '') || (!(/^[\w!#$%&'*+/=?^_`{|}~-]+(?:\.[\w!#$%&'*+/=?^_`{|}~-]+)*@(?:[\w](?:[\w-]*[\w])?\.)+[\w](?:[\w-]*[\w])?$/i.test($('#email').val())))) {
            $.notifyBar({cssClass: "dg-notify-error", html: 'Your email address is invalid', position: "bottom"});
            $('#email').css("border-color", "red");
            return false;
        }

            $.ajax({
                type: "POST",
                url: "<?php echo site_url('contact/addContact') ?>",
                dataType: 'json',
                data: {
              	  contack_type:$(".contack_type:checked").val(),
              	  email:$("#email").val(),
              	  content:$("#content").val(),
              	  verifyCode: $('#verifyCode').val()
                },
                success: function (result) {
                	if (result.clickTimes > 2) {
                         $("#verifyCode-div").css("display", "block");
                    }
                    
                	if (result.success) {
                		$("#content").val("");
                		$('#verifyCode').val("");
                		$('#randVcode').trigger("click");
                  		$.notifyBar({cssClass: "dg-notify-success", html: "Thank you! We have received your message", position: "bottom"});
                        setTimeout(location.href="/home/showSuccess/S2003",5000);
                	}else{
                		$('#randVcode').trigger("click");
                  		$.notifyBar({cssClass: "dg-notify-error", html: result.resultMessage, position: "bottom"});
                	}
                }
            });

        });


        
        $('input').iCheck({
            checkboxClass: 'icheckbox_square-blue',
            radioClass: 'iradio_square-blue',
            increaseArea: '20%' // optional
        });

        $('.selectpicker').selectpicker();
    });

    $('#about-us').on('ifChecked', function(event){
      $('#myModal').modal();
      $('#confirm').click(function(){
        location.href="/pages/about-us";
      })
    });

    $('#payment').on('ifChecked', function(event){
      $('#myModal').modal();
      $('#confirm').click(function(){
        location.href="/";
      })
    });

    $('#delivery').on('ifChecked', function(event){
      $('#myModal').modal();
      $('#confirm').click(function(){
        location.href="/pages/faq#shipping";
      })
    });

    $('#problem').on('ifChecked', function(event){
      $('#myModal').modal();
      $('#confirm').click(function(){
        location.href="/personal/order";
      })
    });
   
    $('#vendor,#advice,#mentioned').on('ifChecked', function(event){
        $('#vendor-form').show();
    });
    $('#vendor,#advice,#mentioned').on('ifUnchecked', function(event){
        $('#vendor-form').hide();
    });
</script>
</body>
</html>
