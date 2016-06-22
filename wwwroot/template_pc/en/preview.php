<?php echo $head; ?>
<link href="css/star-rating.min.css" rel="stylesheet">
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
              <?=$breadcrumb?>
            </ol>
          </div>
        </div>
      </div>
    </div>
  
    <div class="dg-main">
      <div class="container">
        <div class="row">
          <div class="col-xs-10 col-xs-12">
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
                        foreach($pro['pics'] as $vo){
                            if($vo['img']!=NULL){
                                echo '<li><img alt="'.htmlspecialchars_decode($pro['title']).'" src="'.IMAGE_DOMAIN.$vo['img'].'" /></li>';
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
                        $i = 0;
                        foreach($pro['pics'] as $vo){
                            if($vo['img']!=NULL){
                                echo '<a data-slide-index="'.$i.'" href=""><img alt="'.htmlspecialchars_decode($pro['title']).'" src="'.IMAGE_DOMAIN.$vo['img'].'" /></a>';
                                $i++;
                            }
                        }
                        ?>
                      </div>
                    </div>
                  </div>
                <div class="col-xs-6">
                    <div class="dg-main-product-core-panel">
                      <div class="dg-main-product-core-panel-title"><?=htmlspecialchars_decode($pro['title'])?></div>
                      <div class="dg-main-product-core-panel-price"> 
                        <span class="dg-main-product-core-panel-price-current" id="p_price"><?=$currency.$pro['price']/100?></span>
                        <span class="dg-main-product-core-panel-price-was"><del id="p_original"><?=$currency.$pro['original']/100?></del></span>
                      </div>
                      <div class="dg-main-product-core-panel-instock"> <span style="color:#00B6C8;">In Stock.</span> Dispatch in 2-3 business days </div>
                      <div class="dg-main-product-core-panel-countdown">
                      
                        <?php if (empty($pro['endTime'])):?>
                          <table class="dg-main-product-core-panel-countdown table table-bordered table-condensed table-curved">
                          <tr class="dg-main-product-core-panel-countdown-head">
                            <td>Deals Sold</td>
                            <td>You Save</td>
                          </tr>
                          <tr class="dg-main-product-core-panel-countdown-data">
                            <td><img src="<?php echo $cdn ?>image/product/countdown1.png"><?=$pro['sold']['total']?></td>
                            <td><img src="<?php echo $cdn ?>image/product/countdown2.png"><span id="p_save"><?=$save?></span>%</td>
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
                            <td><img src="<?php echo $cdn ?>image/product/countdown1.png"><?=$pro['sold']['init']?></td>
                            <td><img src="<?php echo $cdn ?>image/product/countdown2.png"><span id="p_save"><?=$save?></span>%</td>
                            <td><img src="<?php echo $cdn ?>image/product/countdown3.png"><span id="hours"></span><span data-countdown="<?=$pro['endTime']?>"></span>&nbsp;<span id="countd"></span></td>
                          </tr>
                        </table>
                        <?php endif; ?> 


                      </div>

                      
                       
                      <div class="dg-main-product-core-panel-selector">
                        <?php  $attributes = array('id' => 'upa_attr');?>
                        <?php echo form_open('cart/buy_now',$attributes); ?>
                        	<input type="hidden" value="<?=$pro['_id']?>" name="product_id"   id="cart_pid" />
                          	<input type="hidden" value="<?=$pro['sku']?>" name="product_sku" id="cart_sku" />
                         	<input type="hidden" value=""  name="product_attr" id="product_attr" />
                         	<input type="hidden" value="<?=$product_bundle?>" id="product_bundle" name="product_bundle">
                        <table>
                        <?php if ($product_bundle==1):?>
                          <?php if ($data['is_variants']):?>
                          <?php foreach ($pro['variants'] as $variants): ?>
                            <tr>
                              <td class="dg-main-product-core-panel-selector-title">
                                <?=$variants['option_map']?> :
                              </td>
                              <td>
                              <div class="dg-main-product-core-panel-selector-option">
                                <?php
                                  $mapping = explode(',',$variants['value_map']); 
                                  $valueSku= explode(',',$variants['value']); 
                                ?>
                                <?php foreach ($mapping as $index=> $value): ?>
                                   <div  data-val="<?=$valueSku[$index]?>" ><?=$value?></div>
                                <?php endforeach ?>
                              </div>                              
                              </td>
                            </tr>
                          <?php endforeach ?>
                          <?php endif; ?>
                        <?php elseif ($product_bundle==2):?>
           
                            <tr>
                               <td class="dg-main-product-core-panel-selector-title">
                                Select Buy :
                              </td>
                              
                              <td>
                              <div class="dg-main-product-core-panel-selector-option">
                                 <?php foreach ($pro['plural'] as $plural): ?>
                                 <div  data-val="<?=$plural['number']?>" ><?=$plural['number']?></div>
                                 <?php endforeach ?>
                              </div>                              
                              </td>
                              
                            </tr>
                       
                        <?php elseif ($product_bundle==3):?> 
                         <?php if ($data['is_variants']):?>
                          <?php foreach ($pro['variants'] as $variants): ?>
                            <tr>
                              <td class="dg-main-product-core-panel-selector-title">
                                <?=$variants['option_map']?> :
                              </td>
                              <td>
                              <div class="dg-main-product-core-panel-selector-option">
                                <?php
                                  $mapping = explode(',',$variants['value_map']); 
                                  $valueSku= explode(',',$variants['value']);
                                ?>
                                <?php foreach ($mapping as $index=>$value): ?>
                                   <div  data-val="<?=$valueSku[$index]?>" ><?=$value?></div>
                                <?php endforeach ?>
                              </div>                              
                              </td>
                            </tr>
                          <?php endforeach ?>
                          <?php foreach ($pro['variants'] as $variants): ?>
                            <tr>
                              <td class="dg-main-product-core-panel-selector-title">
                                <?=$variants['option_map']?> :
                              </td>
                              <td>
                              <div class="dg-main-product-core-panel-selector-option">
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
                          <?php endif; ?>
                        <?php endif; ?>
                         <tr>
                           <td class="dg-main-product-core-panel-selector-title">Qty :</td>
                           <td>
                             <div class="qty_product">
                              <button title="Decrease Qty" onClick="qtyDown(398); return false;" class="decrease">-</button>   
                              <input id="qty" name="product_qty"  value="1" size="4" title="Qty" class="input-text product_qty" maxlength="12" readonly>
                              <button title="Increase Qty" onClick="qtyUp(398); return false;" class="increase">+</button>
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
                         <input type="hidden" value="<?=$pro['_id']?>" name="product_id"/>
<!--                          <a href="javascript:void(0)" id="collect"><span class="glyphicon glyphicon-star-empty" aria-hidden="true"></span> Add to Wishlist</a> -->
                      </div>
                      </form>
                      
                      <div class="dg-main-product-core-panel-delivery">
                        <div class="dg-main-product-core-panel-delivery-content">
                          <h3>Delivery Methods</h3>
                          
                          <?php foreach ($shipping as $shipping): ?>
                          <?php if ($shipping['showType']>0) :?>
                          <div class="left">
                            <p class="dg-main-product-core-panel-delivery-drgrab"><?= $shipping['name'] ?></p>
                            <p class="dg-main-product-core-panel-delivery-drgrab"><?php echo $shipping['price'] ? $currency.$shipping['price']/100 : 'Free'; ?></p>
                            <?php
                            	$titles = explode('@',$shipping['title']); 
                            ?>
                                <?php foreach ($titles as $title): ?>
                                   <p ><?=$title?></p>
                                <?php endforeach ?>   
                          </div>
                          <?php endif;?>
                          <?php endforeach ?>
 
                        </div>
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
            
            <div class="row">
              <div class="col-sm-9">
                <div class="dg-main-product-description">
                  <?=htmlspecialchars_decode($pro['description'])?>
                </div>
                <div class="dg-main-product-contactus">
                  <a href="mailto:support@drgrab.com"><img src="<?php echo $cdn ?>image/product/support.png"></a>
                </div>
              </div>
                
                <?php
                /*
                <div class="dg-main-product-comment">
                  <div class="dg-title">Product Comment</div>
                  <div class="dg-main-product-comment-content">
                    <input value="5" type="number" class="rating" min=0 max=5 step=0.5 data-size="xxs" data-disabled="true" >
                    <div class="row">
                      <div class="col-xs-4">Comment by: Hu *****</div>
                      <div class="col-xs-5">option:xl-red</div>
                      <div class="clearfix"></div>
                    </div>
                    <p>
                      Reminder: emider Twitter, QQ, Wechat etc. alerted to mobile applications, etc. Want timely reminder APP communications software to quickly disply viewing.Reminder: emider Twitter, QQ, Wechat etc. alerted to mobile applications, etc. Want timely reminder APP communications software to quickly disply viewing.
                    </p>
                  </div> 
                </div>
              </div>
              */
              ?>
              
             
              <div class="col-sm-3 dg-no-left-padding">
                <div class="dg-main-product-related">
                  <div class="dg-title"> Up For Grab</div>
                   =.=
                </div>
              </div>
            </div>
    	  	</div>
            <?php echo $shoppingcart?>
    	  </div>
    	</div>
    </div>  
<div id="flyItem" class="fly_item"><img src="" width="40" height="40"></div>    
<?php echo $foot ?>

<script src="js/cartjs.js"></script>

    <script>

//to record the X and Y position for the fly origin
cartempty();
// $("#add_cart").click(function (event) {
    
// });

    var eleFlyElement = document.querySelector("#flyItem"), eleShopCart = document.querySelector(".listproductcart");  

        $('.rating').rating({
              disabled: true,
              showClear: false,
           });
        //加入购物车判断
        
        validToAdd = false;
        $num = $(".dg-main-product-core-panel-selector-option").length;
          $(".dg-main-product-core-panel-selector-option div").on('click',function(){
            $(this).addClass('select').siblings().removeClass('select');
            $snum = $(".dg-main-product-core-panel-selector-option .select").length;
            $stext = '';
            if ($snum == $num) {
              validToAdd = true;  
              for(var i=0; i<$snum ; i++){
                if(i == ($snum - 1)){
                  $stext += $(".dg-main-product-core-panel-selector-option .select").eq(i).data('val');
                }else{
                  $stext += $(".dg-main-product-core-panel-selector-option .select").eq(i).data('val')+'/';
                }
              };

              $("#product_attr").val($stext);

              
              $.ajax({
                type: "POST",
                url: "<?php echo site_url('productInfo/getAttr') ?>",
                dataType: 'json',
                data: $("#upa_attr").serialize(),
                success: function (result) {
                  if(result.success){
                    $("#p_price").html("<?=$currency?>"+result.resultMessage.price/100);
                    $("#p_original").html("<?=$currency?>"+result.resultMessage.original/100);
                    $("#p_save").html(+result.resultMessage.save);
                  }else{
                	$.notifyBar({ cssClass: "dg-notify-error", html: result.resultMessage ,position: "bottom" });   
                  }
                }
              });
            };
          });
          if($num ==0){
            validToAdd = true;
          }else{
            validToAdd = false;
          } 
          
          
          $('#upa_attr').submit(function()
          {
              if (!validToAdd) { 
                  $.notifyBar({ cssClass: "dg-notify-error", html: "To Grab, Please Select the Desired Options" ,position: "bottom" });
                  $(".dg-main-product-core-panel-selector-option div").removeClass("a-ring").addClass("a-ring");
                  return false; 
              }
        
          });          

$(function() {
          //bundle购买弹窗
          $bundle_num = $(".dg-main-product-modal-selector-option").length;
          if($bundle_num ==0){
            $('.modal-footer button.btn-danger').attr('disabled',false);
          }else{
            $('.modal-footer button.btn-danger').attr('disabled',true);
          };
          
          $(".dg-main-product-modal-selector-option div").on('click',function(){
            $index = $(".dg-main-product-modal-selector").index($(this).parents('.dg-main-product-modal-selector'));
           
            $(this).addClass('select').siblings().removeClass('select');
            
            $bundle_snumt = $(".dg-main-product-modal-selector:eq("+$index+") .select").length;
            $bundle_numt = $(".dg-main-product-modal-selector:eq("+$index+")  .dg-main-product-modal-selector-option").length;
            $bundle_snum = $(".dg-main-product-modal-selector .select").length;
            $bundle_stext = '';
            if ($bundle_snum == $bundle_num) {$('.modal-footer button.btn-danger').attr('disabled',false);};
            if ($bundle_snumt == $bundle_numt) {
              for(var i=0; i<$bundle_snumt ; i++){
                if(i == ($bundle_snumt - 1)){
                  $bundle_stext += $(".dg-main-product-modal-selector:eq("+$index+") .dg-main-product-modal-selector-option .select").eq(i).text();
                }else{
                  $bundle_stext += $(".dg-main-product-modal-selector:eq("+$index+") .dg-main-product-modal-selector-option .select").eq(i).text()+'-';
                }
              };
              
              $("#bundle_"+$index+"_product_attr").val($bundle_stext);
              $("#bundle_index").val($index);
              

              $.ajax({
                type: "POST",
                url: "<?php echo site_url('productInfo/getBundleAttr') ?>",
                dataType: 'json',
                data: $("#bundle_"+$index+"_upa_attr").serialize(),
                success: function (result) {
                  if(result){
                    $("#bundle_"+$index+"_p_price").html("<?=$currency?>"+result.bundle/100);
                    $("#bundle_"+$index+"_p_original").html("<?=$currency?>"+result.original/100);
                   }
                }
              });
            };
            //alert($index);
          });
          

          //倒计时

            $('[data-countdown]').each(function(){
              var cd=$(this).data('countdown');
              var date=new Date(cd);
              od= date.getDate();
              oh=date.getHours();

              var d=new Date();
              var nd=d.getDate();
              var nh=d.getHours();
              
              fh=(od-nd-1)*24+(24-nh-1)+oh;
              if(fh>=48){
                var $this = $(this), finalDate = $(this).data('countdown');
                $this.countdown(finalDate, function(event) {
                  $this.html(event.strftime('%D days %H:%M:%S'));
                });
              }
              else{
                  function ShowTimes(){
                  var c=new Date();
                  var q=parseInt(c.getMilliseconds()/10);
                  if(q<10)
                  {
                      q="0"+q;
                  }
                  countd.innerHTML=q;
                  }
                  setInterval(ShowTimes,10);
                
                hours.innerHTML=fh+":"
                var $this = $(this), finalDate = $(this).data('countdown');
                $this.countdown(finalDate, function(event) {
                  $this.html(event.strftime('%M:%S'));
                });
              }
              
             })
          
          $('.dg-main-product-core-slide-pic').bxSlider({
            pagerCustom: '.dg-main-product-core-slide-pager',
            auto: true,
            controls: false,
            mode: 'fade'

          });
          

          

          
          $('.dg-main-product-core-slide-pic').css('visibility','visible');
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
            $("#shopCart").prepend("<li id='" + liID + "'><a href=''><img src='" + cartflyimg + "'></a><a class='title' href=''>" + cartflytitle + "</a><p>x <span>" + qty + "</span></p></li>");
            $(".cartempty").css("display", "none");
            $(".checkoutpage").fadeIn("slow");
        }
    });
    
    myParabola.position().move();
}
    </script>
  </body>
</html>