<?php defined('ABSPATH') or die('No script kiddies please!'); ?>
<div class="uab-short-contact">
    <?php
    $author_url = get_the_author_meta('url', $author_id);
    $author_phone = isset($uab_profile_data[0]['uab_company_phone']) ? $uab_profile_data[0]['uab_company_phone'] : '';
    $uab_email_disable = isset($uab_profile_data[0]['uab-email-disable']) ? 1 : 0;

    $author_website_label = (!empty($uab_profile_data[0]['uab_site_label'])
        && ($uab_template_type != 'uab-template-1')
        && ($uab_template_type != 'uab-template-17')) ? esc_attr($uab_profile_data[0]['uab_site_label']) : $author_url;

    $author_phone_label = (!empty($uab_profile_data[0]['uab_phone_label'])
        && ($uab_template_type != 'uab-template-1')
        && ($uab_template_type != 'uab-template-17')) ? esc_attr($uab_profile_data[0]['uab_phone_label']) : $author_phone;

    $author_email_label = (!empty($uab_profile_data[0]['uab_email_label'])
        && ($uab_email_disable == 0)
        && ($uab_template_type != 'uab-template-1')
        && ($uab_template_type != 'uab-template-17')) ? esc_attr($uab_profile_data[0]['uab_email_label']) : (esc_html($this->encode_email(get_the_author_meta('email', $author_id))));


    ?>
    <?php if (!empty($author_url)): ?>

        <div class="uab-contact-inner">

            <?php
            if (!empty($author_url)) {

                ?>
                <span class="uab-contact-label"><?php echo !empty($uab_profile_data[0]['uab_site_label']) ? esc_attr($uab_profile_data[0]['uab_site_label']) : esc_html__('web', 'ultimate-author-box'); ?></span>

                <div class="uab-user-website">
                    <a href="<?php esc_attr_e($author_url); ?>"
                       target="<?php esc_attr_e($uab_general_settings['uab_link_target_option']); ?>"><?php esc_html_e($author_website_label); ?></a>
                </div>

                <?php
            } ?>

        </div>
    <?php endif; ?>
    <?php if (!$uab_email_disable): ?>
        <?php if (isset($uab_general_settings['uab_disable_email']) && ($uab_general_settings['uab_disable_email'] == 0)): ?>
            <div class="uab-contact-inner">
                    <span class="uab-contact-label"><?php echo !empty($uab_profile_data[0]['uab_email_label']) ? esc_attr($uab_profile_data[0]['uab_email_label']) : esc_html__('email', 'ultimate-author-box'); ?></span>
                <div class="uab-user-email">
                    <a href="mailto:<?php esc_attr_e(the_author_meta('email', $author_id)); ?>"><?= $author_email_label ?></a>
                </div>
            </div>
        <?php endif ?>
    <?php endif; ?>
    <?php if (!empty($author_phone)): ?>

            <div class="uab-contact-inner">
            </div>
      
    <?php endif; ?>
</div>