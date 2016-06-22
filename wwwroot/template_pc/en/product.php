<?php echo $head; ?>
<link href="<?php echo $cdn ?>css/star-rating.min.css" rel="stylesheet">
<style>
    
    .dg-main-product-core-panel-selector-option div.disabled {
        border: 1px dashed #DEDEDE !important;
        color: #DEDEDE;
        background: #fcfcfc;
        cursor: not-allowed;
    }
    
</style>
<?php
/*
  <div class="modal fade" id="dg-main-product-bundle">
  <div class="modal-dialog">
  <div class="modal-content">
  <div class="modal-header">
  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
  <h4 class="modal-title">Product Bundle</h4>
  </div>
  <div class="modal-body row">

  <div class="col-sm-6">
  <img src="<?= IMAGE_DOMAIN ?>/product/<?=$pro['sku']?>/<?=$pro['sku']?>.jpg" alt="" width="100%">
  <div class="dg-main-product-modal-title"><?=$pro['title']?> </div>
  <div>
  <span class="dg-main-product-modal-price-current" id="bundle_0_p_price"><?=$currency.$pro['bundle']/100?></span>
  <span class="dg-main-product-modal-price-was"><del id="bundle_0_p_original"><?=$currency.$pro['original']/100?></del></span>
  </div>

  <div class="dg-main-product-modal-selector">
  <?php if ($data['is_variants']):?>
  <form id="bundle_0_upa_attr">
  <input type="hidden" value="<?=$pro['_id']?>" name="bundle_0_product_id"/>
  <input type="hidden" value="<?=$pro['sku']?>/" name="bundle_0_product_sku"/>
  <input type="hidden" value="0" name="bundle_index" id="bundle_index"/>
  <input type="hidden" value="" id="bundle_0_product_attr" name="bundle_0_product_attr"/>
  </form>
  <table>
  <?php foreach ($pro['variants'] as $variants): ?>
  <tr>
  <td class="dg-main-product-modal-selector-title"><?=$variants['option_map']?>:</td>
  <td>
  <div class="dg-main-product-modal-selector-option">
  <?php
  $mapping = explode(',',$variants['value_map']);
  ?>
  <?php foreach ($mapping as $value): ?>
  <div  data-val="<?=$value?>" ><?=$value?></div>
  <?php endforeach ?>
  </div>
  </td>
  </tr>
  <?php endforeach ?>
  </table>
  <?php endif; ?>
  </div>
  </div>




  <div class="col-sm-6">
  <img src="<?= IMAGE_DOMAIN ?>/product/<?=$bundlePro['sku']?>/<?=$bundlePro['sku']?>.jpg"  alt="" width="100%">
  <div class="dg-main-product-modal-title"><?=$bundlePro['title']?> </div>
  <div>
  <span class="dg-main-product-modal-price-current" id="bundle_1_p_price"><?=$currency.$bundlePro['bundle']/100?></span>
  <span class="dg-main-product-modal-price-was"><del id="bundle_1_p_original"><?=$currency.$bundlePro['original']/100?></del></span>
  </div>
  <div class="dg-main-product-modal-selector">
  <?php if ($data['$is_bundleVariants']):?>
  <form id="bundle_1_upa_attr">
  <input type="hidden" value="<?=$bundlePro['_id']?>" name="bundle_1_product_id"/>
  <input type="hidden" value="<?=$bundlePro['sku']?>/" name="bundle_1_product_sku"/>
  <input type="hidden" value="1" name="bundle_index" id="bundle_index"/>
  <input type="hidden" value="" id="bundle_1_product_attr" name="bundle_1_product_attr"/>
  </form>
  <table>
  <?php foreach ($bundlePro['variants'] as $variants): ?>
  <tr>
  <td class="dg-main-product-modal-selector-title"><?=$variants['option_map']?>:</td>
  <td>
  <div class="dg-main-product-modal-selector-option">
  <?php
  $mapping = explode(',',$variants['value_map']);
  ?>
  <?php foreach ($mapping as $value): ?>
  <div  data-val="<?=$value?>" ><?=$value?></div>
  <?php endforeach ?>
  </div>
  </td>
  </tr>
  <?php endforeach ?>
  </table>
  <?php endif; ?>
  </div>
  </div>




  <div class="clearfix"></div>
  </div>
  <div class="modal-footer">
  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
  <button type="button" class="btn btn-danger" data-dismiss="modal" >Add to Cart</button>
  </div>
  </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
  </div>
  <!-- /.modal -->
 */
?>


