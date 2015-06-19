<?php
/**
 * Created by PhpStorm.
 * User: Simon
 * Date: 18.06.2015
 * Time: 11:57
 */
?>

<div clas="wrap">
    <h2>WP Plugin Template</h2>
    <form method="post" action="options.php">
        <?php
            @settings_fields('wp_pdf_gen_group');
            @do_settings_fields('wp_pdf_gen_group');
        ?>

        <table class="form-table">
            <tr valign="top">
                <th scope="row"><label for="pdf_header_dir">PDF Header:</label></th>
                <td><input type="upload_preview" name="pdf_header_dir" id="pdf_header_dir" value="<?php echo get_option('pdf_header_dir'); ?>" /></td>
            </tr>
            <tr valign="top">
                <th scope="row"><label for="pdf_save_dir">Save PDF to:</label></th>
                <td><input type="text" name="pdf_save_dir" id="pdf_save_dir" value="<?php echo get_option('pdf_save_dir'); ?>" /></td>
            </tr>
        </table>

        <?php @submit_button(); ?>

    </form>
</div>