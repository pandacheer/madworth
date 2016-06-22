<!DOCTYPE html>
<html lang="en">
    <head>
        <base href="<?php echo $template ?>">
        <title>Collections list</title>
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
                    <div class="row">
                        <div class="col-xs-12 col-sm-6 col-lg-6">
                            <h1><i class="glyphicon glyphicon-tags" aria-hidden="true"></i> Collections</h1>
                        </div>
                        <div class="col-xs-12 col-sm-6 col-lg-6">
                        	<a href="<?php echo site_url('collection/rank') ?>">
                                <button class="btn btn-info pull-right" type="button">Rank</button>
                            </a>
                            <a href="<?php echo site_url('collection/loadAddPage') ?>">
                                <button class="btn btn-info pull-right" type="button"><i class="fa fa-plus fa-sm"></i> Add a collection</button>
                            </a>
                        </div>
                    </div>	
                </div>
                <div class="row">
                    <div class="col-xs-12">
                        <div class="row">
                            <div class="widget-box widget-box-hledit">
                                <form method="post" action="<?php echo site_url('collection/index') ?>">
                                    <div class="input-group">

                                        <input type="text" id="collection-search" class="form-control" value="<?php if($where!='ALL')echo $where;?>" placeholder="Search for..." name="txtKeyWords">
                                        <span class="input-group-btn">
                                            <button id="collection-submit" class="btn btn-default" type="submit">Go!</button>
                                        </span>

                                    </div>
                                </form>
                                <ul class="pagination alternate">
                                    <?php if (isset($pages)) echo $pages ?>
                                </ul>
                                <div class="widget-content nopadding">
                                    <table class="table table-striped table-hover with-check collections-tablebox">
                                        <thead>
                                            <tr>
                                                <th class="title">Title</th>
                                                <th>Country</th>
                                                <th class="ProductConditions">Product Conditions</th>
                                                <th class="select">sync</th>
                                                <th class="select">delete</th>
                                                <th class="select">hide</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($cursor as $doc) : ?>
                                                <tr id="row<?php echo $doc['_id'] ?>">
                                                    <td class="title"><a href="<?php echo site_url('collection/loadEditPage/' . $doc['_id']) ?>"><?php echo $doc['title'] ?></a></td>
                                                    <td class="country">
                                                        <!--显示已开启的-->
                                                        <?php foreach ($doc['country_show'] as $country_code) : ?> 
                                                            <img src="img/flag/<?php echo $country_code ?>.png"  title="<?php echo $country_code ?>">
                                                        <?php endforeach; ?>
                                                        <!--显示已关闭的-->
                                                        <?php foreach ($doc['country_hide'] as $country_code) : ?> 
                                                            <img class="flaggrey" src="img/flag/<?php echo $country_code ?>.png"  title="<?php echo $country_code ?>">
                                                        <?php endforeach; ?>
                                                    </td>
                                                    <td class="subdued ProductConditions">
                                                        <?php if ($doc['model'] == 2) : ?>
                                                            <?php foreach ($doc['conditions'] as $tj) : ?>
                                                                <span>Product <?php echo $tj['fields'] ?> is <?php echo $tj['link'] ?> to <?php echo $tj['fields'] == 'type' ? $categoryList[$tj['values']]['title'] : $tj['values'] ?></span>
                                                            <?php endforeach; ?>
                                                        <?php endif; ?>
                                                    </td>

                                                    <td class="select">
                                                        <button class="btn btn-default btn-bgcolor-blue" data-bind="<?php echo $doc['_id'] ?>" type="button" data-toggle="modal" data-target=".collections-button-syncdialog">
                                                            <i class="fa fa-refresh"></i> sync
                                                        </button>
                                                    </td>
                                                    <td class="select">
                                                        <button class="btn btn-danger" data-bind="<?php echo $doc['_id'] ?>" type="button" data-toggle="modal" data-target=".collections-button-dialog">
                                                            <i class="fa fa-trash-o fa-lg"></i> Delete
                                                        </button>
                                                    </td>
                                                    <td class="select">
                                                        <button type="button" data-bind="<?php echo $doc['_id'] ?>" class="btn btn-default btn-bgcolor-white showhidebtn-collection" data-toggle="modal" data-target=".collections-showhidebtn-dialog"><?php echo $doc['status'] == 1 ? 'Hide' : 'Show' ?></button>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>	
                                </div>
                                <ul class="pagination alternate">
                                    <?php if (isset($pages)) echo $pages ?>
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
        <!--删除Collection-->
        <div class="modal collections-button-dialog" role="dialog" aria-labelledby="gridSystemModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="gridSystemModalLabel">Change Country</h4>
                    </div>
                    <div class="modal-body">
                        <div class="container-fluid">

                            <div class="row">
                                <div class="dg-country-choose dg-country-choose-delete">
                                    <form id='delForm'>
                                        <?php foreach ($language as $key => $value): ?>
                                            <input type="checkbox" class="select-all" data-lang="<?php echo $key ?>"/> <?php echo $value ?> <br/>
                                            <?php foreach ($country[$key] as $country_code): ?>
                                                <input type="checkbox" name="lang-<?php echo $key ?>[]" value='<?php echo $country_code ?>' disabled="disabled"/> <img src="img/flag/<?php echo $country_code ?>.png" title="<?php echo $country_code ?>">
                                            <?php endforeach; ?>
                                            <br /><br />
                                        <?php endforeach; ?>
                                        <input type="hidden" id="collections-deletebut-value" name="collection_id"/>
                                    </form>
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="button" id='delStatus' class="btn btn-primary">Save changes</button>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->  

        <!--关闭和开启Collection-->
        <div class="modal collections-showhidebtn-dialog" role="dialog" aria-labelledby="gridSystemModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="gridSystemModalLabel">Change Country</h4>
                    </div>
                    <div class="modal-body">
                        <div class="container-fluid">

                            <div class="row">
                                <form id='updateForm'>
                                    <div class="dg-country-choose dg-country-choose-showhide col-sm-8">
                                        <?php foreach ($language as $key => $value): ?>
                                            <input type="checkbox" class="select-all2" data-lang="<?php echo $key ?>"/> <?php echo $value ?> <br/>

                                            <?php foreach ($country[$key] as $country_code): ?>
                                                <input type="checkbox" name="lang-<?php echo $key ?>[]" value='<?php echo $country_code ?>' disabled="disabled" /> <img src="img/flag/<?php echo $country_code ?>.png" title="<?php echo $country_code ?>">
                                            <?php endforeach; ?>
                                            <br /><br />
                                        <?php endforeach; ?>
                                        <input type="hidden" id="collections-changebut-value" name="collection_id"/>
                                    </div>

                                    <div class="dg-country-choose showhide-radio col-sm-4">
                                        <div class="radio">
                                            <label>
                                                <input type="radio" name="optionsRadios" id="optionsRadios1" value="2" data-flag="show">show
                                            </label>
                                        </div>
                                        <div class="radio">
                                            <label>
                                                <input type="radio" name="optionsRadios" id="optionsRadios2" value="1" data-flag="hide">hide
                                            </label>
                                        </div>
                                    </div>
                                </form>
                            </div>

                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" id="showhidebtn-save">Save changes</button>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->  

        <!--同步Collection -->
        <div class="modal collections-button-syncdialog" role="dialog" aria-labelledby="gridSystemModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="gridSystemModalLabel">sync</h4>
                    </div>
                    <div class="modal-body">
                        <div class="container-fluid">
                            <div class="row">
                                <form id='syncForm'>
                                    <div class="dg-country-choose sync-selete col-sm-5">
                                        <h4>Source Country</h4>
                                        <select class="selectbox" id="sourceCountry" name='sourceCountry'>
                                            <option value="Public">Please select</option>
                                        </select>
                                        <input type="hidden" id="collections-syncbut-value" name="collection_id">
                                    </div>
                                    <div class="dg-country-choose dg-country-choose-sync col-sm-7">
                                        <h4>Target Country</h4>

                                        <?php foreach ($language as $key => $value): ?>
                                            <input type="checkbox" class="select-all3" data-lang="<?php echo $key ?>"/> <?php echo $value ?> <br/>

                                            <?php foreach ($country[$key] as $country_code): ?>
                                                <input type="checkbox" name="lang-<?php echo $key ?>[]" value='<?php echo $country_code ?>'/> <img src="img/flag/<?php echo $country_code ?>.png">
                                            <?php endforeach; ?>
                                            <br /><br />
                                        <?php endforeach; ?>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            <button type="button" id='syncCollection' class="btn btn-primary">Save changes</button>
                        </div>
                    </div><!-- /.modal-content -->
                </div><!-- /.modal-dialog -->
            </div><!-- /.modal -->  


            <script src="js/jquery.min.js"></script>
            <script src="js/jquery-ui.custom.js"></script>
            <script src="js/bootstrap.min.js"></script>
            <script src="js/select2.min.js"></script>
            <!--左侧nav-->
            <script src="js/jquery.nicescroll.min.js"></script>
            <script src="js/unicorn.js"></script>
            <?php echo $foot ?>

        <!--<script src="js/jquery.form.min.js"></script>-->

            <script>

                $('#collection-search').keypress(function(e){
                    var keycode = e.charCode;
                        if(keycode == 13)
                        $('#collection-submit').click();
                });
                $(function () {

                    var buttonshowhidebtn, buttondelete;
                    $('.collections-showhidebtn-dialog').on('show.bs.modal', function (event) {
                        buttonshowhidebtn = $(event.relatedTarget);
                        if (buttonshowhidebtn.text() === "hide") {
                            $("input[id='optionsRadios2']").attr("checked", true);
                            $("input[id='optionsRadios1']").attr("checked", false);
                        } else {
                            $("input[id='optionsRadios2']").attr("checked", false);
                            $("input[id='optionsRadios1']").attr("checked", true);
                        }
                    });


                    $('.collections-button-dialog').on('show.bs.modal', function (event) {
                        buttonshowhidebtn = $(event.relatedTarget);
                        $("#collections-deletebut-value").val(buttonshowhidebtn.attr("data-bind"));
                        var td = $('#row' + buttonshowhidebtn.attr("data-bind")).children('.country');
                        td.find("img").each(function (i) {
                            var that = this;
                            $('#delForm').find("img").each(function (i) {
                                if ($(that).attr('title') === $(this).attr('title')) {
                                    $(this).prev().removeAttr("disabled");
                                }
                            });
                        });
                    });

                    $('.collections-button-dialog').on('hidden.bs.modal', function (e) {
                        $('#delForm').find("[name^='lang-']").attr("disabled", "disabled");
                    });

                    $('.selectbox').select2();
                    $('.select-all').click(function (event) {
                        var lang = $(this).data('lang');
                        if (this.checked) {
                            $(".dg-country-choose-delete input[name='lang-" + lang + "[]']").not(':disabled').each(function () {
                                this.checked = true;
                            });
                        } else {
                            $("input[name='lang-" + lang + "[]']").each(function () {
                                this.checked = false;
                            });
                        }
                    });
                    $('.collections-showhidebtn-dialog').on('hidden.bs.modal', function (e) {
                        $('#updateForm').find("[name^='lang-']").attr("disabled", "disabled");
                    });
                    $('.collections-showhidebtn-dialog').on('show.bs.modal', function (event) {
                        buttonshowhidebtn = $(event.relatedTarget);
                        $("#collections-changebut-value").val(buttonshowhidebtn.attr("data-bind"));
                        var td = $('#row' + buttonshowhidebtn.attr("data-bind")).children('.country');
                        td.find("img").each(function (i) {
                            var that = this;
                            $('#updateForm').find("img").each(function (i) {
                                if ($(that).attr('title') === $(this).attr('title')) {
                                    $(this).prev().removeAttr("disabled");
                                }
                            });
                        });
                    });
                    $('.select-all2').click(function (event) {
                        var lang = $(this).data('lang');
                        if (this.checked) {
                            $(".dg-country-choose-showhide input[name='lang-" + lang + "[]']").each(function () {
                                this.checked = true;
                            });
                        } else {
                            $("input[name='lang-" + lang + "[]']").each(function () {
                                this.checked = false;
                            });
                        }
                    });
                    $('.collections-button-syncdialog').on('show.bs.modal', function (event) {
                        buttonshowhidebtn = $(event.relatedTarget);
                        var td = $('#row' + buttonshowhidebtn.attr("data-bind")).children('.country');
                        $('#sourceCountry option:gt(0)').remove();
                        td.find("img").each(function (i) {
                            $('#sourceCountry').append('<option value="' + $(this).attr('title') + '">' + $(this).attr('title') + '</option>');
                        });
                        $("#collections-syncbut-value").val(buttonshowhidebtn.attr("data-bind"));
                    });

                    $('.select-all3').click(function (event) {
                        var lang = $(this).data('lang');
                        if (this.checked) {
                            $(".dg-country-choose-sync input[name='lang-" + lang + "[]']").each(function () {
                                this.checked = true;
                            });
                        } else {
                            $("input[name='lang-" + lang + "[]']").each(function () {
                                this.checked = false;
                            });
                        }
                    });
                    $("#showhidebtn-save").on("click", function () {
                        if (!$("input[name='optionsRadios']").is(':checked')) {
                            alert('Show or Hide ?');
                            return false;
                        }
                        $.ajax({
                            type: "POST",
                            url: "<?php echo site_url('collection/updateStatus') ?>",
                            dataType: 'json',
                            data: $("#updateForm").serialize(),
                            success: function (result) {
                                if (result.success) {
                                    buttonshowhidebtn.text($("input[name='optionsRadios']:checked").attr("data-flag"));
                                    $('.collections-showhidebtn-dialog').modal('hide');
                                    alert(result.msg);
                                    location.reload();
                                } else {
                                    alert(result.error);
                                }
                            }
                        });
                    });
                    $("#delStatus").on("click", function () {
                        $.ajax({
                            type: "POST",
                            url: "<?php echo site_url('collection/del') ?>",
                            dataType: 'json',
                            data: $("#delForm").serialize(),
                            success: function (result) {
                                alert("Data Saved: " + result.msg);
                                location.reload();
                            }
                        });
                    });
                    $("#syncCollection").on("click", function () {
                        $.ajax({
                            type: "POST",
                            url: "<?php echo site_url('collection/syncCollection') ?>",
                            dataType: 'json',
                            data: $("#syncForm").serialize(),
                            success: function (result) {
                                alert("Data Saved: " + result.msg);
                                location.reload();
                            }
                        });
                    });
                });

            </script>
    </body>
</html>
