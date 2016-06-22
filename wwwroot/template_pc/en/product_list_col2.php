<?php echo $head; ?>
<link href="<?php echo $cdn ?>css/star-rating.min.css" rel="stylesheet">
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
                        <span class="dg-main-product-core-panel-price-current"><?php echo $currency ?><span id="price"></span></span>
                        <span class="dg-main-product-core-panel-price-was"><del><?php echo $currency ?><span id="original"></span></del></span>
                    </div>
                    <div class="dg-main-product-core-panel-instock"> <span style="color:#00B6C8;">In Stock.</span> Dispatch in 2-3 business days</div>
                    <form id="upa_attr">
                        <input type="hidden" value="" id="product_id" name="product_id">
                        <input type="hidden" value="" id="product_sku" name="product_sku">
                        <input type="hidden" value="" id="product_attr" name="product_attr">
                        <input type="hidden" value="" id="product_bundle" name="product_bundle">
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
                <button type="button" class="btn pull-right btnCart" id="btnAddToCart" data-image="" >Add to Cart</button>
                <!--<button type="button" class="btn btn-danger btncart" data-dismiss="modal" id="btnAddToCart" disabled="disabled">Add to Cart</button>-->
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<div class="dg-breadcrumb-shadow clearfix">
    <div class="dg-breadcrumb" data-action="<?php echo $doc['seo_url'] ?>" data-offset="<?php echo $doc['offset'] ?>">
    <div class="container">
        <div class="row">
            <div class="col-xs-12">
                <div class="col-xs-6">
                    <ol class="breadcrumb">
                        <?= $breadcrumb ?>
                    </ol>
                </div>
                <div class="dg-main-list-panel-filter pull-right" style="margin-top:-5px;">
                            <span class="glyphicon glyphicon-sort" aria-hidden="true"></span>
                            <select class="selectpicker" data-width="165px" name="sort" id="sort">
                                <option value="manual" <?php if ($doc['sort'] == "manual") echo 'selected="selected"' ?>>Featured</option>
                                <option value="sold.total,-1" <?php if ($doc['sort'] == "sold.total,-1") echo 'selected="selected"' ?>>Best Selling</option>
                                <option value="price,-1" <?php if ($doc['sort'] == "price,-1") echo 'selected="selected"' ?>>By price: ↓</option>
                                <option value="price,1" <?php if ($doc['sort'] == "price,1") echo 'selected="selected"' ?>>By price: ↑</option>
                                <option value="create_time,-1" <?php if ($doc['sort'] == "create_time,-1") echo 'selected="selected"' ?>>Newest to oldest</option>
                                <option value="create_time,1" <?php if ($doc['sort'] == "create_time,1") echo 'selected="selected"' ?>>Oldest to newest</option>
                            </select>
                        </div>
                <div class="dg-main-list-panel-filter pull-right" style="margin-top:-5px;">
                            <select class="selectpicker" data-width="140px" id="tag1">
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
                <div class="dg-main-list-panel-filter pull-right" style="margin-top:-5px;">
                            <span class="glyphicon glyphicon-filter" aria-hidden="true"></span>
                            <select class="selectpicker" data-width="140px" id="tag2">
                                <option value="ALL">Product Filter</option>
                                <?php foreach ($doc['tag2'] as $tag) : ?>
                                    <?php if ($tag): ?>
                                        <option value="<?php echo urlencode(str_replace('/', utf8_encode('中'), $tag)) ?>" <?php if ($tag == $doc['filter2']) echo 'selected="selected"' ?>><?php echo $tag ?></option>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </select>
                        </div>
            </div>
        </div>
    </div>
