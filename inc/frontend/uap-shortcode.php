<?php
defined('ABSPATH') or die('No script kiddies please!');
$uab_general_settings = get_option('uap_general_settings');
$uab_shortcode_atts = shortcode_atts(array(
    'user_id' => get_the_author_meta('ID'),
    'template' => isset($uab_general_settings['uab_template']) ? $uab_general_settings['uab_template'] : 'uab-template-1',
    'anchor_box' => isset($atts['anchor_box']) ? $atts['anchor_box'] : boolval(0),
    'anchor_timeout' => isset($atts['anchor_timeout']) ? $atts['anchor_timeout'] : intval(1000)
), $atts);

$anchor_box = (isset($uab_shortcode_atts['anchor_box']) && (($uab_shortcode_atts['anchor_box'] == 1) || ($uab_shortcode_atts['anchor_box'] == true) || ($uab_shortcode_atts['anchor_box'] == 'yes'))) ? boolval(1) : boolval(0);
$anchor_timeout = (isset($anchor_box) && isset($uab_shortcode_atts['anchor_timeout'])) ? intval($uab_shortcode_atts['anchor_timeout']) : intval(1000);

$uab_shortcode_atts['template'] = isset($uab_general_settings['uab_template']) ? $uab_general_settings['uab_template'] : 'uab-template-1';
$uab_custom_template = $uab_general_settings['uab_custom_template'];
//$uab_coauthor_header_text = $uab_general_settings['uab_coauthor_header_text'];
$uab_template_type = isset($atts['template']) ? $atts['template'] : $uab_general_settings['uab_template'];
$author_id = $uab_shortcode_atts['user_id'];
$author_description = get_the_author_meta('description', $author_id);

$uab_profile_data = maybe_unserialize(get_the_author_meta('uab_profile_data', $author_id));

if (isset($uab_profile_data[0]['uab_custom_description_status'])) {
    $allowed_html = wp_kses_allowed_html('post');
    $uab_custom_description = get_the_author_meta('uab_custom_description', $author_id);
    $author_description = !empty($uab_custom_description) ? wp_kses($uab_custom_description, $allowed_html) : '';
}

$uab_random_identifier = (isset($uab_profile_data[0]['uab_random_identifier']) && !empty($uab_profile_data[0]['uab_random_identifier'])) ? esc_attr('uab_rid_' . $uab_profile_data[0]['uab_random_identifier']) : '';

$uab_social_icons = maybe_unserialize(get_the_author_meta('uab_social_icons', $author_id));
$uab_wysiwyg_content = maybe_unserialize(get_the_author_meta('uab_wysiwyg_content', $author_id));
$uab_company_content = maybe_unserialize(get_the_author_meta('uab_company_content', $author_id));
$uab_wysiwyg_content = maybe_unserialize(get_the_author_meta('uab_wysiwyg_content', $author_id));
$uab_shortcode = maybe_unserialize(get_the_author_meta('uab_shortcode', $author_id));
$uab_contact_shortcode = maybe_unserialize(get_the_author_meta('uab_contact_shortcode', $author_id));
$uab_access_roles = $uab_general_settings['uab_user_roles'];

$uab_co_author_string = get_post_meta(get_the_ID(), 'uab_meta_co_author');
if (isset($uab_co_author_string) && !empty($uab_co_author_string)) {
    $uab_co_author = maybe_unserialize($uab_co_author_string[0]);
}

$uab_current_user_roles = new WP_User($author_id);

if (!empty($uab_current_user_roles->roles) && is_array($uab_current_user_roles->roles)) {
    foreach ($uab_current_user_roles->roles as $role)
        $uab_current_user_role = $role;
}

if (isset($uab_social_icons) && !empty($uab_social_icons)) {
    $error_flag = "1";
    foreach ($uab_social_icons as $key => $val) {
        if (!empty($val['url']))
            $error_flag = "0";
    }
} else {
    $error_flag = "1";
}


?>
<div id="<?php echo $uab_random_identifier; ?>"
     class="uab-frontend-inner-layer uab-frontend-wrapper-author-<?= intval($author_id) ?> <?= (isset($anchor_box) && !empty($anchor_box)) ? esc_attr('uab_anchor_box') : '' ?>" <?= isset($anchor_box) ? ('data-timeout="' . intval($anchor_timeout) . '"') : intval(1000) ?>>
    <?php

    if ($uab_general_settings['uab_disable_uab']) {
        //echo 'Disable author box';
    } else {
        if ($uab_general_settings['uab_empty_bio'] && $author_description == '') {
            //echo 'The Author Box Will not show if the author bio is empty';
        } else {
            if (in_array($uab_current_user_role, $uab_access_roles) || !empty($uab_profile_data)) {
                ?>
                <div id="uab-frontend-wrapper" class="uab-frontend-wrapper uab-template-1">

                    <div class="uab-tab-content">
                        <div class="uab-defaut-tab uab-clearfix">
                            <?php
                            include(UAB_PATH . '/inc/frontend/frontend-default/uab-default.php');
                            ?>
                        </div>

                    </div>
                </div>

                <?php
            }
        }
    }
    ?>
</div>
