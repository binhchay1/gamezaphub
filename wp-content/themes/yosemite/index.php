<?php
$mts_options = get_option(MTS_THEME_NAME);
?>
<?php get_header(); ?>
<div id="page" class="blog-home">
	<div class="container">
		<div class="<?php mts_article_class(); ?>">
			<div id="content_box">
				<?php $j = 0; if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
					<article class="latestPost excerpt panel">
						<?php get_template_part( 'post-format/format', get_post_format() ); ?>
						<header class="entry-header">
							<h2 class="title front-view-title"><a href="<?php the_permalink() ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a></h2>
						</header>
						<?php mts_the_postinfo(); ?>
						<?php if (empty($mts_options['mts_full_posts'])) : ?>
	    					<div class="front-view-content">
	                            <?php echo mts_excerpt(65); ?>
	    					</div>
						    <?php mts_readmore(); ?>
					    <?php else : ?>
	                        <div class="front-view-content full-post">
	                            <?php the_content(); ?>
	                        </div>
	                        <?php if (mts_post_has_moretag()) : ?>
	                            <?php mts_readmore(); ?>
	                        <?php endif; ?>
	                    <?php endif; ?>
					</article><!--.post excerpt-->
				<?php $j++; endwhile; endif; ?>
				<!--Start Pagination-->
	            <?php if (isset($mts_options['mts_pagenavigation_type']) && $mts_options['mts_pagenavigation_type'] == '1' ) { ?>
	                <?php mts_pagination(); ?> 
				<?php } else { ?>
					<div class="pagination">
						<ul>
							<li class="nav-previous"><?php next_posts_link( __( '&larr; '.'Older posts', 'mythemeshop' ) ); ?></li>
							<li class="nav-next"><?php previous_posts_link( __( 'Newer posts'.' &rarr;', 'mythemeshop' ) ); ?></li>
						</ul>
					</div>
				<?php } ?>
				<!--End Pagination-->
			</div>
		</div>
		<?php get_sidebar(); ?>
	</div>
<?php get_footer(); ?>