</div>
</div>
<div class="dg-main">
    <div class="container">
        <div class="row">
            <div class="col-xs-10">
                <div class="dg-main-list-panel clearfix">
                    <div class="dg-main-list-panel-title"><?php echo htmlspecialchars_decode($doc['title']) ?><br/></div>
                    <div class="dg-main-list-panel-description"><?php echo htmlspecialchars_decode($doc['description']) ?></div>
                    
                    
                </div>

                <?php if ($doc['allow']): ?>
                    <div class="dg-main-list-product clearfix" id="productList">
                        <?php foreach ($doc['allow'] as $product_id => $productInfo) : ?>
                            <?php
                            $img = 'http:' . IMAGE_DOMAIN . '/product/' . $productInfo['sku'] . '/' . $productInfo['sku'] . '.jpg';
                            if (!@fopen($img, 'r')) {
                                $img = IMAGE_DOMAIN . $productInfo['image'];
                            }
                            if ($productInfo['freebies'] == 1) {
                                $freeImg = "freebie";
                            } else {
                                $freeImg = "free-shipping";
                            }
                            ?>
                            <div class="dg-main-index-product-item2">
                                <div class="dg-main-index-product-item-image" style="position: relative;">
                                    <div class="dg-main-index-product-item-image-shipping">
                                        <a id="productLink<?php echo $product_id ?>" href="/collections/<?= $doc['seo_url'] ?>/products/<?= $productInfo['seo_url'] ?>">
                                            <img src="<?php echo $cdn ?>image/<?php echo $freeImg; ?>.png"/>
                                        </a>
                                    </div>
                                    <a id="productLink<?php echo $product_id ?>" href="/collections/<?= $doc['seo_url'] ?>/products/<?= $productInfo['seo_url'] ?>">
                                        <img alt="<?php echo htmlspecialchars_decode($productInfo['title']) ?>" src="<?php echo $img; ?>">
                                    </a>
                                    <?php if (!$productInfo['diy']) : ?>
                                    <div class="dg-main-index-product-item-image-button">
                                        <button type="button" class="dg-main-index-product-item-image-button-bnt btn btn-dg-pop-btn pull-left" <?php if ($productInfo['children'] > 0 || $productInfo['bundletype'] > 0) echo 'data-toggle="modal"  data-target="#dg-main-product"' ?> data-bundletype="<?php echo $productInfo['bundletype'] ?>" data-productid="<?php echo $product_id ?>" data-children="<?php echo $productInfo['children'] ?>" data-action="Buy Now">Buy Now</button>
                                        <button type="button" class="dg-main-index-product-item-image-button-bnb btn btn-dg-pop-btn pull-right" <?php if ($productInfo['children'] > 0 || $productInfo['bundletype'] > 0) echo 'data-toggle="modal"  data-target="#dg-main-product"' ?> data-bundletype="<?php echo $productInfo['bundletype'] ?>" data-productid="<?php echo $product_id ?>" data-children="<?php echo $productInfo['children'] ?>" data-action="Add to Cart">Add to Cart</button>
                                    </div>
                                    <?php endif;?>
                                </div>
                                <div class="dg-main-index-product-item-detail">
                                    <div class="dg-main-index-product-item-detail-title">
                                        <a href="/collections/<?= $doc['seo_url'] ?>/products/<?= $productInfo['seo_url'] ?>"><?php echo htmlspecialchars_decode($productInfo['title']) ?></a>
                                    </div>
                                    <div class="dg-main-index-product-item-detail-bottom">
                                        <div class="dg-main-index-product-item-detail-price pull-left">
                                            <?php echo $currency . $productInfo['price'] / 100 ?><del><?php echo $currency . $productInfo['original'] / 100 ?></del>
                                        </div>
                                        <span class="dg-main-index-product-item-detail-bottom-countdown pull-left">
                                            <?php if (array_key_exists('endTime', $productInfo) && !empty($productInfo['endTime'])): ?>
                                                <img src="<?php echo $cdn ?>image/index/countdown.png"> <span data-countdown="<?php echo $productInfo['endTime'] ?>"> 6 days : 2h : 6m</span>&nbsp;<span id="countd" class="countd"></span>
                                            <?php endif; ?>

                                        </span>
                                        <a href="/collections/<?= $doc['seo_url'] ?>/products/<?= $productInfo['seo_url'] ?>"><span class="dg-main-index-product-item-detail-bottom-sold pull-right"><?php echo $productInfo['sold']['total'] ?> sold</span></a>
                                    </div>
                                </div>
                                <?php if ($productInfo['children'] == 0): ?><div class="dg-main-index-product-item-detail-adding" id="productDetail<?php echo $product_id ?>" data-sku="<?php echo $productInfo['sku'] ?>" data-title="<?php echo htmlspecialchars_decode($productInfo['title']) ?>" data-image="<?php echo $productInfo['image']; ?>"></div><?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <?php if (count($doc['allow']) > 11): ?>
                        <div class="next">
                            <button type="button" class="btn btn-default btn-lg btn-block"><i class="fa fa-chevron-down"></i> Next Page </button>
                        </div>

                        <div class="loading" style="display:none;">
                            <button type="button" class="btn btn-default btn-lg btn-block"><span class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></span> Loading.. </button>
                        </div>
                    <?php endif; ?>

                    <?php if (empty(!$collectionComment)) : ?>
                        <div class="dg-main-product-comment">
                            <div class="dg-title">Customer Reviews</div>
                            <?php foreach ($collectionComment as $comment): ?>
                                <div class="dg-main-product-comment-content">
                                    <div class="row">
                                        <div class="col-xs-7"><input value="<?= $comment['product_star'] ?>" type="number" class="rating form-control hide" min="0" max="5" step="0.5" data-size="xxs" data-disabled="true"></div>
                                        <div class="col-xs-3">Reviewed by: <?= substr($comment['commentator'], 0, 5) ?> *****</div>
                                        <?php if (strpos($comment['product_sku'], "/")) : ?>
                                            <div class="col-xs-2">option:<?= substr(strstr($comment['product_sku'], "/"), 1) ?></div>
                                        <?php endif; ?>
                                        <div class="clearfix"></div>
                                    </div>
                                    <p> <?= $comment['product_comment'] ?></p>
                                </div>
                            <?php endforeach ?> 
                        </div>
                    <?php endif; ?>

                <?php else: ?>

                    <div class="dg-noresult">
                        <i class="fa fa-meh-o"></i>
                        <div>Sorry, No Products Found</div>
                    </div>

                <?php endif; ?>
            </div>
            <?php echo $shoppingcart ?>
        </div>
        <div class="row">
            <div class="col-xs-10">
                <br>
                <div class="dg-main-list-panel-description2"><?php echo htmlspecialchars_decode($doc['description2']) ?></div>
            </div>
        </div>
    </div>
</div>  
<div id="flyItem" class="fly_item"><img src="" width="40" height="40"></div>

<?php echo $foot; ?>


<script>
    var currency = "<?php echo $currency; ?>";
    var cdn = "<?php echo $cdn ?>";
    var IMAGE_DOMAIN = "<?php echo IMAGE_DOMAIN ?>";
    var currentPage = <?php echo $currentPage ?>;
    var canonicalUrl = "<?php echo $canonical ?>";
</script>
<script>
    
    $(".dg-breadcrumb").affix({offset: 160})
    ifProductList = 63;
    $('.dg-main-product-core-panel-price').hide();
    $('#dg-main-product').on('shown.bs.modal', function () {
        $('#dg-main-product .modal-content').isLoading({
            text: "Updating",
            position: "overlay",
            class: "fa-refresh", // loader CSS class
            tpl: '<span class="isloading-wrapper %wrapper%">' + szimg + '</span>'
        });
    })
    
    fbq('track', 'ViewContent');

</script>
<script src="<?php echo $cdn ?>js/product_list2.js"></script>
<?php if (isset($countrySEO)) echo $countrySEO ?>

</body>
</html>
