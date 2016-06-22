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
                                <td style="font-size: 16px;">
                                    <p>Dear <?=  $name ? $name : "Member"?>,</p>
                                    
                                    <p>Thank you for being a graber!</p>
                                   
                                    <p>We hope, by now you must have received your order. In order to improve our customer service, we would appreciate if you could share your reviews with us.</p>
                                    
                                    <p>At DrGrab we value the importance of your time. If you post your positive review via Facebook, we would honour you with the "Reviewer Prize!" And if you could post your review with photos of the product, we would double your prize! You need to share screenshot of your Fcebook page to our customer service team, and once confirmed, we will issue the coupon to you. (edition for now)</p>
                                    <p>/</p>
                                    
                                    <p>At DrGrab we value the importance of your time. If you post your positive review on respective product page on DrGrab.com, the automated system will issue you "5% Off Coupon!".</p>
                                    
                                    <p>And if you could share your review with photos on your Facebook account, you could get the "15% Off Coupon!". You need to share screenshot of your Fcebook page to our customer service team, and once confirmed, we will issue the coupon to you. (edition for future)</p>
                                   
                                    <p>Please do not hesitate to contact us if you have any further questions.</p>
                                    
                                    <p><img src="<?php echo STATIC_HTTP_DOMAIN; ?>/template_pc/en/image/email/service_<?= mt_rand(1,4)?>.gif"><br></p>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr> 
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

