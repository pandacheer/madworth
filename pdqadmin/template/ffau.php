<!DOCTYPE html>
<html lang="en">
  <head>
    <base href="<?php echo $template ?>">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>DrGrab Console</title>

    <!-- Bootstrap -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/jquery.handsontable.full.css" rel="stylesheet">
    <link href="css/main_ffau.css" rel="stylesheet">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <body>
 
	<div class="container">
		
		<div class="row">
			<div id="handtable"></div>
		</div>
		<div class="row">
			<div class="form-group col-md-5">
				<h4>Tracking URL</h4>
			    <label for="exampleInputPassword1">Country</label>
			    	<select class="form-control" id="country">
					  <option value="AU" data-country="1">AU</option>
					  <option value="NZ" data-country="2">NZ</option>
					  <option value="US" data-country="3">US</option>
					  <option value="CA" data-country="4">CA</option>
					  <option value="GB" data-country="5">UK</option>
					  <option value="FR" data-country="6">FR</option>
					  <option value="MY" data-country="7">MY</option>
					  <option value="SG" data-country="8">SG</option>
					  <option value="IE" data-country="9">IE</option>
					  <option value="BE" data-country="10">BE</option>
					  <option value="ES" data-country="11">ES</option>
					</select>
			</div>
			<div class="col-md-5">
			    <div class="post">
					<div class="radio">
					  <label>
					    <input type="radio" name="optionsRadios" id="optionsRadios1" value="eyoubao">
					    E邮宝
					  </label>
					</div>
					<div class="radio">
					  <label>
					    <input type="radio" name="optionsRadios" id="optionsRadios2" value="saicheng">
					    赛诚
					  </label>
					</div>
					<div class="radio">
					  <label>
					    <input type="radio" name="optionsRadios" id="optionsRadios3" value="xiaobao">
					    南昌小包
					  </label>
					</div>
					<div class="radio">
					  <label>
					    <input type="radio" name="optionsRadios" id="optionsRadios4" value="shunfeng">
					    澳洲小包
					  </label>
					</div>
					<div class="radio">
					  <label>
					    <input type="radio" name="optionsRadios" id="optionsRadios5" value="untraceable">
					    平邮（无法追踪）
					  </label>
					</div>
				</div>
				<div class="post">
					<div class="radio">
					  <label>
					    <input type="radio" name="optionsRadios" id="optionsRadios2" value="saicheng">
					    赛诚
					  </label>
					</div>
					<div class="radio">
					  <label>
					    <input type="radio" name="optionsRadios" id="optionsRadios3" value="xiaobao">
					    南昌小包
					  </label>
					</div>
					<div class="radio">
					  <label>
					    <input type="radio" name="optionsRadios" id="optionsRadios5" value="untraceable">
					    平邮（无法追踪）
					  </label>
					</div>
				</div>
				<div class="post">
					<div class="radio">
					  <label>
					    <input type="radio" name="optionsRadios" id="optionsRadios1" value="eyoubao">
					    E邮宝
					  </label>
					</div>
					<div class="radio">
					  <label>
					    <input type="radio" name="optionsRadios" id="optionsRadios2" value="ems">
					    EMS
					  </label>
					</div>
					<div class="radio">
					  <label>
					    <input type="radio" name="optionsRadios" id="optionsRadios3" value="shunfeng">
					    美国顺丰小包
					  </label>
					</div>
					<div class="radio">
					  <label>
					    <input type="radio" name="optionsRadios" id="optionsRadios4" value="xiaobao">
					    南昌小包
					  </label>
					</div>
					<div class="radio">
					  <label>
					    <input type="radio" name="optionsRadios" id="optionsRadios5" value="untraceable">
					    平邮（无法追踪）
					  </label>
					</div>
				</div>
				<div class="post">
					<div class="radio">
					  <label>
					    <input type="radio" name="optionsRadios" id="optionsRadios1" value="eyoubao">
					    E邮宝
					  </label>
					</div>
					<div class="radio">
					  <label>
					    <input type="radio" name="optionsRadios" id="optionsRadios2" value="ems">
					    EMS
					  </label>
					</div>
					<div class="radio">
					  <label>
					    <input type="radio" name="optionsRadios" id="optionsRadios3" value="xiaobao">
					    南昌小包
					  </label>
					</div>
					<div class="radio">
					  <label>
					    <input type="radio" name="optionsRadios" id="optionsRadios5" value="untraceable">
					    平邮（无法追踪）
					  </label>
					</div>
				</div>
				<div class="post">
					<div class="radio">
					  <label>
					    <input type="radio" name="optionsRadios" id="optionsRadios1" value="eyoubao">
					    E 邮宝
					  </label>
					</div>
					<div class="radio">
					  <label>
					    <input type="radio" name="optionsRadios" id="optionsRadios2" value="tekuai">
					    E 特快
					  </label>
					</div>
					<div class="radio">
					  <label>
					    <input type="radio" name="optionsRadios" id="optionsRadios3" value="xiaobao">
					    南昌小包
					  </label>
					</div>
					<div class="radio">
					  <label>
					    <input type="radio" name="optionsRadios" id="optionsRadios5" value="untraceable">
					    平邮（无法追踪）
					  </label>
					</div>
				</div>
				<div class="post">
					<div class="radio">
					  <label>
					    <input type="radio" name="optionsRadios" id="optionsRadios1" value="eyoubao">
					    E邮宝
					  </label>
					</div>
					<div class="radio">
					  <label>
					    <input type="radio" name="optionsRadios" id="optionsRadios3" value="xiaobao">
					    南昌小包
					  </label>
					</div>
					<div class="radio">
					  <label>
					    <input type="radio" name="optionsRadios" id="optionsRadios5" value="untraceable">
					    平邮（无法追踪）
					  </label>
					</div>
				</div>
				<div class="post">
					<div class="radio">
					  <label>
					    <input type="radio" name="optionsRadios" id="optionsRadios1" value="zhuanxian">
					   东南亚专线
					  </label>
					</div>
					<div class="radio">
					  <label>
					    <input type="radio" name="optionsRadios" id="optionsRadios3" value="xiaobao">
					    南昌小包
					  </label>
					</div>
					<div class="radio">
					  <label>
					    <input type="radio" name="optionsRadios" id="optionsRadios5" value="untraceable">
					    平邮（无法追踪）
					  </label>
					</div>
				</div>
				<div class="post">
					<div class="radio">
					  <label>
					    <input type="radio" name="optionsRadios" id="optionsRadios1" value="zhuanxian">
					    东南亚专线
					  </label>
					</div>
					<div class="radio">
					  <label>
					    <input type="radio" name="optionsRadios" id="optionsRadios3" value="xiaobao">
					    南昌小包
					  </label>
					</div>
					<div class="radio">
					  <label>
					    <input type="radio" name="optionsRadios" id="optionsRadios5" value="untraceable">
					    平邮（无法追踪）
					  </label>
					</div>
				</div>
				<div class="post">
					<div class="radio">
					  <label>
					    <input type="radio" name="optionsRadios" id="optionsRadios1" value="saicheng">
					    赛程
					  </label>
					</div>
					<div class="radio">
					  <label>
					    <input type="radio" name="optionsRadios" id="optionsRadios2" value="shunfeng">
					    顺丰
					  </label>
					</div>
					<div class="radio">
					  <label>
					    <input type="radio" name="optionsRadios" id="optionsRadios3" value="xiaobao">
					    南昌小包
					  </label>
					</div>
					<div class="radio">
					  <label>
					    <input type="radio" name="optionsRadios" id="optionsRadios4" value="untraceable">
					    平邮（无法追踪）
					  </label>
					</div>
				</div>
				<div class="post">
					<div class="radio">
					  <label>
					    <input type="radio" name="optionsRadios" id="optionsRadios1" value="saicheng">
					    赛程
					  </label>
					</div>
					<div class="radio">
					  <label>
					    <input type="radio" name="optionsRadios" id="optionsRadios2" value="shunfeng">
					    顺丰
					  </label>
					</div>
					<div class="radio">
					  <label>
					    <input type="radio" name="optionsRadios" id="optionsRadios3" value="xiaobao">
					    南昌小包
					  </label>
					</div>
					<div class="radio">
					  <label>
					    <input type="radio" name="optionsRadios" id="optionsRadios4" value="untraceable">
					    平邮（无法追踪）
					  </label>
					</div>
				</div>
				<div class="post">
					<div class="radio">
					  <label>
					    <input type="radio" name="optionsRadios" id="optionsRadios1" value="saicheng">
					    赛程
					  </label>
					</div>
					<div class="radio">
					  <label>
					    <input type="radio" name="optionsRadios" id="optionsRadios2" value="shunfeng">
					    顺丰ss
					  </label>
					</div>
					<div class="radio">
					  <label>
					    <input type="radio" name="optionsRadios" id="optionsRadios3" value="xiaobao">
					    南昌小包
					  </label>
					</div>
					<div class="radio">
					  <label>
					    <input type="radio" name="optionsRadios" id="optionsRadios4" value="untraceable">
					    平邮(无法追踪)
					  </label>
					</div>
					<div class="radio">
					  <label>
					    <input type="radio" name="optionsRadios" id="optionsRadios5" value="xibanya">
					    赛程平邮
					  </label>
					</div>
				</div>
			</div>
		</div>
		<div class="row text-right">
			<button type="button" class="btn btn-lg btn-danger" id="start">Let's Start</button>
			<button type="button" class="btn btn-lg btn-danger" id="clear">Clear</button>
		</div>
				
				<!-- <br>
				<h4>Notify Customer</h4>
				<div class="checkbox">
				  <label>
				    <input type="checkbox" value="" id="ifnotify" checked>
				    Send Shipping Confirmation Emails to Customers.
				  </label>
				</div> -->
				<!-- 
				<button type="button" class="btn btn-default" id="clear">Clear</button> -->
			
	</div>          
    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/jquery.handsontable.full.js"></script>
    <script>
    	$(".post:gt(0)").hide();
    	$('#country').on('change',function(){
    		$(".post").eq($('#country option').index($('#country option:selected'))).fadeIn(100).siblings('.post').hide();
    	});
    	$("#start").click(function(){
			if (!$("input[name='optionsRadios']:checked").val()) {
			   alert('Please Choose a Tracking URL');
			}    	
			else{
				fulfill();
		    	$(this).attr("disabled","disabled");		
			}
    	})

    	$("#clear").click(function(){
	    	clearTable();
	    	$("#start").attr("disabled",false)
    	})    	

		$('#handtable').handsontable({
			startCols: 6,
 			colWidths: [190,190,200,220,180,120],
			minSpareRows: 10,
			colHeaders: ["Number id","Tracking Number","Send Id","product_sku<br/><span style='color:red;white-space: pre-line;vertical-align: inherit;'>填写的话为部分发货-多个产品逗号分隔</span>","reshipment<br/><span style='color:red;white-space: pre-line;vertical-align: inherit;'>添写的话为重寄发货-第一次重寄填写1 以此类推</span>","Status"]
		});
		
        function fulfill(){			
			var totalRows = $("#handtable").handsontable("countRows")-10-1;
			var orderList = $("#handtable").handsontable("getData","0","0",totalRows,"4");

			var ifNotify = $('#ifnotify').is(":checked");
			loadLoop(0,orderList,ifNotify);
		}

		function clearTable(){
			$("#handtable").handsontable("destroy");
			$('#handtable').handsontable({
				startCols: 6,
				colWidths: [190,190,200,220,180,120],
				minSpareRows: 10,
				colHeaders: ["Number id","Tracking Number","Send Id","product_sku<br/><span style='color:red;white-space: pre-line;vertical-align: inherit;'>填写的话为部分发货-多个产品逗号分隔</span>","reshipment<br/><span style='color:red;white-space: pre-line;vertical-align: inherit;'>添写的话为重寄发货-第一次重寄填写1 以此类推</span>","Status"]
			});			
		}
		
		var loadLoop = function(i,orderList,ifNotify) {
		    var num = i || 0;
		    if(num < orderList.length) {
		        jQuery.ajax({
		        	type: "POST",
		        	url: "<?php echo site_url('fulfillau/index') ?>",
		            data: {
		            	country :$("#country").val(),  
		            	order_number:orderList[i][0],
		            	track_code:orderList[i][1],
		            	track_name:$("input[name='optionsRadios']:checked").val(),
		            	send_bill:orderList[i][2],
		            	product_sku:orderList[i][3],
		            	is_resend:orderList[i][4],
		            	ifNotify:ifNotify
		            },
		            success: function(data){
		                loadLoop(num+1,orderList,ifNotify);
		                $("#handtable").handsontable("setDataAtCell",i,5,data)
		            }
		        });
		    }
		};
    </script>
  </body>
</html>