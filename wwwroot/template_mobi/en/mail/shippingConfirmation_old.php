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
								<p>Dear <?=$member['member_name'] ? $member['member_name'] : "Member"?>,</p>
								<p>Thank you for being a graber!</p>
								<p>
									Estimated Time of Arrival:
								    <?php echo date('M, d Y', strtotime('+' . $estimatedTime["estimated_time"] . 'day', $estimatedTime['create_time'])) ?>
								</p>
								<p>You have successfully grabbed all of the items from order #<?= $order_send['country']?><?= $order_send['order_number']?> have now been shipped:</p>
	                            <?php foreach ($pro_details as $details): ?>
	                            	<p><?= $details['product_quantity']?>x <?= $details['product_name']?></p>
	                            <?php endforeach ?>
	                            <p>They are being shipped to the following address:</p>
	                            <p><?= $order_ship['receive_firstName']?><?= $order_ship['receive_lastName']?></p>
	                            <p><?= $order_ship['receive_add2'] ?>  <?= $order_ship['receive_add1'] ?></p>
	                            <p><?= $order_ship['receive_province'] ?>  <?= $order_ship['receive_city'] ?>  <?= $order_ship['receive_zipcode'] ?></p>
	                            <p><?= $order_ship['receive_country'] ?></p>
	                            
	                            <?php if ($order_send['track_name']=="untraceable"): ?>
	                            	<p>The reference number is <?= $order_send['track_code'] ?>. This number is merely a serial number, which cannot be used to track your package. </p>
	                            <?php else: ?>
	                            	<p>The tracking number for these items is <?= $order_send['track_code'] ?>.</p>
	                            	<p><a href="<?= $order_send['track_url'] ?>" target="_blank">Click here to see the status of your shipment.</a></p>
	                            <?php endif; ?>
	                                          
	                            <p>Please note the Estimated Time:</p>
	                            <p>Express delivery: 7-12 working days</p>
	                            <p>Free delivery: 3-4 weeks</p>
	                            <p>Please note that the delivery time does not include the dispatch time and transit time.</p>
	                            <p>You will receive a confirmation email once rest of the items from your order will have been shipped by <?= $order_send['country']?>.</p>
	                            <p>If you have any further questions, please do not hesitate to contact our Customer Support Team and we will get back to you within 24 hours.</p>
	                            <p>Thank you for ordering from DrGrab!</p>
	                            <p>Keep grabbing and be happy! </p>
	                            <p>Best Regards,</p>
	                            <p>Team DrGrab</p>
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