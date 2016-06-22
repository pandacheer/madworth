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
		<!--[if lt IE 9]>
		<script type="text/javascript" src="js/respond.min.js"></script>
		<![endif]-->
	</head>
	<body data-color="grey" class="flat">
		<div id="wrapper">
			<?php echo $head ?>

			<div id="content">
				<div id="content-header" class="mini">
					<div class="row">
						<div class="col-xs-12 col-sm-6 col-lg-6" style="width:30%;">
							<h1><i class="fa fa-glass" aria-hidden="true"></i>Products</h1>
						</div>	
						<div class="col-xs-12 col-sm-6 col-lg-6" style="width:70%;">
							<div class="pull-right">
                                                            <select class="selectbox" onchange="addtolist(this);">
                                                                <option value="0">undefined</option>
									<?php
									foreach($collection as $vo){
										echo '<option value="'.$vo['_id'].'">'.$vo['title'].'</option>';
									}
									?>
                                                            </select>
								<a id="addtolist" class="btn btn-default btn-bgcolor-white"><i class="fa fa-cart-plus"></i> Add to List</a>
								<a href="/productCart" class="btn btn-default btn-bgcolor-white"><i class="fa fa-list-alt"></i> Edit List ( <span id="ListNumber"><?php echo $listnumber ?></span> )</a>
								<a href="/product/add" class="btn btn-default btn-bgcolor-blue"><i class="fa fa-plus-circle"></i> Create a product</a>
							</div>	
						</div>	
				   </div>
				</div>
				
				<div class="row">
					<div class="col-md-6">
						<form id="dg-search">
							<div class="input-group">
								<input name="keyword" id="search-url-trigger-value" type="text" class="form-control">
								<span class="input-group-btn">
									<button class="btn btn-primary" type="submit"><i class="fa fa-search"></i> Search</button>
								</span>
							</div>
						</form>
					</div>
				</div>
				
				<div class="row row-gap">
					<div class="col-md-9 dg-product-condition-container">
						<div class="btn-group">
							<a href="#" data-toggle="dropdown" class="btn btn-primary dropdown-toggle"><i class="fa fa-plus icon-white"></i> Condition</a>
							<ul class="dropdown-menu dropdown-primary">
						      <li><a href="#" data-toggle="modal" data-target="#modalProductType"><i class="fa fa-th-large"></i> Product Type</a></li>
						      <li><a href="#" data-toggle="modal" data-target="#modalTag"><i class="fa fa-tag"></i> Product Contains Tag</a></li>
						      <li><a href="#" data-toggle="modal" data-target="#modalCollection"><i class="fa fa-tags"></i> collection</a></li>
						      <li><a href="#" data-toggle="modal" data-target="#modalPrice"><i class="fa fa-dollar"></i> Price</a></li>
						      <li><a href="#" data-toggle="modal" data-target="#modalCreator"><i class="fa fa-user"></i> Creator</a></li>
						    </ul>
						</div>		
  			  		</div>
                    <div class="col-md-3">
						<div class="form-group pull-right">
						    <span>Sort By</span>
                            <select class="selectbox" id="productSort">
    							<option value="1">Date:Latest</option>
    							<option value="2">Date:Oldest</option>
    							<option value="3">Best selling</option>
    							<option value="4">Price:Highest</option>
    							<option value="5">Price:Lowest</option>
						    </select>
						</div>
				    </div>
				</div>
				
				<div class="row">
					<div class="col-xs-12">
						<div class="widget-box widget-box-hledit">
							<div class="widget-title">
								<h3 class="widget-title-h3">All Products</h3>
							</div>
							<div class="widget-content nopadding">
								<table class="table table-striped table-hover with-check table-productlist">
									<thead>
										<tr>
											<th class="select"><input type="checkbox" id="title-checkbox" name="title-checkbox" /></th>
											<th class="image"></th>
											<th class="Productth">Product</th>
											<th class="prosku">SKU</th>
											<th class="prostype">Type</th>
											<th>Creator</th>
                                                                                        <th>Last</th>
											<th class="select">Status</th>
										</tr>
									</thead>
									
									<tbody>
									<?php
									if(isset($list)){
										foreach($list as $vo) {
											switch($vo['status']){
												case 1:$status='In Stock';$button='<a class="btn btn-success" href="/product/hidden/'.$vo['_id'].'/?'.$_SERVER['QUERY_STRING'].'">In Stock</a>';break;
												case 2:$status='Hidden';$button='<a class="btn btn-danger" href="/product/recover/'.$vo['_id'].'/?'.$_SERVER['QUERY_STRING'].'">Hidden</a>';break;
												case 3:$status='Out Of Stock';$button='';break;
											};
                                            $img = IMAGE_DOMAIN.'/product/'.$vo['sku'].'/'.$vo['sku'].'.jpg';
                                            if(!@fopen($img,'r')&&  is_string($vo['image'])){
                                                $img = IMAGE_DOMAIN.$vo['image'];
                                            }
										echo '
										<tr>
											<td class="select"><input type="checkbox" value="'.$vo['_id'].'" /></td>
											<td class="image">
											<a href="/product/edit/'.$vo['_id'].'"><img alt="'.htmlspecialchars_decode($vo['title']).'" class="block" src="'.$img.'"></a>
											</td>
											<td class="Productth">
												<div class="tabletitle">
													<a href="/product/edit/'.$vo['_id'].'">'.htmlspecialchars_decode($vo['title']).'</a>
													<h6 class="subdued">'.$status.'</h6>
												</div>
											</td>
											<td class="prosku">'.$vo['sku'].'</td>
											<td class="prostype">'.$vo['typetitle'].'</td>
											<td>'.$vo['creator'].'</td>
                                                                                         <td>'.date('y/m/d H:i:s',$vo['update_time']).'</td>
											<td>'.$button.'</td>
										</tr>
										';
										}
									}else{
										echo '<tr><td colspan="7">Sorry, no products matched your selection.</td></tr>';
									}
									?>
									</tbody>
								</table>
								<ul class="pagination alternate">
								<?php echo isset($page) ? $page : '';?>
								</ul>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="row">
				<div id="footer" class="col-xs-12"></div>
			</div>
			
            <!-- Modal Product Type-->
            <div class="modal fade" id="modalProductType" tabindex="-1" role="dialog" aria-labelledby="producttypemodalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title" id="producttypemodalLabel">Add a Condition</h4>
                        </div>
                        <div class="modal-body clearfix">
                    	    <div class="form-group">
                    		    <div class="col-sm-9 col-md-9 col-lg-10">
                                    <span>Product Type = </span>
                        			<select id="dg-productType" class="selectbox">
									<?php
									foreach($type as $vo){
										echo '<option value="'.$vo['_id'].'">'.$vo['title'].'</option>';
									}
									?>
                        			</select>
                    		    </div>
                    	    </div>
                        </div>
                        <div class="modal-footer">
                    	    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary modal-url-trigger" data-trigger="productType">Submit</button>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Modal Tag-->
            <div class="modal fade" id="modalTag" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title">Add a Condition</h4>
                        </div>
                        <div class="modal-body clearfix">
                    	    <div class="form-group">
                    		    <div class="col-sm-9 col-md-9 col-lg-10">
                                    <span>Tag = </span>
									<select id="dg-tag" class="selectbox">
									<?php
									foreach($tag as $vo){
										echo '<option value="'.$vo['_id'].'">'.$vo['title'].'</option>';
									}
									?>
									</select>
                    		    </div>
                    	    </div>
                        </div>
                        <div class="modal-footer">
                    	    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary modal-url-trigger" data-trigger="tag">Submit</button>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Modal Collection-->
            <div class="modal fade" id="modalCollection" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title">Add a Condition</h4>
                        </div>
                        <div class="modal-body clearfix">
                    	    <div class="form-group">
                    		    <div class="col-sm-9 col-md-9 col-lg-10">
                                    <span>Collection = </span>
                        			<select id="dg-collection" class="selectbox">
									<?php
									foreach($collection as $vo){
										echo '<option value="'.$vo['_id'].'">'.$vo['title'].'</option>';
									}
									?>
                        			</select>
                    		    </div>
                    	    </div>
                        </div>
                        <div class="modal-footer">
                    	    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary modal-url-trigger" data-trigger="collection">Submit</button>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Modal Price-->
            <div class="modal fade" id="modalPrice" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title">Add a Condition</h4>
                        </div>
                        <div class="modal-body clearfix">
                    	    <div class="form-group">
                    		    <div class="col-sm-9 col-md-9 col-lg-10">
                                    <span>Price between </span>
                                    <div class="input-group" style="padding-top:5px">
                                        <input type="text" class="form-control" id="dg-price-min" placeholder="Min" name="min" value="0">
                                        <div class="input-group-addon">to</div>
                                        <input type="text" class="form-control" id="dg-price-max" placeholder="Max" name="max" value="999">
                                    </div>
                    		    </div>
                    	    </div>
                        </div>
                        <div class="modal-footer">
                    	    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary modal-url-trigger" data-trigger="price">Submit</button>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Modal Creator-->
            <div class="modal fade" id="modalCreator" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title">Add a Condition</h4>
                        </div>
                        <div class="modal-body clearfix">
                    	    <div class="form-group">
                                <div class="col-sm-9 col-md-9 col-lg-10">
                                    <span>Creator = </span>
                                    <select id="dg-creator" class="selectbox">
									<?php
									foreach($creator as $vo){
										echo '<option value="'.$vo->user_id.'">'.$vo->user_account.'</option>';
									}
									?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                    	    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary modal-url-trigger" data-trigger="creator">Submit</button>
                        </div>
                    </div>
                </div>
            </div>
            
		</div>
		<script src="js/jquery.min.js"></script>
		<script src="js/jquery-ui.custom.js"></script>
		<script src="js/bootstrap.min.js"></script>
		<script src="js/jquery.icheck.min.js"></script>
		<script src="js/select2.min.js"></script>
		<script src="js/jquery.dataTables.min.js"></script>
		<!--左侧nav-->
		<script src="js/jquery.nicescroll.min.js"></script>
		<script src="js/unicorn.js"></script>
		<script src="js/unicorn.icheckbox.js"></script>
		<?php echo $foot; ?>

        <script>
        $(function() {
            $('.modal-producttype').click(function(){
              var newtab = '<div class="btn-group dg-product-condition"><button class="btn btn-default"><i class="fa fa-th-large"></i> Product Type = '+$('#dg-product-type-add').find("option:selected").text()+'</button><button class="btn btn-default dg-condition-close"><i class="fa fa-close"></i></button></div>';
              $('.dg-product-condition-container').append(newtab);
              $('#producttypemodal').modal('hide');
            })
            $('#addtolist').click(function(){
              var i = 0,arr = new Array();
              $('input:checked:not(#title-checkbox)').each(function(){
                arr[i] = $(this).val();
                i++;
              })
              $.ajax({
                type:'POST',
                url:"/productCart/add",
                data:{_id:arr},
                success:function(data){
                  if(data['status'] == 200){
                    $('#ListNumber').text(data['listnumber']);
                  }else{
                      alert(data['listnumber']);
                  }
                }
              })
            });

			queryText = {
				"productType":"<?php echo isset($_GET['productType'])? urlencode($_GET['productType']) : '' ?>",
				"tag":"<?php echo isset($_GET['tag'])? urlencode($_GET['tag']) : '' ?>",
				"collection":"<?php echo isset($_GET['collection'])? urlencode($_GET['collection']) : '' ?>",
				"creator":"<?php echo isset($_GET['creator'])? urlencode($_GET['creator']) : '' ?>",
				"price":"<?php echo isset($_GET['price'])? urlencode($_GET['price']) : '' ?>",
				"search":"<?php echo isset($_GET['search'])? urlencode($_GET['search']) : '' ?>",
				"sortBy":"<?php echo isset($_GET['sortBy'])? urlencode($_GET['sortBy']) : '' ?>"
			};
        
            $.each(queryText,function(name,value){
                switch (name) {
                  case "productType":
                    if(value!==""){
                        var condition = '<div class="btn-group dg-product-condition"><button class="btn btn-default"><i class="fa fa-th-large"></i> Product Type = '+ decodeURIComponent(value) +'</button><button class="btn btn-default dg-condition-close" data-trigger="productType"><i class="fa fa-close"></i></button></div>';
                        $('.dg-product-condition-container').append(condition); 
                    }
                    break;
                  case "tag":
                    if(value!==""){
                        var condition = '<div class="btn-group dg-product-condition"><button class="btn btn-default"><i class="fa fa-tag"></i> Tag = '+ decodeURIComponent(value) +'</button><button class="btn btn-default dg-condition-close" data-trigger="tag"><i class="fa fa-close" data-trigger="tag"></i></button></div>';
                        $('.dg-product-condition-container').append(condition); 
                    }
                    break;
                  case "collection":
                    if(value!==""){
                        var condition = '<div class="btn-group dg-product-condition"><button class="btn btn-default"><i class="fa fa-tags"></i> Collection = '+ decodeURIComponent(value) +'</button><button class="btn btn-default dg-condition-close" data-trigger="collection"><i class="fa fa-close"></i></button></div>';
                        $('.dg-product-condition-container').append(condition); 
                    }
                    break;
                  case "creator":
                    if(value!==""){
                        var condition = '<div class="btn-group dg-product-condition"><button class="btn btn-default"><i class="fa fa-user"></i> Creator = '+ decodeURIComponent(value) +'</button><button class="btn btn-default dg-condition-close" data-trigger="creator"><i class="fa fa-close"></i></button></div>';
                        $('.dg-product-condition-container').append(condition); 
                    }
                    break;
                  case "price":
                    if(value!==""){
                        var condition = '<div class="btn-group dg-product-condition"><button class="btn btn-default"><i class="fa fa-dollar"></i> Price = '+ decodeURIComponent(value) +'</button><button class="btn btn-default dg-condition-close" data-trigger="price"><i class="fa fa-close"></i></button></div>';
                        $('.dg-product-condition-container').append(condition); 
                    }
                    break;
                  case "sortBy":
                    if(value!==""){
                        $('#productSort').val(value);
                    }
                    break;
                  case "search":
                    if(value!==""){
                        v = decodeURIComponent(value);
                        $("[name='keyword']").val(v)
                    }
                    break;
                  default:
                }
            })

            $('.selectbox').select2();

            function dgRedirect(text,value,remove){
                if(remove=="remove"){
                    delete queryText[text];
                }else{
                    queryText[text]=value;
                }
                url = "/product/?";
                $.each(queryText,function(name,value){
                    if(value && typeof(value)=='string'){
                        value = value.replace(/\+/g,' ');
                    }

                    url = url + name + "=" + encodeURIComponent(decodeURIComponent(value)) + "&";
                })
                url = url.substring(0, url.length - 1);
                window.location.href = url;
            }
            
            $(".modal-url-trigger").click(function(){
                text = $(this).data('trigger');
                if(text == "price"){
                    value = $("#dg-price-min").val()+"-"+$("#dg-price-max").val();
                }
                else{
                    value = $("#dg-"+text+" option:selected").text();
                }
                dgRedirect(text,value);
            })
            
            $(".pager-url-trigger").click(function(){
                text = "page";
                value = $(this).data('value');
                dgRedirect(text,value);
            })
            
            $('#dg-search').submit(function(event){
                event.preventDefault();
                text = "search";
                value = $("#search-url-trigger-value").val();
                dgRedirect(text,value);
            });

            $('#search-url-trigger-value').keypress(function(e){
                var keycode = e.charCode;
                    if(keycode == 13){
                    	text = "search";
		                value = $("#search-url-trigger-value").val();
		                dgRedirect(text,value);
                    }
                    
            });

            
            $("#productSort").change(function(){
                text = "sortBy";
                value = $("#productSort").val();
                dgRedirect(text,value);
            })
            
            $(".dg-condition-close").click(function(){
                text = $(this).data('trigger')
                value = "";
                dgRedirect(text,value,"remove");
            })
        });
        function addtolist(selectobj){
            var so = selectobj.value;
            if(so==0){
                return false;
            }
            $.ajax({
                type:'POST',
                url:"/productCart/add",
                data:{_id:selectobj.value},
                success:function(data){
                  if(data['status'] == 200){
                    $('#ListNumber').text(data['listnumber']);
                  }else{
                      alert(data['listnumber']);
                  }
                }
              })
          }
        </script>
	</body>
</html>