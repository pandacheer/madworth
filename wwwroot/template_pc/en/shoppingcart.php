<div class="col-xs-2 dg-cartfly">
    <div class="dg-trust ">
        <div id="cartflysidebar">
            <div class="mui-mbar-tabs">
                
                <div id="quick_links" class="quick_links quick_links2" data-container="#cart-popover" data-toggle="popover" data-placement="bottom" 
                    data-content="<div class='popover-content'>Your cart is empty.  To help start you out, click on one of the links below:<div><a href='/'>- Our Latest Products</a></div><div><a href='/pages/about-us'>- About Us</a></div></div>" 
                    data-html="<div class='popover-content' ><div><a href='#'></a></div><div><a href='#'></a></div></div>" data-delay="0">
                    <h4 class="quick_links-title notproductcart"  <?php echo ($myCarts)?'style="display:none;"':'' ?>><i class="fa fa-shopping-cart"></i> Cart is Empty
                    </h4>
                    <h4 class="quick_links-title listproductcart" <?php echo ($myCarts)?'':'style="display:none;"' ?>>
                        <a href="/cart">Checkout</a><img src="<?php echo $cdn ?>image/checkout.png" class="slideimg"/>
                    </h4>
                    <div id="cartulboxli">
                        <ul id="shopCart">
                            <?php if ($myCarts): ?>
                                <?php foreach ($myCarts as $cart) : ?>
                                    <li id="<?php echo $cart['product_dsku'] ?>">
                                        <a href="/collections/<?= $cart['collection_url'] ?>/products/<?= $cart['seo_url'] ?>"><img alt="<?php echo htmlspecialchars_decode($cart['product_title']) ?>" src="<?php echo IMAGE_DOMAIN . $cart['product_image'] ?>"></a>
                                        <a class="title" href="/collections/<?= $cart['collection_url'] ?>/products/<?= $cart['seo_url'] ?>"><?php echo htmlspecialchars_decode($cart['product_title']) ?></a>
                                        <p>x <span><?php echo $cart['product_qty'] ?></span></p>
                                    </li>
                                <?php endforeach; ?>
                            <?php endif; ?>
                            <li id="shopCarthide">
                                <a href="#" class="message_list"><i class="message"></i><div class="span">Shopping Cart</div><span class="cart_num">0</span></a>
                            </li>
                        </ul>	
                    </div>
                    <h6 class="quick_links-footer checkoutpage" <?php echo ($myCarts)?'':'style="display:none;"' ?>><i class="fa fa-lock"></i>Pay Securely Now</h6>
                </div>	
            </div>
            <img src="<?php echo $cdn.$country ?>/image/trust.png" usemap="#Map" style="width:100%;">
            <map name="Map" id="Map">
                <area alt="" title="" href="/pages/Shop-with-Confidence" shape="rect" coords="0,14,164,90" onfocus="blur(this);"/>
                <area alt="" title="" href="/pages/return-policy" shape="rect" coords="0,109,165,168" onfocus="blur(this);"/>
                <area alt="" title="" href="/pages/Shop-with-Confidence" shape="rect" coords="0,187,165,250" onfocus="blur(this);"/>
                <area alt="" title="" href="/pages/shipping-guide" shape="rect" coords="0,261,165,308" onfocus="blur(this);"/>
            </map>
            <div id="cart-popover"></div>
        </div>
    </div>   
</div>