<?php
/**
 * The template for displaying 404 (Not Found) pages.
 *
 * @package Schema
 */

get_header();
?>

<div id="page">
	<article class="article">
		<div id="content_box" >
			<header>
				<div class="title">
					<h1><?php esc_html_e( 'Error 404 Not Found', 'schema' ); ?></h1>
				</div>
			</header>
			<div class="post-content">
				<p><?php esc_html_e( 'Oops! We couldn\'t find this Page.', 'schema' ); ?></p>
				<p><?php esc_html_e( 'Please check your URL or use the search form below.', 'schema' ); ?></p>
				<?php get_search_form(); ?>
			</div><!--.post-content--><!--#error404 .post-->
		</div><!--#content-->
	</article>
	<?php
	get_sidebar();
	get_footer();
