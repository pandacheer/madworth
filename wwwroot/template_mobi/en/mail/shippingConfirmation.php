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
                                <td>
                                    <img src="<?php echo STATIC_HTTP_DOMAIN; ?>/template_pc/en/image/email/template.png">
                                </td>
                                <td>
                                    <p style="color: #00B6C6;font-size: 20px;font-weight: 600;margin:0;padding: 0">
                                    	<?php if($order_send['is_resend'] > 1) :?>
                                    		Your reshipment has now been shipped.
                                    	<?php else: ?>
                                    		Your Items have now been shipped.
                                    	<?php endif; ?>	
                                    </p>
                                    <?php
                                    /*
                                    	<p style="color: #00B6C6;font-size: 18px;font-weight: 600;margin:0;padding: 0">Estimated Time of Arrival: <span style="color: #FF5062"><?php echo date('M, d Y', strtotime('+' . $estimatedTime["estimated_time"] . 'day', $estimatedTime['create_time'])) ?></span></p>
                                	*/
                                	?>
                                </td>
                            </tr>
                            <tr height="20"></tr>
                            <tr>
                                <td colspan="2">
                                <?php 
                                   switch ($order_send['country']) {
                                    case 'AU' :
                                        echo '<img src="'.STATIC_HTTP_DOMAIN.'/template_pc/en/image/email/flag/au.gif" width="560">';
                                        break;
                                    case 'NZ' :
                                        echo '<img src="'.STATIC_HTTP_DOMAIN.'/template_pc/en/image/email/flag/nz.gif" width="560">';
                                        break;
                                    case 'US' :
                                        echo '<img src="'.STATIC_HTTP_DOMAIN.'/template_pc/en/image/email/flag/us.gif" width="560">';
                                        break;
                                    case 'CA' :
                                        echo '<img src="'.STATIC_HTTP_DOMAIN.'/template_pc/en/image/email/flag/ca.gif" width="560">';
                                        break;
                                    case 'GB' :
                                        echo '<img src="'.STATIC_HTTP_DOMAIN.'/template_pc/en/image/email/flag/uk.gif" width="560">';
                                        break;
                                    case 'MY' :
                                        echo '<img src="'.STATIC_HTTP_DOMAIN.'/template_pc/en/image/email/flag/my.gif" width="560">';
                                        break;
                                    case 'IE' :
                                        echo '<img src="'.STATIC_HTTP_DOMAIN.'/template_pc/en/image/email/flag/ie.gif" width="560">';
                                        break;
                                    case 'SG' :
                                        echo '<img src="'.STATIC_HTTP_DOMAIN.'/template_pc/en/image/email/flag/sg.gif" width="560">';
                                        break;
                                    default :
                                        echo '<img src="'.STATIC_HTTP_DOMAIN.'/template_pc/en/image/email/flag/us.gif">';
                                        break;
                                   }
                                ?>
                                </td>
                            </tr>
                            <tr height="20"></tr>
                        </table>
                        <table cellspacing="0" cellpadding="0" width="560" align="center" bgcolor="#ffffff" border="0" style="margin: 0 auto;">
                            <tr>
                                <td style="font-size:16px;">
                                    <p>Dear <?=$member['member_name'] ? $member['member_name'] : "Member"?>,</p>
                                    
                                    <p>Thank your for ordering from DrGrab!</p>
                                    <p>
                                    	<?php if($order_send['product_sku']) :?>
                                    		The following items in your order 
                                    	<?php else: ?>
                                    		Your items from order
                                    	<?php endif; ?>
                                    	<span style="color: #00B6C6">#<?= $order_send['country']?><?= $order_send['order_number']?></span> have now been shipped:</p>
                                    <ul>
                                     <?php foreach ($pro_details as $details): ?>
                                        <li><?= $details['product_quantity']?>x <?= $details['product_name']?></li>
                                     <?php endforeach ?>
                                    </ul>
                                    
                                    
                                    They are being shipped to the following address:<br><br>
                                    <div style="color: gray">
                                        <?= $order_ship['receive_firstName']?><?= $order_ship['receive_lastName']?><br>
                                        <?= $order_ship['receive_add2'] ?>  <?= $order_ship['receive_add1'] ?><br>
                                        <?= $order_ship['receive_province'] ?>  <?= $order_ship['receive_city'] ?>  <?= $order_ship['receive_zipcode'] ?><br>
                                        <?= $order_ship['receive_country'] ?><br>
                                    </div>
                                    <br>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                <?php if(!$order_send['track_name']) :?>
                                	<div style="background-color: #F3F3F3;border: 1px #DDDDDD solid;border-radius: 5px;font-size: 18px;padding-bottom:25px;padding-top:15px;">
                                    <p align="center">The Tracking Number is still being generated, and we will send you an email as soon as it is available</p>
                                    
                                    <P align="center">
                                        <a href="<?php echo site_url('/personal/order');?>" style="text-decoration: none"><span style="background-color: #00B6C6;border: 1px #00B6C6 solid;border-radius: 5px;color: #fff;font-size: 16px;padding: 10px 30px;">My Order Detail</span></a>
                                    </P>
                                </div>
                                <?php elseif ($order_send['track_name']=="untraceable"): ?>
                                    <p>The reference number is <?= $order_send['track_code'] ?>. This number is merely a serial number, which cannot be used to track your package. </p>
                                <?php else: ?>
                                <div style="background-color: #F3F3F3;border: 1px #DDDDDD solid;border-radius: 5px;font-size: 18px;padding-bottom:25px;padding-top:15px;">
                                    <p align="center">The tracking number for these items is <span style="color: #00B6C6"><?= $order_send['track_code'] ?></span></p>
                                    
                                    <P align="center">
                                        <a href="<?= $order_send['track_url'] ?>" style="text-decoration: none"><span style="background-color: #00B6C6;border: 1px #00B6C6 solid;border-radius: 5px;color: #fff;font-size: 16px;padding: 10px 30px;">Track My Order</span></a>
                                    </P>
                                    
                                </div>
                                <?php endif; ?>
                                </td>
                            </tr>
                            <tr height="20"></tr>
                            <tr>
                                <td>
                                    <img src="<?php echo STATIC_HTTP_DOMAIN; ?>/template_pc/en/image/email/service_<?= mt_rand(1,4)?>.gif">
                                </td>
                            </tr>
                            <tr height="10"></tr>
                        </table>
                        <table cellspacing="0" cellpadding="0" width="560" align="center" bgcolor="#ffffff" border="0" style="margin: 0 auto;">
                            <tr>
                                <td width="130">
                                    <hr style="margin:0px;height:2px;border:0px;background-color:#D5D5D5;color:#D5D5D5;"/>
                                </td>
                                <td width="300" style="height: 40px">
                                    <p align="center" style="font-size: 16px;">You May Also Like The Best Sellers</p>
                                </td>
                                <td width="130">
                                    <hr style="margin:0px;height:2px;border:0px;background-color:#D5D5D5;color:#D5D5D5;padding-right: 5px"/>
                                </td>
                            </tr>
                            <tr height="10"></tr>
                        </table>
                        <?php if(!empty($goodproduct)):?>
                        <table cellspacing="0" cellpadding="0" width="560" align="center" bgcolor="#ffffff" border="0" style="margin: 0 auto;">
                            <tr>
                                <?php foreach($goodproduct as $key=>$value):?>
                                <td>
                                    <a href="<?php echo site_url('collections/'.$value['collection'].'/products/'.$value['seo_url']);?>"><img style="border: 1px #ddd solid;border-radius: 5px;padding: 5px" width="90" src="<?php echo STATIC_HTTP_DOMAIN.$value['image'];?>"></a>
                                </td>
                                    <?php endforeach;?>
                            </tr>
                        </table>
                        <?php endif;?>
                    </td>
                </tr>
                <tr height="20"></tr> 
                <tr>
                    <td>
                        <table cellspacing="0" cellpadding="0" width="560" align="center" bgcolor="#ffffff" border="0" style="margin: 0 auto;">
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

