<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Unicorn Admin</title>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <base href="<?php echo $template ?>">
        <link rel="stylesheet" href="css/bootstrap.min.css" />
        <link rel="stylesheet" href="css/font-awesome.css" />	
        <link rel="stylesheet" href="css/summernote.css" />
        <link rel="stylesheet" href="css/icheck/flat/blue.css" />
        <link rel="stylesheet" href="css/select2.css" />
        <link rel="stylesheet" href="css/jquery-ui.css" />	
        <link rel="stylesheet" href="css/jquery.tagsinput.css" />	
        <link rel="stylesheet" href="css/unicorn.css" />		
        <link rel="stylesheet" href="css/paddy.css" />	
        <link rel="stylesheet" href="css/fileinput.css"/>	
        <link rel="stylesheet" href="css/chartist.min.css"/>	
        <!--[if lt IE 9]>
        <script type="text/javascript" src="js/respond.min.js"></script>
        <![endif]-->

    </head>
    <body data-color="grey" class="flat"><div id="wrapper">
            <?php echo $head ?>

            <div id="content">
                <div id="content-header" class="mini">
                    <div class="row">
                        <div class="col-xs-6 col-sm-6 col-lg-6">
                            <h1><i class="fa fa-home" aria-hidden="true"></i> Dashboard</h1>
                        </div>
                        <div class="col-xs-6">
                        <form action="/home" method="post">
                            <div class="input-group">
                                <input type="text" id="datepicker1" name="startTime" value="<?=$start?>" class="form-control" placeholder="Start Time..." aria-describedby="basic-addon2">
                                <span class="input-group-addon" id="basic-addon2">To</span>
                                <input type="text" id="datepicker2" value="<?=$end?>" name="endTime" class="form-control" placeholder="End Time..." aria-describedby="basic-addon2">
                                <span class="input-group-btn">
                                    <button class="btn btn-default" type="submit">Go!</button>
                                </span> 
                            </div>
                       </form>
                        </div>
                    </div>	
                </div>
                <div class="row">
                    <div class="col-xs-12">
                        <div class="widget-box widget-plain">
                            <div class="widget-content center">
                                <ul class="stats-plain">
                                	<li>										
                                        <h4><?=isset($conversions['amount'])?$conversions['amount']/100:0?></h4>
                                        <span>Total Revenue</span><br/>
                                    </li>
                                    <li>
                                    	<h4><?=isset($conversions['order'])?$conversions['order']:0?></h4>
                                        <span>Total Order</span>
                                    </li>
                                    <li>										
                                        <h4>
                                        	<?=$conversions['memberNumber']?>
                                        	(+<?php
                                        	    if(isset($conversions['reg']) && isset($conversions['autoreg'])){
                                        	    	echo $conversions['reg']+$conversions['autoreg'];
												}else{
                                        	    	echo 0;
                                        	    }
                                        	  ?>
                                        	)
                                        </h4>
                                        <span>Users Registered</span>
                                    </li>
                                    <br/>
                                    <li>										
                                        <h4><?=isset($conversions['addtocart'])?round($conversions['addtocart']/ $conversions['click'] *100,2):0?>%</h4>
                                        <span>Added to Cart</span><br/>
                                        <span><?=isset($conversions['addtocart'])?$conversions['addtocart']:0?></span>
                                    </li>
                                    <li>
                                    	<h4><?=isset($conversions['checkout'])?round($conversions['checkout']/ $conversions['click'] *100,2):0?>%</h4>	
                                        <span>Reached Checkout</span><br/>
                                        <span><?=isset($conversions['checkout'])?$conversions['checkout']:0?></span>
                                    </li>
                                    <li>
                                    	<h4><?=isset($conversions['pay'])?round($conversions['pay']/ $conversions['click'] *100,2):0?>%</h4>	
                                        <span>Reached Payment</span><br/>
                                        <span><?=isset($conversions['pay'])?$conversions['pay']:0?></span>
                                    </li>
                                    <li>
                                    	<h4><?=isset($conversions['purchase'])?round($conversions['purchase']/ $conversions['click'] *100,2):0?>%</h4>
                                        <span>Purchased</span><br/>
                                        <span><?=isset($conversions['purchase'])?$conversions['purchase']:0?></span>
                                    </li>     
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12">
                        <div class="widget-box">
                            <div class="widget-title">
                                <span class="icon">
                                    <i class="fa fa-bar-chart-o"></i>
                                </span>
                                <h5>Line chart</h5>
                            </div>
                            <div class="ct-chart">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-lg-6">
                        <div class="widget-box">
                            <div class="widget-title">
                                <span class="icon">
                                    <i class="fa fa-file"></i>
                                </span>
                                <h5>Sales</h5>
                            </div>
                            <div class="widget-content nopadding">
                                <table class="table table-bordered table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th>country</th>
                                            <th>Total Revenue</th>
                                            <th>Total Order</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($countrySales as $key=>$sales): ?>
                                        <tr>
                                            <td><?=$sales['country']?></td>
                                            <td><?=$sales['amount']/100?></td>
                                            <td><?=$sales['order']?></td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="widget-box">
                            <div class="widget-title">
                                <span class="icon">
                                    <i class="fa fa-file"></i>
                                </span>
                                <h5>Visited</h5>
                            </div>
                            <div class="widget-content nopadding">
                                <table class="table table-bordered table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th>Page</th>
                                            <th>Visits</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>click</td>
                                            <td><?=$conversions['click']?></td>
                                        </tr>
                                        <tr>
                                            <td>addtocart</td>
                                            <td><?=$conversions['addtocart']?></td>
                                        </tr>
                                        <tr>
                                            <td>checkout</td>
                                            <td><?=$conversions['checkout']?></td>
                                        </tr>
                                        <tr>
                                            <td>pay</td>
                                            <td><?=$conversions['pay']?></td>
                                        </tr>
                                        <tr>
                                            <td>purchase</td>
                                            <td><?=$conversions['purchase']?></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-lg-6">
                        <div class="widget-box">
                            <div class="widget-title">
                                <span class="icon">
                                    <i class="fa fa-file"></i>
                                </span>
                                <h5>Pay</h5>
                            </div>
                            <div class="widget-content nopadding">
                                <div class="row">
                                    <div class="col-xs-4">
                                        <ul class="widget-content-pie">
                                            <li><input type="button" style="width: 25px;height: 15px;background-color: #FD7400;border: none">PC-><?=$terminal['pc']?></li>
                                            <li><input type="button" style="width: 25px;height: 15px;background-color: #BEDB39;border: none">Mobile-><?=$terminal['mobi']?></li>
                                        </ul>
                                    </div>
                                    <div class="col-xs-8">
                                        <div class="pay"></div>
                                    </div>
                                </div>   
                            </div>
                        </div>

                        <div class="widget-box">
                            <div class="widget-title">
                                <span class="icon">
                                    <i class="fa fa-file"></i>
                                </span>
                                <h5>Shipping</h5>
                            </div>
                            <div class="widget-content nopadding">
                                <div class="row">
                                    <div class="col-xs-4">
                                        <ul class="widget-content-pie">
                                            <li><input type="button" style="width: 25px;height: 15px;background-color: #FD7400;border: none">Standard-><?=$shippingType['standard']?></li>
                                            <li><input type="button" style="width: 25px;height: 15px;background-color: #BEDB39;border: none">Express-><?=$shippingType['express']?></li>
                                        </ul>
                                    </div>
                                    <div class="col-xs-8">
                                        <div class="shipping"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        
                        <div class="widget-box">
                            <div class="widget-title">
                                <span class="icon">
                                    <i class="fa fa-file"></i>
                                </span>
                                <h5>marketing</h5>
                            </div>
                            <div class="widget-content nopadding">
                                <div class="row">
                                    <div class="col-xs-6">
                                        <ul class="widget-content-pie">                                                                         
                                            <li><input type="button" style="width: 25px;height: 15px;background-color: #FD7400;border: none">Edm-><?=isset($marketing['edm']) ? $marketing['edm'] : 0 ?>-><?=isset($marketing['edm_amount'])?$marketing['edm_amount']/100:0?></li>
                                            <li><input type="button" style="width: 25px;height: 15px;background-color: #BEDB39;border: none">Google-><?=isset($marketing['google']) ? $marketing['google'] : 0 ?>-><?=isset($marketing['google_amount'])?$marketing['google_amount']/100:0?></li>
                                            <li><input type="button" style="width: 25px;height: 15px;background-color: #1F8A70;border: none">Normal-><?=isset($marketing['normal']) ? $marketing['normal'] : 0 ?>-><?=isset($marketing['normal_amount'])?$marketing['normal_amount']/100:0?></li>
                                        </ul>
                                    </div>
                                    <div class="col-xs-6">
                                        <div class="marketing"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">   
					
					<?php if ($productRank):?>
                    <div class="col-xs-12 col-sm-12 col-lg-6">
                        <div class="widget-box">
                            <div class="widget-title">
                                <span class="icon">
                                    <i class="fa fa-eye"></i>
                                </span>
                                <h5>(<?=$this->session->userdata('my_country')?>) Product Rank</h5>
                            </div>
                            <div class="widget-content nopadding">
                                <table class="table table-bordered table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th>Product</th>
                                            <th>sales</th>
                                            <th>total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                       <?php foreach ($productRank as $product): ?>
                                        <tr>
                                            <td><a href="/product/edit/<?=$product['product'] ?>" target="_blank"><?=isset($product['title'])?$product['title']:"产品不存在~~~" ?></a></td>
                                            <td><?=$product['sold']?></td>
                                            <td><?=$product['price']?></td>
                                        </tr>
                                       <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>
                    
                     
                    <?php if ($collectionRank):?>
                    <div class="col-xs-12 col-sm-12 col-lg-6">
                        <div class="widget-box">
                            <div class="widget-title">
                                <span class="icon">
                                    <i class="fa fa-eye"></i>
                                </span>
                                <h5>(<?=$this->session->userdata('my_country')?>) Collection Rank</h5>
                            </div>
                            <div class="widget-content nopadding">
                                <table class="table table-bordered table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th>Collection</th>
                                            <th>sales</th>
                                            <th>total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                       <?php foreach ($collectionRank as $collection): ?>
                                        <tr>
                                            <td><a href="/collection/loadEditPage/<?=$collection['id'] ?>" target="_blank"><?=$collection['title']?></a></td>
                                            <td><?=$collection['sold']?></td>
                                            <td><?=$collection['price']?></td>
                                        </tr>
                                       <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>
                    

                    <div class="col-xs-12 col-sm-12 col-lg-6">
                        <div class="widget-box">
                            <div class="widget-title">
                                <span class="icon">
                                    <i class="fa fa-eye"></i>
                                </span>
                                <h5>ALL Product Rank</h5>
                            </div>
                            <div class="widget-content nopadding">
                                <table class="table table-bordered table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th>Product sku</th>
                                            <th>sales</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                       <?php foreach ($allProductRank as $product): ?>
                                        <tr>
                                            <td><a href="/product/?productType=&tag=&collection=&creator=&price=&search=<?=$product['sku']?>&sortBy=" target="_blank"><?=$product['sku']?></a></td>
                                            <td><?=$product['sold']?></td>
                                        </tr>
                                       <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    
                    
                   

                    
                </div>
            </div>
            <div class="row">
                <div id="footer" class="col-xs-12">
                    2012 - 2013 &copy; Unicorn Admin. Brought to you by <a href="https://wrapbootstrap.com/user/diablo9983">diablo9983</a>
                </div>
            </div>

            <script src="js/excanvas.min.js"></script>
            <script src="js/jquery.min.js"></script>
            <script src="js/jquery-ui.custom.js"></script>
            <script src="js/bootstrap.min.js"></script>
            <script src="js/chartist.min.js"></script>
            <script src="js/jquery.nicescroll.min.js"></script>
            <script src="js/chartist-plugin-pointlabels.min.js"></script>
            <script src="js/unicorn.js"></script>
            <?php echo $foot ?>
            
            <script>
                new Chartist.Line('.ct-chart', {
                  labels: [
                         <?php foreach ($lineChartData as $data): ?>
                         	"<?=$data['date']?>",
                         <?php endforeach; ?>
                          ],
                  series: [
                    [
						<?php foreach ($lineChartData as $data): ?>
							"<?=$data['amount']/100?>",
						<?php endforeach; ?>
                    ]
                  ]
                }, {
                  low: 0,
                  height: 300,
                  showArea: true,
                  lineSmooth: false,

                  <?php if (count($lineChartData) <= 32){?>
                  plugins: [
                    Chartist.plugins.ctPointLabels({
                        textAnchor: 'middle'
                    })
                  ],                  
                  <?php }?>
                  
                  axisX: {
                      labelInterpolationFnc: function(value, index) {
                          var labelNumber = parseInt(<?php echo count($lineChartData);?>/7);
                          if(<?php echo count($lineChartData);?> >= 7){
                            return index % labelNumber === 0 ? value : null;
                          }
                          else{
                              return value;
                          }
                      }
                  }
                });
                

                var datas = {
                  series: [<?=$terminal['pc']?>,<?=$terminal['mobi']?>]
                };

                var sum = function(a, b) { return a + b };

                new Chartist.Pie('.pay', datas, {
                  labelInterpolationFnc: function(value) {
                    return Math.round(value / datas.series.reduce(sum) * 100) + '%';
                  }
                });

                var datap = {
                  series: [<?=$shippingType['standard']?>,<?=$shippingType['express']?>]
                };

                var sum = function(a, b) { return a + b };

                new Chartist.Pie('.shipping', datap, {
                  labelInterpolationFnc: function(value) {
                    return Math.round(value / datap.series.reduce(sum) * 100) + '%';
                  }
                });

				
               	var datam = {
                  series: [<?= $marketing['edm'] ? $marketing['edm'] : 0 ?>,<?=$marketing['google'] ? $marketing['google'] : 0 ?>,<?=$marketing['normal'] ? $marketing['normal'] : 0 ?>]
                };

               	var sum = function(a, b) { return a + b };
               	
               	new Chartist.Pie('.marketing', datam, {
                    labelInterpolationFnc: function(value) {
                      return Math.round(value / datam.series.reduce(sum) * 100) + '%';
                    }
               });

            </script>
            <script>
                // Datepicker
                $('#datepicker1').datepicker({
                    changeMonth: true,
                    dateFormat: "yy-mm-dd",
                    changeYear: true,
                    onClose: function (selectedDate) {
                        $("#datepicker2").datepicker("option", "minDate", selectedDate);
                    }
                });
                $('#datepicker2').datepicker({
                    defaultDate: "+1w",
                    changeMonth: true,
                    dateFormat: "yy-mm-dd",
                    changeYear: true,
                    onClose: function (selectedDate) {
                        $("#datepicker1").datepicker("option", "maxDate", selectedDate);
                    }
                });
            </script>
    </body>
</html>
