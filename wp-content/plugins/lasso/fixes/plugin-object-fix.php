<?php

/**
 * Plugin Object Fix - Sửa lỗi undefined property stdClass::$plugin
 * 
 * Lỗi này xảy ra khi WordPress core cố gắng truy cập property 'plugin' 
 * trên một object không có property này.
 */

add_filter('all_plugins', function ($plugins) {
    if (!is_array($plugins)) {
        return $plugins;
    }

    foreach ($plugins as $plugin_path => &$plugin) {
        if (!is_array($plugin)) {
            continue;
        }

        if (!isset($plugin['Name'])) {
            $plugin['Name'] = 'Unknown Plugin';
        }
        if (!isset($plugin['Version'])) {
            $plugin['Version'] = '1.0.0';
        }
        if (!isset($plugin['Description'])) {
            $plugin['Description'] = '';
        }
        if (!isset($plugin['PluginURI'])) {
            $plugin['PluginURI'] = '';
        }
    }

    return $plugins;
}, 10, 1);

add_action('init', function () {
    if (class_exists('WP_List_Util')) {
        class Lasso_WP_List_Util_Protection extends WP_List_Util
        {
            public function __construct($input)
            {
                if (is_array($input)) {
                    parent::__construct($input);
                } else {
                    parent::__construct(array());
                }
            }
        }
    }
}, 5);

add_filter('pre_option_active_plugins', function ($pre_option) {
    if (!is_array($pre_option)) {
        return get_option('active_plugins', array());
    }
    return $pre_option;
}, 10, 1);

add_filter('plugins_api', function ($result, $action, $args) {
    if (is_wp_error($result)) {
        return $result;
    }

    if (is_object($result)) {
        if (!isset($result->name)) {
            $result->name = 'Unknown Plugin';
        }
        if (!isset($result->version)) {
            $result->version = '1.0.0';
        }
        if (!isset($result->description)) {
            $result->description = '';
        }
        if (!isset($result->plugin)) {
            $result->plugin = '';
        }
    }

    return $result;
}, 10, 3);
