<?php defined('ABSPATH') or die('No script kiddies please!');
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

<div class="uab-social-icons">

    <?php if (!empty($uab_social_icons)): ?>
        <span class="uab-contact-label"><?php esc_html_e('follow me', 'ultimate-author-box'); ?></span>
    <?php endif; ?>

    <ul id="uap-social-outlets-fields">
        <?php
        $uab_social_icons = maybe_unserialize(get_the_author_meta('uab_social_icons', $author_id));
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
