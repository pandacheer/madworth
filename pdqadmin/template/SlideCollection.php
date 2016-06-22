<!DOCTYPE html>
<html lang="en">
    <head>
		<title>Slideshow</title>
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
                        <div class="col-xs-11 col-sm-6 col-lg-6">
                            <h1><i class="glyphicon glyphicon-check" aria-hidden="true"></i>Slideshow</h1>
                        </div>
                    </div>	
                </div>
                <div id="breadcrumb">
                    <a href="/slideshow" class="tip-bottom"><i class="fa fa-tags"></i> Slideshow </a>
                </div>
                <div class="row">

                    <div class="col-xs-12">
                        <ul class="nav nav-tabs ordernav">
                            <li role="presentation" class="active"><a href="javascript:void(0);">All SlideCollection</a></li>
                        </ul>
                        <div class="row">
                            <div class="widget-box widget-box-hledit">
                                <form method="post" action="<?php echo site_url('slideshow/index') ?>">
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="keyword" value="<?php echo $where;?>" placeholder="Search for...">
                                        <span class="input-group-btn">
                                            <button class="btn btn-default" type="submit">Go!</button>
                                        </span>
                                    </div>
                                </form>
                                <div class="widget-content nopadding">
                                    <table class="table">
                                    	<thead>
                                    		<tr>
                                    			<th>Collection Name</th>
                                    			<th>View/Edit</th>
                                    		</tr>
                                    	</thead>
                                    	<tbody>
                                        <?php
                                        foreach($collection as $vo){
                                            echo '<tr><td><a href="/slideshow/edit/'.$vo['_id'].'">'.$vo['title'].'</a></td><td><button class="btn btn-default"><a href="/slideshow/edit/'.$vo['_id'].'"><i class="fa fa-pencil fa-lg"></i></a></button></td></tr>';
                                        }
                                        ?>
                                    	</tbody>
                                    </table>
                                </div>
                                <ul class="pagination alternate">
                                    <?php if (isset($pages)) echo $pages; ?>
                                </ul>
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
<script src="js/jquery.tagsinput.js"></script>

<script src="js/jquery.icheck.min.js"></script>
<!--左侧nav-->
<script src="js/jquery.nicescroll.min.js"></script>
<script src="js/unicorn.js"></script>
<script src="js/unicorn.jui.js"></script>
<?php echo $foot ?>

    </body>
</html>
