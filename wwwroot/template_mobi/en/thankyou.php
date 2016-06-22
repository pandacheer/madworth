<?php echo $head; ?>
<div role="main" class="ui-content">
    <div class="dg-pagetitle">Thank You</div>
    <div class="dg-main-empty">
        <div class="dg-main-empty-icon">
            <span class="icon-right dg-main-empty-icon-success"></span>
        </div>
        <br>
        <div class="dg-main-empty-msg">
            <span>Thank you for your purchase!</span>
        </div>

        <div class="dg-main-empty-detail">
            <?= $this->session->userdata('is_newUser') ? 'A confirmation email and account activation email have been sent to ' . $successMessage : 'A confirmation email has been sent to ' . $successMessage ?>
        </div>
        <div class="dg-main-empty-detail">
            Esitmated Time of Arrival: <?php echo date('M, d Y', strtotime('+' . $orders["estimated_time"] . 'day', $orders['create_time'])) ?>
        </div>

        <div class="dg-main-empty-msg">
            <?php if (!isset($_SESSION['is_newUser'])): ?>
                <a href="/personal/order"><button data-theme="c">View Order Details</button></a>
            <?php endif; ?>
            <a href="/"><button data-theme="c">Continue Shopping</button></a>
        </div>
    </div>
    <h4 style="text-align: center">You May Also Like</h4> 
    <?php if (!empty($product)): ?>
        <div class="ui-grid-a">
            <?php foreach ($product as $k => $v): ?>
                <div class="ui-block-a" style="padding: 5px;border: 1px #ddd solid;width:48%;border-radius: 5px;float: right;margin: 0 3px;background-color: white;">
                    <a href="<?php echo site_url('collections/' . $v['collection'] . '/products/' . $v['seo_url']); ?>"><img alt="<?php echo $v['title']; ?>" src="<?php echo IMAGE_DOMAIN . $v['image']; ?>" style="width: 100%"></a>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

</div>
<?php echo $foot; ?>
</div>


<script>

    fbq('track', 'Purchase', {value: '<?= $orders["payment_amount"] / $au_rate / 100 ?>', currency: 'AUD'});

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
<script type="text/javascript" src="//www.googleadservices.com/pagead/conversion.js"></script>
<noscript>
<div style="display:inline;">
    <img height="1" width="1" style="border-style:none;" alt=""  src="//www.googleadservices.com/pagead/conversion/959384788/?value=1.00&amp;currency_code=USD&amp;label=_TmCCOyd7GIQ1Jm8yQM&amp;guid=ON&amp;script=0"/>
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