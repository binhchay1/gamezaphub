<?php

/** @var bool $is_show_description */

use Lasso\Classes\Helper as Lasso_Helper;
use Lasso\Classes\Html_Helper as Lasso_Html_Helper;

/** @var bool $is_show_disclosure */
/** @var bool $is_show_fields */
/** @var string $type */

?>

<?php

$isPC = false;
$isNintendo = false;
$isPlaystation = false;
$isXbox = false;
$isMobile = false;
$cache_key = 'rawg_stores';
$dataStores = get_transient($cache_key);

?>

<div <?php echo $anchor_id_html ?> class="lasso-container">
	<div class="lasso-display <?php echo $theme_name . ' lasso-url-' . $lasso_url->slug . ' ' . $css_display_theme_mobile ?? ''; ?>">
		<div class="container-box-lasso">
			<div class="header-box-lasso">
				<img src="<?php echo $lasso_url->background_image ?>" alt="Game Image" class="game-image">
				<div class="game-info">
					<div class="game-title-area">
						<a href="<?php echo $lasso_url->target_url ?>"><span class="game-title"><?php echo $lasso_url->name ?></span></a>
						<div class="rating-title">GR ★ <?php echo $lasso_url->rating ?>/5</div>
					</div>

					<div class="d-flex justify-content-between mt-3">
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
									<?php if (strpos($platform['platform']['name'], 'PC') !== false and !$isPC) { ?>
										<?php $isPC = true; ?>
										<div class="platform-icon pc-icon" title="PC">
											<svg viewBox="0 0 24 24" fill="currentColor">
												<rect x="3" y="5" width="18" height="12" rx="2" ry="2" />
												<rect x="5" y="7" width="14" height="8" rx="1" fill="white" />
												<circle cx="7" cy="19" r="1" />
												<circle cx="17" cy="19" r="1" />
											</svg>
										</div>
									<?php } ?>
									<?php if (strpos($platform['platform']['name'], 'Nintendo') !== false and !$isNintendo) { ?>
										<?php $isNintendo = true; ?>
										<div class="platform-icon nintendo-icon" title="Nintendo Switch">
											<svg viewBox="0 0 24 24" fill="currentColor">
												<rect x="3" y="4" width="6" height="16" rx="2" ry="2" />
												<rect x="15" y="4" width="6" height="16" rx="2" ry="2" />
												<rect x="9" y="8" width="6" height="8" rx="1" ry="1" />
												<circle cx="6" cy="10" r="1" fill="white" />
												<circle cx="18" cy="14" r="1" fill="white" />
											</svg>
										</div>
									<?php } ?>
									<?php if (strpos($platform['platform']['name'], 'PlayStation') !== false and !$isPlaystation) { ?>
										<?php $isPlaystation = true; ?>
										<div class="platform-icon playstation-icon" title="PlayStation">
											<svg viewBox="0 0 24 24" fill="currentColor">
												<circle cx="12" cy="12" r="10" />
												<text x="12" y="16" text-anchor="middle" fill="white" font-size="10" font-weight="bold" font-family="Arial">PS</text>
											</svg>
										</div>
									<?php } ?>
									<?php if (strpos($platform['platform']['name'], 'Xbox') !== false and !$isXbox) { ?>
										<?php $isXbox = true; ?>
										<div class="platform-icon xbox-icon" title="Xbox">
											<svg viewBox="0 0 24 24" fill="currentColor">
												<circle cx="12" cy="12" r="10" />
												<text x="12" y="17" text-anchor="middle" fill="white" font-size="12" font-weight="bold" font-family="Arial">X</text>
											</svg>
										</div>
									<?php } ?>
									<?php if ((strpos($platform['platform']['name'], 'iOS') !== false or strpos($platform['platform']['name'], 'Android') !== false) and !$isMobile) { ?>
										<?php $isMobile = true; ?>
										<div class="platform-icon mobile-icon" title="Mobile">
											<svg viewBox="0 0 24 24" fill="currentColor">
												<rect x="6" y="2" width="12" height="20" rx="3" ry="3" />
												<rect x="8" y="5" width="8" height="12" rx="1" fill="white" />
												<circle cx="12" cy="19" r="1" fill="white" />
											</svg>
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
								<?php if (is_array($lasso_url->developers) or is_object($lasso_url->developers)) { ?>
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
								<?php if (is_array($lasso_url->publishers) or is_object($lasso_url->publishers)) { ?>
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
					<h4 class="stores-title">Cửa hàng</h4>
					<div class="stores-grid">
						<?php if (is_array($lasso_url->stores) and is_array($dataStores)) {
							foreach ($lasso_url->stores as $recordStore) {
								foreach ($dataStores['results'] as $store) {
									if ($recordStore['store_id'] == $store['id']) { ?>
										<a href="<?php echo $recordStore['url'] ?>" target="_blank" class="store-link">
											<div class="store-icon" data-store="<?php echo $store['slug'] ?>">
												<?php

												function getStoreSVG($slug)
												{
													switch ($slug) {
														case 'steam':
															return '<svg viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C6.48 2 2 6.48 2 12c0 1.54.36 3 .97 4.29l5.28-2.17c.31-.44.81-.74 1.38-.74.26 0 .51.06.73.16l2.64-3.83V9.5c0-1.93 1.57-3.5 3.5-3.5S20 7.57 20 9.5s-1.57 3.5-3.5 3.5h-.08l-3.63 2.59c0 .14.02.28.02.42 0 1.38-1.12 2.5-2.5 2.5-1.23 0-2.25-.89-2.45-2.06L2.04 13.61C2.28 18.47 6.29 22 12 22c5.52 0 10-4.48 10-10S17.52 2 12 2z"/></svg>';
														case 'epic-games-store':
															return '<svg viewBox="0 0 24 24" fill="currentColor"><path d="M2 12C2 6.48 6.48 2 12 2s10 4.48 10 10-4.48 10-10 10S2 17.52 2 12zm8-4v8h4v-2h-2v-2h2V8h-4zm6 0v8h2V8h-2z"/></svg>';
														case 'gog':
															return '<svg viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 15h-2v-2h2v2zm0-4h-2V9h2v4zm4 4h-2v-6h2v6z"/></svg>';
														case 'origin':
															return '<svg viewBox="0 0 24 24" fill="currentColor"><circle cx="12" cy="12" r="10"/><path d="M8 8h8v8H8z" fill="white"/></svg>';
														case 'uplay':
															return '<svg viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm4 14l-4-2-4 2V8l4 2 4-2v8z"/></svg>';
														default:
															return '<svg viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/></svg>';
													}
												}
												echo getStoreSVG($store['slug']);
												?>
											</div>
											<span class="store-name"><?php echo $store['name'] ?></span>
										</a>
									<?php } ?>
								<?php } ?>
							<?php } ?>
						<?php } ?>
					</div>
				</div>
			</div>

			<?php if (is_array($lasso_url->ratings)) { ?>
				<div class="community-ratings">
					<h4 class="ratings-title">Đánh giá cộng đồng</h4>
					<div class="ratings-chart">
						<?php if (array_key_exists(0, $lasso_url->ratings) and array_key_exists('percent', $lasso_url->ratings[0])) { ?>
							<div class="rating-segment recommended" data-percent="<?php echo $lasso_url->ratings[0]['percent'] ?>">
								<div class="segment-bar"></div>
								<div class="segment-content">
									<div class="segment-icon">
										<svg viewBox="0 0 24 24" fill="currentColor">
											<path d="M7 14l5-5 5 5z" />
											<path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z" />
										</svg>
									</div>
									<div class="segment-info">
										<span class="segment-label">👍 Khuyên dùng</span>
										<span class="segment-percent"><?php echo $lasso_url->ratings[0]['percent'] ?>%</span>
									</div>
								</div>
							</div>
						<?php } ?>

						<?php if (array_key_exists(1, $lasso_url->ratings) and array_key_exists('percent', $lasso_url->ratings[1])) { ?>
							<div class="rating-segment exceptional" data-percent="<?php echo $lasso_url->ratings[1]['percent'] ?>">
								<div class="segment-bar"></div>
								<div class="segment-content">
									<div class="segment-icon">
										<svg viewBox="0 0 24 24" fill="currentColor">
											<path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z" />
										</svg>
									</div>
									<div class="segment-info">
										<span class="segment-label">⭐ Xuất sắc</span>
										<span class="segment-percent"><?php echo $lasso_url->ratings[1]['percent'] ?>%</span>
									</div>
								</div>
							</div>
						<?php } ?>

						<?php if (array_key_exists(2, $lasso_url->ratings) and array_key_exists('percent', $lasso_url->ratings[2])) { ?>
							<div class="rating-segment mixed" data-percent="<?php echo $lasso_url->ratings[2]['percent'] ?>">
								<div class="segment-bar"></div>
								<div class="segment-content">
									<div class="segment-icon">
										<svg viewBox="0 0 24 24" fill="currentColor">
											<path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z" />
										</svg>
									</div>
									<div class="segment-info">
										<span class="segment-label">😐 Trung bình</span>
										<span class="segment-percent"><?php echo $lasso_url->ratings[2]['percent'] ?>%</span>
									</div>
								</div>
							</div>
						<?php } ?>

						<?php if (array_key_exists(3, $lasso_url->ratings) and array_key_exists('percent', $lasso_url->ratings[3])) { ?>
							<div class="rating-segment skip" data-percent="<?php echo $lasso_url->ratings[3]['percent'] ?>">
								<div class="segment-bar"></div>
								<div class="segment-content">
									<div class="segment-icon">
										<svg viewBox="0 0 24 24" fill="currentColor">
											<path d="M7 10l5 5 5-5z" />
											<path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z" />
										</svg>
									</div>
									<div class="segment-info">
										<span class="segment-label">👎 Không khuyên</span>
										<span class="segment-percent"><?php echo $lasso_url->ratings[3]['percent'] ?>%</span>
									</div>
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