<div class="dg-footads">
    <!-- <div class="container">
         <div class="row">
             <img src="<?php echo $cdn ?>image/footer.png" alt="Planets" usemap="#map">
             <map name="map">
                   <area shape="rect" coords="1,13,243,72" alt="Sun" href="/" onfocus="blur(this);">
                   <area shape="rect" coords="332,11,539,73" alt="Sun" href="/" onfocus="blur(this);">
                   <area shape="rect" coords="628,12,857,73" alt="Sun" href="/" onfocus="blur(this);">
                   <area shape="rect" coords="939,10,1140,75" alt="Sun" href="/" onfocus="blur(this);">
                 </map>
         </div>
     </div> --> 
</div>  

<div class="dg-foot">
    <div class="container">
<?php echo $footLogosView ?>
        <div class="row dg-foot-sub">
            <div class="col-xs-3"></div>
            <div class="col-xs-6">
                <h1>Newsletter</h1>
                <p>Sign Up for Our Newsletter to receive latest news and events</p>
                <div class="input-group input-group-lg foot-sign-up">
                    <input type="text" class="form-control" id="subscription-input-foot" placeholder="Enter Your Email Address">
                    <span class="input-group-btn">
                        <button class="btn btn-default" id="subscription-button-foot" type="button"><span class="glyphicon glyphicon-ok" aria-hidden="true"></span></button>
                    </span>
                </div>
            </div>
            <div class="col-xs-3"></div>
        </div>
        <div class="row dg-foot-cms">
            <div class="col-xs-3">
                <h4>About DrGrab</h4>
                <ul class="list-unstyled">
                    <li><a href="<?php echo $domain ?>/pages/about-us">About Us</a></li>
                    <li><a href="<?php echo $domain ?>/pages/contact-us">Contact Us</a></li>
                    <li><a href="<?php echo $domain ?>/pages/Why-choose-us">Why choose Us</a></li>
                    <li><a href="<?php echo $domain ?>/pages/faq">FAQ</a></li>
                </ul>
            </div>
            <div class="col-xs-3">
                <h4>More</h4>
                <ul class="list-unstyled">
                    <li><a href="<?php echo $domain ?>/pages/Shop-with-Confidence">Shop with Confidence</a></li>
                    <li><a href="<?php echo $domain ?>/pages/shipping-guide">Shipping Guide</a></li>
                    <li><a href="<?php echo $domain ?>/pages/return-policy">Return Policy</a></li>
                    <li><a href="<?php echo $domain ?>/pages/privacy-policy">Privacy Policy</a></li>
                </ul>
            </div>
            <div class="col-xs-6">
                <h4 style="border: none">As Featured in..</h4>
                <table>
                    <tr>
                        <td colspan="2"><a href="http://www.msnbc.com/"><img src="<?php echo $cdn ?>image/low/msnbc.gif"></a></td>
                        <td><a href="http://www.dailyherald.com"><img src="<?php echo $cdn ?>image/low/dh.gif"></a></td>
                    </tr>
                    <tr class="dg-foot-cms-img">
                        <td><a href="http://abc.go.com"><img src="<?php echo $cdn ?>image/low/abc.jpg"></a></td>
                        <td><a href="http://www.foxnews.com"><img src="<?php echo $cdn ?>image/low/fox.gif"></a></td>
                        <td><a href="http://www.ask.com"><img src="<?php echo $cdn ?>image/low/ask.gif" style="width: 45%"></a></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>  

<div class="dg-bottom">
    <div class="container">
        <div class="row">
            <div class="col-xs-6">
                <p>© <?= date("Y");?> Copyright by DrGrab. All Rights Reserved.</p>
            </div>
            <div class="col-xs-6 dg-bottom-logo">
                <img src="<?php echo $cdn ?>image/logos.png">
            </div>
        </div>
    </div>
</div>


