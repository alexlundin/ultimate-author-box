<?php
defined('ABSPATH') or die('No script kiddies please!');

$uab_general_settings = get_option('uap_general_settings');
$uab_general_settings = empty($uab_general_settings) ? array() : $uab_general_settings;

?>
<!-- Save settings and Restore Success Message -->
<?php if (isset($_GET['message'])) {
    ?>
    <div class="notice notice-success is-dismissible">
        <p><?php esc_html_e('Settings saved successfully', 'ultimate-author-box'); ?></p></div>
<?php }

?>
<div class="uab-settings-header-wrapper-main">
    <div class="uab-settings-header-wrapper-main-wrap uab-clearfix">
        <div class="uab-settings-header-title">
            <div class="uab-title-menu"><?php _e('Ultimate Author Box'); ?></div>
            <div class="uab-version-wrap">
                <span>Version <?php _e(UAB_VERSION); ?></span>
            </div>
        </div>
    </div>
</div><!--End of uab-settings-header-wrapper-main-->
<div class="uab-setting-page-wrapper">
    <form method="post" action="<?php echo admin_url('admin-post.php'); ?>">
        <input type="hidden" name="action" value="uab_settings_save_action"/>

</div>

<div class="uab-settings-header-wrapper">
    <h3><?php _e('General Settings'); ?></h3>
