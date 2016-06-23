
//shipping 邮费计算
$('.dg-main-cart-total h4:nth-child(2) b').text($('.shipping_name').eq(0).text());
if ($('.dg-main-check-list span').eq(0).text() == "Free") {
    $('.dg-main-cart-total h4:nth-child(2) span').text(0);
} else {
    $('.dg-main-cart-total h4:nth-child(2) span').text($('.dg-main-check-list span').eq(0).text());
};

/*$('.dg-main-shipping .dg-main-check-list').click(function(){
     $(this).siblings().children().removeClass('checked');
     $(this).children().addClass('checked');

     if($(this).find('span').text()=='Free'){
     	$('.dg-main-cart-total h4:nth-child(2) span').text(0);
     }else {
     	$('.dg-main-cart-total h4:nth-child(2) span').text($(this).find('span').text());
     }
     var $type = parseInt($('#coupon_id').attr('data-type'));
     var $condition = parseInt($('#coupon_id').attr('data-condition'));
    if($type==2){
        if($condition==3){
            sumprice(1);
        }else{
            sumprice(2);
        }  
    }else if($type==1){
        sumprice(1);
    }else if($type==3){
        var loading=$('.coupon').data('coupon');
        if(loading){
            $('.coupon span').text($('.dg-main-cart-total h4:nth-child(2) span').text());
        }
    	
    	sumprice(1);
    }else{
    	sumprice(1);
    }
});*/

$('.dg-main-check-list-radio').on('ifChecked', function (event) {
    $num = $('.dg-main-check-list-radio').index(this);
    $htitle = $('.shipping_name').eq($num).text();
    $('.dg-main-cart-total h4:nth-child(2) b').text($('.shipping_name').eq($num).text());
    if ($('.dg-main-check-list-price span').eq($num).text() == "Free") {
        $('.dg-main-cart-total h4:nth-child(2) span').text(0);
    } else {
        $('.dg-main-cart-total h4:nth-child(2) span').text($('.dg-main-check-list-price span').eq($num).text());
    }
    ;
    //$('.coupon').hide();
    //$('.coupon span').text(0);
    //$('#coupon_id').val('');
    recalprice();

});
//未使用coupon时的计算
function sumprice(e){
	var subtotal = 0;
	if (e == 1){
		$('.ui-grid-b .dg-main-cart-center-price').each(function(){
			var pronum = parseInt($(this).parents('.ui-grid-b').find('.dg-main-cart-right-button span').text());
			var proprice = parseFloat($(this).data('price')) * pronum;
			subtotal = subtotal + proprice;
		});
		$('.subtotal').text(subtotal.toFixed(2));
		var shipping = parseFloat($('.dg-main-cart-total h4:nth-child(2) span').text());
		var coupon=parseFloat($('.dg-main-cart-total h4:nth-child(3) span').text());
		var insurance=parseFloat($('.insurance span').text());
		var giftpacking=parseFloat($('.giftpacking span').text());
		$('.total span').text((subtotal+ shipping-coupon+insurance+giftpacking+0.000001).toFixed(2));
	}else if(e == 2){
		$('.ui-grid-b .dg-main-cart-center-price').each(function(){
			var pronum = parseInt($(this).parents('.ui-grid-b').find('.dg-main-cart-right-button span').text());
			var proprice = parseFloat($(this).data('price')) * pronum;
			subtotal = subtotal + proprice;
		});
		$('.subtotal').text(subtotal.toFixed(2));
		var shipping = parseFloat($('.dg-main-cart-total h4:nth-child(2) span').text());
		var coupon=parseFloat($('.coupon span').text())/100;
		var insurance=parseFloat($('.insurance span').text());
		var giftpacking=parseFloat($('.giftpacking span').text());
		$('.total span').text((subtotal+ shipping-(coupon*subtotal)+insurance+giftpacking+0.000001).toFixed(2));
	} 

}


