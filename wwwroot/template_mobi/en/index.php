<?php echo $head; ?>       

            <div role="main" class="ui-content" data-offset="<?php echo $newDeals['offset'] ?>">
                
                <div class="swiper-container dg-swipernoborder">
        		    <div class="swiper-wrapper">
                    <?php
                        foreach ($image as $vo) {
                        	echo '<div class="swiper-slide"><a href="' . $vo['link'] . '"><img src="' . IMAGE_DOMAIN . $vo['image'] . '"></a></div>';
                        }
                    ?>
                    </div>
<!--                     <div class="swiper-pagination"></div> -->
                </div>      
                            
                <div class="dg-pagetitle">New Deals</div>
                <div class="dg-index-product">
                <?php if ($newDeals['sort'] == 'manual'): ?>
                
                    <?php foreach ($newDeals['sortProductID'] as $product_id) : ?>
                       <?php
                            $img = 'http:' . IMAGE_DOMAIN . '/product/' . $newDeals['allow'][(string) $product_id]['sku'] . '/' . $newDeals['allow'][(string) $product_id]['sku'] . '.jpg';
                            if (!@fopen($img, 'r')) {
                                $img = IMAGE_DOMAIN . $newDeals['allow'][(string) $product_id]['image'];
                            }
                            if($newDeals['allow'][(string) $product_id]['freebies']==1){
                                $freeImg = "freebie";
                                $newDeals['allow'][(string) $product_id]['price'] = 0;
                            }else{
                                $freeImg = "free-shipping";
                            }
                       ?>
                       
                      <div class="dg-productcell" style="position: relative">
                        <div class="dg-productcell-shipping">
                            <img src="<?php echo $cdn ?>img/<?php echo $freeImg;?>@2x.png"/>
                        </div>
                          <a id="productLink<?php echo $product_id ?>" href="/collections/<?php echo $newDeals['collection_url'] ?>/products/<?php echo $newDeals['allow'][(string) $product_id]['seo_url'] ?>"><img alt="<?php echo htmlspecialchars_decode($newDeals['allow'][(string) $product_id]['title']) ?>" style="width:100%;border-radius: 5px 5px 0px 0px;-moz-border-radius: 5px 5px 0px 0px;-webkit-border-radius: 5px 5px 0px 0px;" src="<?php echo $img; ?>"></a>
                    	<div class="dg-productcell-info">
                        	<div class="dg-productcell-title"><a href="/collections/<?php echo $newDeals['collection_url'] ?>/products/<?php echo $newDeals['allow'][(string) $product_id]['seo_url'] ?>"><?php echo htmlspecialchars_decode($newDeals['allow'][(string) $product_id]['title']) ?></a></div>
                        	<div class="dg-productcell-dash"></div>
                        	<div>
                            	<span class="dg-productcell-price"><?php echo $currency . $newDeals['allow'][(string) $product_id]['price'] / 100 ?></span>
                            	<span class="dg-productcell-was"><?php echo $currency . $newDeals['allow'][(string) $product_id]['original'] / 100 ?></span>
                            	<?php if (array_key_exists('endTime', $newDeals['allow'][(string) $product_id])&&!empty($newDeals['allow'][(string) $product_id]['endTime'])): ?>
                            		<span class="dg-productcell-countdown"> <span class="icon-clock" data-countdown="<?php echo $newDeals['allow'][(string) $product_id]['endTime'] ?>">22 days 06:31:22</span>&nbsp;<span id="countd" style="color: #00B6C6;font-family: 'icomoon';" class="countd"></span></span>
                            	<?php endif; ?>
                        	</div>
                    	</div>
                	</div>
                    <?php endforeach; ?>
                    
                <?php else: ?>
                
                    <?php foreach ($newDeals['allow'] as $product_id => $productInfo) : ?>
                    	<?php
                            $img = 'http:' . IMAGE_DOMAIN . '/product/' . $productInfo['sku'] . '/' . $productInfo['sku'] . '.jpg';
                            if (!@fopen($img, 'r')) {
                                $img = IMAGE_DOMAIN . $productInfo['image'];
                            }
                            if($newDeals['allow'][(string) $product_id]['freebies']==1){
                                $freeImg = "freebie";
                                $productInfo['price'] = 0;
                            }else{
                                $freeImg = "free-shipping";
                            }
                        ?>
                       
                      <div class="dg-productcell" style="position: relative">
                        <div class="dg-productcell-shipping">
                            <img src="<?php echo $cdn ?>img/<?php echo $freeImg;?>@2x.png"/>
                        </div>
                          <a id="productLink<?php echo $product_id ?>" href="/collections/<?php echo $newDeals['collection_url'] ?>/products/<?php echo $productInfo['seo_url'] ?>"><img alt="<?php echo htmlspecialchars_decode($productInfo['title']) ?>" style="width:100%;border-radius: 5px 5px 0px 0px;-moz-border-radius: 5px 5px 0px 0px;-webkit-border-radius: 5px 5px 0px 0px;" src="<?php echo $img; ?>"></a>
                    	<div class="dg-productcell-info">
                        	<div class="dg-productcell-title"><a href="/collections/<?php echo $newDeals['collection_url'] ?>/products/<?php echo $productInfo['seo_url'] ?>"><?php echo htmlspecialchars_decode($productInfo['title']) ?></a></div>
                        	<div class="dg-productcell-dash"></div>
                        	<div>
                            	<span class="dg-productcell-price"><?php echo $currency . $productInfo['price'] / 100 ?></span>
                            	<span class="dg-productcell-was"><?php echo $currency . $productInfo['original'] / 100 ?></span>
                            	<?php if (array_key_exists('endTime', $productInfo)&&!empty($productInfo['endTime'])): ?>
                            		<span class="dg-productcell-countdown"> <span class="icon-clock" data-countdown="<?php echo $productInfo['endTime'] ?>">22 days 06:31:22</span>&nbsp;<span id="countd" style="color: #00B6C6;font-family: 'icomoon';" class="countd"></span></span>
                            	<?php endif; ?>
                        	</div>
                    	</div>
                	</div>
                    <?php endforeach; ?>
                    
                    
                <?php endif; ?>
                </div>
                <?php if ( count($newDeals['allow'])>7): ?>
                    <div class="next">
                        <button type="button" data-theme="c"><i class="icon-arrow-d" style="font-size: 1em;font-weight: 700"></i> Next Page </button>
                    </div>

                    <div class="loading" style="display:none;">
                        <button type="button" data-theme=""c><span class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></span> Loading.. </button>
                    </div>
                
                <?php endif; ?>
                </div>
                
               <?php echo $foot; ?>       
        </div>
        <script>

            fbq('track', 'ViewContent');

            showcount();
            function showcount() {
                if ($('.dg-productcell span').hasClass('countd')) {
                    /*function ShowTimes() {
                        var c = new Date();
                        var q = parseInt(c.getMilliseconds() / 10);
                        if (q < 10) {
                            q = "0" + q;
                        }
                        countd.innerHTML = q;
                    }
                    var tinterval = setInterval(ShowTimes, 10);*/

                    $('[data-countdown]').each(function () {
                        var $this = $(this), finalDate = $(this).data('countdown');
                        var lday = ($(this).data('countdown') - Date.parse(new Date())) / 1000 / 60 / 60 / 24;
                        var hours = parseInt(($(this).data('countdown') - Date.parse(new Date())) / 1000 / 60 / 60);

                        $this.countdown(finalDate, function (event) {
                            if (lday > 2) {
                                //clearInterval(tinterval);
                                $this.html(event.strftime('%D days %H:%M:%S'));
                            } else if (1 < lday && lday <= 2) {
                                if (hours < 1) {
                                    hours = '00'
                                }
                                ;
                                $this.html(event.strftime(hours + ':%M:%S'));
                            } else {
                                $this.html(event.strftime('%H:%M:%S'));
                               // setInterval(ShowTimes, 10);
                                if (event.elapsed) {
                                    //clearInterval(tinterval);
                                    countd.innerHTML = '';
                                }
                            }
                            ;
                        });
                    });
                }
            }
            $(function(){
                $('.next').click(function () {
                    pageCount = 0;
                    loadProduct();
                })
                
                //Initialize Swiper 
                var swiper = new Swiper('.swiper-container', {
                    pagination: '.swiper-pagination',
                    paginationClickable: true,
                    autoplay: 3000,
                    autoplayDisableOnInteraction: false
                });
        
            })
            var ifLoading = false;

    function loadProduct() {
        if (ifLoading == false) {
            ifLoading = true;
            $(".next").toggle();
            $(".loading").toggle();
            $.post('/home/loadPage', {
                offset: $('.ui-content').data('offset')
            }, function (result) {
                if (result.success) {
                    var rows = '';
                    $('.ui-content').data('offset', result.offset);
                    $.each(result.productList, function (i, rowdata) {
                        if (rowdata.endTime) {
                            var countdown = '<span class="icon-clock"  data-countdown="' + rowdata.endTime + '"></span>&nbsp;<span id="countd" class="countd">';
                        } else {
                            var countdown = '';
                        }
                        if (rowdata.children > 0 || rowdata.bundletype > 0) {
                            var showModel = 'data-toggle="modal"  data-target="#dg-main-product"';
                        } else {
                            var showModal = '';
                        }
                        if(rowdata.freebies==1){
                            var freeImg = "freebie";
                        }else{
                            var freeImg = "free-shipping";
                        }
                        rows = '<div class="dg-productcell">' +
                                '<div class="dg-productcell-shipping">'+
                                '<img src="<?php echo $cdn ?>img/'+freeImg+'@2x.png"/>'+
                                '</div>'+
                                '<a id="productLink' + i + '" href="/collections/' + result.collection_seo + '/products/' + rowdata.seo_url + '">' +
                                '<img src="<?php echo IMAGE_DOMAIN ?>' + rowdata.image + '"  style="width:100%;border-radius: 5px 5px 0px 0px;-moz-border-radius: 5px 5px 0px 0px;-webkit-border-radius: 5px 5px 0px 0px;">' +
                                '</a>' +
                                '<div class="dg-productcell-info">' +
                                '<a href="/collections/' + result.collection_seo + '/products/' + rowdata.seo_url + '">' + rowdata.title + '</a>' +
                                '<div class="dg-productcell-dash"></div>' +
                                '<div>' +
                                '<span class="dg-productcell-price">' + "<?php echo $currency ?>" + rowdata.price / 100 + '</span> ' +
                                '<span class="dg-productcell-was">' + "<?php echo $currency ?>" + rowdata.original / 100 + '</span>' +
                                '<span class="dg-productcell-countdown">' +
                                countdown +
                                '</span>' +
                                '</div>' +
                                '</div>' +
                                '</div>';
                        $('.dg-index-product').append(rows);
                    });

                    //Ajax Loading Unlock
                    ifLoading = false;
                    $(".next").toggle();
                    $(".loading").toggle();

                    //Ajax Scrollspy Position Refresh
                    //Waypoint.refreshAll();

                    pageCount++;

                    showcount();
                } else {
                    //hide the button
                    $(".next").hide();
                    $(".loading").hide();
                }

            }, 'json');
        }

    }
            
        </script>
        <?php if (isset($countrySEO)) echo $countrySEO ?>
    </body>
</html>