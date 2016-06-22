<?php echo $head;?>
<div class="modal fade" id="dg-main-product">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Deal Details</h4>
            </div>
            <div class="modal-body">
                <div class="dg-main-product-core-panel">
                    <div class="dg-main-product-core-panel-price">
                        <span class="dg-main-product-core-panel-price-current"><?php echo $currency ?><span id="price">12.02</span></span>
                        <span class="dg-main-product-core-panel-price-was"><del><?php echo $currency ?><span id="original">26.44</span></del></span>
                    </div>
                    <div class="dg-main-product-core-panel-instock"> <span style="color:#00B6C8;">In Stock.</span> Dispatch in 2-3 business days </div>
                    <form id="upa_attr">
                        <input type="hidden" value="" id="product_id" name="product_id">
                        <input type="hidden" value="" id="product_sku" name="product_sku">
                        <input type="hidden" value="" id="product_attr" name="product_attr">
                    </form>
                    <div class="dg-main-product-core-panel-selector">
                        <table>
                            <tbody id="productBody">
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td class="dg-main-product-core-panel-selector-title">Qty :</td>
                                    <td>
                                        <div class="qty_product">
                                            <button title="Decrease Qty" onclick="qtyDown();
                                                    return false;" class="decrease">-</button>  
                                            <input id="qty" name="qty" value="1" size="4" title="Qty" class="input-text product_qty" maxlength="12">
                                            <button title="Increase Qty" onclick="qtyUp();
                                                    return false;" class="increase">+</button>
                                        </div>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn pull-right btnCart" id="btnAddToCart" data-image=""  >Add to Cart</button>
                <!--<button type="button" class="btn btn-danger btncart" data-dismiss="modal" id="btnAddToCart" disabled="disabled">Add to Cart</button>-->
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div class="dg-main">
    <div class="container">
        <div class="row">
            <div class="col-xs-10 col-xs-12">
                <div class="dg-main-index-slider">
                <?php
                foreach($image as $vo){
                    echo '<li><a href="'.$vo['link'].'"><img src="'.IMAGE_DOMAIN.$vo['image'].'"></a></li>';
                }
                ?>
                </div>
                <div class="dg-main-index-sub">
                    <div class="row">
                        <div class="col-xs-3">
                            <img src="image/index/sub.png"> 
                        </div>
                        <div class="col-xs-9">
                            <div class="input-group input-group-lg">
                                <input type="text" class="form-control" name="email" id="subscription-input" placeholder="Enter Your Email Address">
                                <span class="input-group-btn">
                                    <button class="btn btn-default" id="subscription-button" type="button"><span class="glyphicon glyphicon-ok" aria-hidden="true"></span></button>
                                </span>
                            </div>
                            <div class="dg-main-index-sub-text">Subscribe to our newsletter to receive <hl>special offers</hl> and <hl>discount codes</hl> by email</div>
                        </div>
                    </div>      	  	
                </div>
                <div class="dg-main-index-title">New Deals</div>            

                <div class="dg-main-index-product clearfix">
                    <?php foreach ($newDeals['allow'] as $product_id => $productInfo) : ?>
                        <div class="dg-main-index-product-item2">
                            <div class="dg-main-index-product-item-image">
                                <a href="/product/index/<?php echo $product_id ?>"><img src="<?php echo IMAGE_DOMAIN . '/product/' . $productInfo['sku'] ?>/<?php echo $productInfo['sku'] ?>.jpg"></a>
                                <div class="dg-main-index-product-item-image-button">
                                    <!--<button type="button" class="dg-main-index-product-item-image-button-bn btn btn-dg-pop pull-left">Buy Now</button>-->
                                    <button type="button" class="dg-main-index-product-item-image-button-bnt btn btn-dg-pop-btn pull-left" <?php if ($productInfo['children']) echo 'data-toggle="modal"  data-target="#dg-main-product"' ?> data-productid="<?php echo $product_id ?>" data-children="<?php echo $productInfo['children'] ?>" data-action="Buy Now">Buy Now</button>
                                    <button type="button" class="dg-main-index-product-item-image-button-bnb btn btn-dg-pop-btn pull-right" <?php if ($productInfo['children']) echo 'data-toggle="modal"  data-target="#dg-main-product"' ?> data-productid="<?php echo $product_id ?>" data-children="<?php echo $productInfo['children'] ?>" data-action="Add to Cart">Add to Cart</button>
                                </div>
                            </div>
                            <div class="dg-main-index-product-item-detail">
                                <div class="dg-main-index-product-item-detail-title">
                                    <a href="/product/index/<?php echo $product_id ?>"><?php echo htmlspecialchars_decode($productInfo['title']) ?></a>
                                </div>
                                <div class="dg-main-index-product-item-detail-bottom">
                                    <div class="dg-main-index-product-item-detail-price pull-left">
                                        <?php echo $currency . $productInfo['price'] / 100 ?><del><?php echo $currency . $productInfo['original'] / 100 ?></del>
                                    </div>
                                    <span class="dg-main-index-product-item-detail-bottom-countdown pull-left">
                                        <?php if (array_key_exists('endTime', $productInfo)): ?>
                                            <img src="image/index/countdown.png"> <span data-countdown="<?php echo $productInfo['endTime'] ?>"> 6 days : 2h : 6m</span>
                                        <?php endif; ?>

                                    </span>
                                    <a href="/product/index/<?php echo $product_id ?>"><span class="dg-main-index-product-item-detail-bottom-sold pull-right"><?php echo $productInfo['sold']['total'] ?> solds</span></a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                <div class="dg-main-index-title">Theme</div>            
                <div class="dg-main-index-theme">
                    <div class="row">
                        <div class="col-xs-8">
                            <div class="dg-main-index-theme-slide">
                                <img src="image/testitem2/index/theme1.jpg">
                            </div>
                        </div>
                        <div class="col-xs-4">
                            <div class="dg-main-index-theme-product">
                                <div class="dg-main-index-theme-product-cell clearfix">
                                    <a href=""><img src="image/testitem2/index/theme2.jpg"></a>
                                    <div class="dg-main-index-theme-product-cell-title"><a href="">MSQ 3pcs Eye Shadow Brush Set</a></div>
                                    <div class="dg-main-index-theme-product-cell-price">$97 <del>288</del> </div>
                                    <div class="dg-main-index-theme-product-cell-other">
                                        <a class="dg-main-index-theme-product-cell-other-sold" href="">39 Solds</a>
                                        <a class="dg-main-index-theme-product-cell-other-buynow" href=""><span>Buy Now</span></a>
                                    </div>
                                </div>
                                <div class="dg-main-index-theme-product-cell clearfix">
                                    <a href=""><img src="image/testitem2/index/theme3.jpg"></a>
                                    <div class="dg-main-index-theme-product-cell-title"><a href="">MSQ Makeup Train Case</a></div>
                                    <div class="dg-main-index-theme-product-cell-price">$97 <del>288</del> </div>
                                    <div class="dg-main-index-theme-product-cell-other">
                                        <a class="dg-main-index-theme-product-cell-other-sold" href="">39 Solds</a>
                                        <a class="dg-main-index-theme-product-cell-other-buynow" href=""><span>Buy Now</span></a>
                                    </div>
                                </div>
                                <div class="dg-main-index-theme-product-cell clearfix">
                                    <a href=""><img src="image/testitem2/index/theme4.jpg"></a>
                                    <div class="dg-main-index-theme-product-cell-title"><a href="">MSQ 18pcs Professional Animal Hair Brush Set</a></div>
                                    <div class="dg-main-index-theme-product-cell-price">$97 <del>288</del> </div>
                                    <div class="dg-main-index-theme-product-cell-other">
                                        <a class="dg-main-index-theme-product-cell-other-sold" href="">39 Solds</a>
                                        <a class="dg-main-index-theme-product-cell-other-buynow" href=""><span>Buy Now</span></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="dg-main-index-title">New Dealsssssss</div>            


            </div>
            <?php echo $shoppingcart?>
        </div>
    </div>
