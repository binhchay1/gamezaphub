<?php
/**
 * Create a metabox to added some custom filed in posts.
 *
 * @package CodeVibrant
 * @subpackage News Vibrant
 * @since 1.0.0
 */

add_action( 'add_meta_boxes', 'news_vibrant_post_meta_options' );

if ( ! function_exists( 'news_vibrant_post_meta_options' ) ):
    function  news_vibrant_post_meta_options() {
        add_meta_box(
            'news_vibrant_post_meta',
            esc_html__( 'Post Meta Options', 'news-vibrant' ),
            'news_vibrant_post_meta_callback',
            'post',
            'normal',
            'high'
        );
    }
endif;

$news_vibrant_post_sidebar_options = array(
    'default-sidebar' => array(
            'id'		=> 'post-default-sidebar',
            'value'     => 'default_sidebar',
            'label'     => esc_html__( 'Default Sidebar', 'news-vibrant' ),
            'thumbnail' => get_template_directory_uri() . '/assets/images/default-sidebar.png'
        ), 
    'left-sidebar' => array(
            'id'		=> 'post-right-sidebar',
            'value'     => 'left_sidebar',
            'label'     => esc_html__( 'Left sidebar', 'news-vibrant' ),
            'thumbnail' => get_template_directory_uri() . '/assets/images/left-sidebar.png'
        ), 
    'right-sidebar' => array(
            'id'		=> 'post-left-sidebar',
            'value'     => 'right_sidebar',
            'label'     => esc_html__( 'Right sidebar', 'news-vibrant' ),
            'thumbnail' => get_template_directory_uri() . '/assets/images/right-sidebar.png'
        ),
    'no-sidebar' => array(
            'id'		=> 'post-no-sidebar',
            'value'     => 'no_sidebar',
            'label'     => esc_html__( 'No sidebar Full width', 'news-vibrant' ),
            'thumbnail' => get_template_directory_uri() . '/assets/images/no-sidebar.png'
        ),
    'no-sidebar-center' => array(
            'id'		=> 'post-no-sidebar-center',
            'value'     => 'no_sidebar_center',
            'label'     => esc_html__( 'No sidebar Content Centered', 'news-vibrant' ),
            'thumbnail' => get_template_directory_uri() . '/assets/images/no-sidebar-center.png'
        )
);

/**
 * Callback function for post option
 */
if ( ! function_exists( 'news_vibrant_post_meta_callback' ) ) :
	function news_vibrant_post_meta_callback() {
		global $post, $news_vibrant_post_sidebar_options;

        $get_post_meta_identity = get_post_meta( $post->ID, 'post_meta_identity', true );
        $post_identity_value = empty( $get_post_meta_identity ) ? 'nv-metabox-info' : $get_post_meta_identity;

		wp_nonce_field( basename( __FILE__ ), 'news_vibrant_post_meta_nonce' );
?>
		<div class="nv-meta-container nv-clearfix">
			<ul class="nv-meta-menu-wrapper">
				<li class="nv-meta-tab <?php if ( $post_identity_value == 'nv-metabox-info' ) { echo 'active'; } ?>" data-tab="nv-metabox-info"><span class="dashicons dashicons-clipboard"></span><?php esc_html_e( 'Information', 'news-vibrant' ); ?></li>
				<li class="nv-meta-tab <?php if ( $post_identity_value == 'nv-metabox-sidebar' ) { echo 'active'; } ?>" data-tab="nv-metabox-sidebar"><span class="dashicons dashicons-exerpt-view"></span><?php esc_html_e( 'Sidebars', 'news-vibrant' ); ?></li>
			</ul><!-- .nv-meta-menu-wrapper -->
			<div class="nv-metabox-content-wrapper">
				
				<!-- Info tab content -->
				<div class="nv-single-meta active" id="nv-metabox-info">
					<div class="content-header">
						<h4><?php esc_html_e( 'About Metabox Options', 'news-vibrant' ) ;?></h4>
					</div><!-- .content-header -->
					<div class="meta-options-wrap"><?php esc_html_e( 'In this section we have lots of features which make your post unique and completely different.', 'news-vibrant' ); ?></div><!-- .meta-options-wrap  -->
				</div><!-- #nv-metabox-info -->

				<!-- Sidebar tab content -->
				<div class="nv-single-meta" id="nv-metabox-sidebar">
					<div class="content-header">
						<h4><?php esc_html_e( 'Available Sidebars', 'news-vibrant' ) ;?></h4>
						<span class="section-desc"><em><?php esc_html_e( 'Select sidebar from available options which replaced sidebar layout from customizer settings.', 'news-vibrant' ); ?></em></span>
					</div><!-- .content-header -->
					<div class="nv-meta-options-wrap">
						<div class="buttonset">
							<?php
			                   	foreach ( $news_vibrant_post_sidebar_options as $field ) {
			                    	$news_vibrant_post_sidebar = get_post_meta( $post->ID, 'nv_single_post_sidebar', true );
                                    $news_vibrant_post_sidebar = ( $news_vibrant_post_sidebar ) ? $news_vibrant_post_sidebar : 'default_sidebar';
			                ?>
			                    	<input type="radio" id="<?php echo esc_attr( $field['id'] ); ?>" value="<?php echo esc_attr( $field['value'] ); ?>" name="nv_single_post_sidebar" <?php checked( $field['value'], $news_vibrant_post_sidebar ); ?> />
			                    	<label for="<?php echo esc_attr( $field['id'] ); ?>">
			                    		<span class="screen-reader-text"><?php echo esc_html( $field['label'] ); ?></span>
			                    		<img src="<?php echo esc_url( $field['thumbnail'] ); ?>" title="<?php echo esc_attr( $field['label'] ); ?>" alt="<?php echo esc_attr( $field['label'] ); ?>" />
			                    	</label>
			                    
			                <?php } ?>
						</div><!-- .buttonset -->
					</div><!-- .meta-options-wrap  -->
				</div><!-- #nv-metabox-sidebar -->

            <div class="clear"></div>
            <input type="hidden" id="post-meta-selected" name="post_meta_identity" value="<?php echo esc_attr( $post_identity_value ); ?>" />
		</div><!-- .nv-meta-container -->
<?php
	}
