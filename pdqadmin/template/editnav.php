<!DOCTYPE html>
<html lang="en">
	<head>
		<title>Navigation</title>
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
		<link rel="stylesheet" href="css/editnav.css" />

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
							<h1><i class="glyphicon glyphicon-road" aria-hidden="true"></i>Navigation</h1>
						</div>	
						<div class="col-xs-12 col-sm-6 col-lg-6">
							<div class="pull-right">
							</div>	
						</div>	
				  </div>
					
				</div>
				<div class="row">
					<form method="post" action="/navigation/update">
					<!--pagebegin-->
					<div id="editnav-page" class="col-xs-12">
						 <div class="cf nestable-lists">
							<div class="additembox"><button type="button" class="btn btn-primary btn-bgcolor-blue" id="additem">New Link</button></div>
							<div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="addModalLabel" aria-hidden="true">
							  <div class="modal-dialog">
								<div class="modal-content">
								  <div class="modal-header">
									<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
									<h4 class="modal-title" id="exampleModalLabel">New Link</h4>
								  </div>
								  <div class="modal-body">
									  <div class="form-group">
										<label for="edittitle" class="control-label">Title:</label>
										<input type="text" class="form-control" id="addtitle">
									  </div>
									  <div class="form-group">
										<label for="editurl" class="control-label">Link:</label>
										<textarea class="form-control" id="addurl"></textarea>
									  </div>
								  </div>
								  <div class="modal-footer">
									<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
									<button type="button" class="btn btn-primary"  id="addbtn">Save</button>
								  </div>
								</div>
							  </div>
							</div>
						
							<div class="dd" id="nestable">
								<ol class="dd-list dd-list-all">
								<?php echo $navigation ?>
								</ol>
							</div>
							
							<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
							  <div class="modal-dialog">
								<div class="modal-content">
								  <div class="modal-header">
									<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
									<h4 class="modal-title" id="exampleModalLabel">edit</h4>
								  </div>
								  <div class="modal-body">
									  <div class="form-group">
										<label for="edittitle" class="control-label">Title:</label>
										<input type="text" class="form-control" id="edittitle">
									  </div>
									  <div class="form-group">
										<label for="editurl" class="control-label">Link:</label>
										<textarea class="form-control" id="editurl"></textarea>
									  </div>
								  </div>
								  <div class="modal-footer">
								  	  <div class="pull-left">
								  		<button type="button" class="btn btn-primary" id="deletebtn">Delete</button>
									  </div>
									  <div class="pull-right">	
										<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
										<button type="button" class="btn btn-primary"  id="editbtn">Save</button>
									  </div>	
								  </div>
								</div>
							  </div>
							</div>
													
						</div>
						<p><strong>Serialised Output (per list)</strong></p>
						<textarea name="navigation" id="nestable-output"></textarea>
                                                <div class="dg-country-choose">
                                                    <?php foreach ($language as $key => $value): ?>
                                                    <input type="checkbox" class="select-all" data-lang="<?php echo $key ?>"/> <?php echo $value ?> <br/>
                                                    <?php foreach ($country[$key] as $country_code): ?>
                                                    <input type="checkbox" name="lang-<?php echo $key ?>[]" value='<?php echo $country_code ?>'/> <img src="img/flag/<?php echo $country_code ?>.png">
                                                    <?php endforeach; ?>
                                                    <br /><br />
                                                    <?php endforeach; ?>
                                                </div>
					</div>
					<!--pageend	-->
					<div class="col-xs-12">
						<div class="text-right">
							<input type="submit" class="btn btn-default btn-bgcolor-blue" value="Save nav">
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
		<script src="js/jquery.nestable.js"></script>
        <?php echo $foot ?>

		<script>
		$(document).ready(function() {
			var updateOutput = function(e) {
				var list   = e.length ? e : $(e.target),
					output = list.data('output'); //console.log(list);
				if (window.JSON) {
					output.val(window.JSON.stringify(list.nestable('serialize')));//, null, 2));
				} else {
					output.val('JSON browser support required for this demo.');
				}
			};
			// activate Nestable for list 1
			$('#nestable').nestable({group: 1}).on('change', updateOutput);
			// output initial serialised data
			updateOutput($('#nestable').data('output', $('#nestable-output')));
			$('#nestable-menu').on('click', function(e) {
				var target = $(e.target),
					action = target.data('action');
				if (action === 'expand-all') {
					$('.dd').nestable('expandAll');
				}
				if (action === 'collapse-all') {
					$('.dd').nestable('collapseAll');
				}
			});

			$("#additem").click(function(){   
				$('#addModal').modal('toggle');		
			});
	
			$("#addbtn").click(function(){
				var edittitle = $("#addtitle").val(); var editurl= $("#addurl").val();
                                if(!edittitle){
                                    alert('please enter title');
                                    return false;
                                }
				$(".dd-list-all").append('<li class=\"dd-item\" data-msg=\"{title:'+edittitle+', url:'+editurl+'}\"><div class=\"dd-handle\"><a href="Item" class=\"itemedit\">{title:'+ edittitle +', url:'+ editurl +'}</a></div><b class=\"dd-handle-bedit\" data-toggle=\"modal\" data-target=\"#exampleModal\"><i class=\"fa fa-pencil fa-fw\"></i></b></li>');
				updateOutput($('#nestable').data('output', $('#nestable-output'))); 
				$('#addModal').modal('hide');

				
			});
			$(document).on('click','.dd-handle-bedit',function(){
				//Assigned to the model form
				$text = $(this).siblings('.dd-handle').text();
				$val = (($text.replace('url:','')).replace('{title:','')).replace('}','');
				$arr = $val.split(', ');
				$('#edittitle').val($arr[0]);
				$('#editurl').val($arr[1]);
			});
			var button;
			$('#exampleModal').on('show.bs.modal', function (event) {
			  button = $(event.relatedTarget); 
			});
			
			$("#editbtn").click(function(){
			  var edittitle = $("#edittitle").val();
                          if(!edittitle){
                                    alert('please enter title');
                                    return false;
                                }
			  var editurl= $("#editurl").val();
			  button.prev(".dd-handle").find(".itemedit").attr("href","Item");
			  button.prev(".dd-handle").find(".itemedit").text('{title:'+edittitle+', url:'+editurl+'}');
			  button.offsetParent(".dd-item").removeAttr('data-msg');
			  button.offsetParent(".dd-item").data("msg",'{title:'+edittitle+', url:'+editurl+'}');
			  updateOutput($('#nestable').data('output', $('#nestable-output')));
			  $('#exampleModal').modal('hide');

			});  
			
			$("#deletebtn").click(function(){
			  button.parent("li").remove();
			  updateOutput($('#nestable').data('output', $('#nestable-output'))); 
			  $('#exampleModal').modal('hide');   
			});
                        $('.select-all').click(function () {
                            var lang = $(this).data('lang')
                            if (this.checked) {
                                $("input[name='lang-" + lang + "[]']").each(function () {
                                    this.checked = true;
                                });
                            } else {
                                $("input[name='lang-" + lang + "[]']").each(function () {
                                    this.checked = false;
                                });
                            }
                        });
		});
		</script>
	</body>
</html>