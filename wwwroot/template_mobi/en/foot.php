<div class="dg-footer">
    <div class="dg-main-top-image">
        <img src="<?php echo $cdn ?>img/services.png" class="dg-footer-image-service"  alt="Planets" usemap="#planetmap">
        <map name="planetmap">
            <area alt="" title="" href="<?php echo $domain ?>/pages/return-policy" shape="rect" coords="0,3,236,59" onfocus="blur(this);"/>
            <area alt="" title="" href="<?php echo $domain ?>/pages/shipping-guide" shape="rect" coords="242,0,450,59" onfocus="blur(this);"/>
            <area alt="" title="" href="<?php echo $domain ?>/pages/Shop-with-Confidence" shape="rect" coords="454,0,686,59" onfocus="blur(this);"/>
            <area alt="" title="" href="<?php echo $domain ?>/pages/Shop-with-Confidence" shape="rect" coords="690,0,880,58" onfocus="blur(this);"/>
        </map>
    </div>
    <div class="dg-footer-newsletter">
        <h3>Newsletter</h3>
        <div class="dg-footer-newsletter-description">Subscribe Our Newsletter to receive latest news.</div>
        <table border="0" cellspacing="0" cellpadding="0"><tr>
                <td width="75%"><input type="text" id="subscription-input-foot"  placeholder="Email Address">
                </td>
                <td width="5%"></td>
                <td width="20%"><button data-theme="b" id="subscription-button-foot">Subscribe</button></td>
            </tr></table>
    </div>
    <div class="dg-footer-link">
        <!-- <div class="ui-grid-a">
            <div class="ui-block-a">
                <h4>About DrGrab</h4>
                <ul  data-theme="none" class="dg-footer-link-list">
                    <li><a href="#">About Us</a></li>
                    <li><a href="#">My Account</a></li>
                    <li><a href="#">Order Status</a></li>
                    <li><a href="#">Shop By Brand</a></li>
                    <li><a href="#">Trade-in Program</a></li>
                </ul>
            </div>
            <div class="ui-block-b">
                <h4>More</h4>
                <ul data-theme="none" class="dg-footer-link-list">
                    <li><a href="#">FAQ</a></li>
                    <li><a href="#">Be a Supplier</a></li>
                    <li><a href="#">Order Status</a></li>
                </ul>
            </div>
        </div> -->
        <div class="ui-grid-a">
            <div class="ui-block-a">
                <h4>About DrGrab</h4>
                <ul  data-theme="none" class="dg-footer-link-list">
                    <li><a href="<?php echo $domain ?>/pages/about-us">About Us</a></li>
                    <li><a href="<?php echo $domain ?>/pages/contact-us">Contact Us</a></li>
                    <li><a href="<?php echo $domain ?>/pages/Why-choose-us">Why choose Us</a></li>
                    <li><a href="<?php echo $domain ?>/pages/faq">FAQ</a></li>
                </ul>
            </div>
            <div class="ui-block-b">
                <h4>More</h4>
                <ul data-theme="none" class="dg-footer-link-list">
                    <li><a href="<?php echo $domain ?>/pages/Shop-with-Confidence">Shop with Confidence</a></li>
                    <li><a href="<?php echo $domain ?>/pages/shipping-guide">Shipping Guide</a></li>
                    <li><a href="<?php echo $domain ?>/pages/return-policy">Return Policy</a></li>
                    <li><a href="<?php echo $domain ?>/pages/privacy-policy">Privacy Policy</a></li>                             
                </ul>
            </div>
        </div>
    </div>
    <div class="dg-footer-image">
        <img src="<?php echo $cdn ?>img/icons@2x.png" class="dg-footer-image-icons" usemap="#Map">
        <map name="Map" id="Map">
            <area alt="" title="" href="http://www.paypal.com" shape="rect" coords="0,1,403,158" onfocus="blur(this);"/>
            <area alt="" title="" href="http://www.mastercard.com" shape="rect" coords="444,2,682,157" onfocus="blur(this);"/>
            <area alt="" title="" href="http://www.visa.com" shape="rect" coords="738,0,933,159" onfocus="blur(this);"/>
            <area alt="" title="" href="https://www.digicert.com" shape="rect" coords="1028,0,1347,159" onfocus="blur(this);"/>
            <area alt="" title="" href="http://aws.amazon.com" shape="rect" coords="1422,1,1779,154" onfocus="blur(this);"/>
        </map>
        <div class="dg-footer-image-cc">© <?php echo date('Y'); ?> Copyright by DrGrab. All Rights Reserved. </div>
    </div>
</div>

<script>
var szimg='<img src="<?php echo $cdn ?>img/grab.gif" width="64">';

    cartpronumshow();
    $(document).ready(function (e) {
        $('img[usemap]').rwdImageMaps();
    });

$('#switch').on('click',function(){
    $('#country_switch').slideDown();
    $('#left_content').slideUp();
})
$('#lvjinxiu').on('click',function(){
    $('#country_switch').slideUp();
    $('#left_content').slideDown();
})

