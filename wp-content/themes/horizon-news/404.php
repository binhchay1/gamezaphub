<?php

/**
 * The template for displaying 404 pages (not found)
 *
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 * @package Horizon News
 */

get_header();
?>
<main id="primary" class="site-main">
	<div id="notfound">
		<div class="notfound">
			<div class="notfound-404">
				<h1>4<span></span>4</h1>
			</div>
			<h2>Xin lỗi! Trang web không tìm thấy</h2>
			<p>Xin lỗi nhưng trang bạn đang tìm kiếm không tồn tại, đã bị xóa, tên đã thay đổi hoặc tạm thời không khả dụng</p>
			<a href="<?php home_url('/') ?>">Quay lại trang chủ</a>
		</div>

		<?php get_search_form(); ?>
	</div>
</main>

<?php
if (horizon_news_is_sidebar_enabled()) {
	get_sidebar();
}
get_footer();
