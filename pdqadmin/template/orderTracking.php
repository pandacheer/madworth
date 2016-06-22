<!DOCTYPE html>
<html lang="en">
    <head>
        <base href="<?php echo $template ?>">
        <title>Discounts</title>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
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
                        <div class="col-xs-12 col-sm-6 col-lg-6">
                            <h1><i class="fa fa-users" aria-hidden="true"></i>orderComplaintsList</h1>
                        </div>
                        <div class="clearfix"></div>
                    </div>	
                </div>

                <div class="row">
                
                    <div class="col-xs-12">

                        <div class="row">
                            <div class="widget-box widget-box-hledit widget-box-hledit-order-left">

                                <?php echo form_open('orderTracking/getExcel'); ?>
                                <div class="row order-Complaints-List-time">
                                    <div class="col-xs-12 col-sm-3 col-lg-3">
                                        <b>Begins:</b> 
                                        <h5>
                                            <input id="ui-datepicker" type="text" class="form-control" name="timeStart" />
                                        </h5>
                                    </div>
                                    <div class="col-xs-12 col-sm-3 col-lg-3">
                                        <b>End:</b> 
                                        <h5>
                                            <input id="ui-datepicker-2" type="text" class="form-control" name="timeEnd" />
                                        </h5>
                                    </div>
                                    <div class="col-xs-12 col-sm-2 col-lg-2">
                                        <button class="btn btn-default btn-bgcolor-white"  type="submit">
                                            导出数据
                                        </button>
                                    </div>
                                    <div class="clearfix"></div>
                                </div>
                                </form>

                                <div class="widget-content nopadding">
                                    <table class="table table-striped table-hover Customerslist-table">
                                        <thead>
                                            <tr>
                                                <th>订单号</th>
                                                <th>顾客姓名</th>
                                                <th>发货单</th>
                                                <th>发货日期</th>
                                                <th>跟踪号/单号</th>
                                                <th>操作人</th>
                                                <th>查看详情</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                          <?php foreach ($complaintsList as $complaintsList): ?>
                                            <tr>
                                                <td><?=$complaintsList['order_number']?></td>
                                                <td><?=$complaintsList['member_name']?></td>
                                                <td><?=$complaintsList['send_bill']?></td>
                                                <td><?=date('Y-m-d H:i:s', $complaintsList['send_time'])?></td>
                                                <td><?=$complaintsList['track_code']?></td>
                                                <td><?=$complaintsList['operator']?></td>
                                                <td>
                                                   <a href="/orderTrackingContent/<?=$complaintsList['complaints_id']?>">
                                                    <button class="btn btn-default btn-sm">
                                                        <i class="fa fa-eye"></i> 
                                                    </button>
                                                    </a>
                                                </td>
                                            </tr> 
                                         <?php endforeach ?>                              
                                        </tbody>
                                    </table>    
                                </div>
                            </div>	
                        </div>	
                        <ul class="pagination alternate">
                            <?php if (isset($pages)) echo $pages ?>
                        </ul>
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
        <script src="js/unicorn.icheckbox.js"></script>
        <?php echo $foot ?>

        <script >
        $(document).ready(function(){
            $('.fa-trash-o').parents('button').click(function(){
                if(!$(this).parent().parent().parent().hasClass('collectionadd-hidebox-inputbox-one')){
                    var r=confirm("Are you sure you want to delete ?")
                    if (r==true){
                        $(this).parent().parent(".collectionadd-hidebox-inputbox").remove();
                        return false;
                    }else{ return false;}               
                }
            });
        });
        </script>
    </body>
</html>
