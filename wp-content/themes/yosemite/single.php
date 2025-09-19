<?php get_header(); ?>
<?php $mts_options = get_option(MTS_THEME_NAME); ?>
<div id="page" class="single">
	<div class="container">
		<?php
		if ('1' === $mts_options['mts_breadcrumb']) {
			mts_the_breadcrumb();
		}
		?>
		<article class="main-article <?php mts_article_class(); ?>">
			<div id="content_box" class="panel">
				<?php if (have_posts()) while (have_posts()) : the_post(); ?>
					<div id="post-<?php the_ID(); ?>" <?php post_class('g post'); ?>>
						<div class="single_post">
							<?php get_template_part('post-format/format', get_post_format()); ?>
							<header class="entry-header">
								<h1 class="title single-title entry-title"><?php the_title(); ?></h1>
							</header>
							<?php mts_the_postinfo('single'); ?>
							<div class="post-single-content box mark-links entry-content">
								<?php if ($mts_options['mts_posttop_adcode'] != '') { ?>
									<?php $toptime = $mts_options['mts_posttop_adcode_time'];
									if (strcmp(date("Y-m-d", strtotime("-$toptime day")), get_the_time("Y-m-d")) >= 0) { ?>
										<div class="topad">
											<?php echo do_shortcode($mts_options['mts_posttop_adcode']); ?>
										</div>
									<?php } ?>
								<?php } ?>
								<?php if (isset($mts_options['mts_social_button_position']) && $mts_options['mts_social_button_position'] == 'top') mts_social_buttons(); ?>
								<div class="thecontent">
									<?php the_content(); ?>
								</div>
								<?php wp_link_pages(array('before' => '<div class="pagination">', 'after' => '</div>', 'link_before'  => '<span class="current"><span class="currenttext">', 'link_after' => '</span></span>', 'next_or_number' => 'next_and_number', 'nextpagelink' => '<i class="fa fa-angle-right"></i>', 'previouspagelink' => '<i class="fa fa-angle-left"></i>', 'pagelink' => '%', 'echo' => 1)); ?>
								<?php if ($mts_options['mts_postend_adcode'] != '') { ?>
									<?php $endtime = $mts_options['mts_postend_adcode_time'];
									if (strcmp(date("Y-m-d", strtotime("-$endtime day")), get_the_time("Y-m-d")) >= 0) { ?>
										<div class="bottomad">
											<?php echo do_shortcode($mts_options['mts_postend_adcode']); ?>
										</div>
									<?php } ?>
								<?php } ?>
								<?php if (empty($mts_options['mts_social_button_position']) || $mts_options['mts_social_button_position'] != 'top') mts_social_buttons(); ?>
								<?php if ($mts_options['mts_tags'] == '1') { ?>
									<div class="tags"><?php mts_the_tags('<span class="tagtext">' . __('Tags', 'mythemeshop') . ':</span>', ', ') ?></div>
								<?php } ?>
							</div>
						</div>

						<?php if ($mts_options['mts_author_box'] == '1') { ?>
							<div class="postauthor">
								<h4 class="section-title"><?php _e('About The Author', 'mythemeshop'); ?></h4>
								<?php if (function_exists('get_avatar')) {
									echo get_avatar(get_the_author_meta('email'), '90');
								} ?>
								<h5 class="vcard"><a href="<?php echo get_author_posts_url(get_the_author_meta('ID')); ?>" class="fn"><?php the_author_meta('nickname'); ?></a></h5>
								<p><?php the_author_meta('description') ?></p>
							</div>
						<?php } ?>

					</div>
					<?php comments_template('', true); ?>
				<?php endwhile; ?>
			</div>
		</article>
		<?php if (isset($mts_options['mts_single_prevnext']) && $mts_options['mts_single_prevnext'] == 2 && !in_the_loop()) {
			global $post;
			$post = get_adjacent_post(true, '', false);
			if (!empty($post)) {
				setup_postdata($post); ?>
				<article class="next-article">
					<div id="content_box" class="panel">
						<div id="post-<?php the_ID(); ?>" <?php post_class('g post'); ?>>
							<div class="single_post next_post">
								<?php get_template_part('post-format/format', get_post_format()); ?>
							</div>
						</div>
					</div>
				</article>
		<?php wp_reset_postdata();
			}
		}
		get_sidebar(); ?>
	</div>
	<?php get_footer(); ?>