<?php
/**
 * Created by PhpStorm.
 * User: Simon
 * Date: 19.06.2015
 * Time: 09:37
 */

include ('vendor/appotter/mpdf/mpdf.php');

if(!class_exists('Wp_Pdf_Gen')) {


    class Wp_Pdf_Gen
    {

        function __construct()
        {

            register_activation_hook(__FILE__, array('Wp_Pdf_Gen', 'activate'));
            register_activation_hook(__FILE__, array('Wp_Pdf_Gen', 'deactivate'));


            add_action('admin_init', array(&$this, 'admin_init'));
            add_action('admin_menu', array(&$this, 'add_menu'));
            add_action('media_buttons', 'add_pdf_checkbox');

        }

        function addPdfLink()
        {
            $title = get_the_title(get_the_ID());
            echo "<a href='/wordpress'>Download Artikel:'$title'</a>";
        }

        function add_pdf_checkbox()
        {
            echo "<b>PDF </b><input type='checkbox' name='pdf-checkbox' value='1' checked>";
        }


        function generatePdf()
        {
            //get content
            $title = get_the_title(get_the_ID());
            $id = get_the_ID();
            $post = get_post($id);
            $content = apply_filters('the_content', $post->post_content);

            //create pdf
            $mpdf = new mPDF();
            $mpdf->WriteHTML($content);
            $mpdf->Output('../wp-content/uploads/' . $title . '.pdf', 'F');
            exit;
        }

        /**
         * Installation
         */
        public static function activate()
        {

        }

        /**
         * Deinstallation
         */
        public static function deactivate()
        {

        }

        /**
         * Settings
         */
        public function admin_init()
        {
            //Settings for this Plugin
            $this->init_settings();
        }

        /**
         * Menu
         */
        public function add_menu()
        {
            //add settings PDF Generator with callback plugin_settings_page
            add_options_page('PDF Generator Settings', 'PDF Generator', 'manage_options', 'wp_pdf_gen_settings', array(&$this, 'plugin_settings_page'));
        }


        public function plugin_settings_page()
        {
            if (!current_user_can('manage_options')) {
                wp_die(__('You do not have sufficient permissions to access this page'));
            }

            //load settings template
            include(sprintf('%s/templates/settings.php', dirname(__FILE__)));
        }

        public function init_settings()
        {
            //register settings - namespace: wp_pdf_gen_group
            register_setting('wp_pdf_gen_group', 'pdf_header_dir');
            register_setting('wp_pdf_gen_group', 'pdf_save_dir');
        }

    }
}