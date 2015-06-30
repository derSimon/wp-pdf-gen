<?php
/**
 * Created by PhpStorm.
 * User: Simon
 * Date: 18.06.2015
 * Time: 11:57
 */
?>

<div clas="wrap">
    <h2>WP PDF Generator Settings</h2>
    <form class="pdf_header_dir" method="post" action="#">
        <b>Upload a header image for the generated PDF</b><br>
        <?php
            @settings_fields('wp_pdf_gen_group');
            @do_settings_fields('wp_pdf_gen_group');

        //echo get_option('pdf_header_dir'). "</br>";
        $img_path = get_option('pdf_header_dir');
        ?>

        <p>
            <input type="text" name="path" class="image_path" value="<?php echo $img_path; ?>" id="image_path">
            <input type="button" value="Upload Image" class="button-primary" id="upload_image"/>
        </p>
        <p>
        <div id="show_upload_preview">

            <?php if(! empty($img_path)){
                ?>
                <img src="<?php echo $img_path ; ?>">
        </p>
                <input type="submit" name="remove" class="button-secondary" id="remove_image" value="Remove Image"/>
            <?php } ?>
        </div>
        </br>
        <input type="submit" name="submit" class="save_path button-primary" id="submit_button" value="Save Settings">
    </form>
</div>