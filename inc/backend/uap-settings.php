<?php
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

$uab_general_settings = get_option( 'uap_general_settings' );
$uab_general_settings = empty( $uab_general_settings ) ? array() : $uab_general_settings;
$uab_twitter_status = get_option('uab_twitter_status');
//$this->print_array($uab_general_settings);

?>
<!-- Save settings and Restore Success Message -->
<?php if ( isset( $_GET['message'] ) ) {
	?>
	<div class="notice notice-success is-dismissible"><p><?php esc_html_e( 'Settings saved successfully', 'ultimate-author-box' ); ?></p></div>
	<?php }
	if ( isset( $_GET['restore-message'] ) ) {?>
	<div class="notice notice-success is-dismissible"><p><?php esc_html_e( 'Settings restored to default successfully', 'ultimate-author-box' ); ?></p></div>
	<?php }
	if ( isset( $_GET['cache-message'] ) ) {?>
	<div class="notice notice-success is-dismissible"><p><?php esc_html_e( 'Cache Cleared successfully', 'ultimate-author-box' ); ?></p></div>
	<?php }

	?>
	<div class="uab-settings-header-wrapper-main">
		<div class="uab-settings-header-wrapper-main-wrap uab-clearfix">
			<div class="uab-settings-header-title">
				<div class="uab-title-menu"><?php _e('Ultimate Author Box');?></div>
				<div class="uab-version-wrap">
					<span>Version <?php _e(UAB_VERSION);?></span>
				</div>
			</div>
			<div class="uab-header-social-link">
				<p class="uab-follow-us">Follow us for new updates</p>
				<iframe src="//www.facebook.com/plugins/follow?href=https%3A%2F%2Fwww.facebook.com%2FAccessPressThemes&amp;layout=button&amp;show_faces=true&amp;colorscheme=light&amp;width=450&amp;height=80" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:px; height:20px;" allowTransparency="true"></iframe>
				<iframe id="twitter-widget-0" scrolling="no" frameborder="0" allowtransparency="true" class="twitter-follow-button twitter-follow-button-rendered" style="position: static; visibility: visible; width:px; height: 20px;" title="Twitter Follow Button" src="http://platform.twitter.com/widgets/follow_button.c4fd2bd4aa9a68a5c8431a3d60ef56ae.en.html#dnt=false&amp;id=twitter-widget-0&amp;lang=en&amp;screen_name=apthemes&amp;show_count=false&amp;show_screen_name=true&amp;size=m&amp;time=1484717853708" data-screen-name="accesspressthemes"></iframe>
			</div>
		</div>
	</div><!--End of uab-settings-header-wrapper-main-->
	<div class="uab-setting-page-wrapper">
		<form method="post" action="<?php echo admin_url( 'admin-post.php' ); ?>">
			<input type="hidden" name="action" value="uab_settings_save_action"/>
			<div class="uab-tabs-wrap">
				<ul class="uab-tabs">
					<li class="tab-link uab-general-setting-tab current" data-tab="tab-1"><?php _e( 'General Settings', 'ultimate-author-box' ); ?></li>
					<li class="tab-link uab-permission-setting-tab" data-tab="tab-4"><?php _e( 'Permission Settings', 'ultimate-author-box' ); ?></li>
					<li class="tab-link uab-twitter-setting-tab" data-tab="tab-2"><?php _e( 'Twitter Feeds Settings', 'ultimate-author-box' ); ?></li>
					<li class="tab-link uab-layout-setting-tab" data-tab="tab-3"><?php _e( 'Layout Settings', 'ultimate-author-box' ); ?></li>
					<li class="tab-link uab-custom-setting-tab" data-tab="tab-5"><?php _e( 'Custom Settings', 'ultimate-author-box' ); ?></li>
				</ul>
			</div>
			<div id="tab-5" class="uab-tab-content">
				<div class="uab-settings-header-wrapper">
					<h3><?php _e('Custom Theme Settings');?></h3>
				</div><!--End of uab-settings-header-wrapper-->
				<div class="uab-custom-settings-content-wrapper">
					<div class="uab-fields uab-content-wrapper">
						<div class="uab-label-info-wrap">
							<label for="uab-tab-type-selection"><?php _e( 'Select Template', 'ultimate-author-box' ); ?></label>
						</div>
						<select class="uab-select-input1 uab-custom-template"  name="uab_custom_template">
							<option  value="uab-template-1" <?php if ( $uab_general_settings['uab_custom_template']=='uab-template-1' ) echo 'selected'; ?>><?php _e( 'Template 1', 'ultimate-author-box' ); ?></option>
							<option  value="uab-template-2" <?php if ( $uab_general_settings['uab_custom_template']=='uab-template-2' ) echo 'selected'; ?>><?php _e( 'Template 2', 'ultimate-author-box' ); ?></option>
							<option  value="uab-template-3" <?php if ( $uab_general_settings['uab_custom_template']=='uab-template-3' ) echo 'selected'; ?>><?php _e( 'Template 3', 'ultimate-author-box' ); ?></option>
							<option  value="uab-template-4" <?php if ( $uab_general_settings['uab_custom_template']=='uab-template-4' ) echo 'selected'; ?>><?php _e( 'Template 4', 'ultimate-author-box' ); ?></option>
							<option  value="uab-template-5" <?php if ( $uab_general_settings['uab_custom_template']=='uab-template-5' ) echo 'selected'; ?>><?php _e( 'Template 5', 'ultimate-author-box' ); ?></option>
							<option  value="uab-template-6" <?php if ( $uab_general_settings['uab_custom_template']=='uab-template-6' ) echo 'selected'; ?>><?php _e( 'Template 6', 'ultimate-author-box' ); ?></option>
							<option  value="uab-template-7" <?php if ( $uab_general_settings['uab_custom_template']=='uab-template-7' ) echo 'selected'; ?>><?php _e( 'Template 7', 'ultimate-author-box' ); ?></option>
							<option  value="uab-template-8" <?php if ( $uab_general_settings['uab_custom_template']=='uab-template-8' ) echo 'selected'; ?>><?php _e( 'Template 8', 'ultimate-author-box' ); ?></option>
							<option  value="uab-template-9" <?php if ( $uab_general_settings['uab_custom_template']=='uab-template-9' ) echo 'selected'; ?>><?php _e( 'Template 9', 'ultimate-author-box' ); ?></option>
							<option  value="uab-template-10" <?php if ( $uab_general_settings['uab_custom_template']=='uab-template-10' ) echo 'selected'; ?>><?php _e( 'Template 10', 'ultimate-author-box' ); ?></option>
							<option  value="uab-template-11" <?php if ( $uab_general_settings['uab_custom_template']=='uab-template-11' ) echo 'selected'; ?>><?php _e( 'Template 11', 'ultimate-author-box' ); ?></option>
							<option  value="uab-template-12" <?php if ( $uab_general_settings['uab_custom_template']=='uab-template-12' ) echo 'selected'; ?>><?php _e( 'Template 12', 'ultimate-author-box' ); ?></option>
							<option  value="uab-template-13" <?php if ( $uab_general_settings['uab_custom_template']=='uab-template-13' ) echo 'selected'; ?>><?php _e( 'Template 13', 'ultimate-author-box' ); ?></option>
							<option  value="uab-template-14" <?php if ( $uab_general_settings['uab_custom_template']=='uab-template-14' ) echo 'selected'; ?>><?php _e( 'Template 14', 'ultimate-author-box' ); ?></option>
							<option  value="uab-template-15" <?php if ( $uab_general_settings['uab_custom_template']=='uab-template-15' ) echo 'selected'; ?>><?php _e( 'Template 15', 'ultimate-author-box' ); ?></option>
							<option  value="uab-template-16" <?php if ( $uab_general_settings['uab_custom_template']=='uab-template-16' ) echo 'selected'; ?>><?php _e( 'Template 16', 'ultimate-author-box' ); ?></option>
							<option  value="uab-template-17" <?php if ( $uab_general_settings['uab_custom_template']=='uab-template-17' ) echo 'selected'; ?>><?php _e( 'Template 17', 'ultimate-author-box' ); ?></option>
							<option  value="uab-template-18" <?php if ( $uab_general_settings['uab_custom_template']=='uab-template-18' ) echo 'selected'; ?>><?php _e( 'Template 18', 'ultimate-author-box' ); ?></option>
							<option  value="uab-template-19" <?php if ( $uab_general_settings['uab_custom_template']=='uab-template-19' ) echo 'selected'; ?>><?php _e( 'Template 19', 'ultimate-author-box' ); ?></option>
						</select>
					</div>
					<div class="uab-template-image-preview">
						<?php 
						$uab_image_source='uab-template-1.PNG';
						switch($uab_general_settings['uab_custom_template']){
							case 'uab-template-1':
							$uab_image_source='uab-template-1.PNG';
							break;
							case 'uab-template-2':
							$uab_image_source='uab-template-2.PNG';
							break;
							case 'uab-template-3':
							$uab_image_source='uab-template-3.PNG';
							break;
							case 'uab-template-4':
							$uab_image_source='uab-template-4.PNG';
							break;
							case 'uab-template-5':
							$uab_image_source='uab-template-5.PNG';
							break;
							case 'uab-template-6':
							$uab_image_source='uab-template-6.PNG';
							break;
							case 'uab-template-7':
							$uab_image_source='uab-template-7.PNG';
							break;
							case 'uab-template-8':
							$uab_image_source='uab-template-8.PNG';
							break;
							case 'uab-template-9':
							$uab_image_source='uab-template-9.PNG';
							break;
							case 'uab-template-10':
							$uab_image_source='uab-template-10.PNG';
							break;
							case 'uab-template-11':
							$uab_image_source='uab-template-11.PNG';
							break;
							case 'uab-template-12':
							$uab_image_source='uab-template-12.PNG';
							break;
							case 'uab-template-13':
							$uab_image_source='uab-template-13.PNG';
							break;
							case 'uab-template-14':
							$uab_image_source='uab-template-14.PNG';
							break;
							case 'uab-template-15':
							$uab_image_source='uab-template-15.PNG';
							break;
							case 'uab-template-16':
							$uab_image_source='uab-template-16.PNG';
							break;
							case 'uab-template-17':
							$uab_image_source='uab-template-17.PNG';
							break;
							case 'uab-template-18':
							$uab_image_source='uab-template-18.PNG';
							break;
							case 'uab-template-19':
							$uab_image_source='uab-template-19.PNG';
							break;
							default:
							$uab_image_source='uab-template-1.PNG';
						}							
						?>
						<img src="<?php esc_attr_e(UAB_IMG_DIR); ?>/uab-template-screenshorts/<?php esc_attr_e($uab_image_source); ?>" width="100%"/>
					</div>
					<?php //echo $uab_general_settings['uab_custom_template'];?>
					<div class="uab-fields uab-content-wrapper uab-primary-color uab-custom-template-option">
						<div class="uab-label-info-wrap">
							<label><?php _e('Primary Color','ultimate-author-box');?></label>
						</div>
						<div>
							<input type="text" name="uab_primary_color" data-alpha="true" value="<?php esc_attr_e(isset($uab_general_settings['uab_primary_color'])?$uab_general_settings['uab_primary_color']:'');?>" class="uab-color-picker uab-primary-color-picker" >
						</div>
					</div>
					<div class="uab-fields uab-content-wrapper uab-secondary-color uab-custom-template-option" 

					<?php if(!(
						$uab_general_settings['uab_custom_template']=='uab-template-3'||
						$uab_general_settings['uab_custom_template']=='uab-template-5'||
						$uab_general_settings['uab_custom_template']=='uab-template-7'||
						$uab_general_settings['uab_custom_template']=='uab-template-11'||
						$uab_general_settings['uab_custom_template']=='uab-template-13'||
						$uab_general_settings['uab_custom_template']=='uab-template-14'||
						$uab_general_settings['uab_custom_template']=='uab-template-16'||
						$uab_general_settings['uab_custom_template']=='uab-template-18'
						)){
						echo 'style="display:none;"'; 
					} 
					elseif(!isset($uab_general_settings['uab_custom_template'])) 
					{
						echo 'style="display:none;"';	
					}
					?>>
					<div class="uab-label-info-wrap">
						<label><?php _e('Secondary Color','ultimate-author-box');?></label>
					</div>
					<div>
						<input type="text" name="uab_secondary_color" data-alpha="true" value="<?php esc_attr_e( $uab_general_settings['uab_secondary_color']);?>" class="uab-color-picker uab-secondary-color-picker" >
					</div>
				</div>
				<div class="uab-fields uab-content-wrapper uab-tertiary-color uab-custom-template-option" <?php if(!($uab_general_settings['uab_custom_template']=='uab-template-7'||$uab_general_settings['uab_custom_template']=='uab-template-16')) echo 'style="display:none;"'; elseif(!isset($uab_general_settings['uab_custom_template'])) echo 'style="display:none;"';?>>
					<div class="uab-label-info-wrap">
						<label><?php _e('Tertiary Color','ultimate-author-box');?></label>
					</div>
					<div>
						<input type="text" name="uab_tertiary_color" data-alpha="true" value="<?php esc_attr_e( $uab_general_settings['uab_tertiary_color']);?>" class="uab-color-picker uab-tertiary-color-picker" >
					</div>
				</div>
				<div class="uab-fields uab-content-wrapper uab-background uab-custom-template-option" <?php if(!($uab_general_settings['uab_custom_template']=='uab-template-13'||$uab_general_settings['uab_custom_template']=='uab-template-15'||$uab_general_settings['uab_custom_template']=='uab-template-18'||$uab_general_settings['uab_custom_template']=='uab-template-19')) echo 'style="display:none;"'; elseif(!isset($uab_general_settings['uab_custom_template'])) echo 'style="display:none;"';?>>
					<div class="uab-label-info-wrap">
						<label for="custom_image_background"><?php _e( 'Upload Custom Image', 'ultimate-author-box' ); ?></label>
					</div>
					<div class="background-image-preview uab-custom-template-option">
						<input type="text" class="uab_upload_background_url" name="custom_image_background" value="<?php esc_attr_e( $uab_general_settings['custom_image_background']);?>"/>
						<input type="button" class="custom_image_background_button input-controller image_button button-secondary"  value="<?php esc_attr_e('Upload Image','ultimate-author-box');?>" size="25"/> 
						<div class="current-background-image" ><h4><?php _e( 'Image Preview:', 'ultimate-author-box' ); ?></h4><img src="<?php esc_attr_e( $uab_general_settings['custom_image_background']);?>" style="height:180px; width:180px;"/></div>
					</div>
				</div>

			</div>
		</div>
		<div id="tab-1" class="uab-tab-content current">
			<div class="uab-settings-header-wrapper">
				<h3><?php _e('General Settings');?></h3>
			</div><!--End of uab-settings-header-wrapper-->
			<div class="uab-general-settings-content-wrapper">
				<div class="uap-disable-authorbox uab-content-wrapper">
					<div class="uab-label-info-wrap">
						<label><?php _e( 'Disable Ultimate Author Box', 'ultimate-author-box' ); ?></label>
						<span class="uab-info"><?php _e( 'Disable Author Box in frontend entirely', 'ultimate-author-box' ); ?></span>
					</div>	
					<div class="uab-slide-checkbox-wrapper">
						<div class="uab-slide-checkbox-wrapper-inner">
							<div class="uab-slide-checkbox">  
								<input type="checkbox" id="uab-disable-uab" name="uab_disable_uab" <?php if(!empty($uab_general_settings['uab_disable_uab'])) echo 'checked';?>>
								<label for="uab-disable-uab"></label>
							</div>
						</div>
					</div>
				</div>
				<div class="uab-postion-wrapper ">
					<span class="uab-inner-title"><?php _e( 'Choose place to show Author Box', 'ultimate-author-box' ); ?></span>

					<div class="uap-disable-authorbox uab-content-wrapper">
						<div class="uab-label-info-wrap">
							<label><?php _e( 'Show in Posts', 'ultimate-author-box' ); ?></label>
							<span class="uab-info"><?php _e( 'Check to show Ultimate Author Box in Posts', 'ultimate-author-box' ); ?></span>
						</div>
						<div class="uab-slide-checkbox-wrapper">
							<div class="uab-slide-checkbox-wrapper-inner">
								<div class="uab-slide-checkbox">  
									<input type="checkbox" id="uab-posts" name="uab_posts" <?php if ( !empty($uab_general_settings['uab_posts'] )) echo 'checked'; ?> value="post">
									<label for="uab-posts"></label>
								</div>

							</div>
						</div>
					</div>
					<div class="uap-disable-authorbox uab-content-wrapper" >
						<div class="uab-label-info-wrap">
							<label><?php _e( 'Show in Pages', 'ultimate-author-box' ); ?></label>
							<span class="uab-info"><?php _e( 'Check to show Ultimate Author Box in Pages', 'ultimate-author-box' ); ?></span>
						</div>

						<div class="uab-slide-checkbox-wrapper">
							<div class="uab-slide-checkbox-wrapper-inner">
								<div class="uab-slide-checkbox">  
									<input type="checkbox" id="uab-pages" name="uab_pages" <?php if ( !empty($uab_general_settings['uab_pages'] )) echo 'checked'; ?> value="page">
									<label for="uab-pages"></label>
								</div>

							</div>
						</div>
					</div>
					<?php

					$args = array(
						'public'   => true,
						'_builtin' => false,
					);

			    			$output = 'names'; // names or objects, note names is the default
			    			$operator = 'and'; // 'and' or 'or'

			    			$post_types = get_post_types( $args, $output, $operator ); 
			    			?>
			    			<div class="uap-disable-authorbox uab-content-wrapper" <?php if(empty($post_types)) echo 'style="display:none;"';?>>
			    				<div class="uab-label-info-wrap">
			    					<label><?php _e( 'Show in Custom Post Types', 'ultimate-author-box' ); ?></label>
			    					<span class="uab-info"><?php _e( 'Check to show Ultimate Author Box in Custom Post Type', 'ultimate-author-box' ); ?></span>
			    				</div>
			    				<div class="uab-slide-checkbox-wrapper">
			    					<div class="uab-slide-checkbox-wrapper-inner">
			    						<div class="uab-slide-checkbox">  
			    							<input type="checkbox" id="uab-custom-posts" name="uab_custom_post" <?php if ( !empty($uab_general_settings['uab_custom_post'] )) echo 'checked'; ?>>
			    							<label for="uab-custom-posts"></label>
			    						</div>

			    					</div>
			    				</div>
			    			</div><!-- End of uap-disable-authorbox -->
			    			<div class="uab-custom-post-type-list-wrapper">
			    				<?php
			    				foreach ( $post_types  as $key=>$post_type ) {
			    					?>
			    					<div class="uab-single-checkbox-wrap">
			    						<div class="uab-single-checkbox">
			    							<input type="checkbox" id="uab-post-type-<?php esc_attr_e($key);?>" name="uab_custom_post_type_list[]" value="<?php esc_attr_e($post_type);?>" <?php 
			    							foreach( $uab_general_settings['uab_custom_post_type_list'] as $innerKey => $type){
			    								if($key==$type) echo 'checked';
			    							}
			    							?>>
			    							<label for="uab-post-type-<?php esc_attr_e($key);?>"></label>
			    						</div>
			    						<span class="uab-info"><?php esc_html_e($post_type);?></span>
			    					</div>
			    					<?php
			    				}
			    				?>
			    			</div><!-- End of uab-custom-post-type-list-wrapper -->
			    		</div><!-- End of uab-postion-wrapper -->	
			    		<div class="select-tab-wrapper uab-content-wrapper">
			    			<div class="uab-label-info-wrap">
			    				<label for="uab-tab-type-selection"><?php _e( 'Show Author Box at', 'ultimate-author-box' ); ?></label>
			    			</div>
			    			<div>	
			    				<select class="uab-select-input"   name="uab_box_position">
			    					<option  value="uab_top" <?php if ( $uab_general_settings['uab_box_position'] == 'uab_top' ) echo 'selected'; ?>><?php _e( 'Top of Posts', 'ultimate-author-box' ); ?></option>
			    					<option  value="uab_bottom" <?php if ( $uab_general_settings['uab_box_position'] == 'uab_bottom' ) echo 'selected'; ?>><?php _e( 'Bottom of Posts', 'ultimate-author-box' ); ?></option>
			    					<option  value="uab_none" <?php if ( $uab_general_settings['uab_box_position'] == 'uab_none' ) echo 'selected'; ?>><?php _e( 'None', 'ultimate-author-box' ); ?></option>
			    				</select>
			    			</div>
			    		</div>
			    		<div class="uap-hide-empty-authorbox uab-content-wrapper">
			    			<div class="uab-label-info-wrap">
			    				<label><?php _e( 'Hide Author Box if Author Biographical Info is empty', 'ultimate-author-box' ); ?></label>
			    				<span class="uab-info"><?php _e( 'Check to hide Author Box if Author Biographical Info is empty', 'ultimate-author-box' ); ?></span>
			    			</div>

			    			<div class="uab-slide-checkbox-wrapper">
			    				<div class="uab-slide-checkbox-wrapper-inner">
			    					<div class="uab-slide-checkbox">  
			    						<input type="checkbox" id="uab-empty-bio" name="uab_empty_bio" <?php if(!empty($uab_general_settings['uab_empty_bio'])) echo 'checked'; ?>>
			    						<label for="uab-empty-bio"></label>
			    					</div>

			    				</div>
			    			</div>
			    		</div>
			    		<div class="uap-show-default-authorbox uab-content-wrapper">
			    			<div class="uab-label-info-wrap">
			    				<label><?php _e( 'Show Default Biographical Info if empty', 'ultimate-author-box' ); ?></label>
			    				<span class="uab-info"><?php _e( 'Check to Show Default Biographical Info if empty', 'ultimate-author-box' ); ?></span>
			    			</div>

			    			<div class="uab-slide-checkbox-wrapper">
			    				<div class="uab-slide-checkbox-wrapper-inner">
			    					<div class="uab-slide-checkbox">  
			    						<input type="checkbox" id="uab-default-bio" name="uab_default_bio" <?php if ( !empty($uab_general_settings['uab_default_bio']) ) echo 'checked'; ?>>
			    						<label for="uab-default-bio"></label>
			    					</div>

			    				</div>
			    			</div>
			    		</div>
			    		<div class="uap-default-authorbox-message uab-content-wrapper">
			    			<div class="uab-label-info-wrap">
			    				<label><?php _e( 'Default Author Box Message', 'ultimate-author-box' ); ?></label>
			    			</div>
			    			<div>
			    				<textarea name="uab_default_message"><?php echo (isset( $uab_general_settings['uab_default_message'] )) ? esc_attr( $uab_general_settings['uab_default_message'] ) : ''; ?></textarea>
			    			</div> 
			    		</div>
