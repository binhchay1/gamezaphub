<?php

/**
 * Add custom fields to $item nav object
 * in order to be used in custom Walker
 *
 * @access      public
 * @since       1.0 
 * @return      void
*/
function mts_add_custom_nav_fields( $menu_item ) {
    $menu_item->color = get_post_meta( $menu_item->ID, '_menu_item_color', true );
    $menu_item->icon = get_post_meta( $menu_item->ID, '_menu_item_icon', true );
    return $menu_item;
}
// add custom menu fields to menu
add_filter( 'wp_setup_nav_menu_item', 'mts_add_custom_nav_fields' );


/**
 * Save menu custom fields
 *
 * @access      public
 * @since       1.0 
 * @return      void
*/
function mts_update_custom_nav_fields( $menu_id, $menu_item_db_id, $args ) {
    // Check if element is properly sent
    if ( !empty($_REQUEST['menu-item-color']) && is_array( $_REQUEST['menu-item-color']) ) {
        $color_value = $_REQUEST['menu-item-color'][$menu_item_db_id];
        update_post_meta( $menu_item_db_id, '_menu_item_color', $color_value );
    }
    if ( !empty($_REQUEST['menu-item-icon']) && is_array( $_REQUEST['menu-item-icon']) ) {
        $icon_value = $_REQUEST['menu-item-icon'][$menu_item_db_id];
        update_post_meta( $menu_item_db_id, '_menu_item_icon', $icon_value );
    }
}
// save menu custom fields
add_action( 'wp_update_nav_menu_item','mts_update_custom_nav_fields', 10, 3 );

/**
 * Define new Walker edit
 *
 * @access      public
 * @since       1.0 
 * @return      void
*/
function mts_edit_walker($walker,$menu_id) {
    return 'Walker_Nav_Menu_Edit_Custom';
}
// edit menu walker
add_filter( 'wp_edit_nav_menu_walker', 'mts_edit_walker', 10, 2 );

/**
 *  /!\ This is a copy of Walker_Nav_Menu_Edit class in core
 * 
 * Create HTML list of nav menu input items.
 *
 * @package WordPress
 * @since 3.0.0
 * @uses Walker_Nav_Menu
 */
class Walker_Nav_Menu_Edit_Custom extends Walker_Nav_Menu  {
	/**
	 * @see Walker_Nav_Menu::start_lvl()
	 * @since 3.0.0
	 *
	 * @param string $output Passed by reference.
	 */
	function start_lvl(&$output, $depth = 0, $args = array()) {	
	}
	
	/**
	 * @see Walker_Nav_Menu::end_lvl()
	 * @since 3.0.0
	 *
	 * @param string $output Passed by reference.
	 */
	function end_lvl(&$output, $depth = 0, $args = array()) {
	}
	
