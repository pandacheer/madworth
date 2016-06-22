<?php echo $head; ?>
<link href="<?php echo $cdn ?>css/star-rating.min.css" rel="stylesheet">

<div class="modal fade" id="account-order-comment">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Product Review</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-4"><img src="<?php echo $cdn ?>image/testitem2/4.jpg" alt="" width="170" id="product_image"></div>
                    <div class="col-sm-8">
                        <h5 id="product_title"></h5>
                        <input type="hidden" name="star_id" id="star_id">
                        <form id="productCommentForm">
                            <input type="hidden" name="order_number" id="order_number">
                            <input type="hidden" name="product_id" id="product_id">
                            <input type="hidden" name="details_id" id="details_id">
                            <input id="rating-input" type="number" value="" name="star" />
                            <textarea name="product_comment" class="form-control" id="product_comment" cols="30" rows="3"></textarea>
                        </form>
                    </div>
                    <div class="clearfix"></div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default review-close" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="saveComment" data-dismiss="modal">Save Review</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
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

                        <a class="dg-main-account-menu-tab dg-main-account-menu-tab-orders active"><div class="icon"></div><span class="text">My Orders</span></a>
                        <a class="dg-main-account-menu-tab dg-main-account-menu-tab-coupon " href="/personal/coupon"><div class="icon"></div><span class="text">My Coupons</span></a>

                        <a class="dg-main-account-menu-tab dg-main-account-menu-tab-address " href="/personal/address"><div class="icon"></div><span class="text">Address</span></a>

                        <a class="dg-main-account-menu-tab dg-main-account-menu-tab-info " href="/pages/faq"><div class="icon"></div><span class="text">Need Some Help?</span></a>
                    </div>
                    <?php if ($listOrders) : ?>
                        <div class="dg-main-account-content">
                            <h4>My Orders</h4>
                            <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">

                                <?php foreach ($listOrders as $key => $order) : ?>
                                    <div class="panel panel-default" id="<?php echo $order['order_id']; ?>">
                                        <div class="panel panel-default dg-main-account-order-loading" style="display: none">
                                            <div class="panel-body">
                                                <img src="<?php echo $cdn ?>image/grab2.gif">
                                            </div>
                                        </div>
                                        <div class="panel-heading" role="tab" id="heading<?php echo $order['order_id']; ?>">
                                            <a class="dg-main-account-content-order-title" data-toggle="collapse" data-parent="#accordion" href="#collapse<?php echo $order['order_id']; ?>" aria-expanded="false" aria-controls="collapse<?php echo $order['order_id']; ?>">
                                                <h3 class="panel-title" data-orderid="<?= $order['order_id'] ?>" data-bind="<?php echo ($key === 0) ? 1 : 0; ?>" data-loading="true">
                                                    Order <?php echo $country . $order['order_number']; ?> - <?php echo $order['pay_status']==2 ? 'Cancelled' : $listDispose[$order['send_status']] ?>
                                                    <span class="glyphicon glyphicon-chevron-down pull-right" aria-hidden="true"></span>
                                                </h3>
                                            </a>
                                        </div>


                                        <div id="collapse<?php echo $order['order_id']; ?>" class="panel-collapse collapse <?php echo ($key == 0) ? 'in' : '' ?>" role="tabpanel" aria-labelledby="heading<?php echo $order['order_id']; ?>">
                                            <div class="panel-body" id="panelBody<?php echo $order['order_id']; ?>">
                                                <?php if ($key == 0): ?>
                                                    <div class="row">
                                                        <div class="col-xs-12">
                                                            <div class="row bs-wizard" style="border-bottom:0;">
                                                                <div class="col-xs-4 bs-wizard-step complete-full">
                                                                    <div class="text-center bs-wizard-stepnum">Order Received</div>
                                                                    <div class="progress"><div class="progress-bar"></div></div>
                                                                    <a href="javascript:void(0);" class="bs-wizard-dot"></a>
                                                                    <div class="bs-wizard-info text-center"><?php echo date('M, d Y', $order['create_time']) ?> <br/> <?php echo date('h:i:s', $order['create_time']) ?> </div>
                                                                </div>

                                                                <div class="col-xs-4 bs-wizard-step <?php echo $order['pay_status']==2 ?'complete-full' : $orderInfo['send']['first'] ? 'complete-full' : 'complete' ?>">
                                                                    <div class="text-center bs-wizard-stepnum">Dispatching</div>
                                                                    <div class="progress"><div class="progress-bar"></div></div>
                                                                    <a href="javascript:void(0);" class="bs-wizard-dot"></a>
                                                                    <div class="bs-wizard-info text-center"><?php echo date('M, d Y', $order['create_time']) ?></div>
                                                                </div>
                                                                <?php
                                                                /*
                                                                  <div class="col-xs-4 bs-wizard-step <?php echo $orderInfo['send']['first'] ? 'active' : 'disabled' ?>">
                                                                  <div class="text-center bs-wizard-stepnum">Dispatched</div>
                                                                  <div class="progress">
                                                                  <div class="progress-bar"></div>
                                                                  <div class="progress-bar"></div>
                                                                  </div>
                                                                  <a href="javascript:void(0);" class="bs-wizard-dot"></a>
                                                                  <div class="bs-wizard-info text-center"><?php echo $orderInfo['send']['first'] ?> </div>
                                                                  </div>
                                                                 */
                                                                ?>
																 <?php if ($order['pay_status']==2): ?>
																 	<div class="col-xs-4 bs-wizard-step  active">
                                                                    	<div class="text-center bs-wizard-stepnum">Cancelled</div>
                                                                    	<div class="progress"><div class="progress-bar"></div></div>
                                                                    	<a href="javascript:void(0);" class="bs-wizard-dot"></a>
                                                                	</div>
																 <?php else: ?>
																 	<div class="col-xs-4 bs-wizard-step <?php echo $orderInfo['send']['last'] ? 'active' : 'disabled' ?>">
                                                                    	<div class="text-center bs-wizard-stepnum">Shipped</div>
                                                                    	<div class="progress"><div class="progress-bar"></div></div>
                                                                    	<a href="javascript:void(0);" class="bs-wizard-dot"></a>
                                                                    	<div class="bs-wizard-info text-center"><?php echo $orderInfo['send']['last'] ?> </div>
                                                                	</div>	
																 <?php endif; ?>   
                                                                
                                                            </div>        
                                                        </div>
                                                    </div>
                                                     
                                                    <div class="row">
                                                        <div class="col-xs-6">
                                                            <div class="dg-main-account-content-orderrefund">
                                                                <span class="dg-main-account-content-orderrefund-title">Tracking Number:</span><br/>
                                                                <?php echo $url ? ($url['track_code'] ? $url['track_code']:'Processing...') : 'Processing...' ?>
                                                            </div>

                                                            <a href="<?= $sendUrl ?>"  target="_blank" onclick="trackthisorder($(this).attr('href'));" id="order_<?= $order['order_number'] ?>" type="button" class="btn btn-default btn-md">
                                                                <span class="glyphicon glyphicon-plane" aria-hidden="true"></span> Track This Order
                                                            </a>

                                                        </div>

                                                        <div class="col-xs-6 dg-main-account-content-buttons">
                                                            <div class="dg-main-account-content-orderrefund">
                                                                <span class="dg-main-account-content-orderrefund-title">Estimated Time of Arrival:</span><br/>
                                                                <?php echo date('M, d Y', strtotime('+' . $order["estimated_time"] . 'day', $order['create_time'])) ?>
                                                            </div>

                                                            <a href="<?php echo $applyStatus==1 ? 'javascript:void(0);' : '/refund/orderLost/'.$order["order_number"].'' ?>"  onclick="orderLostApply($(this).attr('href'),'<?=$times ?>');">
                                                            <button type="button" class="btn btn-default btn-md">
                                                                <i class="fa fa-frown-o"></i> Still Haven't Received?
                                                            </button>
                                                            </a>
                                                            
                                                        </div>
                                                        
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-xs-6">
                                                            <div class="dg-main-account-content-orderrefund">
                                                                <span class="dg-main-account-content-orderrefund-title">Shipping Address:</span><br/>
                                                                <?php echo $orderInfo['ship']['receive_firstName'] . ' ' . $orderInfo['ship']['receive_lastName'] ?><br/>
                                                                <?php if ($orderInfo['ship']['receive_add2']): ?>	 
                                                                    <?php echo $orderInfo['ship']['receive_add2'] ?> / <?php echo $orderInfo['ship']['receive_add1'] ?><br/>
                                                                <?php else: ?>
                                                                    <?php echo $orderInfo['ship']['receive_add1'] ?><br/>
                                                                <?php endif; ?>   
                                                                <?php echo $orderInfo['ship']['receive_city'] ?> , <?php echo $orderInfo['ship']['receive_zipcode'] ?><br/>
                                                                <?php echo $orderInfo['ship']['receive_province'] ?><br/>
                                                                <?php echo $orderInfo['ship']['receive_country'] ?><br/>
                                                            </div>
                                                        </div>
                                                        <div class="col-xs-6">
                                                            <div class="dg-main-account-content-orderrefund">
                                                                <span class="dg-main-account-content-orderrefund-title">Order Summary:</span><br/>
                                                                Item Price : <?= $currency ?><?=$orders['order_amount']/100?><br/>
                                                                Shipping Price : <?= $currency ?><?=$orders['freight_amount']/100?><br/>
                                                                <?php if ($orders['order_insurance']):?>
                                                                	Shipping Insurance : <?= $currency ?><?=$orders['order_insurance']/100?><br/>
                                                                <?php endif; ?>
                                                                <?php if ($orders['order_giftbox']):?>
                                                                	Shopping Bag : <?= $currency ?><?=$orders['order_giftbox']/100?><br/>
                                                                <?php endif; ?>
                                                                <?php if ($orders['coupons_id']):?>
                                                                	Coupon : -<?= $currency ?><?=$orders['offers_amount']/100?><br/>
                                                                <?php endif; ?>
                                                                <b>Total : <?= $currency ?><?=$orders['payment_amount']/100?></b>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="dg-main-account-content-items">

                                                        <table class="table table-hover">
                                                            <thead>
                                                                <tr>
                                                                    <th>Image</th>
                                                                    <th>Product title</th>
                                                                    <th style="text-align: center">Qty</th>
                                                                    <th style="text-align: center"><!-- Operating -->Price</th>
                                                                    <th style="text-align: center">Review</th>
                                                                    <th style="text-align: center">Support</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <?php $i=0; foreach ($orderInfo['details'] as $orderDetails) : ++$i?>
                                                                    <tr>
                                                                        <td><img src="<?php echo IMAGE_DOMAIN . $orderDetails['image'] ?>" alt="" ></td>
                                                                        <td>
                                                                            <a href="/collections/<?= $orderDetails['collection_url'] ?>/products/<?= $orderDetails['seo_url'] ?>"><?php echo htmlspecialchars_decode($orderDetails['product_name']) ?></a><span><?php echo $orderDetails['product_attr'] ?></span>
                                                                            <?php if ($orderDetails['freebies']) :?>
                                                                            <div class="freebies">+<?= $currency ?><span><?= ($orderDetails['payment_amount'] / 100) ?></span> Additional Shipping Fee</div>
                                                                            <?php endif;?>
                                                                        </td>
                                                                        <td><?php echo $orderDetails['product_quantity'] ?></td>
                                                                        <td><?= $currency ?><?php echo $orderDetails['freebies']?"0":($orderDetails['payment_amount'] / 100) ?><!--input type="text" data-size="xxs" data-toggle="modal" data-target="#account-order-comment" data-step="1" data-order_number="<?php //echo $order['order_number']   ?>" data-product_sku="<?php //echo $orderDetails['product_sku']   ?>" data-product_name="<?php //echo $orderDetails['product_name']   ?>" data-product_id="<?php //echo $orderDetails['product_id']   ?>"  data-whatever="@mdo" class="rating" value="<?php //echo $orderDetails['comments_star']   ?>"--></td>
                                                                        <td><input type="text" data-size="xxxs" data-toggle="modal" data-target="#account-order-comment" data-step="1" data-details_id="<?php echo $orderDetails['details_id'] ?>" data-order_number="<?php echo $order['order_number'] ?>" data-product_sku="<?php echo $orderDetails['product_sku'] ?>" data-product_name="<?php echo $orderDetails['product_name'] ?>" data-product_id="<?php echo $orderDetails['product_id'] ?>" data-product_image="<?php echo IMAGE_DOMAIN . $orderDetails['image'] ?>"  data-whatever="@mdo" class="rating" value="<?php echo $orderDetails['comments_star'] ?>" id="star<?php echo $orderDetails['details_id'] ?>"></td>
                                                                        <td><a href="/refund/index/<?=$orderDetails["details_id"] ?>"><button class="btn btn-default">Support</button></a>
                                                                        </td>
                                                                    </tr>
                                                                <?php endforeach; ?>
                                                            </tbody>
                                                        </table>
                                                        <?php if ($note['order_guestbook']):?>
                                                            <br>
                                                        	<b>Note:</b> <?=$note['order_guestbook']?>
                                                        <?php endif; ?>
                                                    </div>


                                                <?php endif; ?>
                                            </div>
                                        </div>
                                        
                                    </div>
                                    
                                <?php endforeach; ?>

                            </div>                
                        </div>
                    <?php else: ?>
                        <div class="dg-main-account-content">
                            <h4>My Orders</h4>
                            <div class="dg-main-thankyou">
                                <div class="dg-main-reset-ticker">
                                    <i class="fa fa-map-o"></i>
                                    <div class="dg-main-thankyou-ticker-thanktitle">You have not placed any orders!</div>
                                    <a href="/" class="btn btn-default btn-lg">Go Shopping</a>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>

            </div>

            <?php echo $shoppingcart ?>

        </div>
    </div>
