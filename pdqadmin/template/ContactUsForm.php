<!DOCTYPE html>
<html lang="en">
    <head>
        <title>ContactUs List</title>
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
        <div class="modal fade" id="contact-con">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">ContactUs Content</h4>
              </div>
              <div class="modal-body">
                <div class="row">
                  <div class="col-sm-12" id="contact-main-con"></div>
                  <div class="clearfix"></div>
                </div>
              </div>
              <div class="modal-footer">
                <span id="customer_email" style="float: left"></span>
                <button type="button" class="btn btn-default" id="contact-con-close" data-dismiss="modal">Close</button>
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
                            <h1><i class="fa fa-users" aria-hidden="true"></i>ContactUs</h1>
                        </div>
                    </div>	
                </div>
                <div id="breadcrumb">
                    <a href="#" title="Go to ContactUs List" class="tip-bottom"><i class="fa fa-tags"></i> ContactUs </a>
                    <a href="#" class="current">ContactUs List</a>
                </div>
                <div class="row">

                    <div class="col-xs-12">

                        <div class="row">
                            <div class="widget-box widget-box-hledit widget-box-hledit-order-left">
                            <form method="post" action="<?php echo site_url('contact/index') ?>">
 
                              <div class=" order_search row">
                                 <div class="col-sm-3">
                                    <select class="form-control" name="s_status">
                                            <option value="1" <?php echo $whereStatus==1 ? 'selected=selected' : '';?>>未处理</option>
                                            <option value="2" <?php echo $whereStatus==2 ? 'selected=selected' : '';?>>已处理</option>
                                    </select>
                                 </div>
                                   <div class="input-group col-sm-9 nopadding">
                                     <input type="text" id="comment-search" class="form-control" name="txtKeyWords" value="<?php if($where!='ALL')echo $where;?>"  placeholder="Search for...">                                     
                                   <span class="input-group-btn">
                                       <button class="btn btn-default" type="submit">Go!</button>
                                   </span>
                                  </div>
                              </div>

                            </form>
                                <div class="widget-content nopadding">
                                    <table class="table table-striped table-hover table-striped contact-table">
                                        <thead>
                                            <tr>
                                                <th>Email</th>
                                                <th>country</th>
                                                <th>Choose</th>
                                                <th>CreateTime</th>
                                                <th>Content</th>
                                                <th>Operator</th>
                                            </tr>
                                        </thead>
                                        <tbody>
										<?php foreach ($contacts as $contact): ?>
                                            <tr>
                                                <td class="email"><a href="mailto:<?=$contact['email']?>"><?=$contact['email']?></a></td>
                                                <td><?=$contact['country_code']?></td>
                                                <td>
                                                   <?php if ($contact['contack_type']==5):?>
                                                   		I want to be a DrGrab vendor
                                                   <?php elseif ($contact['contack_type']==6):?>
                                                   		I would like to make a suggestion
                                                   <?php else:?>
                                                   		None of the above
                                                   <?php endif; ?>
                                                </td>
                                                <td><?=date('Y-m-d H:i:s', $contact['_id'])?></td>
                                                <td><span data-toggle="modal" class="btn-xs contact-content" data-target="#contact-con"><?=$contact['content']?></span></td>
                                                <td>
                                                	<?php if ($contact['status']==2):?>
                                                		<?=$contact['operator']?>
                                                	<?php else:?>
                                                		<div class="input-group">
                                                      		<span id="contactResult_<?= $contact['_id'] ?>">
                                                        		<button class="btn btn-success" type="button" id="up_<?= $contact['_id'] ?>"  onClick="update('<?= $contact['_id'] ?>'); return false;">confirm</button>
                                                      		</span>
                                                    	</div>
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
        
        
        <script>
        	 $('.btn-xs').click(function(){
           		 $content = $(this).html();
           		 $('#contact-main-con').html($content);
               $email=$(this).parent().parent().find('.email').text()
               $('#customer_email').html('Reply ：<a href="mailto:'+$email+'">'+$email+'</a>')
            	 $('#contact-con').on('shown.bs.modal', function () {
            	})
        	 });


        	 function update(id) {
           	  $.ajax({
               	type: "POST",
                   url: "<?php echo site_url('contact/updateStatus') ?>",
                   dataType: 'json',
                   data: {
                	   contact_id: id,
                   },
                   success: function (result) {
                       if (result.success) {
   						$("#contactResult_"+id).text(result.message);
                       	$.notifyBar({cssClass: "dg-notify-success", html: "修改成功", position: "bottom"});
                       }else{
                       	$.notifyBar({cssClass: "dg-notify-error", html: "修改失败", position: "bottom"});
                       }
                   }
                });
           }
        	 

        </script>
        
        
        
		<?php echo $foot ?>
    </body>
</html>
