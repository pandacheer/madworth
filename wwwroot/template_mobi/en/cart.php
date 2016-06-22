<?php echo $head; ?>
<?php if ($products): ?>
    <div role="main" class="ui-content">
        <?php echo form_open('order/createOrder', 'onsubmit="return check_cart()"') ?>
        <div class="dg-pagetitle">My Cart</div>
        <div class="dg-main-cart">
            <ul data-role="listview" data-inset="true" data-icon="false" data-shadow="false">
                <?php foreach ($products as $key => $product): ?>
                    <li id="cart_pro_<?= $key ?>" style="padding: 0.5em;height: 90px;background-color: #fff;">
                        <div class="ui-grid-b">
                            <div class="ui-block-a">
                                <a href="<?= $domain ?>/collections/<?= $product['collection_url'] ?>/products/<?= $product['seo_url'] ?>">
                                    <img alt="<?php echo htmlspecialchars_decode($product['product_title']); ?>" style="width:80px;padding:0.3em" src="<?= IMAGE_DOMAIN ?><?= $product['product_image'] ?>" />
                                </a>
                            </div>

                            <div class="ui-block-b" style="width: 66%">
                                <div class="dg-main-cart-item">
                                    <div class="ui-grid-a">
                                        <div class="ui-block-a">
                                            <p class="dg-main-cart-center-title"><a href="<?= $domain ?>/collections/<?= $product['collection_url'] ?>/products/<?= $product['seo_url'] ?>" style="color:#666"><strong ><?= htmlspecialchars_decode($product['product_title']) ?></strong></a></p>

                                            <?php if ($product['freebies']) : ?>
                                                <p class="dg-main-cart-center-color">+ <span style="color:#ff878c;"><?= $currency ?><?= ($product['product_price'] - $product['plural_price']) / 100 ?></span> Additional Shipping Fee</p>
                                            <?php else: ?>

                                                <p class="dg-main-cart-center-color"><?= $product['product_attr'] ?></p>

                                            <?php endif; ?>

                                            <p><a href="#" id="del_<?= $key ?>" onClick="del(<?= $key ?>);
                                                    return false;" data-sku="<?= htmlspecialchars($product['product_dsku'],ENT_COMPAT);?>">
                                                    <i class="icon-delete"></i>
                                                </a>
                                            </p>
                                            <p class="dg-main-cart-center-price" data-price="<?= ($product['product_price'] - $product['plural_price']) / 100 ?>"><?= $currency ?><span><?= $product['freebies'] ? '0' : ($product['product_price'] - $product['plural_price']) / 100 ?></span></p>
                                        </div>
                                        <div class="ui-block-b">
                                            <div class="dg-main-cart-right">
                                                <div class="dg-main-cart-right-button">
                                                    <a data-inline="true" onClick="qtyUp(<?= $key ?>);
                                                            return false;">+</a>
                                                    <span disabled="disabled" id="qty_<?= $key ?>" name="cart[<?= $key ?>][qty]"  data-sku="<?= htmlspecialchars($product['product_dsku'],ENT_COMPAT); ?>"><?= $product['product_qty'] ?></span>
                                                    <a onClick="qtyDown(<?= $key ?>);
                                                            return false;">-</a>
                                                </div>
                                            </div> 
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
                <?php endforeach ?>
            </ul>
        </div>
        <div class="dg-pagetitle">Shipping Methods</div>
        <div class="dg-main-shipping">
            <div class="dg-main-check">
                <?php foreach ($shipping as $shipping): ?>
                    <?php if ($shipping['showType'] > 0) : ?>
                        <?php if (strstr($shipping['name'], "Express")) : ?>
                            <div class="dg-main-check-list dg-main-check-list-image" >
                            <?php else: ?>
                                <div class="dg-main-check-list" >
                                <?php endif; ?>    
                                <!-- <div class="iradio_square-blue" style="float:left;margin: 0.2em 0" id="stand"></div> -->
                                <label><input class="dg-main-check-list-radio" type="radio"  name="shipping" value="<?= $shipping['id'] ?>" data-role="none" style="display:none">
                                    <small class="shipping_name"><?= $shipping['name'] ?></small><small style="float: right" class="dg-main-check-list-price"><?php echo $shipping['price'] / 100 ? $currency . "<span>" . $shipping['price'] / 100 : '<span>Free</span>'; ?></small>
                                </label>
                            </div>
                        <?php endif; ?>
                    <?php endforeach ?>
                </div>
            </div>
            <div class="dg-pagetitle">Additional Options</div>
            <div class="dg-main-Insurance">
                <label><input type="checkbox" id="insurance" name="insurance" value="1" data-role="none" style="display:none"><span>Add a Shipping Insurance ( Additional <?= $currency ?>1 )</span></label>
                <label><input type="checkbox" id="giftbox" name="giftbox" value="1" data-role="none" style="display:none"><span>Add a Shopping Bag  ( Additional <?= $currency ?>1 )</span></label>
            </div>

            <div class="dg-pagetitle">Payment</div>
            <div class="dg-main-pay-header">
                <div class="ui-grid-a">
                    <div class="ui-block-a">
                        <div class="dg-main-pay-msg">
                            <img src="<?php echo $cdn ?>img/lock.png"
                                 style="display: inline-block; float: left;" class="dg-main-pay-msg-img">
                            <p class="dg-main-pay-msg-p">Secure
                                Credit Card Payment</p><h6>Payments are encrypted with SSL security</h6>
                        </div>
                    </div>
                    <div class="ui-block-b">
                        <a href="https://seal.digicert.com/seals/popup/?tag=bbFrnPZh&url=www.drgrab.com&lang=en&cbr=1441097102978"><img src="<?php echo $cdn ?>img/cascade.png" style="float: right;width:56px;height: 33px;"></a>
                    </div>
                </div>
            </div>
            <div class="dg-main-pay">
                <div class="dg-main-pay-list">
                    <label>
                        <div class="ui-grid-a">
                            <div class="ui-block-a">
                                <div class="dg-main-pay-list-title">
                                    <div class="iradio_square-blue" style="float:left;" id="input1"></div>
                                    <input type="radio" name="pay_type" class="dg-main-pay-list-title-input" value="2" data-role="none"  style="opacity: 0;"> 
                                    <small>Credit card</small>
                                </div>
                            </div>
                            <div class="ui-block-b">
                                <div class="dg-main-pay-list-img">
                                    <ul class="field__icon payment-method">
                                        <li class="payment-method visa"></li>
                                        <li class="payment-method master"></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </label>
                </div>
                <div class="dg-main-pay-item cchide">
                    <div class="ui-grid-a">
                        <div class="ui-block-a">Card Number</div>
                        <div class="ui-block-b"><input type="tel" pattern="[0-9]*" inputmode="numeric" x-autocomplete="cc-number" autocomplete="cc-number" id="credit_card_number" name="credit_card_number" placeholder="Card Number"></div>
                    </div>
                </div>
                <div class="dg-main-pay-item cchide">
                    <div class="ui-grid-a">
                        <div class="ui-block-a">First Name</div>
                        <div class="ui-block-b"><input type="text" id="credit_card_first_name" autocomplete="cc-given-name" name="credit_card_first_name" placeholder="Name on card"></div>
                    </div>
                </div>

                <div class="dg-main-pay-item cchide">
                    <div class="ui-grid-a">
                        <div class="ui-block-a">Last Name</div>
                        <div class="ui-block-b"><input type="text" id="credit_card_last_name" autocomplete="cc-family-name" name="credit_card_last_name" placeholder="Name on card"></div>
                    </div>
                </div>

                <div class="dg-main-pay-item cchide">
                    <div class="ui-grid-a">
                        <div class="ui-block-a">Expiry</div>
                        <div class="ui-block-b">
                            <div style="width:48%;float:left"><input type="number" pattern="[0-9]*" inputmode="numeric" autocomplete="cc-exp-month" name="credit_card_expiry_mm" id="credit_card_expiry_mm" maxlength="2" placeholder="MM"></div>
                            <div style="width:48%;float:right"><input type="number" pattern="[0-9]*" inputmode="numeric" autocomplete="cc-exp-year" name="credit_card_expiry_yy" id="credit_card_expiry_yy" maxlength="2" placeholder="YY"></div>
                        </div>

                    </div>
                </div>
                <div class="dg-main-pay-item cchide">
                    <div class="ui-grid-a">
                        <div class="ui-block-a">CVV</div>
                        <div class="ui-block-b"><input type="number" pattern="[0-9]*" inputmode="numeric" autocomplete="cc-csc" id="credit_card_cvv" maxlength="4" name="credit_card_cvv" placeholder="CVV"></div>
                    </div>
                </div>
                <div class="dg-main-pay-list" id="hidelist" style="border-top: 1px #ddd solid;">
                    <label>
                        <div class="ui-grid-a">
                            <div class="ui-block-a">
                                <div class="iradio_square-blue" style="float:left;" id="input2"></div>
                                <div class="dg-main-pay-list-title ">
                                    <input type="radio" name="pay_type" class="dg-main-pay-list-title-input" value="1" data-role="none"  style="opacity: 0;"> 
                                    <small>Paypal</small>
                                </div>
                            </div>
                            <div class="ui-block-b">
                                <div class=" dg-main-pay-list-img"><img src="<?php echo $cdn ?>img/paypal.png" ></div>
                            </div>
                        </div>   
                    </label>
                </div>
            </div>


            <?php if (!$this->session->userdata('member_email')): ?>
                <div class="dg-main-form" style="display:none">
                    <div class="dg-pagetitle">Shipping Address</div>
                    <!--<form>-->
                    <div class="ui-grid-a">
                        <div class="ui-block-a">
                            <input type="hidden" id="address_id" name="address_id" value="0" />
                            <label>First Name<span class="red">*</span></label>
                            <input type="text" name="firstname"  value="" class="form-control dg-requiredfield">
                        </div>
                        <div class="ui-block-b">
                            <label>Last Name<span class="red">*</span></label>
                            <input type="text" name="lastname"  value="" class="form-control dg-requiredfield">
                        </div>
                    </div>
                    <div class="ui-grid-a">
                        <div class="ui-block-a">
                            <label>Email<span class="red">*</span></label>
                            <input type="text" name="emailaddress"  id="emailaddress" value="" class="form-control dg-requiredfield">
                        </div>
                        <div class="ui-block-b">
                            <label>Phone</label>
                            <input type="text" name="phone"  value="" >
                        </div>
                    </div>
                    <div class="ui-grid-a">
                        <div class="ui-block-a" style="width:67%">
                            <label>Address<span class="red">*</span></label>
                            <input type="text" name="address"  value="" class="form-control dg-requiredfield">
                        </div>
                        <div class="ui-block-b" style="width:33%">
                            <label>Apt,Suite,etc</label>
                            <input type="text" name="apt"  value="">
                        </div>
                    </div>
                    <div class="ui-grid-a">
                        <div class="ui-block-a" style="width:67%">
                            <label><?php echo $addCountry['city'] ?><span class="red">*</span></label>
                            <input type="text" name="suburb"  class="form-control dg-requiredfield" value="<?php echo count($States) == 0 ? $countryList[$country]['name'] : '' ?>">
                        </div>
                        <div class="ui-block-b" style="width:33%">
                            <label><?php echo $addCountry['zipcode'] ?><span class="red">*</span></label>
                            <input type="text" name="postcode"  value="" class="form-control dg-requiredfield">
                        </div>
                    </div>
                    <div class="ui-grid-a">
                        <div class="ui-block-a" style="width:67%">
                            <label><?php echo $addCountry['state'] ?><span class="red">*</span></label>
                            <select data-theme="c" name="state" id="unstate">
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
                        <div class="ui-block-b" style="width:33%">
                            <label>COUNTRY<span class="red">*</span></label>
                            <input type="text" readonly id="country" value="<?php echo $countryList[$country]['name'] ?>" >

                        </div>
                    </div>
                    <!--</form>--> 
                </div>

                <div class="dg-main-form-billing" style="display: none">
                    <div class="dg-pagetitle" style="margin-top: 1em;">Billing Address</div>
                    <label><input type="checkbox" id="billing" name="billing" value="1" data-role="none" style="display:none"><span>&nbsp;&nbsp;Same As Shipping Address</span></label>
                    <!--<form>-->
                    <div class="billing" style="display: none">
                        <div class="ui-grid-a">
                            <div class="ui-block-a">
                                <input type="hidden" id="billaddress_id" name="billaddress_id" value="0" />
                                <label>First Name<span class="red">*</span></label>
                                <input type="text" name="bill_firstname"  value="" class="form-control dg-requiredfield2">
                            </div>
                            <div class="ui-block-b">
                                <label>Last Name<span class="red">*</span></label>
                                <input type="text" name="bill_lastname"  value="" class="form-control dg-requiredfield2">
                            </div>
                        </div>
                        <div class="ui-grid-a">
                            <div class="ui-block-a" style="width:67%">
                                <label>Address<span class="red">*</span></label>
                                <input type="text" name="bill_address"  value="" class="form-control dg-requiredfield2">
                            </div>
                            <div class="ui-block-b" style="width:33%">
                                <label>Apt,Suite,etc</label>
                                <input type="text" name="bill_apt"  value="">
                            </div>
                        </div>
                        <div class="ui-grid-a">
                            <div class="ui-block-a" style="width:67%">
                                <label><?php echo $addCountry['city'] ?><span class="red">*</span></label>
                                <input type="text" name="bill_suburb"  class="form-control dg-requiredfield2" value="<?php echo count($States) == 0 ? $countryList[$country]['name'] : '' ?>">
                            </div>
                            <div class="ui-block-b" style="width:33%">
                                <label><?php echo $addCountry['zipcode'] ?><span class="red">*</span></label>
                                <input type="text" name="bill_postcode"  value="" class="form-control dg-requiredfield2">
                            </div>
                        </div>
                        <div class="ui-grid-a">
                            <div class="ui-block-a" style="width:67%">
                                <label><?php echo $addCountry['state'] ?><span class="red">*</span></label>
                                <select data-theme="c" name="bill_suburb" id="unbillstate">
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
                            <div class="ui-block-b" style="width:33%">
                                <label>COUNTRY<span class="red">*</span></label>
                                <input type="text" readonly id="billcountry" value="<?php echo $countryList[$country]['name'] ?>" >

                            </div>
                        </div>
                    </div>
                    <!--</form>--> 
                </div>


            <?php else: ?>
                <!-- 已登录情况 -->

                <div class="dg-main-cart-address" style="display:none">
                    <div class="dg-pagetitle">Shipping Address</div>
                    <div class="dg-main-check" >
                        <?php if ($this->session->userdata('member_email') && $shippingAddress): ?>
                            <input type="hidden" id="address_id" name="address_id" value="<?php echo $shippingAddress[0]["receive_id"] ?>" />
                            <?php foreach ($shippingAddress as $address): ?>
                                <div class="dg-main-check-list" >
                                    <table>
                                        <tr>
                                            <td>
                                                <div class="iradio_square-blue dg-main-check-list-item" style="float:left;margin: 2em 0.5em;" data-receive="<?= $address['receive_id'] ?>"></div>
                                            </td>
                                            <td>
                                                <small style="text-transform: capitalize"><?= $address['receive_firstName'] ?> <?= $address['receive_lastName'] ?></small><br>
                                                <small><?= $address['receive_add2'] ?> <?= $address['receive_add1'] ?></small><br>
                                                <small><?= $address['receive_city'] ?> <?= $address['receive_province'] ?> <?= $address['receive_zipcode'] ?></small><br>
                                                <small><?= $address['receive_country'] ?></small><br>
                                                <small><?= $address['receive_phone'] ?></small><br> 
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            <?php endforeach ?>
                        <?php endif; ?>
                        <?php if ($shippingAddressCount < 5): ?>
                            <div class="dg-main-check-list" >
                                <div class="iradio_square-blue dg-main-check-list-item" style="float:left;margin: 0.2em 0.9em;" id="new_address" data-receive="0"></div>
                                <p>Use A New Shipping Address</p>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="dg-main-form-add" style="display:none">
                        <div class="dg-pagetitle">Add a shipping address</div>
                        <!--<form>-->
                        <div class="ui-grid-b">
                            <div class="ui-block-a">
                                <input type="hidden" name="emailaddress"  value="<?php echo $this->session->userdata('member_email') ?>">
                                <label>First Name<span class="red">*</span></label>
                                <input type="text" name="firstname" class="form-control dg-requiredfield3" value="<?= $this->session->userdata('member_email') ? ($shippingAddressCount ? '' : $member['member_firstName']) : '' ?>">
                            </div>
                            <div class="ui-block-b ">
                                <label>Last Name<span class="red">*</span></label>
                                <input type="text" name="lastname" class="form-control dg-requiredfield3"  value="<?= $this->session->userdata('member_email') ? ($shippingAddressCount ? '' : $member['member_lastName']) : '' ?>">
                            </div>
                            <div class="ui-block-c " >
                                <label>Phone</label>
                                <input type="text" name="phone" value="">
                            </div>
                        </div>
                        <div class="ui-grid-a">
                            <div class="ui-block-a" style="width:67%">
                                <label>Address<span class="red">*</span></label>
                                <input type="text" name="address" class="form-control dg-requiredfield3" value="">
                            </div>
                            <div class="ui-block-b" style="width:33%">
                                <label>Apt,Suite,etc</label>
                                <input type="text" name="apt" value="">
                            </div>
                        </div>
                        <div class="ui-grid-a">
                            <div class="ui-block-a" style="width:67%">
                                <label><?php echo $addCountry['city'] ?><span class="red">*</span></label>
                                <input type="text" name="suburb" class="form-control dg-requiredfield3" value="">
                            </div>
                            <div class="ui-block-b" style="width:33%">
                                <label><?php echo $addCountry['zipcode'] ?><span class="red">*</span></label>
                                <input type="text" name="postcode" class="form-control dg-requiredfield3" value="">
                            </div>
                        </div>
                        <div class="ui-grid-a">
                            <div class="ui-block-a" style="width:67%">
                                <label><?php echo $addCountry['state'] ?><span class="red">*</span></label>
                                <select data-theme="c" name="state" id="addstate">
                                    <option>Please select your <?php echo $addCountry['state'] ?></option>
                                    <?php foreach ($States as $StateCode => $StateName) : ?>
                                        <option value="<?php echo $StateName ?>"><?php echo $StateName ?></option>
                                    <?php endforeach; ?>
                                </select>       
                            </div>
                            <div class="ui-block-b" style="width:33%">
                                <label>COUNTRY<span class="red">*</span></label>
                                <input type="text" readonly="readonly" id="country" value="<?php echo $countryList[$country]['name'] ?>" />
                            </div>
                        </div>
                        <!--</form>--> 
                    </div>
                </div>

                <!-- billing address -->
                <div class="dg-main-cart-billaddress" style="display: none">
                    <div class="dg-pagetitle" style="margin-top: 1em;">Billing Address</div>
                    <label><input type="checkbox" id="billing" name="billing" value="1" data-role="none" style="display:none"><span>&nbsp;&nbsp;Same As Shipping Address</span></label>
                    <div class="billing" style="display: none;">
                        <div class="dg-main-check">
                            <?php if ($this->session->userdata('member_email') && $billAddress): ?>
                                <input type="hidden" id="billaddress_id" name="billaddress_id" value="<?php echo $billAddress[0]["receive_id"] ?>" />
                                <?php foreach ($billAddress as $bill): ?>
                                    <div class="dg-main-check-list" >
                                        <table>
                                            <tr>
                                                <td>
                                                    <div class="iradio_square-blue dg-main-check-list-item" style="float:left;margin: 2em 0.5em;" data-receive="<?php echo $bill['receive_id'] ?>"></div>
                                                </td>
                                                <td>
                                                    <small style="text-transform: capitalize"><?= $bill['receive_firstName'] ?> <?= $bill['receive_lastName'] ?></small><br>
                                                    <small><?= $bill['receive_add2'] ?> <?= $bill['receive_add1'] ?></small><br>
                                                    <small><?= $bill['receive_city'] ?> <?= $bill['receive_province'] ?> <?= $bill['receive_zipcode'] ?></small><br>
                                                    <small><?= $bill['receive_country'] ?></small><br>

                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                <?php endforeach ?>
                            <?php endif; ?>
                            <?php if ($billAddressCount < 5): ?>
                                <div class="dg-main-check-list" >
                                    <div class="iradio_square-blue dg-main-check-list-item" style="float:left;margin: 0.2em 0.9em;" id="billnew_address" data-receive="0"></div>
                                    <p>Use A New Billing Address</p>
                                </div>
                            <?php endif; ?>              
                        </div>

                        <div class="dg-main-form-add-billing" style="display: none">
                            <div class="dg-pagetitle">Add a billing address</div>
                            <!--<form>-->
                            <div class="ui-grid-a">
                                <div class="ui-block-a">
                                    <label>First Name<span class="red">*</span></label>
                                    <input type="text" name="bill_firstname" class="form-control dg-requiredfield1" value="<?= $this->session->userdata('member_email') ? ($billAddressCount ? '' : $member['member_firstName']) : '' ?>">
                                </div>
                                <div class="ui-block-b ">
                                    <label>Last Name<span class="red">*</span></label>
                                    <input type="text" name="bill_lastname" class="form-control dg-requiredfield1"  value="<?= $this->session->userdata('member_email') ? ($billAddressCount ? '' : $member['member_lastName']) : '' ?>">
                                </div>
                            </div>
                            <div class="ui-grid-a">
                                <div class="ui-block-a" style="width:67%">
                                    <label>Address<span class="red">*</span></label>
                                    <input type="text" name="bill_address" class="form-control dg-requiredfield1" value="">
                                </div>
                                <div class="ui-block-b" style="width:33%">
                                    <label>Apt,Suite,etc</label>
                                    <input type="text" name="bill_apt" value="">
                                </div>
                            </div>
                            <div class="ui-grid-a">
                                <div class="ui-block-a" style="width:67%">
                                    <label><?php echo $addCountry['city'] ?><span class="red">*</span></label>
                                    <input type="text" name="bill_suburb" class="form-control dg-requiredfield1" value="">
                                </div>
                                <div class="ui-block-b" style="width:33%">
                                    <label><?php echo $addCountry['zipcode'] ?><span class="red">*</span></label>
                                    <input type="text" name="bill_postcode" class="form-control dg-requiredfield1" value="">
                                </div>
                            </div>
                            <div class="ui-grid-a">
                                <div class="ui-block-a" style="width:67%">
                                    <label><?php echo $addCountry['state'] ?><span class="red">*</span></label>
                                    <select data-theme="c" name="bill_state" id="addbillstate">
                                        <option>Please select your <?php echo $addCountry['state'] ?></option>
                                        <?php foreach ($States as $StateCode => $StateName) : ?>
                                            <option value="<?php echo $StateName ?>"><?php echo $StateName ?></option>
                                        <?php endforeach; ?>
                                    </select>       
                                </div>
                                <div class="ui-block-b" style="width:33%">
                                    <label>COUNTRY<span class="red">*</span></label>
                                    <input type="text" readonly="readonly" id="country" value="<?php echo $countryList[$country]['name'] ?>" />
                                </div>
                            </div>
                            <!--</form>--> 
                        </div>
                    </div>
                </div>


            <?php endif; ?>

            <div class="dg-pagetitle">Coupon</div>            

            <!-- <div class="dg-main-cart-coupon">
                <div class="ui-grid-a">
                    <div class="ui-block-a" style="width:75%;">
                        <input type="text" name="coupon"  id="coupon_id" placeholder="Coupon Code Here">
                    </div>
                    <div class="ui-block-b" style="width:20%;float: right;">
                        <button id="coupon"  type="button" style="background-color: #00B6C6;border: none;width:auto;float:right;padding: 0.5em 1em;margin: 0" data-role="false">Apply</button>
                    </div>
                </div>
                <select data-theme="c" id="coupon_select">
                    <option>Coupon Code Here</option>
            <?php if ($this->session->userdata('member_email')): ?>
                <?php foreach ($myCoupons as $key => $Coupons): ?>
                                                            <option><?= $key ?></option>
                <?php endforeach ?>
            <?php endif; ?>
                </select>
            </div> -->

            <div class="dg-main-check">
                <div class="dg-main-check-list-discount">
                    <label>
                        <table>
                            <tr>
                                <td><input class="" type="radio" value="COUPON" name="ss" id="couponss" data-role="none" style="display:none"></td>
                                <td>
                                    <div class="ui-grid-a">
                                        <div class="ui-block-a" style="width:70%;">
                                            <input type="text" name="coupon"  id="coupon_id" placeholder="<?php
                                            if ($this->session->userdata('member_email'))
                                                echo "Coupon Code Here";
                                            else
                                                echo "Login To Use Coupons";
                                            ?>" <?php if (!$this->session->userdata('member_email')) echo 'disabled="disabled"' ?>>
                                        </div>
                                        <div class="ui-block-b" style="width:20%;float: right;">
                                            <button id="coupon"  type="button" style="background-color: #00B6C6;border: none;width:auto;float:right;padding: 0.6em 1em;margin: 0" data-role="false" <?php if (!$this->session->userdata('member_email')) echo 'disabled="disabled"' ?>>Apply</button>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        </table>      
                    </label>
                </div>
                <div id="discountLabel" class="dg-main-check-list" <?php if ($collection_offer == 0) echo 'style="display:none"' ?>>
                    <label>
                        <table>
                            <tr>
                                <td>
                                    <input class="" type="radio" value="DISCOUNT" id="discount" name="ss" data-role="none" style="display:none">
                                </td>
                                <td>
                                    <small>Collection Discount <?= $currency ?><span id="discountNumber"><?= $collection_offer ?></span></small>
                                </td>
                            </tr>
                        </table>
                    </label>
                </div>
            </div>
            <div class="dg-pagetitle">Complete Your Order</div>

            <div class="dg-main-cart-total">
                <h4>Subtotal<small><?= $currency ?><span class="subtotal"></span></small></h4>
                <h4><b></b><small><?= $currency ?><span>0</span></small></h4>
                <h4 style="display:none" class="coupon" data-coupon="true" data-clickapply="0">Coupon<small>-<?= $currency ?><span>0</span></small></h4>
                <h4 style="display:none" class="collectiondiscount">Collection Discount<small>-<?= $currency ?><span>0</span></small></h4>
                <h4 style="display:none" class="insurance">Shipping Insurance<small><?= $currency ?><span>0</span></small></h4>
                <h4 style="display:none" class="giftpacking">Shopping Bag<small><?= $currency ?><span>0</span></small></h4>
                <h4 class="total">Total<small><?= $currency ?><span></span></small></h4>
            </div>

            <button type="submit" data-theme="g" id="pay">
                Pay Securely Now
            </button>

            <div class="dg-main-pay-secure">
                <span class="methodpaypal"> You will be <span class="success-text">redirected to PayPal's secure
                        website</span> to complete your order</span> <span
                    class="methodcc"> Your payment for
                    this purchase is processed securely.</span>
            </div>
            <?php echo form_close(); ?>
        </div>

    <?php else: ?>
        <div role="main" class="ui-content">
            <div class="dg-pagetitle">My Cart</div>
            <div class="dg-main-empty">
                <div class="dg-main-empty-icon">
                    <span class="icon-cart"></span>
                </div>
                <div class="dg-main-empty-msg">
                    <span>You have not placed any product!</span>
                </div>
                <a href="<?= $domain ?>"><button data-role="none">Go Shopping</button></a>
            </div>
        </div>

    <?php endif; ?>

    <?php echo $foot; ?>