//保险礼物包装计算
$('#insurance').on('ifChecked', function (event) {
    $('.insurance').fadeIn(100);
    $('.insurance span').text($('#insurance').val());
    var $type = parseInt($('#coupon_id').attr('data-type'));
    var $condition = parseInt($('#coupon_id').attr('data-condition'));
    if($type==2){
        if($condition==3){
            sumprice(1);
        }else{
            sumprice(2);
        }
    }else {
        sumprice(1);
    }
    
});
$('#giftbox').on('ifChecked', function (event) {
    $('.giftpacking').fadeIn(100);
    $('.giftpacking span').text($('#giftbox').val());
    var $type = parseInt($('#coupon_id').attr('data-type'));
    var $condition = parseInt($('#coupon_id').attr('data-condition'));
    if($type==2){
        if($condition==3){
            sumprice(1);
        }else{
            sumprice(2);
        }
    }else {
        sumprice(1);
    }
});
$('#insurance').on('ifUnchecked', function (event) {
    $('.insurance').fadeOut(100);
    $('.insurance span').text(0);
    var $type = parseInt($('#coupon_id').attr('data-type'));
    var $condition = parseInt($('#coupon_id').attr('data-condition'));
    if($type==2){
        if($condition==3){
            sumprice(1);
        }else{
            sumprice(2);
        }
    }else {
        sumprice(1);
    }
});
$('#giftbox').on('ifUnchecked', function (event) {
    $('.giftpacking').fadeOut(100);
    $('.giftpacking span').text(0);
    var $type = parseInt($('#coupon_id').attr('data-type'));
    var $condition = parseInt($('#coupon_id').attr('data-condition'));
    if($type==2){
        if($condition==3){
            sumprice(1);
        }else{
            sumprice(2);
        }
    }else {
        sumprice(1);
    }
});



//使用coupon时的计算
function csumprice(e,coupon,min,max){
	$num = $('.ui-grid-b .dg-main-cart-center-price span').length;
	
	if (e == 1) {
		$coupons =0;
        $couponnum=0;
		$('.dg-main-cart-item').each(function($num){
			$iprice = parseFloat($(this).find('.dg-main-cart-center-price').data('price'));
	        if ($iprice >= min && $iprice <= max) {
	        	$coupons += coupon * $(this).find('.dg-main-cart-right-button span').text();
                $couponnum++;
            };
	    });
        if($couponnum==0){
            $.notifyBar({ cssClass: "dg-notify-error", html: 'The coupon could not be used for this order' ,position: "bottom" });
            $('.coupon small').html("-"+currency+"<span>" + "0" + "<span>");;
            $('#coupon_id').val("");
            /*$('#coupon_select option').eq(0).attr('selected','selected');
            $('#coupon_select').parent().find('span').text('Coupon Code Here');*/
        }
	    $('.coupon span').text($coupons);
	   	sumprice(1);
	   	
	}else if (e == 2) {
		sumprice(1);
		$subtotal=parseFloat($('.subtotal').text());
		if ($subtotal >= min && $subtotal <= max) {
			//$('.coupon small').html("-<?= $currency ?><span>" + (coupon) + "<span>");
        	sumprice(1);	
        }else{
        	$.notifyBar({ cssClass: "dg-notify-error", html: 'The coupon could not be used for this order' ,position: "bottom" });
        	$('.coupon small').html("-"+currency+"<span>" + "0" + "<span>");
            $('#coupon_id').val("");
            /*$('#coupon_select option').eq(0).attr('selected','selected');
            $('#coupon_select').parent().find('span').text('Coupon Code Here');*/
            sumprice(1);
        };
        
	}else if (e == 3) {

		$coupons =0;
        $couponnum=0;
		$('.dg-main-cart-item').each(function($num){
			$iprice = parseFloat($(this).find('.dg-main-cart-center-price').data('price'));
	        if ($iprice >= min && $iprice <= max) {
                $couponnum++;
	        	$coupons += ($iprice * coupon).toFixed(2) * $(this).find('.dg-main-cart-right-button span').text() ;	
            };
	    });
        if($couponnum==0){
            $.notifyBar({ cssClass: "dg-notify-error", html: 'The coupon could not be used for this order' ,position: "bottom" });
            $('.coupon small').html("-"+currency+"<span>" + "0" + "<span>");
            $('#coupon_id').val("");
            /*$('#coupon_select option').eq(0).attr('selected','selected');
            $('#coupon_select').parent().find('span').text('Coupon Code Here');*/
        }
	    $('.coupon span').text($coupons.toFixed(2));
	   	sumprice(1);
	}else if( e==4){
        $couponnum=0;
        $('.dg-main-cart-center-price').each(function ($num) {
            $iprice = parseFloat($(this).data('price'));
            if ($iprice >= min && $iprice <= max) {
                $('.coupon span').text($('.dg-main-cart-total h4:nth-child(2) span').text());
                sumprice(1);
                $couponnum++;
                return false;
            }
        });
        if($couponnum==0){
            $.notifyBar({cssClass: "dg-notify-error", html: 'The coupon could not be used for this order', position: "bottom"});
            sumprice(1);
            $('.coupon small').html("-"+currency+"<span>" + "0" + "<span>");
            $('#coupon_id').val("");
            /*$('#coupon_select option').eq(0).attr('selected','selected');
            $('#coupon_select').parent().find('span').text('Coupon Code Here');*/
            $('.coupon').data('coupon',false);
            sumprice(1);
        }
    }
}

