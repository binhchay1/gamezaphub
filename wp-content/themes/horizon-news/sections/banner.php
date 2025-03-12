<?php
if ( ! get_theme_mod( 'horizon_news_enable_banner_section', false ) ) {
	return;
}

$main_news_content_ids  = $trending_content_ids = $editor_content_ids = array();
$trending_content_type  = get_theme_mod( 'horizon_news_trending_posts_content_type', 'post' );
$main_news_content_type = get_theme_mod( 'horizon_news_main_news_content_type', 'post' );
$editor_content_type    = get_theme_mod( 'horizon_news_editor_pick_content_type', 'post' );

if ( $main_news_content_type === 'post' ) {
	for ( $i = 1; $i <= 3; $i++ ) {
		$main_news_content_ids[] = get_theme_mod( 'horizon_news_main_news_content_post_' . $i );
	}
	$main_news_args = array(
		'post_type'           => 'post',
		'posts_per_page'      => absint( 3 ),
		'ignore_sticky_posts' => true,
	);
	if ( ! empty( array_filter( $main_news_content_ids ) ) ) {
		$main_news_args['post__in'] = array_filter( $main_news_content_ids );
		$main_news_args['orderby']  = 'post__in';
	} else {
		$main_news_args['orderby'] = 'date';
	}
} else {
	$cat_content_id = get_theme_mod( 'horizon_news_main_news_content_category' );
	$main_news_args = array(
		'cat'            => $cat_content_id,
		'posts_per_page' => absint( 3 ),
	);
}
$main_news_args = apply_filters( 'horizon_news_banner_section_args', $main_news_args );

if ( $trending_content_type === 'post' ) {
	for ( $i = 1; $i <= 6; $i++ ) {
		$trending_content_ids[] = get_theme_mod( 'horizon_news_trending_posts_content_post_' . $i );
	}
	$trending_args = array(
		'post_type'           => 'post',
		'posts_per_page'      => absint( 6 ),
		'ignore_sticky_posts' => true,
	);
	if ( ! empty( array_filter( $trending_content_ids ) ) ) {
		$trending_args['post__in'] = array_filter( $trending_content_ids );
		$trending_args['orderby']  = 'post__in';
	} else {
		$trending_args['orderby'] = 'date';
	}
} else {
	$cat_content_id = get_theme_mod( 'horizon_news_main_news_content_category' );
	$trending_args  = array(
		'cat'            => $cat_content_id,
		'posts_per_page' => absint( 6 ),
	);
}
$trending_args = apply_filters( 'horizon_news_banner_section_args', $trending_args );

if ( $editor_content_type === 'post' ) {
	for ( $i = 1; $i <= 3; $i++ ) {
		$editor_content_ids[] = get_theme_mod( 'horizon_news_editor_pick_content_post_' . $i );
	}
	$editor_args = array(
		'post_type'           => 'post',
		'posts_per_page'      => absint( 3 ),
		'ignore_sticky_posts' => true,
	);
	if ( ! empty( array_filter( $editor_content_ids ) ) ) {
		$editor_args['post__in'] = array_filter( $editor_content_ids );
		$editor_args['orderby']  = 'post__in';
	} else {
		$editor_args['orderby'] = 'date';
	}
} else {
	$cat_content_id = get_theme_mod( 'horizon_news_editor_choice_content_category' );
	$editor_args    = array(
		'cat'            => $cat_content_id,
		'posts_per_page' => absint( 3 ),
	);
}
$editor_args = apply_filters( 'horizon_news_banner_section_args', $editor_args );

horizon_news_render_banner_section( $main_news_args, $trending_args, $editor_args );

/**
 * Render Banner Section.
 */
function horizon_news_render_banner_section( $main_news_args, $trending_args, $editor_args ) {
	?>

	<section id="horizon_news_banner_section" class="banner-section magazine-frontpage-section banner-section-style-1 banner-grid-slider">
		<?php
		if ( is_customize_preview() ) :
			horizon_news_section_link( 'horizon_news_banner_section' );
		endif;
		?>
		<div class="ascendoor-wrapper">
			<div class="banner-section-wrapper">
				<?php
				require get_template_directory() . '/template-parts/banner/trending-posts.php';
				require get_template_directory() . '/template-parts/banner/main-news.php';
				require get_template_directory() . '/template-parts/banner/editor-pick.php';
				?>
			</div>
		</div>
	</section>

	<?php
}
