<?php echo $head; ?>
    <div role="main" class="ui-content" itemscope itemtype="http://schema.org/Product">
        <ol class="breadcrumb">
            <?= $breadcrumb ?>
        </ol>
        <div class="dg-productcon">
            <!-- banner -->
            <div class="swiper-container">
                <div class="swiper-wrapper" style="position: relative;">
                    <?php
                    if($pro['freebies']==1){
                            $freeImg = "freebie";
                        }else{
                            $freeImg = "free-shipping";
                        }
                    foreach ($pro['pics'] as $vo) {
                        if ($vo['img'] != NULL) {
                            echo '<div class="swiper-slide"><div class="dg-productcell-product-shipping"><img src="'.$cdn.'img/'.$freeImg.'@2x.png"/ style="width:60px;margin-left:15px;"></div><img itemprop="image" content="' . IMAGE_DOMAIN . $vo['img'] . '" alt="' . htmlspecialchars_decode($pro['title']) . '" src="' . IMAGE_DOMAIN . $vo['img'] . '"/></div>';
                        }
                    }
                    ?>
                </div>
                <!-- Add Pagination -->
                <div class="swiper-pagination"></div>
            </div>
            <div class="dg-productcon-info">
                <div class="dg-productcon-title" itemprop="name"><?= htmlspecialchars_decode($pro['title']) ?></div>
                <div class="dg-productcon-dash">
				   	<span><?php if($pro['status']==1):?>In Stock.<?php else:?>Out Of Stock<?php endif;?></span> Dispatch in 2-3 business days

                    <?php if($pro['freebies']==1) :?>
                    <br><br>An Additional Delivery Charge of <span style="color:#00B6C8;"><?= $currency ?><?php echo $pro['oprice'] / 100; ?></span> may apply to this item.</span>
                    <?php endif;?>                    

                </div>
                <div itemprop="offers" itemscope itemtype="http://schema.org/Offer">
                    <meta itemprop="availability" content="http://schema.org/InStock"/>
                    <meta itemprop="priceCurrency" content="<?php echo $currency; ?>" />
                    <span class="dg-productcon-price" id="p_price" itemprop="price" content="<?php echo $pro['price'] / 100; ?>"><?= $currency . $pro['price'] / 100 ?></span>
                    <span class="dg-productcon-was" id="p_original"><del><?= $currency . $pro['original'] / 100 ?></del></span>
                    <span class="dg-productcon-save">You save <span id="p_save"><?= $save ?></span>%!
                    </span>
                    <!-- <span class="dg-productcell-countdown"> <span class="icon-clock"></span> 22 days 06:31:22</span> -->
                </div>
            </div>

            <input type="hidden" value="<?= $pro['_id'] ?>" name="product_id"
                   id="cart_pid" /> <input type="hidden" value="<?= $pro['sku'] ?>"
                   name="product_sku" id="cart_sku" itemprop="sku" /> <input type="hidden" value=""
                   name="product_attr" id="product_attr" /> <input type="hidden"
                   value="<?= $product_bundle ?>" id="product_bundle"
                   name="product_bundle">
            <table class="dg-productcon-butgro">
                <?php if ($product_bundle == 1): ?>
                    <?php if ($data['is_variants']): ?>
                        <?php foreach ($pro['variants'] as $variants): ?>
                            <tr>
                                <td class="dg-productcon-optionname">
                                    <?= $variants['option_map'] ?> :
                                </td>
                                <td>
                                    <?php
                                    $mapping = explode(',', $variants['value_map']);
                                    $valueSku = explode(',', $variants['value']);
                                    ?>
                                    <?php foreach ($mapping as $index => $value): ?>
                                        <button
                                            data-val="<?= $valueSku[$index] ?>"><?= $value ?></button>
                                        <?php endforeach ?>                         
                                </td>
                            </tr>
                        <?php endforeach ?>
                    <?php endif; ?>

                <?php elseif ($product_bundle == 2): ?>
                    <tr>
                        <td class="dg-productcon-optionname">Select Buy :</td>
                        <td>
                            <?php foreach ($pro['plural'] as $plural): ?>
                                <button data-val="<?= $plural['number'] ?>"><?= $plural['number'] ?></button>
                            <?php endforeach ?>
                        </td>
                    </tr>

                <?php elseif ($product_bundle == 3): ?>
                    <?php if ($data['is_variants']): ?>
                        <?php foreach ($pro['variants'] as $variants): ?>
                            <tr>
                                <td class="dg-productcon-optionname">
                                    <?= $variants['option_map'] ?> :
                                </td>
                                <td>
                                    <?php
                                    $mapping = explode(',', $variants['value_map']);
                                    $valueSku = explode(',', $variants['value']);
                                    ?>
                                    <?php foreach ($mapping as $index => $value): ?>
                                        <button
                                            data-val="<?= $valueSku[$index] ?>"><?= $value ?></button>
                                        <?php endforeach ?>                   
                                </td>
                            </tr>
                        <?php endforeach ?>
                        <?php foreach ($pro['variants'] as $variants): ?>
                            <tr>
                                <td class="dg-productcon-optionname">
                                    <?= $variants['option_map'] ?> :
                                </td>
                                <td>
                                    <?php
                                    $mapping = explode(',', $variants['value_map']);
                                    $valueSku = explode(',', $variants['value']);
                                    ?>
                                    <?php foreach ($mapping as $index => $value): ?>
                                        <button
                                            data-val="<?= $valueSku[$index] ?>"><?= $value ?></button>
                                        <?php endforeach ?>                   
                                </td>
                            </tr>
                        <?php endforeach ?>
                    <?php endif; ?>

                <?php endif; ?>
                <tr>
                    <td>Qty :</td>
                    <td><a data-inline="true" onclick="qty(1)">-</a><span id="qty"
                                                                          data-num="2">1</span><a onclick="qty(2)" data-inline="true">+</a>
                    </td>
                </tr>
            </table>
            <div class="dg-product-con" data-role="collapsible-set" data-theme="d" data-content-theme="d" id="dg-product-con">
                <div data-role="collapsible" data-collapsed-icon="" data-expanded-icon="" data-collapsed="false">
                    <h3><i class="icon-category"></i> Description</h3>
                    <p><?= htmlspecialchars_decode($pro['description']) ?></p>
                </div>
                <?php if(strip_tags($pro['topreview'])):?>
                <div data-role="collapsible" data-collapsed-icon="" data-expanded-icon="" data-collapsed="true">
                    <h3><!--<i class="icon-clock"></i> --><i class="icon-review"></i> Top Review</h3>
                    <p><?php echo htmlspecialchars_decode($pro['topreview']); ?></p>
                </div>
                <?php endif;?>
                <!-- <div data-role="collapsible" data-collapsed-icon="" data-expanded-icon="" data-collapsed="true">
                    <h3><i class="icon-clock"></i>Reviews</h3>
                    <p><?= htmlspecialchars_decode($pro['specification']) ?></p>
                </div> -->
                <div data-role="collapsible" data-collapsed-icon="" data-expanded-icon="" data-collapsed="true">
                    <h3><i class="icon-plane"></i> Shipping</h3>
                    <p><?php echo $desc_shipping['pages_content']; ?></p>
                </div>
                <div data-role="collapsible" data-collapsed-icon="" data-expanded-icon="" data-collapsed="true">
                    <h3><i class="icon-credit-card"></i> Payment</h3>
                    <p><?php echo $desc_payment['pages_content']; ?></p>
                </div>
            </div>

            <?php if (empty(!$comments)) :?>
            <div class="dg-product-comment">
                <div class="dg-comment-title">Customer Reviews</div>
                <?php foreach ($comments as $comment): ?>
                <div class="dg-product-comment-content">
                    <div class="dg-product-comment-content-star">
                        <?php
                        $star = $comment['product_star'];
                        if($star>0)
                            echo "<span class='active'>";
                        for($i=1;$i<=5;$i++){
                                echo "<span class='icon-star'></span>";
                                if($i==$star)
                                    echo "</span>";
                        }
                        ?>
                    </div>
                    <div class="dg-product-comment-content-user">
                        Reviewed by: <?php echo substr($comment['commentator'],0,5);?> *****
                    </div>
                    <br>
                    <div class="dg-product-comment-content-des">
                        <?php echo $comment['product_comment'];?>
                    </div>
                </div>
                <?php endforeach;?>
            </div>
            <?php endif;?>
        </div>

    </div>


    <div class="dg-productcon-footer">
        <table border="0" cellspacing="0" cellpadding="0" width="100%">
            <tr>
                <td style="width: 48%;"><a href="#" class="buynow" id="buy_now">
                        Buy Now </a></td>
                <td style="width: 4%;"></td>
                <td style="width: 48%;"><a href="#" class="addtocart"> Add to Cart
                    </a></td>
            </tr>
            <tr>
                <td colspan="3"
                    style="color: white; text-align: center; padding-top: 0.7em;"><span
                        class="icon-star"></span> <?= $pro['sold']['total'] ?> Sold 
                        <?php if (!empty($pro['endTime'])): ?> 
                        <span class="icon-clock"></span> Ends in <span data-countdown="<?= $pro['endTime'] ?>"></span>&nbsp;<span id="countd" class="countd"></span>
                    <?php else: ?>
                        ,&nbsp;Grab it Now!
                    <?php endif; ?>   
                </td>
            </tr>
        </table>
    </div>
