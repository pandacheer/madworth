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
                    	<div class="row">
                    		<div class="col-xs-2">
                    			<a href="/collections/<?= $order_details['collection_url'] ?>/products/<?= $order_details['seo_url'] ?>" target="_blank"><img src="<?= IMAGE_DOMAIN . $order_details['image'] ?>"></a>
                    		</div>
                    		<div class="col-xs-10">
                    			<p class="dg-main-account-content-order-refund-ordernum">Request a Return for Order <?= $country . $order_details['order_number']; ?></p>
				                <p class="dg-main-account-content-order-refund-title"><a href="/collections/<?= $order_details['collection_url'] ?>/products/<?= $order_details['seo_url'] ?>" target="_blank"><?=$order_details['product_name']?></a></p>
				                <p><?=$order_details['product_attr']?></p>
	
                    		</div>
                    	</div>
		                
		                
		                <?php if (!count($refundApply)) : ?>
		                  <table class="dg-main-account-content-order-refund-reason">
		                    <input type="hidden" value="<?=$order_details['details_id']?>" id="details_id"/>
		                    <tr>
		                      <td width="50%">
		                       <div class="form-group">
		                          <label for="exampleInputEmail1">Please select your issue</label>
		                          <select class="selectpicker" data-width="50%" id="reason">
		                            <option value="Missing item">Missing item</option>
		                            <option value="Wrong item">Wrong item</option>
		                            <option value="Wrong size">Wrong size</option>
		                            <option value="Quality issues">Quality issues</option>
		                            <option value="Damaged in transit">Damaged in transit</option>
		                          </select>
		                        </div>                        
		                      </td>
		                    </tr>
		                  </table>

		                  <div class="form-group">
		                     <label for="exampleInputEmail1">Details</label>
		                     <textarea class="form-control" rows="3"  id="details"></textarea>
		                   </div>

		                   <div class="form-group">
		                     <label for="exampleInputEmail1">please attach photos showcasing the issue(s).</label><br>
		              		 <div id="fmUpload" class="dg-main-dropnone dropzones needsclick dz-clickable dz-started">
                        <div class="dz-message needsclick">
                          Drop files here or click to upload.<br>
                          ( Maximum upload file size 3mb )
                        </div>    
                       </div>
		                   </div>
		                   <button type="button" id="go_submit"  class="btn btn-success btn-lg">Submit a Request</button>


		                <?php else: ?>
		                  <?php if ($refundApply[$order_details['details_id']]['status']==2) : ?>
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
      
      $(function() {
        $("#fmUpload").dropzone({
          url: "/refund/apply",
          addRemoveLinks: true,
          autoProcessQueue:false,
          uploadMultiple: true,
          acceptedFiles: 'image/*',
          parallelUploads: 10,
          filesizeBase:1024,
          maxFilesize:3,
          maxFiles:3,
          init: function () {
            myDropzone = this;
            $("#go_submit").click(function(){
              $("#go_submit").prop('disabled', true);
              $("#go_submit").text('Saving');
              var text1=$('#details').val();
              if(!$(".dz-image-preview").length>0){
            	  $.notifyBar({cssClass: "dg-notify-error", html: 'please attach photos showcasing the issue(s).', position: "bottom"});
                  $("#go_submit").prop('disabled', false);
                  $("#go_submit").text('Submit a Request');
              }
              if(text1==''){
                $.notifyBar({cssClass: "dg-notify-error", html: 'Please fill in the details about the return.', position: "bottom"});
                $("#go_submit").prop('disabled', false);
              $("#go_submit").text('Submit a Request');
                return false;
              } 
              myDropzone.processQueue();
            });
            
            this.on("sending", function (file, xhr, formData) {
                formData.append("detailsId", $('#details_id').val());
                formData.append("reason", $('#reason').val());
                formData.append("detailsText", $('#details').val());
            });
            
            this.on("success", function (data) {
            	var res = eval('(' + data.xhr.responseText + ')');
            	if (res.success) {
              		$.notifyBar({cssClass: "dg-notify-success", html: "Your request has been submitted", position: "bottom"});
                  $("#go_submit").text('Submit a Request');
                  setTimeout("location.reload()", 2000);
              		
            	}else{
              		$.notifyBar({cssClass: "dg-notify-error", html: res.resultMessage, position: "bottom"});
                  $("#go_submit").prop('disabled', false);
                  $("#go_submit").text('Submit a Request');
            	}
            });

            this.on("maxfilesexceeded", function(file) {
            	this.removeFile(file);
            	$.notifyBar({cssClass: "dg-notify-error", html: 'Maximum 3 pictures per request are allowed.', position: "bottom"});
              $("#go_submit").prop('disabled', false);
              $("#go_submit").text('Submit a Request');
          	});

          }
          
        });
    	 
    	
    	
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
      });
      
    </script>
</body>
</html>