endif;

/*--------------------------------------------------------------------------------------------------------------*/
/**
 * Function for save value of meta options
 *
 * @since 1.0.8
 */
add_action( 'save_post', 'news_vibrant_save_post_meta' );

if ( ! function_exists( 'news_vibrant_save_post_meta' ) ) :

function news_vibrant_save_post_meta( $post_id ) {

    global $post, $nv_allowed_textarea;

    // Verify the nonce before proceeding.
    $news_vibrant_post_nonce   = isset( $_POST['news_vibrant_post_meta_nonce'] ) ? $_POST['news_vibrant_post_meta_nonce'] : '';
    $news_vibrant_post_nonce_action = basename( __FILE__ );

    //* Check if nonce is set...
    if ( ! isset( $news_vibrant_post_nonce ) ) {
        return;
    }

    //* Check if nonce is valid...
    if ( ! wp_verify_nonce( $news_vibrant_post_nonce, $news_vibrant_post_nonce_action ) ) {
        return;
    }

    //* Check if user has permissions to save data...
    if ( ! current_user_can( 'edit_page', $post_id ) ) {
        return;
    }

    //* Check if not an autosave...
    if ( wp_is_post_autosave( $post_id ) ) {
        return;
    }

    //* Check if not a revision...
    if ( wp_is_post_revision( $post_id ) ) {
        return;
    }

    /**
     * Post sidebar
     */
    $post_sidebar = get_post_meta( $post_id, 'nv_single_post_sidebar', true );
    $stz_post_sidebar = sanitize_text_field( $_POST['nv_single_post_sidebar'] );

    if ( $stz_post_sidebar && $stz_post_sidebar != $post_sidebar ) {  
        update_post_meta ( $post_id, 'nv_single_post_sidebar', $stz_post_sidebar );
    } elseif ( '' == $stz_post_sidebar && $post_sidebar ) {  
        delete_post_meta( $post_id,'nv_single_post_sidebar', $post_sidebar );  
    }

    /**
     * post meta identity
     */
    $post_identity = get_post_meta( $post_id, 'post_meta_identity', true );
    $stz_post_identity = sanitize_text_field( $_POST[ 'post_meta_identity' ] );

    if ( $stz_post_identity && '' == $stz_post_identity ){
        add_post_meta( $post_id, 'post_meta_identity', $stz_post_identity );
    }elseif ( $stz_post_identity && $stz_post_identity != $post_identity ) {
        update_post_meta($post_id, 'post_meta_identity', $stz_post_identity );
    } elseif ( '' == $stz_post_identity && $post_identity ) {
        delete_post_meta( $post_id, 'post_meta_identity', $post_identity );
    }
}
endif;