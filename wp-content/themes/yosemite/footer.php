<?php 
    $mts_options = get_option(MTS_THEME_NAME);
?>
    </div><!--#page-->
    <footer class="main-footer" role="contentinfo" itemscope itemtype="http://schema.org/WPFooter">
        <div id="footer" class="clearfix">
            <div class="container">
                <div class="copyrights">
                    <?php mts_copyrights_credit(); ?>
                </div>
            </div><!--.container-->
        </div>
    </footer><!--footer-->
</div><!--.main-container-->
<?php mts_footer(); ?>
<?php wp_footer(); ?>
</body>
</html>