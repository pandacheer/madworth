<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"> 
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title> </title>
<style type="text/css" media="all">
BODY {MARGIN-LEFT: auto; WIDTH: 100%! important; MARGIN-RIGHT: auto; BACKGROUND-COLOR: #f0f0f0} 
body { width: 100% !important; }
body { -webkit-text-size-adjust: none; -ms-text-size-adjust: none; } 
body { margin: 0; padding: 0; }
img {outline: none; text-decoration: none; }
 #backgroundTable {margin: 0; padding: 0; width: 100% !important; }
a {text-decoration:underline;color:#00B6C6;font-weight:normal;}
 h1, h2, h3, h4, h5, h6 {color: black !important;line-height: 100% !important;}
h1 a, h2 a, h3 a, h4 a, h5 a, h6 a { color: #494949 !important; }
h1 a:active, h2 a:active,  h3 a:active, h4 a:active, h5 a:active, h6 a:active { color: #494949 !important; }
h1 a:visited, h2 a:visited,  h3 a:visited, h4 a:visited, h5 a:visited, h6 a:visited {color: #494949 !important;}
table td {border-collapse: collapse;}
</style>
</head>
<body  bgcolor="#fff" style="BACKGROUND-COLOR:#fff;font-family:Helvetica,Arial,sans-serif;">
<table cellspacing="0" cellpadding="0" width="640" align="center" bgcolor="#fff" border="0" id="backgroundTable" style="padding:20px;">
    <tr>  
        <td width="640">
            <table cellspacing="0" cellpadding="0" width="620" align="center" bgcolor="#ffffff" border="0" style="border:10px solid #baebf2;margin: 0 auto;">
                <tr>
                    <td height="20"></td>
                </tr>
                <tr>
                    <td>
                        <table cellspacing="0" cellpadding="0" width="560" align="center" bgcolor="#ffffff" border="0" style="margin: 0 auto;"> 
                            <tr>
                                <td width="387" valign="top"><a href='<?= $shopurl ?>/'><img src="<?= STATIC_HTTP_DOMAIN ?>/template_pc/en/image/email/email_logo.png" alt=""></a></td>
                                <td width="40" valign="top"><a href='https://www.facebook.com/drgrab'><img src="<?= STATIC_HTTP_DOMAIN ?>/template_pc/en/image/email/email_facebook.png"></a></td>
                                <td width="44" valign="top"><a href='https://twitter.com/Drgrab'><img src="<?= STATIC_HTTP_DOMAIN ?>/template_pc/en/image/email/email_twitter.png"></a></td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr height="20"></tr>

                
                <tr>
                    <td>
                        <table cellspacing="0" cellpadding="0" width="560" align="center" bgcolor="#ffffff" border="0" style="margin: 0 auto;">
                            <tr>
                                <td align="center">
                                    <p style="color: #00B6C6;font-size: 26px;margin:0;padding: 0">Thank you for being a Grabber and<br> placing your order with DrGrab!</p>
                                    <br>
                                    <p style="color: #00B6C6;font-size: 21px;margin:0;padding: 0">( Estimated Time of Arrival: <span style="color: #FF5062"><?php echo date('M, d Y', strtotime('+' . $insert_orderData["estimated_time"] . 'day', $insert_orderData['create_time']) ) ?></span>)</p>
                                </td>
                            </tr>
                            <tr height="20"></tr>
                            <tr>
                                <td>
                                    <div style="border:1px #DDDDDD solid;">
                                        <table width="100%" border="0" cellpadding="0" cellspacing="0" align="center" style="margin: 0 auto;">
                                            <tr style="background-color: #F3F3F3" height="30">
                                                <td></td>
                                                <td>Item</td>
                                                <td>Price</td>
                                                <td>Qty</td>
                                            </tr>
                                            
                                            <?php foreach ($insert_detailsData as $details): ?>
                                            <tr>
                                                <td style="border-top:1px #DDDDDD solid" width="90"><a href="<?php echo site_url('/products/'.$details['seo_url']);?>" ><img width="70" style="padding:10px;" src="<?php echo STATIC_HTTP_DOMAIN.$details['image'];?>"></a></td>
                                                <td style="border-top:1px #DDDDDD solid" width="290">
                                                    <a href="<?php echo site_url('/products/'.$details['seo_url']);?>" style="color: #00B6C6;text-decoration: none"><?= $details['product_name'] ?></a><br>
                                                    <span style="color: gray"><?= $details['product_attr'] ?></span>
                                                    <?php if ($details['freebies']) :?>
                                                        <br><span style="color: #FF696E">+<?= $currency ?><?= $details['payment_price'] / 100 ?> Additional Shipping Fee</span>
                                                    <?php endif;?>
                                                </td>
                                                <td style="border-top:1px #DDDDDD solid" width="90">
                                                    <?= $currency ?><?= $details['freebies']?"0":$details['payment_price'] / 100 ?>
                                                </td>
                                                <td style="border-top:1px #DDDDDD solid" width="90">
                                                    <?= $details['product_quantity'] ?>
                                                </td>
                                            </tr>
                                            <?php endforeach ?>

                                        </table>
                                    </div>
                                </td>
                            </tr>
                            <tr height="20"></tr>
                            <tr>
                                <td>
                                    <table width="100%" border="0" cellpadding="0" cellspacing="0" align="center" style="font-size: 18px;margin: 0 auto;">
                                        <tr>
                                            <td style="color:#00B6C6" width="50%">
                                                Shipping Address
                                            </td>
                                            <td style="color:#00B6C6;float: right;text-align: left;">
                                                Order Summary&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                            </td>
                                        </tr>
                                        <tr height="20"></tr>
                                        <tr>
                                            <td>
                                                <?= $insert_shipData['receive_firstName'] ?> <?= $insert_shipData['receive_lastName'] ?><br>
                                                <?= $insert_shipData['receive_add2'] ?>  <?= $insert_shipData['receive_add1'] ?><br>
                                                <?= $insert_shipData['receive_province'] ?>  <?= $insert_shipData['receive_city'] ?>  <?= $insert_shipData['receive_zipcode'] ?><br>
                                                <?= $insert_shipData['receive_country'] ?><br>
                                            </td>
                                            <td style="float: right">
                                                <table width="100%" border="0" cellpadding="0" cellspacing="0" align="center" style="margin: 0 auto;">
                                                    <tr>
                                                        <td style="float: right">Subtotal : </td>
                                                        <td width="80">&nbsp;<?= $currency ?><?= round($insert_orderData['order_amount']/ 100,2) ?></td>
                                                    </tr>
                                                
                                                    <tr>
                                                        <td style="float: right">Shipping Fee : </td>
                                                        <td width="80">&nbsp;<?= $currency ?><?= $insert_orderData['freight_amount'] / 100 ?></td>
                                                    </tr>
                                                    
                                                    <?php if ($insert_orderData['order_insurance']): ?>
                                                    <tr>
                                                        <td style="float: right">Shipping Insurance : </td>
                                                        <td width="80">&nbsp;<?= $currency ?><?= $insert_orderData['order_insurance'] / 100 ?></td>
                                                    </tr>
                                                    <?php endif; ?>
                                                    
                                                    <?php if ($insert_orderData['order_giftbox']): ?>
                                                    <tr>
                                                        <td style="float: right">Shopping Bag : </td>
                                                        <td width="80">&nbsp;<?= $currency ?><?= $insert_orderData['order_giftbox'] / 100 ?></td>
                                                    </tr>
                                                    <?php endif; ?>
                                                    
                                                    <?php if ($insert_orderData['coupons_id']): ?>
                                                    <tr>
                                                        <td style="float: right">Coupon : </td>
                                                        <td width="80">&nbsp;-<?= $currency ?><?= round($insert_orderData['offers_amount'] / 100,2) ?></td>
                                                    </tr>
                                                    <?php endif; ?>
                                                    
                                                    <tr>
                                                        <td style="float: right"><strong>Total : </strong></td>
                                                        <td width="80"><strong>&nbsp;<?= $currency ?><?= round($insert_orderData['payment_amount'] / 100,2) ?></strong></td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                            <tr height="20"></tr>
                        </table>
                    </td>
                </tr>
                <tr height="20"></tr>

                <tr>
                    <td>
                       <table cellspacing="0" cellpadding="0" width="560" align="center" bgcolor="#ffffff" border="0" style="margin: 0 auto;">
                           <tr>
                            <td>
                                <div style="background-color: #F3F3F3;border: 1px #DDDDDD solid;border-radius: 5px;font-size: 18px;padding-bottom:15px;padding-top:5px;">
                                    <p align="center">You can manage and track your orders in your account center</p>
                                    <P align="center">
                                        <a href="<?php echo site_url('/personal/order');?>" style="text-decoration: none"><span style="background-color: #00B6C6;border: 1px #00B6C6 solid;border-radius: 5px;color: #fff;font-size: 16px;padding: 10px 30px;margin-top:10px;">View Order Details</span></a>
                                    </P>
                                    
                                </div>
                                </td>
                            </tr>
<!--
                            <tr>
                                <td align="center">
                                    <p  style="font-size: 20px;margin-bottom: 0">Save <?php echo $currency ?>2 on your next purchase</p>
                                    Use promo code <span style="color: #00B6C6;"><?php echo $couponsInfo['coupons_id'] ?></span> in your cart when you order.
                                </td>
                            </tr>
-->
                            <tr height="20"></tr>
                            <tr>
                                <td style="font-size: 16px;">
                                    <p>Dear <?php echo $name ?>,</p>
                                    
                                    <p>Thank you for your order placed on <?php $dateTimeUTC = new DateTime('@'.$time, new DateTimeZone("UTC")); echo $dateTimeUTC->format('M d, Y').' at '.$dateTimeUTC->format('H:i'); ?> UTC. </p>
                                    
                                    <p>Your order will be dispatched after the end of the deal. I hope you would be happy with the product. </p>
                                    
                                    <p>However, if there are any issues, please do not hesitate to contact us.</p>
                                </td>
                            </tr>
                            <tr height="10"></tr>
                            <tr>
                                <td>
                                    <img src="<?php echo STATIC_HTTP_DOMAIN; ?>/template_pc/en/image/email/service_<?= mt_rand(1,4)?>.gif">
                                </td>
                            </tr>
                            <tr height="10"></tr>
                       </table> 
                    </td>
                </tr>

                <tr>
                    <td>
                        <table  cellspacing="0" cellpadding="0" width="560" align="center" bgcolor="#ffffff" border="0" style="margin: 0 auto;">
                            <tr>
                                <td width="130">
                                    <hr style="margin:0px;height:2px;border:0px;background-color:#D5D5D5;color:#D5D5D5;"/>
                                </td>
                                <td width="300">
                                    <p align="center" style="font-size: 16px;">You May Also Like These Best Sellers</p>
                                </td>
                                <td width="130">
                                    <hr style="margin:0px;height:2px;border:0px;background-color:#D5D5D5;color:#D5D5D5;padding-right: 5px"/>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>

                <tr height="10"></tr>

                <?php if(!empty($goodproduct)):?>
                <tr>
                    <td>
                        <table  cellspacing="0" cellpadding="0" width="560" align="center" bgcolor="#ffffff" border="0" style="margin: 0 auto;">
                            <tr>
                                <?php foreach($goodproduct as $key=>$value):?>
                                <td>
                                   <a href="<?php echo site_url('collections/'.$value['collection'].'/products/'.$value['seo_url']);?>"><img style="border: 1px #ddd solid;border-radius: 5px;padding: 5px" width="90" src="<?php echo STATIC_HTTP_DOMAIN.$value['image'];?>"></a>
                                </td>
                                <?php endforeach;?>
                            </tr>
                        </table>
                    </td>
                </tr>
                <?php endif;?>

                <tr height="20"></tr>

                <tr>
                    <td>
                        <table cellspacing="0" cellpadding="0" width="560" align="center" bgcolor="#ffffff" border="0">
                            <tr>
                                <td>
                                    <img src="<?= STATIC_HTTP_DOMAIN ?>/template_pc/en/image/email/ser.png" width="100%">
                                </td>
                            </tr>
                            <tr height="20"></tr>
                            <tr>
                                <td align="center" width="600" style="color:#999;">
                                    To keep receiving emails from us,<br />please add <span style="color:#336699;"><?= $shopmail?></span> to your address book
                                </td>
                            </tr>
                            <tr height="20"></tr>
                        </table>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
</body>
</html>