    public $mts_icons = array(
            'Miscellaneous Icons' => array(
                'adjust', 'anchor', 'archive', 'arrows', 'arrows-h', 'arrows-v', 'asterisk', 'ban', 'bar-chart-o', 'barcode', 'bars', 'beer', 'bell', 'bell-o', 'bolt', 'book', 'bookmark', 'bookmark-o', 'briefcase', 'bug', 'building-o', 'bullhorn', 'bullseye', 'calendar', 'calendar-o', 'camera', 'camera-retro', 'caret-square-o-down', 'caret-square-o-left', 'caret-square-o-right', 'caret-square-o-up', 'certificate', 'check', 'check-circle', 'check-circle-o', 'check-square', 'check-square-o', 'circle', 'circle-o', 'clock-o', 'cloud', 'cloud-download', 'cloud-upload', 'code', 'code-fork', 'coffee', 'cog', 'cogs', 'comment', 'comment-o', 'comments', 'comments-o', 'compass', 'credit-card', 'crop', 'crosshairs', 'cutlery', 'desktop', 'dot-circle-o', 'download', 'ellipsis-h', 'ellipsis-v', 'envelope', 'envelope-o', 'eraser', 'exchange', 'exclamation', 'exclamation-circle', 'exclamation-triangle', 'external-link', 'external-link-square', 'eye', 'eye-slash', 'female', 'fighter-jet', 'film', 'filter', 'fire', 'fire-extinguisher', 'flag', 'flag-checkered', 'flag-o', 'flask', 'folder', 'folder-o', 'folder-open', 'folder-open-o', 'frown-o', 'gamepad', 'gavel', 'gift', 'glass', 'globe', 'hdd-o', 'headphones', 'heart', 'heart-o', 'home', 'inbox', 'info', 'info-circle', 'key', 'keyboard-o', 'laptop', 'leaf', 'lemon-o', 'level-down', 'level-up', 'lightbulb-o', 'location-arrow', 'lock', 'magic', 'magnet', 'mail-reply-all', 'male', 'map-marker', 'meh-o', 'microphone', 'microphone-slash', 'minus', 'minus-circle', 'minus-square', 'minus-square-o', 'mobile', 'money', 'moon-o', 'music', 'pencil', 'pencil-square', 'pencil-square-o', 'phone', 'phone-square', 'picture-o', 'plane', 'plus', 'plus-circle', 'plus-square', 'plus-square-o', 'power-off', 'print', 'puzzle-piece', 'qrcode', 'question', 'question-circle', 'quote-left', 'quote-right', 'random', 'refresh', 'reply', 'reply-all', 'retweet', 'road', 'rocket', 'rss', 'rss-square', 'search', 'search-minus', 'search-plus', 'share', 'share-square', 'share-square-o', 'shield', 'shopping-cart', 'sign-in', 'sign-out', 'signal', 'sitemap', 'smile-o', 'sort', 'sort-alpha-asc', 'sort-alpha-desc', 'sort-amount-asc', 'sort-amount-desc', 'sort-asc', 'sort-desc', 'sort-numeric-asc', 'sort-numeric-desc', 'spinner', 'square', 'square-o', 'star', 'star-half', 'star-half-o', 'star-o', 'subscript', 'suitcase', 'sun-o', 'superscript', 'tablet', 'tachometer', 'tag', 'tags', 'tasks', 'terminal', 'thumb-tack', 'thumbs-down', 'thumbs-o-down', 'thumbs-o-up', 'thumbs-up', 'ticket', 'times', 'times-circle', 'times-circle-o', 'tint', 'trash-o', 'trophy', 'truck', 'umbrella', 'unlock', 'unlock-alt', 'upload', 'user', 'users', 'video-camera', 'volume-down', 'volume-off', 'volume-up', 'wheelchair', 'wrench'
            ),
            'Form Control Icons' => array(
                'check-square', 'check-square-o', 'circle', 'circle-o', 'dot-circle-o', 'minus-square', 'minus-square-o', 'plus-square', 'plus-square-o', 'square', 'square-o'
            ),
            'Currency Icons' => array(
                'btc', 'eur', 'gbp', 'inr', 'jpy', 'krw', 'money', 'rub', 'try', 'usd'
            ),
            'Text Editor Icons' => array(
                'align-center', 'align-justify', 'align-left', 'align-right', 'bold', 'chain-broken', 'clipboard', 'columns', 'eraser', 'file', 'file-o', 'file-text', 'file-text-o', 'files-o', 'floppy-o', 'font', 'indent', 'italic', 'link', 'list', 'list-alt', 'list-ol', 'list-ul', 'outdent', 'paperclip', 'repeat', 'scissors', 'strikethrough', 'table', 'text-height', 'text-width', 'th', 'th-large', 'th-list', 'underline', 'undo'
            ),
            'Directional Icons' => array(
                'angle-double-down', 'angle-double-left', 'angle-double-right', 'angle-double-up', 'angle-down', 'angle-left', 'angle-right', 'angle-up', 'arrow-circle-down', 'arrow-circle-left', 'arrow-circle-o-down', 'arrow-circle-o-left', 'arrow-circle-o-right', 'arrow-circle-o-up', 'arrow-circle-right', 'arrow-circle-up', 'arrow-down', 'arrow-left', 'arrow-right', 'arrow-up', 'arrows', 'arrows-alt', 'arrows-h', 'arrows-v', 'caret-down', 'caret-left', 'caret-right', 'caret-square-o-down', 'caret-square-o-left', 'caret-square-o-right', 'caret-square-o-up', 'caret-up', 'chevron-circle-down', 'chevron-circle-left', 'chevron-circle-right', 'chevron-circle-up', 'chevron-down', 'chevron-left', 'chevron-right', 'chevron-up', 'hand-o-down', 'hand-o-left', 'hand-o-right', 'hand-o-up', 'long-arrow-down', 'long-arrow-left', 'long-arrow-right', 'long-arrow-up'
            ),
            'Video Player Icons' => array(
                'arrows-alt', 'backward', 'compress', 'eject', 'expand', 'fast-backward', 'fast-forward', 'forward', 'pause', 'play', 'play-circle', 'play-circle-o', 'step-backward', 'step-forward', 'stop', 'youtube-play'
            ),
            'Brand Icons' => array(
                'adn', 'android', 'apple', 'bitbucket', 'bitbucket-square', 'btc', 'css3', 'dribbble', 'dropbox', 'facebook', 'facebook-square', 'flickr', 'foursquare', 'github', 'github-alt', 'github-square', 'gittip', 'google-plus', 'google-plus-square', 'html5', 'instagram', 'linkedin', 'linkedin-square', 'linux', 'maxcdn', 'pagelines', 'pinterest', 'pinterest-square', 'renren', 'skype', 'stack-exchange', 'stack-overflow', 'trello', 'tumblr', 'tumblr-square', 'twitter', 'twitter-square', 'vimeo-square', 'vk', 'weibo', 'windows', 'xing', 'xing-square', 'youtube', 'youtube-play', 'youtube-square'
            ),
            'Medical Icons' => array(
                'ambulance', 'h-square', 'hospital-o', 'medkit', 'plus-square', 'stethoscope', 'user-md', 'wheelchair'
            ),
        );
    
