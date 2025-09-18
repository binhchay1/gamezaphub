<?php
/**
 * Accessibility Improvements for Yosemite Theme
 * 
 * @package Yosemite
 * @version 1.3.1
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Accessibility Enhancements
 */
class MTS_Accessibility_Improvements {
    
    /**
     * Initialize accessibility improvements
     */
    public static function init() {
        self::add_skip_links();
        self::improve_navigation();
        self::enhance_forms();
        self::add_aria_labels();
        self::improve_keyboard_navigation();
        self::add_high_contrast_mode();
        self::improve_screen_reader_support();
    }
    
    /**
     * Add skip links
     */
    public static function add_skip_links() {
        add_action('wp_body_open', array(__CLASS__, 'output_skip_links'));
    }
    
    /**
     * Output skip links
     */
    public static function output_skip_links() {
        ?>
        <a class="skip-link screen-reader-text" href="#main"><?php _e('Skip to main content', 'mythemeshop'); ?></a>
        <a class="skip-link screen-reader-text" href="#navigation"><?php _e('Skip to navigation', 'mythemeshop'); ?></a>
        <a class="skip-link screen-reader-text" href="#sidebar"><?php _e('Skip to sidebar', 'mythemeshop'); ?></a>
        <?php
    }
    
    /**
     * Improve navigation
     */
    public static function improve_navigation() {
        // Add ARIA labels to navigation
        add_filter('wp_nav_menu_args', array(__CLASS__, 'add_navigation_aria_labels'));
        
        // Improve mobile menu
        add_action('wp_footer', array(__CLASS__, 'improve_mobile_menu'));
    }
    
    /**
     * Add navigation ARIA labels
     */
    public static function add_navigation_aria_labels($args) {
        $args['container_aria_label'] = __('Primary navigation', 'mythemeshop');
        $args['menu_aria_label'] = __('Main menu', 'mythemeshop');
        
        return $args;
    }
    
    /**
     * Improve mobile menu
     */
    public static function improve_mobile_menu() {
        ?>
        <script>
        document.addEventListener('DOMContentLoaded', function() {
            const mobileMenuToggle = document.querySelector('.toggle-mobile-menu');
            const mobileMenu = document.querySelector('#navigation .menu');
            
            if (mobileMenuToggle && mobileMenu) {
                // Add ARIA attributes
                mobileMenuToggle.setAttribute('aria-expanded', 'false');
                mobileMenuToggle.setAttribute('aria-controls', 'mobile-menu');
                mobileMenu.setAttribute('id', 'mobile-menu');
                mobileMenu.setAttribute('aria-label', '<?php _e('Mobile navigation menu', 'mythemeshop'); ?>');
                
                // Handle keyboard navigation
                mobileMenuToggle.addEventListener('keydown', function(e) {
                    if (e.key === 'Enter' || e.key === ' ') {
                        e.preventDefault();
                        toggleMobileMenu();
                    }
                });
                
                // Handle menu toggle
                mobileMenuToggle.addEventListener('click', function(e) {
                    e.preventDefault();
                    toggleMobileMenu();
                });
                
                function toggleMobileMenu() {
                    const isExpanded = mobileMenuToggle.getAttribute('aria-expanded') === 'true';
                    mobileMenuToggle.setAttribute('aria-expanded', !isExpanded);
                    mobileMenu.classList.toggle('mobile-menu-open');
                    
                    // Focus management
                    if (!isExpanded) {
                        const firstMenuItem = mobileMenu.querySelector('a');
                        if (firstMenuItem) {
                            firstMenuItem.focus();
                        }
                    }
                }
                
                // Close menu when clicking outside
                document.addEventListener('click', function(e) {
                    if (!mobileMenuToggle.contains(e.target) && !mobileMenu.contains(e.target)) {
                        mobileMenuToggle.setAttribute('aria-expanded', 'false');
                        mobileMenu.classList.remove('mobile-menu-open');
                    }
                });
                
                // Close menu on escape key
                document.addEventListener('keydown', function(e) {
                    if (e.key === 'Escape' && mobileMenu.classList.contains('mobile-menu-open')) {
                        mobileMenuToggle.setAttribute('aria-expanded', 'false');
                        mobileMenu.classList.remove('mobile-menu-open');
                        mobileMenuToggle.focus();
                    }
                });
            }
        });
        </script>
        <?php
    }
    
