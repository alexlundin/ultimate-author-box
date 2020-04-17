<?php defined('ABSPATH') or die('No script kiddies please!'); ?>
<div class="uab-display-name">
    <!-- User Display Name -->
    <?php if (isset($uab_profile_data[0]['uab-author-url-disable'])): ?>
        <span><?php esc_html_e(the_author_meta('display_name', $author_id)); ?></span>
    <?php else: ?>
        <a href="<?php esc_attr_e(get_author_posts_url($author_id)); ?>"
           target="<?php esc_attr_e($uab_general_settings['uab_link_target_option']); ?>"><?php esc_html_e(the_author_meta('display_name', $author_id)); ?></a>
    <?php endif ?>

    <?php
    if ($uab_template_type == 'uab-template-1' || $uab_template_type == 'uab-template-12') {
        $user_meta = get_userdata($author_id);
        $user_roles = $user_meta->roles;

        $user_role_lists = $this->get_editable_roles();

        foreach ($user_role_lists as $user_role_list => $value) {
            foreach ($user_roles as $role => $val) {
                if ($user_role_list == $val) {
                    ?><span
                    class="uab-user-role uab-role-<?php _e($user_role_lists[$user_role_list]['name']); ?>"><?php esc_html_e($user_role_lists[$user_role_list]['name'], 'ultimate-author-box'); ?></span><?php
                }
            }
        }
    } ?>
</div>

