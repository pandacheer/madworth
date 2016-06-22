<!DOCTYPE html>
<html lang="en">
	<head>
		<title>Discounts</title>
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
						<div class="col-xs-12 col-sm-6 col-lg-6">
							<h1><i class="fa fa-users" aria-hidden="true"></i>Pages</h1>
						</div>	
						<div class="col-xs-12 col-sm-6 col-lg-6">
							<div class="text-right">
								<a href="/pagesContent" class="btn btn-default btn-bgcolor-blue">Add</a>
							</div>
						</div>
                    </div>
				</div>
				<div id="breadcrumb">
					<a href="#" title="Go to Discounts List" class="tip-bottom"><i class="fa fa-tags"></i> Online Store </a>
					<a class="current">Pages</a>
				</div>
				<div class="row">
				
					<div class="col-xs-12">
					   <div class="row">
					     <div class="widget-box widget-box-hledit widget-box-hledit-order-left">
							<div class="pages-table">
								<table class="table table-striped table-hover">
									<thead>
										<tr>
											<th>Tittle</th>
											<th>Last Modified</th>
										</tr>
									</thead>
									<tbody>
										<?php foreach ($pages_list as $list): ?>
										<tr>
											<td>
												<a href="/pagesContent/updatePages/<?=$list['_id']?>"><?=$list['pages_title']?></a>
												<p><?=$list['description']?></p>
											</td>
											<td>
											  <?=date('Y-m-d H:i:s', $list['update_time'])?>
											</td>
										</tr>
			                            <?php endforeach ?>
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

<script src="js/jquery.min.js"></script>
<script src="js/jquery-ui.custom.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/jquery.icheck.min.js"></script>
<script src="js/select2.min.js"></script>

<script src="js/jquery.nicescroll.min.js"></script>
<script src="js/unicorn.js"></script>
<script src="js/unicorn.icheckbox.js"></script>
<?php echo $foot ?>

	</body>
</html>
