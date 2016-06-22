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

                                <a class="dg-main-account-menu-tab dg-main-account-menu-tab-orders " href="/personal/order"><div class="icon"></div><span class="text">My Orders</span></a>
                                <a class="dg-main-account-menu-tab dg-main-account-menu-tab-coupon active"><div class="icon"></div><span class="text">My Coupons</span></a>

                                <a class="dg-main-account-menu-tab dg-main-account-menu-tab-address" href="/personal/address"><div class="icon"></div><span class="text">Address</span></a>

                                <a class="dg-main-account-menu-tab dg-main-account-menu-tab-info " href="/pages/faq"><div class="icon"></div><span class="text">Need Some Help?</span></a>
                            </div>
                            <div class="dg-main-account-content dg-main-account-content-coupon">
                                <h4>My Coupons</h4>
                                <?php if($myCoupons):?>
                                <div class="dg-main-account-content-coupon-list clearfix">
                                    <?php foreach ($myCoupons as $coupons_id => $couponInfo): ?>
                                        <div class="thumbnail">
                                            <?php
                                                switch ($couponInfo['type']) {
                                                    case 1:
                                                        $m = $currency . $couponInfo['amount']/100 . ' OFF';
                                                        break;
                                                    case 2:
                                                        $m = $couponInfo['amount'] . '% OFF';
                                                        break;
                                                    case 3:
                                                        $m = 'Free Express';
                                                        break;
                                                    default:
                                                        break;
                                                }
                                                ?>
                                            <div class="dg-main-account-content-coupon-percent<?php if(strlen($m)>10)echo " dg-short-text";?>">
                                                <?php echo $m;?>
                                            </div>
                                            <div class="caption">
                                                <h3><?php echo $coupons_id ?></h3>
                                                <div class="text-muted">
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
                                                                    //echo ' order containing specific products';
                                                                    break;
                                                                default:
                                                                    break;
                                                            }
                                                            ?>
                                                </div>
                                                <div class="text-muted">( Expire on <?php echo date('F d, Y', $couponInfo['end']); ?> )</div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php else:?>
                                <div class="dg-main-thankyou">
                                  <div class="dg-main-reset-ticker">
                                    <i class="fa fa-map-o"></i>
                                    <div class="dg-main-thankyou-ticker-thanktitle">You currently don't have any coupons.</div>
                                  </div>
                                </div>
                            <?php endif;?>
                            </div>
                        </div>

                    </div>

                    <?php echo $shoppingcart?>

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
cartempty();
        </script>

    </body>
</html>