<!-- 			    	<div class="uap-show-authorbox-small-device uab-content-wrapper">
			    		<div class="uab-label-info-wrap">
			    			<label><?php _e( 'Show Author Box in Small Screen Device', 'ultimate-author-box' ); ?></label>
			    			<span class="uab-info"><?php _e( 'Check to Show Author Box in Small Screen Device', 'ultimate-author-box' ); ?></span>
			    		</div>
			    		<div class="uab-slide-checkbox-wrapper">
			    			<div class="uab-slide-checkbox-wrapper-inner">
			    				<div class="uab-slide-checkbox">  
			    					<input type="checkbox" id="uab-small-device" name="uab_small_device" <?php if ( !empty($uab_general_settings['uab_small_device'] )) echo 'checked'; ?>>
			    					<label for="uab-small-device"></label>
			    				</div>

			    			</div>
			    		</div>
			    	</div> -->
			    	<div class="select-tab-wrapper uab-content-wrapper">
			    		<div class="uab-label-info-wrap">
			    			<label for="uab-link-target-option"><?php _e( 'Frontend link target options ', 'ultimate-author-box' ); ?></label>
			    		</div>
			    		<div>
			    			<select class="uab-select-input" id="uab-link-target-option" name="uab_link_target_option">
			    				<option  value="_blank" <?php if ( $uab_general_settings['uab_link_target_option']=='_blank' ) echo 'selected'; ?>><?php _e( 'New Page', 'ultimate-author-box' ); ?></option>
			    				<option  value="_self" <?php if ( $uab_general_settings['uab_link_target_option']=='_self' ) echo 'selected'; ?>><?php _e( 'Same Page', 'ultimate-author-box' ); ?></option>
			    			</select>
			    		</div>
			    	</div>
			    	<!--@since version 1.0.5 -->
			    	<div class="uab-fontend-popup uab-content-wrapper">
			    		<div class="uab-label-info-wrap">
			    			<label><?php _e( 'Show Author Info Pop-up', 'ultimate-author-box' ); ?></label>
			    			<span class="uab-info"><?php _e( 'Choose whether to show Author Info Pop-up on Author Box image click', 'ultimate-author-box' ); ?></span>
			    		</div>	
			    		<div class="uab-slide-checkbox-wrapper">
			    			<div class="uab-slide-checkbox-wrapper-inner">
			    				<div class="uab-slide-checkbox">  
			    					<input type="checkbox" id="uab-show-popup" name="uab_show_popup" <?php if(!empty($uab_general_settings['uab_show_popup'])) echo 'checked';?>>
			    					<label for="uab-show-popup"></label>
			    				</div>
			    			</div>
			    		</div>
			    	</div>

			    	<!--@since version 1.0.5 -->
			    	<div class="uab-fontend-popup uab-content-wrapper">
			    		<div class="uab-label-info-wrap">
			    			<label><?php _e( 'Disable Author Email', 'ultimate-author-box' ); ?></label>
			    			<span class="uab-info"><?php _e( 'Choose whether to show Author Email Info on Author Box', 'ultimate-author-box' ); ?></span>
			    		</div>	
			    		<div class="uab-slide-checkbox-wrapper">
			    			<div class="uab-slide-checkbox-wrapper-inner">
			    				<div class="uab-slide-checkbox">  
			    					<input type="checkbox" id="uab-disable-email" name="uab_disable_email" <?php if(!empty($uab_general_settings['uab_disable_email'])) echo 'checked';?>>
			    					<label for="uab-disable-email"></label>
			    				</div>
			    			</div>
			    		</div>
			    	</div>

			    	<div class="uab-fontend-popup uab-content-wrapper">
			    		<div class="uab-label-info-wrap">
			    			<label><?php _e( 'Disable Co Authors', 'ultimate-author-box' ); ?></label>
			    			<span class="uab-info"><?php _e( 'Choose whether to show co authors or not in the Author Box', 'ultimate-author-box' ); ?></span>
			    		</div>
			    		<div class="uab-slide-checkbox-wrapper">
			    			<div class="uab-slide-checkbox-wrapper-inner">
			    				<div class="uab-slide-checkbox">  
			    					<input type="checkbox" id="uab-disable-co-author" name="uab_disable_coauthor" <?php if(!empty($uab_general_settings['uab_disable_coauthor'])) echo 'checked';?>>
			    					<label for="uab-disable-co-author"></label>
			    				</div>
			    			</div>
			    		</div>
			    	</div>
			    	<div class="uab-fontend-popup uab-content-wrapper">
							<div class="uab-label-info-wrap">
								<label><?php _e('Co Author Header Text','ultimate-author-box');?></label>
							</div>
							<div>
								<input type="text" name="uab_coauthor_header_text" value="<?php echo (isset( $uab_general_settings['uab_coauthor_header_text'] )) ? esc_attr( $uab_general_settings['uab_coauthor_header_text'] ) : ''; ?>" placeholder="<?php esc_attr_e('Default: Co Authors','ultimate-author-box') ?>">
							</div>
			    	</div>

			    	<!--@since version 1.0.8 -->
			    	<div class="uab-fontend-popup uab-content-wrapper">
			    		<div class="uab-label-info-wrap">
			    			<label><?php _e( 'Disable Template Customization for Individual Users', 'ultimate-author-box' ); ?></label>
			    			<span class="uab-info"><?php _e( 'This will disable the Template Settings in the User Profile Editor Page' ); ?></span>
			    		</div>	
			    		<div class="uab-slide-checkbox-wrapper">
			    			<div class="uab-slide-checkbox-wrapper-inner">
			    				<div class="uab-slide-checkbox">  
			    					<input type="checkbox" id="uab-disable-customizer" name="uab_disable_customizer" <?php if(!empty($uab_general_settings['uab_disable_customizer'])) echo 'checked';?>>
			    					<label for="uab-disable-customizer"></label>
			    				</div>
			    			</div>
			    		</div>
			    	</div>
			    </div><!--End of uab-general-settings-content-wrapper-->
			</div><!--End of tab 1-->
			<div id="tab-4" class="uab-tab-content">
				<div class="uab-template-settings-wrapper">
					<div class="uab-customize-header-wrapper uab-settings-header-wrapper">
						<h3><?php _e('Permission Settings');?></h3>
					</div><!--End of uab-settings-header-wrapper-->
					<div class="uab-permission-settings uab-template-settings">
						<span class="uab-backend-description"><?php _e('Manage user permission for author box user fields access.');?></span>
						<div class="uab-content-wrapper">
							<div class="uab-label-info-wrap">
								<label><?php _e( 'Choose user roles', 'ultimate-author-box' ); ?></label>
								<span class="uab-info"><?php _e( 'Select user roles for user fields access', 'ultimate-author-box' ); ?></span>
							</div>
							<div class="uab-custom-post-type-list-wrapper">
								<?php
								$user_role_list = $this->get_editable_roles();
								foreach($user_role_list as $key => $value){
									?>
									<div class="uab-single-checkbox-wrap">
										<div class="uab-single-checkbox">
											<input type="checkbox" id="uab-post-type-<?php esc_attr_e($key);?>" name="uab_user_roles[]" value="<?php esc_attr_e($key);?>" <?php if(in_array($key,$uab_general_settings['uab_user_roles'])) echo 'checked';?>>
											<label for="uab-post-type-<?php esc_attr_e($key);?>"></label>
										</div>
										<span class="uab-info"><?php esc_html_e($user_role_list[$key]['name']);?></span>
									</div>
									<?php
								}
								?>
							</div><!-- End of uab-custom-post-type-list-wrapper -->
						</div><!-- End of uap-disable-authorbox -->
					</div>
				</div>
			</div>
			<div id="tab-2" class="uab-tab-content">
				<div class="uap-twitter-feeds-settings ">
					<div class="uab-settings-header-wrapper">
						<h3><?php _e('Twitter Feeds Settings');?></h3>
					</div><!--End of uab-settings-header-wrapper-->
					<div class="uab-fields uab-content-wrapper">
						<div class="uab-label-info-wrap">
							<label><?php _e('Twitter Enable','ultimate-author-box');?></label>
						</div>
						<div class="uab-slide-checkbox">
							<input type="checkbox" id="uab-twitter-status" class="uab-bulb-switch" name="uab_twitter_status" value="1" <?php checked((isset($uab_twitter_status)?boolval($uab_twitter_status):boolval(0)),1) ?>>
							<label for="uab-twitter-status"></label>
						</div>
					</div>
					<div class="uab-light-bulb" style="display: none;">
						<div class="uab-fields uab-content-wrapper">
							<div class="uab-label-info-wrap">
								<label><?php _e('Consumer Key (API Key)','ultimate-author-box');?></label>
							</div>
							<div>
								<input type="text" name="uab_twitter_api_key" value="<?php echo (isset( $uab_general_settings['uab_twitter_api_key'] )) ? esc_attr( $uab_general_settings['uab_twitter_api_key'] ) : ''; ?>">
							</div>
						</div>
						<div class="uab-fields uab-content-wrapper">
							<div class="uab-label-info-wrap">
								<label><?php _e('Consumer Secret (API Secret)','ultimate-author-box');?></label>
							</div>
							<div>
								<input type="text" name="uab_twitter_api_secret" value="<?php echo (isset( $uab_general_settings['uab_twitter_api_secret'] )) ? esc_attr( $uab_general_settings['uab_twitter_api_secret'] ) : ''; ?>">
							</div>
						</div>
						<div class="uab-fields uab-content-wrapper">
							<div class="uab-label-info-wrap">
								<label><?php _e('Access Token','ultimate-author-box');?></label>
							</div>
							<div>
								<input type="text" name="uab_twitter_access_token" value="<?php echo (isset( $uab_general_settings['uab_twitter_access_token'] )) ? esc_attr( $uab_general_settings['uab_twitter_access_token'] ) : ''; ?>">
							</div>
						</div>
						<div class="uab-fields uab-content-wrapper">
							<div class="uab-label-info-wrap">
								<label><?php _e('Access Token Secret','ultimate-author-box');?></label>
							</div>
							<div>
								<input type="text" name="uab_twitter_token_secret" value="<?php echo (isset( $uab_general_settings['uab_twitter_token_secret'] )) ? esc_attr( $uab_general_settings['uab_twitter_token_secret'] ) : ''; ?>">
							</div>
						</div>
						<div class="uab-fields uab-content-wrapper">
							<div class="uab-label-info-wrap">
								<label><?php _e('Cache Period','ultimate-author-box');?></label>
							</div>
							<div>
								<input type="number" name="uab_twitter_cache_period" value="<?php echo (isset( $uab_general_settings['uab_twitter_cache_period'] )) ? esc_attr( $uab_general_settings['uab_twitter_cache_period'] ) : ''; ?>">
							</div>
						</div>
						<div class="uab-fields uab-content-wrapper">
							<div class="uab-label-info-wrap">
								<label><?php _e('Delete Cache','ultimate-author-box');?></label>
							</div>
							<div>
								<a href="<?php echo admin_url() . 'admin-post.php?action=uab_delete_cache'; ?>" onclick="return confirm('<?php _e('Are you sure you want to delete cache?', 'accesspress-twitter-feed') ?>');"><input type="button" value="<?php _e('Delete Cache', 'accesspress-twitter-feed'); ?>" class="button button-secondary"/></a>
							</div>
						</div>
					</div>

					
				</div><!--End of uab-Twitter-feeds-settings-->
			</div>

			<div class="uab_admin-general-bttn">
				<?php 
				wp_nonce_field( 'uab_admin_option_update' ); 
				wp_nonce_field('uab_action_nonce', 'uab_nonce_field');
				$restore_nonce = wp_create_nonce('uab-restore-nonce');
				?>
				<input type="submit" class="button-primary" value="<?php _e( 'Save Changes', 'ultimate-author-box' ); ?>" name="uab_settings_save_button"/>
				<a href="<?php echo admin_url() . 'admin-post.php?action=uab_restore_settings&_wpnonce=' . $restore_nonce; ?>" onclick="return confirm('<?php _e('Are you sure you want to restore default settings?', 'ultimate-author-box') ?>');"><input type="button" class="button-secondary" value="<?php _e('Restore Default Settings', 'ultimate-author-box'); ?>" class="button-primary"/></a>
				<a href="<?php echo admin_url() . 'profile.php';?>"><input type="button" class="button-secondary" value="<?php _e('Go to Profile Settings', 'ultimate-author-box'); ?>"></a>
			</div>
		</div>

		
	</form>
</div><!--End of uab-setting-page-wrapper-->



