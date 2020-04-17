<?php defined( 'ABSPATH' ) or die( 'No script kiddies please!' );?>

<div class="uab-author-profile-pic">
	<?php 
	if(isset($uab_profile_data[0]['uab_image_shape'])){
		switch($uab_profile_data[0]['uab_image_shape']){
			case 'uab_is_circle':
			$uap_profile_image_shape = 'uap-profile-image-circle';
			break;
			default:
			$uap_profile_image_shape = 'uap-profile-image-square';
		}
	}else{
		$uap_profile_image_shape = 'uap-profile-image-square';
	}

		?>
	<div class="uap-profile-image <?php esc_attr_e($uap_profile_image_shape); ?>">
		<?php
		$uab_select_image_option = isset($uab_profile_data[0]['uab_image_select'])?$uab_profile_data[0]['uab_image_select']:'uab_gravatar';
		switch($uab_select_image_option){
			case 'uab_upload_image':
			if(!empty($uab_profile_data[0]['uab_upload_image_url'])){
				?>
				<!--Custom Image-->
				<img src="<?php esc_attr_e($uab_profile_data[0]['uab_upload_image_url']);?>" width="200">
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