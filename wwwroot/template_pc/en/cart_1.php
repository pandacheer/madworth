<?php echo $head; ?>
<div class="dg-breadcrumb">
    <div class="container">
        <div class="row">
            <div class="col-xs-12">
                <ol class="breadcrumb cartbreadcrumb">
                    <li><a href="<?=$domain?>/">Home</a></li>
                    <li class="active">shopping cart</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="dg-main dg-main-cart">
    <div class="container">
        <div class="row">

            <?php echo form_open('order/createOrder', 'onsubmit="return check_cart()"') ?>
            <?php if ($products): ?>		    			  
                <div class="col-xs-10" id="full-cart">
                    <div class="dg-main-cart-order clearfix">
                        <div class="dg-main-cart-order-left">Complete Your Order</div>
                        <div class="dg-main-cart-order-right"><i class="fa fa-clock-o"></i> Be quick! Stock is not reserved until you place your order</div>
                    </div>
                    <div class="dg-main-cart-shoppingbox">
                        <div class="dg-main-cart-shoppingbox-items">

                            <table class="table">
                                <thead>
                                    <tr class="header">
                                        <td colspan="2" style="width: 340px;">Item</td>
                                        <td style="width: 110px;">Price</td>
                                        <td style="width: 175px;">QTY</td>
                                        <td style="width: 120px;">Remove</td>
                                        <td style="width: 140px;">Subtotal</td>
                                    </tr>
                                </thead>
                                <tbody>        
                                    <?php foreach ($products as $key => $product): ?>
                                        <tr id="pdel_<?= $key ?>">
                                            <td>
                                                <div class="dg-main-cart-shoppingbox-items-img">
                                                    <a href="<?=$domain?>/collections/<?= $product['collection_url'] ?>/products/<?= $product['seo_url'] ?>">
                                                        <img alt="<?php echo htmlspecialchars_decode($product['product_title']); ?>" src="<?= IMAGE_DOMAIN ?><?= $product['product_image'] ?>" />
                                                    </a>
                                                </div>
                                            </td>
                                            <td class="vertical-middle">
                                                <div class="dg-main-cart-shoppingbox-items-text">
                                                    <div class="title">
                                                        <a href="<?=$domain?>/collections/<?= $product['collection_url'] ?>/products/<?= $product['seo_url'] ?>"><?= htmlspecialchars_decode($product['product_title']) ?></a>
                                                    </div>
                                                    <div class="option"><?= $product['product_attr'] ?></div>
                                                    <?php if ($product['freebies']) : ?>
                                                        <div class="freebies">+<?= $currency ?><span><?= ($product['product_price'] - $product['plural_price']) / 100 ?></span> Additional Shipping Fee</div>
                                                    <?php endif; ?>
                                                </div>
                                            </td>
                                            <td class="vertical-middle">
                                                <div class="dg-main-cart-shoppingbox-items-price" data-price="<?= ($product['product_price'] - $product['plural_price']) / 100 ?>">
                                                    <?= $currency ?>
                                                    <span><?= $product['freebies'] ? '0' : ($product['product_price'] - $product['plural_price']) / 100 ?></span>
                                                </div>
                                            </td>
                                            <td class="vertical-middle">
                                                <div class="qty_cart">
                                                    <button title="Decrease Qty"
                                                            onClick="qtyDown(<?= $key ?>);
                                                                    return false;" class="decrease">-</button>
                                                    <input disabled="disabled" id="qty_<?= $key ?>"
                                                           name="cart[<?= $key ?>][qty]"
                                                           value="<?= $product['product_qty'] ?>" size="4" title="Qty"
                                                           class="input-text qty"
                                                           data-sku="<?= $product['product_dsku'] ?>" maxlength="12">
                                                    <button title="Increase Qty" onClick="qtyUp(<?= $key ?>);
                                                            return false;" class="increase">+</button>
                                                </div>
                                            </td>
                                            <td class="vertical-middle">
                                                <div class="dg-main-cart-shoppingbox-items-trash">
                                                    <a href="#" id="del_<?= $key ?>" onClick="del(<?= $key ?>);
                                                            return false;" data-sku="<?= $product['product_dsku'] ?>"><i class="fa fa-trash-o"></i></a>
                                                </div>
                                            </td>
                                            <td class="vertical-middle price-sum">
                                                <?= $currency ?>
                                                <span><?= ($product['product_price'] - $product['plural_price']) * $product['product_qty'] / 100 ?></span>
                                            </td>
                                        </tr>
                                    <?php endforeach ?>
                                </tbody>
                            </table>
                        </div>

                        <div class="row">
                            <div class="col-xs-6">
                                <div class="dg-main-cart-shoppingbox-shipping">
                                    <div class="dg-main-cart-shoppingbox-shipping-title">
                                        <h3>Shipping Methods</h3>
                                    </div>
                                    <div class="dg-main-cart-shoppingbox-shipping-listbox">
                                        <?php foreach ($shipping as $shipping): ?>
                                            <?php if ($shipping['showType'] > 0) : ?>
                                                <?php if (strstr($shipping['name'], "Express")) : ?>
                                                    <div class="dg-main-cart-shoppingbox-shipping-listbox-list dg-main-cart-shoppingbox-shipping-listbox-list-image">
                                                    <?php else: ?>
                                                        <div class="dg-main-cart-shoppingbox-shipping-listbox-list">
                                                        <?php endif; ?>
                                                        <label> <input type="radio" name="shipping" value="<?= $shipping['id'] ?>" class="dg-main-cart-shoppingbox-shipping-listbox-list-radio">
                                                            <h5 class="dg-main-cart-shoppingbox-shipping-listbox-list-Standard"><?= $shipping['name'] ?></h5>
                                                            <h5 class="dg-main-cart-shoppingbox-shipping-listbox-list-price"><?php echo $shipping['price'] / 100 ? $currency . "<span>" . $shipping['price'] / 100 : '<span>Free</span>'; ?></h5>
                                                        </label>
                                                    </div>
                                                <?php endif; ?>
                                            <?php endforeach ?>
                                        </div>
                                    </div>

                                    <div class="dg-main-cart-shoppingbox-Insurance">
                                        <p>
                                            <label><input type="checkbox" id="insurance" name="insurance" value="1">
                                                <span>Add a Shipping Insurance <span style="color:#aaa">( Additional <?= $currency ?><em>1</em> )</span>
                                                    <span class="popover-cvv">
                                                        <a href="javascript:void(0)" type="button"
                                                           class="icon icon-info has-tooltip" data-placement="top" 
                                                           data-trigger="hover" data-container="body"
                                                           data-toggle="popover"
                                                           data-content="Shipping Insurance is provided by DrGrab and the world leading insuarance corporation PICC, any lost shipping or damage in transit, you will get fully protected. "></a>
                                                    </span>
                                                </span>
                                            </label>
                                        </p>
                                        <p>
                                            <label><input type="checkbox" id="giftbox" name="giftbox" value="1">
                                                <span>Add a Shopping Bag <span style="color:#aaa">( Additional <?= $currency ?><em>1</em> )</span>
                                                </span>
                                        </p>

                                    </div>
                                    <div class="dg-main-cart-shoppingbox-note">
                                        <div class="dg-main-cart-shoppingbox-note-title">
                                            <h3>Note</h3>
                                        </div>
                                        <div class="form-group">
                                            <input type="text" class="form-control" id="note" name="note">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xs-1"></div>
                                <div class="col-xs-5 coupon-updating">
                                    <div class="dg-main-cart-shoppingbox-coupon">
                                        <div class="dg-main-cart-shoppingbox-shipping-title">
                                            <h3>Coupon</h3>
                                        </div>
                                        <div class="input-group">
                                            <input type="text" name="coupon" class="form-control" id="coupon_id" placeholder="Coupon Code Here"  data-trigger="focus" autocomplete="off" >


                                            <!-- <select class="form-control" id="coupon_select" data-trigger="hover">
                                                <option>Coupon Code Here</option>
                                                <?php if ($this->session->userdata('member_email')): ?>
                                                    <?php foreach ($myCoupons as $key => $Coupons): ?>
                                                        <option><?= $key ?></option>
                                                    <?php endforeach ?>
                                                <?php endif; ?>
                                            </select> -->
                                            

                                            <span class="input-group-btn" >
                                                <button class="btn btn-drgrab" id="coupon" type="button" data-trigger="focus" <?php if (!$this->session->userdata('member_email')) echo "disabled='disabled'"; ?>>
                                                    <span class="glyphicon glyphicon-ok" aria-hidden="true"></span>
                                                    Apply
                                                </button>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="dg-main-cart-shoppingbox-order">
                                        <div class="dg-main-cart-shoppingbox-shipping-title">
                                            <h3>Order Summary</h3>
                                        </div>
                                        <div class="dg-main-cart-shoppingbox-order-listbox-listbox">
                                            <div class="dg-main-cart-shoppingbox-order-listbox-list">
                                                <h4>Subtotal<small><?= $currency ?><span class="subtotal"></span></small></h4>
                                                <h4><i></i><small><?= $currency ?><span>0</span></small></h4>
                                                <h4 style="display:none" class="coupon" data-coupon="true">Coupon<small><?= $currency ?><span>0</span></small></h4>
                                                <h4 style="display:none" class="insurance">Shipping Insurance<small><?= $currency ?><span>0</span></small></h4>
                                                <h4 style="display:none" class="giftpacking">Shopping Bag<small><?= $currency ?><span>0</span></small></h4>
                                            </div>
                                            <div class="dg-main-cart-shoppingbox-order-listbox-list total">
                                                <h4>Total<small><?= $currency ?><span></span></small></h4>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>



                        <?php if (!$this->session->userdata('member_email')): ?>
                            <div class="dg-main-cart-account">
                                <div class="row">
                                    <div class="col-xs-6">
                                        <div class="dg-main-cart-account-title" id="user_login">Already Have an Account?</div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xs-6 dg-main-cart-account-login">
                                        <div class="form-group">
                                            <input type="email" class="form-control input-lg"  id="login-email" placeholder="Email Address">
                                        </div>
                                        <div class="form-group">
                                            <input type="password" class="form-control input-lg" id="login-password" placeholder="Password">
                                        </div>
                                        <div class="form-group" id ="verifyCode-div"  <?php echo ($this->session->userdata('Verification')) && $this->session->userdata('Verification')['clickTimes'] > 2 ? 'style="display:block"' : 'style="display:none"' ?>>
                                            <div class="row">
                                                <div class="col-xs-3">
                                                    <input type="text" class="form-control" id="login-verify">
                                                </div>
                                                <div class="col-xs-3" style="cursor: pointer;">
                                                    <img id="body_Login_Img" src="/reg/vcode" onclick="this.src = '/reg/vcode?k=' + Math.random()"/>
                                                </div>
                                            </div>	
                                        </div>
                                        <button type="button" id="cart_login" class="btn btn-drgrab btn-lg">login</button>
                                        <span><a href="<?=$domain?>/forget">Forget Your Password?</a></span>
                                    </div>
                                    <div class="col-xs-6 dg-main-cart-account-reg">
                                        <button type="button" class="btn btn-drgrab btn-lg" id="guest">Continue As a Guest</button>
                                        <h5 class="dg-main-cart-account-reg-msg">An account will be automatically created for you</h5>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>


                        <div class="dg-main-cart-payment">
                            <div class="row">
                                <div class="col-xs-12">
                                    <div class="dg-main-cart-payment-title">Shipping and Payment</div>
                                </div>
                            </div>
                            <div class="row">



                                <div class="col-xs-6 dg-main-cart-payment-lr">
                                    <div class="dg-main-cart-payment-lr-blur">
                                        <div class="panel panel-primary dg-blur-hint-paypal">
                                            <div class="panel-body">
                                                <img src="<?php echo $cdn ?>image/paypal-shipping.png"><br /> Please Complete Your Shipping Address on PayPal Website
                                            </div>
                                        </div>

                                        <div class="panel panel-success dg-blur-hint-none">
                                            <div class="panel-body">
                                                <img src="<?php echo $cdn ?>image/payment-shipping.png"><br />
                                            </div>
                                        </div>

                                        <div class="dg-blur"></div>
                                    </div>

                                    <h4 class="dg-main-cart-payment-lr-titleadd">
                                        <i class="fa fa-plane"></i> Shipping Address
                                    </h4>


                                    <div class="dg-main-cart-payment-lr-left">  

                                        <?php if ($this->session->userdata('member_email') && $shippingAddress): ?>
                                            <div class="shipping-choose">
                                                <input type="hidden" id="address_id" name="address_id" value="<?php echo $shippingAddress[0]["receive_id"] ?>" />
                                                <div id="append_address"></div>
                                                <?php foreach ($shippingAddress as $address): ?>

                                                    <div class="panel clickable panel-default" data-shipping="<?php echo $address['receive_id'] ?>">
                                                        <div class="panel-heading">
                                                            <h3 class="panel-title" style="text-transform: capitalize">
                                                                <?= $address['receive_firstName'] ?> <?= $address['receive_lastName'] ?>
                                                            </h3>
                                                        </div>
                                                        <div class="panel-body">
                                                            <?= $address['receive_firstName'] ?> <?= $address['receive_lastName'] ?> <br>
                                                            <?= $address['receive_add2'] ?> <?= $address['receive_add1'] ?><br>
                                                            <?= $address['receive_city'] ?> <?= $address['receive_province'] ?> <?= $address['receive_zipcode'] ?><br>
                                                            <?= $address['receive_country'] ?><br>
                                                            <?= $address['receive_phone'] ?><br>
                                                        </div>
                                                    </div>
                                                <?php endforeach ?>
                                                <?php if ($shippingAddressCount < 5): ?>
                                                    <button type="button" class="btn btn-drgrab use-new-address" data-toggle="modal" data-target="#address">
                                                        <i class="glyphicon glyphicon-plus"></i> Add a New Address
                                                    </button>
                                                <?php endif; ?>
                                            </div>
                                        <?php else: ?>

                                            <div class="dg-main-cart-payment-lr-left">
                                                <div class="dg-main-cart-payment-lr-left-form">
                                                    <div class="row">
                                                        <div class="col-xs-6">
                                                            <div class="form-group">
                                                                <label for="firstname">First Name<span class="reddian">*</span></label>
                                                                <input type="text" class="form-control dg-requiredfield" name="firstname"  value="<?= $this->session->userdata('member_email') ? $member['member_firstName'] : ($this->session->userdata('cartInputAddress') ? $this->session->userdata('cartInputAddress')['receive_firstName'] : '') ?>">
                                                            </div>
                                                        </div>
                                                        <div class="col-xs-6">
                                                            <div class="form-group">
                                                                <label for="lastname">Last Name<span class="reddian">*</span></label>
                                                                <input type="text" class="form-control dg-requiredfield" name="lastname" value="<?= $this->session->userdata('member_email') ? $member['member_lastName'] : ($this->session->userdata('cartInputAddress') ? $this->session->userdata('cartInputAddress')['receive_lastName'] : '') ?>" >
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-xs-8">
                                                            <div class="form-group">
                                                                <label for="email">Email Address<span class="reddian">*</span></label>
                                                                <input type="text" class="form-control dg-requiredfield" name="emailaddress" id="is_emailaddress" <?= $this->session->userdata('member_email') ? 'readonly="readonly" value="' . $this->session->userdata('member_email') . '"' : ($this->session->userdata('cartInputAddress') ? 'value="' . $this->session->userdata('cartInputAddress')['receive_eamil'] . '"' : '') ?> >
                                                            </div>
                                                        </div>
                                                        <div class="col-xs-4">
                                                            <div class="form-group">
                                                                <label for="tel">Phone</label>
                                                                <input type="text" class="form-control" placeholder="optional" name="phone"  value="<?php echo $this->session->userdata('cartInputAddress') ? $this->session->userdata('cartInputAddress')['receive_phone'] : '' ?>">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-xs-8">
                                                            <div class="form-group">
                                                                <label for="address">Address<span class="reddian">*</span></label>
                                                                <input type="text" class="form-control dg-requiredfield" name="address" value="<?php echo $this->session->userdata('cartInputAddress') ? $this->session->userdata('cartInputAddress')['receive_add1'] : '' ?>">
                                                            </div>
                                                        </div>
                                                        <div class="col-xs-4">
                                                            <div class="form-group">
                                                                <label for="apt">Apt,Suite,etc</label> <input type="text" class="form-control" name="apt" value="<?php echo $this->session->userdata('cartInputAddress') ? $this->session->userdata('cartInputAddress')['receive_add2'] : '' ?>" >
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-xs-8">
                                                            <div class="form-group">
                                                                <label for="surburb"><?php echo $addCountry['city'] ?><span class="reddian">*</span></label>
                                                                <?php if (count($States) == 0): ?>
                                                                    <input type="text" class="form-control dg-requiredfield" name="suburb" value="<?php echo $countryList[$country]['name'] ?>">
                                                                <?php else: ?>
                                                                    <input type="text" class="form-control dg-requiredfield" name="suburb" value="<?php echo $this->session->userdata('cartInputAddress') ? $this->session->userdata('cartInputAddress')['receive_city'] : '' ?>">
                                                                <?php endif; ?>
                                                            </div>
                                                        </div>
                                                        <div class="col-xs-4">
                                                            <div class="form-group">
                                                                <label for="postcode"><?php echo $addCountry['zipcode'] ?><span class="reddian">*</span></label>
                                                                <input type="tetx" class="form-control dg-requiredfield" name="postcode" value="<?php echo $this->session->userdata('cartInputAddress') ? $this->session->userdata('cartInputAddress')['receive_zipcode'] : '' ?>">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-xs-8">
                                                            <div class="form-group">
                                                                <label for="state"><?php echo $addCountry['state'] ?><span class="reddian">*</span></label>
                                                                <select class="form-control dg-main-cart-payment-lr-left-select dg-requiredfield unstate" name="state" id="new_addr_state">
                                                                    <?php if (count($States) == 0): ?>
                                                                        <option value="<?php echo $countryList[$country]['name'] ?>"><?php echo $countryList[$country]['name'] ?></option>
                                                                    <?php else: ?>
                                                                        <option>Please select your <?php echo $addCountry['state'] ?></option>
                                                                        <?php foreach ($States as $StateCode => $StateName) : ?>
                                                                            <option value="<?php echo $StateName ?>"><?php echo $StateName ?></option>
                                                                        <?php endforeach; ?>
                                                                    <?php endif; ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-xs-4">
                                                            <div class="form-group">
                                                                <label for="country">COUNTRY<span class="reddian">*</span></label>
                                                                <input type="text" readonly="readonly" id="country" value="<?php echo $countryList[$country]['name'] ?>" class="form-control">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="alert alert-danger alert-dismissible" id="cartwaring" role="alert" style="margin-top: 15px;">
                                                <div id="error_error">Please Complete the required fields.</div>
                                            </div>
                                        <?php endif; ?>
                                    </div>

                                    <br>
                                    <h4 class="dg-main-cart-payment-lr-titleadd">
                                        <i class="fa fa-credit-card"></i> Billing Address
                                    </h4>

                                    <label>
                                        <input type="checkbox" id="billing" name="billing" value="1" ><small class="bill-desc">Same As Shipping Address</small>
                                    </label>
                                    <div class="dg-main-cart-payment-lr-left-billing">  

                                        <?php if ($this->session->userdata('member_email') && $billAddress): ?>
                                            <div class="panel-billing">
                                                <input type="hidden" id="billaddress_id" name="billaddress_id" value="<?php echo $billAddress[0]["receive_id"] ?>" />
                                                <div id="billappend_address"></div>
                                                <?php foreach ($billAddress as $bill): ?>
                                                    <div class="panel clickable panel-default " data-bill="<?php echo $bill['receive_id'] ?>">
                                                        <div class="panel-heading">
                                                            <h3 class="panel-title" style="text-transform: capitalize">
                                                                <?= $bill['receive_firstName'] ?> <?= $bill['receive_lastName'] ?>
                                                            </h3>
                                                        </div>
                                                        <div class="panel-body">
                                                            <?= $bill['receive_firstName'] ?> <?= $bill['receive_lastName'] ?> <br>
                                                            <?= $bill['receive_add2'] ?> <?= $bill['receive_add1'] ?><br>
                                                            <?= $bill['receive_city'] ?> <?= $bill['receive_province'] ?> <?= $bill['receive_zipcode'] ?><br>
                                                            <?= $bill['receive_country'] ?><br>
                                                        </div>
                                                    </div>
                                                <?php endforeach ?>
                                                <?php if ($billAddressCount < 5): ?>
                                                    <button type="button" class="btn btn-drgrab use-new-address" data-toggle="modal" data-target="#billaddress">
                                                        <i class="glyphicon glyphicon-plus"></i> Add a New Address
                                                    </button>
                                                <?php endif; ?>
                                            </div>
                                        <?php else: ?>

                                            <div class="dg-main-cart-payment-lr-left-billing billing-address">
                                                <div class="dg-main-cart-payment-lr-left-form">
                                                    <div class="row">
                                                        <div class="col-xs-6">
                                                            <div class="form-group">
                                                                <label for="firstname">First Name<span class="reddian">*</span></label>
                                                                <input type="text" class="form-control dg-requiredfield3" name="bill_firstname"  value="<?= $this->session->userdata('member_email') ? $member['member_firstName'] : ($this->session->userdata('cartInputAddress') ? $this->session->userdata('cartInputAddress')['receive_firstName'] : '') ?>">
                                                            </div>
                                                        </div>
                                                        <div class="col-xs-6">
                                                            <div class="form-group">
                                                                <label for="lastname">Last Name<span class="reddian">*</span></label>
                                                                <input type="text" class="form-control dg-requiredfield3" name="bill_lastname" value="<?= $this->session->userdata('member_email') ? $member['member_lastName'] : ($this->session->userdata('cartInputAddress') ? $this->session->userdata('cartInputAddress')['receive_lastName'] : '') ?>" >
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-xs-8">
                                                            <div class="form-group">
                                                                <label for="address">Address<span class="reddian">*</span></label>
                                                                <input type="text" class="form-control dg-requiredfield3" name="bill_address" value="<?php echo $this->session->userdata('cartInputAddress') ? $this->session->userdata('cartInputAddress')['receive_add1'] : '' ?>">
                                                            </div>
                                                        </div>
                                                        <div class="col-xs-4">
                                                            <div class="form-group">
                                                                <label for="apt">Apt,Suite,etc</label> <input type="text" class="form-control" name="bill_apt" value="<?php echo $this->session->userdata('cartInputAddress') ? $this->session->userdata('cartInputAddress')['receive_add2'] : '' ?>" >
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-xs-8">
                                                            <div class="form-group">
                                                                <label for="surburb"><?php echo $addCountry['city'] ?><span class="reddian">*</span></label>
                                                                <?php if (count($States) == 0): ?>
                                                                    <input type="text" class="form-control dg-requiredfield3" name="bill_suburb" value="<?php echo $countryList[$country]['name'] ?>">
                                                                <?php else: ?>
                                                                    <input type="text" class="form-control dg-requiredfield3" name="bill_suburb" value="<?php echo $this->session->userdata('cartInputAddress') ? $this->session->userdata('cartInputAddress')['receive_city'] : '' ?>">
                                                                <?php endif; ?>
                                                            </div>
                                                        </div>
                                                        <div class="col-xs-4">
                                                            <div class="form-group">
                                                                <label for="postcode"><?php echo $addCountry['zipcode'] ?><span class="reddian">*</span></label>
                                                                <input type="tetx" class="form-control dg-requiredfield3" name="bill_postcode" value="<?php echo $this->session->userdata('cartInputAddress') ? $this->session->userdata('cartInputAddress')['receive_zipcode'] : '' ?>">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-xs-8">
                                                            <div class="form-group">
                                                                <label for="state"><?php echo $addCountry['state'] ?><span class="reddian">*</span></label>
                                                                <select class="form-control dg-main-cart-payment-lr-left-select dg-requiredfield3 unbillstate" name="bill_state" id="new_addr_state">
                                                                    <?php if (count($States) == 0): ?>
                                                                        <option value="<?php echo $countryList[$country]['name'] ?>"><?php echo $countryList[$country]['name'] ?></option>
                                                                    <?php else: ?>
                                                                        <option>Please select your <?php echo $addCountry['state'] ?></option>
                                                                        <?php foreach ($States as $StateCode => $StateName) : ?>
                                                                            <option value="<?php echo $StateName ?>"><?php echo $StateName ?></option>
                                                                        <?php endforeach; ?>
                                                                    <?php endif; ?>

                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-xs-4">
                                                            <div class="form-group">
                                                                <label for="country">COUNTRY<span class="reddian">*</span></label>
                                                                <input type="text" readonly="readonly" id="bill_country" value="<?php echo $countryList[$country]['name'] ?>" class="form-control">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="alert alert-danger alert-dismissible" id="billcartwaring" role="alert" style="margin-top: 15px;">
                                                <div id="error_error">Please Complete the required fields.</div>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>


                                <div class="col-xs-6 dg-main-cart-payment-lr">
                                    <h4 class="dg-main-cart-payment-lr-titlemet">Choose a Payment Method</h4>
                                    <div class="dg-main-cart-payment-lr-right">
                                        <div class="dg-main-cart-payment-lr-right-form">
                                            <div class="row">
                                                <div class="col-xs-10">
                                                    <div class="dg-main-cart-payment-lr-right-form-msg">
                                                        <img src="<?php echo $cdn ?>image/lock.png" style="display: inline-block; float: left;">
                                                        <p class="dg-main-cart-payment-lr-right-form-msg-p">Secure Credit Card Payment</p>
                                                        <h6 class="dg-main-cart-payment-lr-right-form-msg-h5">Payments are encrypted with SSL security</h6>
                                                    </div>
                                                </div>
                                                <div class="col-xs-2">
                                                    <a href="https://seal.digicert.com/seals/popup/?tag=bbFrnPZh&url=www.drgrab.com&lang=en&cbr=1441097102978"><img src="<?php echo $cdn ?>image/cascade.png" style="float: right;"></a>                                              
                                                </div>
                                            </div>

                                            <div class="dg-main-cart-payment-lr-right-form-method">
                                                <div class="row">
                                                    <div class="dg-main-cart-payment-lr-right-form-method-list">
                                                        <label>
                                                            <div class="col-xs-5 dg-main-cart-payment-lr-right-form-method-list-title">
                                                                <input type="radio" name="pay_type" class="dg-main-cart-payment-lr-right-form-method-list-title-input" value="2">
                                                                <small  class="dg-main-cart-public-stylecss">Credit card</small>
                                                            </div>
                                                            <div  class="col-xs-7 dg-main-cart-payment-lr-right-form-method-list-img">
                                                                <ul class="field__icon payment-method">
                                                                    <li class="payment-method visa">Visa</li>
                                                                    <li class="payment-method master">MasterCard</li>
                                                                </ul>
                                                            </div>
                                                        </label>
                                                    </div>
                                                    <div class="dg-main-cart-payment-lr-right-form-method-list cchide">
                                                        <div class="col-xs-4 dg-main-cart-payment-lr-right-form-method-list-title">Card Number</div>
                                                        <div  class="col-xs-6 dg-main-cart-payment-lr-right-form-method-cardnumber">
                                                            <input autocomplete="cc-number" data-autofocus="true"
                                                                   data-credit-card="number"
                                                                   data-persist="credit_card_number"
                                                                   data-session-storage="false"
                                                                   id="credit_card_number"
                                                                   name="credit_card_number" type="tel"
                                                                   placeholder="Card Number" value="">
                                                        </div>
                                                        <div
                                                            class="col-xs-2 dg-main-cart-payment-lr-right-form-method-list-img">
                                                            <p class="popover-options">
                                                                <a href="javascript:void(0)" type="button"
                                                                   class="icon-lock-dark tooltip-show" data-placement="top"
                                                                   data-trigger="hover" data-container="body"
                                                                   data-toggle="popover"
                                                                   data-content="All transactions are secure and encrypted. Credit card information will never be stored."></a>
                                                            </p>
                                                        </div>
                                                    </div>
                                                    <div class="dg-main-cart-payment-lr-right-form-method-list cchide">
                                                        <div  class="col-xs-4 dg-main-cart-payment-lr-right-form-method-list-title">First Name</div>
                                                        <div  class="col-xs-8 dg-main-cart-payment-lr-right-form-method-list-firstname">
                                                            <input autocomplete="cc-given-name"
                                                                   data-persist="credit_card_name"
                                                                   id="credit_card_first_name"
                                                                   name="credit_card_first_name"
                                                                   placeholder="Name on card" size="30" type="text" value="">
                                                        </div>
                                                    </div>

                                                    <div class="dg-main-cart-payment-lr-right-form-method-list cchide">
                                                        <div  class="col-xs-4 dg-main-cart-payment-lr-right-form-method-list-title">Last Name</div>
                                                        <div  class="col-xs-8 dg-main-cart-payment-lr-right-form-method-list-lastname">
                                                            <input autocomplete="cc-family-name"
                                                                   data-persist="credit_card_name"
                                                                   id="credit_card_last_name"
                                                                   name="credit_card_last_name"
                                                                   placeholder="Name on card" size="30" type="text" value="">
                                                        </div>
                                                    </div>
                                                    <div class="dg-main-cart-payment-lr-right-form-method-list cchide">
                                                        <div  class="col-xs-4 dg-main-cart-payment-lr-right-form-method-list-title">Expiry</div>
                                                        <div  class="col-xs-8 dg-main-cart-payment-lr-right-form-method-title-expiry">
                                                            <input class="ccmonth" autocomplete="cc-exp-month" type="text" placeholder="MM" name="credit_card_expiry_mm" id="credit_card_expiry_mm"
                                                                   maxlength="2"> / <input class="ccyear" autocomplete="cc-exp-year" type="text" name="credit_card_expiry_yy" id="credit_card_expiry_yy"
                                                                   placeholder="YY" maxlength="2">
                                                        </div>
                                                    </div>
                                                    <div class="dg-main-cart-payment-lr-right-form-method-list cchide">
                                                        <div  class="col-xs-4 dg-main-cart-payment-lr-right-form-method-list-title">
                                                            CVV
                                                            <p class="popover-cvv">
                                                                <a href="javascript:void(0)" type="button"
                                                                   class="icon icon-info has-tooltip" data-placement="top" 
                                                                   data-trigger="hover" data-container="body"
                                                                   data-toggle="popover"
                                                                   data-content="3 or 4 digit security code usually located on the back of the card."></a>
                                                            </p>
                                                        </div>
                                                        <div class="col-xs-8 col-xs-4 dg-main-cart-payment-lr-right-form-method-list-cvv">
                                                            <input data-credit-card="cvv" autocomplete="cc-csc"
                                                                   data-persist="credit_card_cvv"
                                                                   data-session-storage="false"
                                                                   id="credit_card_cvv" maxlength="4"
                                                                   name="credit_card_cvv"
                                                                   placeholder="CVV" type="tel">
                                                        </div>
                                                    </div>
                                                    <div class="dg-main-cart-payment-lr-right-form-method-list" id="hidelist">
                                                        <label>
                                                            <div  class="col-xs-8 dg-main-cart-payment-lr-right-form-method-list-title">
                                                                <input type="radio" name="pay_type"
                                                                       class="dg-main-cart-payment-lr-right-form-method-list-title-input"
                                                                       value="1"> <small
                                                                       class="dg-main-cart-public-stylecss">Paypal</small>
                                                            </div>
                                                            <div  class="col-xs-4 dg-main-cart-payment-lr-right-form-method-list-img">
                                                                <img src="<?php echo $cdn ?>image/paypal.png">
                                                            </div>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="dg-main-cart-payment-lr-right-form-total">Amount : <span class="success-text"></span> </div>
                                            <button type="submit" class="dg-main-cart-payment-lr-right-form-submit btn btn-success btn-lg">
                                                <i class="fa fa-lock"></i> Pay Securely Now
                                            </button>
                                            <div class="dg-main-cart-payment-lr-right-form-secure">
                                                <span class="methodpaypal"><i class="fa fa-lock"></i> You will
                                                    be <span class="success-text">redirected to PayPal's secure
                                                        website</span> to complete your order</span> <span
                                                    class="methodcc"><i class="fa fa-lock"></i> Your payment for
                                                    this purchase is processed securely.</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="col-xs-10 col-xs-12" id="empty-cart">
                        <div class="dg-main-thankyou">
                            <div class="dg-main-reset-ticker">
                                <i class="fa fa-shopping-cart fa-lg"></i>
                                <div class="dg-main-thankyou-ticker-thanktitle">You have no items in your cart.</div>
                                <a href="<?=$domain?>" class="btn btn-default btn-lg" style="margin: 1em 0 3em 0;">Continue Shopping</a>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
                <?php echo form_close(); ?>


                <div  class="modal fade modal-address" id="address" tabindex="-1" role="dialog" aria-labelledby="address">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                <span class="modal-title" style="font-size: 20px;">New Shipping Address</span>
                            </div>

                            <div class="modal-body">
                                <input type="hidden" name="receive_id" id="receive_id"  value="0">
                                <div class="row">
                                    <div class="col-xs-4">
                                        <div class="form-group">
                                            <label for="firstname">First Name<span class="reddian">*</span></label>
                                            <input type="text" class="form-control dg-requiredfield1" id="firstname" name="firstname">
                                        </div>
                                    </div>
                                    <div class="col-xs-4">
                                        <div class="form-group">
                                            <label for="lastname">Last Name<span class="reddian">*</span></label>
                                            <input type="text" class="form-control dg-requiredfield1" id="lastname" name="lastname">
                                        </div>
                                    </div>
                                    <div class="col-xs-4">
                                        <div class="form-group">
                                            <label for="tel">Phone</label>
                                            <input type="text" class="form-control" id="phone" name="phone" placeholder="optional">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xs-8">
                                        <div class="form-group">
                                            <label for="address">Address<span class="reddian">*</span></label>
                                            <input type="text" class="form-control dg-requiredfield1" name="address" id="address1">
                                        </div>
                                    </div>
                                    <div class="col-xs-4">
                                        <div class="form-group">
                                            <label for="apt">Apt,Suite,etc</label> <input type="text"  class="form-control" name="apt" id="apt" placeholder="optional">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xs-8">
                                        <div class="form-group">
                                            <label for="surburb"><?php echo $addCountry['city'] ?><span class="reddian">*</span></label>
                                            <input type="text" class="form-control dg-requiredfield1" name="suburb" id="suburb" value="<?php echo count($States) == 0 ? $countryList[$country]['name'] : '' ?>">
                                        </div>
                                    </div>
                                    <div class="col-xs-4">
                                        <div class="form-group">
                                            <label for="postcode"><?php echo $addCountry['zipcode'] ?><span class="reddian">*</span></label>
                                            <input type="tetx" class="form-control dg-requiredfield1" name="postcode" id="postcode">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xs-8">
                                        <div class="form-group">
                                            <label for="state"><?php echo $addCountry['state'] ?><span class="reddian">*</span></label>
                                            <select class="form-control dg-main-cart-payment-lr-left-select dg-requiredfield1" name="state" id="state">
                                                <?php if (count($States) == 0): ?>
                                                    <option value="<?php echo $countryList[$country]['name'] ?>"><?php echo $countryList[$country]['name'] ?></option>
                                                <?php else: ?>
                                                    <option>Please select your <?php echo $addCountry['state'] ?></option>
                                                    <?php foreach ($States as $StateCode => $StateName) : ?>
                                                        <option value="<?php echo $StateName ?>"><?php echo $StateName ?></option>
                                                    <?php endforeach; ?>
                                                <?php endif; ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-xs-4">
                                        <div class="form-group">
                                            <label for="country">COUNTRY<span class="reddian">*</span></label>
                                            <input type="text" readonly id="country" value="<?php echo $countryList[$country]['name'] ?>" class="form-control">
                                        </div>
                                    </div>
                                </div>
                                </form>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal" id="add_close">Close</button>
                                <button type="button" class="btn btn-success" id="add_address">Save</button>
                            </div>

                        </div>
                    </div>
                </div>

                <div  class="modal fade modal-address" id="billaddress" tabindex="-1" role="dialog" aria-labelledby="address">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                <span class="modal-title" style="font-size: 20px;">New Billing Address</span>
                            </div>

                            <div class="modal-body">
                                <input type="hidden" name="billreceive_id" id="receive_id"  value="0">
                                <div class="row">
                                    <div class="col-xs-6">
                                        <div class="form-group">
                                            <label for="firstname">First Name<span class="reddian">*</span></label>
                                            <input type="text" class="form-control dg-requiredfield2" id="bill_firstname" name="firstname">
                                        </div>
                                    </div>
                                    <div class="col-xs-6">
                                        <div class="form-group">
                                            <label for="lastname">Last Name<span class="reddian">*</span></label>
                                            <input type="text" class="form-control dg-requiredfield2" id="bill_lastname" name="lastname">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xs-8">
                                        <div class="form-group">
                                            <label for="address">Address<span class="reddian">*</span></label>
                                            <input type="text" class="form-control dg-requiredfield2" name="address" id="bill_address">
                                        </div>
                                    </div>
                                    <div class="col-xs-4">
                                        <div class="form-group">
                                            <label for="apt">Apt,Suite,etc</label> <input type="text"  class="form-control" name="apt" id="bill_apt" placeholder="optional">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xs-8">
                                        <div class="form-group">
                                            <label for="surburb"><?php echo $addCountry['city'] ?><span class="reddian">*</span></label>
                                            <input type="text" class="form-control dg-requiredfield2" name="suburb" id="bill_suburb" value="<?php echo count($States) == 0 ? $countryList[$country]['name'] : '' ?>">
                                        </div>
                                    </div>
                                    <div class="col-xs-4">
                                        <div class="form-group">
                                            <label for="postcode"><?php echo $addCountry['zipcode'] ?><span class="reddian">*</span></label>
                                            <input type="tetx" class="form-control dg-requiredfield2" name="postcode" id="bill_postcode">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xs-8">
                                        <div class="form-group">
                                            <label for="state"><?php echo $addCountry['state'] ?><span class="reddian">*</span></label>
                                            <select class="form-control dg-main-cart-payment-lr-left-select dg-requiredfield2" name="state"d id="bill_state">
                                                <?php if (count($States) == 0): ?>
                                                    <option value="<?php echo $countryList[$country]['name'] ?>" selected="selected"><?php echo $countryList[$country]['name'] ?></option>
                                                <?php else: ?>
                                                    <option>Please select your <?php echo $addCountry['state'] ?></option>
                                                    <?php foreach ($States as $StateCode => $StateName) : ?>
                                                        <option value="<?php echo $StateName ?>"><?php echo $StateName ?></option>
                                                    <?php endforeach; ?>
                                                <?php endif; ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-xs-4">
                                        <div class="form-group">
                                            <label for="country">COUNTRY<span class="reddian">*</span></label>
                                            <input type="text" readonly id="bill_country" value="<?php echo $countryList[$country]['name'] ?>" class="form-control">
                                        </div>
                                    </div>
                                </div>
                                </form>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal" id="billadd_close">Close</button>
                                <button type="button" class="btn btn-success" id="billadd_address">Save</button>
                            </div>

                        </div>
                    </div>
                </div>

                <div class="col-xs-2 dg-cartfly">
                    <div class="dg-trust ">
                        <div id="cartflysidebar">

                            <div class="mui-mbar-tabs dg-hide">
                                <div id="quick_links" class="quick_links">
                                    <h4 class="quick_links-title notproductcart"  <?php echo ($myCarts) ? 'style="display:none;"' : '' ?>>Shopping Cart</h4>
                                    <h4 class="quick_links-title listproductcart" <?php echo ($myCarts) ? '' : 'style="display:none;"' ?>>
                                        <a href="/cart">Checkout</a><img src="<?php echo $cdn ?>image/checkout.png" class="slideimg"/>
                                    </h4>
                                    <div id="cartulboxli">
                                        <ul id="shopCart">
                                            <?php if ($myCarts): ?>
                                                <?php foreach ($myCarts as $cart) : ?>
                                                    <li id="<?php echo $cart['product_dsku'] ?>">
                                                        <a href="/product/index/<?php echo $cart['product_id'] ?>"><img alt="<?php echo $cart['product_title'] ?>" src="<?php echo IMAGE_DOMAIN . $cart['product_image'] ?>"></a>
                                                        <a class="title" href="/product/index/<?php echo $cart['product_id'] ?>"><?php echo $cart['product_title'] ?></a>
                                                        <p>x <span><?php echo $cart['product_qty'] ?></span></p>
                                                    </li>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                            <li id="shopCarthide">
                                                <a href="#" class="message_list"><i class="message"></i><div class="span">cart</div><span class="cart_num">0</span></a>
                                            </li>
                                        </ul>	
                                    </div>
                                    <h6 class="quick_links-footer checkoutpage" <?php echo ($myCarts) ? '' : 'style="display:none;"' ?>><i class="fa fa-lock"></i>Pay Securely Now</h6>
                                    <h6 class="quick_links-footer cartempty" <?php echo ($myCarts) ? 'style="display:none;"' : '' ?>><i class="fa fa-shopping-cart"></i>Your Cart is Empty</h6>			
                                </div>	
                            </div>
                            <img src="<?php echo $cdn . $country ?>/image/trust.png" usemap="#Map" style="width:100%;">
                            <map name="Map" id="Map">
                                <area alt="" title="" href="<?=$domain?>/pages/Shop-with-Confidence" shape="rect" coords="0,14,164,90" onfocus="blur(this);"/>
                                <area alt="" title="" href="<?=$domain?>/pages/return-policy" shape="rect" coords="0,109,165,168" onfocus="blur(this);"/>
                                <area alt="" title="" href="<?=$domain?>/pages/Shop-with-Confidence" shape="rect" coords="0,187,165,250" onfocus="blur(this);"/>
                                <area alt="" title="" href="<?=$domain?>/pages/shipping-guide" shape="rect" coords="0,261,165,308" onfocus="blur(this);"/>
                            </map>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>



    <?php echo $foot ?>
    <script>
        var currency = "<?php echo $currency; ?>";
        var cdn = "<?php echo $cdn ?>";
        var state = "<?php echo $addCountry['state'] ?>";
    </script>
    <script src="<?php echo $cdn ?>js/cartjs.js"></script>
    <script src="<?php echo $cdn ?>js/cartdata.js"></script>
    <script src="<?php echo $cdn ?>js/smooth-scroll.min.js"></script>
    <script>


        if (!"<?php echo $this->session->userdata('member_email') ?>") {

            CartsmoothScroll();
            CartCouponPop();
        }

        var cardvalid = true;
        var carderror = '';
        $('.dg-main-cart-payment-lr-right-form input[name=pay_type]').on('ifChecked', function (event) {
            if (event.target.defaultValue == "2") {
                $('#credit_card_first_name,#credit_card_last_name,#credit_card_expiry_mm,#credit_card_expiry_yy,#credit_card_cvv').focus(function () {
                    if ($('#credit_card_number').val() != '') {
                        $('#credit_card_number').validateCreditCard(function (result) {
                            if (result.length_valid) {
                                if (result.card_type.name == "American Express" || result.card_type.name == "JCB" || result.card_type.name == "Discover") {
                                    $.notifyBar({cssClass: "dg-notify-error", html: 'Sorry, we do not accept ' + result.card_type.name + ' directly ( Please Choose PayPal to use it )', position: "bottom"});
                                    carderror = result.card_type.name;
                                    cardvalid = false;
                                } else {
                                    cardvalid = true;
                                }
                            }
                        });
                    }
                })
            }
            else {
                cardvalid = true;
            }
        });


        function check_cart() {

            if (!cardvalid) {
                $.notifyBar({cssClass: "dg-notify-error", html: 'Sorry, we do not accept ' + carderror + ' directly ( Please Choose PayPal to use it ) ', position: "bottom"});
                return false;
            }
            if (!$("input:radio[name='shipping']:checked").val()) {
                $.notifyBar({cssClass: "dg-notify-error", html: 'Please choose your desired shipping method', position: "bottom"});
                return false;
            }

            if (!$("input:radio[name='pay_type']:checked").val()) {
                $.notifyBar({cssClass: "dg-notify-error", html: 'Please choose your prefered payment method', position: "bottom"});
                return false;
            }


            if ($("input:radio[name='pay_type']:checked").val() == 2) {
                if (!(/^[+]?[1-9]+\d*$/i.test($('#credit_card_number').val()))) {
                    $.notifyBar({cssClass: "dg-notify-error", html: 'Invalid credit card number ', position: "bottom"});
                    return false;
                }
                if (($('#credit_card_number').val().length < 15) || ($('#credit_card_number').val().length > 28)) {
                    $.notifyBar({cssClass: "dg-notify-error", html: 'Invalid credit card number ', position: "bottom"});
                    return false;
                }
                if ($.trim($('#credit_card_first_name').val()).length < 1) {
                    $.notifyBar({cssClass: "dg-notify-error", html: "Please check the card holder's first name", position: "bottom"});
                    return false;
                }
                if ($.trim($('#credit_card_last_name').val()).length < 1) {
                    $.notifyBar({cssClass: "dg-notify-error", html: "Please check the card holder's last name", position: "bottom"});
                    return false;
                }
                if (!(/^[0-9]{2}$/i.test($('#credit_card_expiry_mm').val()))) {
                    $.notifyBar({cssClass: "dg-notify-error", html: 'Please check the expiry date', position: "bottom"});
                    return false;
                }
                if (!(/^[0-9]{2}$/i.test($('#credit_card_expiry_yy').val()))) {
                    $.notifyBar({cssClass: "dg-notify-error", html: 'Please check the expiry date', position: "bottom"});
                    return false;
                }
                if (!(/^[0-9]{3,4}$/i.test($('#credit_card_cvv').val()))) {
                    $.notifyBar({cssClass: "dg-notify-error", html: 'CVV : 3 or 4 digit security code usually located on the back of the card.！', position: "bottom"});
                    return false;
                }


                if ("<?php echo $this->session->userdata('member_email') ?>") {
                    if (!"<?php empty($billAddress) ?>") {
                        if ($('.billing-address').hasClass('billshow')) {
                            ifValid = true;
                            $(".dg-requiredfield3").each(function () {
                                if (!$(this).val()) {
                                    ifValid = false;
                                    $(this).css('border-color', '#dd514c');
                                }
                            });
                            if (!ifValid) {
                                $.notifyBar({cssClass: "dg-notify-error", html: 'Please fill in all mandatory fields marked *', position: "bottom"});
                                $('#billcartwaring').fadeIn(200);
                                return false;
                            }

                            if ($('.unbillstate').val() == "Please select your <?php echo $addCountry['state'] ?>") {
                                $.notifyBar({cssClass: "dg-notify-error", html: 'Please fill in all mandatory fields marked *', position: "bottom"});
                                $('.unbillstate').css('border-color', '#dd514c');
                                return false;
                            }

                        }
                    }

                    if (!"<?php empty($shippingAddress) ?>") {
                        ifValid = true;
                        $(".dg-requiredfield").each(function () {
                            if (!$(this).val()) {
                                ifValid = false;
                                $(this).css('border-color', '#dd514c');
                            }
                        });
                        if (!ifValid) {
                            $.notifyBar({cssClass: "dg-notify-error", html: 'Please fill in all mandatory fields marked *', position: "bottom"});
                            $('#cartwaring').fadeIn(200);
                            return false;
                        }
                        if ($('.unstate').val() == "Please select your <?php echo $addCountry['state'] ?>") {
                            $.notifyBar({cssClass: "dg-notify-error", html: 'Please fill in all mandatory fields marked *', position: "bottom"});
                            $('.unstate').css('border-color', '#dd514c');
                            return false;
                        }
                    }

                }
                else {
                    ifValid = true;
                    $(".dg-requiredfield").each(function () {
                        if (!$(this).val()) {
                            ifValid = false;
                            $(this).css('border-color', '#dd514c');
                        }
                    });

                    if (!ifValid) {
                        $.notifyBar({cssClass: "dg-notify-error", html: 'Please fill in all mandatory fields marked *', position: "bottom"});
                        $('#cartwaring').fadeIn(200);
                        return false;
                    }

                    if (ifValid && !$("#is_emailaddress").val().match(/^([a-zA-Z0-9_.-])+@([a-zA-Z0-9_-])+(.[a-zA-Z0-9_-])+/)) {
                        $.notifyBar({cssClass: "dg-notify-error", html: 'Please provide a valid email address', position: "bottom"});
                        return false;
                    }

                    if ($('.unstate').val() == "Please select your <?php echo $addCountry['state'] ?>") {
                        $.notifyBar({cssClass: "dg-notify-error", html: 'Please fill in all mandatory fields marked *', position: "bottom"});
                        $('.unstate').css('border-color', '#dd514c');
                        return false;
                    }

                    if ($('.billing-address').hasClass('billshow')) {
                        ifValids = true;
                        $(".dg-requiredfield3").each(function () {
                            if (!$(this).val()) {
                                ifValids = false;
                                $(this).css('border-color', '#dd514c');
                            }
                        });
                        if ($('.unbillstate').val() == "Please select your <?php echo $addCountry['state'] ?>") {
                            $.notifyBar({cssClass: "dg-notify-error", html: 'Please fill in all mandatory fields marked *', position: "bottom"});
                            $('#billcartwaring').fadeIn(200);
                            $('.unbillstate').css('border-color', '#dd514c');
                            return false;
                        }

                        if (!ifValids) {
                            $.notifyBar({cssClass: "dg-notify-error", html: 'Please fill in all mandatory fields marked *', position: "bottom"});
                            $('#billcartwaring').fadeIn(200);
                            return false;
                        }
                    }
                }
            }
            $('.dg-main-cart-payment-lr-right-form-submit').attr('disabled', 'disabled');
            $('.dg-main-cart-payment-lr-right-form-submit').html('<i class="fa fa-lock"></i> Payment in progress ...');
        }

    </script>
    <?php if (isset($countrySEO)) echo $countrySEO ?>
    <?php if (!$this->session->userdata('member_email') || !$shippingAddressCount): ?>
        <script src="//js.maxmind.com/js/apis/geoip2/v2.1/geoip2.js" type="text/javascript" ></script>
        <script type="application/javascript">
            $(document).ready(function() {

            var onSuccess = function(location){
            if($("#new_addr_state option[value='" + location.subdivisions[0].names.en + "']").length){
            $("#new_addr_state").val(location.subdivisions[0].names.en)
            }
            };
            var onError = function(error){
            return false;
            };
            geoip2.city(onSuccess, onError); 
            });
        </script> 

    <?php endif; ?>

</body>
</html>
