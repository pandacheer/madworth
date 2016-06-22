<?php echo $head; ?>
     <div role="main" class="ui-content">
              
                <div class="dg-pagetitle">My Shipping Address</div>
                <div class="dg-main-address">
                  <?php if($listAddress): ?>
                     <?php foreach ($listAddress as $key => $address) : ?>
                     	<?php if ($key == 0): ?>
                     		<ul data-role="listview" data-inset="true" data-theme="none" class="dg-main-address-primary">
                        		<li data-role="list-divider" class="dg-main-address-primary-title" style="border:none;text-transform: capitalize"><?php echo $address['receive_firstName'] . '&nbsp;' . $address['receive_lastName'] ?> <a href="/personal/getAddressInfo/<?=$address['receive_id']?>"><span style="float: right;color:#fff;font-weight: 600">Edit Address</span></a></li>
                        		<li style="border: none">
                            		<p><strong style="text-transform: capitalize"><?php echo $address['receive_firstName'] .'&nbsp;'. $address['receive_lastName'] ?></strong></p>
                            		<p>
                            		   <?php if ($address['receive_add2']):?>
                            		   		<?php echo $address['receive_add2'] ?> / <?php echo $address['receive_add1'] ?>
                            		   <?php else: ?>
                            		   		<?php echo $address['receive_add1'] ?>
                            		   <?php endif; ?>
                            		</p>
                            		<p><?php echo $address['receive_city'] ?> , <?php echo $address['receive_province'] ?> , <?php echo $address['receive_zipcode'] ?></p>
                            		<p><?php echo $address['receive_phone'] ?></p>
                        		</li>
                    		</ul>
                     	<?php else : ?>
                     	   <ul data-role="listview" data-inset="true" data-theme="c" class="dg-main-address-list">
                           	  <li data-role="list-divider" class="dg-main-address-list-title" style="border-bottom: 1px #CAC5C5 solid;text-transform: capitalize"><?php echo $address['receive_firstName'] . '&nbsp;' . $address['receive_lastName'] ?> <a href="/personal/getAddressInfo/<?=$address['receive_id']?>"><span style="float: right;color: #00B6C6;font-weight: 600">Edit Address</span></a></li>
                              <li style="background-color: #fff" class="dg-main-address-list-content">
                              	<p><strong style="text-transform: capitalize"><?php echo $address['receive_firstName'] .'&nbsp;'. $address['receive_lastName'] ?></strong></p>
                                <p>
                                  <?php if ($address['receive_add2']):?>
                            	  		<?php echo $address['receive_add2'] ?> / <?php echo $address['receive_add1'] ?>
                            	  <?php else: ?>
                            		   	<?php echo $address['receive_add1'] ?>
                            	  <?php endif; ?>
                                </p>
                                <p><?php echo $address['receive_city'] ?> , <?php echo $address['receive_province'] ?> , <?php echo $address['receive_zipcode'] ?></p>
                                <p><?php echo $address['receive_phone'] ?></p>
                             </li>
                          </ul>
                        <?php endif; ?>
                    <?php endforeach; ?>
                    <?php else:?>
                        <div class="dg-main-empty">
                          <div class="dg-main-empty-icon">
                              <span class="icon-map"></span>
                          </div>
                          <div class="dg-main-empty-msg">
                               <span>You currently don't have any shipping addresses.</span>
                          </div>
                      </div>
                  <?php endif; ?>
                  <?php if ($count < 5): ?>
                    <div class="dg-main-address-add">
                        <a href="/personal/add_address"><button data-theme="g">Add a New Shipping Address</button></a>
                    </div>
                  <?php endif; ?>
                </div>
                
                
                
                
                
                
                <div class="dg-pagetitle">My Billing Address</div>
                <div class="dg-main-address">
                  <?php if($billAddress): ?>
                     <?php foreach ($billAddress as $key => $bill_address) : ?>
                     	<?php if ($key == 0): ?>
                     		<ul data-role="listview" data-inset="true" data-theme="none" class="dg-main-address-primary">
                        		<li data-role="list-divider" class="dg-main-address-primary-title" style="border:none;text-transform: capitalize"><?php echo $bill_address['receive_firstName'] . '&nbsp;' . $bill_address['receive_lastName'] ?> <a href="/personal/getBillAddressInfo/<?=$bill_address['receive_id']?>"><span style="float: right;color:#fff;font-weight: 600">Edit Address</span></a></li>
                        		<li style="border: none">
                            		<p><strong style="text-transform: capitalize"><?php echo $bill_address['receive_firstName'] .'&nbsp;'. $bill_address['receive_lastName'] ?></strong></p>
                            		<p>
                            		   <?php if ($bill_address['receive_add2']):?>
                            		   		<?php echo $bill_address['receive_add2'] ?> / <?php echo $bill_address['receive_add1'] ?>
                            		   <?php else: ?>
                            		   		<?php echo $bill_address['receive_add1'] ?>
                            		   <?php endif; ?>
                            		</p>
                            		<p><?php echo $bill_address['receive_city'] ?> , <?php echo $bill_address['receive_province'] ?> , <?php echo $bill_address['receive_zipcode'] ?></p>
                        		</li>
                    		</ul>
                     	<?php else : ?>
                     	   <ul data-role="listview" data-inset="true" data-theme="c" class="dg-main-address-list">
                           	  <li data-role="list-divider" class="dg-main-address-list-title" style="border-bottom: 1px #CAC5C5 solid;text-transform: capitalize"><?php echo $bill_address['receive_firstName'] . '&nbsp;' . $bill_address['receive_lastName'] ?> <a href="/personal/getBillAddressInfo/<?=$bill_address['receive_id']?>"><span style="float: right;color: #00B6C6;font-weight: 600">Edit Address</span></a></li>
                              <li style="background-color: #fff" class="dg-main-address-list-content">
                              	<p><strong style="text-transform: capitalize"><?php echo $bill_address['receive_firstName'] .'&nbsp;'. $bill_address['receive_lastName'] ?></strong></p>
                                <p>
                                  <?php if ($bill_address['receive_add2']):?>
                            	  		<?php echo $bill_address['receive_add2'] ?> / <?php echo $bill_address['receive_add1'] ?>
                            	  <?php else: ?>
                            		   	<?php echo $bill_address['receive_add1'] ?>
                            	  <?php endif; ?>
                                </p>
                                <p><?php echo $bill_address['receive_city'] ?> , <?php echo $bill_address['receive_province'] ?> , <?php echo $bill_address['receive_zipcode'] ?></p>
                             </li>
                          </ul>
                        <?php endif; ?>
                    <?php endforeach; ?>
                    <?php else:?>
                        <div class="dg-main-empty">
                          <div class="dg-main-empty-icon">
                              <span class="icon-map"></span>
                          </div>
                          <div class="dg-main-empty-msg">
                               <span>You currently don't have any billing addresses.</span>
                          </div>
                      </div>
                  <?php endif; ?>
                  <?php if ($billCount < 5): ?>
                    <div class="dg-main-address-add">
                        <a href="/personal/add_billAddress"><button data-theme="g">Add a New Billing Address</button></a>
                    </div>
                  <?php endif; ?>
                </div>
                
    
            </div>
            <?php echo $foot; ?>
        </div>
        
    </body>
</html>






                    