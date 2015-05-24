<?php

/*
Plugin Name: Simple Post Slider
Plugin URI: http://URI_Of_Page_Describing_Plugin_and_Updates
Description: A brief description of the Plugin.
Version: 1.0
Author: Belic Slavko
Author URI: http://URI_Of_The_Plugin_Author
License: A "Slug" license name e.g. GPL2
*/


/**
 *  Activation plugin
 */

register_activation_hook(__FILE__, 'sp_slider_db_install');

register_deactivation_hook(__FILE__, 'sp_slider_db_uninstall');

/**
 *  Install DB
 */

global $db_version;
$db_version = '1.0';

function sp_slider_db_install()
{
    global $wpdb;
    global $db_version;

    $table_name = $wpdb->prefix . 'sp_slider';

    $table_post = $wpdb->prefix . 'sp_slider_posts';

    $charset_collate = '';

    if (!empty($wpdb->charset)) {
        $charset_collate = "DEFAULT CHARACTER SET {$wpdb->charset}";
    }

    if (!empty($wpdb->collate)) {
        $charset_collate .= " COLLATE {$wpdb->collate}";
    }

    $sql_slider = "CREATE TABLE IF NOT EXISTS $table_name (
    id int(11) NOT NULL AUTO_INCREMENT,
    name varchar(255) NOT NULL,
    interval int(11) NOT NULL,
    wrap int(11) NOT NULL,
    PRIMARY KEY (id)
    ) $charset_collate";

    $sql_slider_post = "CREATE TABLE IF NOT EXISTS $table_post (
      id int(11) NOT NULL AUTO_INCREMENT,
      post_type varchar(255) NOT NULL,
      post_id int(11) NOT NULL,
      PRIMARY KEY (id)
    ) $charset_collate";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql_slider);
    dbDelta($sql_slider_post);

    add_option('db_version', $db_version);
}

/**
 *  Uninstall DB
 */

function sp_slider_db_uninstall()
{
    global $wpdb;

    $wpdb->query("DROP TABLE IF EXISTS " . $table_name);
    $wpdb->query("DROP TABLE IF EXISTS " . $table_post);

}

/**
 *  Plugin css
 */

add_action('admin_head', 'sp_slider_css');

function sp_slider_css()
{
    echo '<link rel="stylesheet" type="text/css" href="' . plugins_url('sp-slider/css/style.css') . '">';
}
