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

?>

<div <?php echo $anchor_id_html ?> class="lasso-container">
	<div class="lasso-display <?php echo $theme_name . ' lasso-url-' . $lasso_url->slug . ' ' . $css_display_theme_mobile ?? ''; ?>">
		<div class="container-box-lasso">
			<div class="header-box-lasso">
				<img src="<?php echo $lasso_url->background_image ?>" alt="Game Image" class="game-image">
				<div class="game-info">
					<div class="game-title-area">
						<span class="game-title"><?php echo $lasso_url->name ?></span>
						<div class="rating-title">GR ★ <?php echo $lasso_url->rating ?>/5</div>
					</div>

					<div class="d-flex justify-content-between mt-3">
						<div class="genre">
							<?php foreach ($lasso_url->genres as $genre) { ?>
								<div class="genre-item">
									<?php echo $genre['name'] ?>
								</div>
							<?php } ?>
						</div>
						<div class="systems">
							<?php foreach ($lasso_url->platforms as $platform) { ?>
								<?php if (strpos($platform['platform']['name'], 'PC') !== false and !$isPC) { ?>
									<?php $isPC = true; ?>
									<img class="img-system" src="<?php echo LASSO_PLUGIN_URL . 'admin/assets/images/icons/pc-1.png' ?>" alt="PC">
								<?php } ?>
								<?php if (strpos($platform['platform']['name'], 'Nintendo') !== false and !$isNintendo) { ?>
									<?php $isNintendo = true; ?>
									<img class="img-system" src="<?php echo LASSO_PLUGIN_URL . 'admin/assets/images/icons/nintendo-switch-1.png' ?>" alt="Nintendo">
								<?php } ?>
								<?php if (strpos($platform['platform']['name'], 'PlayStation') !== false and !$isPlaystation) { ?>
									<?php $isPlaystation = true; ?>
									<img class="img-system" src="<?php echo LASSO_PLUGIN_URL . 'admin/assets/images/icons/playstation-1.png' ?>" alt="Playstation">
								<?php } ?>
								<?php if (strpos($platform['platform']['name'], 'Xbox') !== false and !$isXbox) { ?>
									<?php $isXbox = true; ?>
									<img class="img-system" src="<?php echo LASSO_PLUGIN_URL . 'admin/assets/images/icons/xbox-1.png' ?>" alt="Xbox">
								<?php } ?>
							<?php } ?>
						</div>
					</div>
				</div>
			</div>
			<p class="game-description">
				<?php echo $lasso_url->description ?>
			</p>
			<div class="open-critic">
				<p><span>Đánh giá cộng động</span></p>
				<div class="d-flex justify-content-between">
					<div class="open-critic-item">
						<img class="img-critic" src="<?php echo LASSO_PLUGIN_URL . 'admin/assets/images/icons/recommended.png' ?>" alt="Recommended">
						<span class="text-center text-green"><?php echo $lasso_url->ratings[0]['percent'] ?></span>
					</div>

					<div class="open-critic-item">
						<img class="img-critic" src="<?php echo LASSO_PLUGIN_URL . 'admin/assets/images/icons/exceptional.png' ?>" alt="Exceptional">
						<span class="text-center text-yellow"><?php echo $lasso_url->ratings[1]['percent'] ?></span>
					</div>

					<div class="open-critic-item">
						<img class="img-critic" src="<?php echo LASSO_PLUGIN_URL . 'admin/assets/images/icons/meh.png' ?>" alt="Meh">
						<span class="text-center text-grey"><?php echo $lasso_url->ratings[2]['percent'] ?></span>
					</div>

					<div class="open-critic-item">
						<img class="img-critic" src="<?php echo LASSO_PLUGIN_URL . 'admin/assets/images/icons/skip.png' ?>" alt="Skip">
						<span class="text-center text-red"><?php echo $lasso_url->ratings[3]['percent'] ?></span>
					</div>
				</div>
			</div>
			<div class="d-flex justify-content-between mt-3">
				<dl>
					<div>
						<dt>
							<strong>Released</strong>
						</dt>
						<dd>
							<span>
								<?php echo $lasso_url->released ?>
							</span>
						</dd>
					</div>
					<div>
						<dt>
							<strong>Tuổi</strong>
						</dt>
						<dd>
							<span>
								<?php echo $lasso_url->esrb_rating_name ?>
							</span>
						</dd>
					</div>
				</dl>

				<dl>
					<div>
						<dt>
							<strong>Nhà phát triển</strong>
						</dt>
						<dd>
							<?php foreach ($lasso_url->developers as $developer) { ?>
								<span>
									<a href="/tag/<?php echo $developer['name'] ?>/" target="_blank"><?php echo $developer['name'] ?></a>
								</span>
							<?php } ?>
						</dd>
					</div>
					<div>
						<dt>
							<strong>Nhà phát hành</strong>
						</dt>
						<dd>
							<?php foreach ($lasso_url->publishers as $publisher) { ?>
								<span>
									<a href="/tag/<?php echo $publisher['name'] ?>/" target="_blank"><?php echo $publisher['name'] ?></a>
								</span>
							<?php } ?>
						</dd>
					</div>
				</dl>
			</div>

			<div class="footer-box-lasso">
				<div class="owl-carousel owl-theme">
					<?php foreach ($lasso_url->screen_shots as $screen_shot) { ?>
						<div class="item">
							<img src="<?php echo $screen_shot ?>" alt="screen shot">
						</div>
					<?php } ?>
				</div>
			</div>
		</div>
	</div>
</div>