<?php echo $head; ?>

<div role="main" class="ui-content">
   <div class="dg-pagetitle">How can we help you?</div>
   <div class="dg-main-contact-us">
       <p id="something"><a href="/pages/about-us">I want to know something about DrGrab.</a></p>
       <p id="delivery"><a href="/pages/faq#shipping">I want to know more about the delivery process.</a></p>
       <p id="problem"><a href="/personal/order">I want to report a problem about my order.</a></p>
       <p id="vendor"><a href=""> I want to be a DrGrab vendor.</a></p>
       <p id="suggestion"><a href="">I would like to make a suggestion.</a></p>
       <p id="above"><a href="">None of the above.</a></p>

       <div class="dg-main-contact-us-content" style="display: none">
           <p>Please leave your email address and we shall get back to you.</p>
           <input type="text" id="email"  placeholder="Email Address" name="email-input" value="<?php echo $this->session->userdata('member_email') ?>">
           <p>I want to say..</p>
           <textarea maxlength="1000" id="content" name="email-textarea"></textarea>
           <input type="hidden" value="" id="contack_type"/>
           
           <div id="verifyCode-div" <?php echo ($this->session->userdata('Verification')) && $this->session->userdata('Verification')['clickTimes'] > 2 ? 'style="display:block"' : 'style="display:none"' ?>>
                
                <label for="verifyCode">Verify Code</label>
                <input type="text" class="form-control" id="verifyCode" name="myCode">
                <div class="dg-main-login-code">
                    <img id="Login_Img" src="/reg/vcode" onclick="this.src = '/reg/vcode?k=' + Math.random()"/>
                </div>
            </div>
           <button  type="button" data-theme="g" class="dg-account-button" id="addContact" data-ajax="false">Submit</button>
           <button  type="button" data-theme="c" id="close">Cancel</button>
       </div>
       
   </div>
   
</div>
<?php echo $foot; ?>
<script>

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
          	  contack_type:$("#contack_type").val(),
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


    $('#vendor,#suggestion,#above').on('click', function(event){
        $('.dg-main-contact-us-content').show();
    });

    $('#vendor').click(function(){
      $('#suggestion,#above,#something,#delivery,#problem').hide()
      $('#contack_type').val(5);
    });
    $('#suggestion').click(function(){
      $('#vendor,#above,#something,#delivery,#problem').hide()
      $('#contack_type').val(6);
    });
    $('#above').click(function(){
      $('#suggestion,#vendor,#something,#delivery,#problem').hide()
      $('#contack_type').val(7);
    });
    
    $('#close').click(function(){
      $('#vendor,#suggestion,#above,#something,#delivery,#problem').show();
      $('.dg-main-contact-us-content').hide();
    })
    $('#something,#delivery,#problem').on('click', function(event){
        $('.dg-main-contact-us-content').hide();
    });
</script>
</div>