    /**
     * Enhance forms
     */
    public static function enhance_forms() {
        // Add ARIA labels to search form
        add_filter('get_search_form', array(__CLASS__, 'enhance_search_form'));
        
        // Improve comment form
        add_filter('comment_form_defaults', array(__CLASS__, 'enhance_comment_form'));
        
        // Add form validation
        add_action('wp_footer', array(__CLASS__, 'add_form_validation'));
    }
    
    /**
     * Enhance search form
     */
    public static function enhance_search_form($form) {
        $form = str_replace(
            '<input type="search"',
            '<input type="search" aria-label="' . __('Search', 'mythemeshop') . '"',
            $form
        );
        
        $form = str_replace(
            '<input type="submit"',
            '<input type="submit" aria-label="' . __('Submit search', 'mythemeshop') . '"',
            $form
        );
        
        return $form;
    }
    
    /**
     * Enhance comment form
     */
    public static function enhance_comment_form($defaults) {
        $defaults['comment_field'] = str_replace(
            '<textarea',
            '<textarea aria-label="' . __('Comment', 'mythemeshop') . '"',
            $defaults['comment_field']
        );
        
        $defaults['fields']['author'] = str_replace(
            '<input',
            '<input aria-label="' . __('Your name', 'mythemeshop') . '"',
            $defaults['fields']['author']
        );
        
        $defaults['fields']['email'] = str_replace(
            '<input',
            '<input aria-label="' . __('Your email', 'mythemeshop') . '"',
            $defaults['fields']['email']
        );
        
        $defaults['fields']['url'] = str_replace(
            '<input',
            '<input aria-label="' . __('Your website', 'mythemeshop') . '"',
            $defaults['fields']['url']
        );
        
        return $defaults;
    }
    