<?php echo $foot; ?>
</div>
    <script src="<?php echo $cdn ?>js/jquery.fly.min.js"></script>
    <script>
      var freebie="<?php echo $freeImg ?>"
   	  var productDetails=JSON.parse('<?php echo $pro['children'] ? str_replace('\"','\\\"',json_encode($pro['details'])) : 0 ?>');
    </script>
	<script>
        
        fbq('track', 'ViewContent');

        $("#container").css("padding-bottom","100px");
        
        //Initialize Swiper 
        var swiper = new Swiper('.swiper-container', {
            pagination: '.swiper-pagination',
            paginationClickable: true,
            // nextButton: '.swiper-button-next',
            // prevButton: '.swiper-button-prev'
        });
        
        function qty(sum){ 
            $nums = parseInt($('#qty').text());
            if (sum == 1) {
                if($nums > 1){
                    $('#qty').text($nums-1);
                    $('#qty').data('num',($nums-1));
                }
            }else{
                $('#qty').text($nums+1);
                $('#qty').data('num',($nums+1));
            };

        }

        
        $('.buynow').on('click',function(){
            if ($('.dg-productcon-optionname').length!=$('.dg-productcon-butgro .select').length) {
                $.notifyBar({ cssClass: "dg-notify-error", html:"To Grab, Please Select the Desired Options",position: "bottom"});
                $("html,body").animate({scrollTop:$(".dg-productcon-title").offset().top},500);
                $('.dg-productcon-butgro button').removeClass("a-ring").addClass("a-ring");
                return false;
            }else{
              $('body').isLoading({
                text: "Updating",
                position: "overlay",
                class: "fa-refresh", // loader CSS class
                tpl: '<span class="isloading-wrapper %wrapper%">'+szimg+'</span>'
              });
              button_addcart_disabled('.buynow',"Processing...");
            	$.ajax({
                	type: "POST",
                    url: "<?php echo site_url('cart/addCart') ?>",
                    dataType: 'json',
                    	data: {
                            p_id:$("#cart_pid").val(),
                            p_sku:$("#cart_sku").val(),
                            p_attr:$("#product_attr").val(),
                            p_bundle: $("#product_bundle").val(),
                            p_qty:$("#qty").html()
                        },
                    success: function (result) {
                    	if(result.success){

                            fbq('track', 'AddToCart');

                    		self.location = '/cart';
                    	}else{
                            button_addcart_enabled(".buynow",'Buy Now');
                            $.notifyBar({ cssClass: "dg-notify-error", html: result.resultMessage,position: "bottom" });
                        }
                      $('body').isLoading("hide");
                    }
                 }); 
            }
        });


        
        $('.addtocart').on('click',function(){
            if ($('.dg-productcon-optionname').length!=$('.dg-productcon-butgro .select').length) {
                $.notifyBar({ cssClass: "dg-notify-error", html:"To Grab, Please Select the Desired Options",position: "bottom"});
                $("html,body").animate({scrollTop:$(".dg-productcon-title").offset().top},500);
                $('.dg-productcon-butgro button').removeClass("a-ring").addClass("a-ring");
                return false;
            }else{
              $('body').isLoading({
                text: "Updating",
                position: "overlay",
                class: "fa-refresh", // loader CSS class
                tpl: '<span class="isloading-wrapper %wrapper%">'+szimg+'</span>'
              });
              button_addcart_disabled('.addtocart',"Adding...");
            	$.ajax({
                    type: "POST",
                    url: "<?php echo site_url('cart/addCart') ?>",
                    dataType: 'json',
                    data: {
                        p_id:$("#cart_pid").val(),
                        p_sku:$("#cart_sku").val(),
                        p_attr:$("#product_attr").val(),
                        p_bundle: $("#product_bundle").val(),
                        p_qty:$("#qty").html()
                    },
                    success: function (result) {
                    	if(result.success){

                            fbq('track', 'AddToCart');

                        addProduct();
                        button_addcart_enabled('.addtocart',"Add to Cart");
                    	}else{
                    		$.notifyBar({ cssClass: "dg-notify-error", html: result.resultMessage,position: "bottom" });
                        button_addcart_enabled('.addtocart',"Add to Cart");
                    	}
                      $('body').isLoading("hide");
                    }
                  });
            }
            setTimeout(cartpronumshow,4000);
            //cartpronumshow();
        });

        
        validToAdd = false;
        $num = $(".dg-productcon-optionname").length;
        $(".dg-productcon-butgro button").on('click',function(){

        	 $(this).addClass('select').siblings().removeClass('select');
        	 $snum = $('.dg-productcon-butgro .select').length;
        	 $stext = '';

        	 if ($snum == $num) {
        	 validToAdd = true;  
             for(var i=0; i<$snum ; i++){
                if(i == ($snum - 1)){
                  $stext += $(".dg-productcon-butgro .select").eq(i).data('val');
                }else{
                  $stext += $(".dg-productcon-butgro .select").eq(i).data('val')+'/';
                }
             };

             $("#product_attr").val($stext);

              var prosku=$('#cart_sku').val();
              var proselect=$('#product_attr').val();
              var matchsku=prosku+'/'+proselect;
              var smatchsku=matchsku.replace(/\s/g, "").toLowerCase();
              
              var length=productDetails.length;
              
              for(var x=0;x<length;x++){
                //console.log(String(productDetails[x].sku).replace(/\s/g, "").toLowerCase());
                  if(smatchsku === String(productDetails[x].sku).replace(/\s/g, "").toLowerCase()){
                      if(freebie=="freebie"){
                        $("#p_price").html("<?=$currency?>"+"0");
                      }else{
                        $("#p_price").html("<?=$currency?>"+productDetails[x].price / 100);
                      }
                      $("#p_original").html("<?=$currency?>"+productDetails[x].original / 100);
                      $('#p_save').html(productDetails[x].save)
                      validToAdd = true;
                  }
              }
        	}
          });

          if($('.dg-productcon-footer span').hasClass('countd')){

          $('[data-countdown]').each(function() {
              var $this = $(this), finalDate = $(this).data('countdown');
              var lday = ($(this).data('countdown')-Date.parse(new Date()))/1000/60/60/24;
              var hours = parseInt(($(this).data('countdown')-Date.parse(new Date()))/1000/60/60);
             
              $this.countdown(finalDate, function(event) {
                if (lday > 2) {
                  
                  $this.html(event.strftime('%D days %H:%M:%S'));
                }else if(1 < lday && lday <= 2 ) {

                  if (hours < 1) {
                    hours ='00'
                  };
                  $this.html(event.strftime(hours+':%M:%S'));
                 }else{
                  
                  $this.html(event.strftime('%H:%M:%S'));
                 
                  if (event.elapsed){
                    
                    countd.innerHTML='';
                  }
                 };
              });
            });
          }
    function addProduct(){
        var offset = $("#end").offset(),
            img = $('.swiper-wrapper img:nth-child(2)').attr('src'),
            //img = "http://image.catchoftheworld.com:1234/product/PRO-10333/PRO-10333.jpg",
            flyer = $('<img src="'+ img +'" style="width:50px;height:50px;border-radius:30px;border:2px solid #FF666C;z-index:999999">'),
            cartpronum = parseInt($('#cartpronum').text()),
            qty = parseInt($('#qty').text());
        flyer.fly({
            start: {
                left: $(window).width()/2,
                top: $(window).height()-100
                // left: $(".addtocart").offset().left,
                // top: $(".addtocart").offset().top
            },
            end: {
                  left: $(window).width()-30, //结束位置（必填）
                  top: 10,  //结束位置（必填）
                  width: 10, //结束时高度
                  height: 10, //结束时高度
            },
            speed: 0.9,
            onEnd: function(){
                flyer.hide();
            }
        });
        setTimeout(function(){$('#cartpronum').text(cartpronum + qty);},1000);
    }

    $(function() {
        $(".ui-collapsible-heading").click(function(){
            $(document).scrollTop( $(this).offset().top-92);
        })
    });

</script>
<?php if (isset($countrySEO)) echo $countrySEO ?>
</body>
</html>