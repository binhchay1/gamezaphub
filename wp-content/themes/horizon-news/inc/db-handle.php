<?php

function create_custom_users_table()
{
    global $wpdb;

    $table_name = $wpdb->prefix . 'custom_users';

    if ($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
        $sql = "CREATE TABLE $table_name (
            id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
            email VARCHAR(255) NOT NULL UNIQUE,
            password VARCHAR(255) NOT NULL,
            first_name VARCHAR(100),
            last_name VARCHAR(100),
            verification_token VARCHAR(255),
            is_verified TINYINT(1) DEFAULT 0,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (id)
        ) {$wpdb->get_charset_collate()};";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);

        if ($wpdb->last_error) {
            error_log('Error creating wp_custom_users table: ' . $wpdb->last_error);
        }
    }
}
add_action('after_switch_theme', 'create_custom_users_table');
