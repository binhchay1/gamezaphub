<?php 
    $mts_options = get_option(MTS_THEME_NAME);
?>
    </div>
    <footer class="main-footer" role="contentinfo" itemscope itemtype="http://schema.org/WPFooter">
        <div id="footer" class="clearfix">
            <div class="container">
                <div class="copyrights">
                    <?php mts_copyrights_credit(); ?>
                </div>
            </div>
        </div>
    </footer>
</div>
<?php mts_footer(); ?>
	<?php wp_footer(); ?>
	
	<script src="<?php echo get_template_directory_uri(); ?>/js/gaming-effects.js"></script>
</body>
</html>