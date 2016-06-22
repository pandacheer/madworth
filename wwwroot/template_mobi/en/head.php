<!DOCTYPE html>
<html>
    <head>
        <base href="//<?php echo $this->input->server('HTTP_HOST') ?>">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no"/>
        <meta charset="utf-8">
        <meta name="keywords" content="<?php echo isset($keywords) ? $keywords : '' ?>">
        <meta name="description" content="<?php echo isset($description) ? $description : '' ?>" />
        <?php
        foreach ($countryList as $countryCodeKey => $countryListInfo) {
            echo '<link rel="alternate" href="' . $countryListInfo['domain'] . '" hreflang="' . $countryListInfo['language_code'] . '-' . $countryCodeKey . '" />';
        }
        ?>
        <!--<link rel="alternate" href="<?php echo $domain ?>" hreflang="<?php echo $language ?>-<?php echo $country ?>" />-->
        <title><?php echo isset($title) && !empty($title) ? $title . ' |' : '' ?> DrGrab</title>

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

        <?php echo isset($seoInfo) ? $seoInfo : ''; ?>
        <link rel="stylesheet" href="<?php echo $cdn ?>css/jquery.mobile.flatui.min.css">
        <link rel="stylesheet" href="<?php echo $cdn ?>css/icons.css">
        <link rel="stylesheet" href="<?php echo $cdn ?>css/bootstrap-datepicker.standalone.min.css">
        <link rel="stylesheet" href="<?php echo $cdn ?>css/style.css?v=0118">
        <link rel="stylesheet" href="<?php echo $cdn ?>css/icheck/blue.css">
        <link rel="stylesheet" href="<?php echo $cdn ?>css/swiper.min.css">
        <script src="<?php echo $cdn ?>js/jquery-1.11.3.min.js"></script>
        <script type="text/javascript">
            $(document).bind("mobileinit", function () {
                $.mobile.ajaxEnabled = false;
            });
        </script>
        <script src="<?php echo $cdn ?>js/jquery.mobile-1.4.5.min.js"></script>
        <script src="<?php echo $cdn ?>js/jquery.creditCardValidator.js"></script>
        <script src="<?php echo $cdn ?>js/lib.js"></script>
        <!--
                <script src="<?php echo $cdn ?>js/jquery.notifyBar.js"></script>
                <script src="<?php echo $cdn ?>js/bootstrap-datepicker.min.js"></script>
                <script src="<?php echo $cdn ?>js/swiper.min.js"></script>
                <script src="<?php echo $cdn ?>js/jquery.countdown.min.js"></script>
                <script src="<?php echo $cdn ?>js/jquery.rwdImageMaps.min.js"></script>
                <script src="<?php echo $cdn ?>js/jquery.isloading.js"></script>
                <script src="<?php echo $cdn ?>js/icheck.min.js"></script>
                <script src="<?php echo $cdn ?>js/jquery-hightlight.js"></script>
        -->
        <script src="<?php echo $cdn ?>js/main.js"></script>
    </head>
    <body>
        <div id="container" data-role="page">
            <div id="dg-panel" data-role="panel" data-position="left" data-display="overlay" data-theme="c">
                <div id="left_content">
                    <span style="font-weight: 700;color:#00B6C6;font-size: 1.2em;">My Account</span>
                    <a href="#demo-links" data-rel="close" id="panleleftclose" style="float: right;color: #00B6C6">
                        <span class="icon-close" style=""></span>
                    </a>
                    <ul data-role="listview">

                        <?php
                        if ($this->session->userdata('member_email')) {
                            echo '<li><a class="ui-btn" href="' . $domain . '/personal" rel="external">Personal Details</a></li>';
                            echo '<li><a class="ui-btn" href="' . $domain . '/personal/order" rel="external">My Orders</a></li>';
                            echo '<li><a class="ui-btn" href="' . $domain . '/personal/coupon" rel="external">My Coupon</a></li>';
                            echo '<li><a class="ui-btn" href="' . $domain . '/personal/address" rel="external">Address</a></li>';
                            echo '<li><a class="ui-btn" href="' . $domain . '/reg/logOut" rel="external">Log Out</a></li>';
                        } else {
                            echo '<li><a class="ui-btn" href="' . $domain . '/login" rel="external">Login</a></li>';
                            echo '<li><a class="ui-btn" href="' . $domain . '/reg" rel="external">Sign Up</a></li>';
                            echo '<li><a class="ui-btn" href="' . $domain . '/forget" rel="external">Forgot Password</a></li>';
                        }
                        ?>
                    </ul>

                    <div class="dg-panel-search">
                        <input type="text" name="search-restaurants" id="search_val" placeholder="Search" />
                        <button data-role="button" data-icon="search" id="search_but" data-theme="b">Search</button>
                    </div>

                    <div class="dg-panel-country">
                        <div class="ui-grid-a">
                            <div class="ui-block-a" style="width: 30%">
                                <img data-original-title="<?php echo $countryList[$country]['name'] ?>" src="<?php echo $cdn ?>img/flag/<?php echo $country ?>.png">
                            </div>
                            <div class="ui-block-b" style="width: 70%">
                                <span>You Are Visiting the <?php echo $countryList[$country]['name'] ?> Site</span>
                            </div>
                        </div>
                        <p id="switch">Not in this Country?</p>
                    </div>
                </div>

                <div id="country_switch" style="display: none">
                    <span style="font-weight: 700;color:#00B6C6;font-size: 1.2em;text-align: center" id="lvjinxiu">Go Back</span>
                    <div class="dg-main-countryslider jq" >
                        <?php foreach ($flag_sort as $country_code) : ?>
                            <div class="dg-main-countryslider-list">
                                <?php if (strpos(strtolower(uri_string()), 'collections/') === 0 || strpos(strtolower(uri_string()), 'products/') === 0): ?>
                                    <a href="http://<?php echo $countryList[$country_code]['domain'] . '/' . uri_string(); ?>">
                                    <?php else: ?>
                                        <a href="http://<?php echo $countryList[$country_code]['domain']; ?>">
                                        <?php endif; ?>
                                        <table>
                                            <tr>
                                                <td><img data-original-title="<?php echo $countryList[$country_code]['name'] ?>" src="<?php echo $cdn ?>img/flag/<?php echo $country_code ?>.png"></td>
                                                <td><span class="dg-main-countryslider-list-title"><?php echo $countryList[$country_code]['name'] ?></span></td>
                                            </tr>
                                        </table>
                                    </a>
                            </div>
                        <?php endforeach; ?>

                    </div> 
                </div>
            </div>

            <div id="dg-header" data-role="header" data-theme="b" data-position="fixed" data-tap-toggle="false">
                <div class="dg-header-top ui-grid-b">
                    <div class="ui-block-a"><a href="#dg-panel" class="icon-menu"></a></div>
                    <div class="ui-block-b"><a href="<?php echo $domain ?>"><img src="<?php echo $cdn ?>img/logo.png" style="max-width: 100%;max-height: 1.8em;"></a></div>
                    <div class="ui-block-c"><a href="/cart">
                            <div class="dg-header-cart" id="cartpronum"><?php echo $myCarts ?></div>
                            <span class="icon-cart"></span>
                        </a></div>
                </div>
                <div class="dg-header-nav">
                    <a href="#" id="dg-header-categorybtn" data-theme="c" data-rel="popup" data-position-to="window" class="ui-btn ui-btn-c ui-button-l" data-transition="pop">    
                        <span id="category">Shop By Category <span class="icon-shop" style="float: right;font-size: 1.3em;"></span></span>
                        <span style="display: none" id="back">Go back<span class="icon-back" style="float: right;font-size: 1.3em;"></span></span>
                    </a>
                </div>

            </div>
            <div class="dg-main-navslider jq dg-cate-hide">
                <?php echo $navigation; ?>
            </div>

            <div style="display: none">
                <img src="<?php echo $cdn ?>img/grab.gif">
            </div>

            <!-- <div class="dg-notice" style="padding: 0.5em 1em 0 1em;color: #FF858A">
                <span>We will be running a maintenance in <span data-countdownlv="1454382000000" id="down"></span>. <br>We do not anticipate more than 10 minutes of downtime, and we apologize for any impact these activities may have.</span>
            </div> -->
            <!-- 
            <div id="dg-cataforynav" data-role="popup" data-theme="none" data-overlay-theme="a" data-position="fixed">
                <div data-role="collapsibleset" data-theme="b" data-content-theme="a" data-collapsed-icon="carat-d" data-expanded-icon="carat-u" 
data-iconpos="right" class="ui-nodisc-icon">
                </div>
            </div>
            -->