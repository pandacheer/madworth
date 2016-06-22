<!DOCTYPE html>
<html lang="en">
    <head>
        <base href="<?php echo $template ?>">
        <title>Countdown Add</title>
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
            <?php echo $head; ?>
            <div id="content">
                <div id="content-header" class="mini">
                    <div class="row"><div class="col-xs-12">
                            <h1>New Countdown</h1>
                        </div></div>  
                </div>

                <div id="breadcrumb">
                    <a href="/countdown" title="Go to Countdown List" class="tip-bottom"><i class="fa fa-tags"></i>Countdown</a>
                    <a class="current">Add a Countdown</a>
                </div>

                <div class="row">
                    <form method="post" action="<?php echo site_url('countdown/insert') ?>">
                        <div class="Countdown-box">
                            <h3 class="titleh3">Name</h3>
                            <input type="text" class="form-control" id="CountdownName" placeholder="Countdown Name" name="name">
                        </div>		
                        <div class="Countdown-box">
                            <div class="Countdown-box-right">
                                <h3 class="titleh3">Date Range</h3>
                                <div class="checkbox">
                                  <label>
                                    <input type="checkbox" value="2" name="auto_recount">
                                    Automatically restart the Countdown once it ends
                                  </label>
                                </div>                                
                                <div class="col-xs-12 datetimebox">
                                    <div class="col-xs-12 StartDatebox">
                                        <b class="col-sm-2">Start Date: </b> 
                                        <h5 class="col-sm-4">
                                            <div class="input-group">
                                                <div class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></div>
                                                <input id="ui-datepicker" type="text" class="form-control input-sm" placeholder="Start" name="start"/>
                                            </div>
                                        </h5>
                                        <h5 class="col-sm-2"><input type="text" id="startTime" class="form-control" value="0:00" name="startTime"></h5>
                                    </div>
                                    <div class="col-xs-12 EndDatebox">	
                                        <b class="col-sm-2">End Date: </b> 
                                        <h5 class="col-sm-4">
                                            <div class="input-group">
                                                <div class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></div>
                                                <input id="ui-datepicker-2" type="text" class="form-control input-sm" placeholder="End" name="end"/>
                                            </div>
                                        </h5>
                                        <h5 class="col-sm-2"><input type="text" id="endTime" class="form-control" value="0:00" name="endTime"></h5>
                                    </div>	
                                    <div class="clearfix"></div>
                                </div><div class="clearfix"></div>
                            </div>
                        </div>
                        <div class="Countdown-box">
                            <div class="Countdown-box-right">
                                <h3 class="titleh3">Cents</h3>
                                <div class="checkbox">
                                  <label>
                                    <input type="checkbox" value="1" name="saveDecimal">
                                    Override the cents on the calculated price.
                                  </label>
                                </div>
                                <div class="datetimebox col-sm-4">
                                    <div class="form-group">
                                        <div class="input-group">
                                            <div class="input-group-addon">€ 0.</div>
                                            <input type="text" class="form-control" id="exampleInputAmount" placeholder="99" value="99" name="decimal">
                                        </div>
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                        </div>
                        <div class="Countdown-box">
                            <div class="Countdown-box-right">
                                <h3 class="titleh3">Discount</h3>
                                <div class="col-xs-12 datetimebox">
                                    <div class="col-xs-12 StartDatebox">
                                        <b class="col-sm-1">Discount: </b> 
                                        <h5 class="col-sm-2">
                                            <input type="text" class="form-control" value="0" name="credits"/>
                                        </h5>
                                        <h5 class="col-sm-2">
                                            <select class="form-control" name="type">
                                                <option value="1">%</option>
                                                <option value="2">€</option>
                                            </select>
                                        </h5>
                                    </div>

                                    <div class="clearfix"></div>
                                </div><div class="clearfix"></div>
                            </div>
                        </div>
                        <div class="col-xs-12">
                            <div class="text-right bottom-btn">
                                <button type="submit" id="Create" class="btn btn-default btn-bgcolor-blue">Create</button>
                            </div>	
                        </div>
                    </form>
                </div>	
            </div>
            <div class="row">
                <div id="footer" class="col-xs-12"></div>
            </div>
        </div>

        <script src="js/jquery.min.js"></script>
        <script src="js/jquery-ui.custom.js"></script>
        <script src="js/bootstrap.min.js"></script>
        <!--左侧nav-->
        <script src="js/jquery.nicescroll.min.js"></script>
        <script src="js/unicorn.js"></script>
        <script src="js/jquery.icheck.min.js"></script>

        <script src="js/unicorn.jui.js"></script>
        <script>
            $('#Create').click(function(){
             if ($('#CountdownName').val()==''){
                 alert('Countdown Name 不能为空');
                 return false;
             }
             else if ($('#ui-datepicker').val()==''){
                 alert('start不能为空');
                 return false;
             }
             else if ($('#ui-datepicker-2').val()==''){
                 alert('end不能为空');
                 return false;
             }

             else if ($('#startTime').val()==''){
                 alert('起始时间分钟不能为空');
                 return false;
             }
             else if ($('#endTime').val()==''){
                 alert('结束时间分钟不能为空');
                 return false;
             }
            })
        </script>
        <?php echo $foot ?>

    </body>
</html>
