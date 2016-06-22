<?php echo $head; ?>
            <div role="main" class="ui-content">
                <div class="dg-pagetitle">Personal Details</div>
                <label>FirstName</label>
                <input type="text" id="firstname" placeholder="" value="" />

                <label>LastName</label>
                <input type="text" id="lastname" placeholder="" value="" />

                <label>Email</label>
                <input type="text" id="email" placeholder="" value="paddyzhu@me.com" readonly/>
                
                <label>Birthday</label>
                <input type="text" id="birthday" placeholder="" value="" class="datepicker" readonly/>

                <label>Gender</label>
                <div class="dg-main-check">
                    <div class="dg-main-check-list">
                        <div class="iradio_square-blue checked" style="float:left;" id="male"></div>
                            <input type="radio"
                                   value="2" data-role="none"  style="opacity: 0;"> 
                            <strong>Male</strong>
                    </div>
                    <div class="dg-main-check-list">
                        <div class="iradio_square-blue" style="float:left;" id="female"></div>
                            <input type="radio"
                                   value="1" data-role="none"  style="opacity: 0;"> 
                            <strong>Female</strong>
                    </div>
                </div>

                <a href="#">Change Your Password</a>                
                
                <button data-theme="b" class="dg-account-button">Save</button>
            </div>
            <?php echo $foot; ?>
        </div>
        
        <script>
            $(function() {
                var dgbirthday = new Object;
                dgbirthday.year = 1980;
                $('.datepicker').datepicker({
                    startView: 2,
                    defaultViewDate: dgbirthday,
                    autoclose: true,
                });

                $('.dg-main-check-list').click(function(){
                     $(this).siblings().children().removeClass('checked');
                     $(this).children().addClass('checked');
                })
            });
        </script>
    </body>
</html>