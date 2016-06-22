<script src="js/select2.min.js"></script>


<script>
    function format(state) {
        var originalOption = state.element;
        return "<img class='flag' src='img/flag/" + state.id.toUpperCase() + ".png' alt='" + state.id.toUpperCase() + "' />" + state.text;
    }
    $(".js-templating").select2({
        formatResult: format,
        formatSelection: format,
        escapeMarkup: function (m) {
            return m;
        }
    });

    $('.js-templating').change(function () {
        var optionSelected = $(this).find("option:selected").val();
        $.post('<?php echo site_url('home/changeCountry') ?>', {
            country_code: optionSelected
        }, function (result) {
            if (result.success) {
                 window.location.reload();
            } else {
                alert(optionSelected + result.error);
            }
        }, 'json');
    });
</script>

<script>
    $(function(){
        if($('.summernote').length){
            $('.summernote').summernote({
                height: 280,
                toolbar: [
                    ['style',['style']],
                    ['style', ['bold', 'italic', 'underline', 'clear']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['color', ['color']],
                    ['picture', ['picture']],
                    ['link', ['link']],
                    ['group', [ 'video' ]],
                    ['fullscreen', ['fullscreen']],
                    ['codeview', ['codeview']],
                ]
            });
        }
        
        /*
         * this swallows backspace keys on any non-input element.
         * stops backspace -> back
         */
        var rx = /INPUT|SELECT|TEXTAREA/i;
    
        $(document).bind("keydown keypress", function(e){
            if( e.which == 8 ){ // 8 == backspace
                if(!rx.test(e.target.tagName) || e.target.disabled || e.target.readOnly){
                    if(!$(e.target).hasClass("note-editable")){
                        e.preventDefault();
                    }
                    
                }
            }
        });
    })

// ctrl + s监听事件
// $(document).keydown(function(e){
//     if( e.ctrlKey  == true && e.keyCode == 83 ){
//        $('button[type="sumbit"]').trigger('click');
//     }
// });
</script>