<div bgcolor="#fff" style="width:100%;margin:0 auto; font-family:Helvetica,Arial,sans-serif;">
    <div style="margin:0 auto;width:640px;border:10px solid #baebf2;font-size: 16px;">
        <table width="640" border="0" cellpadding="0" cellspacing="0" style="background-color:white;">
            <tr>
                <td height="20"></td>
            </tr>
            <tr>
                <td width="20" height="100%"></td>
                <td width="600" height="100%">
                    <table width="100%" border="0" cellpadding="0" cellspacing="0">
                        <tr>
                            <td height="90" width="500">
                                <a href='<?= $shopurl ?>/'><img src="<?= STATIC_HTTP_DOMAIN ?>/template_pc/en/image/email/email_logo.png" alt=""></a>
                            </td>
                            <td width="45" valign="top">
                                <a href='https://www.facebook.com/drgrab'><img src="<?= STATIC_HTTP_DOMAIN ?>/template_pc/en/image/email/email_facebook.png"></a>
                            </td>
                            <td width="40" valign="top">
                                <a href='https://twitter.com/Drgrab'><img src="<?= STATIC_HTTP_DOMAIN ?>/template_pc/en/image/email/email_twitter.png"></a>
                            </td>
                        </tr>
                    </table>
                    <table width="100%" border="0" cellpadding="0" cellspacing="0">
                        <tr height="20"></tr>
                        <tr>
                            <td style="font-size: 16px;">
                                <p>Dear <?=  $name ? $name : "Member"?>,</p>
                                <p>Thank you for being a Grabber and placing your order with DrGrab! </p>
                                <p>Here is your recent order.</p>
                                <p><strong>Date</strong> <?= date('d/m/Y', $time) ?></p>
                                <h3 style="color: #00B6C6">Shipping address</h3>
                                <p>
                                <p><?= $insert_shipData['receive_firstName'] ?><?= $insert_shipData['receive_lastName'] ?></p>
                                <p><?= $insert_shipData['receive_add2'] ?>  <?= $insert_shipData['receive_add1'] ?></p>
                                <p><?= $insert_shipData['receive_province'] ?>  <?= $insert_shipData['receive_city'] ?>  <?= $insert_shipData['receive_zipcode'] ?></p>
                                <p><?= $insert_shipData['receive_country'] ?></p>
                                </p>
                                <h3 style="color: #00B6C6">Item</h3>
                                <?php foreach ($insert_detailsData as $details): ?>
                                    <p><?= $details['product_quantity'] ?> × <?= $details['product_name'] ?> <?= $currency ?><?= $details['payment_price'] / 100 ?> each</p>
                                <?php endforeach ?>
                                <p><strong>Subtotal : </strong><?= $currency ?><?= round(($insert_orderData['order_amount']-$insert_orderData['order_insurance']-$insert_orderData['order_giftbox']) / 100,2) ?> </p>
                                <?php if ($insert_orderData['order_insurance']): ?>
                                    <p><strong>Insurance :</strong> <?= $currency ?><?= $insert_orderData['order_insurance'] / 100 ?></p>
                                <?php endif; ?>
                                <?php if ($insert_orderData['order_giftbox']): ?>
                                    <p><strong>Giftbox :</strong> <?= $currency ?><?= $insert_orderData['order_giftbox'] / 100 ?></p>
                                <?php endif; ?>
                                <?php if ($insert_orderData['offers_amount']): ?>
                                    <p><strong>Offers_amount :</strong> <?= $currency ?><?= round($insert_orderData['offers_amount'] / 100,2) ?></p>
                                <?php endif; ?>
                                <p><strong>Shipping :</strong> <?= $currency ?><?= $insert_orderData['freight_amount'] / 100 ?> </p>
                                <p><strong>Total :</strong> <?= $currency ?><?= round($insert_orderData['payment_amount'] / 100,2) ?> </p>
                                <p>Please do not hesitate to contact us if you have any further questions.</p>
                                <p><img src="<?php echo STATIC_HTTP_DOMAIN; ?>/template_pc/en/image/email/service_<?= mt_rand(1,4)?>.gif"><br>

                            </td>
                        </tr>
                        <tr height="40"></tr>
                        <tr>
                            <td><img src="<?= STATIC_HTTP_DOMAIN ?>/template_pc/en/image/email/ser.png" alt="" width="600"></td>
                        </tr>
                        <tr height="40"></tr>
                        <tr>
                            <td align="center" width="600" style="color:#999;">
                                To keep receiving emails from us,<br />please add <span style="color:#336699;"><?= $shopmail?></span> to your address book
                            </td>
                        </tr>
                    </table>
                </td>               
                <td width="20" height="100%"></td>
            </tr>
            <tr>
                <td height="40"></td>
            </tr>       
        </table>
        
    </div>
</div> 