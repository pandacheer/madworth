<?php echo $head; ?>

<div class="dg-main">
    <div class="container">
        <div class="row">
            <div class="col-xs-10 col-xs-12">
                <div class="dg-main-thankyou">
                    <div class="dg-main-thankyou-ticker">
                        <i class="fa fa-check-circle fa-lg"></i>
                        <div class="dg-main-thankyou-ticker-thanktitle">Thank you for your purchase!</div>
                        <div class="dg-main-thankyou-ticker-thankdesc"><?= $this->session->userdata('is_newUser') ? 'A confirmation email and account activation email have been sent to ' . $successMessage : 'A confirmation email has been sent to ' . $successMessage ?></div>
                        <div class="dg-main-thankyou-ticker-order"></div>


                        <div class="dg-main-thankyou-ticker-estimated">
                            ( Estimated Time of Arrival : <?php echo date('M, d Y', strtotime('+' . $orders["estimated_time"] . 'day', $orders['create_time'])) ?> )
                        </div>

                        <div class="dg-main-thankyou-ticker-button">
                            <?php if ($this->session->userdata('member_email')): ?>
                                <a href="/personal/order"><button type="button" class="btn btn-default btn-lg">View Order Details</button></a>
                            <?php endif; ?>
                            <a href="/"><button type="button" class="btn btn-default btn-lg">Continue Shopping</button></a>
                        </div>
                    </div>
                    <div class="dg-main-product-core-panel-share" align="center">
                        <!-- facebookShare -->
                        <meta property="og:image" content="<?php echo $share['image']; ?>"/>
                        <!-- facebookShare / -->
                        <!-- Go to www.addthis.com/dashboard to generate a new set of sharing buttons -->
                        <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo $share['url']; ?>&t=<?php echo $share['title'] ? urlencode($share['title'] . ' | DrGrab') : ''; ?>" target="_blank">
                            <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAFAAAABQCAYAAACOEfKtAAAABHNCSVQICAgIfAhkiAAAAAlwSFlzAAALEgAACxIB0t1+/AAAABZ0RVh0Q3JlYXRpb24gVGltZQAxMS8xMS8xNZitZ1EAAAAcdEVYdFNvZnR3YXJlAEFkb2JlIEZpcmV3b3JrcyBDUzbovLKMAAADkUlEQVR4nO2dP0gbURzHP4o4iKJQh9rFOLTQLmp1KcF2LhSMdQuUqsPRRczejnZPoUPJoMlyW2sEoXMpN7amS4W2YFzqUAoKVqhLO9xFo8nlznuad+/8fUbzcvflw/tz9yS/10FE0tnCGJABxoAUMBr1Wpr4AlSBClB2bKsS5SId52mczhZSQA5X3HCUG8aYHaAM5B3bqob9UiiB6WxhAMgDTyNFM48SkHNsay+oYaDAdLaQAYpAv3ouo9gH5hzbKrdq1FJgOlsocnV6nR8lx7bm/D5sKtAbskVg+nIyGcc6bm9sGNKdPl8oIvLqmcZ10kCDQG/YirxGpj03pzg1hL0FY61diQxlpn5hORbozXtVrt5qe172gVRtPqwfwnlEXhj6cV0BXg/03jC2NQUylRHHtqq1HpjTGsVMcnAyhDMag5hKBqDD21XZ1BzGVMa7iHHvm5pMcX9ymKHBPsZuDwW2X3n3mZW3n9qQ7JhMF+5+XqyYmkyx9OQe1wd7dUcJYqwLdzM0Njx/9oCHU7d0xwhLqpMY7SQvzE6YJA9g1G8zoe3cHL7GwuO7umOcm9gIXJid0B0hErEQ2NvTzdSEmf9iiYXA8Ts3dEeITJfuAODOf2E5ODxi8+tPvu/8bvhsc2v3ImOFwjiBi8sbTeXpIhZDuK+nO1S7ytZurORBTASajAhURAQqIgIV6UhnC//aecPXLx6F2pqKwvuP33j55sOlXNuPRPXA3V8Hbb9nogTqIFECdTwjJkrgwZ+/bb9nogTqIFECdWwmJEqgDhIj8ODwSMt9EyPwh6Zdmra/iTQj7NvJ4vKGlnmuFYnpgboQgYqIQEVEoCIiUBERqIgIVEQEKiICFRGBiohARUSgIiJQERGoiAhURAQqIgIVEYGKiEBFRKAiIlCRTtzqZUI0Kp24VcuEaOxID1SjIj/5V2O8VvZkm5j98NoAqo5tjdRW4XWtUcxkHU4eY/ItGgrNyYMn0KsZWtKZxjBKtTqr9Q/SOSCwZqjAHnWVno4FetXI5nUkMoz5+kqWp17lvLp4xXYnMoji2aK0fjVU14hxRSNNlB3bmjn7R7/NhHncotSCSxmf6S2oDPIqMHcJgUyi6NiW79oQthD3KjBwkakMYA93wWg5EgP3A70LjHC1FpciboXKwGksymEES7gLTCpCsDhTxZ3rXl34YQTN8HZxpjk5DiN2ZfQCqHByHMZ61OMw/gMFadQS1rEv4AAAAABJRU5ErkJggg==" border="0" alt="Facebook"/>
                        </a>
                        <a href="https://twitter.com/intent/tweet?text=<?php echo $share['title'] ? urlencode($share['title'] . ' | DrGrab') : ''; ?>&url=<?php echo $share['url']; ?>&related=" target="_blank">
                            <img src="data:image/png;base64,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" border="0" alt="Twitter"/>
                        </a>
                        <a href="https://www.pinterest.com/pin/create/button/?url=<?php echo $share['url']; ?>&media=<?php echo $share['image']; ?>&description=<?php echo $share['desc']; ?>" target="_blank">
                            <img src="data:image/png;base64,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" border="0" alt="Pinterest"/>
                        </a>
                        <a href="https://plus.google.com/share?url=<?php echo $share['url']; ?>&t=<?php echo $share['title'] ? urlencode($share['title'] . ' | DrGrab') : ''; ?>" target="_blank">
                            <img src="data:image/png;base64,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" border="0" alt="Google+"/>
                        </a>
                    </div>
                    <?php if (!empty($product)): ?>
                        <table class="dg-main-recommended" align="center">
                            <tr>
                                <td ><hr style="margin:0px;height:2px;border:0px;background-color:#D5D5D5;color:#D5D5D5;width: 250px"/></td>
                                <td colspan="3"><h4>You May Also Like These Best Sellers</h4></td>
                                <td ><hr style="margin:0px;height:2px;border:0px;background-color:#D5D5D5;color:#D5D5D5;width: 250px"/></td>
                            </tr>
                        </table>
                        <table class="dg-main-recommended">
                            <!-- <tr>
                              <td colspan="2" ><hr style="margin:0px;height:2px;border:0px;background-color:#D5D5D5;color:#D5D5D5;"/></td>
                              <td><h4>You May Also Like These Best Sellers</h4></td>
                              <td colspan="2" ><hr style="margin:0px;height:2px;border:0px;background-color:#D5D5D5;color:#D5D5D5;"/></td>
                            </tr> -->
                            <tr>
                                <?php foreach ($product as $k => $v): ?>
                                    <td><a href="<?php echo site_url('collections/' . $v['collection'] . '/products/' . $v['seo_url']); ?>"><img alt="<?php echo $v['title']; ?>" src="<?php echo IMAGE_DOMAIN . $v['image']; ?>" ></a></td>
                                <?php endforeach; ?>
                            </tr>
                        </table>
                    <?php endif; ?>
                </div>
            </div>

            <?php $this->session->unset_userdata('is_newUser'); ?>
            <?php echo $shoppingcart ?>

        </div>
    </div>
