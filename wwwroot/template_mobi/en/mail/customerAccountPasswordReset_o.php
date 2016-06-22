<div bgcolor="#fff" style="width:100%;margin:0 auto; font-family:Helvetica,Arial,sans-serif;word-break: break-word;">
    <div style="margin:0 auto;width:640px;border:1px solid #baebf2;">
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
                                <a href='<?= $shopurl ?>/'><img src="http://static.catchoftheworld.com:1234/template_pc/en/image/email/email_logo.png" alt=""></a>
                            </td>
                            <td width="45" valign="top">
                                <a href='https://www.facebook.com/drgrab'><img src="http://static.catchoftheworld.com:1234/template_pc/en/image/email/email_facebook.png"></a>
                            </td>
                            <td width="40" valign="top">
                                <a href='https://twitter.com/Drgrab'><img src="http://static.catchoftheworld.com:1234/template_pc/en/image/email/email_twitter.png"></a>
                            </td>
                        </tr>
                    </table>
                    <table width="100%" border="0" cellpadding="0" cellspacing="0">
                        <tr height="20"></tr>
                        <tr>
                            <td style="font-size: 16px;">
                                <p>Dear <?=  $name ? $name : "Member"?>,</p>
                                <p>Thank you for being a Grabber!</p>
                                <p>We received a request to reset the password associated with this e-mail address.</p>
                                <p>If you made this request, please click the link below to reset your password using our secure server:</p>
                                <p style="word-break:break-all"><a href="<?= $reseturl ?>" style="word-break: break-word;"><?= $reseturl ?></a></p>
                                <p>If you did not request to have your password reset you can safely ignore this email.</p>
                                <p>If clicking the link doesn't seem to work, you can copy and paste the link into your browser's address window, or retype it there. Once you have returned to DrGrab.com,</p>
                                <p>we will give instructions for resetting your password.</p>
                                <p><img src="<?php echo STATIC_HTTP_DOMAIN; ?>/template_pc/en/image/email/service_<?= mt_rand(1,4)?>.gif"><br>

                            </td>
                        </tr>
                        <tr height="40"></tr>
                        <tr>
                            <td><img src="http://static.catchoftheworld.com:1234/template_pc/en/image/email/ser.png" alt="" width="600"></td>
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