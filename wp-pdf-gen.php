<?php
/*
Plugin Name: Wp Pdf Gen
Plugin URI: http://URI_Of_Page_Describing_Plugin_and_Updates
Description: A brief description of the Plugin.
Version: 1.0
Author: Simon
Author URI: http://URI_Of_The_Plugin_Author
License: A "Slug" license name e.g. GPL2
*/

include 'Wp_Pdf_Gen.php';

/**
     * WP_Pdf_Gen
     */
$pdfGenerator = new Wp_Pdf_Gen();

/**
     * Adds Shordcode [pdf] for pdf download link
     */
add_shortcode('pdf', array($pdfGenerator,'addPdfLink'));

add_action('publish_post', array($pdfGenerator,'generatePdf'));
add_action('publish_page', array($pdfGenerator,'generatePdf'));

/**
* Add Settings Link
*/

if(isset($pdfGenerator)){

    //Add the settings link to the plugins page
    function add_plugin_settings_link($links){
        $settings_link = "<a href = 'options-general.php?page=wp_pdf_gen_settings'>Settings Link</a>";
        array_unshift($link, $settings_link);
        return $links;
    }
    $plugin = plugin_basename(__FILE__);
    add_filter('plugin_action_links_$plugin', 'add_plugin_settings_link');
}