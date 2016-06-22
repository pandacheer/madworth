<div id="header">
    <h1><a href="www.drgrab.com">Shopify</a></h1>	
    <a id="menu-trigger" href="#"><i class="fa fa-bars"></i></a>	
</div>

<div id="user-nav">
    <ul class="btn-group">
        <li class="btn"><a href="http://<?=$this->session->userdata('domain')?>" target="_blank"><i class="fa fa-reply"></i> <span class="text">Go to the Drgrab</span></a></li>
        <li class="btn" ><a href="#"><i class="fa fa-user"></i> <span class="text"><?php echo $this->session->userdata('user_account') ?></span></a></li>
        <li class="btn"><a href="/login/logout"><i class="fa fa-share"></i> <span class="text">Logout</span></a></li>
    </ul>
</div>

<div id="sidebar">
    <div id="search">
        <select class="js-templating">
            <?php foreach ($countryList as $code => $name): ?>
                <option value="<?php echo $code ?>" <?php if ($code == $this->session->userdata('my_country')) echo 'selected="selected"'; ?> > <?php echo $name ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <ul>
        <li<?php echo $active['dashboard'] ?>><a href="/"><i class="fa fa-home"></i><span>Dashboard</span></a></li>
        <li<?php echo $active['orders'] ?>>
            <a href="/orders">
                <i class="glyphicon glyphicon-inbox" aria-hidden="true"></i><span>Orders</span>
            </a>
        </li>
        <li<?php echo $active['refundApply'] ?>>
            <a href="/orderRefundApply">
                <i class="glyphicon glyphicon-usd" aria-hidden="true"></i><span>Order Support</span>
            </a>
        </li>
        <li<?php echo $active['contact'] ?>>
            <a href="/contact">
                <i class="glyphicon glyphicon-inbox" aria-hidden="true"></i><span>Contact</span>
            </a>
        </li>
        <li<?php echo $active['fulfil'] ?>>
            <a href="/orders/fulfil">
                <i class="glyphicon glyphicon-inbox" aria-hidden="true"></i><span>Fulfil</span>
            </a>
        </li>
        <li<?php echo $active['refund'] ?>>
            <a href="/orderRefund">
                <i class="glyphicon glyphicon-usd" aria-hidden="true"></i><span>Refunds</span>
            </a>
        </li>
        <li<?php echo $active['complaints'] ?>>
            <a href="/orderTracking">
                <i class="glyphicon glyphicon-usd" aria-hidden="true"></i><span>Complaints</span>
            </a>
        </li>				
        <li<?php echo $active['product'] ?>>
            <a href="/product">
                <i class="glyphicon glyphicon-glass" aria-hidden="true"></i><span>Product</span>
            </a>
        </li>
        <li<?php echo $active['comment'] ?>>
            <a href="/comment">
                <i class="glyphicon glyphicon-inbox" aria-hidden="true"></i><span>Comment</span>
            </a>
        </li>
        <li<?php echo $active['collection'] ?>>
            <a href="/collection">
                <i class="glyphicon glyphicon-tags" aria-hidden="true"></i><span>Collection</span>
            </a>
        </li>
        <li<?php echo $active['category'] ?>>
            <a href="/category">
                <i class="glyphicon glyphicon-list-alt" aria-hidden="true"></i><span>Product Type</span>
            </a>
        </li>
        <li<?php echo $active['countdown'] ?>>
            <a href="/countdown">
                <i class="glyphicon glyphicon-time" aria-hidden="true"></i><span>CountDown</span>
            </a>
        </li>
        <li<?php echo $active['coupons'] ?>>
            <a href="/coupons">
                <i class="glyphicon glyphicon-gift" aria-hidden="true"></i><span>Coupons</span>
            </a>
        </li>
        <li<?php echo $active['discount'] ?>>
            <a href="/discount">
                <i class="glyphicon glyphicon-gift" aria-hidden="true"></i><span>Discount Collection</span>
            </a>
        </li>
        <li<?php echo $active['member'] ?>>
            <a href="/customers/memberList">
                <i class="glyphicon glyphicon-user" aria-hidden="true"></i><span>customer</span>
            </a>
        </li>
        <li<?php echo $active['customers'] ?>>
            <a href="/customers">
                <i class="glyphicon glyphicon-user" aria-hidden="true"></i><span>customerAnalysis</span>
            </a>
        </li>
        <li<?php echo $active['navigation'] ?>>
            <a href="/navigation">
                <i class="glyphicon glyphicon-road" aria-hidden="true"></i><span>Navigation</span>
            </a>
        </li>
        <li<?php echo $active['shipping'] ?>>
            <a href="/shipping">
                <i class="glyphicon glyphicon-plane" aria-hidden="true"></i><span>Shipping</span>
            </a>
        </li>
        <li<?php echo $active['pages'] ?>>
            <a href="/pages">
                <i class="glyphicon glyphicon-gift" aria-hidden="true"></i><span>pages</span>
            </a>
        </li>
        <li<?php echo $active['slideshow'] ?>>
            <a href="/slideshow">
                <i class="glyphicon glyphicon-film" aria-hidden="true"></i><span>Slideshow</span>
            </a>
        </li>
        <li<?php echo $active['setting'] ?>>
            <a href="/system">
                <i class="glyphicon glyphicon-cog" aria-hidden="true"></i><span>Setting</span>
            </a>
        </li>
        <li>
            <a href="/subscriptionlist">
                <i class="glyphicon glyphicon-user" aria-hidden="true"></i><span>subscriptionList</span>
            </a>
        </li>
        <li <?php echo $active['sku_mapping'] ?>>
            <a href="/sku_mapping">
                <i class="glyphicon glyphicon-cog" aria-hidden="true"></i><span>sku_mapping</span>
            </a>
        </li>
    </ul>
</div>