	/**
	 * @see Walker::start_el()
	 * @since 3.0.0
	 *
	 * @param string $output Passed by reference. Used to append additional content.
	 * @param object $item Menu item data object.
	 * @param int $depth Depth of menu item. Used for padding.
	 * @param object $args
	 */
	function start_el(&$output, $item, $depth = 0, $args = array(), $id = 0) {
	    global $_wp_nav_menu_max_depth;
	   
	    $_wp_nav_menu_max_depth = $depth > $_wp_nav_menu_max_depth ? $depth : $_wp_nav_menu_max_depth;
	
	    $indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';
	
	    ob_start();
	    $item_id = esc_attr( $item->ID );
	    $removed_args = array(
	        'action',
	        'customlink-tab',
	        'edit-menu-item',
	        'menu-item',
	        'page-tab',
	        '_wpnonce',
	    );
	
	    $original_title = '';
	    if ( 'taxonomy' == $item->type ) {
	        $original_title = get_term_field( 'name', $item->object_id, $item->object, 'raw' );
	        if ( is_wp_error( $original_title ) )
	            $original_title = false;
	    } elseif ( 'post_type' == $item->type ) {
	        $original_object = get_post( $item->object_id );
	        $original_title = $original_object->post_title;
	    }
	
	    $classes = array(
	        'menu-item menu-item-depth-' . $depth,
	        'menu-item-' . esc_attr( $item->object ),
	        'menu-item-edit-' . ( ( isset( $_GET['edit-menu-item'] ) && $item_id == $_GET['edit-menu-item'] ) ? 'active' : 'inactive'),
	    );
	
	    $title = $item->title;
	
	    if ( ! empty( $item->_invalid ) ) {
	        $classes[] = 'menu-item-invalid';
	        /* translators: %s: title of menu item which is invalid */
	        $title = sprintf( __( '%s (Invalid)' ), $item->title );
	    } elseif ( isset( $item->post_status ) && 'draft' == $item->post_status ) {
	        $classes[] = 'pending';
	        /* translators: %s: title of menu item in draft status */
	        $title = sprintf( __('%s (Pending)'), $item->title );
	    }
	
	    $title = empty( $item->label ) ? $title : $item->label;
	
	    ?>
	    <li id="menu-item-<?php echo $item_id; ?>" class="<?php echo implode(' ', $classes ); ?>">
	        <dl class="menu-item-bar">
	            <dt class="menu-item-handle">
	                <span class="item-title"><?php echo esc_html( $title ); ?></span>
	                <span class="item-controls">
	                    <span class="item-type"><?php echo esc_html( $item->type_label ); ?></span>
	                    <span class="item-order hide-if-js">
	                        <a href="<?php
	                            echo wp_nonce_url(
	                                add_query_arg(
	                                    array(
	                                        'action' => 'move-up-menu-item',
	                                        'menu-item' => $item_id,
	                                    ),
	                                    remove_query_arg($removed_args, admin_url( 'nav-menus.php' ) )
	                                ),
	                                'move-menu_item'
	                            );
	                        ?>" class="item-move-up"><abbr title="<?php esc_attr_e('Move up'); ?>">&#8593;</abbr></a>
	                        |
	                        <a href="<?php
	                            echo wp_nonce_url(
	                                add_query_arg(
	                                    array(
	                                        'action' => 'move-down-menu-item',
	                                        'menu-item' => $item_id,
	                                    ),
	                                    remove_query_arg($removed_args, admin_url( 'nav-menus.php' ) )
	                                ),
	                                'move-menu_item'
	                            );
	                        ?>" class="item-move-down"><abbr title="<?php esc_attr_e('Move down'); ?>">&#8595;</abbr></a>
	                    </span>
	                    <a class="item-edit" id="edit-<?php echo $item_id; ?>" title="<?php esc_attr_e('Edit Menu Item'); ?>" href="<?php
	                        echo ( isset( $_GET['edit-menu-item'] ) && $item_id == $_GET['edit-menu-item'] ) ? admin_url( 'nav-menus.php' ) : add_query_arg( 'edit-menu-item', $item_id, remove_query_arg( $removed_args, admin_url( 'nav-menus.php#menu-item-settings-' . $item_id ) ) );
	                    ?>"><?php _e( 'Edit Menu Item','mythemeshop' ); ?></a>
	                </span>
	            </dt>
	        </dl>
	
	        <div class="menu-item-settings" id="menu-item-settings-<?php echo $item_id; ?>">
	            <?php if( 'custom' == $item->type ) : ?>
	                <p class="field-url description description-wide">
	                    <label for="edit-menu-item-url-<?php echo $item_id; ?>">
	                        <?php _e( 'URL','mythemeshop' ); ?><br />
	                        <input type="text" id="edit-menu-item-url-<?php echo $item_id; ?>" class="widefat code edit-menu-item-url" name="menu-item-url[<?php echo $item_id; ?>]" value="<?php echo esc_attr( $item->url ); ?>" />
	                    </label>
	                </p>
	            <?php endif; ?>
	            <p class="description description-thin">
	                <label for="edit-menu-item-title-<?php echo $item_id; ?>">
	                    <?php _e( 'Navigation Label','mythemeshop' ); ?><br />
	                    <input type="text" id="edit-menu-item-title-<?php echo $item_id; ?>" class="widefat edit-menu-item-title" name="menu-item-title[<?php echo $item_id; ?>]" value="<?php echo esc_attr( $item->title ); ?>" />
	                </label>
	            </p>
	            <p class="description description-thin">
	                <label for="edit-menu-item-attr-title-<?php echo $item_id; ?>">
	                    <?php _e( 'Title Attribute','mythemeshop' ); ?><br />
	                    <input type="text" id="edit-menu-item-attr-title-<?php echo $item_id; ?>" class="widefat edit-menu-item-attr-title" name="menu-item-attr-title[<?php echo $item_id; ?>]" value="<?php echo esc_attr( $item->post_excerpt ); ?>" />
	                </label>
	            </p>
	            <p class="field-link-target description">
	                <label for="edit-menu-item-target-<?php echo $item_id; ?>">
	                    <input type="checkbox" id="edit-menu-item-target-<?php echo $item_id; ?>" value="_blank" name="menu-item-target[<?php echo $item_id; ?>]"<?php checked( $item->target, '_blank' ); ?> />
	                    <?php _e( 'Open link in a new window/tab','mythemeshop' ); ?>
	                </label>
	            </p>
	            <p class="field-css-classes description description-thin">
	                <label for="edit-menu-item-classes-<?php echo $item_id; ?>">
	                    <?php _e( 'CSS Classes (optional)','mythemeshop' ); ?><br />
	                    <input type="text" id="edit-menu-item-classes-<?php echo $item_id; ?>" class="widefat code edit-menu-item-classes" name="menu-item-classes[<?php echo $item_id; ?>]" value="<?php echo esc_attr( implode(' ', $item->classes ) ); ?>" />
	                </label>
	            </p>
	            <p class="field-xfn description description-thin">
	                <label for="edit-menu-item-xfn-<?php echo $item_id; ?>">
	                    <?php _e( 'Link Relationship (XFN)','mythemeshop' ); ?><br />
	                    <input type="text" id="edit-menu-item-xfn-<?php echo $item_id; ?>" class="widefat code edit-menu-item-xfn" name="menu-item-xfn[<?php echo $item_id; ?>]" value="<?php echo esc_attr( $item->xfn ); ?>" />
	                </label>
	            </p>
	            <p class="field-description description description-wide">
	                <label for="edit-menu-item-description-<?php echo $item_id; ?>">
	                    <?php _e( 'Description','mythemeshop' ); ?><br />
	                    <textarea id="edit-menu-item-description-<?php echo $item_id; ?>" class="widefat edit-menu-item-description" rows="3" cols="20" name="menu-item-description[<?php echo $item_id; ?>]"><?php echo esc_html( $item->description ); // textarea_escaped ?></textarea>
	                    <span class="description"><?php _e('The description will be displayed in the menu if the current theme supports it.','mythemeshop'); ?></span>
	                </label>
	            </p>        
	            <?php
	            /* New fields insertion starts here */
	            ?>
                
                <p class="field-custom description description-thin">
	                <label for="edit-menu-item-icon-<?php echo $item_id; ?>">
	                    <?php _e( 'Icon (optional)','mythemeshop' ); ?><br />
	                    <?php 
                        echo '<select id="edit-menu-item-icon-'.$item_id.'" name="menu-item-icon['.$item_id.']" style="width: 100%; max-width: 240px;">';
                		echo '<option value=""'.selected($item->icon, '', false).'>'.__('No Icon').'</option>';
                        foreach ( $this->mts_icons as $icon_category => $icons ) {
                            echo '<optgroup label="'.$icon_category.'">';
                            foreach ($icons as $icon) {
                                echo '<option value="'.$icon.'"'.selected($item->icon, $icon, false).'>'.ucwords(str_replace('-', ' ', $icon)).'</option>';
                            }
                            echo '</optgroup>';
                		}
                
                		echo '</select>';
                        ?>
	                </label>
	            </p>
                
	            <p class="field-custom description description-thin">
	                <label for="edit-menu-item-color-<?php echo $item_id; ?>" style="position:relative;">
	                    <?php _e( 'Color (optional)','mythemeshop' ); ?><br />
	                    <input type="text" id="edit-menu-item-color-<?php echo $item_id; ?>" class="widefat code edit-menu-item-custom edit-menu-color" name="menu-item-color[<?php echo $item_id; ?>]" value="<?php echo esc_attr( $item->color ); ?>" />
	                </label>
	            </p>
                
	            <?php
	            /* New fields insertion ends here */
	            ?>
	            <div class="menu-item-actions description-wide submitbox">
	                <?php if( 'custom' != $item->type && $original_title !== false ) : ?>
	                    <p class="link-to-original">
	                        <?php printf( __('Original: %s'), '<a href="' . esc_attr( $item->url ) . '">' . esc_html( $original_title ) . '</a>' ); ?>
	                    </p>
	                <?php endif; ?>
	                <a class="item-delete submitdelete deletion" id="delete-<?php echo $item_id; ?>" href="<?php
	                echo wp_nonce_url(
	                    add_query_arg(
	                        array(
	                            'action' => 'delete-menu-item',
	                            'menu-item' => $item_id,
	                        ),
	                        remove_query_arg($removed_args, admin_url( 'nav-menus.php' ) )
	                    ),
	                    'delete-menu_item_' . $item_id
	                ); ?>"><?php _e('Remove','mythemeshop'); ?></a> <span class="meta-sep"> | </span> <a class="item-cancel submitcancel" id="cancel-<?php echo $item_id; ?>" href="<?php echo esc_url( add_query_arg( array('edit-menu-item' => $item_id, 'cancel' => time()), remove_query_arg( $removed_args, admin_url( 'nav-menus.php' ) ) ) );
	                    ?>#menu-item-settings-<?php echo $item_id; ?>"><?php _e('Cancel','mythemeshop'); ?></a>
	            </div>
	
	            <input class="menu-item-data-db-id" type="hidden" name="menu-item-db-id[<?php echo $item_id; ?>]" value="<?php echo $item_id; ?>" />
	            <input class="menu-item-data-object-id" type="hidden" name="menu-item-object-id[<?php echo $item_id; ?>]" value="<?php echo esc_attr( $item->object_id ); ?>" />
	            <input class="menu-item-data-object" type="hidden" name="menu-item-object[<?php echo $item_id; ?>]" value="<?php echo esc_attr( $item->object ); ?>" />
	            <input class="menu-item-data-parent-id" type="hidden" name="menu-item-parent-id[<?php echo $item_id; ?>]" value="<?php echo esc_attr( $item->menu_item_parent ); ?>" />
	            <input class="menu-item-data-position" type="hidden" name="menu-item-position[<?php echo $item_id; ?>]" value="<?php echo esc_attr( $item->menu_order ); ?>" />
	            <input class="menu-item-data-type" type="hidden" name="menu-item-type[<?php echo $item_id; ?>]" value="<?php echo esc_attr( $item->type ); ?>" />
                
                <script type="text/javascript">
                jQuery(document).ready(function($) {
                    $('#edit-menu-item-icon-<?php echo $item_id; ?>').select2({
                        formatResult: function(state) {
                            if (!state.id) return state.text; // optgroup
                            return '<i class="fa fa-' + state.id + '"></i>&nbsp;&nbsp;' + state.text;
                        },
                        formatSelection: function(state) {
                            if (!state.id) return state.text; // optgroup
                            return '<i class="fa fa-' + state.id + '"></i>&nbsp;&nbsp;' + state.text;
                        },
                        escapeMarkup: function(m) { return m; }
                    });
                    $('#edit-menu-item-color-<?php echo $item_id; ?>').wpColorPicker();
                });
                </script>
                
            </div><!-- .menu-item-settings-->
	        <ul class="menu-item-transport"></ul>
	    <?php
	    
	    $output .= ob_get_clean();

	    }
}


