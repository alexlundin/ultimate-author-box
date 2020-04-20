<?php defined( 'ABSPATH' ) or die( 'No script kiddies please!' );?>

<div class="uab-author-profile-pic">

	<div class="uap-profile-image">
		<?php
		$uab_select_image_option = isset($uab_profile_data[0]['uab_image_select'])?$uab_profile_data[0]['uab_image_select']:'uab_gravatar';
		switch($uab_select_image_option){
			case 'uab_upload_image':
			if(!empty($uab_profile_data[0]['uab_upload_image_url'])){
				?>
				<!--Custom Image-->
				<img src="<?php esc_attr_e($uab_profile_data[0]['uab_upload_image_url']);?>" width="200" alt="<?php esc_html_e(the_author_meta('display_name', $user_id)); ?>">
				<?php
			}
			else{
				_e(get_avatar($author_id,200));
			}
			break;
			default:
			_e(get_avatar($author_id,200));
		}
		?>
	</div>
</div>