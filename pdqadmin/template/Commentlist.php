<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Comment List</title>
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
        <link href="css/star-rating.min.css" rel="stylesheet"> 
        <!--[if lt IE 9]>
        <script type="text/javascript" src="js/respond.min.js"></script>
        <![endif]-->
            
    </head> 
    <body data-color="grey" class="flat">
        <div class="modal fade" id="comment-con">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Product Comment</h4>
              </div>
              <div class="modal-body">
                <div class="row">
                  <div class="col-sm-12" id="comment-main-con"></div>
                  <div class="clearfix"></div>
                </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-default" id="comment-con-close" data-dismiss="modal">Close</button>
              </div>
            </div><!-- /.modal-content -->
          </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->
        <div id="wrapper">
           <?php echo $head ?>

            <div id="content">
                <div id="content-header" class="mini">
                    <div class="row">
                        <div class="col-xs-12 col-sm-6 col-lg-6">
                            <h1><i class="fa fa-users" aria-hidden="true"></i>Comment</h1>
                        </div>
                    </div>	
                </div>
                <div id="breadcrumb">
                    <a href="#" class="tip-bottom"><i class="fa fa-tags"></i> Comment </a>
                    <a href="#" class="current">Comment List</a>
                </div>
                <div class="row">

                    <div class="col-xs-12">

                        <div class="row">
                            <div class="widget-box widget-box-hledit widget-box-hledit-order-left">
                            <form method="post" action="<?php echo site_url('comment/index') ?>">
                                <div class="input-group">
                                    <input type="text" id="comment-search" class="form-control" name="txtKeyWords" value="<?php if($where!='ALL')echo $where;?>"  placeholder="Search for...">
                                    <span class="input-group-btn">
                                        <button class="btn btn-default" id="comment-submit" type="submit">Go!</button>
                                    </span>
                                </div>
                            </form>    
                                <div class="widget-content nopadding">
                                    <table class="table table-striped table-hover table-striped comment-table">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Product</th>
                                                <th>Commenttime</th>
                                                <th>Comment</th>
                                                <th>Stars</th>
                                                <th>Check</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        
                                          <?php foreach ($comments as $comment): ?>
                                            <tr id="comment_<?=$comment['_id']?>">
                                                <td><a href="/ordersContent/<?=$comment['order_number']?>"><?=$comment['_id']?></a></td>
                                                <td>
                                                    <a href="/product/edit/<?=$comment['product_id']?>"><?=htmlspecialchars_decode($comment['product_name'])?></a>
                                                </td>
                                                <td><?=date('Y-m-d H:i:s', $comment['create_time'])?></td>
                                                <td>
                                                <span><?=$comment['product_comment']?></span>
                                                <button class="btn btn-xs btn-default" data-toggle="modal" data-target="#comment-con"><i class="fa fa-eye"></i></button></td>
                                                <td>
                                                    <input type="text" data-size="xxxs" data-whatever="@mdo" value="<?=$comment['product_star']?>" class="rating">
                                                </td>
                                                <td>
                                                <?php if ($comment['status']==1):?>
                                                    <button class="btn btn-info" id="up_<?= $comment['_id'] ?>" type="button"  onClick="update('<?= $comment['_id'] ?>',2); return false;"><i class="fa fa-check"></i></button>
                                                    <button class="btn btn-danger" type="button" id="cancel_<?= $comment['_id'] ?>" onClick="update('<?= $comment['_id'] ?>',3); return false;"><i class="fa fa-times fa-lg"></i></button>
                                                    <span id="commentResult_<?= $comment['_id'] ?>"></span>
                                                <?php elseif ($comment['status']==2):?>
                                                    	已审核通过
                                                <?php else:?>
                                                  		已审核未通过     
                                                <?php endif; ?>    
                                                    
                                                </td>
                                            </tr>
                                          <?php endforeach; ?>
                                          
                                        </tbody>
                                    </table>
                                    <ul class="pagination alternate">
                                		<?php if (isset($pages)) echo $pages ?>
                        		    </ul>	
                                </div>
                            </div>	
                        </div>		
                    </div>
                    <div class="row">
                        <div id="footer" class="col-xs-12"></div>
                    </div>
                </div>
            </div>
        </div>


        <script src="js/jquery.min.js"></script>
        <script src="js/jquery-ui.custom.js"></script>
        <script src="js/bootstrap.min.js"></script>
        <script src="js/jquery.icheck.min.js"></script>
        <script src="js/select2.min.js"></script>

        <script src="js/jquery.nicescroll.min.js"></script>
        <script src="js/unicorn.js"></script>
        <script src="js/unicorn.jui.js"></script>
        <script src="js/highlight.js"></script>
        <script src="js/bootstrap-switch.js"></script>
        <script src="js/main.js"></script>
        <script src="js/jquery.notifyBar.js"></script>
        <script src="js/star-rating.min.js"></script>
        <script >

        $('#comment-search').keypress(function(e){
            var keycode = e.charCode;
                if(keycode == 13)
                $('#comment-submit').click();
        });

        
        function update(id,status) {
        	  $.ajax({
            	type: "POST",
                url: "<?php echo site_url('comment/updateStatus') ?>",
                dataType: 'json',
                data: {
                	comment_id: id,
                	status:status
                },
                success: function (result) {
                    if (result.success) {
                    	$("#up_"+id).fadeToggle(2000);
                    	$("#cancel_"+id).fadeToggle(2000);
						if(status==2){
							$("#commentResult_"+id).text("已审核通过");
						}else{
							$("#commentResult_"+id).text("已审核未通过");
						}

                    	
                    	$.notifyBar({cssClass: "dg-notify-success", html: "修改成功", position: "bottom"});
                    }else{
                    	$.notifyBar({cssClass: "dg-notify-error", html: "修改失败", position: "bottom"});
                    }
                }
             });
        }



        $('.btn-xs').click(function(){
            $content = $(this).siblings('span').text();
            $('#comment-main-con').text($content);
            $('#comment-con').on('shown.bs.modal', function () {
              
            })
        });
        $('#comment-con-close').on('click',function(){
            $('#comment-main-con').text('');
        });
        $('.rating').rating({
            disabled: true
        });
        </script>
        <?php echo $foot ?>
    </body>
</html>
