<?php echo $head; ?>
            <div role="main" class="ui-content">
                <div class="dg-pagetitle">Error</div>
                <div class="dg-main-empty">
                    <div class="dg-main-empty-icon">
                        <span class="icon-wrong dg-main-empty-icon-error"></span>
                    </div>
                    <div class="dg-main-empty-msg">
                         <span><?php echo $errorMessage ?></span>
                    </div>
                    <a href="<?php isset($actionUrl) ? $actionUrl : '/' ?>"><button data-role="none"><?php echo isset($actionTitle) ? $actionTitle : 'Continue Shopping' ?></button></a>
                </div>
            </div>
            <?php echo $foot; ?>
        </div>
        
    </body>
</html>