</div>  

<?php echo $foot; ?>

<script>

    fbq('track', 'Purchase', {value: '<?= $orders["payment_amount"] / $au_rate / 100 ?>', currency: 'AUD'});

    $(function () {
        cartempty();
        $('.dg-navbar').affix({
            offset: {top: $('.dg-navbar').offset().top}
        });
    });

    $('.dropdown-menu input').click(function (e) {
        e.stopPropagation();
    });
</script>

<!-- Google Code for checkout-20160103 Conversion Page -->
<script type="text/javascript">
    /* <![CDATA[ */
    var google_conversion_id = 959384788;
    var google_conversion_language = "en";
    var google_conversion_format = "3";
    var google_conversion_color = "ffffff";
    var google_conversion_label = "_TmCCOyd7GIQ1Jm8yQM";
    var google_conversion_value = <?= $orders["payment_amount"] / 100 ?>;
    var google_conversion_currency = "<?= $currency_payment ?>";
    var google_remarketing_only = false;
    /* ]]> */

    ga('require', 'ecommerce');
    ga('ecommerce:addTransaction', {<?php echo $ga_addTransaction ?>});
    ga('ecommerce:addItem', <?php echo $ga_addItem ?>);
    ga('ecommerce:send');
</script>
<script type="text/javascript"  src="//www.googleadservices.com/pagead/conversion.js"></script>
<noscript>
<div style="display:inline;">
    <img height="1" width="1" style="border-style:none;" alt=""  
         src="//www.googleadservices.com/pagead/conversion/959384788/?value=1.00&amp;currency_code=USD&amp;label=_TmCCOyd7GIQ1Jm8yQM&amp;guid=ON&amp;script=0"/>
</div>
</noscript>
<?php if ($country == 'US'): ?>
    <script type='text/javascript'>
    !function (d, s) {
        var rc = d.location.protocol + "//go.referralcandy.com/purchase/hkikhb2z9k2wps6nnc98gzdjl.js";
        var js = d.createElement(s);
        js.src = rc;
        var fjs = d.getElementsByTagName(s)[0];
        fjs.parentNode.insertBefore(js, fjs);
    }(document, "script");
    </script>
<?php endif; ?>


<?php if ($country == 'AU') : ?>
    <script src="//platform.twitter.com/oct.js" type="text/javascript"></script>
    <script type="text/javascript">twttr.conversion.trackPid('l60bj', {tw_sale_amount: <?= $orders["payment_amount"] / 100 ?>, tw_order_quantity: 1});</script>
    <noscript>
    <img height="1" width="1" style="display:none;" alt="" src="https://analytics.twitter.com/i/adsct?txn_id=l60bj&p_id=Twitter&tw_sale_amount=<?= $orders["payment_amount"] / 100 ?>&tw_order_quantity=1" />
    <img height="1" width="1" style="display:none;" alt="" src="//t.co/i/adsct?txn_id=l60bj&p_id=Twitter&tw_sale_amount=<?= $orders["payment_amount"] / 100 ?>&tw_order_quantity=1" />
    </noscript>
<?php endif; ?>

<?php if ($country == 'US') : ?>
	<img height="1" width="1" style="display:none;" alt="" src="https://ct.pinterest.com/?tid=8QiLjVLNJz7&value=<?= $orders["payment_amount"] / 100 ?>&quantity=1"/>
<?php endif; ?>

<?php if (isset($countrySEO)) echo $countrySEO ?>

</body>
</html>