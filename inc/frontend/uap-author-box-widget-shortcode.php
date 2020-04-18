<?php
$uab_shortcode_atts = shortcode_atts(array(
    'author_list' => 1,
    'display_type' => 'template-1',
    'detect_author' => 0,
    'display_author_name' => 1,
    'display_author_designation' => 1,
    'display_author_description' => 1,
    'display_social_icons' => 1,
    'display_contacts' => 0,

), $atts);

$authorList = isset($atts['author_list']) ? $atts['author_list'] : $uab_shortcode_atts['author_list'];
$displayType = isset($atts['display_type']) ? $atts['display_type'] : $uab_shortcode_atts['display_type'];
$detectAuthor = isset($atts['detect_author']) ? $atts['detect_author'] : $uab_shortcode_atts['detect_author'];
$displayAuthorName = isset($atts['display_author_name']) ? $atts['display_author_name'] : $uab_shortcode_atts['display_author_name'];
$displayAuthorDesignation = isset($atts['display_author_designation']) ? $atts['display_author_designation'] : $uab_shortcode_atts['display_author_designation'];
$displayAuthorDescription = isset($atts['display_author_description']) ? $atts['display_author_description'] : $uab_shortcode_atts['display_author_description'];
$displaySocialIcons = isset($atts['display_social_icons']) ? $atts['display_social_icons'] : $uab_shortcode_atts['display_social_icons'];
$displayContacts = isset($atts['display_contacts']) ? $atts['display_contacts'] : $uab_shortcode_atts['display_contacts'];


$user_id = $authorList;
if ($detectAuthor) {
    if (is_author()) {
        $obj = get_queried_object();
        $user_id = $obj->data->ID;
    } elseif (is_single()) {
        $obj = get_queried_object();
        $user_id = $obj->post_author;
    }
}


$uab_profile_data = maybe_unserialize(get_the_author_meta('uab_profile_data', $user_id));
$uab_general_settings = get_option('uap_general_settings');
$uab_social_icons = maybe_unserialize(get_the_author_meta('uab_social_icons', $user_id));

// Added version silver
$uab_influence_target_link = intval(get_the_author_meta('uab_frontend_target_ranking', $user_id));
$follow_var = ($uab_influence_target_link == intval(1)) ? ('rel="' . esc_attr("nofollow noopener") . '"') : ('');