//header-notice
/*var finalDatelv = $('#down').data('countdownlv');
var ldaylv = ($('#down').data('countdownlv')-Date.parse(new Date()))/1000/60/60/24;
var hourslv = parseInt(($('#down').data('countdownlv')-Date.parse(new Date()))/1000/60/60);
$('#down').countdown(finalDatelv, function(event) {
    if (ldaylv > 2) {
        //clearInterval(tinterval);
        $('#down').html(event.strftime('%D days %H:%M:%S'));
    }else if(1 < ldaylv && ldaylv <= 2 ) {
        if (hourslv < 1) {
            hourslv ='00'
        };
        $('#down').html(event.strftime(hourslv+':%M:%S'));
    }else{
        $('#down').html(event.strftime('%H:%M:%S'));
        
    };
});*/

//search 
    $('#search_but').on('click', function () {
        window.location.href = "/search/" + $('#search_val').val();
    });
//enter search
    $('#search_val').keypress(function (e) {
        var keyCode = e.keyCode ? e.keyCode : e.which ? e.which : e.charCode;
        if (keyCode == 13) {
            $('#search_but').trigger('click');
        }
        ;
    });
    $('[role="main"]').addClass("dg-cate-on");
    var nav_toggle = true;
    $('#dg-header-categorybtn').on('click', function (e) {
        e.preventDefault();
        if (nav_toggle) {
            $('.dg-footer').hide();
            $('[role="main"]').slideToggle("slow", function () {
                $('.dg-main-navslider').slideToggle();
                $('#category').hide();
                $('#back').show();
                $('.dg-productcon-footer').hide();
                nav_toggle = false;
            });
        }
        else {
            $('.dg-main-navslider').slideToggle("slow", function () {
                $('[role="main"]').slideToggle("slow", function () {
                    $('.dg-footer').show();
                });
                $('#category').show();
                $('#back').hide();
                $('.dg-productcon-footer').show();
                nav_toggle = true;
            });
        }
    })



    $("#subscription-button-foot").click(function () {

        function button_disabled_foot(e) {
            $(e).attr("disabled", true);
            $(e).text('Subscribing');
        }
        function button_enabled(e) {
            $(e).attr("disabled", false);
            $(e).text('Subscribe');
        }

        button_disabled_foot(this);
        if ($('#subscription-input-foot').val() === '') {
            $.notifyBar({cssClass: "dg-notify-error", html: "Please enter a valid email address", position: "bottom"});
            button_enabled(this);
        } else {
            if (!(/^[\w!#$%&'*+/=?^_`{|}~-]+(?:\.[\w!#$%&'*+/=?^_`{|}~-]+)*@(?:[\w](?:[\w-]*[\w])?\.)+[\w](?:[\w-]*[\w])?$/i.test($('#subscription-input-foot').val()))) {
                $.notifyBar({cssClass: "dg-notify-error", html: "Please enter a valid email address", position: "bottom"});
                button_enabled(this);
            } else {
                $.post("/subscription/insert", {
                    email: $('#subscription-input-foot').val()
                }, function (result) {
                    var result = $.parseJSON(result);
                    if (result.status) {
                        $.notifyBar({cssClass: "dg-notify-success", html: "Thank you! You have successfully subscribed to our newsletter.", position: "bottom"});
                        button_enabled("#subscription-button-foot");
                        $('#subscription-input-foot').reset();
                    } else {
                        $.notifyBar({cssClass: "dg-notify-error", html: "You are already subscribed to our newsletter.", position: "bottom"});
                        button_enabled("#subscription-button-foot");
                    }
                })
//                        .fail(function (xhr, errorText, errorType) {
//                    $.notifyBar({cssClass: "dg-notify-error", html: "Failed to process your request, please try again.", position: "bottom"});
//                });

            }
        }
    });

</script>
<script>
    $(function () {
        var slider_title = $('.dg-main-navslider .dg-main-navslider-list-title');
        var slider_content = null, parents = null, index = null;


        slider_title.each(function (i) {
            $(this).click(function () {


                parents = $(this).parents('.dg-main-navslider');
                slider_title = parents.find('.dg-main-navslider-list-title');
                slider_content = parents.find('div.dg-main-navslider-content');
                index = slider_title.index(this);

                if (slider_content.eq(index).is(':visible')) {

                    slider_content.eq(index).hide('fast');
                    $(this).children().last().removeClass('icon-arrow-u').addClass('icon-arrow-d');
                } else {

                    slider_content.eq(index).show('fast').end().not(':eq(' + index + ')').hide('fast');
                    $(this).parents().find('.icon-arrow-u').removeClass('icon-arrow-u').addClass('icon-arrow-d');
                    $(this).children().last().removeClass('icon-arrow-d').addClass('icon-arrow-u');

                }
            });
        });
    });
    
      (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', '<?php echo $google ?>', 'auto');
  ga('send', 'pageview');


</script>
  <!-- Facebook Pixel Code -->
<script>
!function(f,b,e,v,n,t,s){if(f.fbq)return;n=f.fbq=function(){n.callMethod?
n.callMethod.apply(n,arguments):n.queue.push(arguments)};if(!f._fbq)f._fbq=n;
n.push=n;n.loaded=!0;n.version='2.0';n.queue=[];t=b.createElement(e);t.async=!0;
t.src=v;s=b.getElementsByTagName(e)[0];s.parentNode.insertBefore(t,s)}(window,
document,'script','//connect.facebook.net/en_US/fbevents.js');

fbq('init', '<?php echo $facebook_id ?>');
fbq('track', "PageView");</script>
<noscript><img height="1" width="1" style="display:none"
src="https://www.facebook.com/tr?id=<?php echo $facebook_id ?>&ev=PageView&noscript=1"
/></noscript>
<!-- End Facebook Pixel Code -->