</div>
<script src="<?php echo $cdn ?>js/cartdata.js"></script>
<script>
                                                var currency = "<?php echo $currency; ?>";
</script>
<script>
    $('#insurance,#giftbox').iCheck('uncheck');
    $(document).ready(function () {
        $('.dg-main-Insurance input').iCheck({
            checkboxClass: 'icheckbox_square-blue',
            radioClass: 'iradio_square-blue',
            increaseArea: '20%' // optional
        });
    });
    $(document).ready(function () {
        $('.dg-main-check input').iCheck({
            checkboxClass: 'iradio_square-blue',
            radioClass: 'iradio_square-blue',
            increaseArea: '20%' // optional
        });
    });
    $(document).ready(function () {
        $('#billing').iCheck({
            checkboxClass: 'icheckbox_square-blue',
            radioClass: 'iradio_square-blue',
            increaseArea: '20%' // optional
        });
    });

    $('#billing').iCheck('check');
    $('#billing').on('ifUnchecked', function (event) {
        $('.billing').show();
        $('.billing').addClass('billshow');
    });
    $('#billing').on('ifChecked', function (event) {
        $('.billing').hide();
        $('.billing').removeClass('billshow');
    });
</script>
<script>

    $(function () {
        $("#credit_card_expiry_mm").keyup(function () {
            if ($("#credit_card_expiry_mm").val().length == 2) {
                $("#credit_card_expiry_yy").focus();
            }
        });
        $("#credit_card_expiry_yy").keyup(function () {
            if ($("#credit_card_expiry_yy").val().length == 2) {
                $("#credit_card_cvv").focus();
            }
        });
    });

    $(function () {
        $('.guest').click(function () {
            $('.dg-main-cart-footer').hide();
        })
        sumprice(1);
        $('.dg-main-check-list').find('input').eq(0).attr('checked', true);
        $('.dg-main-check-list').find('.iradio_square-blue').removeClass('checked');
        $('.dg-main-check-list').find('.iradio_square-blue').eq(0).addClass('checked');

        $('.dg-main-cart-address').find('.iradio_square-blue').removeClass('checked');
        $('.dg-main-cart-address').find('.iradio_square-blue').eq(0).addClass('checked');

        $('.dg-main-cart-billaddress .dg-main-check-list').find('.iradio_square-blue').eq(0).addClass('checked');
        $('.dg-main-cart-billaddress').find('.iradio_square-blue').eq(0).addClass('checked');

        if ("<?php echo $this->session->userdata('member_email') ?>") {
            $('.dg-main-pay input[name=pay_type]').on('click', function (event) {
                if (event.target.defaultValue === "2") {

                    fbq('track', 'InitiateCheckout');

                    $('#input1').addClass('checked');
                    $('#input2').removeClass('checked');
                    $(".cchide").show();
                    $(".methodcc").show();
                    $(".methodpaypal").hide();
                    $('.dg-main-cart-address').show();
                    $('.dg-main-cart-billaddress').show();
                }
                else {

                    fbq('track', 'InitiateCheckout');

                    $('#input2').addClass('checked');
                    $('#input1').removeClass('checked');
                    $(".cchide").hide();
                    $(".methodcc").hide();
                    $('.dg-main-cart-address').hide();
                    $('.dg-main-cart-billaddress').hide();
                    $(".methodpaypal").show();
                }
            });
        } else {

            $('.dg-main-pay input[name=pay_type]').on('click', function (event) {
                if (event.target.defaultValue === "2") {

                    fbq('track', 'InitiateCheckout');

                    $('#input1').addClass('checked');
                    $('#input2').removeClass('checked');
                    $(".cchide").show();
                    $(".methodcc").show();
                    $(".methodpaypal").hide();
                    $('.dg-main-form').show();
                    $('.dg-main-form-billing').show();
                }
                else {

                    fbq('track', 'InitiateCheckout');

                    $('#input2').addClass('checked');
                    $('#input1').removeClass('checked');
                    $(".cchide").hide();
                    $(".methodcc").hide();
                    $(".methodpaypal").show();
                    $('.dg-main-form').hide();
                    $('.dg-main-form-billing').hide();
                }
            });
        }

        //shipping address
        $('.dg-main-cart-address .dg-main-check-list').click(function () {
            $('.dg-main-form-add').hide();
            $('#address_id').val($(this).find('div').data('receive'));
            $('.dg-main-cart-address .dg-main-check-list-item').removeClass('checked');
            $(this).find('.iradio_square-blue').addClass('checked');
        });

        if ($('#new_address').hasClass('checked')) {
            $('.dg-main-form-add').show();
        }
        $('#new_address').parent().click(function () {
            $('.dg-main-form-add').show();
            $('#address_id').val(0);
        });

        //billing address
        $('.dg-main-cart-billaddress .dg-main-check-list').click(function () {
            $('.dg-main-form-add-billing').hide();
            $('#billaddress_id').val($(this).find('div').data('receive'));
            $('.dg-main-cart-billaddress .dg-main-check-list-item').removeClass('checked');
            $(this).find('.iradio_square-blue').addClass('checked');
        });

        if ($('#billnew_address').hasClass('checked')) {
            $('.dg-main-form-add-billing').show();
        }
        $('#billnew_address').parent().click(function () {
            $('.dg-main-form-add-billing').show();
            $('#billaddress_id').val(0);
        });
    });



    function qtyUp(id) {
        $('body').isLoading({
            text: "Updating",
            position: "overlay",
            class: "fa-refresh", // loader CSS class
            tpl: '<span class="isloading-wrapper %wrapper%">' + szimg + '</span>'
        });
        id_true = 'qty_' + id;
        var qty_el = $("#" + id_true).text();
        $.ajax({
            type: "POST",
            url: "<?php echo site_url('cart/updateCart') ?>",
            dataType: 'json',
            data: {
                p_sku: $("#" + id_true).data("sku"),
                state: 0
            },
            success: function (result) {
                if (result.success) {
                    var qty = ++qty_el;
                    $("#" + id_true).text(qty);
                    sumprice(1);
                    sumcartpronu();
                    if (result.discountNumber == 0) {
                        $('#discountLabel').css("display", "none");
                        $('#discount').removeAttr('checked');
                        $('.collectiondiscount').css("display", "none");
                    } else {
                        $('#discountLabel').css("display", "");
                    }
                    recalprice(result.discountNumber);
                }
                $('body').isLoading("hide");
            }
        });
        return false;
    }




    function qtyDown(id) {
        id_true = 'qty_' + id;
        var qty_el = $("#" + id_true).text();
        if ($("#" + id_true).text() != "1") {
            $('body').isLoading({
                text: "Updating",
                position: "overlay",
                class: "fa-refresh", // loader CSS class
                tpl: '<span class="isloading-wrapper %wrapper%">' + szimg + '</span>'
            });
        }
        $.ajax({
            type: "POST",
            url: "<?php echo site_url('cart/updateCart') ?>",
            dataType: 'json',
            data: {
                p_sku: $("#" + id_true).data("sku"),
                state: 1
            },
            success: function (result) {
                if (result.success) {
                    var qty = --qty_el;
                    if (qty < 1) {
                        qty = 1;
                    }
                    $("#" + id_true).text(qty);
                    sumcartpronu();
                    if (result.discountNumber == 0) {
                        $('#discountLabel').css("display", "none");
                        $('#discount').removeAttr('checked');
                        $('.collectiondiscount').css("display", "none");
                    } else {
                        $('#discountLabel').css("display", "");
                    }
                    recalprice(result.discountNumber);
                }
                $('body').isLoading('hide');
            }
        });
        return false;
    }




    function del(id) {
        $('body').isLoading({
            text: "Updating",
            position: "overlay",
            class: "fa-refresh", // loader CSS class
            tpl: '<span class="isloading-wrapper %wrapper%">' + szimg + '</span>'
        });
        id_true = 'del_' + id;
        $.ajax({
            type: "POST",
            url: "<?php echo site_url('cart/delCart') ?>",
            dataType: 'json',
            data: {
                p_sku: $("#" + id_true).data("sku"),
            },
            success: function (result) {
                if (result.success) {
                    $('#cart_pro_' + id).fadeOut(200);
                    setTimeout(function () {
                        $('#cart_pro_' + id).remove();
                        sumprice(1);
                    }, 250);
                    setTimeout(sumcartpronu, 260);
                }
                $('body').isLoading('hide');
                $('#cart_pro_' + id).remove();
                if ($('.dg-main-cart li').length == 0) {
                    location.reload();
                }
                if (result.discountNumber == 0) {
                    $('#discountLabel').css("display", "none");
                    $('#discount').removeAttr('checked');
                    $('.collectiondiscount').css("display", "none");
                } else {
                    $('#discountLabel').css("display", "");
                }
                recalprice(result.discountNumber);
            }
        });
        setTimeout(cartpronumshow, 4000);
        // cartpronumshow();
    }

    function sumcartpronu() {
        //var spannum = $(".dg-main-cart span[id^='qty_']").length;
        var num = 0;
        $(".dg-main-cart span[id^='qty_']").each(function () {
            num = num + parseInt($(this).text());
        });
        setTimeout(function () {
            $('#cartpronum').text(num);
        }, 400);
        //alert($(".dg-main-cart span[id^='qty_']").length);

    }

    function calcoupon(c) {
        if (($('#coupon_id').val() != '') && (!/^[a-zA-Z0-9_]{5,20}$/i.test($('#coupon_id').val()))) {
            $.notifyBar({cssClass: "dg-notify-error", html: 'Invalid coupon code', position: "bottom"});
            return false;
        }
        if ($('#coupon_id').val() === '') {
            $('.coupon').data('clickapply', 1);
            $('.coupon').fadeOut(100);
            $('.coupon span').text(0);
            $("#coupon_id").removeAttr("data-type");
            $("#coupon_id").removeAttr("data-amount");
            $("#coupon_id").removeAttr("data-condition");
            $("#coupon_id").removeAttr("data-min");
            $("#coupon_id").removeAttr("data-max");
            recalprice(-1);
            return false;
        }

        $.ajax({
            type: "POST",
            url: "<?php echo site_url('cart/checkCoupon') ?>",
            dataType: 'json',
            data: {
                coupon_id: $("#coupon_id").val()
            },
            success: function (result) {

                if (result.success) {
                    $('.coupon').data('clickapply', 1);
                    $("#coupon_id").attr("data-type", result.couponInfo.type);
                    $type = parseInt(result.couponInfo.type);

                    $("#coupon_id").attr("data-amount", result.couponInfo.amount / 100);
                    $("#coupon_id").attr("data-condition", result.couponInfo.condition);
                    $condition = parseInt(result.couponInfo.condition);


                    $("#coupon_id").attr("data-min", result.couponInfo.min / 100);
                    $("#coupon_id").attr("data-max", result.couponInfo.max / 100);
                    $(".coupon").fadeIn(100);
                    var coupon = result.couponInfo.amount / 100,
                            minnum = result.couponInfo.min / 100,
                            subtotal = parseFloat($("#subtotal").text()),
                            maxnum = result.couponInfo.max / 100;
                    if (c == 1) {
                        coupon = 0;
                    }
                    sumprice(1);
                    //type=1代表coupon类型为$AUD
                    if ($type == 1) {
                        if ($condition == 1) {
                            var subtotals = $('.subtotal').text();
                            if (subtotals < coupon) {
                                $.notifyBar({cssClass: "dg-notify-error", html: 'The coupon could not be used for this order', position: "bottom"});
                                $('.coupon small').html("-" + currency + "<span>" + "0" + "<span>");
                                $('#coupon_id').val("");
                                /*$('#coupon_select option').eq(0).attr('selected', 'selected');
                                 $('#coupon_select').parent().find('span').text('Coupon Code Here');*/
                                sumprice(1);
                            } else {
                                $('.coupon small').html("-<?= $currency ?><span>" + (coupon) + "</span>");
                                sumprice(1);
                            }

                        } else if ($condition == 2) {

                            $('.coupon small').html("-<?= $currency ?><span>" + (coupon) + "</span>");
                            csumprice(2, coupon, minnum, maxnum);

                        } else if ($condition == 3) {

                            csumprice(1, coupon, minnum, maxnum);
                        }
                        ;
                        //type=2代表coupon类型为%Discount
                    } else if ($type == 2) {
                        if ($condition == 1) {
                            $('.coupon small').html("-<span>" + coupon * 100 + "</span>%");
                            sumprice(2);
                        } else if ($condition == 2) {

                            $subtotal = parseFloat($('.subtotal').text());
                            if ($subtotal >= minnum && $subtotal <= maxnum) {
                                $('.coupon small').html("-<span>" + (coupon * 100) + "</span>%");
                                sumprice(2);
                            } else {
                                $.notifyBar({cssClass: "dg-notify-error", html: 'The coupon could not be used for this order', position: "bottom"});
                                $('.coupon small').html("-" + currency + "<span>" + "0" + "<span>");
                                $('#coupon_id').val("");
                                /*$('#coupon_select option').eq(0).attr('selected', 'selected');
                                 $('#coupon_select').parent().find('span').text('Coupon Code Here');*/
                                sumprice(2);
                            }
                        } else if ($condition == 3) {

                            csumprice(3, coupon, minnum, maxnum);
                        }
                        ;
                        //type=3代表free-shipping
                    } else if ($type == 3) {

                        if ($condition == 1) {
                            var shippings = $('.dg-main-cart-total h4:nth-child(2) span').text()
                            $('.coupon small').html("-<?= $currency ?><span>" + (shippings) + "</span>");
                            sumprice(1);
                        } else if ($condition == 2) {
                            $subtotal = parseFloat($('.subtotal').text());
                            if ($subtotal >= minnum && $subtotal <= maxnum) {
                                var shippings = $('.dg-main-cart-total h4:nth-child(2) span').text()
                                $('.coupon small').html("-<?= $currency ?><span>" + (shippings) + "</span>");
                                sumprice(1);
                                $('.coupon').data('coupon', true);
                            } else {
                                $.notifyBar({cssClass: "dg-notify-error", html: 'The coupon could not be used for this order', position: "bottom"});
                                sumprice(1);
                                $('.coupon small').html("-" + currency + "<span>" + "0" + "<span>");
                                $('#coupon_id').val("");
                                /*$('#coupon_select option').eq(0).attr('selected', 'selected');
                                 $('#coupon_select').parent().find('span').text('Coupon Code Here');*/
                                $('.coupon').data('coupon', false);
                                sumprice(1);
                            }
                            ;
                        } else if ($condition == 3) {
                            csumprice(4, coupon, minnum, maxnum);
                        }
                        ;
                    }
                    ;

                } else {
                    $('.coupon').data('clickapply', 0);
                    $.notifyBar({cssClass: "dg-notify-error", html: result.error, position: "bottom"});
                }
            }
        });
    }

    /*$('#coupon_select').change(function () {
     if ($(this).val() != "Coupon Code Here") {
     $('#coupon_id').val($(this).val());
     $("#coupon").click();
     }
     })*/
    $("#coupon").click(function () {
        calcoupon(0);
    });
    $("#coupon_id").blur(function () {
        if ($('#discount').is(':checked')) {
            calcoupon(1);
        } else {
            calcoupon(0);
        }
    });
    $('#couponss').on('ifChecked', function () {
        calcoupon(0);
    });

    var cardvalid = true;
    var carderror = '';
    $('.dg-main-pay input[name=pay_type]').on('click', function (event) {
        if (event.target.defaultValue === "2") {
            $('#credit_card_first_name,#credit_card_last_name,#credit_card_expiry_mm,#credit_card_expiry_yy,#credit_card_cvv').focus(function () {
                if ($('#credit_card_number').val() != '') {
                    $('#credit_card_number').validateCreditCard(function (result) {
                        if (result.length_valid) {
                            if (result.card_type.name == "American Express" || result.card_type.name == "JCB" || result.card_type.name == "Discover") {
                                $.notifyBar({cssClass: "dg-notify-error", html: 'Sorry, we do not accept ' + result.card_type.name + ' directly ( Please Choose PayPal to use it ) ', position: "bottom"});
                                $('#credit_card_first_name,#credit_card_last_name,#credit_card_expiry_mm,#credit_card_expiry_yy,#credit_card_cvv').blur();
                                carderror = result.card_type.name;
                                cardvalid = false;
                            } else {
                                cardvalid = true;
                            }
                        }
                    });
                }
            });
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
                if ($('#new_address').hasClass('checked')) {
                    ifValid = true;
                    $(".dg-requiredfield3").each(function () {
                        if (!$(this).val()) {
                            ifValid = false;
                            $(this).css('border-color', '#dd514c');
                        }
                    });
                    if (!ifValid) {
                        $.notifyBar({cssClass: "dg-notify-error", html: 'Please fill in all mandatory fields marked *', position: "bottom"});

                        return false;
                    }
                    if ($('#addstate').val() == "Please select your <?php echo $addCountry['state'] ?>") {
                        $.notifyBar({cssClass: "dg-notify-error", html: 'Please fill in all mandatory fields marked *', position: "bottom"});
                        return false;
                    }
                }
                if ($('#billnew_address').hasClass('checked') && $('.billing').hasClass('billshow')) {
                    ifValid = true;
                    $(".dg-requiredfield1").each(function () {
                        if (!$(this).val()) {
                            ifValid = false;
                            $(this).css('border-color', '#dd514c');
                        }
                    });
                    if (!ifValid) {
                        $.notifyBar({cssClass: "dg-notify-error", html: 'Please fill in all mandatory fields marked *', position: "bottom"});

                        return false;
                    }
                    if ($('#addbillstate').val() == "Please select your <?php echo $addCountry['state'] ?>") {
                        $.notifyBar({cssClass: "dg-notify-error", html: 'Please fill in all mandatory fields marked *', position: "bottom"});
                        return false;
                    }
                }
            } else {
                ifValid = true;
                $(".dg-requiredfield").each(function () {
                    if (!$(this).val()) {
                        ifValid = false;
                        $(this).css('border-color', '#dd514c');
                    }
                });
                if (!ifValid) {
                    $.notifyBar({cssClass: "dg-notify-error", html: 'Please fill in all mandatory fields marked *', position: "bottom"});

                    return false;
                }
                if ($('#unstate').val() == "Please select your <?php echo $addCountry['state'] ?>") {
                    $.notifyBar({cssClass: "dg-notify-error", html: 'Please fill in all mandatory fields marked *', position: "bottom"});
                    return false;
                }

                if ($('.billing').hasClass('billshow')) {
                    ifValids = true;
                    $(".dg-requiredfield2").each(function () {
                        if (!$(this).val()) {
                            ifValids = false;
                            $(this).css('border-color', '#dd514c');
                        }
                    });
                    if (!ifValids) {
                        $.notifyBar({cssClass: "dg-notify-error", html: 'Please fill in all mandatory fields marked *', position: "bottom"});

                        return false;
                    }
                    if ($('#unbillstate').val() == "Please select your <?php echo $addCountry['state'] ?>") {
                        $.notifyBar({cssClass: "dg-notify-error", html: 'Please fill in all mandatory fields marked *', position: "bottom"});
                        return false;
                    }

                }

                if (!$("#emailaddress").val().match(/^([a-zA-Z0-9_.-])+@([a-zA-Z0-9_-])+(.[a-zA-Z0-9_-])+/)) {
                    $.notifyBar({cssClass: "dg-notify-error", html: 'Please provide a valid email address', position: "bottom"});
                    return false;
                }
            }
        }

        $('#pay').attr('disabled', 'disabled');
        $('#pay').html('Payment in progress ...');
    }
</script>
<?php if (isset($countrySEO)) echo $countrySEO ?>
</body>
</html>