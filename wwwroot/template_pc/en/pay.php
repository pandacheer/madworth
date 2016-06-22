<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <title>TODO supply a title</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <script src="https://js.braintreegateway.com/v2/braintree.js"></script>
        <style>
            input[type="text"],
            .hosted-field {
                border: 1px solid #3A3A3A;
                -webkit-border-radius: 3px;
                border-radius: 3px;
            }
        </style>
    </head>
    <body>
        <div>TODO write content</div>
        <form id="checkout" action="/home/pay" method="post">
<!--            <div id="number">aaaa</div>
            <div id="expiration-date">sdfsadf</div>
            <input type="submit" id="submit" value="Pay">-->
<div id="form"></div>
        </form>
        <script>
            var colorTransition = 'color 100ms ease-out';
            braintree.setup("<?php echo $client_token; ?>", "dropin", {
                container: "from"
            });
        </script>
    </body>
</html>