/**
 * Custom Walker
 *
 * @access      public
 * @since       1.0 
 * @return      void
*/
class mts_menu_walker extends Walker_Nav_Menu
{
      function start_el(&$output, $item, $depth = 0, $args = array(), $id = 0)
      {
           global $wp_query;
           $indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';

           $class_names = $value = '';

           $classes = empty( $item->classes ) ? array() : (array) $item->classes;

           $class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item ) );
           $class_names = ' class="'. esc_attr( $class_names ) . '"';

           $output .= $indent . '<li id="menu-item-'. $item->ID . '"' . $value . $class_names .'>';

           $attributes  = ! empty( $item->attr_title ) ? ' title="'  . esc_attr( $item->attr_title ) .'"' : '';
           $attributes .= ! empty( $item->target )     ? ' target="' . esc_attr( $item->target     ) .'"' : '';
           $attributes .= ! empty( $item->xfn )        ? ' rel="'    . esc_attr( $item->xfn        ) .'"' : '';
           $attributes .= ! empty( $item->url )        ? ' href="'   . esc_attr( $item->url        ) .'"' : '';

           $prepend = '';
           $append = '';
           $description  = ! empty( $item->description ) ? '<span>'.esc_attr( $item->description ).'</span>' : '';

           if($depth != 0)
           {
	           $description = $append = $prepend = "";
           }

            $item_output = $args->before;
            $item_output .= '<a'. $attributes;
            // Color
            if (!empty($item->color)) 
                $item_output .= ' style="color: '.$item->color.';"';
            $item_output .= '>';
            // Icon
            if (!empty($item->icon)) {
                $item_output .= '<i class="fa fa-'.$item->icon.'"></i> ';
            }
            $item_output .= $args->link_before .$prepend.apply_filters( 'the_title', $item->title, $item->ID ).$append;
            //$item_output .= $description;
            $item_output .= $args->link_after;
            $item_output .= '</a>';
            $item_output .= $args->after;
            
            $output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
            }
}

// enqueue iris color picker
add_action('admin_enqueue_scripts', 'mts_custom_menu_admin_scripts');
function mts_custom_menu_admin_scripts($hook) {
    if ($hook != 'nav-menus.php')
        return;
        
    wp_enqueue_script('wp-color-picker');
    wp_enqueue_style('wp-color-picker');
    
    wp_enqueue_script(
		'select2', 
		get_template_directory_uri().'/options/js/select2.min.js', 
		array('jquery'),
		null,
		true
	);
    wp_enqueue_style(
		'select2', 
		get_template_directory_uri().'/options/css/select2.css', 
		array(),
		null,
		'all'
	);
    wp_enqueue_style(
		'font-awesome', 
		get_template_directory_uri().'/css/font-awesome.min.css',
		array(),
		null,
		'all'
	);
}

?>