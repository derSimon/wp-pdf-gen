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

            // Register javascript.
            add_action('admin_enqueue_scripts', array(&$this, 'enqueue_admin_js'));

            // Call Function to store value into database.
            add_action('init', array(&$this, 'store_in_database'));
            // Call Function to delete image.
            add_action('init', array(&$this, 'delete_image'));

        }

        function addPdfLink()
        {
            $title = get_the_title(get_the_ID());

            $upload_dir = wp_upload_dir();
            $upload_dir = $upload_dir['baseurl'];
            $download = $upload_dir ."/". $this->get_permalink_post_name();
            $img_dir = plugin_dir_url(dirname(__FILE__)).basename(__DIR__);
            echo "<a href='".$download.".pdf'><img src='".$img_dir."/img/Adobe_ExportPDF_icon.png' width='40pt'/>Download PDF</a>";

        }


        /**
         * generates the PDF File
         */
        function generatePdf()
        {
            //get content
            //$title = get_the_title(get_the_ID());
            //echo '<script type="text/javascript" language="Javascript">alert("generatepdf")</script>';
            $title = $this->get_permalink_post_name();
            $id = get_the_ID();
            $post = get_post($id);
            $content = apply_filters('the_content', $post->post_content);
            //add header to pdf file
            $content = '<img src="'. get_option('pdf_header_dir') .' ">'. $content;

            //create pdf
            $mpdf = new mPDF();
            $mpdf->WriteHTML($content);
            $mpdf->Output('../wp-content/uploads/' . $title . '.pdf', 'F');
            //exit;
        }

        /**
         * returns the post title es diyplayed in the uri
         */
        function get_permalink_post_name(){
            //
            $permalink = get_permalink();
            $tokens = explode('/', $permalink);
            $title = $tokens[sizeof($tokens)-2];
            return $title;
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

            global $pagenow;
            if ('media-upload.php' == $pagenow || 'async-upload.php' == $pagenow) {
            // replace the 'Insert into Post Button inside Thickbox' with replace_window_text method
                add_filter('gettext', array($this, 'replace_window_text'), 1, 2);
            // gettext filter and every sentence.
            }
        }

        /**
         * Referer parameter in our script file is for to know from which page we are launching the Media Uploader as we want to change the text "Insert into Post".
         */
        function replace_window_text($translated_text, $text) {
            if ('Insert into Post' == $text) {
                $referer = strpos(wp_get_referer(), 'media_page');
                if ($referer != '') {
                    return __('Upload Image', 'ink');
                }
            }
            return $translated_text;
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
           // register_setting('wp_pdf_gen_group', 'pdf_save_dir');
        }

        public function enqueue_admin_js() {
            wp_enqueue_script('media-upload'); //Provides all the functions needed to upload, validate and give format to files.
            wp_enqueue_script('thickbox'); //Responsible for managing the modal window.
            wp_enqueue_style('thickbox'); //Provides the styles needed for this window.
            wp_enqueue_script('script', plugins_url('js/upload.js', __FILE__), array('jquery'), '', true); //It will initialize the parameters needed to show the window properly.
        }


        // The Function store image path in option table.
        public function store_in_database(){
            if(isset($_POST['submit'])){
                $image_path = $_POST['path'];
                update_option('pdf_header_dir', $image_path);
            }
        }

        // Below Function will delete image.
        function delete_image()
        {
            if (isset($_POST['remove'])) {
                global $wpdb;
                $img_path = $_POST['path'];

                // get the images meta ID.
                $query = "SELECT ID FROM wp_posts where guid = '" . esc_url($img_path) . "' AND post_type = 'attachment'";
                $results = $wpdb->get_results($query);

                // delete images meta
                foreach ($results as $row) {
                    wp_delete_attachment($row->ID); //delete the image and also delete the attachment from the Media Library.
                }
                delete_option('ink_image'); //delete image path from database.
            }
        }
    }
}