<?php echo $head; ?>
<div role="main" class="ui-content dg-productlist"  data-action="<?php echo $search_word ?>" data-offset="12">
    <ol class="breadcrumb">
        <li><a href="/" class="ui-link">DrGrab</a></li><li> &gt; </li><li class="active"><a href="#">Results for '<?=$search_word?>'</a></li>   
    </ol>

    <div class="dg-productlist-filter">
        <div class="ui-grid-solo">
            <div class="ui-block-a">
                <select class="selectpicker" data-width="140px" name="sort" id="sort" data-theme="c" data-icon="order" data-mini="true">
                    <option value="manual" <?php if ($doc['sort'] == "manual") echo 'selected="selected"' ?>>Featured</option>
                    <option value="sold.total,-1" <?php if ($doc['sort'] == "sold.total,-1") echo 'selected="selected"' ?>>Best Selling</option>
                    <option value="price,-1" <?php if ($doc['sort'] == "price,-1") echo 'selected="selected"' ?>>By price: ↓</option>
                    <option value="price,1" <?php if ($doc['sort'] == "price,1") echo 'selected="selected"' ?>>By price: ↑</option>
                    <option value="create_time,-1" <?php if ($doc['sort'] == "create_time,-1") echo 'selected="selected"' ?>>Newest to oldest</option>
                    <option value="create_time,1" <?php if ($doc['sort'] == "create_time,1") echo 'selected="selected"' ?>>Oldest to newest</option>
                </select>
            </div>
        </div>
        <div class="ui-grid-a" >
            <div class="ui-block-a">
                <select class="selectpicker" data-width="140px" id="tag1" data-theme="c" data-icon="filter" data-mini="true">
                    <option value="ALL">Price Filter</option>
                    <option value="<?php echo urlencode($currency) . '0 - ' . urlencode($currency) . '9.99' ?>" <?php if (($currency) . '0 - ' . ($currency) . '9.99' == $doc['filter1']) echo 'selected="selected"' ?>><?php echo $currency ?>0 - <?php echo $currency ?>9.99</option>
                    <option value="<?php echo urlencode($currency) . '10 - ' . urlencode($currency) . '19.99' ?>" <?php if (($currency) . '10 - ' . ($currency) . '19.99' == $doc['filter1']) echo 'selected="selected"' ?>><?php echo $currency ?>10 - <?php echo $currency ?>19.99</option>
                    <option value="<?php echo urlencode($currency) . '20 - ' . urlencode($currency) . '29.99' ?>" <?php if (($currency) . '20 - ' . ($currency) . '29.99' == $doc['filter1']) echo 'selected="selected"' ?>><?php echo $currency ?>20 - <?php echo $currency ?>29.99</option>
                    <option value="<?php echo urlencode($currency) . '30 - ' . urlencode($currency) . '39.99' ?>" <?php if (($currency) . '30 - ' . ($currency) . '39.99' == $doc['filter1']) echo 'selected="selected"' ?>><?php echo $currency ?>30 - <?php echo $currency ?>39.99</option>
                    <option value="<?php echo urlencode($currency) . '40 - ' . urlencode($currency) . '69.99' ?>" <?php if (($currency) . '40 - ' . ($currency) . '69.99' == $doc['filter1']) echo 'selected="selected"' ?>><?php echo $currency ?>40 - <?php echo $currency ?>69.99</option>
                    <option value="<?php echo urlencode($currency) . '70 - ' . urlencode($currency) . '99.99' ?>" <?php if (($currency) . '70 - ' . ($currency) . '99.99' == $doc['filter1']) echo 'selected="selected"' ?>><?php echo $currency ?>70 - <?php echo $currency ?>99.99</option>
                    <option value="<?php echo urlencode($currency) . '100 - ' . urlencode($currency) . '199.99' ?>" <?php if (($currency) . '100 - ' . ($currency) . '199.99' == $doc['filter1']) echo 'selected="selected"' ?>><?php echo $currency ?>100 - <?php echo $currency ?>199.99</option>
                    <option value="<?php echo urlencode($currency) . '200' ?>" <?php if (($currency) . '200' == $doc['filter1']) echo 'selected="selected"' ?>><?php echo $currency ?>200+</option>

                </select>
            </div>
            <div class="ui-block-b">
                <select class="selectpicker" data-width="140px" id="tag2" data-theme="c" data-icon="filter" data-mini="true">
                    <option value="ALL">Product Filter</option>
                    <?php foreach ($doc['tag2'] as $tag) : ?>
                        <?php if ($tag): ?>
                            <option value="<?php echo urlencode($tag) ?>" <?php if ($tag == $doc['filter2']) echo 'selected="selected"' ?>><?php echo $tag ?></option>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
    </div>
    
    <?php if($doc['allow']):?>
    <div class="dg-main-list-product">                
        <?php foreach ($doc['allow'] as $product_id => $productInfo) : ?>
        <?php
        if($productInfo['freebies']==1){
                                $freeImg = "freebie";
                            }else{
                                $freeImg = "free-shipping";
                            }
        ?>
            <div class="dg-productcell" style="position: relative">
                <div class="dg-productcell-shipping">
                    <img src="<?php echo $cdn ?>img/<?php echo $freeImg;?>@2x.png"/>
                </div>
                <a id="productLink<?php echo $product_id ?>" href="/collections/<?php echo $productInfo['collection_url'] ?>/products/<?php echo $productInfo['seo_url'] ?>">
                    <img alt="<?php echo htmlspecialchars_decode($productInfo['title']) ?>" src="<?php echo IMAGE_DOMAIN . $productInfo['image'] ?>"  style="width:100%;border-radius: 5px 5px 0px 0px;-moz-border-radius: 5px 5px 0px 0px;-webkit-border-radius: 5px 5px 0px 0px;">
                </a>
                <div class="dg-productcell-info">
                    <div class="dg-productcell-title"><a href="/collections/<?php echo $productInfo['collection_url'] ?>/products/<?php echo $productInfo['seo_url'] ?>"><?php echo htmlspecialchars_decode($productInfo['title']) ?></a></div>
                    <div class="dg-productcell-dash"></div>
                    <div>
                        <span class="dg-productcell-price"><?php echo $currency . $productInfo['price'] / 100 ?></span>
                        <span class="dg-productcell-was"><?php echo $currency . $productInfo['original'] / 100 ?></span>
                        <?php if (array_key_exists('endTime', $productInfo)&&!empty($productInfo['endTime'])): ?>
                            <span class="dg-productcell-countdown">
                                <span class="icon-clock" data-countdown="<?php echo $productInfo['endTime'] ?>"></span>&nbsp;<span id="countd" class="countd"></span>
                            </span>
                        <?php endif; ?>  
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    
    <?php if ( count($doc['allow'])>11 ): ?>
    <div class="next">
        <button type="button" data-theme="c"><i class="icon-arrow-d" style="font-size: 1em;font-weight: 700"></i> Next Page </button>
    </div>

    <div class="loading" style="display:none;">
        <button type="button" data-theme=""c><span class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></span> Loading.. </button>
    </div>
    <?php endif; ?>
    
    <?php else: ?>
                <div class="dg-noresult">
                    <i class="icon-confused"></i>
                    <br><br>
                    <div>Sorry, No Products Found</div>
                </div>
    <?php endif; ?>

