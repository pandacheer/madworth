<!DOCTYPE html>
<?php header("Access-Control-Allow-Origin:*"); ?>
<html lang="en">
    <head>
        <base href="//<?php echo $this->input->server('HTTP_HOST') ?>">
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="keywords" content="<?php echo isset($keywords) ? $keywords : '' ?>">
        <?php foreach ($countryList as $countryCodeKey => $countryListInfo) {
echo '<link rel="alternate" href="'.$countryListInfo['domain'].'" hreflang="'.$countryListInfo['language_code'].'-'.$countryCodeKey.'" />';
 }

 ?>
        <meta name="description" content="<?php echo isset($description) ? $description : '' ?>" />
        <title><?php echo isset($title)&&!empty($title) ? $title.' |' : '' ?> DrGrab</title>
        
        <link rel="apple-touch-icon" sizes="57x57" href="/apple-touch-icon-57x57.png">
        <link rel="apple-touch-icon" sizes="60x60" href="/apple-touch-icon-60x60.png">
        <link rel="apple-touch-icon" sizes="72x72" href="/apple-touch-icon-72x72.png">
        <link rel="apple-touch-icon" sizes="76x76" href="/apple-touch-icon-76x76.png">
        <link rel="apple-touch-icon" sizes="114x114" href="/apple-touch-icon-114x114.png">
        <link rel="apple-touch-icon" sizes="120x120" href="/apple-touch-icon-120x120.png">
        <link rel="apple-touch-icon" sizes="144x144" href="/apple-touch-icon-144x144.png">
        <link rel="apple-touch-icon" sizes="152x152" href="/apple-touch-icon-152x152.png">
        <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon-180x180.png">
        <link rel="icon" type="image/png" href="/favicon-32x32.png" sizes="32x32">
        <link rel="icon" type="image/png" href="/favicon-194x194.png" sizes="194x194">
        <link rel="icon" type="image/png" href="/favicon-96x96.png" sizes="96x96">
        <link rel="icon" type="image/png" href="/android-chrome-192x192.png" sizes="192x192">
        <link rel="icon" type="image/png" href="/favicon-16x16.png" sizes="16x16">
        <link rel="manifest" href="/manifest.json">
        <link rel="mask-icon" href="/safari-pinned-tab.svg" color="#5bbad5">
        <meta name="msapplication-TileColor" content="#00b6c6">
        <meta name="msapplication-TileImage" content="/mstile-144x144.png">
        <meta name="theme-color" content="#ffffff">
        
        <?php echo isset($seoInfo)?$seoInfo:'';?>
        <!-- Bootstrap -->
        <link href="<?php echo $cdn ?>css/bootstrap.min.css" rel="stylesheet">
        <link href="<?php echo $cdn ?>css/style.css?v=160310" rel="stylesheet">
        <link href="<?php echo $cdn ?>css/bootstrap-select.min.css" rel="stylesheet">
        <link href="<?php echo $cdn ?>css/bootstrap-datepicker.min.css" rel="stylesheet">
        <link href="<?php echo $cdn ?>css/jquery.bxslider.css" rel="stylesheet">
        <link href="<?php echo $cdn ?>css/MyFontsWebfontsKit.css" rel="stylesheet">
        <link href="<?php echo $cdn ?>css/icheck/blue.css" rel="stylesheet">
        <link href="<?php echo $cdn ?>css/icheck/minimal/blue.css" rel="stylesheet">
        <link href="<?php echo $cdn ?>css/cartfly.css" rel="stylesheet">
        <link href="<?php echo $cdn ?>css/perfect-scrollbar.min.css" rel="stylesheet">
        <link href="<?php echo $cdn ?>css/font-awesome/css/font-awesome.min.css" rel="stylesheet">
        <link href="<?php echo $cdn ?>css/emailpop.css" rel="stylesheet">
        <link href="<?php echo $cdn ?>css/animate.css" rel="stylesheet">
 
        <!-- <link href="css/dropzone.min.css" rel="stylesheet"> -->
        <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
          <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
    </head>
    <body>
        <?php require_once('head_top.php') ?>


        <div class="dg-head">
            <div class="container">
                <div class="row">
                    <div class="col-xs-3">
                        <div class="dg-head-logo">
                            <a href="<?php echo $domain ?>"><img src="<?php echo $cdn ?>image/logo.png"></a>
                        </div>
                    </div>
                    <div class="col-xs-9">
                        <img src="<?php echo $cdn ?>image/services.png" style="height: 36px;margin-top:40px;margin-left:10px;" usemap="#services" >
                        <map name="services" id="services">
                            <area alt="" title="" href="<?php echo $domain ?>/pages/return-policy" shape="rect" coords="0,1,130,35" onfocus="blur(this);"/>
                            <area alt="" title="" href="<?php echo $domain ?>/pages/shipping-guide" shape="rect" coords="150,1,263,35" onfocus="blur(this);"/>
                            <area alt="" title="" href="<?php echo $domain ?>/pages/Shop-with-Confidence" shape="rect" coords="280,1,402,35" onfocus="blur(this);"/>
                            <area alt="" title="" href="<?php echo $domain ?>/pages/Shop-with-Confidence" shape="rect" coords="420,1,528,35" onfocus="blur(this);"/>
                        </map>

                        <div class="fb-page" style="float: right;width:250px;margin-top: 24px;" data-href="<?php echo $facebook ?>" data-width="250" data-small-header="true" data-adapt-container-width="true" data-hide-cover="true" data-show-facepile="false" data-show-posts="false"></div>

                    </div>
                </div>
            </div>
        </div>

        <div class="dg-navbar-shadow clearfix ">
            <nav class="dg-navbar navbar navbar-static-top">
                <div class="container">
                    <div class="row">
                        <div class="col-xs-10">
                            <ul class="nav navbar-nav">
                                <li><a href="<?php echo $domain ?>">Today's New</a></li>
                                <?php echo $navigation; ?>
                            </ul>
                        </div>
                        <div class="col-xs-2 dg-navbar-search">
                            <div class="input-group input-group">
                                <input type="text" class="form-control" id="search_val"  placeholder="Search" value="<?php 
                                if (isset($search_word)) {
                                    echo $search_word;
                                }else{
                                    echo "";
                                }
                                ?>">
                                <span class="input-group-btn">
                                    <button class="btn btn-default" id="search_but" type="button"><span class="glyphicon glyphicon-search" aria-hidden="true"></span></button>
                                </span>
                            </div>
                        </div>
                    </div>
                    
                </div>
            </nav>
        </div>
        

        <div style="display: none">
            <img src="<?php echo $cdn ?>image/grab.gif">
        </div>
