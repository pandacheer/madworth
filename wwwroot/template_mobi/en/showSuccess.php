<?php echo $head; ?>
<?php echo isset($jumpUrl)&&!empty($jumpUrl) ? '<meta http-equiv="refresh" content="3;url=' . urldecode($jumpUrl) . '">' : "" ?>
            <div role="main" class="ui-content">
                <div class="dg-pagetitle">Success</div>
                <div class="dg-main-empty">
                    <div class="dg-main-empty-icon">
                        <span class="icon-right dg-main-empty-icon-success"></span>
                    </div>
                    <br>
                    <div class="dg-main-empty-msg">
                         <span><?php echo $successMessage ?></span>
                    </div>
                    <a href="/"><button data-role="none">Continue Shopping</button></a>
                </div>
            </div>
            <?php echo $foot; ?>
        </div>
        
    </body>
</html>