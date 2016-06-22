<?php echo $head;?>
<div class="dg-main test">

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
		                <h4>Request a Return for Order <?php echo $country . $order_number; ?></h4>
		                <p>
		                    Please complete the form below to request an RMA number.<br/>
		                    
		                    Please tick the product you would like to return, select the quantity and the reason.
		                </p>
		                <input type="hidden" value="<?=$order_number?>" id="order_number"/>
		                <?php echo form_open('refund/apply'); ?>
		                  <table class="table table-bordered table-striped">
		                    <tr>
		                      <th width="10%"><!-- <input type="checkbox" id="checkbox"> --></th>
		                      <th width="10%">QTY</th>
		                      <th width="70%">Product Title</th>
		                      <th width="10%">Price</th>
		                    </tr>
		                    
		                    <?php foreach ($order_details as $details) : ?>
		                    <?php if ($details['product_quantity']): ?>
		                    <tr class="plist">
		                      <td><input type="radio" name="pro[]" value="<?=$details['details_id']?>"></td>
		                      <td>
		                        <select class="selectpicker" data-width="60px" name="qty[]">
		                           <?php 
		                              for ($i = 1; $i <= $details['product_quantity']; $i++){
		                              	 echo "<option value='$i'>$i</option>";
		                              }
		                           ?>
		                        </select>
		                      </td>
		                      <td><?=$details['product_name']?><?php echo  $details['product_attr'] ? '-'.$details['product_attr'] : '';?></td>
		                      <td><?= $currency ?><?= $details['payment_price'] / 100 ?></td>
		                    </tr>
		                    <?php endif; ?>
		                    <?php endforeach; ?>
		                
		               
		                  
		                  </table>

		                  <table class="dg-main-account-content-order-refund-reason">
		                    <tr>
		                      <td width="50%">
		                       <div class="form-group">
		                          <label for="exampleInputEmail1">Reason for Return</label>
		                          <select class="selectpicker" data-width="50%" id="reason">
		                            <option value="0">Please Select</option>
		                            <option value="Faulty">Faulty</option>
		                            <option value="Damaged">Damaged</option>
		                            <option value="Incorrect">Incorrect</option>
		                            <option value="Change of Mind">Change of Mind</option>
		                          </select>
		                        </div>                        
		                      </td>
		                      <!-- <td>
		                        <div class="form-group">
		                          <label for="exampleInputPassword1">Preferred Resolution</label>
		                          <select class="selectpicker" data-width="90%" id="resolution">
		                            <option value="0">Please Select</option>
		                            <option value="Store Credit">Store Credit</option>
		                            <option value="Cash Refund">Cash Refund</option>
		                          </select>
		                        </div>                       
		                      </td> -->
		                    </tr>
		                  </table>

		                  <div class="form-group">
		                     <label for="exampleInputEmail1">Details for Return</label>
		                     <textarea class="form-control" rows="3" id="details"></textarea>
		                   </div>

		                   <div class="form-group">
		                     <label for="exampleInputEmail1">Upload Image attachments</label><br>
		                     <button type="button" class="btn btn-default"><i class="fa fa-upload"></i>Upload Now</button>
		                   </div>
                          <?php if (empty($is_untreated) && !$is_Apply): ?>
		                   	<button type="button" id="goRefund" class="btn btn-success btn-lg">Submit Request</button>
		                  <?php else:?>
		                      hi  gays!!  你的退款正在处理中哦哦哦哦哦
		                  <?php endif; ?>
		                <?php echo form_close(); ?>
		                   
		              </div>
                </div>
            </div>

            <?php echo $shoppingcart?>

        </div>
    </div>
</div>  

<?php echo $foot ?>
<script>
      $(function() { 
        $('#checkbox').on('ifChecked', function(event){
		    $('input').iCheck('check');
		});
		$('#checkbox').on('ifUnchecked', function(event){
		    $('input').iCheck('uncheck');
		});     
        $('.dg-navbar').affix({
            offset: { top: $('.dg-navbar').offset().top }
        });
        
        $('input').iCheck({
          checkboxClass: 'icheckbox_square-blue',
          radioClass: 'iradio_square-blue',
          increaseArea: '20%' // optional
        });
        
        $('.selectpicker').selectpicker();

        $('#goRefund').click(function () {
        	var datajson = {};
        	$('.plist').each(function(){
            	 //alert($(this).find('input').val());
            	 if ($(this).find('input').is(":checked")) {
            	 	var suibian = $(this).find('input').val(),
            	 	qty = $(this).find('select').val();
            	 	datajson[suibian]=qty;
            	 }
            });
        	$.ajax({
                type: "POST",
                url: "<?php echo site_url('refund/apply') ?>",
                dataType: 'json',
                data: {
                	order_number :$('#order_number') .val(),
                    pro: JSON.stringify(datajson),
                    reason:$('#reason option:selected') .val(),
                    resolution:$('#resolution option:selected') .val(),
                    details:$('#details').val()
                },
                success: function (result) {
                	 if (result.success) {
                    	$.notifyBar({cssClass: "dg-notify-success",html: result.resultMessage, position: "bottom"});
                    	setTimeout(location.reload(),2000);
                    } else {
                    	$.notifyBar({cssClass: "dg-notify-error", html: result.resultMessage, position: "bottom"});
                    }
                }
            });   
        });
        
      });
      
    </script>
</body>
</html>