</div>  

<div id="flyItem" class="fly_item"><img src="" width="40" height="40"></div>

<?php echo $foot ?>
<script>

//to fix the button showing up bug
$(".dg-main-index-product-item-image-button-bnt,.dg-main-index-product-item-image-button-bnb").focus(function (event) {
    $(this).blur();
});
//to record the X and Y position for the fly origin
$(".dg-main-index-product-item-image-button-bnt,.dg-main-index-product-item-image-button-bnb").click(function (event) {
    flyX = event.clientX;
    flyY = event.clientY;
});
//item qty modifying
function qtyDown(id) {
    if (parseInt($('#qty').val()) > 1) {
        $('#qty').val(parseInt($('#qty').val()) - 1);
    }
    return false;
}
function qtyUp(id) {
    $('#qty').val(parseInt($('#qty').val()) + 1);
    return false;
}
$(function () {
    var buttonshowhidebtn;
    //navigation bar fix
    $('.dg-navbar').affix({
        offset: {top: $('.dg-navbar').offset().top}
    });
    //big picture slider init
    $('.dg-main-index-slider').bxSlider({
        auto: true,
        mode: 'fade'
    });
    $('.dg-main-index-slider').css('visibility', 'visible');
    //Countdown init
    $('[data-countdown]').each(function () {
        var $this = $(this), finalDate = $(this).data('countdown');
        $this.countdown(finalDate, function (event) {
            $this.html(event.strftime('%D days %H:%M:%S'));
        });
    });
});
// Add to cart for No-Option Product
$('.btn-dg-pop-btn').click(function(){
    //if product has no option
    // if ($(this).data('action') == 'Buy Now') {
    //     button_buynow_disabled(this);
    // }else{
    //     button_addcart_disabled(this);
    // };
    if( $(this).data('children')== 0){
        var product_id = $(this).data('productid');
        var action =  $(this).data('action');
        if ($(this).data('action') == 'Buy Now') {
            button_buynow_disabled(this);
        }else{
            button_addcart_disabled(this);
        };
        //get product detail
        $.post('/product/getProduct', {
            product_id: product_id
        }, function (result) {
            if (result.success) {
                pdimage = result.image;
                pdtitle = result.title;
                pdsku = result.sku;
                $('#product_sku').val(result.sku);
                $('#product_attr').val('');
                $('#qty').val(1);
                //post data to cart
                $.ajax({
                    type: "POST",
                    url: "/cart/addCart",
                    dataType: 'json',
                    data: {
                        p_id: product_id,
                        p_sku: pdsku,
                        p_attr: '',
                        p_qty: 1
                    },
                    success: function (result) {
                        //if success, redirect to cart or activate fly animation
                        if (action==="Buy Now") {
                            self.location = '/cart';
                            setTimeout(button_addcart_enabled('.dg-main-index-product-item-image-button .btn-dg-pop-btn:nth-child(odd)','Buy Now'),1000);
                        } else {
                            $(".notproductcart").css("display", "none");
                            $(".listproductcart").css("display", "block");
                            cartflyhref = '/product/index/' + product_id
                            cartflyimg = '<?php echo IMAGE_DOMAIN ?>' + pdimage;
                            $("#flyItem").find("img").attr('src', cartflyimg);
                                                                            cartflytitle = pdtitle;
                                                                            flyNow();
                                                                            setTimeout("button_addcart_enabled('.dg-main-index-product-item-image-button .btn-dg-pop-btn:nth-child(even)','Add to Cart')",800);
                                                                           //setTimeOut(alert(),5000);
                                                                            
                                                                        }
                                                                    }
                                                                });
                                                        
                                                            }
                                                        }, 'json');
                                                    }
                                                })
                                                
                                                // Add to cart for Pop-up Product (Pop-up Preparation)
                                                validToAdd = false;
                                                
                                                $('#dg-main-product').on('hidden.bs.modal', function (event) {
                                                    validToAdd = false;
                                                    $('#productBody').empty();
                                                });
                                                
                                                $('#dg-main-product').on('show.bs.modal', function (event) {
                                                    buttonshowhidebtn = $(event.relatedTarget);
                                                    var product_id = buttonshowhidebtn.attr("data-productID");
                                                    var action = buttonshowhidebtn.attr("data-action");
                                                    if (action == "Buy Now") {
                                                        $('#btnAddToCart').addClass('show-modal-buynow').removeClass('show-modal-addtocart');
                                                    }else{
                                                        $('#btnAddToCart').addClass('show-modal-addtocart').removeClass('show-modal-buynow');
                                                    };
                                                    $('#qty').val(1);
                                                    $.post('/product/getProduct', {
                                                        product_id: product_id
                                                    }, function (result) {
                                                        if (result.success) {
                                                            $('#product_id').val(product_id);
                                                            $('.modal-title').html(result.title);
                                                            $('#price').html(result.price / 100);
                                                            $('#original').html(result.original / 100);
                                                            $('#product_sku').val(result.sku);
                                                            $('#product_attr').val('');
                                                            $('#btnAddToCart').html(action);
                                                            $('#btnAddToCart').data('image', result.image);
                                                            $('#btnAddToCart').data('title', result.title);
                                                            $('#btnAddToCart').data('productID', product_id);
                                                            if (result.details > 0)
                                                            {
                                                                var body = '';
                                                                $.each(result.variants, function (i, rowdata) {
                                                                    body = body + '<tr><td class="dg-main-product-core-panel-selector-title">' + rowdata.option_map + ' :</td><td><div class="dg-main-product-core-panel-selector-option">';
                                                                    $.each((rowdata.value_map).split(','), function (i, value_mapping) {
                                                                        body = body + '<div data-val="' + value_mapping + '">' + value_mapping + '</div>';
                                                                    });
                                                                    body = body + '</div></td></tr>';
                                                                });
                                                                $('#productBody').append(body);
                                                            } else {
                                                                validToAdd = true;
                                                            }
                                                            //加入购物车判断
                                                            
                                                            $num = $(".dg-main-product-core-panel-selector-option").length;
                                                            $(".dg-main-product-core-panel-selector-option div").on('click', function () {
                                                                $(this).addClass('select').siblings().removeClass('select');
                                                                $snum = $(".dg-main-product-core-panel-selector-option .select").length;
                                                                $stext = '';
                                                                if ($snum === $num) {
                                                                    for (var i = 0; i < $snum; i++) {
                                                                        if (i === ($snum - 1)) {
                                                                            $stext += $(".dg-main-product-core-panel-selector-option .select").eq(i).text();
                                                                        } else {
                                                                            $stext += $(".dg-main-product-core-panel-selector-option .select").eq(i).text() + '-';
                                                                        }
                                                                    }
                                                                    $("#product_attr").val($stext);


                                                                    $.ajax({
                                                                        type: "POST",
                                                                        url: "<?php echo site_url('product/getAttr') ?>",
                                                                        dataType: 'json',
                                                                        data: $("#upa_attr").serialize(),
                                                                        success: function (result) {
                                                                            if (result.success) {
                                                                                validToAdd = true;
                                                                                $("#price").html(result.resultMessage.price / 100);
                                                                                $("#original").html(+result.resultMessage.original / 100);
                                                                            }
                                                                        }
                                                                    });
                                                                }

                                                            });


                                                        }
                                                    }, 'json');
                                                });

                                                // Add to cart for Pop-up Product (Add to cart)
                                                var eleFlyElement = document.querySelector("#flyItem"), eleShopCart = document.querySelector(".listproductcart");
                                                if (eleFlyElement && eleShopCart) {

                                                    $(".btnCart").click(function(event){
                                                        //to check if all options are checked.
                                                        //button_addcart_disabled(this);

                                                        if(validToAdd){
                                                            if ($(this).text() == 'Buy Now') {
                                                                button_buynow_disabled(this);
                                                            }else{
                                                                button_addcart_disabled(this);
                                                            };
                                                            buttonObj = $(this);
                                                            
                                                            $.ajax({
                                                                type: "POST",
                                                                url: "/cart/addCart",
                                                                dataType: 'json',
                                                                data: {
                                                                    p_id: $('#product_id').val(),
                                                                    p_sku: $("#product_sku").val(),
                                                                    p_attr: $("#product_attr").val(),
                                                                    p_qty: $("#qty").val()
                                                                },
                                                                success: function (result) {
                                                                    
                                                                    if (buttonObj.html() === 'Processing..') {
                                                                        self.location = '/cart';
                                                                        button_buynow_enabled(this);
                                                                    } else {
                                                                        $('#dg-main-product').modal('hide');
                                                                        $(".notproductcart").css("display", "none");
                                                                        $(".listproductcart").css("display", "block");
                                                                        cartflyhref = '/product/index/' + buttonObj.data('productID')
                                                                        cartflyimg = '<?php echo IMAGE_DOMAIN ?>' + buttonObj.data('image');
                                                                        $("#flyItem").find("img").attr('src', cartflyimg);
                                                                        cartflytitle = buttonObj.data('title');
                                                                        setTimeout("button_addcart_enabled(this)",800);
                                                                        flyNow();
                                                                        
                                                                    }
                                                                }
                                                            });
                                                        }
                                                        else{
                                                            $.notifyBar({ cssClass: "dg-notify-error", html: "To Grab, Please Select the Options" ,position: "bottom" });
                                                            $(".dg-main-product-core-panel-selector-option div").removeClass("a-ring").addClass("a-ring");
                                                            button_addcart_enabled(this);
                                                        }

                                                        
                                                    })
                                                }
                                                
                                                //fly Animation 
                                                function flyNow(){
                                                    // 滚动大小
                                                    var scrollLeft = document.documentElement.scrollLeft || document.body.scrollLeft || 0,
                                                            scrollTop = document.documentElement.scrollTop || document.body.scrollTop || 0;
                                                    eleFlyElement.style.left = flyX + scrollLeft + "px";
                                                    eleFlyElement.style.top = flyY + scrollTop + "px";
                                                    eleFlyElement.style.visibility = "visible";
                                                    
                                                    var numberItem = 0;
                                                    // 抛物线运动
                                                    var myParabola = funParabola(eleFlyElement, eleShopCart, {
                                                        speed: 100, //抛物线速度
                                                        curvature: 0.0006, //控制抛物线弧度
                                                        complete: function () {
                                                            eleFlyElement.style.visibility = "hidden";
                                                            if ($('#product_attr').val() === '') {
                                                                var liID = $('#product_sku').val();
                                                            } else {
                                                                var liID = $('#product_sku').val() + '/' + $('#product_attr').val();
                                                            }
                                                            var qty = parseInt($('#qty').val());
                                                            $('#shopCart li').each(function () {
                                                                if ($(this).attr("id") === liID) {
                                                                    qty = qty + parseInt($(this).find('span').html());
                                                                    $(this).detach();
                                                                }
                                                            });
                                                    
                                                            $("#shopCart").prepend("<li id='" + liID + "'><a href='" + cartflyhref + "'><img src='" + cartflyimg + "'></a><a class='title' href='" + cartflyhref + "'>" + cartflytitle + "</a><p>x <span>" + qty + "</span></p></li>");
                                                            $(".cartempty").css("display", "none");
                                                            $(".checkoutpage").fadeIn("slow");
                                                        }
                                                    });
                                                    
                                                    myParabola.position().move();
                                                }
                                                
                                                //login 

                                                
                                                //subscription

                                                $("#subscription-button").click(function () {
                                                    button_disabled_foot(this);
                                                    if($('#subscription-input').val() == ''){
                                                        $.notifyBar({ cssClass: "dg-notify-error", html: "Your email not null" ,position: "bottom" });
                                                        button_enabled(this);
                                                    }else{
                                                        if (!(/^([\w-_]+(?:\.[\w-_]+)*)@((?:[a-z0-9]+(?:-[a-zA-Z0-9]+)*)+\.[a-z]{2,6})$/i.test($('#subscription-input').val()))) {
                                                            $.notifyBar({ cssClass: "dg-notify-error", html: "Your email address is invalid" ,position: "bottom" });
                                                            button_enabled(this);
                                                        }else{

                                                            $.post("/subscription/insert", {
                                                                email: $('#subscription-input').val()
                                                            }, function (result) {
                                                                var result = $.parseJSON(result);
                                                                if (result.status) {
                                                                    button_enabled("#subscription-button");
                                                                    $.notifyBar({ cssClass: "dg-notify-success", html: "Success" ,position: "bottom" });
                                                                    $('#subscription-input').reset();
                                                                    
                                                                }
                                                                else{
                                                                    $.notifyBar({ cssClass: "dg-notify-error", html: "The existing email!" ,position: "bottom" });
                                                                    button_enabled("#subscription-button");
                                                                }
                                                                button_enabled("#subscription-button");
                                                            });
                                                            
                                                        }
                                                    }


});
cartempty();
</script>
</body>
</html>
