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
        <link rel="stylesheet" href="css/jquery.bxslider.css"/>
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
                        <div class="col-xs-12">
                            <h1><i class="fa fa-users" aria-hidden="true"></i>Slideshow</h1>
                        </div>
                    </div>	
                </div>
                <div id="breadcrumb">
                    <a href="/slideshow" title="Go to SlideCollection List" class="tip-bottom"><i class="fa fa-tags"></i> SlideCollection </a>
                    <a href="#" class="current">Slideshow</a>
                </div>
                <div class="row" id="row" style="margin-bottom: 20px;">
                    <?php
                    if (isset($image)) {
                        foreach ($image as $vo) {
                            $id = is_object($vo['_id']) ? (string) $vo['_id'] : $vo['_id'];
                            $vlink = stripos($vo['link'], 'http') === 0 ? $vo['link'] : 'http://' . $this->session->userdata('domain') . $vo['link'];
                            $alink = $vo['link'] ? '<a href="' . $vlink . '" target="_blank"><img src="' . IMAGE_DOMAIN . $vo['image'] . '" /></a>' : '<img src="' . IMAGE_DOMAIN . $vo['image'] . '" />';
                            echo '<div class="col-xs-4">
                                        <p class="thumbnail">' . $alink . '</p>
                                        <button picid="' . $vo['_id'] . '" class="removepic btn btn-danger pull-left">Remove This Pic</button><button picid="' .$id . '" class="syncpic btn btn-danger pull-left" style="margin-left:5px;">Sync This Pic</button><br><br><br>
                                        <input type="text" id="up_' . $id . '" value="' . $vo['sort'] . '"/>
                                        <button type="button" class="btn btn-success"  onClick="upSort(' . " '$id' " . '); return false;" style="line-height:18px!important;">Update Sort</button>
                                        <input type="text" id="uplink_' . $id . '" value="' . $vo['link'] . '"/>                                        
                                        <button type="button" class="btn btn-success"  onClick="upLink(' . " '$id' " . '); return false;" style="line-height:18px!important;">Update Link</button>    
                                 </div>';
                        }
                    }
                    ?>
                </div>
                <div class="row">
                    <div class="col-xs-10">
                        <h4>Add a New Image</h4>
                        <form method="post" enctype="multipart/form-data" action="/slideshow/upPic">
                            <div id="piclist">
                                <div class="col-xs-6">
                                    <input name="collection" type="hidden" value="<?php echo $_id ?>">
                                    <input name="pic" type="file" class="form-control">
                                </div>
                                <div class="col-xs-6">
                                    <input name="piclink" type="text" class="form-control" placeholder="http://www.drgrab.com">
                                </div>
                            </div>


                            <div class="dg-country-choose col-xs-6" style="width: 100%;">
                                <?php foreach ($language as $key => $value): ?>
                                    <input type="checkbox" class="select-all" data-lang="<?php echo $key ?>"/><?php echo $value ?><br/>
                                    <?php foreach ($country[$key] as $country_code): ?>
                                        <input type="checkbox" name="lang-<?php echo $key ?>[]" value='<?php echo $country_code ?>'/> 
                                        <img src="img/flag/<?php echo $country_code ?>.png">
                                    <?php endforeach; ?>
                                    <br /><br />
                                <?php endforeach; ?>
                            </div>

                            <div class="col-xs-6"> <button type="submit" class="btn btn-success btn-lg" style="margin:15px 0 15px 15px">Submit</button>
                                <a href="/slideshow"><button type="button" class="btn btn-default btn-lg" style="margin:15px 0 15px 15px">Cancel</button></a>
                            </div>
                        </form>
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
        <script src="js/jquery.tagsinput.js"></script>
        <script src="js/jquery.bxslider.min.js"></script>
        <script src="js/jquery.icheck.min.js"></script>
        <!--左侧nav-->
        <script src="js/jquery.nicescroll.min.js"></script>
        <script src="js/unicorn.js"></script>
        <script src="js/unicorn.jui.js"></script>
        <script src="js/jquery.notifyBar.js"></script>
        <?php echo $foot ?>
        <script>
            $(function () {
                $('.removepic,.syncpic').click(function () {
                    var picid = $(this).attr('picid');
                    var parent = $(this).parent('.col-xs-4');
                    var cty = [];
                    $(".dg-country-choose input[name^='lang-'").each(function () {
                        if (this.checked) {
                            cty.push(this.value);
                        }
                    });
                    if ($(this).hasClass('removepic')) {
                        var a = 'removepic';
                    } else {
                        var a = 'syncpic';
                    }
                    $.ajax({
                        type: 'POST',
                        url: "/slideshow/" + a,
                        dataType: "json",
                        data: {id: picid, cty: cty.join(',')},
                        success: function (data) {
                            if (data.status == 200) {
                                if (a == 'removepic') {
                                    parent.remove();
                                }
                                if (data.info) {
                                    alert(data.info);
                                }
                            } else {
                                alert(data.info);
                            }
                        }
                    })
                })
                $('.select-all').click(function (event) {
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
            })


            function upSort(id) {
                var cty = [];
                $(".dg-country-choose input[name^='lang-'").each(function () {
                    if (this.checked) {
                        cty.push(this.value);
                    }
                });
                $.ajax({
                    type: "POST",
                    url: "<?php echo site_url('slideshow/changesort') ?>",
                    dataType: 'json',
                    data: {
                        id: id,
                        sort: $("#up_" + id).val(),
                        cty: cty.join(',')
                    },
                    success: function (result) {
                        if (result.success) {
                            $.notifyBar({cssClass: "dg-notify-success", html: "修改此图片顺序成功", position: "bottom", delay: 2000, onHide: function () {
                                    location.reload();
                                }});
                        } else {
                            $.notifyBar({cssClass: "dg-notify-error", html: result.info, position: "bottom", delay: 2000, onHide: function () {
                                    location.reload();
                                }});
                        }
                    }
                });
            }
            function upLink(id) {
                var cty = [];
                $(".dg-country-choose input[name^='lang-'").each(function () {
                    if (this.checked) {
                        cty.push(this.value);
                    }
                });
                $.ajax({
                    type: "POST",
                    url: "<?php echo site_url('slideshow/changelink') ?>",
                    dataType: 'json',
                    data: {
                        id: id,
                        link: $("#uplink_" + id).val(),
                        cty: cty.join(',')
                    },
                    success: function (result) {
                        if (result.success) {
                            $.notifyBar({cssClass: "dg-notify-success", html: "修改此图片链接成功", position: "bottom", delay: 2000, onHide: function () {
                                    location.reload();
                                }});
                        } else {
                            $.notifyBar({cssClass: "dg-notify-error", html: result.info, position: "bottom", delay: 2000, onHide: function () {
                                    location.reload();
                                }});
                        }
                    }
                });
            }
        </script>
    </body>
</html>