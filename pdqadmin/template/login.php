<!DOCTYPE html>
<html lang="en">
    <head>
        <base href="<?php echo $template ?>">
        <title>Unicorn Admin</title>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <link rel="stylesheet" href="css/bootstrap.min.css" />
        <link rel="stylesheet" href="css/font-awesome.css" />
        <link rel="stylesheet" href="css/unicorn-login.css" />
        <script type="text/javascript" src="js/respond.min.js"></script>
    </head>
    <body>
        <div id="container">
            <div id="logo">
                <img src="img/logo.png" alt="" />
            </div>
            <div id="user">
                <div class="avatar">
                    <div class="inner"></div>
                    <img src="img/demo/av1_1.jpg" />
                </div>
                <div class="text">
                    <h4>Hello,<span class="user_name"></span></h4>
                </div>
            </div>
            <div id="loginbox">            
                <form id="loginform" action="<?php echo site_url('login/enter') ?>" method="post">
                    <p>Enter username and password to continue.</p>
                    <div class="input-group input-sm">
                        <span class="input-group-addon"><i class="fa fa-user"></i></span><input class="form-control" type="text" name="username" id="username" placeholder="Username" />
                    </div>
                    <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-lock"></i></span><input class="form-control" type="password" name="password" id="password" placeholder="Password" />
                    </div>
                    <div class="form-actions clearfix">
                        <div class="pull-left">
                            <!--<a href="#registerform" class="flip-link to-register blue">Create new account</a>-->
                        </div>
                        <div class="pull-right">
                            <a href="#recoverform" class="flip-link to-recover grey">Lost password?</a>
                        </div>
                        <input type="button" class="btn btn-block btn-primary btn-default" value="Login" id="loginBtn" />
                    </div>
                    <!--                    <div class="footer-login">
                                            <div class="pull-left text">
                                                Login with
                                            </div>
                                            <div class="pull-right btn-social">
                                                <a class="btn btn-facebook" href="#"><i class="fa fa-facebook"></i></a>
                                                <a class="btn btn-twitter" href="#"><i class="fa fa-twitter"></i></a>
                                                <a class="btn btn-google-plus" href="#"><i class="fa fa-google-plus"></i></a>
                                            </div>
                                        </div>-->

                </form>
                <form id="recoverform" action="#">
                    <p>Enter your e-mail address below and we will send you instructions how to recover a password.</p>
                    <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-envelope"></i></span><input class="form-control" type="text" placeholder="E-mail address" />
                    </div>
                    <div class="form-actions clearfix">
                        <div class="pull-left">
                            <a href="#loginform" class="grey flip-link to-login">Click to login</a>
                        </div>
                        <div class="pull-right">
                            <a href="#registerform" class="blue flip-link to-register">Create new account</a>
                        </div>
                        <input type="submit" class="btn btn-block btn-inverse" value="Recover" />
                    </div>
                </form>
                <!--                <form id="registerform" action="#">
                                    <p>Enter information required to register:</p>
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="fa fa-user"></i></span><input class="form-control" type="text" placeholder="Enter Username" />
                                    </div>
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="fa fa-lock"></i></span><input class="form-control" type="password" placeholder="Choose Password" />
                                    </div>
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="fa fa-lock"></i></span><input class="form-control" type="password" placeholder="Confirm password" />
                                    </div>
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="fa fa-envelope"></i></span><input class="form-control" type="text" placeholder="Enter E-mail address" />
                                    </div>
                                    <div class="form-actions clearfix">
                                        <div class="pull-left">
                                            <a href="#loginform" class="grey flip-link to-login">Click to login</a>
                                        </div>
                                        <div class="pull-right">
                                            <a href="#recoverform" class="grey flip-link to-recover">Lost password?</a>
                                        </div>
                                        <input type="submit" class="btn btn-block btn-success" value="Register" />
                                    </div>
                                </form>-->
            </div>
        </div>

        <script src="js/jquery.min.js"></script>  
        <script src="js/jquery-ui.custom.min.js"></script>
        <script src="js/unicorn.login.js"></script> 

        <script type="text/javascript">
            $(function () {
                var redirctURL = "<?php echo $redirectURL;?>";
                $("#loginBtn").on("click", function () {
                    var that = this;
                    $.post('<?php echo site_url('login/enter') ?>', {
                        username: $('#username').val(),
                        password: $('#password').val()
                    }, function (result) {
                        if (result.success) {
                            if(redirctURL){
                                location.href = redirctURL;
                            }else{
                                location.href = '<?php echo site_url('home/index') ?>';
                            }
                        } else {
                            alert(result.msg);
                        }
                    }, 'json');
                });
            });
        </script>
    </body>
</html>