<div class="dg-breadcrumb">
    <div class="container">
        <div class="row">
            <div class="col-xs-12">
                <ol class="breadcrumb">
                    <?= $breadcrumb ?>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="dg-main">
    <div class="container">
        <div class="row">
            <div class="col-xs-10 col-xs-12" itemscope itemtype="http://schema.org/Product">
                <div class="dg-main-product-core">
                    <div class="row">
                        <div class="col-xs-6">
                            <div class="dg-main-product-core-slide">
                                <ul class="dg-main-product-core-slide-pic">
                                    <?php
                                    /* 暂不考虑排序
                                      foreach($pro['pics'] as $key => $vo){
                                      if(isset($vo['sort'])){
                                      $arr[$key] = $vo['sort'];
                                      }else{
                                      $arr[$key] = 0;
                                      }
                                      }
                                      asort($arr);
                                      foreach($arr as $key =>$vo){
                                      if($pro['pics'][$key]['img']!=NULL){
                                      echo '<li><img src="'.IMAGE_DOMAIN.$pro['pics'][$key]['img'].'" /></li>';
                                      }
                                      }
                                     */
                                    if ($pro['freebies'] == 1) {
                                        $freeImg = "freebie";
                                    }
                                    else {
                                        $freeImg = "free-shipping";
                                    }


                                    $pro['pics'] = arr_sort($pro['pics'], 'sort');
                                    if (!empty($pro['pics'])) {
                                        foreach ($pro['pics'] as $vo) {
                                            if ($vo['img'] != NULL) {
                                                echo '<li style="position: relative"><div class="dg-main-index-product-item-image-shipping"><img src="' . $cdn . 'image/' . $freeImg . '.png"/></div><img alt="' . htmlspecialchars_decode($pro['title']) . '" src="' . IMAGE_DOMAIN . $vo['img'] . '" /></li>';
                                            }
                                        }
                                    }
                                    ?>
                                </ul>
                                <div class="dg-main-product-core-slide-pager">
                                    <?php
                                    /*
                                      $i = 0;
                                      foreach($arr as $key =>$vo){
                                      if($pro['pics'][$key]['img']!=NULL){
                                      echo '<a data-slide-index="'.$i.'" href=""><img src="'.IMAGE_DOMAIN.$pro['pics'][$key]['img'].'" /></a>';
                                      $i++;
                                      }
                                      }
                                     */
                                    if (!empty($pro['pics'])) {
                                        $i = 0;
                                        foreach ($pro['pics'] as $vo) {
                                            if ($vo['img'] != NULL) {
                                                echo '<a data-slide-index="' . $i . '" href="javascript:void(0)"><img alt="' . htmlspecialchars_decode($pro['title']) . '" src="' . IMAGE_DOMAIN . $vo['img'] . '" /></a>';
                                                echo '<meta itemprop="image" content="' . IMAGE_DOMAIN . $vo['img'] . '">';
                                                $i++;
                                            }
                                        }
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-6">
                            <div class="dg-main-product-core-panel">
                                <div class="dg-main-product-core-panel-title" itemprop="name"><?= htmlspecialchars_decode($pro['title']) ?></div>
                                <div class="dg-main-product-core-panel-sku" itemprop="sku" style="display: none;"><?= $pro['sku'] ?></div>
                                <div class="dg-main-product-core-panel-price" itemprop="offers" itemscope itemtype="http://schema.org/Offer">
                                    <meta itemprop="availability" content="http://schema.org/InStock"/>
                                    <meta itemprop="priceCurrency" content="<?php echo $currency; ?>" />
                                    <span class="dg-main-product-core-panel-price-current" id="p_price" itemprop="price" content="<?php echo $pro['price'] / 100; ?>"><?= $currency . $pro['price'] / 100 ?></span>
                                    <span class="dg-main-product-core-panel-price-was"><del id="p_original"><?= $currency . $pro['original'] / 100 ?></del></span>
                                </div>

                                <div class="dg-main-product-core-panel-instock"> <span style="color:#00B6C8;"><?php if ($pro['status'] == 1): ?>In Stock.<?php else: ?>Out Of Stock<?php endif; ?></span> Dispatch in 2-3 business days

                                    <?php if ($pro['freebies'] == 1) : ?>
                                        <span class="dg-main-product-core-panel-instock-freebie">
                                            <br>An Additional Delivery Charge of <span style="color:#00B6C8;"><?= $currency ?><?php echo $pro['oprice'] / 100; ?></span> may apply to this item.</span>
                                        </span>
                                    <?php endif; ?>

                                </div>

                                <div class="dg-main-product-core-panel-countdown">

                                    <?php if (empty($pro['endTime'])): ?>
                                        <table class="dg-main-product-core-panel-countdown table table-bordered table-condensed table-curved">
                                            <tr class="dg-main-product-core-panel-countdown-head">
                                                <td>Deals Sold</td>
                                                <td>You Save</td>
                                            </tr>
                                            <tr class="dg-main-product-core-panel-countdown-data">
                                                <td><img src="<?php echo $cdn ?>image/product/countdown1.png"><?= $pro['sold']['total'] ?></td>
                                                <td><img src="<?php echo $cdn ?>image/product/countdown2.png"><span id="p_save"><?= $save ?></span>%</td>
                                            </tr>
                                        </table>
                                    <?php else: ?>
                                        <table class="dg-main-product-core-panel-countdown table table-bordered table-condensed table-curved">
                                            <tr class="dg-main-product-core-panel-countdown-head">
                                                <td>Deals Sold</td>
                                                <td>You Save</td>
                                                <td>The Deal Ends in</td>
                                            </tr>
                                            <tr class="dg-main-product-core-panel-countdown-data">
                                                <td><img src="<?php echo $cdn ?>image/product/countdown1.png"><?= $pro['sold']['total'] ?></td>
                                                <td><img src="<?php echo $cdn ?>image/product/countdown2.png"><span id="p_save"><?= $save ?></span>%</td>
                                                <td><img src="<?php echo $cdn ?>image/product/countdown3.png"><span id="hours"></span><span data-countdown="<?= $pro['endTime'] ?>" itemprop="priceValidUntil" datetime="<?php echo date('Y-m-d H:i', $pro['endTime'] / 1000); ?>"></span>&nbsp;<span id="countd" class="countd"></span></td>
                                            </tr>
                                        </table>
                                    <?php endif; ?> 


                                </div>



                                <div class="dg-main-product-core-panel-selector">
                                    <?php $attributes = array('id' => 'upa_attr'); ?>
                                    <?php echo form_open('cart/buy_now', $attributes); ?>
                                    <input type="hidden" value="<?= $pro['_id'] ?>" name="product_id"   id="cart_pid" />
                                    <input type="hidden" value="<?= $pro['sku'] ?>" name="product_sku" id="cart_sku" />
                                    <input type="hidden" value=""  name="product_attr" id="product_attr" />
                                    <input type="hidden" value="<?= $product_bundle ?>" id="product_bundle" name="product_bundle">
                                    <table>
                                        <?php if ($product_bundle == 1): ?>
                                            <?php if ($data['is_variants']):$i = 0; ?>
                                                <?php foreach ($pro['variants'] as $variants):$i++; ?>
                                                    <tr class="proattr" id="attr<?php echo $i;?>">
                                                        <td class="dg-main-product-core-panel-selector-title">
                                                            <?= $variants['option_map'] ?> :
                                                        </td>
                                                        <td>
                                                            <div class="dg-main-product-core-panel-selector-option">
                                                                <?php
                                                                $mapping = explode(',', $variants['value_map']);
                                                                $valueSku = explode(',', $variants['value']);
                                                                ?>
                                                                <?php foreach ($mapping as $index => $value): ?>
                                                                    <div data-nsl="0" data-val="<?= str_replace('"', '&quot;', $valueSku[$index]) ?>" ><?= $value ?></div>
                                                                <?php endforeach ?>
                                                            </div>                              
                                                        </td>
                                                    </tr>
                                                <?php endforeach ?>
                                            <?php endif; ?>
                                        <?php elseif ($product_bundle == 2): ?>

                                            <tr>
                                                <td class="dg-main-product-core-panel-selector-title">
                                                    Select Buy :
                                                </td>

                                                <td>
                                                    <div class="dg-main-product-core-panel-selector-option">
                                                        <?php foreach ($pro['plural'] as $plural): ?>
                                                            <div data-val="<?= $plural['number'] ?>" ><?= $plural['number'] ?></div>
                                                        <?php endforeach ?>
                                                    </div>                              
                                                </td>

                                            </tr>

                                        <?php elseif ($product_bundle == 3): ?> 
                                            <?php if ($data['is_variants']):$i=0; ?>
                                                <?php foreach ($pro['variants'] as $variants): $i++;?>
                                                    <tr class="proattr" id="attr<?php echo $i;?>">
                                                        <td class="dg-main-product-core-panel-selector-title">
                                                            <?= $variants['option_map'] ?> :
                                                        </td>
                                                        <td>
                                                            <div class="dg-main-product-core-panel-selector-option">
                                                                <?php
                                                                $mapping = explode(',', $variants['value_map']);
                                                                $valueSku = explode(',', $variants['value']);
                                                                ?>
                                                                <?php foreach ($mapping as $index => $value): ?>
                                                                    <div data-nsl="0" data-val="<?= str_replace('"', '&quot;', $valueSku[$index]) ?>" ><?= $value ?></div>
                                                                <?php endforeach ?>
                                                            </div>                              
                                                        </td>
                                                    </tr>
                                                <?php endforeach ?>
                                                <?php foreach ($pro['variants'] as $variants):$i++; ?>
                                                    <tr class="proattr" id="attr<?php echo $i;?>">
                                                        <td class="dg-main-product-core-panel-selector-title">
                                                            <?= $variants['option_map'] ?> :
                                                        </td>
                                                        <td>
                                                            <div class="dg-main-product-core-panel-selector-option">
                                                                <?php
                                                                $mapping = explode(',', $variants['value_map']);
                                                                ?>
                                                                <?php foreach ($mapping as $value): ?>
                                                                    <div data-nsl="0" data-val="<?= str_replace('"', '&quot;', $value); ?>" ><?= $value ?></div>
                                                                <?php endforeach ?>
                                                            </div>                              
                                                        </td>
                                                    </tr>
                                                <?php endforeach ?>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                        <tr>
                                            <td class="dg-main-product-core-panel-selector-title">Qty :</td>
                                            <td>
                                                <div class="qty_product">
                                                    <button title="Decrease Qty" onClick="qtyDown(398);
                                      return false;" class="decrease">-</button>   
                                                    <input id="qty" name="product_qty"  value="1" size="4" title="Qty" class="input-text product_qty" maxlength="12" readonly>
                                                    <button title="Increase Qty" onClick="qtyUp(398);
                                      return false;" class="increase">+</button>
                                                </div>
                                            </td>
                                        </tr>

                                    </table>
                                </div>

                                <div class="dg-main-product-core-panel-cta">
                                    <button type="button" class="btn dg-main-product-threedbtn btn-dg-threed" id="buy_now">Buy Now</button>
                                    <button type="button" class="btn dg-main-product-threedbtnb btn-dg-threed" id="add_cart">Add to Cart</button>
                                </div>
                                <?php echo form_close(); ?>



                                <form id="upa_collect">
                                    <div class="dg-main-product-core-panel-wishlist">
                                        <input type="hidden" value="<?= $pro['_id'] ?>" name="product_id"/>
               <!--                          <a href="javascript:void(0)" id="collect"><span class="glyphicon glyphicon-star-empty" aria-hidden="true"></span> Add to Wishlist</a> -->
                                    </div>
                                </form>

                                <div class="dg-main-product-core-panel-delivery">
                                    <div class="dg-main-product-core-panel-delivery-content">
                                        <h3>Delivery Methods</h3>
                                        <?php if (!empty($shipping)): ?>
                                            <?php foreach ($shipping as $shipping): ?>
                                                <?php if ($shipping['showType'] > 0) : ?>
                                                    <div class="left">
                                                        <p class="dg-main-product-core-panel-delivery-drgrab"><?= preg_replace('/\(.*?\)/', '', $shipping['name']) ?></p>
                                                        <p class="dg-main-product-core-panel-delivery-drgrab"><?php echo $shipping['price'] ? $currency . $shipping['price'] / 100 : 'Free'; ?></p>
                                                        <?php
                                                        $titles = explode('@', $shipping['title']);
                                                        ?>
                                                        <?php foreach ($titles as $title): ?>
                                                            <p ><?= $title ?></p>
                                                        <?php endforeach ?>   
                                                    </div>
                                                <?php endif; ?>
                                            <?php endforeach ?>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <div class="dg-main-product-core-panel-share">
                                    <!-- facebookShare -->
                                    <meta property="og:image" content="<?php echo isset($pro['pics'][0]) ? IMAGE_DOMAIN . $pro['pics'][0]['img'] : ''; ?>"/>
                                    <!-- facebookShare / -->
                                    <!-- Go to www.addthis.com/dashboard to generate a new set of sharing buttons -->
                                    <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo $site_url; ?>&t=<?php echo $pro['title'] ? urlencode($pro['title'] . ' | DrGrab') : ''; ?>" target="_blank">
                                        <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAFAAAABQCAYAAACOEfKtAAAABHNCSVQICAgIfAhkiAAAAAlwSFlzAAALEgAACxIB0t1+/AAAABZ0RVh0Q3JlYXRpb24gVGltZQAxMS8xMS8xNZitZ1EAAAAcdEVYdFNvZnR3YXJlAEFkb2JlIEZpcmV3b3JrcyBDUzbovLKMAAADkUlEQVR4nO2dP0gbURzHP4o4iKJQh9rFOLTQLmp1KcF2LhSMdQuUqsPRRczejnZPoUPJoMlyW2sEoXMpN7amS4W2YFzqUAoKVqhLO9xFo8nlznuad+/8fUbzcvflw/tz9yS/10FE0tnCGJABxoAUMBr1Wpr4AlSBClB2bKsS5SId52mczhZSQA5X3HCUG8aYHaAM5B3bqob9UiiB6WxhAMgDTyNFM48SkHNsay+oYaDAdLaQAYpAv3ouo9gH5hzbKrdq1FJgOlsocnV6nR8lx7bm/D5sKtAbskVg+nIyGcc6bm9sGNKdPl8oIvLqmcZ10kCDQG/YirxGpj03pzg1hL0FY61diQxlpn5hORbozXtVrt5qe172gVRtPqwfwnlEXhj6cV0BXg/03jC2NQUylRHHtqq1HpjTGsVMcnAyhDMag5hKBqDD21XZ1BzGVMa7iHHvm5pMcX9ymKHBPsZuDwW2X3n3mZW3n9qQ7JhMF+5+XqyYmkyx9OQe1wd7dUcJYqwLdzM0Njx/9oCHU7d0xwhLqpMY7SQvzE6YJA9g1G8zoe3cHL7GwuO7umOcm9gIXJid0B0hErEQ2NvTzdSEmf9iiYXA8Ts3dEeITJfuAODOf2E5ODxi8+tPvu/8bvhsc2v3ImOFwjiBi8sbTeXpIhZDuK+nO1S7ytZurORBTASajAhURAQqIgIV6UhnC//aecPXLx6F2pqKwvuP33j55sOlXNuPRPXA3V8Hbb9nogTqIFECdTwjJkrgwZ+/bb9nogTqIFECdWwmJEqgDhIj8ODwSMt9EyPwh6Zdmra/iTQj7NvJ4vKGlnmuFYnpgboQgYqIQEVEoCIiUBERqIgIVEQEKiICFRGBiohARUSgIiJQERGoiAhURAQqIgIVEYGKiEBFRKAiIlCRTtzqZUI0Kp24VcuEaOxID1SjIj/5V2O8VvZkm5j98NoAqo5tjdRW4XWtUcxkHU4eY/ItGgrNyYMn0KsZWtKZxjBKtTqr9Q/SOSCwZqjAHnWVno4FetXI5nUkMoz5+kqWp17lvLp4xXYnMoji2aK0fjVU14hxRSNNlB3bmjn7R7/NhHncotSCSxmf6S2oDPIqMHcJgUyi6NiW79oQthD3KjBwkakMYA93wWg5EgP3A70LjHC1FpciboXKwGksymEES7gLTCpCsDhTxZ3rXl34YQTN8HZxpjk5DiN2ZfQCqHByHMZ61OMw/gMFadQS1rEv4AAAAABJRU5ErkJggg==" border="0" alt="Facebook"/>
                                    </a>
                                    <a href="https://twitter.com/intent/tweet?text=<?php echo $pro['title'] ? urlencode($pro['title'] . ' | DrGrab') : ''; ?>&url=<?php echo $site_url; ?>&related=" target="_blank">
                                        <img src="data:image/png;base64,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" border="0" alt="Twitter"/>
                                    </a>
                                    <a href="https://www.pinterest.com/pin/create/button/?url=<?php echo $site_url; ?>&media=<?php echo isset($pro['pics'][0]) ? urlencode(IMAGE_DOMAIN . $pro['pics'][0]['img']) : ''; ?>&description=<?php echo isset($description) ? $description : ''; ?>" target="_blank">
                                        <img src="data:image/png;base64,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" border="0" alt="Pinterest"/>
                                    </a>
                                    <a href="https://plus.google.com/share?url=<?php echo $site_url; ?>&t=<?php echo $pro['title'] ? urlencode($pro['title'] . ' | DrGrab') : ''; ?>" target="_blank">
                                        <img src="data:image/png;base64,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" border="0" alt="Google+"/>
                                    </a>
                                    <!-- <a href="https://www.addthis.com/bookmark.php?source=tbx32nj-1.0&v=300&url=<?php echo $site_url; ?>" target="_blank">
                                      <img src="https://cache.addthiscdn.com/icons/v2/thumbs/32x32/addthis.png" border="0" alt="Addthis"/>
                                    </a> -->
                                </div>


                            </div>
                        </div>     
                    </div>
                </div>

                <?php
                /*
                  <div class="dg-main-product-bundle">
                  <div class="row">
                  <div class="col-xs-12">
                  <div class="dg-main-product-bundle-image">
                  <img src="<?php echo $cdn ?>image/product/bundle.png">
                  </div>

                  <div class="dg-main-product-bundle-core">
                  <a href="/product/index/<?=$pro['_id']?>" target="_black"><img class="dg-main-product-bundle-core-item" src="<?= IMAGE_DOMAIN ?><?=$pro['image']?>" ></a>
                  <img class="dg-main-product-bundle-core-symbol" src="<?php echo $cdn ?>image/product/bundle-plus.png" >
                  <a href="/product/index/<?=$bundlePro['_id']?>" target="_black"><img class="dg-main-product-bundle-core-item" src="<?= IMAGE_DOMAIN ?><?=$bundlePro['image']?>" ></a>
                  <img class="dg-main-product-bundle-core-symbol" src="<?php echo $cdn ?>image/product/bundle-equal.png" >
                  <div class="dg-main-product-bundle-core-cta">
                  <button type="button" class="btn btn-dg-cta" data-toggle="modal" data-target="#dg-main-product-bundle">Buy Bundle<br></button>
                  <br>
                  <span class="dg-main-product-bundle-core-cta-yousave">Additional <span style="color:#00B6C8"><?=$data['bundle_save']?>%</span> off!</span>
                  </div>
                  </div>

                  </div>
                  </div>
                  </div>
                 */
                ?>
                <?php if (!empty(strip_tags(htmlspecialchars_decode($pro['topreview']), '<img>'))): ?>
                    <div class="dg-main-product-topreview">
                        <img src="<?php echo $cdn ?>image/product/quote.png" class="dg-main-product-topreview-quote">
                        <?php echo htmlspecialchars_decode($pro['topreview']); ?>
                    </div>
                <?php endif; ?>
                <div class="row">
                    <div class="col-sm-9">
                        <div class="dg-main-product-con">
                            <ul class="nav nav-tabs" role="tablist">
                                <li role="presentation" class="active"><a href="#description" aria-controls="home" role="tab" data-toggle="tab"> Description</a></li>
              <!--                   <li role="presentation"><a href="#reviews" aria-controls="profile" role="tab" data-toggle="tab"> <i class="fa fa-info-circle"></i> Specifications</a></li> -->
                                <li role="presentation"><a href="#shipping" aria-controls="messages" role="tab" data-toggle="tab"><i class="fa fa-plane"></i> Shipping</a></li>
                                <li role="presentation"><a href="#payment" aria-controls="messages" role="tab" data-toggle="tab"><i class="fa fa-credit-card"></i> Payment</a></li>
                            </ul>

                            <!-- Tab panes -->
                            <div class="tab-content dg-main-product-description">
                                <div role="tabpanel" class="tab-pane active" id="description" itemprop="description">
                                    <?= htmlspecialchars_decode($pro['description']) ?>
                                </div>
              <!--                   <div role="tabpanel" class="tab-pane" id="reviews"><?php echo htmlspecialchars_decode($pro['specification']) ?></div> -->
                                <div role="tabpanel" class="tab-pane" id="shipping"><?php echo $desc_shipping['pages_content']; ?></div>
                                <div role="tabpanel" class="tab-pane" id="payment"><?php echo $desc_payment['pages_content']; ?></div>
                            </div>
                        </div>

                        <?php if (empty(!$comments)) : ?>
                            <div class="dg-main-product-comment">
                                <div class="dg-title">Customer Reviews</div>
                                <?php foreach ($comments as $comment): ?>
                                    <div class="dg-main-product-comment-content">
                                        <input value="<?= $comment['product_star'] ?>" type="number" class="rating" min=0 max=5 step=0.5 data-size="xxs" data-disabled="true" >
                                        <div class="row">
                                            <div class="col-xs-4">Reviewed by: <?= substr($comment['commentator'], 0, 5) ?> *****</div>
                                            <?php if ($comment['product_sku']) : ?>
                                                <div class="col-xs-5">option:<?= $comment['product_sku'] ?></div>
                                            <?php endif; ?>
                                            <div class="clearfix"></div>
                                        </div>
                                        <p>
                                            <?= $comment['product_comment'] ?>
                                        </p>
                                    </div> 
                                <?php endforeach ?>
                            </div>
                        <?php endif; ?>

                        <!-- 
                                        <div class="dg-main-product-description" itemprop="description">
                                          <?//=htmlspecialchars_decode($pro['description'])?>
                                        </div> 
                        -->

                        <div class="dg-main-product-contactus">
                            <a href="/pages/faq"><img src="<?php echo $cdn ?>image/product/support.png"></a>
                        </div>
                    </div>

                    <div class="col-sm-3 dg-no-left-padding">
                        <div class="dg-main-product-related">
                            <div class="dg-title"> Up For Grabs</div>


                            <?php if (isset($specialProduct) && !empty($specialProduct)): ?>
                                <?php foreach ($specialProduct as $key => $special): ?>
                                    <?php if ($pro['_id'] != $key): ?>
                                        <?php
                                        if ($special['freebies'] == 1) {
                                            $special['price'] = 0;
                                        }
                                        ?>
                                        <div class="dg-main-product-related-item">
                                            <a href="/collections/<?= $return['seo_url'] ?>/products/<?= $special['seo_url'] ?>"><img alt="<?php echo $special['title']; ?>" src="<?= IMAGE_DOMAIN ?><?= $special['image'] ?>"></a>
                                            <div class="dg-main-product-related-item-title">
                                                <a href="/collections/<?= $return['seo_url'] ?>/products/<?= $special['seo_url'] ?>">
                                                    <?= $special['title'] ?>
                                                </a>
                                            </div>
                                            <div class="dg-main-product-related-item-detail">
                                                <div class="dg-main-product-related-item-detail-price">
                                                    <span><?= $currency . $special['price'] / 100 ?></span>
                                                    <del><?= $currency . $special['original'] / 100 ?></del>                        
                                                </div>
                                                <div class="dg-main-product-related-item-detail-yousave">
                                                    <?php if ($special['original']) : ?>
                                                        <?= ceil((($special['original'] - $special['price']) / $special['original']) * 100); ?>% off
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                <?php endforeach ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php echo $shoppingcart ?>
        </div>
    </div>
</div>  
<div id="flyItem" class="fly_item"><img src="" width="40" height="40"></div>    
    <?php echo $foot ?>
    <?php
    if ($pro['children']) {
        function finalDetail($a,$b,$c,$d){
            if($b){
                $h = false;
                foreach($b as $e => $f){
                    $f = explode(',',strtolower($f['value']));
                    if(in_array($a, $f)){
                        $h = true;
                        break;
                    }
                }
                if(!$h){
                    $d++;
                    $_SESSION['skip'][] = $d;
                    $a .= '/'.$c[$d];
                    return finalDetail($a, $b, $c, $d);
                }else{
                    return $a;
                }
            }else{
                return '';
            }
        }
        
        
        $tmp = [];
        foreach ($pro['details'] as $k1 => $v1) {
            $tmp[$k1]['status'] = $v1['status'];
            $sku = explode('/', $v1['sku']);
            unset($sku[0]);
            $sku = array_values($sku);
            
            foreach ($sku as $kk => $vv) {
                if(isset($_SESSION['skip'])){
                    $skip = $_SESSION['skip'];
                }else{
                    $skip = [];
                }
                $vv = strtolower($vv);
                if(!empty($skip)){
                    if(in_array($kk,$skip))
                        continue;
                }
                if(isset($_SESSION['skip'])){
                    unset($_SESSION['skip']);
                }
                $vv = finalDetail($vv,$pro['variants'],$sku,$kk);
                $tmp[$k1]['sku'][] = $vv;
            }
        }
        $jsondata = !empty($tmp) ? str_replace('\"', '\\\"', json_encode($tmp)) : '';
        echo "<script>var productDetailArray = JSON.parse('" . $jsondata . "')</script>";
    }
    ?>
<script src="<?php echo $cdn ?>js/cartjs.js"></script>
<script>
var freebie = "<?php echo $freeImg ?>"
var productDetails = JSON.parse('<?php echo $pro['children'] ? str_replace('\"', '\\\"', json_encode($pro['details'])) : 0 ?>');
var selectedItem = '';
</script>
<script>
    function arrayDiff(a, b) {
        var clone = a.slice(0);
        for (var i = 0; i < b.length; i++) {
            var temp = b[i];
            for (var j = 0; j < clone.length; j++) {
                if (temp === clone[j]) {
                    clone.splice(j, 1);
                }
            }
        }
        return clone;
    }

    function arrayIntersection(a, b) {
        var ai = 0;
        var result = new Array();

        while (ai < a.length) {
            if ($.inArray(a[ai], b) == -1) {
                ai++;
            } else
            {
                result.push(a[ai]);
                ai++;
            }
        }
        return result;
    }

    function unique(arr) {
        result = arr.sort().join(",,").replace(/(,|^)([^,]+)(,,\2)+(,|$)/g, "$1$2$4").replace(/,,+/g, ",").replace(/,$/, "").split(",");
        return result;
    }
    var attrvalue = [],label;
    $('tr.proattr').each(function (i0, e0) {
            Pdiv = $(e0).find('td:eq(1)').find('div.dg-main-product-core-panel-selector-option');
            Pdiv.find('div').each(function (i10, e10) {
                label = $(e10).data('val').toString().toLowerCase();
                attrvalue[label.toString()]= $(e0).attr('id');
            })
        })
    function advanceSkuhandle() {
        var Pdiv;
        var selectedItemArray = new Array();
        var sl, int, diff;
        var inA;
        $('tr.proattr').each(function (i, e) {
            Pdiv = $(e).find('td:eq(1)').find('div.dg-main-product-core-panel-selector-option');
            Pdiv.find('div').each(function (i1, e1) {
                sl = false;
                inA = false;
                if (selectedItem) {
                    selectedItemArray = selectedItem.split(',')
                    for(var z=0;z<selectedItemArray.length;z++){
                        if(attrvalue[selectedItemArray[z].toString()]==$(e1).closest('tr.proattr').attr('id')){
                            selectedItemArray.splice(z,1);
                        }
                    }
                } else {
                    selectedItemArray.length = 0;
                }
                
                selectedItemArray.push($(e1).data('val').toString().toLowerCase());
                selectedItemArray = unique(selectedItemArray);
                $.each(productDetailArray, function (ke, val) {
                    diff = arrayDiff(selectedItemArray,val['sku']);
                    if(!inA && diff.length == 0){
                        inA = true;
                    }
                });
                $.each(productDetailArray, function (key, value) {
                    if (selectedItem) {
                        int = arrayIntersection(selectedItemArray, value['sku']);
                        
                        if (!inA && $(e1).data('nsl') != '1') {
                            sl = true;
                        }else if (int.length == selectedItemArray.length) {
                            if (value['status'] == 1) {
                                sl = true;
                            }
                        }
                    } else {
                        if ($.inArray($(e1).data('val').toString().toLowerCase(), value['sku']) > -1) {
                            if (value['status'] == 1) {
                                sl = true;
                            }
                        }
                    }
                })

                if (!sl) {
                    $(e1).addClass('disabled').removeClass('select');
                    if (!selectedItem) {
                        $(e1).data('nsl', '1');
                    }
                } else {
                    $(e1).removeClass('disabled');
                }
            })
        })
    }
    $(function () {
        advanceSkuhandle();
    })


    fbq('track', 'ViewContent');

//to record the X and Y position for the fly origin
    cartempty();

    var eleFlyElement = document.querySelector("#flyItem"), eleShopCart = document.querySelector(".listproductcart");
    $("#add_cart").on("click", function (event) {
        flyX = event.clientX;
        flyY = event.clientY;
        if (validToAdd) {
            button_addcart_disabled(this);
            $.ajax({
                type: "POST",
                url: "<?php echo site_url('cart/addCart') ?>",
                dataType: 'json',
                data: {
                    p_id: $("#cart_pid").val(),
                    p_sku: $("#cart_sku").val(),
                    p_attr: $("#product_attr").val(),
                    p_bundle: $("#product_bundle").val(),
                    p_qty: $(".product_qty").val(),
                    p_diyImg: "/product/PRO-953/6.jpg"
                },
                success: function (result) {
                    if (result.success) {

                        fbq('track', 'AddToCart');

                        cartflyimg = $('.dg-main-product-core-slide-pager img:eq(0)').attr('src');
                        cartflyhref = '/product/index/' + $("#cart_pid").val();
                        cartflytitle = $('.dg-main-product-core-panel-title').text();
                        $(".notproductcart").css("display", "none");
                        $(".listproductcart").css("display", "block");
                        $("#flyItem").find("img").attr('src', cartflyimg);
                        flyNow();
                        setTimeout("button_addcart_enabled('#add_cart','Add to Cart')", 800);


                    } else {
                        button_addcart_enabled("#add_cart", 'Add to Cart');
                        $.notifyBar({cssClass: "dg-notify-error", html: result.resultMessage, position: "bottom"});
                    }
                }
            });
        }
        else {
            button_addcart_enabled(this, 'Add to Cart');
            $.notifyBar({cssClass: "dg-notify-error", html: "To Grab, Please Select the Desired Options", position: "bottom"});
            $(".dg-main-product-core-panel-selector-option div").removeClass("a-ring").addClass("a-ring");
        }
    });


    $("#buy_now").on("click", function (event) {
        flyX = event.clientX;
        flyY = event.clientY;
        if (validToAdd) {
            button_buynow_disabled(this);
            $.ajax({
                type: "POST",
                url: "<?php echo site_url('cart/addCart') ?>",
                dataType: 'json',
                data: {
                    p_id: $("#cart_pid").val(),
                    p_sku: $("#cart_sku").val(),
                    p_attr: $("#product_attr").val(),
                    p_bundle: $("#product_bundle").val(),
                    p_qty: $(".product_qty").val(),
                    p_diyImg: "/product/PRO-953/6.jpg"
                },
                success: function (result) {
                    if (result.success) {

                        fbq('track', 'AddToCart');

                        cartflyimg = $('.dg-main-product-core-slide-pager img:eq(0)').attr('src');
                        cartflyhref = '/product/index/' + $("#cart_pid").val();
                        cartflytitle = $('.dg-main-product-core-panel-title').text();
                        self.location = '/cart';
                    } else {
                        button_addcart_enabled("#buy_now", 'Buy Now');
                        $.notifyBar({cssClass: "dg-notify-error", html: result.resultMessage, position: "bottom"});
                    }
                }
            });
        }
        else {
            button_buynow_enabled(this, 'Buy Now');
            $.notifyBar({cssClass: "dg-notify-error", html: "To Grab, Please Select the Desired Options", position: "bottom"});
            $(".dg-main-product-core-panel-selector-option div").removeClass("a-ring").addClass("a-ring");
        }
    });


    $('.rating').rating({
        disabled: true,
        showClear: false,
    });


    validToAdd = false;
    $num = $(".dg-main-product-core-panel-selector-option").length;
    $(".dg-main-product-core-panel-selector-option div").on('click', function () {
        if ($(this).hasClass('disabled')) {
            return false;
        }
        $(this).toggleClass('select').siblings().removeClass('select');
        $snum = $(".dg-main-product-core-panel-selector-option .select").length;
        $stext = '';
        selectedItem = '';
        for (var i = 0; i < $snum; i++) {
            if (i == ($snum - 1)) {
                selectedItem += $(".dg-main-product-core-panel-selector-option .select").eq(i).data('val').toString().toLowerCase();
            } else {
                selectedItem += $(".dg-main-product-core-panel-selector-option .select").eq(i).data('val').toString().toLowerCase() + ',';
            }
        }
        ;
        advanceSkuhandle();
        $snum = $(".dg-main-product-core-panel-selector-option .select").length;
        if ($snum == $num) {
            validToAdd = true;
            for (var i = 0; i < $snum; i++) {
                if (i == ($snum - 1)) {
                    $stext += $(".dg-main-product-core-panel-selector-option .select").eq(i).data('val');
                    selectedItem += $(".dg-main-product-core-panel-selector-option .select").eq(i).data('val');
                } else {
                    $stext += $(".dg-main-product-core-panel-selector-option .select").eq(i).data('val') + '/';
                    selectedItem += $(".dg-main-product-core-panel-selector-option .select").eq(i).data('val') + ',';
                }
            }
            ;



            $("#product_attr").val($stext);



            var prosku = $('#cart_sku').val();
            var proselect = $('#product_attr').val();
            var matchsku = prosku + '/' + proselect;
            var smatchsku = matchsku.replace(/\s/g, "").toLowerCase();

            var length = productDetails.length;

            for (var x = 0; x < length; x++) {
                //console.log(String(productDetails[x].sku).replace(/\s/g, "").toLowerCase());
                if (smatchsku === String(productDetails[x].sku).replace(/\s/g, "").toLowerCase()) {
                    if (freebie == "freebie") {
                        $("#p_price").html("<?= $currency ?>" + "0");
                    } else {
                        $("#p_price").html("<?= $currency ?>" + productDetails[x].price / 100);
                    }
                    $("#p_original").html("<?= $currency ?>" + productDetails[x].original / 100);
                    $('#p_save').html(productDetails[x].save)
                    validToAdd = true;
                }
            }
        } else {
            $('#product_attr').val("");
            validToAdd = false;
        }
    });
    if ($num == 0) {
        validToAdd = true;
    } else {
        validToAdd = false;
    }


    $('#upa_attr').submit(function ()
    {
        if (!validToAdd) {
            $.notifyBar({cssClass: "dg-notify-error", html: "To Grab, Please Select the Desired Options", position: "bottom"});
            $(".dg-main-product-core-panel-selector-option div").removeClass("a-ring").addClass("a-ring");
            return false;
        }

    });

    $(function () {

        $bundle_num = $(".dg-main-product-modal-selector-option").length;
        if ($bundle_num == 0) {
            $('.modal-footer button.btn-danger').attr('disabled', false);
        } else {
            $('.modal-footer button.btn-danger').attr('disabled', true);
        }
        ;

        $(".dg-main-product-modal-selector-option div").on('click', function () {
            $index = $(".dg-main-product-modal-selector").index($(this).parents('.dg-main-product-modal-selector'));

            $(this).addClass('select').siblings().removeClass('select');

            $bundle_snumt = $(".dg-main-product-modal-selector:eq(" + $index + ") .select").length;
            $bundle_numt = $(".dg-main-product-modal-selector:eq(" + $index + ")  .dg-main-product-modal-selector-option").length;
            $bundle_snum = $(".dg-main-product-modal-selector .select").length;
            $bundle_stext = '';
            if ($bundle_snum == $bundle_num) {
                $('.modal-footer button.btn-danger').attr('disabled', false);
            }
            ;
            if ($bundle_snumt == $bundle_numt) {
                for (var i = 0; i < $bundle_snumt; i++) {
                    if (i == ($bundle_snumt - 1)) {
                        $bundle_stext += $(".dg-main-product-modal-selector:eq(" + $index + ") .dg-main-product-modal-selector-option .select").eq(i).text();
                    } else {
                        $bundle_stext += $(".dg-main-product-modal-selector:eq(" + $index + ") .dg-main-product-modal-selector-option .select").eq(i).text() + '-';
                    }
                }
                ;

                $("#bundle_" + $index + "_product_attr").val($bundle_stext);
                $("#bundle_index").val($index);


                $.ajax({
                    type: "POST",
                    url: "<?php echo site_url('productInfo/getBundleAttr') ?>",
                    dataType: 'json',
                    data: $("#bundle_" + $index + "_upa_attr").serialize(),
                    success: function (result) {
                        if (result) {
                            $("#bundle_" + $index + "_p_price").html("<?= $currency ?>" + result.bundle / 100);
                            $("#bundle_" + $index + "_p_original").html("<?= $currency ?>" + result.original / 100);
                        }
                    }
                });
            }
            ;
            //alert($index);
        });


        $('.dg-main-product-core-slide-pic').bxSlider({
            pagerCustom: '.dg-main-product-core-slide-pager',
            auto: true,
            pause: 5000,
            controls: false,
            mode: 'fade'

        });

        $("#collect").one("click", function () {
            $.ajax({
                type: "POST",
                url: "<?php echo site_url('collect/insert') ?>",
                dataType: 'json',
                data: $("#upa_collect").serialize(),
                success: function (result) {
                    if (result.success) {
                        $("#collect span").removeClass('glyphicon-star-empty');
                        $("#collect span").addClass('glyphicon-star');
                        $.notifyBar({cssClass: "dg-notify-success", html: result.resultMessage, position: "bottom"});
                    } else {
                        $.notifyBar({cssClass: "dg-notify-error", html: result.resultMessage, position: "bottom"});
                    }

                    $("#collect").removeAttr("id");
                }
            });
        });

        $('.dg-main-product-core-slide-pic').css('visibility', 'visible');
    });
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


//fly Animation 
    function flyNow() {


        var scrollLeft = document.documentElement.scrollLeft || document.body.scrollLeft || 0,
                scrollTop = document.documentElement.scrollTop || document.body.scrollTop || 0;
        eleFlyElement.style.left = flyX + scrollLeft + "px";
        eleFlyElement.style.top = flyY + scrollTop + "px";
        eleFlyElement.style.visibility = "visible";
        var numberItem = 0;

        var myParabola = funParabola(eleFlyElement, eleShopCart, {
            speed: 100,
            curvature: 0.0006,
            complete: function () {
                eleFlyElement.style.visibility = "hidden";
                if ($('#product_attr').val() === '') {
                    var liID = $('#cart_sku').val();
                } else {
                    var liID = $('#cart_sku').val() + '/' + $('#product_attr').val();
                }
                var qty = parseInt($('#qty').val());
                $('#shopCart li').each(function () {
                    if ($(this).attr("id") === liID) {
                        qty = qty + parseInt($(this).find('span').html());
                        $(this).detach();
                    }

                });
                $("#shopCart").prepend("<li id='" + liID + "'><a href=''><img src='" + cartflyimg + "'></a><a class='title' href='javascript:void(0);'>" + cartflytitle + "</a><p>x <span>" + qty + "</span></p></li>");
                $(".cartempty").css("display", "none");
                $(".checkoutpage").fadeIn("slow");
            }
        });

        myParabola.position().move();
    }
</script>
<?php if (isset($countrySEO)) echo $countrySEO ?>
</body>
</html>