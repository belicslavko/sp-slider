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



// Install table

register_activation_hook(__FILE__, 'sp_slider_db_install');

register_deactivation_hook(__FILE__, 'sp_slider_db_uninstall');

// css
add_action('admin_head', 'sp_slider_css');

function sp_slider_css()
{

    echo '<link rel="stylesheet" type="text/css" href="' . plugins_url('sp-slider/css/style.css') . '">';

}


global $db_version;
$db_version = '1.0';

function sp_slider_db_install()
{
    global $wpdb;
    global $db_version;

    $table_name = $wpdb->prefix . 'plugin_nbs';

    $table_type = $wpdb->prefix . 'plugin_nbs_type';

    $table_template = $wpdb->prefix . 'plugin_nbs_template';

    $charset_collate = '';

    if (!empty($wpdb->charset)) {
        $charset_collate = "DEFAULT CHARACTER SET {$wpdb->charset}";
    }

    if (!empty($wpdb->collate)) {
        $charset_collate .= " COLLATE {$wpdb->collate}";
    }

    $sqlL = "CREATE TABLE IF NOT EXISTS $table_name (
    id int(11) NOT NULL AUTO_INCREMENT,
    id_template int(11) NOT NULL,
    exchange_rate_type int(11) NOT NULL,
    exchange_rate_username varchar(255) NOT NULL,
    exchange_rate_password varchar(255) NOT NULL,
    exchange_rate_licenceid varchar(255) NOT NULL,
    exchange_rate_wsdl varchar(255) NOT NULL,
    exchange_rate_namespace varchar(255) NOT NULL,
    eur varchar(3) NOT NULL,
    aud varchar(3) NOT NULL,
    cad varchar(3) NOT NULL,
    hrk varchar(3) NOT NULL,
    czk varchar(3) NOT NULL,
    dkk varchar(3) NOT NULL,
    huf varchar(3) NOT NULL,
    jpy varchar(3) NOT NULL,
    kwd varchar(3) NOT NULL,
    nok varchar(3) NOT NULL,
    rub varchar(3) NOT NULL,
    sek varchar(3) NOT NULL,
    chf varchar(3) NOT NULL,
    gbp varchar(3) NOT NULL,
    usd varchar(3) NOT NULL,
    bam varchar(3) NOT NULL,
    pln varchar(3) NOT NULL,
    ats varchar(3) NOT NULL,
    bef varchar(3) NOT NULL,
    fim varchar(3) NOT NULL,
    frf varchar(3) NOT NULL,
    dem varchar(3) NOT NULL,
    grd varchar(3) NOT NULL,
    iep varchar(3) NOT NULL,
    itl varchar(3) NOT NULL,
    luf varchar(3) NOT NULL,
    pte varchar(3) NOT NULL,
    esp varchar(3) NOT NULL,

    PRIMARY KEY (id)
    ) $charset_collate";

    $sqlT = "CREATE TABLE IF NOT EXISTS $table_type (
      id int(11) NOT NULL AUTO_INCREMENT,
      name varchar(255) NOT NULL,
      PRIMARY KEY (id)
    ) $charset_collate";

    $sqlM = "CREATE TABLE IF NOT EXISTS $table_template (
      id int(11) NOT NULL AUTO_INCREMENT,
      name varchar(255) NOT NULL,
      header text NOT NULL,
      body text NOT NULL,
      footer text NOT NULL,
      PRIMARY KEY (id)
    ) $charset_collate";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sqlL);
    dbDelta($sqlT);
    dbDelta($sqlM);

    $wpdb->insert(
        $table_type,
        array(
            'id' => 1,
            'name' => 'Kupovni kurs'
        )
    );

    $wpdb->insert(
        $table_type,
        array(
            'id' => 2,
            'name' => 'Srednji kurs'
        )
    );

    $wpdb->insert(
        $table_type,
        array(
            'id' => 3,
            'name' => 'Prodajni kurs'
        )
    );


    $wpdb->insert(
        $table_template,
        array(

            'name' => 'Table template',
            'header' => '<table class="nbs-table">
                    <tr>
                        <th>Naziv zemlje</th>
                        <th>Oznaka valute</th>
                        <th>Važi za</th>
                        <th>Srednji kurs</th>
                    </tr>',
            'body' => '<tr>
                   <td>{CurrencyNameSerLat}</td>
                   <td>{CurrencyCodeAlfaChar}</td>
                   <td>{Unit}</td>
                   <td>{Value}</td>
                   </tr>',
            'footer' => '</table>',
        )
    );

    add_option('db_version', $db_version);
}