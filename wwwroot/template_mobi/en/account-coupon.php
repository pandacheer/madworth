<?php echo $head; ?>
            <div role="main" class="ui-content">
                <div class="dg-pagetitle">My Coupons</div>
                <?php if($myCoupons):?>
                  <?php foreach ($myCoupons as $coupons_id => $couponInfo): ?>
                  	<ul data-role="listview" data-inset="true" data-theme="e">
                    	<li data-role="list-divider">Coupon : 
                    	
                    		<?php
                            	switch ($couponInfo['type']) {
                                	case 1:
                                    	echo $currency . $couponInfo['amount']/100 . ' OFF';
                                        break;
                                    case 2:
                                        echo $couponInfo['amount'] . '% OFF';
                                        break;
                                    case 3:
                                        echo 'Free Express';
                                        break;
                                    default:
                                        break;
                                 }
                              ?>
                    	</li>
                    	<li>
                            <span style="float: left;font-size: 1.2em;margin-bottom: 0.4em;">Code : <span><?php echo $coupons_id ?></span></span>
                            <span style="float: left;width: 100%;white-space: normal;color:#888;">
                                <?php
                                    if ($couponInfo['type'] == 3) {
                                        echo 'For';
                                    } else {
                                        echo ($couponInfo['type'] == 1) ? '$' . number_format($couponInfo['amount'] / 100, 2) . ' off' : $couponInfo['amount'] . '% off';
                                    }
                                    switch ($couponInfo['condition']) {
                                        case 1:
                                            echo ' any order';
                                            break;
                                        case 2:
                                            echo ' order over $' . number_format($couponInfo['min'] / 100, 2);
                                            break;
                                        case 3:
                                        	echo ' for specific products over $' . number_format($couponInfo['min'] / 100, 2);
                                            break;
                                        default:
                                            break;
                                    }
                                ?>
                            </span>
                            <br>
                            <span style="float: left;color:#888;">( Expires on <?php echo date('F d, Y', $couponInfo['end']); ?> )</span>
                        </li>
               		</ul>
                  <?php endforeach; ?>
                  <?php else:?>
                        <div class="dg-main-empty">
                            <div class="dg-main-empty-icon">
                                <span class="icon-close"></span>
                            </div>
                            <div class="dg-main-empty-msg">
                                 <span>You currently don't have any coupons.</span>
                            </div>
                        </div>
                <?php endif;?>
            </div>
            <?php echo $foot; ?>
        </div>
        
    </body>
</html>