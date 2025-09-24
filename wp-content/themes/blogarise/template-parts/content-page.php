<?php

/**
 * Template part for displaying page content in page.php
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package blogarise
 */

if( class_exists('woocommerce') && (is_account_page() || is_cart() || is_checkout())) { ?>
		<div class="col-md-12">
			<div class="bs-card-box padding-20">
			<?php if (have_posts()) {  while (have_posts()) : the_post();  the_content(); endwhile; } ?>
			</div>
		</div> 
		<?php 
	} else {
		$blogarise_page_layout = get_theme_mod('blogarise_page_layout','page-align-content-right');
			if($blogarise_page_layout == "page-align-content-left"){ ?>
				<!--sidebar-->
					<!--col-md-4-->
						<aside class="col-lg-4">
								<?php get_sidebar();?>
						</aside>
					<!--/col-md-4-->
				<!--/sidebar-->
			<?php } ?>

        	<div class="<?php echo esc_attr($blogarise_page_layout == 'page-full-width-content' ? 'col-md-12' : 'col-lg-8') ?>">
				<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
					<div class="bs-card-box padding-20">
						<?php while (have_posts()) : the_post();  
							if(has_post_thumbnail()) {
								if ( is_single() ) { ?>
									<figure class="post-thumbnail">
										<?php the_post_thumbnail('full', array('class' => 'img-fluid attachment-full size-full')); ?>					
									</figure>
								<?php }
								else { ?>
									<figure class="post-thumbnail">
										<a href="<?php the_permalink(); ?>" >
											<?php the_post_thumbnail('full', array('class' => 'img-fluid attachment-full size-full')); ?>
										</a>				
									</figure>
								<?php }
							}		
							the_content();
							blogarise_edit_link();
							
							if (comments_open() || get_comments_number()) :
								comments_template();
							endif;
								
						endwhile; ?>	
					</div>
				</div>
			</div>

		<?php if($blogarise_page_layout == "page-align-content-right") { ?>
      		<!--sidebar-->
	          <!--col-md-4-->
	            <aside class="col-lg-4">
	                  <?php get_sidebar();?>
	            </aside>
	          <!--/col-md-4-->
      		<!--/sidebar-->
      	<?php }
	} ?>
	<!--Sidebar Area-->