    /**
     * Add form validation
     */
    public static function add_form_validation() {
        ?>
        <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Add form validation
            const forms = document.querySelectorAll('form');
            
            forms.forEach(function(form) {
                form.addEventListener('submit', function(e) {
                    const requiredFields = form.querySelectorAll('[required]');
                    let hasErrors = false;
                    
                    requiredFields.forEach(function(field) {
                        if (!field.value.trim()) {
                            hasErrors = true;
                            field.setAttribute('aria-invalid', 'true');
                            
                            // Add error message
                            let errorMessage = field.parentNode.querySelector('.error-message');
                            if (!errorMessage) {
                                errorMessage = document.createElement('span');
                                errorMessage.className = 'error-message';
                                errorMessage.textContent = '<?php _e('This field is required', 'mythemeshop'); ?>';
                                field.parentNode.appendChild(errorMessage);
                            }
                        } else {
                            field.setAttribute('aria-invalid', 'false');
                            const errorMessage = field.parentNode.querySelector('.error-message');
                            if (errorMessage) {
                                errorMessage.remove();
                            }
                        }
                    });
                    
                    if (hasErrors) {
                        e.preventDefault();
                        
                        // Focus first error field
                        const firstError = form.querySelector('[aria-invalid="true"]');
                        if (firstError) {
                            firstError.focus();
                        }
                    }
                });
            });
        });
        </script>
        <?php
    }
    
    /**
     * Add ARIA labels
     */
    public static function add_aria_labels() {
        // Add ARIA labels to images
        add_filter('wp_get_attachment_image_attributes', array(__CLASS__, 'add_image_aria_labels'), 10, 3);
        
        // Add ARIA labels to links
        add_filter('the_content', array(__CLASS__, 'add_link_aria_labels'));
        
        // Add ARIA labels to buttons
        add_action('wp_footer', array(__CLASS__, 'add_button_aria_labels'));
    }
    
    /**
     * Add image ARIA labels
     */
    public static function add_image_aria_labels($attributes, $attachment, $size) {
        if (empty($attributes['alt'])) {
            $attributes['alt'] = get_post_meta($attachment->ID, '_wp_attachment_image_alt', true);
        }
        
        if (empty($attributes['alt'])) {
            $attributes['alt'] = get_the_title($attachment->ID);
        }
        
        return $attributes;
    }
    
    /**
     * Add link ARIA labels
     */
    public static function add_link_aria_labels($content) {
        // Add ARIA labels to external links
        $content = preg_replace_callback(
            '/<a([^>]*?)href=["\'](https?:\/\/[^"\']*?)["\']([^>]*?)>(.*?)<\/a>/i',
            function($matches) {
                $attributes = $matches[1] . $matches[3];
                $url = $matches[2];
                $text = $matches[4];
                
                // Check if it's an external link
                if (strpos($url, home_url()) === false) {
                    $attributes .= ' aria-label="' . esc_attr($text . ' ' . __('(opens in new tab)', 'mythemeshop')) . '"';
                }
                
                return '<a' . $attributes . 'href="' . esc_url($url) . '">' . $text . '</a>';
            },
            $content
        );
        
        return $content;
    }
    
    /**
     * Add button ARIA labels
     */
    public static function add_button_aria_labels() {
        ?>
        <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Add ARIA labels to buttons without text
            const buttons = document.querySelectorAll('button:not([aria-label]):not([aria-labelledby])');
            
            buttons.forEach(function(button) {
                if (!button.textContent.trim()) {
                    const icon = button.querySelector('i, .fa, .icon');
                    if (icon) {
                        const iconClass = icon.className;
                        let label = '';
                        
                        if (iconClass.includes('search')) {
                            label = '<?php _e('Search', 'mythemeshop'); ?>';
                        } else if (iconClass.includes('menu')) {
                            label = '<?php _e('Menu', 'mythemeshop'); ?>';
                        } else if (iconClass.includes('close')) {
                            label = '<?php _e('Close', 'mythemeshop'); ?>';
                        } else if (iconClass.includes('play')) {
                            label = '<?php _e('Play', 'mythemeshop'); ?>';
                        } else if (iconClass.includes('pause')) {
                            label = '<?php _e('Pause', 'mythemeshop'); ?>';
                        }
                        
                        if (label) {
                            button.setAttribute('aria-label', label);
                        }
                    }
                }
            });
        });
        </script>
        <?php
    }
    
    /**
     * Improve keyboard navigation
     */
    public static function improve_keyboard_navigation() {
        add_action('wp_footer', array(__CLASS__, 'add_keyboard_navigation'));
    }
    
    /**
     * Add keyboard navigation
     */
    public static function add_keyboard_navigation() {
        ?>
        <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Add keyboard navigation for galleries
            const galleries = document.querySelectorAll('.custom-gallery-container');
            
            galleries.forEach(function(gallery) {
                const images = gallery.querySelectorAll('.thumbnail-image');
                const mainImage = gallery.querySelector('.main-gallery-image');
                
                images.forEach(function(image, index) {
                    image.setAttribute('tabindex', '0');
                    image.setAttribute('role', 'button');
                    image.setAttribute('aria-label', '<?php _e('View image', 'mythemeshop'); ?> ' + (index + 1));
                    
                    image.addEventListener('keydown', function(e) {
                        if (e.key === 'Enter' || e.key === ' ') {
                            e.preventDefault();
                            image.click();
                        }
                    });
                });
                
                // Add keyboard navigation for main image
                if (mainImage) {
                    mainImage.setAttribute('tabindex', '0');
                    mainImage.setAttribute('role', 'button');
                    mainImage.setAttribute('aria-label', '<?php _e('View full size image', 'mythemeshop'); ?>');
                    
                    mainImage.addEventListener('keydown', function(e) {
                        if (e.key === 'Enter' || e.key === ' ') {
                            e.preventDefault();
                            const zoomBtn = gallery.querySelector('.zoom-btn');
                            if (zoomBtn) {
                                zoomBtn.click();
                            }
                        }
                    });
                }
            });
            
            // Add keyboard navigation for video player
            const videoPlayers = document.querySelectorAll('.video-player');
            
            videoPlayers.forEach(function(player) {
                const playPauseBtn = player.querySelector('.play-pause');
                const volumeBtn = player.querySelector('.volume-btn');
                const progressBar = player.querySelector('.progress-bar');
                
                if (playPauseBtn) {
                    playPauseBtn.setAttribute('tabindex', '0');
                    playPauseBtn.setAttribute('role', 'button');
                    playPauseBtn.setAttribute('aria-label', '<?php _e('Play/Pause video', 'mythemeshop'); ?>');
                }
                
                if (volumeBtn) {
                    volumeBtn.setAttribute('tabindex', '0');
                    volumeBtn.setAttribute('role', 'button');
                    volumeBtn.setAttribute('aria-label', '<?php _e('Mute/Unmute video', 'mythemeshop'); ?>');
                }
                
                if (progressBar) {
                    progressBar.setAttribute('tabindex', '0');
                    progressBar.setAttribute('role', 'slider');
                    progressBar.setAttribute('aria-label', '<?php _e('Video progress', 'mythemeshop'); ?>');
                }
            });
        });
        </script>
        <?php
    }
    
    /**
     * Add high contrast mode
     */
    public static function add_high_contrast_mode() {
        add_action('wp_head', array(__CLASS__, 'add_high_contrast_styles'));
        add_action('wp_footer', array(__CLASS__, 'add_high_contrast_toggle'));
    }
    
    /**
     * Add high contrast styles
     */
    public static function add_high_contrast_styles() {
        ?>
        <style>
        .high-contrast {
            filter: contrast(150%) brightness(120%);
        }
        
        .high-contrast * {
            background-color: white !important;
            color: black !important;
            border-color: black !important;
        }
        
        .high-contrast a {
            text-decoration: underline !important;
        }
        
        .high-contrast button,
        .high-contrast input,
        .high-contrast textarea,
        .high-contrast select {
            border: 2px solid black !important;
        }
        
        .accessibility-toggle {
            position: fixed;
            top: 10px;
            right: 10px;
            z-index: 9999;
            background: #000;
            color: #fff;
            border: none;
            padding: 10px;
            cursor: pointer;
            border-radius: 5px;
        }
        
        .accessibility-toggle:hover {
            background: #333;
        }
        </style>
        <?php
    }
    
    /**
     * Add high contrast toggle
     */
    public static function add_high_contrast_toggle() {
        ?>
        <button class="accessibility-toggle" id="high-contrast-toggle" aria-label="<?php _e('Toggle high contrast mode', 'mythemeshop'); ?>">
            <?php _e('High Contrast', 'mythemeshop'); ?>
        </button>
        
        <script>
        document.addEventListener('DOMContentLoaded', function() {
            const toggle = document.getElementById('high-contrast-toggle');
            const body = document.body;
            
            // Check for saved preference
            if (localStorage.getItem('high-contrast') === 'true') {
                body.classList.add('high-contrast');
                toggle.setAttribute('aria-pressed', 'true');
            }
            
            toggle.addEventListener('click', function() {
                body.classList.toggle('high-contrast');
                const isActive = body.classList.contains('high-contrast');
                
                toggle.setAttribute('aria-pressed', isActive);
                localStorage.setItem('high-contrast', isActive);
            });
        });
        </script>
        <?php
    }
    
    /**
     * Improve screen reader support
     */
    public static function improve_screen_reader_support() {
        // Add screen reader text
        add_action('wp_head', array(__CLASS__, 'add_screen_reader_styles'));
        
        // Add live regions
        add_action('wp_footer', array(__CLASS__, 'add_live_regions'));
    }
    
    /**
     * Add screen reader styles
     */
    public static function add_screen_reader_styles() {
        ?>
        <style>
        .screen-reader-text {
            clip: rect(1px, 1px, 1px, 1px);
            position: absolute !important;
            height: 1px;
            width: 1px;
            overflow: hidden;
        }
        
        .screen-reader-text:focus {
            background-color: #f1f1f1;
            border-radius: 3px;
            box-shadow: 0 0 2px 2px rgba(0, 0, 0, 0.6);
            clip: auto !important;
            color: #21759b;
            display: block;
            font-size: 14px;
            font-weight: bold;
            height: auto;
            left: 5px;
            line-height: normal;
            padding: 15px 23px 14px;
            text-decoration: none;
            top: 5px;
            width: auto;
            z-index: 100000;
        }
        
        .skip-link {
            position: absolute;
            left: -9999px;
            z-index: 999999;
        }
        
        .skip-link:focus {
            left: 6px;
            top: 7px;
        }
        </style>
        <?php
    }
    
    /**
     * Add live regions
     */
    public static function add_live_regions() {
        ?>
        <div id="live-region" class="screen-reader-text" aria-live="polite" aria-atomic="true"></div>
        
        <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Announce page changes
            const liveRegion = document.getElementById('live-region');
            
            // Announce search results
            const searchForms = document.querySelectorAll('form[role="search"]');
            searchForms.forEach(function(form) {
                form.addEventListener('submit', function() {
                    liveRegion.textContent = '<?php _e('Searching...', 'mythemeshop'); ?>';
                });
            });
            
            // Announce AJAX content loading
            const observer = new MutationObserver(function(mutations) {
                mutations.forEach(function(mutation) {
                    if (mutation.type === 'childList' && mutation.addedNodes.length > 0) {
                        liveRegion.textContent = '<?php _e('Content updated', 'mythemeshop'); ?>';
                    }
                });
            });
            
            observer.observe(document.body, {
                childList: true,
                subtree: true
            });
        });
        </script>
        <?php
    }
}

/**
 * Initialize accessibility improvements
 */
function mts_init_accessibility_improvements() {
    MTS_Accessibility_Improvements::init();
}

// Initialize accessibility improvements
add_action('init', 'mts_init_accessibility_improvements');
