<?php
defined('ABSPATH') or die('No script kiddies please!');
/*
  Plugin Name: Ultimate Author Box Lite by Alex Lundin
  Plugin URI: https://alexlundin.com/wp-update-server?action=get_metadata&slug=ultimate-author-box
  Description: Ultimate Author Box is a plugin that allows you to add additional information about the author in your Post, Page Custom Post Type as a default option or through use of shortcode and widgets.
  Version: 2.1
  Author: Alex Lundin
  Author URI: https://alexlundin.com
  License: GPL2 or later
  License URI: https://www.gnu.org/licenses/gpl-2.0.html
  Domain Path: /languages/
  Text Domain: ultimate-author-box
 */
/* include File for Widget */

include_once('inc/backend/uab-widgets/uab-author-box-widgets.php');

require 'plugin-update-checker/plugin-update-checker.php';
$MyUpdateChecker = Puc_v4_Factory::buildUpdateChecker(
    'https://alexlundin.com/wp-update-server?action=get_metadata&slug=ultimate-author-box', //Metadata URL.
    __FILE__, //Full path to the main plugin file.
    'ultimate-author-box' //Plugin slug. Usually it's the same as the name of the directory.
);

/* Create class Ultimate_Author_Box */
if (!class_exists('Ultimate_Author_Box')) {

    class Ultimate_Author_Box
    {
        /* Construtor to load all hooks */

        function __construct()
        {

            /* Define Folder Paths */
            $this->define_constants();
            /* Start Session for Facebook API and Define Text Domain */
            add_action('init', array($this, 'uab_init'));
            /* Enqueue Backend Scripts */
            add_action('admin_enqueue_scripts', array($this, 'uab_register_backend_assets'));
            /* Enqueue Frontend Scripts */
            add_action('wp_enqueue_scripts', array($this, 'uab_register_frontend_assets'));
            /* Register Ultimate Author Box Dashboard Menu */
            add_action('admin_menu', array($this, 'uab_menu'));

            add_action('show_user_profile', array($this, 'uab_profile_fields'));
            add_action('edit_user_profile', array($this, 'uab_profile_fields'));
            /* add_action( 'wp_ajax_save_tab_option', array( $this, 'uab_profile_fields' ) ); */
            add_action('personal_options_update', array($this, 'uab_save_profile_fields'));
            add_action('edit_user_profile_update', array($this, 'uab_save_profile_fields'));
            /* General Settings Save */
            add_action('admin_post_uab_settings_save_action', array($this, 'uab_save_settings'));


            add_shortcode('ultimate_author_box', array($this, 'ultimate_author_box'));
            add_shortcode('ultimate_author_box_widget', array($this, 'ultimate_author_box_widget'));

            add_filter('the_content', array($this, 'uab_add_post_content'), 0);

            /* Register Widgets */
            add_action('widgets_init', array($this, 'register_uap_author_box_widget'));
            /* Register Meta Box */
            add_action('add_meta_boxes', array($this, 'uab_metabox'));
            add_action('save_post', array($this, 'uab_meta_save'));
            add_action('save_post', array($this, 'uab_guest_save'));

            /* add selected widgets on div section using ajax */
            add_action('wp_ajax_add_selected_widget', array($this, 'add_selected_widget'));
            /* edit widget data of specific widgets */
            add_action('wp_ajax_edit_widget_data', array($this, 'ajax_edit_widget_data'));
            /* edit widget data of specific widgets */
            add_action('wp_ajax_uab_delete_widget', array($this, 'ajax_delete_widget_form'));
            /* save widgets data */
            add_action('wp_ajax_uab_save_widget', array($this, 'ajax_save_widget'));
            add_action('after_widget_add', array($this, 'clear_caches'));
            add_action('after_widget_save', array($this, 'clear_caches'));
            add_action('after_widget_delete', array($this, 'clear_caches'));
            /* disable WordPress sanitization to allow more than just $allowedtags from /wp-includes/kses.php */
            remove_filter('pre_user_description', 'wp_filter_kses');
            /* add sanitization for WordPress posts */
            add_filter('pre_user_description', 'wp_filter_post_kses');
        }

        /* Get all editable roles */

        function get_editable_roles()
        {
            global $wp_roles;
            $all_roles = $wp_roles->roles;
            return $all_roles;
        }


        /* Register UAB_Author_box_widget */

        function register_uap_author_box_widget()
        {
            register_widget('uab_author_box_widget');
        }

        /* Define Folder Paths */

        function define_constants()
        {
            defined('UAB_CSS_DIR') or define('UAB_CSS_DIR', plugin_dir_url(__FILE__) . 'css');
            defined('UAB_JS_DIR') or define('UAB_JS_DIR', plugin_dir_url(__FILE__) . 'js');
            defined('UAB_IMG_DIR') or define('UAB_IMG_DIR', plugin_dir_url(__FILE__) . 'images');
            defined('UAB_PATH') or define('UAB_PATH', plugin_dir_path(__FILE__));
            defined('UAB_VERSION') or define('UAB_VERSION', '2.1');


        }

        /* Register Text Domain */

        function uab_init()
        {

            if (!session_id() && !headers_sent()) {
                session_start();
            }
            load_plugin_textdomain('ultimate-author-box', false, dirname(plugin_basename(__FILE__)) . '/languages');
        }

        /* Register Backend resources (Enqueue scripts and style) */


        function uab_register_backend_assets($hook)
        {

            if (!('toplevel_page_ultimate-author-box' == $hook || 'profile.php' == $hook || 'post-new.php' == $hook || 'post.php' == $hook || 'user-edit.php' == $hook || 'ultimate-author-box_page_ultimate-author-box-how-to' == $hook || 'ultimate-author-box_page_ultimate-author-box-about' == $hook)) {
                return;
            }
            wp_enqueue_style('jquery-ui-css', UAB_CSS_DIR . '/jquery-ui.css', array(), '1.12.1');

            wp_enqueue_style('ultimate-author-box-backend-style', UAB_CSS_DIR . '/backend.css', array(), UAB_VERSION);

            wp_enqueue_script('jquery-ui', UAB_JS_DIR . '/jquery-ui.js', array('jquery'), '1.12.1');

            wp_enqueue_script('uab-backend-script', UAB_JS_DIR . '/backend.js', array('jquery', 'jquery-ui', 'jquery-ui-dialog'), UAB_VERSION);
            wp_enqueue_media();
            wp_localize_script('uab-backend-script', 'uab_variable', array(
                'plugin_javascript_path' => UAB_JS_DIR,
                'plugin_image_path' => UAB_IMG_DIR,
                'ajax_url' => admin_url('admin-ajax.php'),
                '_wpnonce' => wp_create_nonce('uab_form_nonce'),
                'selected_widget_limits' => __('Only 1 widget allowed per tab', 'ultimate-author-box'),
                'saving_msg' => __('Saving Data.', 'ultimate-author-box'),
                'saved_msg' => __('Saved Data.', 'ultimate-author-box'),
            ));
        }

        /* Register Frontend resources (Enqueue scripts and style) */

        function uab_register_frontend_assets()
        {
            wp_enqueue_style('uab-frontend-style', UAB_CSS_DIR . '/frontend.css', array(), UAB_VERSION);
            wp_enqueue_style('uab-frontend-responsive-style', UAB_CSS_DIR . '/uab-responsive.css', array(), UAB_VERSION);
        }


        /* Registering Plugin access through Dashboard */

        function uab_menu()
        {
            add_menu_page(
                __('Ultimate Author Box', 'ultimate-author-box'), __('Ultimate Author Box', 'ultimate-author-box'), 'manage_options', 'ultimate-author-box', array($this, 'uab_settings_page'), 'dashicons-id', 70
            );
        }

        /* Registering Plugin backend settings */

        function uab_settings_page()
        {
            include(UAB_PATH . '/inc/backend/uap-settings.php');
        }

        /* Save General Settings */

        function uab_save_settings()
        {
            if (check_admin_referer('uab_admin_option_update')) {
                if (isset($_POST['uab_settings_save_button'])) {
                    $uab_general_settings = array();

                    $uab_general_settings['uab_user_roles'] = array();


                    if (isset($_POST['uab_user_roles'])) {
                        foreach ($_POST['uab_user_roles'] as $key => $value) {
                            $uab_general_settings['uab_user_roles'][$key] = $value;
                        }
                    } else {
                        $uab_general_settings['uab_user_roles'] = array();
                    }

                    $uab_general_settings['uab_disable_uab'] = (isset($_POST['uab_disable_uab']) ? 1 : 0);
                    $uab_general_settings['uab_posts'] = (isset($_POST['uab_posts']) ? 1 : 0);
                    $uab_general_settings['uab_pages'] = (isset($_POST['uab_pages']) ? 1 : 0);
                    $uab_general_settings['uab_box_position'] = sanitize_text_field($_POST['uab_box_position']);
                    $uab_general_settings['uab_empty_bio'] = (isset($_POST['uab_empty_bio']) ? 1 : 0);
                    $uab_general_settings['uab_default_bio'] = (isset($_POST['uab_default_bio']) ? 1 : 0);
                    $uab_general_settings['uab_default_message'] = sanitize_text_field($_POST['uab_default_message']);
                    $uab_general_settings['uab_link_target_option'] = sanitize_text_field($_POST['uab_link_target_option']);

                    $uab_general_settings['uab_disable_email'] = (isset($_POST['uab_disable_email']) ? 1 : 0);


                    $uab_general_settings['uab_template'] = sanitize_text_field($_POST['uab_template']);

                    $uab_general_settings['uab_custom_template'] = sanitize_text_field($_POST['uab_custom_template']);

                    $check = update_option('uap_general_settings', $uab_general_settings);
                    wp_redirect(admin_url('admin.php?page=ultimate-author-box&message=1'));
                    exit;

                }
            } else {
                die('No script kiddies please!');
            }
        }

        /* Settings Default Values */

        function get_default_settings()
        {
            $uab_general_settings = array();
            $user_role_list = $this->get_editable_roles();
            foreach ($user_role_list as $key => $value) {
                $uab_general_settings['uab_user_roles'][] = $key;
            }
            $uab_general_settings['uab_disable_uab'] = 0;
            $uab_general_settings['uab_posts'] = 1;
            $uab_general_settings['uab_pages'] = 1;
            $uab_general_settings['uab_box_position'] = 'uab_bottom';
            $uab_general_settings['uab_empty_bio'] = 0;
            $uab_general_settings['uab_default_bio'] = 1;
            $uab_general_settings['uab_default_message'] = __('Sorry! The Author has not filled his profile.', 'ultimate-author-box');
            $uab_general_settings['uab_link_target_option'] = '_blank';

            $uab_general_settings['uab_template'] = 'uab-template-1';
            $uab_general_settings['uab_custom_template'] = 'uab-template-1';
            return $uab_general_settings;
        }


        /* Register Shortcode [ultimate_author_box user_id="1" template='uab-template-1'] */

        function ultimate_author_box($atts)
        {
            ob_start();
            ?>
            <div class="uab-frontend-wrapper-outer">
                <?php
                include(UAB_PATH . '/inc/frontend/uap-shortcode.php');
                ?>
            </div>
            <?php
            $form_html = ob_get_contents();
            ob_end_clean();
            return $form_html;
        }

        function ultimate_author_box_widget($atts)
        {
            ob_start();
            include(UAB_PATH . '/inc/frontend/uap-author-box-widget-shortcode.php');
            $form_html = ob_get_contents();
            ob_end_clean();
            return $form_html;
        }

        /* Add Author Box To post content */

        function uab_add_post_content($content)
        {
            $uab_general_settings = get_option('uap_general_settings');
            $post_id = get_the_ID();
            $author = get_the_author_meta('ID');
            $uab_stored_meta = (get_post_meta($post_id, 'uab_option') !== NULL) ? get_post_meta($post_id, 'uab_option') : array();
            $uab_stored_meta_position = (get_post_meta($post_id, 'uab_meta_position') !== NULL) ? get_post_meta($post_id, 'uab_meta_position') : array();
            $uab_stored_meta_value = (isset($uab_stored_meta[0]) && !empty($uab_stored_meta[0])) ? $uab_stored_meta[0] : 'yes';
            $uab_stored_meta_value_position = (isset($uab_stored_meta_position[0]) && !empty($uab_stored_meta[0])) ? $uab_stored_meta_position[0] : 'default';

            $postIDfield = '';
            if (is_singular('post')) {
                $postID = get_the_ID();
                $postIDfield .= '<input type="hidden" value="' . $postID . '">';
                $count_key = 'post_views_count';
                $count = get_post_meta($postID, $count_key, true);
                if ($count == '') {
                    $count = 0;
                    delete_post_meta($postID, $count_key);
                    add_post_meta($postID, $count_key, '0');
                } else {
                    $count++;
                    update_post_meta($postID, $count_key, $count);
                }
            }
            if ($uab_general_settings['uab_posts'] && $uab_stored_meta_value == 'yes') {
                if (is_singular('post') && is_main_query() && in_the_loop()) {
                    if ($uab_stored_meta_value_position != 'default') {
                        $check_position = $uab_stored_meta_value_position;
                    } else {
                        $check_position = $uab_general_settings['uab_box_position'];
                    }
                    switch ($check_position) {
                        case 'uab_top':
                            remove_filter('the_content', 'wpautop');
                            $content = $postIDfield . do_shortcode('[ultimate_author_box]') . wpautop($content);
                            break;
                        case 'uab_bottom':
                            remove_filter('the_content', 'wpautop');
                            $content = wpautop($content) . $postIDfield;
                            $content .= do_shortcode('[ultimate_author_box]');
                            break;
                        default:
                            return $content;
                    }
                } elseif (is_singular('post')) {
                    if ($uab_stored_meta_value_position != 'default') {
                        $check_position = $uab_stored_meta_value_position;
                    } else {
                        $check_position = $uab_general_settings['uab_box_position'];
                    }
                    switch ($check_position) {
                        case 'uab_top':
                            remove_filter('the_content', 'wpautop');
                            $content = $postIDfield . do_shortcode('[ultimate_author_box]') . wpautop($content);
                            break;
                        case 'uab_bottom':
                            remove_filter('the_content', 'wpautop');
                            $content = wpautop($content) . $postIDfield;
                            $content .= do_shortcode('[ultimate_author_box]');
                            break;
                        default:
                            return $content;
                    }
                }
            }
            if ($uab_general_settings['uab_pages'] && $uab_stored_meta_value == 'yes') {
                if (is_singular('page')) {
                    if ($uab_stored_meta_value_position != 'default') {
                        $check_position = $uab_stored_meta_value_position;
                    } else {
                        $check_position = $uab_general_settings['uab_box_position'];
                    }
                    switch ($check_position) {
                        case 'uab_top':
                            remove_filter('the_content', 'wpautop');
                            $content = $postIDfield . do_shortcode('[ultimate_author_box]') . wpautop($content);
                            break;
                        case 'uab_bottom':
                            remove_filter('the_content', 'wpautop');
                            $content = wpautop($content) . $postIDfield;
                            $content .= do_shortcode('[ultimate_author_box]');
                            break;
                        default:
                            return $content;
                    }
                }
            }
            return $content;
        }

        /* Print function to Print Array */

        function print_array($array)
        {
            echo "<pre>";
            print_r($array);
            echo "</pre>";
        }

        /* Twitter Feed Request */

        function get_oauth_connection($cons_key, $cons_secret, $oauth_token, $oauth_token_secret)
        {
            $ai_connection = new TwitterOAuth($cons_key, $cons_secret, $oauth_token, $oauth_token_secret);
            return $ai_connection;
        }

        /* Callback funtion to Add Content to Profile.php */

        function uab_profile_fields($user)
        {
            if (!current_user_can('edit_posts', $user->ID))
                return false;

            $uab_current_user = get_current_user_id();

            $uab_current_user_roles = new WP_User($uab_current_user);

            if (!empty($uab_current_user_roles->roles) && is_array($uab_current_user_roles->roles)) {
                foreach ($uab_current_user_roles->roles as $role)
                    $uab_current_user_role[] = $role;
            }



            /* $this->print_array($uab_current_user_role); */
            /* $this->print_array($uab_general_settings['uab_user_roles']); */
            $user_permission_flag = 0;
            foreach ($uab_current_user_role as $user_role) {
                /* echo $user_role; */
                if (in_array($user_role, $uab_general_settings['uab_user_roles']) || $user_role == 'administrator') {

                    // Added version silver
                    $uab_target_ranking = get_the_author_meta('uab_frontend_target_ranking', $user->ID);
                    $uab_target_ranking = ($uab_target_ranking != '') ? intval($uab_target_ranking) : '';

                    $unserialized_uab_profile_data = maybe_unserialize(get_the_author_meta('uab_profile_data', $user->ID));
                    /* $uab_key_set = get_the_author_meta( 'wpKeySet', $user->ID ); */
                    /* echo 'keys:'.$uab_key_set; */
                    $uab_social_data = maybe_unserialize(get_the_author_meta('uab_social_icons', $user->ID));
                    /* $this->print_array($uab_social_data); */
                    if (!empty($uab_social_data)) {
                        $uab_social_icons = maybe_unserialize(get_the_author_meta('uab_social_icons', $user->ID));
                        $uab_social_icons = array(
                            'facebook' => array(
                                'icon' => 'facebook',
                                'label' => 'Facebook',
                                'url' => isset($uab_social_icons['facebook']['url']) ? esc_url($uab_social_icons['facebook']['url']) : ''
                            ),
                            'twitter' => array(
                                'icon' => 'twitter',
                                'label' => 'Twitter',
                                'url' => isset($uab_social_icons['twitter']['url']) ? esc_url($uab_social_icons['twitter']['url']) : ''
                            ),
                            'instagram' => array(
                                'icon' => 'instagram',
                                'label' => 'Instagram',
                                'url' => isset($uab_social_icons['instagram']['url']) ? esc_url($uab_social_icons['instagram']['url']) : ''
                            ),
                            'youtube' => array(
                                'icon' => 'youtube',
                                'label' => 'Youtube',
                                'url' => isset($uab_social_icons['youtube']['url']) ? esc_url($uab_social_icons['youtube']['url']) : ''
                            ),
                            'linkedin' => array(
                                'icon' => 'linkedin',
                                'label' => 'Linkedin',
                                'url' => isset($uab_social_icons['linkedin']['url']) ? esc_url($uab_social_icons['linkedin']['url']) : ''
                            ),
                            'pinterest' => array(
                                'icon' => 'pinterest',
                                'label' => 'Pinterest',
                                'url' => isset($uab_social_icons['pinterest']['url']) ? esc_url($uab_social_icons['pinterest']['url']) : ''
                            ),
                            'google-plus' => array(
                                'icon' => 'google-plus',
                                'label' => 'Google+',
                                'url' => isset($uab_social_icons['google-plus']['url']) ? esc_url($uab_social_icons['google-plus']['url']) : ''
                            ),
                            'tumblr' => array(
                                'icon' => 'tumblr',
                                'label' => 'Tumblr.',
                                'url' => isset($uab_social_icons['tumblr']['url']) ? esc_url($uab_social_icons['tumblr']['url']) : ''
                            ),
                            'reddit' => array(
                                'icon' => 'reddit',
                                'label' => 'Reddit',
                                'url' => isset($uab_social_icons['reddit']['url']) ? esc_url($uab_social_icons['reddit']['url']) : ''
                            ),
                            'flickr' => array(
                                'icon' => 'flickr',
                                'label' => 'Flickr',
                                'url' => isset($uab_social_icons['flickr']['url']) ? esc_url($uab_social_icons['flickr']['url']) : ''
                            ),
                            'vine' => array(
                                'icon' => 'vine',
                                'label' => 'Vine',
                                'url' => isset($uab_social_icons['vine']['url']) ? esc_url($uab_social_icons['vine']['url']) : ''
                            ),
                            'meetup' => array(
                                'icon' => 'meetup',
                                'label' => 'Meetup',
                                'url' => isset($uab_social_icons['meetup']['url']) ? esc_url($uab_social_icons['meetup']['url']) : ''
                            ),
                            'github' => array(
                                'icon' => 'github',
                                'label' => 'Github',
                                'url' => isset($uab_social_icons['github']['url']) ? esc_url($uab_social_icons['github']['url']) : ''
                            ),
                            'soundcloud' => array(
                                'icon' => 'soundcloud',
                                'label' => 'Soundcloud',
                                'url' => isset($uab_social_icons['soundcloud']['url']) ? esc_url($uab_social_icons['soundcloud']['url']) : ''
                            ),
                            'steam' => array(
                                'icon' => 'steam',
                                'label' => 'Steam',
                                'url' => isset($uab_social_icons['steam']['url']) ? esc_url($uab_social_icons['steam']['url']) : ''
                            ),
                            'vimeo' => array(
                                'icon' => 'vimeo',
                                'label' => 'Vimeo',
                                'url' => isset($uab_social_icons['vimeo']['url']) ? esc_url($uab_social_icons['vimeo']['url']) : ''
                            ),
                            'wordpress' => array(
                                'icon' => 'wordpress',
                                'label' => 'WordPress',
                                'url' => isset($uab_social_icons['wordpress']['url']) ? esc_url($uab_social_icons['wordpress']['url']) : ''
                            ),
                            'telegram' => array(
                                'icon' => 'telegram',
                                'label' => 'Telegram',
                                'url' => isset($uab_social_icons['telegram']['url']) ? esc_url($uab_social_icons['telegram']['url']) : ''
                            ),
                            'spotify' => array(
                                'icon' => 'spotify',
                                'label' => 'Spotify',
                                'url' => isset($uab_social_icons['spotify']['url']) ? esc_url($uab_social_icons['spotify']['url']) : ''
                            ),
                            'snapchat' => array(
                                'icon' => 'snapchat',
                                'label' => 'Snapchat',
                                'url' => isset($uab_social_icons['snapchat']['url']) ? esc_url($uab_social_icons['snapchat']['url']) : ''
                            ),
                            'skype' => array(
                                'icon' => 'skype',
                                'label' => 'Skype',
                                'url' => isset($uab_social_icons['skype']['url']) ? esc_url($uab_social_icons['skype']['url']) : ''
                            ),
                            'whatsapp' => array(
                                'icon' => 'whatsapp',
                                'label' => 'Whatsapp',
                                'url' => isset($uab_social_icons['whatsapp']['url']) ? esc_url($uab_social_icons['whatsapp']['url']) : ''
                            ),
                            'dribbble' => array(
                                'icon' => 'dribbble',
                                'label' => 'Dribbble',
                                'url' => isset($uab_social_icons['dribbble']['url']) ? esc_url($uab_social_icons['dribbble']['url']) : ''
                            ),
                            'rss' => array(
                                'icon' => 'rss',
                                'label' => 'RSS',
                                'url' => isset($uab_social_icons['rss']['url']) ? esc_url($uab_social_icons['rss']['url']) : ''
                            ),
                            'quora' => array(
                                'icon' => 'quora',
                                'label' => 'Quora',
                                'url' => isset($uab_social_icons['quora']['url']) ? esc_url($uab_social_icons['quora']['url']) : ''
                            ),
                            'blogger' => array(
                                'icon' => 'blogger',
                                'label' => 'Blogger',
                                'url' => isset($uab_social_icons['blogger']['url']) ? esc_url($uab_social_icons['blogger']['url']) : ''
                            ),
                            'odnoklassniki' => array(
                                'icon' => 'odnoklassniki',
                                'label' => 'Odnoklassniki',
                                'url' => isset($uab_social_icons['odnoklassniki']['url']) ? esc_url($uab_social_icons['odnoklassniki']['url']) : ''
                            ),
                            'vk' => array(
                                'icon' => 'vk',
                                'label' => 'Vkontakte',
                                'url' => isset($uab_social_icons['vk']['url']) ? esc_url($uab_social_icons['vk']['url']) : ''
                            ),
                        );
                    } else {
                        $uab_social_icons = array(
                            'facebook' => array(
                                'icon' => 'facebook',
                                'label' => 'Facebook',
                                'url' => ''
                            ),
                            'twitter' => array(
                                'icon' => 'twitter',
                                'label' => 'Twitter',
                                'url' => ''
                            ),
                            'instagram' => array(
                                'icon' => 'instagram',
                                'label' => 'Instagram',
                                'url' => ''
                            ),
                            'youtube' => array(
                                'icon' => 'youtube',
                                'label' => 'Youtube',
                                'url' => ''
                            ),
                            'linkedin' => array(
                                'icon' => 'linkedin',
                                'label' => 'Linkedin',
                                'url' => ''
                            ),
                            'pinterest' => array(
                                'icon' => 'pinterest',
                                'label' => 'Pinterest',
                                'url' => ''
                            ),
                            'google-plus' => array(
                                'icon' => 'google-plus',
                                'label' => 'Google+',
                                'url' => ''
                            ),
                            'tumblr' => array(
                                'icon' => 'tumblr',
                                'label' => 'Tumblr.',
                                'url' => ''
                            ),
                            'reddit' => array(
                                'icon' => 'reddit',
                                'label' => 'Reddit',
                                'url' => ''
                            ),
                            'flickr' => array(
                                'icon' => 'flickr',
                                'label' => 'Flickr',
                                'url' => ''
                            ),
                            'vine' => array(
                                'icon' => 'vine',
                                'label' => 'Vine',
                                'url' => ''
                            ),
                            'meetup' => array(
                                'icon' => 'meetup',
                                'label' => 'Meetup',
                                'url' => ''
                            ),
                            'github' => array(
                                'icon' => 'github',
                                'label' => 'Github',
                                'url' => ''
                            ),
                            'soundcloud' => array(
                                'icon' => 'soundcloud',
                                'label' => 'Soundcloud',
                                'url' => ''
                            ),
                            'steam' => array(
                                'icon' => 'steam',
                                'label' => 'Steam',
                                'url' => ''
                            ),
                            'vimeo' => array(
                                'icon' => 'vimeo',
                                'label' => 'Vimeo',
                                'url' => ''
                            ),
                            'wordpress' => array(
                                'icon' => 'wordpress',
                                'label' => 'WordPress',
                                'url' => ''
                            ),
                            'telegram' => array(
                                'icon' => 'telegram',
                                'label' => 'Telegram',
                                'url' => ''
                            ),
                            'spotify' => array(
                                'icon' => 'spotify',
                                'label' => 'Spotify',
                                'url' => ''
                            ),
                            'snapchat' => array(
                                'icon' => 'snapchat',
                                'label' => 'Snapchat',
                                'url' => ''
                            ),
                            'skype' => array(
                                'icon' => 'skype',
                                'label' => 'Skype',
                                'url' => ''
                            ),
                            'whatsapp' => array(
                                'icon' => 'whatsapp',
                                'label' => 'Whatsapp',
                                'url' => ''
                            ),
                            'dribbble' => array(
                                'icon' => 'dribbble',
                                'label' => 'Dribbble',
                                'url' => ''
                            ),
                            'rss' => array(
                                'icon' => 'rss',
                                'label' => 'RSS',
                                'url' => ''
                            ),
                            'quora' => array(
                                'icon' => 'quora',
                                'label' => 'Quora',
                                'url' => ''
                            ),
                            'blogger' => array(
                                'icon' => 'blogger',
                                'label' => 'Blogger',
                                'url' => ''
                            ),
                            'odnoklassniki' => array(
                                'icon' => 'odnoklassniki',
                                'label' => 'Odnoklassniki',
                                'url' => ''
                            ),
                            'vk' => array(
                                'icon' => 'vk',
                                'label' => 'Vkontakte',
                                'url' => ''
                            ),
                        );
                    }
                    $uab_wysiwyg_content = maybe_unserialize(get_the_author_meta('uab_wysiwyg_content', $user->ID));


                    /* $this->print_array($uab_wysiwyg_content); */
                    $uab_company_content = maybe_unserialize(get_the_author_meta('uab_company_content', $user->ID));
                    $uab_shortcode = maybe_unserialize(get_the_author_meta('uab_shortcode', $user->ID));
                    $uab_contact_shortcode = maybe_unserialize(get_the_author_meta('uab_contact_shortcode', $user->ID));
                    /* $this->print_array($uab_contact_shortcode); */
                    /* $this->print_array($uab_wysiwyg_content); */

                    $uab_frontend_shortcode_title = get_user_meta($user->ID, 'uab_frontend_shortcode_title', true);
                    $uab_frontend_shortcode = get_user_meta($user->ID, 'uab_frontend_shortcode', true);

                    $uab_random_identifier = (isset($unserialized_uab_profile_data[0]['uab_random_identifier']) && !empty($unserialized_uab_profile_data[0]['uab_random_identifier'])) ? esc_attr($unserialized_uab_profile_data[0]['uab_random_identifier']) : str_pad(dechex(mt_rand(0, pow(16, 5))), 2, '0', STR_PAD_LEFT);

                    include(UAB_PATH . '/inc/backend/ultimate-profile-settings.php');
                    break;
                } else {
                    $user_permission_flag++;
                }
            }
            if ($user_permission_flag > 0) {
                ?>
                <div id="setting-error-bloger" class="notice notice-info is-dismissible">
                <p><strong><span
                                style="display: block; margin: 0.5em 0.5em 0 0; clear: both;"><?php esc_html_e('Note: The Ultimate Author Box is installed but you do not have the permission to configure it. Please contact the site Admin to have access to your AuthorBox.', 'ultimate-author-box'); ?></span>
                    </strong></p>
                <button type="button" class="notice-dismiss"><span
                            class="screen-reader-text">Dismiss this notice.</span></button></div><?php
            }
        }


        function return_cache_period()
        {
            /* please set the integer value in seconds */
            return 2;
        }

        /* Fetch RSS Feeds */

        function uab_get_rss_feed($feed_url, $number_of_feeds_to_show)
        {

            /* Get a rss feed object from the specified feed source. */
            add_filter('wp_feed_cache_transient_lifetime', array($this, 'return_cache_period'));
            $rss = fetch_feed($feed_url);
            remove_filter('wp_feed_cache_transient_lifetime', array($this, 'return_cache_period'));
            if (!is_wp_error($rss)) {
                /* Figure out how many total items there are, but limit it to number specified */
                $maxitems = $rss->get_item_quantity($number_of_feeds_to_show);
                $rss_items = $rss->get_items(0, $maxitems);
                return $rss_items;
            } else {
                return false;
            }
        }

        /* Register Ultimate Author Box Option Metabox */

        function uab_metabox()
        {
            $args = array(
                'public' => true,
            );
            /* names or objects, note names is the default */
            $output = 'names';
            /* 'and' or 'or' */
            $operator = 'and';

            $post_types = get_post_types($args, $output, $operator);

            $uab_general_settings = get_option('uap_general_settings');

            add_meta_box(
                'uab_meta', __('Ultimate Author Box Options'), array($this, 'uab_meta_callback'), array('post', 'page', $post_types), 'side', 'high'
            );


            $this->uab_co_author_meta();

        }

        function uab_co_author_meta()
        {
            add_meta_box('uab_guest_company_details', __('Company Details'), array($this, 'uab_guest_company_details'), array('uab_guest_author', 'side', 'high'));
            add_meta_box('uab_guest_general_details', __('Guest Details'), array($this, 'uab_guest_general_details'), array('uab_guest_author'), 'side', 'high');
            add_meta_box('uab_guest_image_details', __('Profile Image'), array($this, 'uab_guest_image_details'), array('uab_guest_author'), 'side', 'low');
            add_meta_box('uab_guest_social_icons', __('Social Icons'), array($this, 'uab_guest_social_icons'), array('uab_guest_author'));

            add_filter('gettext', array($this, 'change_publish_button'), 10, 2);
        }


        function change_publish_button($translation, $text)
        {
            if (isset($_GET['post_type']) && ($_GET['post_type'] == 'uab_guest_author')) {
                if ($text == 'Publish')
                    return 'Add Guest';
            }
            return $translation;
        }

        function uab_guest_social_icons()
        {
            $guest_details = get_post_meta(get_the_ID(), 'uab_guest_details', true);
            $guest_details_social = isset($guest_details['social']) ? $guest_details['social'] : array();
            include_once('inc/backend/guest_author/guest_social_icons.php');
        }

        function uab_guest_general_details()
        {
            $guest_details = get_post_meta(get_the_ID(), 'uab_guest_details', true);
            include_once('inc/backend/guest_author/guest_general_details.php');
        }

        function uab_guest_company_details()
        {
            $guest_details = get_post_meta(get_the_ID(), 'uab_guest_details', true);
            include_once('inc/backend/guest_author/guest_company_details.php');
        }

        function uab_guest_image_details()
        {
            $guest_details = get_post_meta(get_the_ID(), 'uab_guest_details', true);
            include_once('inc/backend/guest_author/guest_image.php');
        }

        function uab_co_author_callback($post)
        {
            if (current_user_can('manage_options')) {

                $blogusers = get_users();
                $uab_co_authors_main = get_post_meta($post->ID, 'uab_meta_co_author');
                $uab_co_authors = !empty($uab_co_authors_main[0]) ? maybe_unserialize($uab_co_authors_main[0]) : array();
                $post_author = intval($post->post_author);

                $args = array(
                    'offset' => 0,
                    'post_type' => 'uab_guest_author',
                    'post_status' => 'publish',
                    'suppress_filters' => true,
                    'numberposts' => -1,
                );
                $guest_author = get_posts($args);
                // $this->print_array($guest_author);

                // Array of WP_User objects.
                $output = "<div class='uab-co-author-wrap'>";
                foreach ($blogusers as $index => $user_obj) {
                    if ($post_author != $user_obj->data->ID) {
                        $output .= "<div class='uab-co-author-individual'>";
                        $output .= "<input type='checkbox' name='uab_co_authors[author][]' value='" . intval($user_obj->data->ID) . "' ";
                        if (isset($uab_co_authors['author']) && in_array(intval($user_obj->data->ID), $uab_co_authors['author'])) {
                            $output .= "checked";
                        }
                        $output .= "/>";
                        $output .= esc_attr($user_obj->data->display_name);
                        $output .= "</div>";
                    }
                }
                foreach ($guest_author as $index => $guest_details) {
                    $output .= "<div class='uab-co-author-individual'>";
                    $output .= "<input type='checkbox' name='uab_co_authors[guest][]' value='" . intval($guest_details->ID) . "' ";
                    if (isset($uab_co_authors['guest']) && in_array(intval($guest_details->ID), $uab_co_authors['guest'])) {
                        $output .= "checked";
                    }
                    $output .= "/>";
                    $output .= esc_attr($guest_details->post_title);
                    $output .= "</div>";
                }
                $output .= "</div>";
                if ((count($blogusers) == 1) && (count($guest_author) == 0)) {
                    $output .= '<div>';
                    $output .= esc_attr__('No other authors assigned in the site', 'ultimate-author-box');
                    $output .= '</div>';
                }
                echo $output;
                // $this->print_array($blogusers);
            }
        }

        /* Ultimate Author Box Option Metabox Callback Function */

        function uab_meta_callback($post)
        {
            wp_nonce_field(basename(__FILE__), 'uab_nonce');
            $uab_stored_meta = get_post_meta($post->ID, 'uab_option');
            $uab_stored_meta_position = get_post_meta($post->ID, 'uab_meta_position');
            $uab_co_author_display_type = get_post_meta($post->ID, 'uab_co_author_display_type', true);
            $uab_general_settings = get_option('uap_general_settings');

            /* $this->print_array($uab_stored_meta); */
            /* $this->print_array($uab_stored_meta_position); */
            /* $this->print_array($uab_co_author_display_type); */
            ?>
            <p>
                <label><?php _e('Show Author Box in this post', 'ultimate-author-box'); ?></label>
                <select name="uab_meta_option" id="uab-meta-option"
                        value="<?php !empty($uab_stored_meta[0]) ? $uab_stored_meta[0] : 'yes' ?>">
                    <option value="yes" <?php if (!empty($uab_stored_meta[0])) selected($uab_stored_meta[0], 'yes'); ?>><?php _e('Yes', 'ultimate-author-box'); ?></option>
                    <option value="no" <?php if (!empty($uab_stored_meta[0])) selected($uab_stored_meta[0], 'no'); ?>><?php _e('No', 'ultimate-author-box'); ?></option>
                </select>
            </p>
            <p>
                <label><?php _e('Author Box Position', 'ultimate-author-box'); ?></label>
                <select name="uab_meta_position" id="uab-meta-position"
                        value="<?php !empty($uab_stored_meta_position[0]) ? $uab_stored_meta_position[0] : 'default' ?>">
                    <option value="default" <?php if (!empty($uab_stored_meta_position[0])) selected($uab_stored_meta_position[0], 'default'); ?>><?php _e('Default', 'ultimate-author-box'); ?></option>
                    <option value="uab_top" <?php if (!empty($uab_stored_meta_position[0])) selected($uab_stored_meta_position[0], 'uab_top'); ?>><?php _e('Top', 'ultimate-author-box'); ?></option>
                    <option value="uab_bottom" <?php if (!empty($uab_stored_meta_position[0])) selected($uab_stored_meta_position[0], 'uab_bottom'); ?>><?php _e('Bottom', 'ultimate-author-box'); ?></option>
                </select>
            </p>

            <?php
        }

        /* Ultimate Author Box Option Metabox Save */

        function uab_meta_save($post_id)
        {
            /* Checks save status */
            $is_autosave = wp_is_post_autosave($post_id);
            $is_revision = wp_is_post_revision($post_id);
            $is_valid_nonce = (isset($_POST['uab_nonce']) && wp_verify_nonce($_POST['uab_nonce'], basename(__FILE__))) ? 'true' : 'false';
            /* die($_POST['uab_meta_option']); */
            /* Exits script depending on save status */
            if ($is_autosave || $is_revision || !$is_valid_nonce) {
                return;
            }

            $uab_meta_co_author = !empty($_POST['uab_co_authors']) ? maybe_serialize($_POST['uab_co_authors']) : '';
            $uab_co_author_type = !empty($_POST['uab_co_author_display_type']) ? sanitize_text_field($_POST['uab_co_author_display_type']) : '';
            $uab_meta_option = !empty($_POST['uab_meta_option']) ? $_POST['uab_meta_option'] : '';
            $uab_meta_position = !empty($_POST['uab_meta_position']) ? $_POST['uab_meta_position'] : '';

            update_post_meta($post_id, 'uab_option', sanitize_text_field($uab_meta_option));
            update_post_meta($post_id, 'uab_meta_position', sanitize_text_field($uab_meta_position));

            if (!empty($uab_meta_co_author)) {
                update_post_meta($post_id, 'uab_meta_co_author', sanitize_text_field($uab_meta_co_author));
            }
            if (!empty($uab_co_author_type)) {
                update_post_meta($post_id, 'uab_co_author_display_type', $uab_co_author_type);
            }
        }

        function uab_guest_save($post_id)
        {
            if (isset($_POST['uab_guest_nonce_field']) && wp_verify_nonce($_POST['uab_guest_nonce_field'], 'uab_guest_nonce')) {
                $uab_guest_details = $_POST['uab_guest_detail'];
                $temp_array = array();
                foreach ($uab_guest_details as $type => $type_array) {
                    foreach ($type_array as $key => $value) {
                        if (is_array($value)) {
                            foreach ($value as $socialname => $data) {
                                $temp_array[sanitize_text_field($type)][sanitize_text_field($key)]['icon'] = $key;
                                $temp_array[sanitize_text_field($type)][sanitize_text_field($key)]['label'] = $key;
                                $temp_array[sanitize_text_field($type)][sanitize_text_field($key)]['url'] = sanitize_text_field($data);
                            }
                        } else {
                            $temp_array[sanitize_text_field($type)][sanitize_text_field($key)] = sanitize_text_field($value);
                        }
                    }
                }
                // $this->print_array($temp_array);
                // die();
                update_post_meta($post_id, 'uab_guest_details', $temp_array);
            }
        }

        /* Encode Email */

        function encode_email($e)
        {
            $output = '';
            for ($i = 0; $i < strlen($e); $i++) {
                $output .= '&#' . ord($e[$i]) . ';';
            }
            return $output;
        }

        /* Callback funtion to Save values of Profile.php */

        function uab_save_profile_fields($user_id)
        {
            // $this->print_array($_POST);
            // die();

            $uab_frontend_shortcode = isset($_POST['uab_frontend_shortcode']) ? wp_kses_post($_POST['uab_frontend_shortcode']) : '';
            $uab_frontend_shortcode_title = isset($_POST['uab_frontend_shortcode_title']) ? sanitize_text_field($_POST['uab_frontend_shortcode_title']) : '';
            // Added version silver
            $uab_link_target_ranking = isset($_POST['uab_link_influence_avoid']) ? intval(1) : intval(0);

            update_user_meta($user_id, 'uab_frontend_shortcode', $uab_frontend_shortcode);
            update_user_meta($user_id, 'uab_frontend_shortcode_title', $uab_frontend_shortcode_title);
            // Added version silver
            update_user_meta($user_id, 'uab_frontend_target_ranking', $uab_link_target_ranking);

            if (!current_user_can('edit_user', $user_id))
                return false;

            /* Query to save current tab structure setting into usermeta table */
            if (isset($_POST['uab_profile_data'])) {
                foreach ($_POST as $key => $val) {
                    if ($key == 'uab_profile_data') {
                        $$key = $val;
                    }
                }

                /* Sanitizing each form fields for Menu field added */
                $uab_profile_data_temp = array();
                foreach ($uab_profile_data as $key => $val) {
                    $uab_profile_data_temp[$key] = array();
                    foreach ($val as $k => $v) {
                        if (!is_array($v)) {
                            $uab_profile_data_temp[$key][$k] = sanitize_text_field($v);
                        } else {
                            $uab_profile_data_temp[$key][$k] = array_map('sanitize_text_field', $v);
                        }
                    }

                    if ($val['uab_tab_type'] == 'uab_linkedin_profile') {
                        if (isset($val['uab_linkedin_client_id']) && isset($val['uab_linkedin_secret_id'])) {
                            if (!empty($val['uab_linkedin_client_id'])) {
                                $client_id = esc_attr($val['uab_linkedin_client_id']);
                                update_user_meta($user_id, 'uab_linkedin_client_id', $client_id);
                            }
                            if (!empty($val['uab_linkedin_secret_id'])) {
                                $secret_id = esc_attr($val['uab_linkedin_secret_id']);
                                update_user_meta($user_id, 'uab_linkedin_secret_id', $secret_id);
                            }
                        }
                    }

                }

                $uab_profile_data = $uab_profile_data_temp;
                $serialized_uab_profile_data = serialize($uab_profile_data);

                update_user_meta($user_id, 'uab_profile_data', $serialized_uab_profile_data);
            }

            if (isset($_POST['uab_social_icons'])) {
                $uab_social_icons = array();
                foreach ($_POST['uab_social_icons'] as $socialname => $innerarray) {
                    $uab_social_icons[$socialname]['icon'] = $socialname;
                    $uab_social_icons[$socialname]['label'] = $socialname;
                    $uab_social_icons[$socialname]['url'] = $innerarray['url'];
                }

                $serialized_social_icons = serialize($uab_social_icons);
                update_user_meta($user_id, 'uab_social_icons', $serialized_social_icons);
            }

            $allowed_html = array(
                'quotes' => array()
            );


            if (isset($_POST['uab_shortcode'])) {
                $uab_shortcode = array();
                foreach ($_POST['uab_shortcode'] as $key => $value) {
                    $uab_shortcode[$key] = wp_kses($value, $allowed_html);
                }

                update_user_meta($user_id, 'uab_shortcode', $uab_shortcode);
            }
            if (isset($_POST['uab_contact_shortcode'])) {
                $uab_contact_shortcode = array();
                foreach ($_POST['uab_contact_shortcode'] as $key => $value) {
                    $uab_contact_shortcode[$key] = wp_kses($value, $allowed_html);
                }

                update_user_meta($user_id, 'uab_contact_shortcode', $uab_contact_shortcode);
            }

            if (isset($_POST['uab_wysiwyg_content'])) {
                $uab_wysiwyg_content = array();
                foreach ($_POST['uab_wysiwyg_content'] as $key => $value) {
                    $uab_wysiwyg_content[$key] = wp_kses_post($value);
                }

                update_user_meta($user_id, 'uab_wysiwyg_content', $uab_wysiwyg_content);
            }

            if (isset($_POST['uab_company_content'])) {
                $uab_company_content = array();
                foreach ($_POST['uab_company_content'] as $key => $value) {
                    $uab_company_content[$key] = wp_kses_post($value);
                }

                update_user_meta($user_id, 'uab_company_content', $uab_company_content);
            }

            $allowed_html = wp_kses_allowed_html('post');
            $custom_bio_box = !empty($_POST['uab_custom_description']) ? wp_kses($_POST['uab_custom_description'], $allowed_html) : '';
            update_user_meta($user_id, 'uab_custom_description', $custom_bio_box);
        }


        /* Clear the cache @since 1.0 */

        public function clear_caches()
        {

            /* https://wordpress.org/plugins/widget-output-cache/ */
            if (function_exists('menu_output_cache_bump')) {
                menu_output_cache_bump();
            }

            /* https://wordpress.org/plugins/widget-output-cache/ */
            if (function_exists('widget_output_cache_bump')) {
                widget_output_cache_bump();
            }
        }

        /* Ajax callback function to add new Widget @since 1.0.0 */

        public function add_selected_widget()
        {
            check_ajax_referer('uab_form_nonce', 'nonce');
            if (isset($_POST) && $_POST['widget_id'] != '') {
                $widgets_id = sanitize_text_field($_POST['widget_id']);
                $widget_title = sanitize_text_field($_POST['title']);
                $tab_key = sanitize_text_field($_POST['widget_key']);

                $added_widgets = $this->add_widget_selected($widgets_id, $widget_title, $tab_key);
                if ($added_widgets) {
                    if (ob_get_contents())
                        ob_clean();
                    wp_send_json_success($added_widgets);
                } else {
                    if (ob_get_contents())
                        ob_clean();
                    wp_send_json_error(sprintf(__("Failed to add %s to %d", 'ultimate-author-box')));
                }
            }
        }

        /* Adds a widget to WordPress. @since 1.0 @param string $id_base as $widgets_id_value @param string $title as $widget_title */

        public function add_widget_selected($widgets_id, $widget_title, $tab_key)
        {
            require_once(ABSPATH . 'wp-admin/includes/widgets.php');
            $next_id = next_widget_id_number($widgets_id);
            $my_current_widgets = get_option('widget_' . $widgets_id);

            $my_current_widgets[$next_id] = array(
                "widget_columns" => 3
            );

            update_option('widget_' . $widgets_id, $my_current_widgets);
            $widget_id = $this->wpmm_add_widget_to_sidebar($widgets_id, $next_id);

            $return .= '<div class="uab_widget_area ui-sortable" data-title="' . esc_attr($widget_title) . '" data-id="' . $widget_id . '">';
            $return .= '<input type="hidden" name="uab_profile_data[' . $tab_key . '][widget_id]" value="' . $widget_id . '"/>';
            $return .= '<input type="hidden" name="uab_profile_data[' . $tab_key . '][widget_title]" value="' . esc_attr($widget_title) . '"/>';
            $return .= '<div class="widget_area">';
            $return .= '<div class="widget_title">';
            $return .= '<span class="wptitle">' . esc_html($widget_title) . '</div></span>';
            $return .= '<div class="widget_right_action">';
            $return .= '<a class="widget-option widget-action" title="' . esc_attr(__("Edit", 'ultimate-author-box')) . '">';
            $return .= '<i class="far fa-edit" aria-hidden="true"></i></a>';
            $return .= '</div>';
            $return .= '</div>';
            $return .= '<div class="widget_inner"></div>';
            $return .= '</div>';

            return $return;
        }

        private function wpmm_add_widget_to_sidebar($id_base, $next_id)
        {

            $widget_id = $id_base . '-' . $next_id;

            $sidebar_widgets = $this->get_sidebar_widgets();

            $sidebar_widgets[] = $widget_id;

            $this->set_sidebar_widgets($sidebar_widgets);

            do_action("after_widget_add");

            return $widget_id;
        }

        /* Returns an unfiltered array of all widgets in our sidebar @since 1.0 @return array */

        public function get_sidebar_widgets()
        {

            $sidebar_widgets = wp_get_sidebars_widgets();

            if (!isset($sidebar_widgets['uab-tab-widget'])) {

                return false;
            }

            return $sidebar_widgets['uab-tab-widget'];
        }

        /* Sets the sidebar widgets @since 1.0 */

        private function set_sidebar_widgets($widgets)
        {

            $sidebar_widgets = wp_get_sidebars_widgets();

            $sidebar_widgets['uab-tab-widget'] = $widgets;

            wp_set_sidebars_widgets($sidebar_widgets);
        }

        /* Ajax callback function to add new Widget @since 1.0.0 */

        public function ajax_edit_widget_data()
        {
            check_ajax_referer('uab_form_nonce', '_wpnonce');

            $widget_id = sanitize_text_field($_POST['widget_id']);
            /* remove any warnings or output from other plugins which may corrupt the response */
            if (ob_get_contents())
                ob_clean();

            wp_die(trim($this->show_widget_form($widget_id)));
        }

        /* Widget CallBack Form */

        public function show_widget_form($widget_id)
        {
            global $wp_registered_widget_controls;
            $control_widget = $wp_registered_widget_controls[$widget_id];
            $id_base = $this->get_id_base_for_widget_id($widget_id);
            $parts = explode("-", $widget_id);
            $widget_number = absint(end($parts));
            $widget_nonce = wp_create_nonce('uab_save_widget_' . $widget_id);
            ?>

            <div class='uab_widget-content'>
                <form method='post'>
                    <input type="hidden" name="widget_id" class="widget-id" value="<?php esc_attr_e($widget_id); ?>"/>
                    <input type='hidden' name='action' value='uab_save_widget'/>
                    <input type='hidden' name='id_base' value='<?php esc_attr_e($id_base); ?>'/>
                    <input type='hidden' name='_wpnonce' value='<?php esc_attr_e($widget_nonce); ?>'/>
                    <?php
                    if (is_callable($control_widget['callback'])) {
                        call_user_func_array($control_widget['callback'], $control_widget['params']);
                    }
                    ?>

                    <div class='uab-widget-controls'>
                        <a class='uab_delete' href='#delete'><?php _e("Delete", 'ultimate-author-box'); ?></a> |
                        <a class='uab_close' href='#close'><?php _e("Close", 'ultimate-author-box'); ?></a>
                    </div>
                    <?php
                    submit_button(__('Save'), 'button-primary alignright', 'uab_savewidget', false);
                    ?>
                </form>
            </div>
            <?php
        }

        /* Returns the id_base value for a Widget ID @since 1.0 */

        public function get_id_base_for_widget_id($widget_id)
        {
            global $wp_registered_widget_controls;

            if (!isset($wp_registered_widget_controls[$widget_id])) {
                return false;
            }

            $control = $wp_registered_widget_controls[$widget_id];

            $id_base = isset($control['id_base']) ? $control['id_base'] : $control['id'];

            return $id_base;
        }

        /* Delete widget form */

        public function ajax_delete_widget_form()
        {
            check_ajax_referer('uab_form_nonce', 'nonce');

            $widget_id = sanitize_text_field($_POST['widget_id']);

            $deleted_widgets = $this->uab_delete_widgets($widget_id);

            if ($deleted_widgets) {
                wp_send_json_success(sprintf(__("Deleted %s", 'ultimate-author-box'), $widget_id));
            } else {
                wp_send_json_error(sprintf(__("Failed to delete %s", 'ultimate-author-box'), $widget_id));
            }
        }

        /* Deletes a widget from WordPress */

        public function uab_delete_widgets($widget_id)
        {

            $this->remove_widget_from_sidebar($widget_id);
            $this->remove_widget_instance($widget_id);

            do_action("after_widget_delete");

            return true;
        }

        /* Removes a widget from the Ultimate Author Box widget sidebar @since 1.0 @return string The widget that was removed */

        private function remove_widget_from_sidebar($widget_id)
        {

            $widgets = $this->get_sidebar_widgets();

            $new_widgets = array();

            foreach ($widgets as $widget) {

                if ($widget != $widget_id)
                    $new_widgets[] = $widget;
            }

            $this->set_sidebar_widgets($new_widgets);

            return $widget_id;
        }

        /* Removes a widget instance from the database @since 1.0 @param string $widget_id e.g. meta-3 @return bool. True if widget has been deleted. */

        private function remove_widget_instance($widget_id)
        {

            $id_base = $this->get_id_base_for_widget_id($widget_id);
            $parts = explode("-", $widget_id);
            $widget_number = absint(end($parts));

            /* add blank widget */
            $current_widgets = get_option('widget_' . $id_base);

            if (isset($current_widgets[$widget_number])) {

                unset($current_widgets[$widget_number]);

                update_option('widget_' . $id_base, $current_widgets);

                return true;
            }

            return false;
        }

        /* Save a widget @since 1.0 */

        public function ajax_save_widget()
        {
            $widget_id = sanitize_text_field($_POST['widget_id']);
            $id_base = sanitize_text_field($_POST['id_base']);

            check_ajax_referer('uab_save_widget_' . $widget_id);

            $saved_widgets = $this->uab_save_widget($id_base);

            if ($saved_widgets) {
                wp_send_json_success(sprintf(__("Saved %s", 'ultimate-author-box'), $id_base));
            } else {
                wp_send_json_error(sprintf(__("Failed to save %s", 'ultimate-author-box'), $id_base));
            }
        }

        /* Saves a widget. Calls the update callback on the widget. The callback inspects the post values and updates all widget instances which match the base ID. */

        public function uab_save_widget($id_base)
        {
            global $wp_registered_widget_updates;

            $control_widgets = $wp_registered_widget_updates[$id_base];

            if (is_callable($control_widgets['callback'])) {

                call_user_func_array($control_widgets['callback'], $control_widgets['params']);

                do_action("after_widget_save");

                return true;
            }

            return false;
        }

        /* Returns the HTML for a single widget instance */

        static public function show_widget($id)
        {
            global $wp_registered_widgets;

            $lists_arr_parameters = array_merge(
                array(array_merge(array('widgetid' => $id, 'widgetname' => $wp_registered_widgets[$id]['name']))), (array)$wp_registered_widgets[$id]['params']
            );

            $lists_arr_parameters[0]['before_title'] = apply_filters("before_widget_title", '<h4 class="uab-mega-block-title">', $wp_registered_widgets[$id]);
            $lists_arr_parameters[0]['after_title'] = apply_filters("after_widget_title", '</h4>', $wp_registered_widgets[$id]);
            $lists_arr_parameters[0]['before_widget'] = apply_filters("before_widget", "", $wp_registered_widgets[$id]);
            $lists_arr_parameters[0]['after_widget'] = apply_filters("after_widget", "", $wp_registered_widgets[$id]);

            $callback = $wp_registered_widgets[$id]['callback'];

            if (is_callable($callback)) {
                ob_start();
                call_user_func_array($callback, $lists_arr_parameters);
                return ob_get_clean();
            }
        }

        public function get_token_process_url($r = false)
        {
            $query = array();
            $rules = get_option('rewrite_rules');

            if (is_array($rules) and !empty($rules)) {
                // we have rewrite rules activated
                $url = home_url('/oauth/linkedin/');
            } else {
                $url = home_url();
                $query['oauth'] = 'linkedin';
            }

            if ($r) {
                // cleanup the url, remove args specified below
                $removable_query_args = array(
                    'message', 'settings-updated', 'saved',
                    'update', 'updated', 'activated',
                    'activate', 'deactivate', 'locked',
                    'deleted', 'trashed', 'untrashed',
                    'enabled', 'disabled', 'skipped',
                    'spammed', 'unspammed',
                );
                $removable_query_args = apply_filters('removable_query_args', $removable_query_args);

                $r = remove_query_arg($removable_query_args, $r);
                $query['r'] = $r;
            }

            if (!empty($query)) {
                $url .= '?' . http_build_query($query);
            }

            return $url;
        }

        public function get_state_token()
        {
            $time = intval(time() / 172800);
            $salt = (defined('NONCE_SALT')) ? NONCE_SALT : SECRET_KEY;
            return sha1('linkedin-oauth' . $salt . $time);
        }

    }

    /* Creating AP_Contact_Form class object */
    $ultimate_author_box_obj = new Ultimate_Author_Box();
}