//点击商品数量加减时重新计算总价格
function recalprice(){
    var $type = parseInt($('#coupon_id').attr('data-type'));
    var $condition = parseInt($('#coupon_id').attr('data-condition'));
    var minnum = parseInt($('#coupon_id').attr('data-min'));
    var maxnum = parseInt($('#coupon_id').attr('data-max'));
    var coupon = parseFloat($('#coupon_id').attr('data-amount'));
    
    if ($type == 1) {
        if ($condition == 1) {
            sumprice(1);
            //防止使用coupon后价钱为负
            var subtotal=parseFloat($('.subtotal').text());
            if((subtotal-coupon)<0){
                $('.coupon small').html("-"+currency+"<span>" + "0" + "<span>");
                $('#coupon_id').val("");
                /*$('#coupon_select option').eq(0).attr('selected','selected');
                $('#coupon_select').parent().find('span').text('Coupon Code Here');*/
                sumprice(1);
            }
        } else if ($condition == 2) {
            csumprice(2, coupon, minnum, maxnum);
            //防止使用coupon后价钱为负
            var subtotal=parseFloat($('.subtotal').text());
            if(subtotal<minnum) {
                $('.coupon small').html("-"+currency+"<span>" + "0" + "<span>");
                $('#coupon_id').val("");
                /*$('#coupon_select option').eq(0).attr('selected','selected');
                $('#coupon_select').parent().find('span').text('Coupon Code Here');*/
                sumprice(1);
            }
            
        } else if ($condition == 3) {
            csumprice(1, coupon, minnum, maxnum);
            //防止使用coupon后价钱为负
            var subtotal=parseFloat($('.subtotal').text());
            if((subtotal-coupon)<0) {
                $('.coupon small').html("-"+currency+"<span>" + "0" + "<span>");
                $('#coupon_id').val("");
                /*$('#coupon_select option').eq(0).attr('selected','selected');
                $('#coupon_select').parent().find('span').text('Coupon Code Here');*/
                sumprice(1);
            }
        }
    } else if ($type == 2) {
        if ($condition == 3) {
            csumprice(3, coupon, minnum, maxnum);
            //防止使用coupon后价钱为负
            var subtotal=parseFloat($('.subtotal').text());
            if((subtotal-(coupon*subtotal))<0){
                $('.coupon small').html("-"+currency+"<span>" + "0" + "<span>");
                $('#coupon_id').val("");
                /*$('#coupon_select option').eq(0).attr('selected','selected');
                $('#coupon_select').parent().find('span').text('Coupon Code Here');*/
                sumprice(1);
            }
        }else if($condition == 2){
            sumprice(2);
            //防止使用coupon后价钱为负
            var subtotal=parseFloat($('.subtotal').text());
            if(subtotal<minnum) {
                $('.coupon small').html("-"+currency+"<span>" + "0" + "<span>");
                $('#coupon_id').val("");
                /*$('#coupon_select option').eq(0).attr('selected','selected');
                $('#coupon_select').parent().find('span').text('Coupon Code Here');*/
                sumprice(1);
            }
            
        }else if($condition == 1){
            sumprice(2);
        }
    } else if ($type == 3) {
        if($condition==1){
            $('.coupon span').text($('.dg-main-cart-total h4:nth-child(2) span').text());
            sumprice(1);
        }else if($condition==2){
            sumprice(1);
            var subtotal=$('.subtotal').text();
            if(subtotal<minnum){
                $('.coupon small').html("-"+currency+"<span>" + "0" + "<span>");
                $('#coupon_id').val("");
                /*$('#coupon_select option').eq(0).attr('selected','selected');
                $('#coupon_select').parent().find('span').text('Coupon Code Here');*/
                $('.coupon').data('coupon',false);
                sumprice(1);
            }else{
                var loading=$('.coupon').data('coupon');
                if(loading){
                    $('.coupon span').text($('.dg-main-cart-total h4:nth-child(2) span').text());
                    sumprice(1);
                }
            }
        }else if($condition==3){
            sumprice(1);
            var subtotal=$('.subtotal').text();
            if(subtotal<minnum){
                $('.coupon small').html("-"+currency+"<span>" + "0" + "<span>");
                $('#coupon_id').val("");
                /*$('#coupon_select option').eq(0).attr('selected','selected');
                $('#coupon_select').parent().find('span').text('Coupon Code Here');*/
                $('.coupon').data('coupon',false);
                sumprice(1);
            }else{
                var loading=$('.coupon').data('coupon');
                if(loading){
                    $('.coupon span').text($('.dg-main-cart-total h4:nth-child(2) span').text());
                    sumprice(1);
                }
            }
        }
        //$('.coupon small').html("-<?= $currency ?><span>" + (coupon) + "</span>");
        
    }else{
        sumprice(1);
    }
}







