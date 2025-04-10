<?php

/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Horizon News
 */

?>
<!doctype html>
<html <?php language_attributes(); ?>>

<head>
	<meta charset="<?php bloginfo('charset'); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">
	<script async src="https://www.googletagmanager.com/gtag/js?id=AW-16922998234"></script>
	<link href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css" rel="stylesheet">

	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
	<?php wp_body_open(); ?>
	<div id="page" class="site ascendoor-site-wrapper">
		<a class="skip-link screen-reader-text" href="#primary"><?php esc_html_e('Skip to content', 'horizon-news'); ?></a>
		<div id="loader">
			<div class="loader-container">
				<div id="preloader" class="style-2">
					<div class="dot"></div>
				</div>
			</div>
		</div>
		<header id="masthead" class="site-header logo-size-small">
			<?php if (get_theme_mod('horizon_news_enable_topbar', false) === true) : ?>
				<div class="top-header-part">
					<div class="ascendoor-wrapper">
						<div class="top-header-wrapper">
							<div class="top-header-left">
								<div class="date-wrap">
									<?php if (has_custom_logo()) { ?>
										<div class="site-logo">
											<?php the_custom_logo(); ?>
										</div>
									<?php } ?>
								</div>
							</div>
							<div class="top-header-right">
								<div class="auth-buttons">
									<?php if (is_user_logged_in() || is_custom_user_logged_in()) : ?>
										<?php
										if (is_user_logged_in()) {
											$current_user = wp_get_current_user();
											$avatar = get_avatar($current_user->ID, 32);
											$display_name = esc_html($current_user->display_name);
											$logout_url = wp_logout_url(home_url('/'));
										} else {
											$custom_user = get_custom_user();
											$avatar = get_avatar($custom_user['email'], 32);
											$display_name = esc_html($custom_user['name']);
											$logout_url = home_url('?custom_logout=1');
											$profile_url = home_url('/profile');
										}

										?>
										<div class="user-menu">
											<a id="signin-button" class="profile-btn">
												<div class="user-info">
													<?php echo $avatar; ?>
													<span class="user-name"><?php echo $display_name; ?></span>
												</div>
											</a>

											<div class="dropdown-menu">
												<a href="<?php echo home_url('/profile'); ?>">Xem Profile</a>
												<a href="<?php echo esc_url($logout_url); ?>">Đăng Xuất</a>
											</div>
										</div>
									<?php else : ?>
										<a id="signin-button" class="signin-btn">Đăng nhập ngay</a>
										<?php require get_template_directory() . '/sections/modal-auth.php'; ?>
									<?php endif; ?>
								</div>
							</div>
						</div>
					</div>
				</div>
			<?php endif; ?>

			<div class="bottom-header-part-outer">
				<div class="bottom-header-part">
					<div class="ascendoor-wrapper">
						<div class="bottom-header-wrapper">
							<div class="navigation-part">
								<nav id="site-navigation" class="main-navigation">
									<button class="menu-toggle" aria-controls="primary-menu" aria-expanded="false" aria-label="Expand Menu">
										<span class="hamburger">
											<svg viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
												<circle cx="50" cy="50" r="30"></circle>
												<path class="line--1" d="M0 70l28-28c2-2 2-2 7-2h64"></path>
												<path class="line--2" d="M0 50h99"></path>
												<path class="line--3" d="M0 30l28 28c2 2 2 2 7 2h64"></path>
											</svg>
										</span>
									</button>
									<div class="main-navigation-links">
										<?php
										if (has_nav_menu('primary')) {
											wp_nav_menu(
												array(
													'theme_location' => 'primary',
												)
											);
										}
										?>
									</div>
								</nav><!-- #site-navigation -->
							</div>
							<div class="bottom-header-right-part">
								<div class="header-search">
									<div class="header-search-wrap">
										<a href="#" title="Search" class="header-search-icon">
											<i class="fa-solid fa-magnifying-glass"></i>
										</a>
										<div class="header-search-form">
											<?php get_search_form(); ?>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</header>

		<?php

		if (is_true_homepage()) {
			require get_template_directory() . '/sections/sections.php';
		}
		?>
		<div id="content" class="site-content">
			<div class="ascendoor-wrapper">
				<div class="ascendoor-page">