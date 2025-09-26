<?php

if (is_admin()) {
	wp_enqueue_style('lasso-layout-6-box', LASSO_PLUGIN_URL . '/admin/assets/css/layout-6-box.css', array(), '1.0');
}


$isPC = false;
$isNintendo = false;
$isPlaystation = false;
$isXbox = false;
$isMobile = false;
$cache_key = 'rawg_stores';
$dataStores = get_transient($cache_key);

$anchor_id_html = isset($anchor_id_html) ? $anchor_id_html : '';
$theme_name = isset($theme_name) ? $theme_name : 'lasso-cactus';
$css_display_theme_mobile = isset($css_display_theme_mobile) ? $css_display_theme_mobile : '';

require_once __DIR__ . '/svg-loader.php';

$container_classes = 'lasso-container';

?>

<?php do_action('lasso_layout_6_box_before', $lasso_url); ?>

<div <?php echo $anchor_id_html ?> class="<?php echo esc_attr($container_classes); ?>">
	<div class="lasso-display <?php echo $theme_name . ' lasso-url-' . $lasso_url->slug . ' ' . ($css_display_theme_mobile ?? ''); ?>">
		<div class="container-box-lasso">
			<div class="header-box-lasso">
				<img src="<?php echo $lasso_url->background_image ?>" alt="Game Image" class="game-image">
				<div class="game-info">
					<div class="game-title-area">
						<a aria-label="Đọc bài" href="<?php echo $lasso_url->target_url ?>"><span class="game-title"><?php echo $lasso_url->name ?></span></a>
						<div class="rating-title">GR ★ <?php echo $lasso_url->rating ?>/5</div>
					</div>

					<div class="d-flex justify-content-between mt-10">
						<div class="genre">
							<?php if (is_array($lasso_url->genres)) { ?>
								<?php foreach ($lasso_url->genres as $genre) { ?>
									<div class="genre-item">
										<?php echo $genre['name'] ?>
									</div>
								<?php } ?>
							<?php } ?>
						</div>
						<div class="systems">
							<?php if (is_array($lasso_url->platforms)) { ?>
								<?php foreach ($lasso_url->platforms as $platform) { ?>
									<?php if (strpos($platform['platform']['name'], 'PC') !== false && !$isPC) { ?>
										<?php $isPC = true; ?>
										<div class="platform-icon pc-icon" title="PC">
											<?php
											$pc_svg = getPlatformSVG('pc');
											if ($pc_svg) {
												echo $pc_svg;
											}
											?>
										</div>
									<?php } ?>
									<?php if (strpos($platform['platform']['name'], 'Nintendo') !== false && !$isNintendo) { ?>
										<?php $isNintendo = true; ?>
										<div class="platform-icon nintendo-icon" title="Nintendo Switch">
											<?php
											$nintendo_svg = getPlatformSVG('nintendo');
											if ($nintendo_svg) {
												echo $nintendo_svg;
											}
											?>
										</div>
									<?php } ?>
									<?php if (strpos($platform['platform']['name'], 'PlayStation') !== false && !$isPlaystation) { ?>
										<?php $isPlaystation = true; ?>
										<div class="platform-icon playstation-icon" title="PlayStation">
											<?php
											$ps_svg = getPlatformSVG('playstation');
											if ($ps_svg) {
												echo $ps_svg;
											}
											?>
										</div>
									<?php } ?>
									<?php if (strpos($platform['platform']['name'], 'Xbox') !== false && !$isXbox) { ?>
										<?php $isXbox = true; ?>
										<div class="platform-icon xbox-icon" title="Xbox">
											<?php
											$xbox_svg = getPlatformSVG('xbox');
											if ($xbox_svg) {
												echo $xbox_svg;
											}
											?>
										</div>
									<?php } ?>
									<?php if ((strpos($platform['platform']['name'], 'iOS') !== false || strpos($platform['platform']['name'], 'Android') !== false) && !$isMobile) { ?>
										<?php $isMobile = true; ?>
										<div class="platform-icon mobile-icon" title="Mobile">
											<?php
											$mobile_svg = getPlatformSVG('mobile');
											if ($mobile_svg) {
												echo $mobile_svg;
											}
											?>
										</div>
									<?php } ?>
								<?php } ?>
							<?php } ?>
						</div>
					</div>
				</div>
			</div>
			<p class="game-description">
				<?php echo $lasso_url->description ?>
			</p>

			<div class="game-details">
				<div class="details-row">
					<div class="detail-card">
						<div class="card-icon">
							<svg viewBox="0 0 24 24" fill="currentColor">
								<path d="M19 3h-1V1h-2v2H8V1H6v2H5c-1.11 0-1.99.9-1.99 2L3 19c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm0 16H5V8h14v11zM7 10h5v5H7z" />
							</svg>
						</div>
						<div class="card-content">
							<span class="card-label">📅 Ngày phát hành</span>
							<span class="card-value"><?php echo $lasso_url->released ?></span>
						</div>
					</div>

					<div class="detail-card">
						<div class="card-icon">
							<svg viewBox="0 0 24 24" fill="currentColor">
								<path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z" />
							</svg>
						</div>
						<div class="card-content">
							<span class="card-label">🎯 Độ tuổi</span>
							<span class="card-value"><?php echo $lasso_url->esrb_rating_name ?></span>
						</div>
					</div>

					<div class="detail-card">
						<div class="card-icon">
							<svg viewBox="0 0 24 24" fill="currentColor">
								<path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z" />
							</svg>
						</div>
						<div class="card-content">
							<span class="card-label">👨‍💻 Nhà phát triển</span>
							<span class="card-value">
								<?php $countDeveloper = 0; ?>
								<?php if (is_array($lasso_url->developers) || is_object($lasso_url->developers)) { ?>
									<?php foreach ($lasso_url->developers as $developer) { ?>
										<?php if ($countDeveloper >= 1) { ?>
											<?php break; ?>
										<?php } ?>
										<?php echo $developer['name'] ?>
										<?php $countDeveloper++; ?>
									<?php } ?>
								<?php } ?>
							</span>
						</div>
					</div>

					<div class="detail-card">
						<div class="card-icon">
							<svg viewBox="0 0 24 24" fill="currentColor">
								<path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 17.93c-3.94-.49-7-3.85-7-7.93 0-.62.08-1.21.21-1.79L9 15v1c0 1.1.9 2 2 2v1.93zm6.9-2.54c-.26-.81-1-1.39-1.9-1.39h-1v-3c0-.55-.45-1-1-1H8v-2h2c.55 0 1-.45 1-1V7h2c1.1 0 2-.9 2-2v-.41c2.93 1.19 5 4.06 5 7.41 0 2.08-.8 3.97-2.1 5.39z" />
							</svg>
						</div>
						<div class="card-content">
							<span class="card-label">🏢 Nhà phát hành</span>
							<span class="card-value">
								<?php $countPublish = 0; ?>
								<?php if (is_array($lasso_url->publishers) || is_object($lasso_url->publishers)) { ?>
									<?php foreach ($lasso_url->publishers as $publisher) { ?>
										<?php if ($countPublish >= 1) { ?>
											<?php break; ?>
										<?php } ?>
										<?php echo $publisher['name'] ?>
										<?php $countPublish++; ?>
									<?php } ?>
								<?php } ?>
							</span>
						</div>
					</div>
				</div>

				<div class="stores-section">
					<p class="stores-title">Cửa hàng</p>
					<div class="stores-grid">
						<?php
						$storeMapping = [
							'1' => ['slug' => 'steam', 'name' => 'Steam'],
							'11' => ['slug' => 'epic-games', 'name' => 'Epic Games Store']
						];

						if (is_array($lasso_url->stores)) {
							foreach ($lasso_url->stores as $index => $recordStore) {

								if (isset($storeMapping[$recordStore['store_id']])) {
									$store = $storeMapping[$recordStore['store_id']];
						?>
									<a href="<?php echo $recordStore['url'] ?>" target="_blank" aria-label="Cửa hàng" class="store-link">
										<div class="store-icon" data-store="<?php echo $store['slug'] ?>">
											<?php
											$store_svg = getStoreSVG($store['slug']);
											if ($store_svg) {
												echo $store_svg;
											}
											?>
										</div>
									</a>
						<?php
								}
							}
						}
						?>
					</div>
				</div>
			</div>

			<?php if (is_array($lasso_url->ratings)) { ?>
				<div class="community-ratings">
					<p class="ratings-title">Đánh giá cộng đồng</p>
					<div class="ratings-chart">
						<?php if (array_key_exists(0, $lasso_url->ratings) && array_key_exists('percent', $lasso_url->ratings[0])) { ?>
							<div class="rating-segment recommended" style="width: <?php echo $lasso_url->ratings[0]['percent'] ?>%">
								<div class="segment-content">
									<span class="segment-label">👍</span>
									<span class="segment-percent"><?php echo $lasso_url->ratings[0]['percent'] ?>%</span>
								</div>
							</div>
						<?php } ?>

						<?php if (array_key_exists(1, $lasso_url->ratings) && array_key_exists('percent', $lasso_url->ratings[1])) { ?>
							<div class="rating-segment exceptional" style="width: <?php echo $lasso_url->ratings[1]['percent'] ?>%">
								<div class="segment-content">
									<span class="segment-label">⭐</span>
									<span class="segment-percent"><?php echo $lasso_url->ratings[1]['percent'] ?>%</span>
								</div>
							</div>
						<?php } ?>

						<?php if (array_key_exists(2, $lasso_url->ratings) && array_key_exists('percent', $lasso_url->ratings[2])) { ?>
							<div class="rating-segment mixed" style="width: <?php echo $lasso_url->ratings[2]['percent'] ?>%">
								<div class="segment-content">
									<span class="segment-label">😐</span>
									<span class="segment-percent"><?php echo $lasso_url->ratings[2]['percent'] ?>%</span>
								</div>
							</div>
						<?php } ?>

						<?php if (array_key_exists(3, $lasso_url->ratings) && array_key_exists('percent', $lasso_url->ratings[3])) { ?>
							<div class="rating-segment skip" style="width: <?php echo $lasso_url->ratings[3]['percent'] ?>%">
								<div class="segment-content">
									<span class="segment-label">👎</span>
									<span class="segment-percent"><?php echo $lasso_url->ratings[3]['percent'] ?>%</span>
								</div>
							</div>
						<?php } ?>
					</div>
				</div>
			<?php } ?>

			<div class="footer-box-lasso">
				<div class="owl-carousel owl-theme">
					<?php if (is_array($lasso_url->screen_shots)) { ?>
						<?php foreach ($lasso_url->screen_shots as $index => $screen_shot) { ?>
							<?php if ($index == 0) {
								continue;
							} ?>
							<div class="item">
								<img src="<?php echo $screen_shot ?>" alt="screen shot">
							</div>
						<?php } ?>
					<?php } ?>
				</div>
			</div>
		</div>
	</div>
</div>