</div><!--End of uab-settings-header-wrapper-->
<div class="uab-general-settings-content-wrapper">
    <div class="uap-disable-authorbox uab-content-wrapper">
        <div class="uab-label-info-wrap">
            <label><?php _e('Disable Ultimate Author Box', 'ultimate-author-box'); ?></label>
            <span class="uab-info"><?php _e('Disable Author Box in frontend entirely', 'ultimate-author-box'); ?></span>
        </div>
        <div class="uab-slide-checkbox-wrapper">
            <div class="uab-slide-checkbox-wrapper-inner">
                <div class="uab-slide-checkbox">
                    <input type="checkbox" id="uab-disable-uab"
                           name="uab_disable_uab" <?php if (!empty($uab_general_settings['uab_disable_uab'])) echo 'checked'; ?>>
                    <label for="uab-disable-uab"></label>
                </div>
            </div>
        </div>
    </div>
    <div class="uab-postion-wrapper ">
        <span class="uab-inner-title"><?php _e('Choose place to show Author Box', 'ultimate-author-box'); ?></span>

        <div class="uap-disable-authorbox uab-content-wrapper">
            <div class="uab-label-info-wrap">
                <label><?php _e('Show in Posts', 'ultimate-author-box'); ?></label>
                <span class="uab-info"><?php _e('Check to show Ultimate Author Box in Posts', 'ultimate-author-box'); ?></span>
            </div>
            <div class="uab-slide-checkbox-wrapper">
                <div class="uab-slide-checkbox-wrapper-inner">
                    <div class="uab-slide-checkbox">
                        <input type="checkbox" id="uab-posts"
                               name="uab_posts" <?php if (!empty($uab_general_settings['uab_posts'])) echo 'checked'; ?>
                               value="post">
                        <label for="uab-posts"></label>
                    </div>

                </div>
            </div>
        </div>
        <div class="uap-disable-authorbox uab-content-wrapper">
            <div class="uab-label-info-wrap">
                <label><?php _e('Show in Pages', 'ultimate-author-box'); ?></label>
                <span class="uab-info"><?php _e('Check to show Ultimate Author Box in Pages', 'ultimate-author-box'); ?></span>
            </div>

            <div class="uab-slide-checkbox-wrapper">
                <div class="uab-slide-checkbox-wrapper-inner">
                    <div class="uab-slide-checkbox">
                        <input type="checkbox" id="uab-pages"
                               name="uab_pages" <?php if (!empty($uab_general_settings['uab_pages'])) echo 'checked'; ?>
                               value="page">
                        <label for="uab-pages"></label>
                    </div>

                </div>
            </div>
        </div>
    </div><!-- End of uab-postion-wrapper -->
    <div class="select-tab-wrapper uab-content-wrapper">
        <div class="uab-label-info-wrap">
            <label for="uab-tab-type-selection"><?php _e('Show Author Box at', 'ultimate-author-box'); ?></label>
        </div>
        <div>
            <select class="uab-select-input" name="uab_box_position">
                <option value="uab_top" <?php if ($uab_general_settings['uab_box_position'] == 'uab_top') echo 'selected'; ?>><?php _e('Top of Posts', 'ultimate-author-box'); ?></option>
                <option value="uab_bottom" <?php if ($uab_general_settings['uab_box_position'] == 'uab_bottom') echo 'selected'; ?>><?php _e('Bottom of Posts', 'ultimate-author-box'); ?></option>
                <option value="uab_none" <?php if ($uab_general_settings['uab_box_position'] == 'uab_none') echo 'selected'; ?>><?php _e('None', 'ultimate-author-box'); ?></option>
            </select>
        </div>
    </div>
    <div class="uap-hide-empty-authorbox uab-content-wrapper">
        <div class="uab-label-info-wrap">
            <label><?php _e('Hide Author Box if Author Biographical Info is empty', 'ultimate-author-box'); ?></label>
            <span class="uab-info"><?php _e('Check to hide Author Box if Author Biographical Info is empty', 'ultimate-author-box'); ?></span>
        </div>

        <div class="uab-slide-checkbox-wrapper">
            <div class="uab-slide-checkbox-wrapper-inner">
                <div class="uab-slide-checkbox">
                    <input type="checkbox" id="uab-empty-bio"
                           name="uab_empty_bio" <?php if (!empty($uab_general_settings['uab_empty_bio'])) echo 'checked'; ?>>
                    <label for="uab-empty-bio"></label>
                </div>

            </div>
        </div>
    </div>
    <div class="uap-show-default-authorbox uab-content-wrapper">
        <div class="uab-label-info-wrap">
            <label><?php _e('Show Default Biographical Info if empty', 'ultimate-author-box'); ?></label>
            <span class="uab-info"><?php _e('Check to Show Default Biographical Info if empty', 'ultimate-author-box'); ?></span>
        </div>

        <div class="uab-slide-checkbox-wrapper">
            <div class="uab-slide-checkbox-wrapper-inner">
                <div class="uab-slide-checkbox">
                    <input type="checkbox" id="uab-default-bio"
                           name="uab_default_bio" <?php if (!empty($uab_general_settings['uab_default_bio'])) echo 'checked'; ?>>
                    <label for="uab-default-bio"></label>
                </div>

            </div>
        </div>
    </div>
    <div class="uap-default-authorbox-message uab-content-wrapper">
        <div class="uab-label-info-wrap">
            <label><?php _e('Default Author Box Message', 'ultimate-author-box'); ?></label>
        </div>
        <div>
                <textarea
                        name="uab_default_message"><?php echo (isset($uab_general_settings['uab_default_message'])) ? esc_attr($uab_general_settings['uab_default_message']) : ''; ?></textarea>
        </div>
    </div>

    <div class="select-tab-wrapper uab-content-wrapper">
        <div class="uab-label-info-wrap">
            <label for="uab-link-target-option"><?php _e('Frontend link target options ', 'ultimate-author-box'); ?></label>
        </div>
        <div>
            <select class="uab-select-input" id="uab-link-target-option" name="uab_link_target_option">
                <option value="_blank" <?php if ($uab_general_settings['uab_link_target_option'] == '_blank') echo 'selected'; ?>><?php _e('New Page', 'ultimate-author-box'); ?></option>
                <option value="_self" <?php if ($uab_general_settings['uab_link_target_option'] == '_self') echo 'selected'; ?>><?php _e('Same Page', 'ultimate-author-box'); ?></option>
            </select>
        </div>
    </div>


    <!--@since version 1.0.5 -->
    <div class="uab-fontend-popup uab-content-wrapper">
        <div class="uab-label-info-wrap">
            <label><?php _e('Disable Author Email', 'ultimate-author-box'); ?></label>
            <span class="uab-info"><?php _e('Choose whether to show Author Email Info on Author Box', 'ultimate-author-box'); ?></span>
        </div>
        <div class="uab-slide-checkbox-wrapper">
            <div class="uab-slide-checkbox-wrapper-inner">
                <div class="uab-slide-checkbox">
                    <input type="checkbox" id="uab-disable-email"
                           name="uab_disable_email" <?php if (!empty($uab_general_settings['uab_disable_email'])) echo 'checked'; ?>>
                    <label for="uab-disable-email"></label>
                </div>
            </div>
        </div>
    </div>


</div><!--End of uab-general-settings-content-wrapper-->


<div class="uab_admin-general-bttn">
    <?php
    wp_nonce_field('uab_admin_option_update');
    wp_nonce_field('uab_action_nonce', 'uab_nonce_field');
    $restore_nonce = wp_create_nonce('uab-restore-nonce');
    ?>
    <input type="submit" class="button-primary" value="<?php _e('Save Changes', 'ultimate-author-box'); ?>"
           name="uab_settings_save_button"/>
    <a href="<?php echo admin_url() . 'profile.php'; ?>"><input type="button" class="button-secondary"
                                                                value="<?php _e('Go to Profile Settings', 'ultimate-author-box'); ?>"></a>
</div>
</div>


</form>
</div><!--End of uab-setting-page-wrapper-->



