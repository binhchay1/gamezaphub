<?php
/**
 * The template for displaying the header.
 *
 * Displays everything from the doctype declaration down to the navigation.
 *
 * @package Schema
 */
$mts_options    = get_option( MTS_THEME_NAME );
$header_class   = $mts_options['mts_header_style'];
$disable_header = '';
if ( is_singular() ) {
	$disable_header = get_post_meta( get_the_ID(), '_disable_header', true );
}
?>
<!DOCTYPE html>
<html class="no-js" <?php language_attributes(); ?>>

<head itemscope itemtype="http://schema.org/WebSite">
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<!-- Always force latest IE rendering engine (even in intranet) & Chrome Frame -->
	<!--[if IE ]>
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<![endif]-->
	<link rel="profile" href="http://gmpg.org/xfn/11" />
	<?php mts_meta(); ?>
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
	<?php wp_head(); ?>
</head>

<body id="blog" <?php body_class( 'main' ); ?> itemscope itemtype="http://schema.org/WebPage">
	<div class="main-container">
		<?php
		if ( empty( $disable_header ) ) {
			?>

				<?php
				// Elementor `header` location.
				if ( ! function_exists( 'elementor_theme_do_location' ) || ! elementor_theme_do_location( 'header' ) ) { ?>
					<header id="site-header" class="main-header <?php echo esc_attr( $header_class ); ?>" role="banner" itemscope itemtype="http://schema.org/WPHeader">
					<?php if ( '1' === $mts_options['mts_show_primary_nav'] ) {
						?>
						<div id="primary-nav">
							<div class="container">
								<div id="primary-navigation" class="primary-navigation" role="navigation" itemscope itemtype="http://schema.org/SiteNavigationElement">
									<nav class="navigation clearfix">
										<?php
										if ( has_nav_menu( 'primary-menu' ) ) {
											wp_nav_menu( array(
												'theme_location' => 'primary-menu',
												'menu_class' => 'menu clearfix',
												'container' => '',
												'walker' => new mts_menu_walker(),
											) );
										} else {
											?>
											<ul class="menu clearfix">
												<?php wp_list_pages( 'title_li=' ); ?>
											</ul>
										<?php } ?>
										<?php if ( '1' === $mts_options['mts_header_social_icons'] && ! empty( $mts_options['mts_header_social'] ) && is_array( $mts_options['mts_header_social'] ) ) { ?>
											<div class="header-social-icons">
											<?php foreach ( $mts_options['mts_header_social'] as $header_icons ) : ?>
												<?php if ( ! empty( $header_icons['mts_header_icon'] ) && isset( $header_icons['mts_header_icon'] ) ) : ?>
													<a href="<?php echo esc_url( $header_icons['mts_header_icon_link'] ); ?>" class="header-<?php echo esc_attr( $header_icons['mts_header_icon'] ); ?>" target="_blank">
														<span class="fa fa-<?php echo esc_attr( $header_icons['mts_header_icon'] ); ?>"></span>
													</a>
												<?php endif; ?>
											<?php endforeach; ?>
											</div>
										<?php } ?>
										<?php mts_cart(); ?>
									</nav>
								</div>
							</div>
						</div>
						<?php
					}

					if ( 'regular_header' === $mts_options['mts_header_style'] ) {
						?>
						<div id="regular-header">
							<div class="container">
								<div class="logo-wrap">
									<?php
									$mts_logo = wp_get_attachment_image_src( $mts_options['mts_logo'], 'full' );
									if ( '' !== $mts_options['mts_logo'] && $mts_logo ) {
										if ( is_home() || is_404() ) {
											?>
											<h1 id="logo" class="image-logo" itemprop="headline">
												<a href="<?php echo esc_url( home_url() ); ?>"><img src="<?php echo esc_url( $mts_logo[0] ); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>" width="<?php echo esc_attr( $mts_logo[1] ); ?>" height="<?php echo esc_attr( $mts_logo[2] ); ?>"></a>
											</h1><!-- END #logo -->
											<?php
										} else {
											?>
											<h2 id="logo" class="image-logo" itemprop="headline">
												<a href="<?php echo esc_url( home_url() ); ?>">
													<img src="<?php echo esc_url( $mts_logo[0] ); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>" width="<?php echo esc_attr( $mts_logo[1] ); ?>" height="<?php echo esc_attr( $mts_logo[2] ); ?>"></a>
											</h2><!-- END #logo -->
											<?php
										}
									} else {
										if ( is_home() || is_404() ) {
											?>
											<h1 id="logo" class="text-logo" itemprop="headline">
												<a href="<?php echo esc_url( home_url() ); ?>"><?php bloginfo( 'name' ); ?></a>
											</h1><!-- END #logo -->
											<?php
										} else {
											?>
											<h2 id="logo" class="text-logo" itemprop="headline">
												<a href="<?php echo esc_url( home_url() ); ?>"><?php bloginfo( 'name' ); ?></a>
											</h2><!-- END #logo -->
											<?php
										}
									}
									?>
								</div>
								<?php if ( '' !== $mts_options['mts_header_adcode'] ) { ?>
									<div class="widget-header"><?php echo $mts_options['mts_header_adcode']; // PHPCS:ignore ?></div>
								<?php } ?>
							</div>
						</div>
						<?php
					}

					if ( '1' === $mts_options['mts_sticky_nav'] ) {
						?>
						<div class="clear" id="catcher"></div>
						<div id="header" class="sticky-navigation">
						<?php
					} else {
						?>
						<div id="header">
						<?php
					}
					?>
						<div class="container">
							<?php if ( 'logo_in_nav_header' === $mts_options['mts_header_style'] ) { ?>
								<div class="logo-wrap">
									<?php
									$mts_logo = wp_get_attachment_image_src( $mts_options['mts_logo'], 'full' );
									if ( '' !== $mts_options['mts_logo'] && $mts_logo ) {
										if ( is_front_page() || is_home() || is_404() ) {
											?>
											<h1 id="logo" class="image-logo" itemprop="headline">
												<a href="<?php echo esc_url( home_url() ); ?>"><img src="<?php echo esc_url( $mts_logo[0] ); ?>" alt="<?php bloginfo( 'name' ); ?>" width="<?php echo esc_attr( $mts_logo[1] ); ?>" height="<?php echo esc_attr( $mts_logo[2] ); ?>" /></a>
											</h1><!-- END #logo -->
											<?php
										} else {
											?>
											<h2 id="logo" class="image-logo" itemprop="headline">
												<a href="<?php echo esc_url( home_url() ); ?>"><img src="<?php echo esc_url( $mts_logo[0] ); ?>" alt="<?php bloginfo( 'name' ); ?>" width="<?php echo esc_attr( $mts_logo[1] ); ?>" height="<?php echo esc_attr( $mts_logo[2] ); ?>" /></a>
											</h2><!-- END #logo -->
											<?php
										}
									} else {
										if ( is_front_page() || is_home() || is_404() ) {
											?>
											<h1 id="logo" class="text-logo" itemprop="headline">
												<a href="<?php echo esc_url( home_url() ); ?>"><?php bloginfo( 'name' ); ?></a>
											</h1><!-- END #logo -->
											<?php
										} else {
											?>
											<h2 id="logo" class="text-logo" itemprop="headline">
												<a href="<?php echo esc_url( home_url() ); ?>"><?php bloginfo( 'name' ); ?></a>
											</h2><!-- END #logo -->
											<?php
										}
									}
									?>
								</div>
							<?php } ?>

							<div id="secondary-navigation" class="secondary-navigation" role="navigation" itemscope itemtype="http://schema.org/SiteNavigationElement">
								<a href="#" id="pull" class="toggle-mobile-menu"><?php esc_html_e( 'Menu', 'schema' ); ?></a>
								<?php if ( has_nav_menu( 'mobile' ) ) { ?>
									<nav class="navigation clearfix">
										<?php
										if ( has_nav_menu( 'secondary-menu' ) ) {
											wp_nav_menu( array(
												'theme_location' => 'secondary-menu',
												'menu_class' => 'menu clearfix',
												'container' => '',
												'walker' => new mts_menu_walker(),
											) );
										} else {
											?>
											<ul class="menu clearfix">
												<?php wp_list_categories( 'title_li=' ); ?>
											</ul>
											<?php
										}
										?>
									</nav>
									<nav class="navigation mobile-only clearfix mobile-menu-wrapper">
										<?php
										wp_nav_menu( array(
											'theme_location' => 'mobile',
											'menu_class' => 'menu clearfix',
											'container'  => '',
											'walker'     => new mts_menu_walker(),
										) );
										?>
									</nav>
								<?php } else { ?>
									<nav class="navigation clearfix mobile-menu-wrapper">
										<?php
										if ( has_nav_menu( 'secondary-menu' ) ) {
											wp_nav_menu( array(
												'theme_location' => 'secondary-menu',
												'menu_class' => 'menu clearfix',
												'container' => '',
												'walker' => new mts_menu_walker(),
											) );
										} else {
											?>
											<ul class="menu clearfix">
												<?php wp_list_categories( 'title_li=' ); ?>
											</ul>
											<?php
										}
										?>
									</nav>
								<?php } ?>
							</div>
						</div><!--.container-->
					</div>
					</header>
					<?php
				}
				?>


			<?php
			if ( 'logo_in_nav_header' === $mts_options['mts_header_style'] && '' !== $mts_options['mts_header_adcode'] ) {
				?>
				<div class="container small-header">
					<div class="widget-header"><?php echo $mts_options['mts_header_adcode']; // PHPCS:ignore ?></div>
				</div>
				<?php
			}
		}
