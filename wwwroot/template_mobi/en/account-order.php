<?php echo $head; ?>
            <div role="main" class="ui-content">
                <div class="dg-pagetitle">My Orders</div>
                <div data-role="collapsible-set" data-theme="d" data-content-theme="d">
                <?php if($listOrders) :?>
                	<?php foreach ($listOrders as $key => $order) : ?>
                	<?php if ($key == 0): ?>
                	  <div data-role="collapsible" data-collapsed-icon="" data-expanded-icon="" data-collapsed="false">
                        <h3>Order <?=$order['order_number']?></h3>
                    <?php else:?> 
                      <div data-role="collapsible" data-collapsed-icon="" data-expanded-icon="">
                        <h3>Order <?=$order['order_number']?></h3>
                	<?php endif; ?> 
                	
                        <div class="dg-account-order-info">
                            <p>Status:<span><?php echo $order['pay_status']==2 ? 'Cancelled' : $listDispose[$order['send_status']] ?></span></p>
                            <p>Purchase Date:<span><?php echo date('d-m-Y', $order['update_time']) ?></span></p>
							<p>Estamated time of arrival:<span><?php echo date('M,d Y', strtotime('+'.($order["estimated_time"]). 'day',$order['create_time'])) ?></span></p>
                            <p>Track Number:<span><?php echo $sends[$key]['track_code'] ? $sends[$key]['track_code'] : 'Processing...' ?></span></p>

                        </div>
                        
                        <div class="dg-account-order-button">
                        	<a href="<?php echo $sends[$key]['sendUrl'] ?>" onclick="trackthisorder($(this).attr('href'));" target="_blank"  id="order_<?=$order['order_number']?>" type="button">
                           	 	<button data-theme="b" data-mini="true"><span class="icon-plane"></span>Track This Order</button>
                        	</a>
                        </div>

                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Product title</th>
                                    <th>Qty</th>
                                    <th>Price</th>
                                </tr>
                            </thead>
                            <tbody>
                               <?php foreach ($orderInfo[$key] as $orderDetails) : ?>
                                <tr>
                                    <td>
                                        <span>
                                        <a href="/collections/<?= $orderDetails['collection_url'] ?>/products/<?= $orderDetails['seo_url'] ?>"><?php echo htmlspecialchars_decode($orderDetails['product_name']) ?></a>
                                        
                                         <?php if ($orderDetails['freebies']) :?>
                                         <br>
                                         <pd class="dg-main-cart-center-color"> + <pd style="color:#ff878c;"><?= $currency ?><?= ($orderDetails['payment_amount']/100) ?></pd> Additional Shipping Fee</pd>
                                         <?php endif;?>
                                        
                                        </span>
                                        <?php echo $orderDetails['product_attr'] ?>
                                    </td>
                                    <td><?php echo $orderDetails['product_quantity'] ?></td>
                                    <td><?= $currency ?><?php echo $orderDetails['freebies']?"0":($orderDetails['payment_amount']/100) ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                        <div class="dg-account-order-subtatol">
                            <table>
                            <?php if ($order['note']):?>
                                <tr>
                                    <td style="float: right">Note:</td>
                                    <td><?=$order['note']?></td>
                                </tr>
                            <?php endif; ?>
                            <tr>
                                <td style="float: right">Item Price :</td>
                                <td><?= $currency ?><?=$order['order_amount']/100?></td>
                            </tr>
                            <tr>
                                <td style="float: right">Shipping Fee :</td>
                                <td><?= $currency ?><?=$order['freight_amount']/100?></td>
                            </tr>
                            <?php if ($order['order_insurance']):?>
                                <tr>
                                    <td style="float: right">Shipping Insurance :</td>
                                    <td><?= $currency ?><?=$order['order_insurance']/100?></td>
                                </tr>
                            <?php endif; ?>
                            <?php if ($order['order_giftbox']):?>
                                <tr>
                                    <td style="float: right">Shopping Bag : </td>
                                    <td><?= $currency ?><?=$order['order_giftbox']/100?></td>
                                </tr>
                            <?php endif; ?>
                            <?php if ($order['coupons_id']):?>
                                <tr>
                                    <td style="float: right">Coupon :</td>
                                    <td>-<?= $currency ?><?=$order['offers_amount']/100?></td>
                                </tr>
                            <?php endif; ?>
                            <tr>
                                <td style="float: right">Total :</td>
                                <td><?= $currency ?><?=$order['payment_amount']/100?></td>
                            </tr>
                            </table>
                        </div>  
                        
                    </div>
                    <?php endforeach; ?>
                   <?php else:?>
                   <div class="dg-main-empty">
                    	<div class="dg-main-empty-icon">
                        	<span class="icon-cart"></span>
                    	</div>
                    	<div class="dg-main-empty-msg">
                         	<span>You currently don't have any orders.</span>
                    	</div>
                    	<a href="/"><button data-role="none">Go Shopping</button></a>
                	</div>
                   <?php endif; ?> 
                    
                    
                    <!-- <div data-role="collapsible" data-collapsed-icon="flat-calendar" data-expanded-icon="flat-cross">
                        <h3>Section 2</h3>
                        <p>I'm the collapsible content for section 2</p>
                    </div>
                    <div data-role="collapsible" data-collapsed-icon="flat-settings" data-expanded-icon="flat-cross">
                        <h3>Section 3</h3>
                        <p>I'm the collapsible content for section 3</p>
                    </div> -->
                
                </div>
            </div>
            <?php echo $foot; ?>
        </div>
        
        <script>
        $(function() {
        $(".ui-collapsible-heading").click(function(){
            $(document).scrollTop( $(this).offset().top-92);
        })
    });
      //my orders 'Track This Order' button
        function trackthisorder(shref){
            if (shref == "javascript:void(0);") {
                $.notifyBar({ cssClass: "dg-notify-error", html: "Please be patient while the tracking number is being generated." ,position: "bottom" });
            };
        }
        </script>
    </body>
</html>