</div>
<?php echo $foot; ?>
</div>


<script>

    fbq('track', 'Search');

    //sorting function
    $('.dg-main-list-product').highlight('<?=$search_word?>');
    $('#sort').change(function (e) {
        var url = '/search/' + $('.dg-productlist').data('action') + '/' + $('#sort').val() + '/' + $('#tag1').val() + '/' + $('#tag2').val();
        self.location = url;
    });

    $('#tag1').change(function (e) {
        var url = '/search/' + $('.dg-productlist').data('action') + '/' + $('#sort').val() + '/' + $('#tag1').val() + '/' + $('#tag2').val();
        self.location = url;
    });

    $('#tag2').change(function (e) {
        var url = '/search/' + $('.dg-productlist').data('action') + '/' + $('#sort').val() + '/' + $('#tag1').val() + '/' + $('#tag2').val();
        self.location = url;
    });



    //Ajax New Product Auto-Load, pageCount = 3 so user have to click 'next page' for the first time.
    pageCount = 3;

    /* $('.next').waypoint({
     handler: function (direction) {
     if (direction == "down") {
     if (pageCount < 3) {
     loadProduct();
     }
     }
     },
     offset: 'bottom-in-view'
     }) */

    //Ajax Product Load
    $('.next').click(function () {
        pageCount = 0;
        loadProduct();
    })

    ifLoading = false;

    function loadProduct() {
        if (ifLoading == false) {
            ifLoading = true;
            $(".next").toggle();
            $(".loading").toggle();
            $.post('/search/loadPage', {
                offset: $('.dg-productlist').data('offset'),
                seo_url: $('.dg-productlist').data('action'),
                sort: $('#sort').val(),
                tag1: $('#tag1').val(),
                tag2: $('#tag2').val()
            }, function (result) {
                if (result.success) {
                    var rows = '';
                    var rows2 = '';
                    $('.dg-productlist').data('offset', result.offset);
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
                                '<a id="productLink' + i + '" href="/collections/' + rowdata.collection_url + '/products/' + rowdata.seo_url + '">' +
                                '<img src="<?php echo IMAGE_DOMAIN ?>' + rowdata.image + '"  style="width:100%;border-radius: 5px 5px 0px 0px;-moz-border-radius: 5px 5px 0px 0px;-webkit-border-radius: 5px 5px 0px 0px;">' +
                                '</a>' +
                                '<div class="dg-productcell-info">' +
                                '<a href="/collections/' + rowdata.collection_url + '/products/' + rowdata.seo_url + '">' + rowdata.title + '</a>' +
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
                        $('.dg-main-list-product').append(rows);
                    });

                    //Ajax Loading Unlock
                    ifLoading = false;
                    $(".next").toggle();
                    $(".loading").toggle();

                    //Ajax Scrollspy Position Refresh
                    //Waypoint.refreshAll();

                    pageCount++;
                    $('.dg-main-list-product').highlight('<?=$search_word?>');
                    showcount();
                } else {
                    //hide the button
                    $(".next").hide();
                    $(".loading").hide();
                }

            }, 'json');
        }

    }
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
                        //setInterval(ShowTimes, 10);
                        if (event.elapsed) {
                           // clearInterval(tinterval);
                            countd.innerHTML = '';
                        }
                    }
                    ;
                });
            });
        }
    }


</script>        
<?php if (isset($countrySEO)) echo $countrySEO ?>
</body>
</html>