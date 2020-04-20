<?php defined('ABSPATH') or die('No script kiddies please!'); ?>
<?php
if (empty($unserialized_uab_profile_data)) {
    esc_html_e('Please fill your Author Box', 'ultimate-author-box');
}
?>
<div class="uab-profile-backend-wrapper">
    <div class="uab-profile-header-wrapper">
        <div class="uab-profile-header-main">
            <h2><?php _e('Ultimate Author Box Profile Settings', 'ultimate-author-box'); ?></h2>
        </div>
    </div>
    <input type="hidden" name="uab_profile_data[0][uab_random_identifier]" value="<?php echo $uab_random_identifier ?>">
    <div class="uab-profile-content-wrapper">

        <div id="tabs" class="uab-backend-tabs">
            <div id="tabs-d">
                <!-- <input type="hidden" name="uab_tab_keys" class="uab-tab-keys" value="<?php echo(isset($uab_key_set) ? esc_attr($uab_key_set) : ''); ?>"> -->
                <?php include(UAB_PATH . '/inc/backend/uab-backend-tabs/uap-defaut-tab.php'); ?>
            </div><!--End of Default Tab-->
        </div>
    </div>

	
