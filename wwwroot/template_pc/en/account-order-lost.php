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
                        <a class="dg-main-account-menu-tab dg-main-account-menu-tab-detail" href="/personal"><div class="icon"></div><span class="text">Personal Details</span></a>


                        <a class="dg-main-account-menu-tab dg-main-account-menu-tab-orders active" href="/personal/order"><div class="icon"></div><span class="text">My Orders</span></a>
                        <a class="dg-main-account-menu-tab dg-main-account-menu-tab-coupon " href="/personal/coupon"><div class="icon"></div><span class="text">My Coupons</span></a>

                        <a class="dg-main-account-menu-tab dg-main-account-menu-tab-address"><div class="icon"></div><span class="text">Address</span></a>

                        <a class="dg-main-account-menu-tab dg-main-account-menu-tab-info " href="/pages/faq"><div class="icon"></div><span class="text">Need Some Help?</span></a>
                    </div>
                    
                    
                    
                    <div class="dg-main-account-content dg-main-account-content-order-refund">
                    	<h4><a href="javascript:history.go(-1)"><i class="fa fa-angle-left"></i> Back to Order Details</a></h4>
						
                    <?php if (!count($refundApply)) : ?>
		                  <div class="form-group">
		                     <label for="exampleInputEmail1">We are sorry for the inconvenience caused. <br>Please provide us your order details and our Customer Support Team(CST) shall get back to you soon. </label>
		                     <input type="hidden" value="<?=$order_number?>"  id="orderNumber"/>
		                     <textarea class="form-control"   id="details" id="details"></textarea>
		                   </div>
		                   <button type="button"  id="go_submit"  class="btn btn-success btn-lg">Submit Request</button>  	                 
		       

		            <?php else: ?>
		            	<?php if ($refundApply[$order_number]['status']==2) : ?>
	                        <div class="dg-main-thankyou-ticker">
                                <i class="fa fa-check-circle fa-lg"></i>
                                <div class="dg-main-thankyou-ticker-thankdesc">
                                  Your request has already processed and confirmed. For further details, please check your registered email ID.
                                </div>
                            </div>
		                <?php else: ?>
                            <div class="dg-main-thankyou-ticker">
                                <i class="fa fa-check-circle fa-lg"></i>
                                <div class="dg-main-thankyou-ticker-thankdesc">
                                    Your request has been submitted successfully.<br>
                                    Our friendly support staff will respond back to you within 24 hours or the next business day. 
                                </div>
                            </div>
		                 <?php endif; ?>
		            <?php endif; ?>
		            
		            
		         </div>   
                </div>
            </div>

            <?php echo $shoppingcart?>

        </div>
    </div>
</div>  
<?php echo $foot ?>
 <script>
    $(document).ready(function() {
    	 $('#go_submit').click(function () {
                $("#go_submit").prop('disabled', true);
                $("#go_submit").text('Saving');
    	        $.ajax({
    	            type: "POST",
    	            url: "<?php echo site_url('refund/orderLostApply') ?>",
    	            dataType: 'json',
    	            data: {
    	            	orderNumber: $('#orderNumber').val(),
    	            	details: $('#details').val(),
                    },
    	            success: function (result) {
    	            	if (result.success) {
    	              		$.notifyBar({cssClass: "dg-notify-success", html: "Your request has been submitted", position: "bottom"});
                            $("#go_submit").prop('disabled', false);
                            $("#go_submit").text('Submit Request');
    	              		setTimeout("location.reload()", 2000);
    	            	}else{
    	              		$.notifyBar({cssClass: "dg-notify-error", html: result.resultMessage, position: "bottom"});
                            $("#go_submit").prop('disabled', false);
                            $("#go_submit").text('Submit Request');
    	            	}
    	            }
    	        });
    	    });
  
    });
</script> 
                
</body>
</html>
