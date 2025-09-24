<?php
/**
 * Index and Archive Main content.
 */
require get_template_directory().'/inc/ansar/hooks/hook-index-main.php';
/**
 * Header Menu section.
 */
require get_template_directory().'/inc/ansar/hooks/hook-header-type-section.php';

/**
 * Header section.
 */
require get_template_directory().'/inc/ansar/hooks/hook-header-section.php';

/**
 * Header section.
 */
require get_template_directory().'/inc/ansar/hooks/hook-footer-section.php';

/**
 * Logo section.
 */
require get_template_directory().'/inc/ansar/hooks/hook-header-logo-section.php';


/**
* Banner additions.
*/
require get_template_directory().'/inc/ansar/hooks/hook-front-page-main-banner-section.php';


/**
* Banner Ad.
*/
require get_template_directory().'/inc/ansar/hooks/hook-front-page-banner-promotions.php';



/**
 *  * Missed Footer Section.
*/
require get_template_directory().'/inc/ansar/hooks/hook-footer-missed-posts.php';


/**
 * Featured Ads section.
 */
require get_template_directory().'/inc/ansar/hooks/hook-featured-ads-section.php';


/**
 * Category Meta Hookes
 */
require get_template_directory().'/inc/ansar/hooks/hook-meta.php';