<!--
<script src="<?php echo $cdn ?>js/bootstrap.min.js"></script>
<script src="<?php echo $cdn ?>js/bootstrap-hover-dropdown.min.js"></script>
<script src="<?php echo $cdn ?>js/jquery.bxslider.min.js"></script>
<script src="<?php echo $cdn ?>js/jquery.notifyBar.js"></script>
<script src="<?php echo $cdn ?>js/bootstrap-select.min.js"></script>
<script src="<?php echo $cdn ?>js/bootstrap-datepicker.min.js"></script>
<script src="<?php echo $cdn ?>js/icheck.js"></script>
<script src="<?php echo $cdn ?>js/jquery.bxslider.min.js"></script>
<script src="<?php echo $cdn ?>js/star-rating.min.js"></script>
<script src="<?php echo $cdn ?>js/jquery.countdown.min.js"></script>
<script src="<?php echo $cdn ?>js/perfect-scrollbar.min.js"></script>
<script src="<?php echo $cdn ?>js/jquery-scrolltofixed-min.js"></script>
<script src="<?php echo $cdn ?>js/parabola.js"></script>
<script src="<?php echo $cdn ?>js/jquery.waypoints.min.js"></script>
<script src="<?php echo $cdn ?>js/jquery-hightlight.js"></script>
<script src="<?php echo $cdn ?>js/jquery.isloading.js"></script>
<script src="<?php echo $cdn ?>js/emailpop.js"></script>
<script src="<?php echo $cdn ?>js/dropzone.min.js"></script>
<script src="<?php echo $cdn ?>js/bootstrap-notify.min.js"></script>
-->

<script src="<?php echo $cdn ?>js/jquery.min.js"></script>
<script src="<?php echo $cdn ?>js/jquery.creditCardValidator.js"></script>
<script src="<?php echo $cdn ?>js/lib.js"></script>
<script src="<?php echo $cdn ?>js/main.js"></script>
<script type="text/javascript" src="//analytics.aweber.com/js/awt_analytics.js?id=Ajvw"></script>
<div id="fb-root"></div>
<script>
                        (function (d, s, id) {
                            var js, fjs = d.getElementsByTagName(s)[0];
                            if (d.getElementById(id))
                                return;
                            js = d.createElement(s);
                            js.id = id;
                            js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.4&appId=901119496611377";
                            fjs.parentNode.insertBefore(js, fjs);
                        }(document, 'script', 'facebook-jssdk'));
</script>

<script>
var cdn="<?php echo $cdn ?>";
var szimg='<img src="'+cdn+'image/grab.gif" width="64">';
var productObj=<?php echo str_replace("'","\'",json_encode($buyList)) ?>;

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

//商品底部弹出
function productpop(i){
        var time=productObj[i]["buy_time"];

        var popcontant='<table><tr><td><img src="<?php echo IMAGE_DOMAIN ?>'+productObj[i]["image"]+'" style="width:70px;margin-right:10px;"></td><td><strong>Someone just bought</strong><p>'+productObj[i]["title"]+'</p><span>'+time+'  ago...&nbsp;&nbsp;&nbsp;&nbsp;<img src="'+'<?php echo $cdn ?>'+'image/flag/'+productObj[i]["country_code"]+'.png"></span></td></tr></table>';
        var popurl=productObj[i]["seo_url"];
        $.notify({
            title: '',
            message: popcontant,
            url:'<?=$domain ?>/products/'+ popurl,
            target: '_blank'
        },{
            type: 'minimalist',
            delay: 5000,
            timer: 1000,
            mouse_over: "pause",
            allow_dismiss: true,
            placement: {
                from: "bottom",
                align: "right"
            },
            animate: {
                enter: 'animated fadeInRight',
                exit: 'animated fadeOutRight'
            },
            onClosed:checkClose
        });
    
}


isClose = true;
productObj_i = 0;

function checkClose(){
    isClose = true;
}

function newOrderPop(){
    if(!sessionStorage.length || sessionStorage['closeCount']<2){
        if(isClose){
            productpop(productObj_i);
            isClose = false;
            if(productObj_i==productObj.length-1){
                productObj_i=0;
            }else{
              productObj_i++;   
            }
        }
    }
}



setInterval(function(){
    newOrderPop() 
    },25000
);

setTimeout(newOrderPop, 8000);

$("body").on("click", "[data-notify='dismiss']", function(){
    if(!sessionStorage.length){
        sessionStorage['closeCount'] = 1;
    }
    else{
        sessionStorage['closeCount'] = parseInt(sessionStorage['closeCount']) + 1;
    }
});

/*beforeclose();
function beforeclose(){
    
    $(window).bind('beforeunload', function(){
        
        if(window.is_confirm !== false)
            return 'You may have data not saved';
    })
    .bind('mouseover mouseleave', function(event){
        //console.log(event.clientY);
        is_confirm = event.type == 'mouseleave';
    });
}*/

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
