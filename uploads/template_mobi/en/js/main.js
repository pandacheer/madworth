//cart zero hide
function cartpronumshow(){
  if(parseInt($('#cartpronum').text())== 0){
    $('#cartpronum').hide();
  }else{
    $('#cartpronum').fadeIn(100);
  };
}

//product button show
function button_addcart_disabled(e,text){
  $(e).attr('disabled',true);
  $(e).text(text);
}
function button_addcart_enabled(e,text){
  $(e).attr('disabled',false);
  $(e).text(text);
}


function button_disabled_foot(e){
    //$(e).attr('disabled',true);
    $(e).isLoading({
        position:   "overlay",
        class:  "fa-refresh",    // loader CSS class
        tpl:'<u class=" %wrapper%">%text%<i class="fa %class% fa-spin"></i></u>'
    });
}
function button_enabled(e){
    $(e).isLoading("hide");
}