</div>  

<?php echo $foot ?>

<script>

    $('.rating').each(function (e) {
        if ($(this).val() > 0) {
            $(this).rating({
                disabled: true
            });
        } else {
            $('.rating').on('rating.change', function () {

                $('#account-order-comment').modal({
                    show: true
                });
                $val = $(this).val();
                $('#rating-input').val($val);
                
                $('#product_title').html($(this).data('product_name'));
                $('#product_id').val($(this).data('product_id'));
                $('#order_number').val($(this).data('order_number'));
                $('#details_id').val($(this).data('details_id'));
                $('#star_id').val($(this).attr('id'));
                $('#product_image').attr('src', $(this).data('product_image'));

                $('#account-order-comment').on('show.bs.modal', function (e) {
                    $('#product_comment').val(' ');
                });
                $('#account-order-comment').on('shown.bs.modal', function (e) {
                    $('#product_comment').val(' ');
                    $('#account-order-comment .rating-stars').css({width: $val * 20 + "%"});
                });
            });
        }
        ;
    });

    $('#rating-input').rating({
        min: 0,
        max: 5,
        step: 1,
        size: 'ss',
        disabled: false,
        showClear: false
    });

    $('body').on('click','.review-close',function(){
         $('#'+$('#star_id').val()).rating('clear');
    })
    
    $('body').on('click','#saveComment',function(){
        $.ajax({
            type: "POST",
            url: "<?php echo site_url('comment/insert') ?>",
            dataType: 'json',
            data: $("#productCommentForm").serialize(),
            success: function (result) {
                if (result.success) {

                    $('#'+$('#star_id').val()).val(result.comments_star); 
                    $('#'+$('#star_id').val()).rating('refresh', {disabled: true, showClear: false, showCaption: false});
                    $.notifyBar({cssClass: "dg-notify-success", html: "Your review has been submitted successfully!", position: "bottom"});
                } else {
                    $.notifyBar({cssClass: "dg-notify-error", html: result.resultMessage, position: "bottom"});
                }
            }
        });
        $('#account-order-comment').on('hide.bs.modal', function () {
            $('#product_comment').empty();
        });
    })
    
    $('[data-orderid]').click(function () {
        var $id=$(this).parent().parent().parent().attr('id');
        
        var loading=$(this).data('loading');
        if(loading){
            $('#'+$id).addClass('dg-main-account-order-loading-height');
            $('#'+$id).find('.dg-main-account-order-loading').show();
        }
        
        $('#'+$id).on('show.bs.collapse',function(){
            $('body,html').animate({scrollTop:290},0);
        });

        $('#'+$id).on('hide.bs.collapse',function(){
            $('#'+$id).find('.dg-main-account-order-loading').hide();
            $('#'+$id).removeClass('dg-main-account-order-loading-height');
        });

        if (parseInt($(this).data('bind')) === 0) {
            var order_id = $(this).data('orderid');
            var that = this;
            $.post('/personal/ajaxOrder', {
                order_id: order_id
            }, function (result) {
                if (result.success) {

                    $('#'+$id).removeClass('dg-main-account-order-loading-height');
                    $('#'+$id).find('.dg-main-account-order-loading').hide();
                    $('#'+$id).find('.panel-title').data('loading',false);
                     
                    $('#panelBody' + order_id).prepend(result.html);

                    //ajax加载成功后执行js
                    $('input').on('rating.change', function(event) {
                        var id='star'+$(this).data('details_id');

                        $('body').on('click','.review-close',function(){
                            $('#'+id).rating('clear');
                        })
                        $('#saveComment').click(function () {
                            $('#'+id).rating('refresh', {disabled: true, showClear: false, showCaption: false});
                        });
                    });

                    $('#panelBody' + order_id).find('input').each(function () {
                        if ($(this).val() > 0) {
                            $(this).rating({
                                disabled: true
                            });
                        } else {
                            $(this).rating({
                                min: 0,
                                max: 5,
                                step: 1,
                                size: 'ss',
                                disabled: false,
                                showClear: false
                            });
                            $('.rating').on('rating.change', function () {

                                $('#account-order-comment').modal({
                                    show: true
                                });
                                $val = $(this).val();
                                $('#rating-input').val($val);
                                $('#product_title').html($(this).data('product_name'));
                                $('#details_id').val($(this).data('details_id'));
                                $('#product_id').val($(this).data('product_id'));
                                $('#order_number').val($(this).data('order_number'));
                                $('#product_image').attr('src', $(this).data('product_image'));

                                $('#account-order-comment').on('show.bs.modal', function (e) {
                                    $('#product_comment').val(' ');
                                });
                                $('#account-order-comment').on('shown.bs.modal', function (e) {
                                    $('#account-order-comment .rating-stars').css({width: $val * 20 + "%"});
                                });
                            });
                        };
                    });
                    $(that).data('bind', 1);
                }
            }, 'json');
        }
    });

    cartempty();
    
    $(function() {
        $('.panel').eq(0).find('h3').data('loading',false);
    });    
</script>
</body>
</html>