?>
<div class="uab-abw-wrapper <?php esc_attr_e($displayType); ?>">
    <?php if ($displayType == 'template-3') { ?>
        <div id="<?php echo $uab_random_identifier; ?>"
             class="uab-frontend-inner-layer uab-frontend-wrapper-author-<?= intval($author_id) ?> <?= (isset($anchor_box) && !empty($anchor_box)) ? esc_attr('uab_anchor_box') : '' ?>" <?= isset($anchor_box) ? ('data-timeout="' . intval($anchor_timeout) . '"') : intval(1000) ?>>
            <?php
            if ($uab_general_settings['uab_disable_uab']) {
                //echo 'Disable author box';
            } else {
                if ($uab_general_settings['uab_empty_bio'] && $author_description == '') {
                    //echo 'The Author Box Will not show if the author bio is empty';
                } else { ?>
                    <div id="uab-frontend-wrapper" class="uab-frontend-wrapper uab-template-1">
                        <div class="uab-tab-content">
                            <div class="uab-defaut-tab uab-clearfix">
                                <?php
                                include(UAB_PATH . 'inc/frontend/frontend-default/components/uab-component-image.php');
                                ?>
                                <div class="uab-front-content">
                                    <div class="uab-display-name">
                                        <?php if ($displayAuthorName): ?>
                                            <span><?php esc_html_e(the_author_meta('display_name', $user_id)); ?></span>
                                        <?php else: ?>
                                            <a href="<?php echo get_author_posts_url($user_id); ?>"><?php esc_html_e(the_author_meta('display_name', $user_id)); ?></a>
                                        <?php endif ?>
                                        <?php
                                        $user_meta = get_userdata($user_id);
                                        $user_roles = $user_meta->roles;
                                        $user_role_lists = $this->get_editable_roles();
                                        foreach ($user_role_lists as $user_role_list => $value) {
                                            foreach ($user_roles as $role => $val) {
                                                if ($user_role_list == $val) {
                                                    ?>
                                                    <span
                                                    class="uab-user-role uab-role-<?php _e($user_role_lists[$user_role_list]['name']); ?>"><?php esc_html_e($user_role_lists[$user_role_list]['name'], 'ultimate-author-box'); ?></span><?php
                                                }
                                            }
                                        } ?>
                                    </div>
                                    <div class="uab-short-info">
                                        <?php
                                        if ((get_the_author_meta('description', $user_id) == '' && $uab_general_settings['uab_default_bio'])) {
                                            esc_html_e($uab_general_settings['uab_default_message']);
                                        } else {
                                            esc_html_e(the_author_meta('description', $user_id));
                                        }
                                        ?>
                                    </div>


                                    <div class="uab-short-contact">
                                        <?php
                                        $author_url = get_the_author_meta('url', $user_id);
                                        $author_phone = isset($uab_profile_data[0]['uab_company_phone']) ? $uab_profile_data[0]['uab_company_phone'] : '';
                                        $uab_email_disable = isset($uab_profile_data[0]['uab-email-disable']) ? 1 : 0;

                                        $author_website_label = $author_url;

                                        $author_phone_label = $author_phone;

                                        $author_email_label = esc_html($this->encode_email(get_the_author_meta('email', $user_id)));
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
                                                        <a href="mailto:<?php esc_attr_e(the_author_meta('email', $user_id)); ?>"><?= $author_email_label ?></a>
                                                    </div>
                                                </div>
                                            <?php endif ?>
                                        <?php endif; ?>
                                    </div>

                                    <div class="uab-social-icons">

                                        <?php if (!empty($uab_social_icons)): ?>
                                            <span class="uab-contact-label"><?php esc_html_e('follow me', 'ultimate-author-box'); ?></span>
                                        <?php endif; ?>

                                        <ul id="uap-social-outlets-fields">
                                            <?php
                                            $uab_social_icons = maybe_unserialize(get_the_author_meta('uab_social_icons', $user_id));
                                            if (!empty($uab_social_icons)) {
                                                foreach ($uab_social_icons as $socialname => $innerarray) {
                                                    if (!empty($uab_social_icons[$socialname]['url'])) {
                                                        $uab_font_type = 'fab';
                                                        if ($uab_social_icons[$socialname]['icon'] == 'rss') {
                                                            $uab_font_type = 'fas';
                                                        }
                                                        ?>
                                                        <li class="uab-icon-<?php esc_attr_e($uab_social_icons[$socialname]['label']); ?>">
                                                            <a href="<?php esc_attr_e($uab_social_icons[$socialname]['url']); ?>"
                                                               target="<?php esc_attr_e($uab_general_settings['uab_link_target_option']); ?>"
                                                               rel="nofollow noopener">
                                                                <i class="<?php esc_attr_e($uab_font_type); ?> fa-<?php esc_attr_e($uab_social_icons[$socialname]['icon']); ?>"></i>
                                                            </a>
                                                        </li>
                                                        <?php
                                                    }
                                                }
                                            }
                                            ?>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php
                }
            }
            ?>
        </div>
    <?php } ?>
    <?php if ($displayType == 'template-2') { ?>
    <?php if ($displayAuthorName) { ?>
        <div class="uab-abw-name-wrapper">
            <a href="<?php echo get_author_posts_url($user_id); ?>"><?php esc_html_e(the_author_meta('display_name', $user_id)); ?></a>
        </div>
    <?php } ?>
    <?php if ($displayAuthorDesignation) { ?>
        <?php if (isset($uab_profile_data[0]['uab_company_designation']) && !empty($uab_profile_data[0]['uab_company_designation'])) { ?>
            <div class="uab-abw-designation-wrapper">
                <?php
                esc_html_e($uab_profile_data[0]['uab_company_designation']);
                if (!empty($uab_profile_data[0]['uab_company_url'])) {
                    _e(' at', 'ultimate-author-box');
                    ?>
                    <a href="<?php esc_attr_e($uab_profile_data[0]['uab_company_url']); ?>"
                       target="<?php esc_attr_e($uab_general_settings['uab_link_target_option']); ?>" <?= $follow_var ?>><?php esc_html_e($uab_profile_data[0]['uab_company_name']); ?></a>
                    <?php
                }
                ?>
            </div>
        <?php } ?>
    <?php } ?>
    <div class="uab-abw-image-wrapper">
        <a href="<?php echo get_author_posts_url($user_id); ?>">
            <?php $uab_select_image_option = isset($uab_profile_data[0]['uab_image_select']) ? $uab_profile_data[0]['uab_image_select'] : 'uab_gravatar';
            switch ($uab_select_image_option) {
                case 'uab_upload_image':
                    if (!empty($uab_profile_data[0]['uab_upload_image_url'])) {
                        ?>
                        <!--Custom Image-->
                        <div class="uap-profile-image ">
                            <img src="<?php esc_attr_e($uab_profile_data[0]['uab_upload_image_url']); ?>"
                                 width="200">
                        </div>
                        <?php
                    } else {
                        ?>
                        <div class="uap-profile-image ">
                            <?php _e(get_avatar($user_id, 200)); ?>
                        </div>
                        <?php
                    }
                    break;
                default:
                    ?>
                    <!--Gravatar Image-->
                    <div class="uap-profile-image ">
                        <?php _e(get_avatar($user_id, 200)); ?>
                    </div>
                <?php
            } ?></a>
    </div>
    <?php if ($displayAuthorDesignation) { ?>
        <?php if (isset($uab_profile_data[0]['uab_company_designation']) && !empty($uab_profile_data[0]['uab_company_designation'])) { ?>
            <div class="uab-abw-designation-wrapper">
                <?php
                esc_html_e($uab_profile_data[0]['uab_company_designation']);
                if (!empty($uab_profile_data[0]['uab_company_url'])) {
                    _e(' at', 'ultimate-author-box');
                    ?>
                    <a href="<?php esc_attr_e($uab_profile_data[0]['uab_company_url']); ?>"
                       target="<?php esc_attr_e($uab_general_settings['uab_link_target_option']); ?>" <?= $follow_var ?>><?php esc_html_e($uab_profile_data[0]['uab_company_name']); ?></a>
                    <?php
                }
                ?>
            </div>
        <?php } ?>
    <?php } ?>
    <?php if ($displayAuthorDescription) { ?>
        <div class="uab-abw-description-wrapper">
            <?php
            if ((get_the_author_meta('description', $user_id) == '' && $uab_general_settings['uab_default_bio'])) {
                esc_html_e($uab_general_settings['uab_default_message']);
            } else {
                esc_html_e(the_author_meta('description', $user_id));
            }
            ?>
        </div>
    <?php } ?>
    <?php if ($displaySocialIcons) { ?>
        <div class="uab-abw-social-wrapper">
            <ul id="uap-social-outlets-fields">
                <?php
                if (!empty($uab_social_icons)) {
                    foreach ($uab_social_icons as $socialname => $innerarray) {
                        if (!empty($uab_social_icons[$socialname]['url'])) {
                            $uab_font_type = 'fab';
                            if ($uab_social_icons[$socialname]['icon'] == 'rss') {
                                $uab_font_type = 'fas';
                            }
                            ?>
                            <li>
                                <a href="<?php esc_attr_e($uab_social_icons[$socialname]['url']); ?>"
                                   target="<?php esc_attr_e($uab_general_settings['uab_link_target_option']); ?>"
                                   rel="nofollow noopener">
                                    <i class="<?php esc_attr_e($uab_font_type); ?> fa-<?php esc_attr_e($uab_social_icons[$socialname]['icon']); ?>"></i>
                                </a>
                            </li>
                            <?php
                        }
                    }
                }

                if ($displayContacts) {
                    $author_phone = isset($uab_profile_data[0]['uab_company_phone']) ? $uab_profile_data[0]['uab_company_phone'] : '';
                    $author_url = get_the_author_meta('url', $user_id);
                    $uab_email_disable = isset($uab_profile_data[0]['uab-email-disable']) ? 1 : 0;
                    if (!empty($author_url)) {
                        ?>
                        <li class="uab-icon-web">
                            <a href="<?php esc_attr_e(get_the_author_meta('url', $user_id)); ?>"
                               target="<?php esc_attr_e($uab_general_settings['uab_link_target_option']); ?>">
                                <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="globe-americas"
                                     role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 496 512"
                                     class="svg-inline--fa fa-globe-americas fa-w-16 fa-2x">
                                    <path fill="currentColor"
                                          d="M248 8C111.03 8 0 119.03 0 256s111.03 248 248 248 248-111.03 248-248S384.97 8 248 8zm82.29 357.6c-3.9 3.88-7.99 7.95-11.31 11.28-2.99 3-5.1 6.7-6.17 10.71-1.51 5.66-2.73 11.38-4.77 16.87l-17.39 46.85c-13.76 3-28 4.69-42.65 4.69v-27.38c1.69-12.62-7.64-36.26-22.63-51.25-6-6-9.37-14.14-9.37-22.63v-32.01c0-11.64-6.27-22.34-16.46-27.97-14.37-7.95-34.81-19.06-48.81-26.11-11.48-5.78-22.1-13.14-31.65-21.75l-.8-.72a114.792 114.792 0 0 1-18.06-20.74c-9.38-13.77-24.66-36.42-34.59-51.14 20.47-45.5 57.36-82.04 103.2-101.89l24.01 12.01C203.48 89.74 216 82.01 216 70.11v-11.3c7.99-1.29 16.12-2.11 24.39-2.42l28.3 28.3c6.25 6.25 6.25 16.38 0 22.63L264 112l-10.34 10.34c-3.12 3.12-3.12 8.19 0 11.31l4.69 4.69c3.12 3.12 3.12 8.19 0 11.31l-8 8a8.008 8.008 0 0 1-5.66 2.34h-8.99c-2.08 0-4.08.81-5.58 2.27l-9.92 9.65a8.008 8.008 0 0 0-1.58 9.31l15.59 31.19c2.66 5.32-1.21 11.58-7.15 11.58h-5.64c-1.93 0-3.79-.7-5.24-1.96l-9.28-8.06a16.017 16.017 0 0 0-15.55-3.1l-31.17 10.39a11.95 11.95 0 0 0-8.17 11.34c0 4.53 2.56 8.66 6.61 10.69l11.08 5.54c9.41 4.71 19.79 7.16 30.31 7.16s22.59 27.29 32 32h66.75c8.49 0 16.62 3.37 22.63 9.37l13.69 13.69a30.503 30.503 0 0 1 8.93 21.57 46.536 46.536 0 0 1-13.72 32.98zM417 274.25c-5.79-1.45-10.84-5-14.15-9.97l-17.98-26.97a23.97 23.97 0 0 1 0-26.62l19.59-29.38c2.32-3.47 5.5-6.29 9.24-8.15l12.98-6.49C440.2 193.59 448 223.87 448 256c0 8.67-.74 17.16-1.82 25.54L417 274.25z"
                                          class=""></path>
                                </svg>
                            </a>
                        </li>
                        <?php
                    }
                    ?>
                    <?php if (!$uab_email_disable): ?>
                        <li class="uab-icon-mail">
                            <a href="mailto:<?php esc_attr_e(get_the_author_meta('email', $user_id)); ?>">
                                <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="envelope"
                                     role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"
                                     class="svg-inline--fa fa-envelope fa-w-16">
                                    <path fill="currentColor"
                                          d="M502.3 190.8c3.9-3.1 9.7-.2 9.7 4.7V400c0 26.5-21.5 48-48 48H48c-26.5 0-48-21.5-48-48V195.6c0-5 5.7-7.8 9.7-4.7 22.4 17.4 52.1 39.5 154.1 113.6 21.1 15.4 56.7 47.8 92.2 47.6 35.7.3 72-32.8 92.3-47.6 102-74.1 131.6-96.3 154-113.7zM256 320c23.2.4 56.6-29.2 73.4-41.4 132.7-96.3 142.8-104.7 173.4-128.7 5.8-4.5 9.2-11.5 9.2-18.9v-19c0-26.5-21.5-48-48-48H48C21.5 64 0 85.5 0 112v19c0 7.4 3.4 14.3 9.2 18.9 30.6 23.9 40.7 32.4 173.4 128.7 16.8 12.2 50.2 41.8 73.4 41.4z"
                                          class=""></path>
                                </svg>
                            </a>
                        </li>
                    <?php endif; ?>
                    <?php
                }
                ?>
            </ul>
        </div>
    <?php } ?>

<?php } ?>
<?php if ($displayType == 'template-1') { ?>
    <div class="uab-abw-image-wrapper">
        <a href="<?php echo get_author_posts_url($user_id); ?>">
            <?php $uab_select_image_option = isset($uab_profile_data[0]['uab_image_select']) ? $uab_profile_data[0]['uab_image_select'] : 'uab_gravatar';
            switch ($uab_select_image_option) {
                case 'uab_upload_image':
                    if (!empty($uab_profile_data[0]['uab_upload_image_url'])) {
                        ?>
                        <!--Custom Image-->
                        <div class="uap-profile-image ">
                            <img src="<?php esc_attr_e($uab_profile_data[0]['uab_upload_image_url']); ?>"
                                 width="200">
                        </div>
                        <?php
                    } else {
                        ?>
                        <div class="uap-profile-image ">
                            <?php _e(get_avatar($user_id, 200)); ?>
                        </div>
                        <?php
                    }
                    break;
                default:
                    ?>
                    <!--Gravatar Image-->
                    <div class="uap-profile-image ">
                        <?php _e(get_avatar($user_id, 200)); ?>
                    </div>
                <?php
            } ?></a>
    </div>
    <?php if ($displayAuthorName) { ?>
        <div class="uab-abw-name-wrapper">
            <a href="<?php echo get_author_posts_url($user_id); ?>
				"><?php esc_html_e(the_author_meta('display_name', $user_id)); ?></a>
        </div>
    <?php } ?>
    <?php if ($displayAuthorDesignation) { ?>
        <?php if (isset($uab_profile_data[0]['uab_company_designation']) && !empty($uab_profile_data[0]['uab_company_designation'])) { ?>
            <div class="uab-abw-designation-wrapper">
                <?php
                esc_html_e($uab_profile_data[0]['uab_company_designation']);
                if (!empty($uab_profile_data[0]['uab_company_url'])) {
                    _e(' at', 'ultimate-author-box');
                    ?>
                    <a href="<?php esc_attr_e($uab_profile_data[0]['uab_company_url']); ?>"
                       target="<?php esc_attr_e($uab_general_settings['uab_link_target_option']); ?>" <?= $follow_var ?>><?php esc_html_e($uab_profile_data[0]['uab_company_name']); ?></a>
                    <?php
                }
                ?>
            </div>
        <?php } ?>
    <?php } ?>
    <?php if ($displayAuthorDescription) { ?>
        <div class="uab-abw-description-wrapper">
            <?php
            if ((get_the_author_meta('description', $user_id) == '' && $uab_general_settings['uab_default_bio'])) {
                esc_html_e($uab_general_settings['uab_default_message']);
            } else {
                esc_html_e(the_author_meta('description', $user_id));
            }
            ?>
        </div>
    <?php } ?>
    <?php if ($displaySocialIcons) { ?>
        <div class="uab-abw-social-wrapper">
            <ul id="uap-social-outlets-fields">
                <?php
                if (!empty($uab_social_icons)) {
                    foreach ($uab_social_icons as $socialname => $innerarray) {
                        if (!empty($uab_social_icons[$socialname]['url'])) {
                            $uab_font_type = 'fab';
                            if ($uab_social_icons[$socialname]['icon'] == 'rss') {
                                $uab_font_type = 'fas';
                            }
                            ?>
                            <li>
                                <a href="<?php esc_attr_e($uab_social_icons[$socialname]['url']); ?>"
                                   target="<?php esc_attr_e($uab_general_settings['uab_link_target_option']); ?>"
                                   rel="nofollow noopener">
                                    <i class="<?php esc_attr_e($uab_font_type); ?> fa-<?php esc_attr_e($uab_social_icons[$socialname]['icon']); ?>"></i>
                                </a>
                            </li>
                            <?php
                        }
                    }
                }

                if ($displayContacts) {
                    $author_phone = isset($uab_profile_data[0]['uab_company_phone']) ? $uab_profile_data[0]['uab_company_phone'] : '';
                    $author_url = get_the_author_meta('url', $user_id);
                    $uab_email_disable = isset($uab_profile_data[0]['uab-email-disable']) ? 1 : 0;
                    if (!empty($author_url)) {
                        ?>
                        <li class="uab-icon-web">
                            <a href="<?php esc_attr_e(get_the_author_meta('url', $user_id)); ?>"
                               target="<?php esc_attr_e($uab_general_settings['uab_link_target_option']); ?>">
                                <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="globe-americas"
                                     role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 496 512"
                                     class="svg-inline--fa fa-globe-americas fa-w-16 ">
                                    <path fill="currentColor"
                                          d="M248 8C111.03 8 0 119.03 0 256s111.03 248 248 248 248-111.03 248-248S384.97 8 248 8zm82.29 357.6c-3.9 3.88-7.99 7.95-11.31 11.28-2.99 3-5.1 6.7-6.17 10.71-1.51 5.66-2.73 11.38-4.77 16.87l-17.39 46.85c-13.76 3-28 4.69-42.65 4.69v-27.38c1.69-12.62-7.64-36.26-22.63-51.25-6-6-9.37-14.14-9.37-22.63v-32.01c0-11.64-6.27-22.34-16.46-27.97-14.37-7.95-34.81-19.06-48.81-26.11-11.48-5.78-22.1-13.14-31.65-21.75l-.8-.72a114.792 114.792 0 0 1-18.06-20.74c-9.38-13.77-24.66-36.42-34.59-51.14 20.47-45.5 57.36-82.04 103.2-101.89l24.01 12.01C203.48 89.74 216 82.01 216 70.11v-11.3c7.99-1.29 16.12-2.11 24.39-2.42l28.3 28.3c6.25 6.25 6.25 16.38 0 22.63L264 112l-10.34 10.34c-3.12 3.12-3.12 8.19 0 11.31l4.69 4.69c3.12 3.12 3.12 8.19 0 11.31l-8 8a8.008 8.008 0 0 1-5.66 2.34h-8.99c-2.08 0-4.08.81-5.58 2.27l-9.92 9.65a8.008 8.008 0 0 0-1.58 9.31l15.59 31.19c2.66 5.32-1.21 11.58-7.15 11.58h-5.64c-1.93 0-3.79-.7-5.24-1.96l-9.28-8.06a16.017 16.017 0 0 0-15.55-3.1l-31.17 10.39a11.95 11.95 0 0 0-8.17 11.34c0 4.53 2.56 8.66 6.61 10.69l11.08 5.54c9.41 4.71 19.79 7.16 30.31 7.16s22.59 27.29 32 32h66.75c8.49 0 16.62 3.37 22.63 9.37l13.69 13.69a30.503 30.503 0 0 1 8.93 21.57 46.536 46.536 0 0 1-13.72 32.98zM417 274.25c-5.79-1.45-10.84-5-14.15-9.97l-17.98-26.97a23.97 23.97 0 0 1 0-26.62l19.59-29.38c2.32-3.47 5.5-6.29 9.24-8.15l12.98-6.49C440.2 193.59 448 223.87 448 256c0 8.67-.74 17.16-1.82 25.54L417 274.25z"
                                          class=""></path>
                                </svg>
                            </a>
                        </li>
                        <?php
                    }
                    ?>
                    <?php if (!$uab_email_disable): ?>
                        <li class="uab-icon-mail">
                            <a href="mailto:<?php esc_attr_e(get_the_author_meta('email', $user_id)); ?>">
                                <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="envelope"
                                     role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"
                                     class="svg-inline--fa fa-envelope fa-w-16">
                                    <path fill="currentColor"
                                          d="M502.3 190.8c3.9-3.1 9.7-.2 9.7 4.7V400c0 26.5-21.5 48-48 48H48c-26.5 0-48-21.5-48-48V195.6c0-5 5.7-7.8 9.7-4.7 22.4 17.4 52.1 39.5 154.1 113.6 21.1 15.4 56.7 47.8 92.2 47.6 35.7.3 72-32.8 92.3-47.6 102-74.1 131.6-96.3 154-113.7zM256 320c23.2.4 56.6-29.2 73.4-41.4 132.7-96.3 142.8-104.7 173.4-128.7 5.8-4.5 9.2-11.5 9.2-18.9v-19c0-26.5-21.5-48-48-48H48C21.5 64 0 85.5 0 112v19c0 7.4 3.4 14.3 9.2 18.9 30.6 23.9 40.7 32.4 173.4 128.7 16.8 12.2 50.2 41.8 73.4 41.4z"
                                          class=""></path>
                                </svg>
                            </a>
                        </li>
                    <?php endif;
                }
                ?>
            </ul>
        </div>
    <?php } ?>
<?php } ?>
</div>
